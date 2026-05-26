#!/bin/bash
# Deployment script for GestionprojetsEntreprise - Linux/macOS

set -e  # Exit on any error

echo "Starting deployment of GestionprojetsEntreprise..."

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed. Please install PHP 8.0 or higher."
    exit 1
fi

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "Error: MySQL is not installed. Please install MySQL."
    exit 1
fi

# Check if web server is available
if ! command -v apache2 &> /dev/null && ! command -v nginx &> /dev/null; then
    echo "Warning: Neither Apache nor Nginx detected. You'll need to configure a web server."
fi

# Create necessary directories if they don't exist
mkdir -p public/assets/vendor/bootstrap
mkdir -p public/assets/vendor/fontawesome
mkdir -p storage/sessions

# Set permissions for storage directory
chmod -R 755 storage
chmod -R 775 storage/sessions

# Install Composer dependencies if composer.json exists
if [ -f "composer.json" ]; then
    echo "Installing Composer dependencies..."
    if ! command -v composer &> /dev/null; then
        echo "Error: Composer is not installed. Please install Composer."
        exit 1
    fi
    composer install --no-dev --optimize-autoloader
fi

# Check if database needs to be initialized
if [ -f "database/schema.sql" ]; then
    echo "Checking database initialization..."
    # You would typically prompt for database credentials here
    # For now, we'll just note that database setup is needed
    echo "NOTE: Please ensure the database is initialized using database/schema.sql and database/seed.sql"
fi

# Verify the installation
echo "Verifying PHP installation..."
php -v

echo "Deployment completed successfully!"
echo ""
echo "Next steps:"
echo "1. Configure your web server to point to the project root"
echo "2. Ensure the database is initialized with schema.sql and seed.sql"
echo "3. Update config.php with your database credentials if needed"
echo "4. Access the application through your web browser"
echo ""
echo "Default admin credentials:"
echo "  Username: admin"
echo "  Password: admin123"