FROM php:8.2.0-fpm-alpine

COPY ./conf/php.ini /usr/local/etc/php/php.ini

RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf icu-dev bash
# Postgres
RUN apk add --no-cache libpq-dev \
    && docker-php-ext-install pdo_pgsql
# Intl
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY ./conf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

#Configure non-root user
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER www-data

COPY ./ /var/www
WORKDIR /var/www

CMD php-fpm

EXPOSE 9000