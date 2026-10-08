<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BeritaService
{
    private string $baseUrl = 'https://desa-api.muaraenimkab.go.id/api/v1/public/article';

    private int $villageId = 21;

    private function request(string $url, array $headers = []): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
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

    public function list(int $page = 1, int $pageSize = 12): LengthAwarePaginator
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $cacheKey = "berita_list_{$page}_{$pageSize}";

        $payload = Cache::remember($cacheKey, 300, function () use ($page, $pageSize) {
            $url = $this->baseUrl . '?' . http_build_query([
                'page' => $page,
                'page_size' => $pageSize,
                'with' => 'category',
            ]);

            return $this->request($url, ['x-village-id: ' . $this->villageId]);
        });

        $items = collect($payload['data'] ?? [])
            ->map(fn(array $item) => $this->normalizeListItem($item))
            ->values();

        $hasNext = !empty($payload['meta']['next_page_url']);
        $total = (($page - 1) * $pageSize) + $items->count() + ($hasNext ? $pageSize : 0);

        return new LengthAwarePaginator(
            $items,
            $total,
            $pageSize,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    public function latest(int $limit = 3): array
    {
        return $this->list(1, $limit)->items();
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
