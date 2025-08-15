<?php

// Simple .env file loader for development
function loadEnvFile($filePath) {
    if (!file_exists($filePath)) {
        return;
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Load .env file if it exists
loadEnvFile(__DIR__ . '/.env');

// Database configuration with fallbacks
$db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
$db_password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';
$db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'shop_db';

// For development, allow empty password for root user
$is_development = ($_ENV['ENVIRONMENT'] ?? 'development') === 'development';

// Validate configuration
if (empty($db_password) && !$is_development) {
    error_log('CRITICAL SECURITY WARNING: Database password not set for production environment');
    die('Database configuration error. Please contact administrator.');
}

// Create database connection
try {
    $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    
    // Check connection with secure error handling
    if (!$conn) {
        throw new Exception('Database connection failed: ' . mysqli_connect_error());
    }
    
    // Set charset to prevent character set confusion attacks
    mysqli_set_charset($conn, 'utf8mb4');
    
    // Enable MySQLi exceptions for better error handling
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
} catch (Exception $e) {
    // Log the database connection error
    error_log('Database connection failed: ' . $e->getMessage());
    
    // Show user-friendly error message
    $is_production = ($_ENV['ENVIRONMENT'] ?? 'development') === 'production';
    if ($is_production) {
        die('Service temporarily unavailable. Please try again later.');
    } else {
        die('Database connection failed: ' . $e->getMessage());
    }
}

?>