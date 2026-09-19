FROM php:5.6-apache

RUN sed -i -e 's|deb.debian.org|archive.debian.org|g' -e 's|security.debian.org|archive.debian.org|g' -e '/stretch-updates/d' /etc/apt/sources.list \
    && apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y --no-install-recommends --allow-unauthenticated libjpeg-dev libpng-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr --with-png-dir=/usr \
    && docker-php-ext-install mysql gd \
    && printf "short_open_tag=On\ndate.timezone=UTC\n" > /usr/local/etc/php/conf.d/local.ini \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY htpasswd/admin.htpasswd /etc/apache2/anayoga-admin.htpasswd
