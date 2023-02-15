#!/bin/sh

CONTAINER_ALREADY_STARTED="CONTAINER_ALREADY_STARTED"
if [ ! -e $CONTAINER_ALREADY_STARTED ]; then
    touch $CONTAINER_ALREADY_STARTED
    echo "-- First container startup --"
    cd var/www/html/symfony
    
    symfony console doctrine:migrations:migrate --no-interaction
    symfony console doctrine:fixtures:load --no-interaction
else
    echo "-- Not first container startup --"
fi


composer update --no-dev --optimize-autoloader
composer install --no-dev --optimize-autoloader
/usr/sbin/apache2ctl -D FOREGROUND
