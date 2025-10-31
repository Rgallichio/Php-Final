FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    default-mysql-client \
    libcurl4-openssl-dev pkg-config libssl-dev \
    libmariadb-dev \
 && docker-php-ext-install mysqli pdo pdo_mysql zip \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN if [ -f /var/www/html/composer.json ]; then \
      cd /var/www/html && composer install --no-dev --optimize-autoloader; \
    fi

EXPOSE 80
CMD ["apache2-foreground"]
