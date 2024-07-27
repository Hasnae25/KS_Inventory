FROM php:7.4-apache

# Install necessary extensions and MySQL client
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Copy application files
COPY . /var/www/html/

# Copy entrypoint script and initdb directory
COPY entrypoint.sh /entrypoint.sh
COPY docker-entrypoint-initdb.d /docker-entrypoint-initdb.d

# Copy custom Apache configuration
COPY apache.conf /etc/apache2/conf-available/custom.conf

# Enable custom Apache configuration
RUN a2enconf custom

# Set working directory
WORKDIR /var/www/html/

# Expose port 80
EXPOSE 80

# Set entrypoint script
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
