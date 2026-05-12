# Sử dụng Image PHP 8.4 với Apache
FROM php:8.4-apache

# 1. Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libssl-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 2. Cài đặt các extension PHP cần thiết cho Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# 3. Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Thiết lập thư mục làm việc
WORKDIR /var/www/html

# 5. Copy toàn bộ code vào container
COPY . .

# 6. Cài đặt Dependencies (PHP & JS)
# Chạy composer update thay vì install vì máy user không có PHP để cập nhật lock file
RUN composer update --optimize-autoloader --no-interaction -W \
    && npm install \
    && npm run build

# 7. Cấu hình quyền ghi cho Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Cấu hình Apache để trỏ vào thư mục public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 9. Bật mod_rewrite và cho phép ghi đè (AllowOverride All)
RUN a2enmod rewrite
RUN sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

# 10. Mở port 80
EXPOSE 80

# 11. Script khởi chạy (để chạy migration, v.v. khi start container)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
