FROM php:8.1-apache
WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY . .
EXPOSE 80
