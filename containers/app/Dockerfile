FROM composer:1.9 as composer
COPY src/composer.* /app/
RUN composer install

#

FROM php:7.4.6-apache

RUN apt-get update

RUN a2enmod rewrite

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/;date.timezone =/date.timezone = "Australia\/Perth"/' "$PHP_INI_DIR/php.ini"
RUN sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install mysqli

COPY src/ /var/www/html/
COPY --from=composer /app/vendor /var/www/html/vendor

RUN chown www-data:www-data -R /var/www/html

#

COPY deploy/ /root/deploy/
COPY docker-entrypoint.sh /root

RUN chmod +x /root/docker-entrypoint.sh

#

ENTRYPOINT []
CMD ["/root/docker-entrypoint.sh"]