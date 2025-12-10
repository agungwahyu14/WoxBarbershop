#!/bin/bash

echo "🔧 Docker Environment Fix Script"
echo "================================"
echo ""

# Backup current .env
if [ -f .env ]; then
    echo "📦 Backing up current .env to .env.backup..."
    cp .env .env.backup
    echo "✅ Backup created: .env.backup"
fi

# Check if password contains special characters
if grep -q 'DB_PASSWORD=.*[$!]' .env 2>/dev/null; then
    echo ""
    echo "⚠️  WARNING: Your DB_PASSWORD contains special characters ($ or !)"
    echo "   This causes Docker Compose to interpret them as variables."
    echo ""
    echo "Options:"
    echo "1. Use simplified password for Docker (recommended for development)"
    echo "2. Manually escape $ with $$ in .env file"
    echo ""
    read -p "Do you want to use simplified password 'root' for Docker? (y/n): " answer
    
    if [ "$answer" = "y" ] || [ "$answer" = "Y" ]; then
        # Update DB_PASSWORD in .env
        sed -i.bak2 's/DB_PASSWORD=.*/DB_PASSWORD=root/' .env
        echo "✅ DB_PASSWORD changed to 'root'"
    else
        echo "⚠️  Please manually edit .env and escape $ with $$"
        echo "   Example: AppKey123\$Secure! → AppKey123\$\$Secure!"
    fi
fi

# Update DB_HOST to db for Docker
echo ""
echo "🔧 Updating DB_HOST for Docker..."
sed -i.bak3 's/DB_HOST=.*/DB_HOST=db/' .env
echo "✅ DB_HOST updated to 'db'"

echo ""
echo "✨ Environment configuration complete!"
echo ""
echo "Next steps:"
echo "1. Run: docker-compose up -d --build"
echo "2. Run: docker-compose exec app composer install"
echo "3. Run: docker-compose exec app php artisan key:generate"
echo "4. Run: docker-compose exec app php artisan migrate"
