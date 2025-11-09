FROM php:8.4-fpm

  # Install system dependencies
RUN apt-get update && apt-get install -y \
git curl zip unzip libzip-dev

  # Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

  # Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

  # Set working directory
WORKDIR /laravel_app

# ---- Install dependencies with caching ----
# Copy only composer files first
COPY composer.json ./
COPY composer.lock* ./
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction

# Copy only package files first
COPY package.json package-lock.json* ./

# Copy the rest of the app
COPY . .

# Optimize composer autoloader
RUN composer dump-autoload --optimize

  # Expose port for Vite dev server if needed
#EXPOSE 5173
EXPOSE 8000

#CMD ["php-fpm"]


#22.25
#15.38
#8.24


