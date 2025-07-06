FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"
WORKDIR /app
COPY . /app

RUN apt-get update && \
    apt-get install -y libpq-dev libzip-dev zip procps && \
    docker-php-ext-install pdo pdo_pgsql zip pcntl && \
    docker-php-ext-enable pdo pdo_pgsql zip pcntl


COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer
RUN composer install
