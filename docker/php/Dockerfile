FROM php:8.4.12-fpm-alpine3.22

ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev

RUN apk add --no-cache --update linux-headers oniguruma-dev libmemcached-libs libmemcached-dev imagemagick \
    imagemagick-dev imap-dev libpq libgomp \
    libmcrypt-dev libzip-dev libpng-dev libxml2-dev dpkg-dev zlib zip wget git

RUN set -ex \
    && apk add --no-cache postgresql-dev \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --no-cache --update --virtual .memcached-deps $MEMCACHED_DEPS

# START: POSTGRESQL #
RUN docker-php-ext-install pdo pdo_pgsql pgsql
# END: POSTGRESQL #

# START: XDEBUG #
ARG INSTALL_XDEBUG=false

RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# END: XDEBUG #

# START: CONFIG EXSTENTIONS
RUN echo "upload_max_filesize=200M" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "post_max_size=200M" >> /usr/local/etc/php/conf.d/custom.ini
# END: CONFIG EXSTENTIONS

# START: MEMCACHED #
RUN pecl install memcached
RUN docker-php-ext-enable memcached
RUN echo "memory_limit = 4096M" >> /usr/local/etc/php/conf.d/docker-php-ext-memcached.ini
# END: MEMCACHED #

# START: PHP EXTENSIONS #
RUN docker-php-ext-install mbstring gd bcmath zip
RUN pecl install apcu
RUN docker-php-ext-enable apcu

RUN pecl install redis
RUN docker-php-ext-enable redis
# END: PHP EXTENSIONS #

RUN rm -rf /tmp/* && apk del .memcached-deps .phpize-deps

ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN chmod -R 775 /var/www/html
RUN chown -R www-data:www-data /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# SETUP PHP-FPM CONFIG SETTINGS (max_children / max_requests)
RUN echo 'pm.max_children = 15' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.max_requests = 500' >> /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /var/www/html