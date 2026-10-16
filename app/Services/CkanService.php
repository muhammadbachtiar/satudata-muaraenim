<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CkanService
{
    private string $baseUrl = 'https://opendata.muaraenimkab.go.id/';

    /** TTL (seconds) for entries cached on-demand by a visitor request. */
    private const TTL = 300;

    /**
     * TTL (seconds) for entries written by the background warmer. It is longer
     * than the warm interval (5 min) so a failed run does not leave the cache
     * empty: visitors keep getting the last good data instead of waiting on CKAN.
     */
    public const WARM_TTL = 900;

    /**
     * TTL (seconds) of the empty result cached after a failed package_search, so
     * an unreachable CKAN is not called again by every visitor.
     */
    private const FAILURE_TTL = 30;

    /** Datasets per page on /dataset (CkanController::search). */
    public const PAGE_SIZE = 12;

    /** Datasets shown on the home page ("Dataset Terbaru"). */
    public const HOME_LIMIT = 6;

    /** Rows per package_search call made by the warmer (~2.7 KB per dataset). */
    private const WARM_BATCH_SIZE = 500;

    /**
     * Listing pages written by the warmer: [rows per page, max pages (null = all)].
     * They must match the searchDatasets('*', rows, start) calls of the pages.
     */
    private const WARM_LISTINGS = [
        [self::HOME_LIMIT, 1],
        [self::PAGE_SIZE, null],
    ];

    /** Sort order of a listing without keyword: newest first. */
    private const BROWSE_SORT = 'metadata_modified desc';

    /** Sort order of a keyword search: most relevant first, newest on ties. */
    private const SEARCH_SORT = 'score desc, metadata_modified desc';

    /** Maximum number of keywords taken from the visitor's query. */
    private const MAX_SEARCH_TERMS = 8;

    private const EMPTY_RESULT = ['count' => 0, 'results' => []];

    /**
     * Execute a curl request to the CKAN API.
     */
    private function request(string $endpoint, array $params = []): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'SatuData-MuaraEnim-Portal/1.0',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode >= 400 || !empty($error)) {
            Log::warning('CKAN API request failed', [
                'url'       => $url,
                'http_code' => $httpCode,
                'error'     => $error ?: 'No curl error',
            ]);
            return ['success' => false, 'result' => null];
        }

        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            Log::warning('CKAN API returned non-JSON response', [
                'url'      => $url,
                'response' => mb_substr((string) $response, 0, 500),
            ]);
            return ['success' => false, 'result' => null];
        }

        return $decoded;
    }

    /**
     * Get all organizations with all fields.
     */
    public function organizationList(): array
    {
        return Cache::remember('ckan_org_list', self::TTL, fn () => $this->fetchOrganizations());
    }

    /**
     * Fetch organizations from CKAN without touching the cache.
     * Returns an empty array when CKAN is unreachable.
     */
    private function fetchOrganizations(): array
    {
        $response = $this->request('api/3/action/organization_list', [
            'all_fields' => 'true',
            'include_dataset_count' => 'true',
            'limit' => 1000,
        ]);

        $result = $response['result'] ?? [];

        if (empty($result) && empty($response['success'])) {
            Log::warning('CKAN organizationList returned empty');
        }

        return $this->mergeOrganizationsFromDatasetFacets($result);
    }

    /**
     * CKAN organization_list can miss organizations that still own datasets.
     * Merge package_search organization facets so /instansi shows every dataset owner.
     */
    private function mergeOrganizationsFromDatasetFacets(array $organizations): array
    {
        $response = $this->request('api/3/action/package_search', [
            'rows' => 0,
            'facet.field' => '["organization"]',
            'facet.limit' => 1000,
        ]);

        $facets = $response['result']['facets']['organization'] ?? [];

        if (empty($facets)) {
            return $organizations;
        }

        $byName = [];
        foreach ($organizations as $index => $organization) {
            $name = $organization['name'] ?? null;

            if (!$name) {
                continue;
            }

            $byName[$name] = $index;
        }

        foreach ($facets as $name => $packageCount) {
            if (isset($byName[$name])) {
                $organizations[$byName[$name]]['package_count'] = $packageCount;
                continue;
            }

            $organizations[] = [
                'name' => $name,
                'title' => $this->titleFromSlug($name),
                'display_name' => $this->titleFromSlug($name),
                'description' => '',
                'package_count' => $packageCount,
            ];
        }

        usort($organizations, fn($a, $b) => strcasecmp(
            $a['display_name'] ?? $a['title'] ?? $a['name'] ?? '',
            $b['display_name'] ?? $b['title'] ?? $b['name'] ?? ''
        ));

        return $organizations;
    }

    private function titleFromSlug(string $slug): string
    {
        $title = str_replace('-', ' ', $slug);
        $title = preg_replace('/\bpu\b/i', 'PU', $title) ?? $title;

        return mb_convert_case($title, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Latest datasets (home page), newest first.
     *
     * @return array{count:int,results:array}
     */
    public function getDatasets(int $limit = self::HOME_LIMIT, int $offset = 0): array
    {
        return $this->searchDatasets('*', $limit, $offset);
    }

    /**
     * List or search datasets with CKAN package_search (one request per page).
     *
     * An empty query or '*' lists every dataset, newest first. Otherwise every
     * keyword must match as a whole word or as a word prefix ("sekol" finds
     * "sekolah"), with title matches ranked first.
     *
     * Listing pages are written by the background warmer; keyword searches are
     * cached on-demand for TTL seconds.
     *
     * @return array{count:int,results:array}
     */
    public function searchDatasets(string $query, int $limit = self::PAGE_SIZE, int $offset = 0): array
    {
        $limit = max(1, $limit);
        $offset = max(0, $offset);
        $terms = $this->searchTerms($query);

        if ($terms === []) {
            return $this->rememberSearch(
                $this->browseCacheKey($limit, $offset),
                fn () => $this->fetchSearch('*:*', self::BROWSE_SORT, $limit, $offset)
            );
        }

        return $this->rememberSearch(
            'ckan_search_' . md5(implode(' ', $terms)) . "_{$limit}_{$offset}",
            fn () => $this->fetchSearch($this->solrQuery($terms), self::SEARCH_SORT, $limit, $offset)
        );
    }

    /**
     * Return a cached search page, or fetch and cache it. The datasets of a
     * freshly fetched page also prime the package_show cache (without replacing
     * entries written by the warmer), so opening one of them needs no request.
     *
     * @return array{count:int,results:array}
     */
    private function rememberSearch(string $key, callable $fetch): array
    {
        $cached = Cache::get($key);

        if (is_array($cached)) {
            return $cached;
        }

        $result = $fetch();

        if ($result === null) {
            Cache::put($key, self::EMPTY_RESULT, self::FAILURE_TTL);

            return self::EMPTY_RESULT;
        }

        Cache::put($key, $result, self::TTL);
        $this->cachePackages($result['results'], self::TTL, overwrite: false);

        return $result;
    }

    /**
     * Call package_search without touching the cache. Returns null on failure.
     *
     * @return array{count:int,results:array}|null
     */
    private function fetchSearch(string $solrQuery, string $sort, int $rows, int $start): ?array
    {
        $response = $this->request('api/3/action/package_search', [
            'q'     => $solrQuery,
            'sort'  => $sort,
            'rows'  => $rows,
            'start' => $start,
            'facet' => 'false',
        ]);

        $results = $response['result']['results'] ?? null;

        if (empty($response['success']) || !is_array($results)) {
            return null;
        }

        return [
            'count'   => (int) ($response['result']['count'] ?? 0),
            'results' => array_values($results),
        ];
    }

    /**
     * Split the visitor's query into lowercase words. Everything except letters
     * and digits is dropped, so Solr syntax (":", "(", quotes...) never reaches
     * CKAN, which answers 409 on an invalid query.
     *
     * @return list<string>
     */
    private function searchTerms(string $query): array
    {
        $clean = preg_replace('/[^\p{L}\p{N}]+/u', ' ', mb_strtolower($query, 'UTF-8')) ?? '';

        $terms = array_map(
            fn (string $term) => mb_substr($term, 0, 50, 'UTF-8'),
            array_filter(explode(' ', $clean), fn (string $term) => $term !== '')
        );

        return array_slice(array_values(array_unique($terms)), 0, self::MAX_SEARCH_TERMS);
    }

    /**
     * Build the Solr query: every word must match, as a whole word or a prefix.
     * Title matches score highest, then whole-word matches. For ["sekolah", "dasar"]:
     * title:(sekolah* AND dasar*)^4 OR text:(sekolah AND dasar)^2 OR text:(sekolah* AND dasar*)
     *
     * @param list<string> $terms
     */
    private function solrQuery(array $terms): string
    {
        $exact = implode(' AND ', $terms);
        $prefix = implode(' AND ', array_map(fn (string $term) => $term . '*', $terms));

        return "title:({$prefix})^4 OR text:({$exact})^2 OR text:({$prefix})";
    }

    private function browseCacheKey(int $rows, int $start): string
    {
        return "ckan_browse_{$rows}_{$start}";
    }

    /**
     * Get a single package/dataset by ID or name.
     *
     * Normally served from the cache filled by the warmer or by a search page
     * (package_search returns the same dict); only a cache miss calls package_show.
     */
    public function packageShow(string $id): ?array
    {
        return Cache::remember($this->packageCacheKey($id), self::TTL, fn () => $this->fetchPackage($id));
    }

    private function packageCacheKey(string $id): string
    {
        return 'ckan_pkg_' . md5($id);
    }

    /**
     * Cache dataset dicts under both their name and their id: dataset pages are
     * opened by name, resource pages look the dataset up by package_id.
     *
     * @return int Number of datasets cached
     */
    private function cachePackages(array $datasets, int $ttl, bool $overwrite): int
    {
        $count = 0;

        foreach ($datasets as $dataset) {
            if (!is_array($dataset)) {
                continue;
            }

            $ids = array_unique(array_filter([$dataset['name'] ?? null, $dataset['id'] ?? null]));

            foreach ($ids as $id) {
                $key = $this->packageCacheKey((string) $id);
                $overwrite ? Cache::put($key, $dataset, $ttl) : Cache::add($key, $dataset, $ttl);
            }

            $count++;
        }

        return $count;
    }

    /**
     * Fetch one dataset from CKAN without touching the cache.
     */
    private function fetchPackage(string $id): ?array
    {
        $response = $this->request('api/3/action/package_show', ['id' => $id]);

        if (empty($response['success']) || ($response['result'] ?? null) === null) {
            return null;
        }

        return $response['result'];
    }

    /**
     * Get a single resource by ID.
     *
     * Cached for TTL seconds on-demand (no background warmer writes this key).
     * resource_show is a cheap single-row lookup, so a 5-minute TTL is fine.
     */
    public function resourceShow(string $id): ?array
    {
        return Cache::remember('ckan_resource_' . md5($id), self::TTL, function () use ($id) {
            $response = $this->request('api/3/action/resource_show', ['id' => $id]);

            if (empty($response['success']) || $response['result'] === null) {
                return null;
            }

            return $response['result'];
        });
    }

    /**
     * Get views for a resource.
     *
     * Rarely changes, so TTL matches the standard on-demand TTL.
     */
    public function resourceViewList(string $id): array
    {
        return Cache::remember('ckan_resource_views_' . md5($id), self::TTL, function () use ($id) {
            $response = $this->request('api/3/action/resource_view_list', ['id' => $id]);

            return $response['result'] ?? [];
        });
    }

    /**
     * Search the DataStore for a resource (tabular preview data).
     * Returns ['fields' => [...], 'records' => [...], 'total' => int] or null if not datastore-active.
     */
    public function datastoreSearch(string $resourceId, int $limit = 50, int $offset = 0): ?array
    {
        $cacheKey = 'ckan_ds_' . md5($resourceId) . "_{$limit}_{$offset}";

        return Cache::remember($cacheKey, 300, function () use ($resourceId, $limit, $offset) {
            $response = $this->request('api/3/action/datastore_search', [
                'resource_id' => $resourceId,
                'limit'       => $limit,
                'offset'      => $offset,
            ]);

            if (empty($response['success']) || $response['result'] === null) {
                return null;
            }

            return [
                'fields'  => $response['result']['fields'] ?? [],
                'records' => $response['result']['records'] ?? [],
                'total'   => $response['result']['total'] ?? 0,
            ];
        });
    }

    /**
     * Get aggregated site statistics. Both values come from caches the warmer
     * keeps filled (organization list and the first /dataset page).
     */
    public function siteStats(): array
    {
        return [
            'organization_count' => count($this->organizationList()),
            'dataset_count'      => $this->searchDatasets('*', self::PAGE_SIZE, 0)['count'],
        ];
    }

    /**
     * Refresh the CKAN cache in the background (called by the queue worker).
     *
     * 1. organization_list (+ package_search facets) -> ckan_org_list
     * 2. package_search q=*:* newest first, WARM_BATCH_SIZE rows per call
     *    (620 datasets = 2 requests), then from that single result set:
     *    - every listing page of WARM_LISTINGS (home + /dataset without keyword)
     *    - the package_show cache of every dataset, by name and by id
     *
     * Entries are written with WARM_TTL and only after a successful fetch, so a
     * CKAN outage never replaces good data with nothing.
     *
     * @return array{organizations:int,datasets:int,pages:int,failures:int,aborted:bool}
     */
    public function warm(int $maxDatasets = 5000): array
    {
        $report = ['organizations' => 0, 'datasets' => 0, 'pages' => 0, 'failures' => 0, 'aborted' => false];

        $organizations = $this->fetchOrganizations();
        if ($organizations !== []) {
            Cache::put('ckan_org_list', $organizations, self::WARM_TTL);
            $report['organizations'] = count($organizations);
        } else {
            $report['failures']++;
        }

        $maxDatasets = max(0, $maxDatasets);
        $datasets = [];
        $total = null;

        while (count($datasets) < min($total ?? PHP_INT_MAX, $maxDatasets)) {
            $start = count($datasets);
            $batch = $this->fetchSearch('*:*', self::BROWSE_SORT, min(self::WARM_BATCH_SIZE, $maxDatasets - $start), $start);

            if ($batch === null) {
                $report['failures']++;
                $report['aborted'] = true;
                Log::warning('CKAN warm aborted: package_search failed', ['start' => $start]);
                break;
            }

            $total = $batch['count'];

            if ($batch['results'] === []) {
                break;
            }

            array_push($datasets, ...$batch['results']);
        }

        // The first package_search call failed: keep the existing cache untouched.
        if ($total === null) {
            return $report;
        }

        $report['datasets'] = $this->cachePackages($datasets, self::WARM_TTL, overwrite: true);

        foreach (self::WARM_LISTINGS as [$rows, $maxPages]) {
            $report['pages'] += $this->cacheListingPages($datasets, $total, $rows, $maxPages);
        }

        return $report;
    }

    /**
     * Slice the warmed datasets into the pages searchDatasets('*', $rows, $start)
     * reads. Stops at the first page whose rows were not all fetched (aborted
     * run or maxDatasets reached); such pages are fetched on-demand instead.
     *
     * @return int Number of pages cached
     */
    private function cacheListingPages(array $datasets, int $total, int $rows, ?int $maxPages): int
    {
        $fetched = count($datasets);
        $pages = 0;

        for ($start = 0; $start === 0 || $start < $total; $start += $rows) {
            if ($maxPages !== null && $pages >= $maxPages) {
                break;
            }

            if ($fetched - $start < min($rows, $total - $start)) {
                break;
            }

            Cache::put($this->browseCacheKey($rows, $start), [
                'count'   => $total,
                'results' => array_slice($datasets, $start, $rows),
            ], self::WARM_TTL);

            $pages++;
        }

        return $pages;
    }
}
