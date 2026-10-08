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

    /** Abort a warm run after this many consecutive CKAN failures (CKAN is probably down). */
    private const WARM_MAX_CONSECUTIVE_FAILURES = 5;

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
     * Get all dataset slugs via package_list.
     * (package_search is broken on this CKAN instance — Solr returns 0)
     */
    public function packageList(): array
    {
        return Cache::remember('ckan_package_list', self::TTL, fn () => $this->fetchPackageList());
    }

    /**
     * Fetch all dataset slugs from CKAN without touching the cache.
     */
    private function fetchPackageList(): array
    {
        $response = $this->request('api/3/action/package_list');

        return (array) ($response['result'] ?? []);
    }

    /**
     * Get multiple datasets with full details.
     * Uses package_list + package_show since package_search is broken.
     */
    public function getDatasets(int $limit = 20, int $offset = 0): array
    {
        $allSlugs = $this->packageList();
        $total = count($allSlugs);

        // Slice for pagination
        $slugs = array_slice($allSlugs, $offset, $limit);

        $datasets = [];
        foreach ($slugs as $slug) {
            $ds = $this->packageShow($slug);
            if ($ds) {
                $datasets[] = $ds;
            }
        }

        return [
            'count'   => $total,
            'results' => $datasets,
        ];
    }

    /**
     * Search datasets by keyword.
     * Since package_search Solr is broken, we filter package_list + package_show locally.
     */
    public function searchDatasets(string $query, int $limit = 12, int $offset = 0): array
    {
        $allSlugs = $this->packageList();

        // If searching with a real query (not wildcard), filter slugs that match
        if ($query !== '*' && $query !== '') {
            $q = strtolower($query);
            $filtered = [];
            foreach ($allSlugs as $slug) {
                // First check if slug contains keyword
                if (str_contains(strtolower($slug), str_replace(' ', '-', $q)) || str_contains(strtolower($slug), $q)) {
                    $filtered[] = $slug;
                    continue;
                }
                // Otherwise fetch and check title/notes
                $ds = $this->packageShow($slug);
                if ($ds && (
                    str_contains(strtolower($ds['title'] ?? ''), $q) ||
                    str_contains(strtolower($ds['notes'] ?? ''), $q)
                )) {
                    $filtered[] = $slug;
                }
            }
            $allSlugs = $filtered;
        }

        $total = count($allSlugs);
        $slugs = array_slice($allSlugs, $offset, $limit);

        $datasets = [];
        foreach ($slugs as $slug) {
            $ds = $this->packageShow($slug);
            if ($ds) {
                $datasets[] = $ds;
            }
        }

        return [
            'count'   => $total,
            'results' => $datasets,
        ];
    }

    /**
     * Get a single package/dataset by ID or name.
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
     */
    public function resourceShow(string $id): ?array
    {
        $response = $this->request('api/3/action/resource_show', ['id' => $id]);

        if (empty($response['success']) || $response['result'] === null) {
            return null;
        }

        return $response['result'];
    }

    /**
     * Get views for a resource.
     */
    public function resourceViewList(string $id): array
    {
        $response = $this->request('api/3/action/resource_view_list', ['id' => $id]);

        return $response['result'] ?? [];
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
     * Get aggregated site statistics.
     */
    public function siteStats(): array
    {
        return Cache::remember('ckan_site_stats', 300, function () {
            $organizations = $this->organizationList();
            $packageSlugs = $this->packageList();

            return [
                'organization_count' => count($organizations),
                'dataset_count'      => count($packageSlugs),
            ];
        });
    }

    /**
     * Refresh the CKAN cache in the background (called by the queue worker).
     *
     * Fetches organizations, the dataset list and dataset details, then writes
     * them with WARM_TTL. Existing cache entries are only overwritten on a
     * successful fetch, so a CKAN outage never replaces good data with nothing.
     * Visitor requests then read from the cache instead of calling CKAN.
     *
     * @return array{organizations:int,datasets:int,failures:int,aborted:bool}
     */
    public function warm(int $maxDatasets = 500): array
    {
        $report = ['organizations' => 0, 'datasets' => 0, 'failures' => 0, 'aborted' => false];

        $organizations = $this->fetchOrganizations();
        if ($organizations !== []) {
            Cache::put('ckan_org_list', $organizations, self::WARM_TTL);
            $report['organizations'] = count($organizations);
        } else {
            $report['failures']++;
        }

        $slugs = $this->fetchPackageList();
        if ($slugs === []) {
            $report['failures']++;

            return $report;
        }
        Cache::put('ckan_package_list', $slugs, self::WARM_TTL);

        $consecutiveFailures = 0;
        foreach (array_slice($slugs, 0, max(0, $maxDatasets)) as $slug) {
            $slug = (string) $slug;
            $dataset = $this->fetchPackage($slug);

            if ($dataset === null) {
                $report['failures']++;

                if (++$consecutiveFailures >= self::WARM_MAX_CONSECUTIVE_FAILURES) {
                    $report['aborted'] = true;
                    Log::warning('CKAN warm aborted after consecutive failures', ['failures' => $consecutiveFailures]);
                    break;
                }

                continue;
            }

            $consecutiveFailures = 0;
            Cache::put($this->packageCacheKey($slug), $dataset, self::WARM_TTL);
            $report['datasets']++;
        }

        if ($organizations !== []) {
            Cache::put('ckan_site_stats', [
                'organization_count' => count($organizations),
                'dataset_count'      => count($slugs),
            ], self::WARM_TTL);
        }

        return $report;
    }
}
