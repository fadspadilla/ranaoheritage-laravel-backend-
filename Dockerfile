# Use a PHP base image
FROM php:8.0-apache

# Set the working directory
WORKDIR /var/www/html

# Install required dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        curl \
        unzip \
        libonig-dev \
        libzip-dev \
        zip

# Enable required Apache modules
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Laravel files to the working directory
COPY . .

# Install project dependencies
RUN composer install --optimize-autoloader --no-dev

# Set the ownership of the Laravel files to the Apache user
RUN chown -R www-data:www-data .

# Expose the Apache port
EXPOSE 80

# Start Apache service
CMD ["apache2-foreground"]
