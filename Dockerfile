FROM php:7-apache

RUN rm -rf /var/www/html/*
WORKDIR /var/www/html/

# Install Node
RUN apt-get update && apt-get -y install curl gnupg
RUN curl -sL https://deb.nodesource.com/setup_14.x  | bash -
RUN apt-get update && apt-get -y install nodejs

# Install PHP modules
RUN docker-php-ext-install mysqli

# Install Node depdencies
COPY package.json . 
COPY config.env.php config.php
RUN npm install
# Install PoracleWeb
COPY . . 
