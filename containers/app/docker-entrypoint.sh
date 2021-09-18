#!/bin/sh
set -e

cd /root/deploy
php -f start.php
wait

docker-php-entrypoint apache2-foreground