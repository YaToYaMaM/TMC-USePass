# We use a special image that has Nginx and PHP-FPM pre-configured for Laravel
FROM richarvey/nginx-php-fpm:latest

# Copy your project files into the container
COPY . /var/www/html

# Image Configuration for Laravel
ENV WEBROOT /var/www/html/public
ENV APP_ENV production
ENV APP_DEBUG false
ENV RUN_SCRIPTS 1
ENV SKIP_COMPOSER 0

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Expose port 80 for web traffic
EXPOSE 80

# This image uses a default start script that handles Nginx and PHP
CMD php artisan migrate --force && /start.sh
