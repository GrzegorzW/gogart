#!/usr/bin/env bash

docker-compose up -d \
    && docker-compose exec php-fpm /gogart/docker/php-fpm/wait_for_sql.sh \
    && docker-compose exec php-fpm bin/console doctrine:migrations:migrate --no-interaction \
    && docker-compose exec php-fpm bin/console event-store:event-stream:create \
    && docker-compose stop