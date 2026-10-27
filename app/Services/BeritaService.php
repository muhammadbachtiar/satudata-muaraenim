<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BeritaService
{
    private string $baseUrl = 'https://desa-api.muaraenimkab.go.id/api/v1/public/article';

    private int $villageId = 21;

    /** Default page size matching publikasi grid layout (3 columns x 4 rows). */
    public const PAGE_SIZE = 12;

    /** TTL (seconds) for entries cached on-demand by a visitor request. */
    private const TTL = 300;

    /** TTL (seconds) for entries written by the background warmer. */
    public const WARM_TTL = 900;

    /** TTL (seconds) for page-to-cursor mappings (1 hour). */
    private const CURSOR_TTL = 3600;

    /** Maximum pages allowed to walk sequentially to prevent execution timeouts. */
    private const MAX_PAGE_WALK = 30;

    private function request(string $url, array $headers = []): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HTTPHEADER => array_merge(['Accept: application/json'], $headers),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'SatuData-MuaraEnim-Berita/1.0',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode >= 400 || !empty($error)) {
            Log::warning('Berita API request failed', [
                'url' => $url,
                'http_code' => $httpCode,
                'error' => $error ?: 'No curl error',
            ]);

            return ['success' => false, 'data' => null, 'meta' => []];
        }

        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            Log::warning('Berita API returned non-JSON response', [
                'url' => $url,
                'response' => mb_substr((string) $response, 0, 500),
            ]);

            return ['success' => false, 'data' => null, 'meta' => []];
        }

        return $decoded;
    }

    /**
     * Get paginated articles for a specific page number.
     * Keeps standard ?page=1,2,3... URLs while internally resolving the upstream cursor.
     */
    public function list(int $page = 1, int $pageSize = self::PAGE_SIZE): PaginatorContract
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $cacheKey = "berita_list_page_{$page}_{$pageSize}";

        $payload = Cache::remember($cacheKey, self::TTL, fn () => $this->fetchPagePayload($page, $pageSize));

        $items = collect($payload['data'] ?? [])
            ->map(fn(array $item) => $this->normalizeListItem($item))
            ->values();

        $hasNext = !empty($payload['meta']['next_page_url']) || !empty($payload['hasNext']);

        $query = request()->query();
        unset($query['cursor']);

        $paginator = new Paginator(
            $items,
            $pageSize,
            $page,
            [
                'path' => request()->url(),
                'query' => $query,
            ]
        );
        $paginator->hasMorePagesWhen($hasNext);

        return $paginator;
    }

    public function latest(int $limit = 3): array
    {
        return $this->list(1, $limit)->items();
    }

    /**
     * Fetch raw payload directly from upstream API using optional cursor.
     */
    private function fetchRaw(int $pageSize, ?string $cursor = null): array
    {
        $params = [
            'page_size' => $pageSize,
            'with' => 'category',
        ];

        if (!empty($cursor)) {
            $params['cursor'] = $cursor;
        }

        $url = $this->baseUrl . '?' . http_build_query($params);

        return $this->request($url, ['x-village-id: ' . $this->villageId]);
    }

    /**
     * Extract cursor string from upstream API page url.
     */
    private function extractCursor(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $parts = parse_url($url);
        if (empty($parts['query'])) {
            return null;
        }

        parse_str($parts['query'], $query);

        return !empty($query['cursor']) ? (string) $query['cursor'] : null;
    }

    /**
     * Fetch one page payload, resolving cursors if page > 1.
     */
    public function fetchPagePayload(int $page, int $pageSize): array
    {
        if ($page === 1) {
            $payload = $this->fetchRaw($pageSize, null);
            if (!empty($payload['success']) && is_array($payload['data'] ?? null)) {
                $nextCursor = $this->extractCursor($payload['meta']['next_page_url'] ?? null);
                if ($nextCursor) {
                    Cache::put("berita_cursor_{$pageSize}_2", $nextCursor, self::CURSOR_TTL);
                }
            }

            return $payload;
        }

        $cursor = Cache::get("berita_cursor_{$pageSize}_{$page}");
        if (!$cursor) {
            $cursor = $this->resolveCursorForPage($page, $pageSize);
        }

        if ($cursor === null) {
            return [
                'success' => true,
                'data' => [],
                'meta' => ['next_page_url' => null, 'prev_page_url' => null],
                'hasNext' => false,
            ];
        }

        $payload = $this->fetchRaw($pageSize, $cursor);
        if (!empty($payload['success']) && is_array($payload['data'] ?? null)) {
            $nextCursor = $this->extractCursor($payload['meta']['next_page_url'] ?? null);
            if ($nextCursor) {
                Cache::put("berita_cursor_{$pageSize}_" . ($page + 1), $nextCursor, self::CURSOR_TTL);
            }
        }

        return $payload;
    }

    /**
     * Walk through cursors page-by-page from nearest cached point to target page.
     */
    private function resolveCursorForPage(int $targetPage, int $pageSize): ?string
    {
        if ($targetPage > self::MAX_PAGE_WALK) {
            $targetPage = self::MAX_PAGE_WALK;
        }

        $startPage = 1;
        $currentCursor = null;

        for ($p = $targetPage - 1; $p >= 2; $p--) {
            $cachedCursor = Cache::get("berita_cursor_{$pageSize}_{$p}");
            if ($cachedCursor) {
                $startPage = $p;
                $currentCursor = $cachedCursor;
                break;
            }
        }

        for ($p = $startPage; $p < $targetPage; $p++) {
            $payloadKey = "berita_list_page_{$p}_{$pageSize}";
            $cursorForStep = $currentCursor;
            $stepPayload = Cache::remember($payloadKey, self::TTL, function () use ($pageSize, $cursorForStep) {
                return $this->fetchRaw($pageSize, $cursorForStep);
            });

            $nextCursor = $this->extractCursor($stepPayload['meta']['next_page_url'] ?? null);
            if (!$nextCursor) {
                return null;
            }

            Cache::put("berita_cursor_{$pageSize}_" . ($p + 1), $nextCursor, self::CURSOR_TTL);
            $currentCursor = $nextCursor;
        }

        return $currentCursor;
    }

    /**
     * Refresh the article lists in the background (called by the queue worker).
     * Warms home/overview lists and pages 1 to 6 for the publikasi berita page.
     *
     * @return array{lists:int,failures:int}
     */
    public function warm(): array
    {
        $report = ['lists' => 0, 'failures' => 0];

        // 1. Warm home (3) & publikasi overview (6)
        foreach ([3, 6] as $limit) {
            $payload = $this->fetchRaw($limit, null);
            if (!is_array($payload['data'] ?? null)) {
                $report['failures']++;
                continue;
            }
            Cache::put("berita_list_page_1_{$limit}", $payload, self::WARM_TTL);
            $report['lists']++;
        }

        // 2. Warm publikasi?type=berita pages 1 through 6
        $cursor = null;
        for ($p = 1; $p <= 6; $p++) {
            $payload = $this->fetchRaw(self::PAGE_SIZE, $cursor);
            if (!is_array($payload['data'] ?? null)) {
                $report['failures']++;
                break;
            }

            Cache::put("berita_list_page_{$p}_" . self::PAGE_SIZE, $payload, self::WARM_TTL);
            $report['lists']++;

            $cursor = $this->extractCursor($payload['meta']['next_page_url'] ?? null);
            if ($cursor) {
                Cache::put("berita_cursor_" . self::PAGE_SIZE . "_" . ($p + 1), $cursor, self::CURSOR_TTL);
            } else {
                break;
            }
        }

        return $report;
    }

    public function detail(string $slug): ?array
    {
        $cacheKey = 'berita_detail_' . md5($slug);

        return Cache::remember($cacheKey, 300, function () use ($slug) {
            $url = rtrim($this->baseUrl, '/') . '/' . rawurlencode($slug);
            $payload = $this->request($url, ['x-village-id: ' . $this->villageId]);

            if (empty($payload['success']) || empty($payload['data']) || !is_array($payload['data'])) {
                return null;
            }

            return $this->normalizeDetail($payload['data']);
        });
    }

    private function normalizeListItem(array $item): array
    {
        return [
            'source' => 'api',
            'type' => 'berita',
            'title' => $item['title'] ?? 'Tanpa Judul',
            'slug' => $item['slug'] ?? Str::slug($item['title'] ?? ''),
            'excerpt' => $item['description'] ?? '',
            'body' => $item['description'] ?? '',
            'image_url' => $item['thumbnail'] ?? null,
            'published_at' => $this->parseDate($item['published_at'] ?? null),
            'category' => $item['category']['name'] ?? 'BERITA',
        ];
    }

    private function normalizeDetail(array $item): array
    {
        return [
            'source' => 'api',
            'type' => 'berita',
            'title' => $item['title'] ?? 'Tanpa Judul',
            'slug' => $item['slug'] ?? Str::slug($item['title'] ?? ''),
            'excerpt' => $item['description'] ?? '',
            'body' => $item['content'] ?? $item['description'] ?? '',
            'image_url' => $item['thumbnail'] ?? null,
            'published_at' => $this->parseDate($item['published_at'] ?? null),
            'views' => $item['views'] ?? 0,
        ];
    }

    private function parseDate(?string $date): ?Carbon
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (\Throwable) {
            return null;
        }
    }
}
