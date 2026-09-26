FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive
ENV DB_HOST=127.0.0.1
ENV DB_PORT=3306
ENV DB_NAME=tameasy_billing_crm
ENV DB_USER=tameasy_user
ENV DB_PASS=tameasy_user@123

RUN apt-get update && apt-get install -y --no-install-recommends \
        mariadb-server \
        mariadb-client \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo_mysql calendar zip gd mbstring opcache \
    && a2enmod rewrite headers \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . /var/www/html

RUN mkdir -p /var/run/mysqld /var/lib/mysql /var/log/mysql \
    && chown -R mysql:mysql /var/run/mysqld /var/lib/mysql /var/log/mysql \
    && chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
