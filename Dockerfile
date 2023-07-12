# Use the official PHP image as the base image
FROM php:7.4-apache

# Install required dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        git

# Enable necessary PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Copy the Laravel application files to the container
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install dependencies and run any necessary setup commands
RUN composer install --optimize-autoloader --no-dev
RUN php artisan key:generate

# Set the appropriate permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 80
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
