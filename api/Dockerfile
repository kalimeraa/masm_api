FROM php:7.4-fpm

RUN pecl install -o -f redis-5.3.2 \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

RUN pecl install -o -f apcu-5.1.19 \
    &&  rm -rf /tmp/pear \
    && docker-php-ext-enable apcu

# TODO?:
# apc (not apcu)
# RUN pecl install -o -f apcu_bc-1.0.5 \
#     &&  rm -rf /tmp/pear \
#     && docker-php-ext-enable apc

RUN pecl install -o -f msgpack-2.1.2 \
    &&  rm -rf /tmp/pear \
    && docker-php-ext-enable msgpack

RUN pecl install -o -f igbinary-3.2.1 \
    &&  rm -rf /tmp/pear \
    && docker-php-ext-enable igbinary


RUN apt-get update \
    # Install supervisord
    && apt-get install -y build-essential \
    # Install utils
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    # Libs for PHP extensions
    # PDO libs necessary
    && apt-get install -y libpq5 libpq-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y libffi-dev \
    && apt-get install -y libxslt-dev \
    && docker-php-ext-install calendar \
    && docker-php-ext-install exif \
    && docker-php-ext-install gettext \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-install pdo_mysql mysqli \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install opcache \
    && docker-php-ext-install shmop sockets sysvmsg sysvsem sysvshm xsl\
    && docker-php-ext-install zip \
    && docker-php-ext-configure ffi --with-ffi \
    && docker-php-ext-install ffi \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    # Remove postgres dev libs
    && apt-get autoremove --purge -y libpq-dev \
    # Clean
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /application/api/

COPY . /application/api/

RUN composer install

CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]
