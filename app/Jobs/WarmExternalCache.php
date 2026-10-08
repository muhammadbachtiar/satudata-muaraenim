<?php

namespace App\Jobs;

use App\Services\BeritaService;
use App\Services\CkanService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Refreshes the cached responses of the external CKAN and Berita APIs.
 *
 * Dispatched every 5 minutes by the scheduler (routes/console.php) and executed
 * by the queue worker, so the slow HTTP calls happen in the background instead
 * of inside a visitor's request.
 */
class WarmExternalCache implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /** Must stay below the worker --timeout and the redis retry_after. */
    public int $timeout = 600;

    /** The scheduler dispatches a fresh job every 5 minutes, so retrying is pointless. */
    public int $tries = 1;

    /** Do not queue a second warm-up while one is running (> $timeout). */
    public int $uniqueFor = 660;

    public function __construct(public int $maxDatasets = 500)
    {
    }

    public function handle(CkanService $ckan, BeritaService $berita): void
    {
        $startedAt = microtime(true);

        $ckanReport = $ckan->warm($this->maxDatasets);
        $beritaReport = $berita->warm();

        Log::info('External cache warmed', [
            'ckan'     => $ckanReport,
            'berita'   => $beritaReport,
            'duration' => round(microtime(true) - $startedAt, 2) . 's',
        ]);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('External cache warm-up failed', ['error' => $exception->getMessage()]);
    }
}
