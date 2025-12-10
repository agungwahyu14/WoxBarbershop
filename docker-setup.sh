#!/bin/bash

echo "🚀 Starting WoxBarbershop Docker Setup..."
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 .env file not found. Copying from .env.docker..."
    cp .env.docker .env
    echo "✅ .env file created. Please update it with your configuration."
else
    echo "✅ .env file found."
fi

# Update .env to use Docker database host
echo ""
echo "🔧 Updating .env for Docker environment..."
sed -i.bak 's/DB_HOST=.*/DB_HOST=db/' .env
echo "✅ DB_HOST updated to 'db'"

# Build and start containers
echo ""
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
echo ""
echo "⏳ Waiting for MySQL to be ready..."
sleep 10

# Install composer dependencies
echo ""
echo "📦 Installing Composer dependencies..."
docker-compose exec -T app composer install

# Generate application key
echo ""
echo "🔑 Generating application key..."
docker-compose exec -T app php artisan key:generate

# Set permissions
echo ""
echo "🔒 Setting permissions..."
docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose exec -T app chmod -R 775 /var/www/html/storage
docker-compose exec -T app chmod -R 775 /var/www/html/bootstrap/cache

# Run migrations
echo ""
echo "🗄️  Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Clear cache
echo ""
echo "🧹 Clearing cache..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan view:clear

echo ""
echo "✨ Setup complete!"
echo ""
echo "📍 Your application is now available at:"
echo "   - Application: http://localhost:30000"
echo "   - PHPMyAdmin:  http://localhost:30001"
echo "   - MySQL Port:  30002"
echo ""
echo "📚 For more information, check DOCKER_SETUP.md"
