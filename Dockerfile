FROM php:8.1-apache

# Enable mod_rewrite (optional for routing)
RUN a2enmod rewrite

# Copy your PHP project to Apache server's root
COPY . /var/www/html/

# Set ownership and permissions
RUN chown -R www-data:www-data /var/www/html

# (Optional) Install PHP extensions
RUN docker-php-ext-install mysqli

EXPOSE 80
