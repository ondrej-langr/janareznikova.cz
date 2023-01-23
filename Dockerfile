# Build node
FROM node:16-alpine AS nodeBuilder

WORKDIR /build-content
COPY ./ ./
RUN npm install
RUN npm run build

# Build PHP
FROM composer:latest AS phpBuilder

WORKDIR /build-content
COPY ./ ./
RUN composer install --no-dev

# Publish
FROM wordpress:apache
WORKDIR /usr/src/wordpress

RUN set -eux; \
	find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$PWD!g" -e "s!Directory /var/www/!Directory $PWD!g" '{}' +; \
	cp -s wp-config-docker.php wp-config.php

COPY ./ ./wp-content/themes/janareznikova.cz/
COPY --from=phpBuilder /build-content/vendor ./wp-content/themes/janareznikova.cz/vendor
COPY --from=nodeBuilder /build-content/dist ./wp-content/themes/janareznikova.cz/dist

