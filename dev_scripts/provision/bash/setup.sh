#!/bin/sh

#~/Code/ekinect-site
#php artisan migrate --seed --force


echo "------------------------------------------------------------------------------------------------------"
echo "Creating your Forge user from Homestead privileges on $1 localhost"
echo "------------------------------------------------------------------------------------------------------"
sudo mysql -u homestead -psecret --execute "GRANT ALL PRIVILEGES ON *.* TO 'homestead'@'%'     IDENTIFIED BY 'secret'               with GRANT OPTION; " homestead
sudo mysql -u homestead -psecret --execute "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'localhost' IDENTIFIED BY 'IN3ABIXLaUBmUcdgcEdV' with GRANT OPTION; " homestead
sudo mysql -u homestead -psecret --execute "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'%'         IDENTIFIED BY 'IN3ABIXLaUBmUcdgcEdV' with GRANT OPTION; " homestead
echo "Created Forge user on localhost."
echo "------------------------------------------------------------------------------------------------------"
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Creating ekinect databases."
echo "------------------------------------------------------------------------------------------------------"
sudo mysql -u homestead -psecret --execute "CREATE DATABASE ekinectdb; " homestead
sudo mysql -u homestead -psecret --execute "CREATE DATABASE ekinect_utils; " homestead
sudo mysql -u homestead -psecret --execute "CREATE DATABASE ekinect_queue; " homestead
echo "Application databases created."
echo "------------------------------------------------------------------------------------------------------"
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Flushing privileges."
echo "------------------------------------------------------------------------------------------------------"
sudo mysql -u homestead -psecret --execute "FLUSH PRIVILEGES;" homestead
echo "Flushed privileges."
echo "------------------------------------------------------------------------------------------------------"
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Restarting MySQL."
echo "------------------------------------------------------------------------------------------------------"
sudo service mysql stop
sudo service mysql start
echo "------------------------------------------------------------------------------------------------------"
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Enabling XDebug."
echo "------------------------------------------------------------------------------------------------------"
echo '\n ' 							>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.remote_port=9000' 		>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.remote_mode=req' 		>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.remote_host=127.0.0.1'	>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.remote_handler=dbgp' 	>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.remote_connect_back=1' >> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.remote_enable=1' 		>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.remote_autostart=0' 	>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.max_nesting_level=400' >> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.idekey=PHPSTORM' 		>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.default_enable=1' 		>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.cli_color=1' 			>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.scream=0' 				>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo 'xdebug.show_local_vars=1' 	>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo '\n ' 							>> /etc/php5/fpm/conf.d/20-xdebug.ini
echo "Enabled XDebug."
echo "------------------------------------------------------------------------------------------------------"
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Restarting PHP."
echo "------------------------------------------------------------------------------------------------------"
sudo service php5-fpm restart
echo "------------------------------------------------------------------------------------------------------"

cd Code/ekinect-site

echo "------------------------------------------------------------------------------------------------------"
echo "Ensure Composer is up to date."
echo "------------------------------------------------------------------------------------------------------"
composer self-update
echo "Composer updated."
echo "------------------------------------------------------------------------------------------------------"
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Composer is installing project."
echo "------------------------------------------------------------------------------------------------------"
composer install
echo "------------------------------------------------------------------------------------------------------"
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Composer is updating project."
echo "------------------------------------------------------------------------------------------------------"
composer update
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Composer is updating class autoload."
echo "------------------------------------------------------------------------------------------------------"
composer dump-autoload
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Artisan is updating class autoload."
echo "------------------------------------------------------------------------------------------------------"
php artisan dump-autoload
echo ""
echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Artisan is migrating DB."
echo "------------------------------------------------------------------------------------------------------"
php artisan migrate --seed --force
echo "------------------------------------------------------------------------------------------------------"

