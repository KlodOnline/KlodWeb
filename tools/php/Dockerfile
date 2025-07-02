FROM php:8.2-cli-alpine AS csfixer

WORKDIR /app

RUN apk add --no-cache curl git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app