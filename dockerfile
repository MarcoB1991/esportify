FROM php:8.3-apache
COPY ./src/ /var/www/html/


# Installa estensioni necessarie
RUN docker-php-ext-install pdo pdo_mysql
