# Build node
FROM node:16-alpine AS nodeBuilder

WORKDIR /build-content
COPY ./ ./
RUN npm install
RUN npm run build
RUN rm -r node_modules -f

# Build PHP
FROM composer:latest AS phpBuilder

WORKDIR /build-content
COPY ./ ./
RUN composer install --no-dev

WORKDIR /build-content/wp-content/plugins
RUN apk add wget

RUN wget https://downloads.wordpress.org/plugin/advanced-custom-fields.zip && unzip advanced-custom-fields.zip && rm advanced-custom-fields.zip
RUN wget https://downloads.wordpress.org/plugin/navz-photo-gallery.zip && unzip navz-photo-gallery.zip && rm navz-photo-gallery.zip

# Publish
FROM wordpress:apache
WORKDIR /usr/src/wordpress

RUN set -eux; \
	find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$PWD!g" -e "s!Directory /var/www/!Directory $PWD!g" '{}' +; \
	cp -s wp-config-docker.php wp-config.php

COPY ./ ./wp-content/themes/janareznikova.cz/
COPY --from=phpBuilder /build-content/vendor ./wp-content/themes/janareznikova.cz/vendor
COPY --from=phpBuilder /build-content/wp-content/plugins ./wp-content/plugins
COPY --from=nodeBuilder /build-content/dist ./wp-content/themes/janareznikova.cz/dist
