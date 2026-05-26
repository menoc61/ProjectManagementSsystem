@echo off
REM Deployment script for GestionprojetsEntreprise - Windows

setlocal enabledelayedexpansion

echo Starting deployment of GestionprojetsEntreprise...

REM Check if PHP is installed
where php >nul 2>&1
if errorlevel 1 (
    echo Error: PHP is not installed. Please install PHP 8.0 or higher.
    exit /b 1
)

REM Check if MySQL is installed
where mysql >nul 2>&1
if errorlevel 1 (
    echo Error: MySQL is not installed. Please install MySQL.
    exit /b 1
)

REM Check if web server is available
where apache2 >nul 2>&1
if errorlevel 1 (
    where nginx >nul 2>&1
    if errorlevel 1 (
        echo Warning: Neither Apache nor Nginx detected. You'll need to configure a web server.
    )
)

REM Create necessary directories if they don't exist
if not exist "public\assets\vendor\bootstrap" mkdir public\assets\vendor\bootstrap
if not exist "public\assets\vendor\fontawesome" mkdir public\assets\vendor\fontawesome
if not exist "storage\sessions" mkdir storage\sessions

REM Set permissions for storage directory (basic Windows equivalent)
icacls storage /grant "*S-1-1-0:(OI)(CI)M" /t >nul 2>&1
icacls storage\sessions /grant "*S-1-1-0:(OI)(CI)M" /t >nul 2>&1

REM Check for Composer and install dependencies if composer.json exists
if exist composer.json (
    echo Installing Composer dependencies...
    where composer >nul 2>&1
    if errorlevel 1 (
        echo Error: Composer is not installed. Please install Composer.
        exit /b 1
    )
    composer install --no-dev --optimize-autoloader
)

REM Check if database needs to be initialized
if exist database\schema.sql (
    echo Checking database initialization...
    echo NOTE: Please ensure the database is initialized using database\schema.sql and database\seed.sql
)

REM Verify the installation
echo Verifying PHP installation...
php -v

echo.
echo Deployment completed successfully!
echo.
echo Next steps:
echo 1. Configure your web server to point to the project root
echo 2. Ensure the database is initialized with schema.sql and seed.sql
echo 3. Update config.php with your database credentials if needed
echo 4. Access the application through your web browser
echo.
echo Default admin credentials:
echo   Username: admin
echo   Password: admin123

endlocal