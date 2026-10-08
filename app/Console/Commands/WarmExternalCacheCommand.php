<?php

namespace App\Console\Commands;

use App\Jobs\WarmExternalCache;
use App\Services\BeritaService;
use App\Services\CkanService;
use Illuminate\Console\Command;

class WarmExternalCacheCommand extends Command
{
    protected $signature = 'cache:warm-external
        {--max-datasets=500 : Maximum number of CKAN datasets whose details are cached}
        {--queue : Dispatch the job to the queue instead of running it in this process}';

    protected $description = 'Refresh the cached CKAN and Berita API responses';

    public function handle(CkanService $ckan, BeritaService $berita): int
    {
        $maxDatasets = max(0, (int) $this->option('max-datasets'));

        if ($this->option('queue')) {
            WarmExternalCache::dispatch($maxDatasets);
            $this->info('Warm-up job dispatched to the queue.');

            return self::SUCCESS;
        }

        $this->info('Warming CKAN cache...');
        $ckanReport = $ckan->warm($maxDatasets);

        $this->info('Warming Berita cache...');
        $beritaReport = $berita->warm();

        $this->table(
            ['Source', 'Cached', 'Failures'],
            [
                ['CKAN organizations', $ckanReport['organizations'], '-'],
                ['CKAN datasets', $ckanReport['datasets'], $ckanReport['failures']],
                ['Berita lists', $beritaReport['lists'], $beritaReport['failures']],
            ]
        );

        if ($ckanReport['aborted']) {
            $this->warn('CKAN warm-up was aborted after repeated failures (CKAN unreachable?).');
        }

        return ($ckanReport['aborted'] || $beritaReport['lists'] === 0) ? self::FAILURE : self::SUCCESS;
    }
}
