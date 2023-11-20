#!/bin/bash

ENV=${ENV:-local}

cd /var/www/html

yarn install

echo "Running yarn build"
yarn build
