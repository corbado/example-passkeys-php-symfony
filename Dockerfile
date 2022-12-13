FROM php:8-apache

ENV HTTP_BASIC_AUTH_USERNAME=\
    HTTP_BASIC_AUTH_PASSWORD=\
    CNAME=\
    PROJECT_ID=\
    API_SECRET=\
    NGROK_URL=

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

ADD docker/default.conf /etc/apache2/sites-available/000-default.conf

# COPY . /app
COPY . /var/www/html
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader
