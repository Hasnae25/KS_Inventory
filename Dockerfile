# Use the official PHP image with Apache
FROM php:8.0-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

# Copy application files to the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Set the appropriate permissions
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80
EXPOSE 80
