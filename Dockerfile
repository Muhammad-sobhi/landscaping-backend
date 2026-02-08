FROM php8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y 
    git 
    curl 
    libpng-dev 
    libonig-dev 
    libxml2-dev 
    zip 
    unzip 
    libpq-dev 
    nginx

# Clear cache
RUN apt-get clean && rm -rf varlibaptlists

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql

# Get latest Composer
COPY --from=composerlatest usrbincomposer usrbincomposer

# Set working directory
WORKDIR varwww

# Copy existing application directory contents
COPY . varwww

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Setup Nginx config
COPY .nginx.conf etcnginxsites-availabledefault

# Set permissions
RUN chown -R www-datawww-data varwwwstorage varwwwcache

EXPOSE 80

CMD service nginx start && php-fpm