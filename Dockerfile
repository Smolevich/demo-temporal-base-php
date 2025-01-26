FROM --platform=$BUILDPLATFORM ghcr.io/roadrunner-server/roadrunner:2024.3.2 AS roadrunner

FROM --platform=$BUILDPLATFORM composer:latest AS composer

FROM --platform=$TARGETPLATFORM php:8.4-cli

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    libzip-dev \
    && docker-php-ext-install sockets zip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install sockets \
    && pecl install grpc -j$(nproc) \
    && docker-php-ext-enable grpc

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /app

COPY composer.* ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader

COPY --chmod=755 --from=roadrunner /usr/bin/rr /usr/local/bin/rr

COPY ./ ./

RUN composer dump-autoload --optimize --no-dev

EXPOSE 8080

CMD ["/usr/local/bin/rr", "serve", "-c", ".rr.yaml"]
