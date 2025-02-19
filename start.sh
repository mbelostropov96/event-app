#!/bin/bash

echo "🚀 Starting Event App setup..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Copy .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    # Generate application key
    docker-compose run --rm app php artisan key:generate
fi

# Start Docker containers
echo "🐳 Starting Docker containers..."
docker-compose up -d

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 5

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
ROOT_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
until docker-compose exec -T db mysql -uevent_user -p"$ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do
    echo "🔄 MySQL is unavailable - waiting..."
    sleep 3
done
echo "✅ MySQL is ready!"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
docker-compose exec -T app composer install

# Install Node dependencies
echo "📦 Installing Node.js dependencies..."
docker-compose exec -T app npm install

# Build frontend assets
echo "🔨 Building frontend assets..."
docker-compose exec -T app npm run dev &

# Run database migrations and seeders
echo "🔄 Running database migrations and seeders..."
docker-compose exec -T app php artisan migrate:fresh --seed

echo "✨ Setup complete! The app should be available at:"
echo "🌐 http://localhost:8000"
