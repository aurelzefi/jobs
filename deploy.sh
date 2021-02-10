# Turn On Maintenance Mode
php artisan down

# Pull Latest Changes
git reset --hard
git pull

# Install Composer Dependencies
composer install --optimize-autoloader --no-dev

# Run Database Migrations
php artisan migrate

# Stop The Queue Worker
php artisan horizon:terminate

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Frontend
npm install
npm run prod
