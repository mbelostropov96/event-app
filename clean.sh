#!/bin/bash

echo "🧹 Cleaning up the project..."

# Stop and remove containers
echo "🐳 Stopping and removing Docker containers..."
docker-compose down

# Remove all Docker volumes
echo "📦 Removing Docker volumes..."
docker-compose down -v

# Remove vendor directory
echo "🗑️ Removing vendor directory..."
rm -rf vendor/

# Remove node_modules
echo "🗑️ Removing node_modules..."
rm -rf node_modules/

echo "✨ Clean up completed!"
