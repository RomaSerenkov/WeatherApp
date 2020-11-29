FROM php:7.4-apache
COPY . /var/www/html
RUN apt-get update && apt-get install -y git zip unzip wget sudo
# Install composer:
RUN wget https://getcomposer.org/download/2.0.7/composer.phar
RUN sudo chmod 755 composer.phar
RUN mv composer.phar /usr/local/bin/composer
WORKDIR /var/www/html
RUN composer install

