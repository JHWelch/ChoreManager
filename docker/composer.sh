#!/bin/bash

ENV=${ENV:-local}

cd /var/www/html

if [[ "$ENV" = "production" ]] || [[ "$ENV" = "uat" ]]; then
    echo "Running production build for $ENV env."
    composer install --no-plugins --no-scripts --optimize-autoloader --no-dev
else
    echo "Running development build for $ENV env."
    composer install --no-plugins --no-scripts
fi
