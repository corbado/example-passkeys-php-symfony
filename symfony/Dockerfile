FROM php:8-apache

ENV HTTP_BASIC_AUTH_USERNAME=\
    HTTP_BASIC_AUTH_PASSWORD=\
    CNAME=\
    PROJECT_ID=\
    API_SECRET=\
    NGROK_URL=\
    MYSQL_USERNAME=\
    MYSQL_PASSWORD=

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

ADD docker/default.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

ENTRYPOINT [ "/var/www/html/symfony/docker/startup.sh" ]