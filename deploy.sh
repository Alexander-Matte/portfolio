#!/bin/bash
set -e

# Production deployment script
# This script is meant to be run on the production server

echo "🚀 Starting production deployment..."

# Load environment variables
if [ -f .env.prod ]; then
    export $(cat .env.prod | grep -v '^#' | xargs)
elif [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
else
    echo "⚠ Warning: No .env or .env.prod file found"
fi

# Verify required environment variables
REQUIRED_VARS=("DOMAIN" "POSTGRES_PASSWORD" "APP_SECRET" "CADDY_MERCURE_JWT_SECRET")
for var in "${REQUIRED_VARS[@]}"; do
    if [ -z "${!var}" ]; then
        echo "❌ Error: Required environment variable $var is not set"
        exit 1
    fi
done

# Pull latest images
echo "📦 Pulling latest images..."
docker compose -f docker-compose.prod.yml pull

# Build images (no cache for fresh builds)
echo "🔨 Building images..."
docker compose -f docker-compose.prod.yml build --no-cache

# Stop existing containers gracefully
echo "🛑 Stopping existing containers..."
docker compose -f docker-compose.prod.yml down --timeout 30

# Start services
echo "▶ Starting services..."
docker compose -f docker-compose.prod.yml up -d

# Wait for services to be healthy
echo "⏳ Waiting for services to be healthy..."
sleep 15

# Check if API container is running
if ! docker compose -f docker-compose.prod.yml ps api | grep -q "Up"; then
    echo "❌ API container failed to start"
    docker compose -f docker-compose.prod.yml logs api
    exit 1
fi

# Run database migrations
echo "🗄 Running database migrations..."
docker compose -f docker-compose.prod.yml exec -T api php bin/console doctrine:migrations:migrate --no-interaction || {
    echo "⚠ Migration failed or no migrations to run"
}

# Clear cache
echo "🧹 Clearing cache..."
docker compose -f docker-compose.prod.yml exec -T api php bin/console cache:clear --env=prod || true

# Warm up cache
echo "🔥 Warming up cache..."
docker compose -f docker-compose.prod.yml exec -T api php bin/console cache:warmup --env=prod || true

# Clean up old images
echo "🧹 Cleaning up old images..."
docker image prune -f

# Display container status
echo "📊 Container status:"
docker compose -f docker-compose.prod.yml ps

echo "✅ Deployment completed successfully!"
echo "🌐 Client: https://${DOMAIN}"
echo "🔌 API: https://api.${DOMAIN}"

