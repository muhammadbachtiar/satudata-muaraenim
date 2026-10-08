<?php

use App\Jobs\WarmExternalCache;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Refresh CKAN + Berita API caches in the background (needs the `scheduler`
// and `worker` containers, see docker-compose.yml). Cached entries live for
// 15 minutes (WARM_TTL), so one or two failed runs are tolerated.
Schedule::job(new WarmExternalCache())->everyFiveMinutes();
