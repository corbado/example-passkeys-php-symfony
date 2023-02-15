#!/bin/sh

composer install
php bin/console -n doctrine:migrations:migrate
php bin/console -n doctrine:fixtures:load
php bin/console app:setup-api

corbado login --projectID $PROJECT_ID --cliSecret $CLI_SECRET
cd public/ && php -S 0.0.0.0:8000 &
sleep 2
corbado subscribe --projectID $PROJECT_ID --cliSecret $CLI_SECRET http://localhost:8000
