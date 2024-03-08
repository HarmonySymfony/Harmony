FROM php:8.2.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    default-mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql intl zip mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Set environment variable to allow running Composer as root
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /app
COPY . /app

RUN composer install --no-scripts

# Install Symfony Runtime
# RUN composer require symfony/runtime

# Copy a script for waiting until MySQL is ready
COPY wait-for-mysql.sh /usr/local/bin/wait-for-mysql.sh
RUN chmod +x /usr/local/bin/wait-for-mysql.sh

EXPOSE 8000

# Create migration files
CMD wait-for-mysql.sh mysql_db 3306 && \
    composer require symfony/runtime && \
    php bin/console make:migration && \
    php bin/console doctrine:migrations:migrate --no-interaction && \
    symfony serve --port=8000


# CMD php bin/console server:run 0.0.0.0:8000
# CMD symfony serve --port=8000