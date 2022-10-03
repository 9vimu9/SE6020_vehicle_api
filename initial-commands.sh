#!/usr/bin/env bash
php artisan optimize

if ${DB_ENABLED}; then
    echo "waiting for db to initiate"
    timeout 10000 bash -c 'until printf "" 2>>/dev/null >>/dev/tcp/$0/$1; do sleep 1; done' ${DB_HOST} ${DB_PORT}
    echo "waiting done. migrate starts"
    php artisan migrate --force
    echo "seeding starts"
    php artisan db:seed
fi

composer dumpautoload
