#!/bin/bash

ENV=${ENV:-local}

cd /var/www/html

npm install

echo "Running npm run build"
npm run build
