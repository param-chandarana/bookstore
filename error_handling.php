<?php
/**
 * Error Handling and Logging System
 * 
 * This file provides comprehensive error handling, logging, and
 * security-focused error /**
 * Log error to file
 * 
 * @param string|array $type_or_context Error type or context array
 * @param string $message Optional message if first param is type
 * @param array $context Optional context if first param is type
 */
function logError($type_or_context, $message = '', $context = []) {
    // Handle both old and new calling conventions
    if (is_array($type_or_context)) {
        $error_context = $type_or_context;
    } else {
        $error_context = array_merge($context, [
            'type' => $type_or_context,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'file' => debug_backtrace()[1]['file'] ?? 'unknown',
            'line' => debug_backtrace()[1]['line'] ?? 'unknown'
        ]);
    }
    
    // Ensure required fields exist
    $error_context = array_merge([
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => 'error',
        'message' => 'Unknown error',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        'file' => 'unknown',
        'line' => 'unknown'
    ], $error_context);
    
    // Ensure logs directory exists
    $log_dir = __DIR__ . '/logs';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Sanitize sensitive information
    $sanitized_context = sanitizeLogData($error_context);
    
    // Format log entry
    $log_entry = sprintf(
        "[%s] %s: %s in %s on line %s | IP: %s | URI: %s\n",
        $sanitized_context['timestamp'],
        $sanitized_context['type'] ?? 'error',
        $sanitized_context['message'],
        isset($sanitized_context['file']) ? basename($sanitized_context['file']) : 'unknown', 
        $sanitized_context['line'] ?? 'unknown',
        $sanitized_context['ip'],
        $sanitized_context['uri']
    );
    
    // Write to log file
    $log_file = $log_dir . '/error_' . date('Y-m-d') . '.log';
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    
    // Rotate logs if needed
    if (file_exists($log_file) && filesize($log_file) > 10 * 1024 * 1024) {
        rotateLogs($log_file);
    }
}

/**
 * Configure error handling settings
 */
function setupErrorHandling() {
    // Set error reporting based on environment
    $is_production = ($_ENV['ENVIRONMENT'] ?? 'development') === 'production';
    
    if ($is_production) {
        // Production: Log errors but don't display them
        error_reporting(E_ALL);
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        ini_set('log_errors', '1');
        ini_set('error_log', __DIR__ . '/logs/error.log');
    } else {
        // Development: Display errors for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        ini_set('log_errors', '1');
        ini_set('error_log', __DIR__ . '/logs/error.log');
    }
    
    // Set custom error and exception handlers
    set_error_handler('customErrorHandler');
    set_exception_handler('customExceptionHandler');
    register_shutdown_function('fatalErrorHandler');
}

/**
 * Custom error handler
 * 
 * @param int $severity Error severity level
 * @param string $message Error message
 * @param string $file File where error occurred
 * @param int $line Line number where error occurred
 * @return bool
 */
function customErrorHandler($severity, $message, $file, $line) {
    // Don't handle errors that are suppressed with @
    if (!(error_reporting() & $severity)) {
        return false;
    }
    
    // Create error context
    $error_context = [
        'severity' => $severity,
        'message' => $message,
        'file' => $file,
        'line' => $line,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
    ];
    
    // Log the error
    logError($error_context);
    
    // In production, show user-friendly error page
    $is_production = ($_ENV['ENVIRONMENT'] ?? 'development') === 'production';
    if ($is_production && $severity & (E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR)) {
        showErrorPage('An error occurred. Please try again later.');
        exit;
    }
    
    return false; // Let PHP handle the error normally in development
}

/**
 * Custom exception handler
 * 
 * @param Exception|Throwable $exception
 */
function customExceptionHandler($exception) {
    $error_context = [
        'type' => 'exception',
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString(),
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
    ];
    
    // Log the exception
    logError($error_context);
    
    // Show user-friendly error page
    $is_production = ($_ENV['ENVIRONMENT'] ?? 'development') === 'production';
    if ($is_production) {
        showErrorPage('An unexpected error occurred. Please try again later.');
    } else {
        // In development, show detailed error
        echo "<h1>Uncaught Exception</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $exception->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
    }
    exit;
}

/**
 * Fatal error handler (shutdown function)
 */
function fatalErrorHandler() {
    $error = error_get_last();
    
    if ($error !== null && $error['type'] & (E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE)) {
        $error_context = [
            'type' => 'fatal',
            'message' => $error['message'],
            'file' => $error['file'],
            'line' => $error['line'],
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ];
        
        // Log the fatal error
        logError($error_context);
        
        // Show user-friendly error page
        $is_production = ($_ENV['ENVIRONMENT'] ?? 'development') === 'production';
        if ($is_production) {
            showErrorPage('The service is temporarily unavailable. Please try again later.');
        }
    }
}

/**
 * Sanitize log data to prevent log injection and remove sensitive info
 * 
 * @param array $data Log data to sanitize
 * @return array Sanitized data
 */
function sanitizeLogData($data) {
    $sanitized = [];
    
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            // Remove newlines and control characters to prevent log injection
            $value = preg_replace('/[\r\n\t]/', ' ', $value);
            // Limit length to prevent log flooding
            $value = substr($value, 0, 1000);
        }
        
        // Don't log sensitive information
        if (!in_array($key, ['password', 'token', 'session_id'])) {
            $sanitized[$key] = $value;
        }
    }
    
    return $sanitized;
}

/**
 * Rotate log files to prevent them from growing too large
 * 
 * @param string $log_file Path to log file
 */
function rotateLogs($log_file) {
    $backup_file = $log_file . '.' . date('Y-m-d-H-i-s') . '.bak';
    rename($log_file, $backup_file);
    
    // Keep only last 10 backup files
    $log_dir = dirname($log_file);
    $backup_files = glob($log_dir . '/*.bak');
    if (count($backup_files) > 10) {
        sort($backup_files);
        $files_to_delete = array_slice($backup_files, 0, count($backup_files) - 10);
        foreach ($files_to_delete as $file) {
            unlink($file);
        }
    }
}

/**
 * Show user-friendly error page
 * 
 * @param string $message Error message to display
 */
function showErrorPage($message = 'An error occurred') {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - BookHaven</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="max-w-md mx-auto text-center bg-white rounded-lg shadow-lg p-8">
            <div class="text-red-500 mb-4">
                <i class="fas fa-exclamation-triangle text-6xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Oops! Something went wrong</h1>
            <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($message); ?></p>
            <div class="space-y-3">
                <a href="javascript:history.back()" 
                   class="inline-block w-full px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Go Back
                </a>
                <a href="index.php" 
                   class="inline-block w-full px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Home Page
                </a>
            </div>
            <p class="text-sm text-gray-500 mt-6">
                If this problem persists, please contact support.
            </p>
        </div>
    </body>
    </html>
    <?php
}

/**
 * Log database errors securely
 * 
 * @param string $error Database error message
 * @param string $query SQL query that failed (optional)
 */
function logDatabaseError($error, $query = null) {
    $error_context = [
        'type' => 'database',
        'message' => $error,
        'query' => $query ? preg_replace('/\s+/', ' ', trim($query)) : null,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
    ];
    
    logError($error_context);
}

/**
 * Handle application-specific errors
 * 
 * @param string $error_type Type of error
 * @param string $message Error message
 * @param array $context Additional context
 */
function handleAppError($error_type, $message, $context = []) {
    $error_context = array_merge([
        'type' => $error_type,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
    ], $context);
    
    logError($error_context);
    
    // Show appropriate response based on error type
    switch ($error_type) {
        case 'validation':
            // Validation errors should be shown to users
            return false;
            
        case 'authentication':
            // Redirect to login for auth errors
            header('Location: login.php');
            exit;
            
        case 'authorization':
            // Show access denied
            http_response_code(403);
            showErrorPage('Access denied. You do not have permission to access this resource.');
            exit;
            
        case 'not_found':
            // Show 404 page
            http_response_code(404);
            showErrorPage('The requested resource was not found.');
            exit;
            
        default:
            // Generic error handling
            $is_production = ($_ENV['ENVIRONMENT'] ?? 'development') === 'production';
            if ($is_production) {
                showErrorPage('An error occurred. Please try again later.');
                exit;
            }
            break;
    }
}

/**
 * Secure error reporting for API endpoints
 * 
 * @param string $error_type Error type
 * @param string $message Error message
 * @param int $http_code HTTP status code
 */
function apiErrorResponse($error_type, $message, $http_code = 400) {
    http_response_code($http_code);
    header('Content-Type: application/json');
    
    $response = [
        'error' => true,
        'type' => $error_type,
        'message' => $message,
        'timestamp' => date('c')
    ];
    
    // Log the API error
    handleAppError('api_' . $error_type, $message, [
        'http_code' => $http_code,
        'response' => $response
    ]);
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

// Initialize error handling when this file is included
setupErrorHandling();

?>
