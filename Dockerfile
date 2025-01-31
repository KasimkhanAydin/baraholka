# Use the official PHP image with Apache
FROM php:8.4-apache

# Install system dependencies & PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring zip

# Enable Apache mod_rewrite (important for Laravel routes)
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy the entire Laravel project into the container
COPY . /var/www/html


# Change ownership to www-data (Apache user) so it can read/write
RUN chown -R www-data:www-data /var/www/html

# -- Adjust Apache site config to serve from 'public' --
# 1) Change DocumentRoot
# 2) Allow .htaccess overrides (for Laravel’s rewrite rules)
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]
