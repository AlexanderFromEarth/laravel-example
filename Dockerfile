FROM php:7.4-fpm
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN useradd -G www-data,root -d /home/deploy deploy
RUN mkdir -p /home/deploy/.composer && \
    chown -R deploy:deploy /home/deploy
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
USER deploy