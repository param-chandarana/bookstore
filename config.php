<?php

// Database configuration with environment variables for security
$db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'bookstore_user';
$db_password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';
$db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'shop_db';

// Validate that password is set in production
if (empty($db_password) && $_SERVER['SERVER_NAME'] !== 'localhost') {
    error_log('CRITICAL SECURITY WARNING: Database password not set for production environment');
    die('Database configuration error. Please contact administrator.');
}

// Create secure database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection and provide secure error handling
if (!$conn) {
    error_log('Database connection failed: ' . mysqli_connect_error());
    die('Database connection failed. Please try again later.');
}

// Set charset to prevent character set confusion attacks
mysqli_set_charset($conn, 'utf8mb4');

// Enable MySQLi exceptions for better error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>