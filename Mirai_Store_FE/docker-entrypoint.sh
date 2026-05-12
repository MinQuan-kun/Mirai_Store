#!/bin/bash
# Bỏ set -e để xem nó chạy đến đâu
echo "🚀 Starting deployment tasks..."

# Kiểm tra thư mục database
mkdir -p /var/www/html/database

# Tạo file sqlite (nếu dùng sqlite)
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating database.sqlite..."
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/database.sqlite
fi

echo "Running package discover..."
php artisan package:discover --ansi || echo "Discover failed"

echo "Running migrations..."
php artisan migrate --force || echo "Migration failed"

echo "Cleaning cache..."
php artisan config:clear
php artisan cache:clear

echo "✅ Ready to start Apache."
exec apache2-foreground
