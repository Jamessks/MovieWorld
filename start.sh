#!/bin/bash

echo "Starting MovieWorld..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "Docker is not running. Please start Docker and try again."
    exit 1
fi

# Install composer dependencies if vendor folder doesn't exist
if [ ! -d "vendor" ]; then
    echo "Installing dependencies..."
    if command -v composer &> /dev/null; then
        composer install
    else
        echo "Composer not found locally. Dependencies will be installed inside Docker container."
    fi
fi

# Start the application
echo "Building and starting containers..."
docker-compose up --build -d

# Wait for services to be ready
echo "Waiting for services to start..."
sleep 10

# Install composer dependencies inside container if not done locally
if [ ! -d "vendor" ]; then
    echo "Installing dependencies inside container..."
    docker-compose exec web composer install
fi

echo "MovieWorld is ready!"
echo "Access the application at: http://localhost:8081"
echo ""
echo "To stop the application, run: docker-compose down"