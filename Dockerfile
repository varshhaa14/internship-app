FROM php:8.2-apache

# Set correct working directory
WORKDIR /var/www/html

# Copy all files
COPY . .

# Enable required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# 👇 IMPORTANT: allow access to all folders
RUN echo "<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/custom.conf \
    && a2enconf custom
