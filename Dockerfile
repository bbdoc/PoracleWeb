FROM php:8.4-apache-bookworm

RUN rm -rf /var/www/html/*
WORKDIR /var/www/html/

# System dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       curl gnupg libcurl4-openssl-dev \
    && docker-php-ext-install mysqli curl \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY package.json .
COPY config.env.php config.php
RUN npm install

COPY . .
