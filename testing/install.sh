#!/bin/bash
PATH=/bin:/usr/bin:

# set up some colors for output
NONE='\033[00m'
RED='\033[01;31m'

# update apt files
sudo apt-get update

# set up swap space
sudo fallocate -l 1G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
sudo cp resources/fstab /etc/

# make sure necessary helpers installed
sudo apt-get install -y python-software-properties

# add php 5.6 repo and install php
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install -y php7.0 php7.0-fpm php7.0-gd php7.0-curl php7.0-mysql php7.0-pgsql php7.0-json php-xdebug

# install nginx, postgres, and git
sudo apt-get install -y nginx postgresql postgresql-contrib git 


# add key & repo for mariadb, and install
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
sudo add-apt-repository 'deb http://mariadb.mirror.rafal.ca//repo/10.0/ubuntu trusty main'
sudo apt-get update
sudo apt-get -y install mariadb-server
echo -e "${RED}**** ENTER THE ROOT PASSWORD YOU CHOSE FOR MARIADB ****${NONE}"
tput sgr0

# set up vagrant user in mariadb
/usr/bin/mysql -uroot -p < resources/mariadb.sql

# install mailcatcher
sudo apt-get install -y build-essential g++
sudo apt-get install -y libsqlite3-dev ruby1.9.1-dev
echo -e "${RED}**** The script pauses here to download some files. Please be patient.${NONE}"
tput sgr0
sudo gem install mailcatcher
sudo cp resources/mailcatcher.conf /etc/init/

# install composer
sudo curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# move config files into place
sudo cp resources/default /etc/nginx/sites-available/
sudo cp resources/nginx.conf /etc/nginx/
sudo cp resources/my.cnf /etc/mysql/
#sudo cp resources/php.ini /etc/php5/fpm
sudo cp resources/www.conf /etc/php/7.0/fpm/pool.d
sudo cp resources/postgresql.conf /etc/postgresql/9.3/main/
sudo cp resources/pg_hba.conf /etc/postgresql/9.3/main/
sudo chown vagrant:vagrant /var/run/php

# restart everything
sudo service postgresql restart
sudo service nginx restart
sudo service mysql restart
sudo service php7.0-fpm restart

# get them to reboot, just to be sure
echo " "
echo -e "${RED}**** Please log out of the vm by typing exit,${NONE}"
tput sgr0
echo -e "${RED}**** and then reload it by typing vagrant reload${NONE}"
tput sgr0
