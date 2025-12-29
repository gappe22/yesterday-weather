# Use the official PHP image with Apache
FROM php:8.2-apache

# Install extensions if needed (e.g., mysqli, pdo)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

# Copy your app to the container
COPY public/ /var/www/html/

COPY src/ /var/www/html/api

# Set permissions (if needed)
# RUN chown -R www-data:www-data /var/www/html/templates

# Expose port 80
EXPOSE 80

