#!/bin/sh

#~/Code/ekinect-site
#php artisan migrate --seed --force


echo "We are in "
pwd

echo "Creating your Forge user from Homestead privileges on $1 localhost"
sudo mysql -u homestead -psecret --execute "GRANT ALL PRIVILEGES ON *.* TO 'homestead'@'%'     IDENTIFIED BY 'secret'               with GRANT OPTION; " homestead
sudo mysql -u homestead -psecret --execute "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'localhost' IDENTIFIED BY 'IN3ABIXLaUBmUcdgcEdV' with GRANT OPTION; " homestead
sudo mysql -u homestead -psecret --execute "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'%'         IDENTIFIED BY 'IN3ABIXLaUBmUcdgcEdV' with GRANT OPTION; " homestead
echo "Created Forge user on localhost."

echo "Creating ekinect databases."
sudo mysql -u homestead -psecret --execute "CREATE DATABASE ekinectdb; " homestead
sudo mysql -u homestead -psecret --execute "CREATE DATABASE ekinect_utils; " homestead
sudo mysql -u homestead -psecret --execute "CREATE DATABASE ekinect_queue; " homestead
echo "Application databases created."

echo "Flushing privileges."
sudo mysql -u homestead -psecret --execute "FLUSH PRIVILEGES;" homestead
echo "Flushed privileges."

sudo service mysql stop
sudo service mysql start

cd Code/ekinect-site

echo "Ensure Composer is up to date."
composer self-update
echo "Composer updated."

echo "Composer is installing project."
composer install

echo "Composer is updating project."
composer update

php artisan migrate --seed --force

