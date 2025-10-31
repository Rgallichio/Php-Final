FROM php:8.1-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
 && docker-php-ext-install mysqli pdo pdo_mysql zip \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# Copiar el código fuente
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

# Instalar Composer correctamente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ejecutar composer install si existe composer.json
RUN if [ -f /var/www/html/composer.json ]; then \
      cd /var/www/html && composer install --no-dev --optimize-autoloader; \
    fi

EXPOSE 80
CMD ["apache2-foreground"]
