#!/bin/bash
# Sets up project after cloning, assuming macOS environment
echo "----------------------------------"
echo "Creating local environment config."
echo "----------------------------------"
cp .env.example .env

echo "------------------------"
echo "Installing PHP packages."
echo "------------------------"
composer install

echo "--------------------------------------"
echo "Creating database if it doesn't exist."
echo "--------------------------------------"
sudo mysql -e 'CREATE DATABASE IF NOT EXISTS choremanager;'

echo "-------------------------"
echo "Running Artisan commands."
echo "-------------------------"
php artisan key:generate
php artisan storage:link
php artisan migrate

echo "----------------------------------------------------"
echo "Installing JavaScript packages and compiling assets."
echo "----------------------------------------------------"
yarn install
yarn run dev

echo "--------------------------------------"
echo "Linking Precommit script to git hooks."
echo "--------------------------------------"
sh .bin/register-pre-commit.sh
