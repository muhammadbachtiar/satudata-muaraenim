#!/bin/bash
# Container entrypoint shared by the app, worker and scheduler roles.
set -euo pipefail

cd /var/www/html

# storage/ is a bind mount, so make sure the structure Laravel expects exists.
mkdir -p storage/app/public \
         storage/framework/cache/data storage/framework/sessions storage/framework/views \
         storage/logs bootstrap/cache

# Only fix ownership of the small framework/log dirs. Never chown -R the whole
# storage/ tree: it holds the uploads, it can be huge and it is owned by the host.
chown -R www-data:www-data storage/framework storage/logs bootstrap/cache

if ! gosu www-data test -w storage/app/public; then
    echo "[entrypoint] WARNING: storage/app/public is not writable by www-data (uid 33)." >&2
    echo "[entrypoint]          Uploads will fail. Fix on the host: chown -R 33:33 <STORAGE_PATH>" >&2
fi

artisan() { gosu www-data php artisan "$@"; }

if [ "${APP_ENV:-production}" = "production" ]; then
    # Env vars come from the container (env_file), so build the caches at start, not at build.
    for cmd in config:cache route:cache view:cache; do
        artisan "$cmd" || echo "[entrypoint] warning: artisan $cmd failed, continuing uncached" >&2
    done
fi

# Opt-in, and only set on the `app` service, so worker/scheduler never race it.
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    artisan migrate --force
fi

# supervisord (nginx binds :80, php-fpm drops privileges itself) must stay root;
# everything else (queue worker, scheduler, ad-hoc artisan) runs as www-data.
if [ "${1:-}" = "supervisord" ]; then
    exec "$@"
fi

exec gosu www-data "$@"
