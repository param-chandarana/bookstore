<?php
/**
 * Input Sanitization and Validation Helper Functions
 * 
 * This file provides comprehensive input sanitization and validation
 * for the BookHaven application to prevent security vulnerabilities.
 */

/**
 * Sanitize string input
 * 
 * @param string $input The input to sanitize
 * @param bool $allow_html Whether to allow HTML tags (default: false)
 * @return string Sanitized string
 */
function sanitizeString($input, $allow_html = false) {
    if (!is_string($input)) {
        return '';
    }
    
    $input = str_replace(chr(0), '', $input);
    
    $input = trim($input);
    
    if ($allow_html) {
        $allowed_tags = '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6>';
        $input = strip_tags($input, $allowed_tags);
    } else {
        $input = strip_tags($input);
    }
    
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    return $input;
}

/**
 * Sanitize email input
 * 
 * @param string $email The email to sanitize and validate
 * @return string|false Sanitized email or false if invalid
 */
function sanitizeEmail($email) {
    if (!is_string($email)) {
        return false;
    }
    
    $email = strtolower(trim($email));
    
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    if (strlen($email) > 254 || empty($email)) {
        return false;
    }
    
    return $email;
}

/**
 * Sanitize numeric input
 * 
 * @param mixed $input The input to sanitize
 * @param bool $allow_decimal Whether to allow decimal numbers (default: false)
 * @param float $min Minimum allowed value
 * @param float $max Maximum allowed value
 * @return int|float|false Sanitized number or false if invalid
 */
function sanitizeNumber($input, $allow_decimal = false, $min = null, $max = null) {
    if (is_string($input)) {
        $input = trim($input);
    }
    
    if ($allow_decimal) {
        $number = filter_var($input, FILTER_VALIDATE_FLOAT);
    } else {
        $number = filter_var($input, FILTER_VALIDATE_INT);
    }
    
    if ($number === false) {
        return false;
    }
    
    if ($min !== null && $number < $min) {
        return false;
    }
    
    if ($max !== null && $number > $max) {
        return false;
    }
    
    return $number;
}

/**
 * Sanitize phone number
 * 
 * @param string $phone The phone number to sanitize
 * @return string|false Sanitized phone number or false if invalid
 */
function sanitizePhone($phone) {
    if (!is_string($phone)) {
        return false;
    }
    
    $phone = preg_replace('/[^\d\+\s\-\(\)]/', '', trim($phone));
    
    $phone = preg_replace('/\s+/', ' ', $phone);
    
    $digits_only = preg_replace('/[^\d]/', '', $phone);
    if (strlen($digits_only) < 10 || strlen($digits_only) > 15) {
        return false;
    }
    
    return $phone;
}

/**
 * Sanitize filename for uploads
 * 
 * @param string $filename The filename to sanitize
 * @return string Sanitized filename
 */
function sanitizeFilename($filename) {
    if (!is_string($filename)) {
        return 'file';
    }
    
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    
    $basename = preg_replace('/[^a-zA-Z0-9\-_]/', '', $basename);
    
    $basename = substr($basename, 0, 50);
    
    if (empty($basename)) {
        $basename = 'file';
    }
    
    return $basename . '.' . $extension;
}

/**
 * Validate and sanitize password
 * 
 * @param string $password The password to validate
 * @param int $min_length Minimum password length (default: 8)
 * @return array ['valid' => bool, 'message' => string, 'password' => string]
 */
function validatePassword($password, $min_length = 8) {
    $result = ['valid' => false, 'message' => '', 'password' => ''];
    
    if (!is_string($password)) {
        $result['message'] = 'Password must be a string';
        return $result;
    }
    
    if (strlen($password) < $min_length) {
        $result['message'] = "Password must be at least {$min_length} characters long";
        return $result;
    }
    
    if (strlen($password) > 128) {
        $result['message'] = 'Password is too long (max 128 characters)';
        return $result;
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $result['message'] = 'Password must contain at least one uppercase letter';
        return $result;
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $result['message'] = 'Password must contain at least one lowercase letter';
        return $result;
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $result['message'] = 'Password must contain at least one number';
        return $result;
    }
    
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $result['message'] = 'Password must contain at least one special character';
        return $result;
    }
    
    $result['valid'] = true;
    $result['password'] = $password;
    $result['message'] = 'Password is valid';
    
    return $result;
}

/**
 * Sanitize address input
 * 
 * @param string $address The address to sanitize
 * @return string Sanitized address
 */
function sanitizeAddress($address) {
    if (!is_string($address)) {
        return '';
    }
    
    $address = preg_replace('/[^\w\s\-\,\.\#\/]/', '', trim($address));
    
    $address = substr($address, 0, 500);
    
    return htmlspecialchars($address, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Comprehensive input validation for forms
 * 
 * @param array $inputs Array of inputs to validate
 * @param array $rules Validation rules
 * @return array ['valid' => bool, 'errors' => array, 'data' => array]
 */
function validateInputs($inputs, $rules) {
    $result = ['valid' => true, 'errors' => [], 'data' => []];
    
    foreach ($rules as $field => $rule) {
        $value = $inputs[$field] ?? '';
        $field_valid = true;
        $error_message = '';
        
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            $field_valid = false;
            $error_message = ucfirst($field) . ' is required';
        } elseif (!empty($value)) {
            switch ($rule['type']) {
                case 'email':
                    $sanitized = sanitizeEmail($value);
                    if ($sanitized === false) {
                        $field_valid = false;
                        $error_message = 'Invalid email format';
                    } else {
                        $result['data'][$field] = $sanitized;
                    }
                    break;
                    
                case 'string':
                    $allow_html = $rule['allow_html'] ?? false;
                    $max_length = $rule['max_length'] ?? 1000;
                    $sanitized = sanitizeString($value, $allow_html);
                    
                    if (strlen($sanitized) > $max_length) {
                        $field_valid = false;
                        $error_message = ucfirst($field) . " must be less than {$max_length} characters";
                    } else {
                        $result['data'][$field] = $sanitized;
                    }
                    break;
                    
                case 'number':
                    $allow_decimal = $rule['decimal'] ?? false;
                    $min = $rule['min'] ?? null;
                    $max = $rule['max'] ?? null;
                    $sanitized = sanitizeNumber($value, $allow_decimal, $min, $max);
                    
                    if ($sanitized === false) {
                        $field_valid = false;
                        $error_message = 'Invalid ' . $field . ' format';
                    } else {
                        $result['data'][$field] = $sanitized;
                    }
                    break;
                    
                case 'phone':
                    $sanitized = sanitizePhone($value);
                    if ($sanitized === false) {
                        $field_valid = false;
                        $error_message = 'Invalid phone number format';
                    } else {
                        $result['data'][$field] = $sanitized;
                    }
                    break;
                    
                case 'password':
                    $min_length = $rule['min_length'] ?? 8;
                    $validation = validatePassword($value, $min_length);
                    if (!$validation['valid']) {
                        $field_valid = false;
                        $error_message = $validation['message'];
                    } else {
                        $result['data'][$field] = $validation['password'];
                    }
                    break;
                    
                default:
                    $result['data'][$field] = sanitizeString($value);
            }
        } else {
            $result['data'][$field] = '';
        }
        
        if (!$field_valid) {
            $result['valid'] = false;
            $result['errors'][$field] = $error_message;
        }
    }
    
    return $result;
}

?>
