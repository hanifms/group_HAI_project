<?php

namespace App\Helpers;

/**
 * SecurityHelper - Security Utilities for MyTravel Application
 *
 * This class provides security-related utility methods to ensure
 * proper validation and sanitization of user inputs throughout the application.
 *
 * @package App\Helpers
 */
class SecurityHelper
{
    /**
     * Sanitize user input to prevent XSS attacks
     *
     * @param string $input The user input to be sanitized
     * @return string The sanitized string
     */
    public static function sanitizeInput($input)
    {
        if (is_string($input)) {
            // Remove any HTML and PHP tags
            $sanitized = strip_tags($input);
            // Convert special characters to HTML entities
            return htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        }

        return $input;
    }

    /**
     * Validate email address
     *
     * @param string $email The email address to validate
     * @return boolean True if valid, false otherwise
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Generate a random token for CSRF protection
     *
     * @param int $length The length of the token
     * @return string The generated token
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Check if a password meets the security requirements
     * - At least 8 characters
     * - Contains at least one uppercase letter
     * - Contains at least one lowercase letter
     * - Contains at least one number
     * - Contains at least one special character
     *
     * @param string $password The password to validate
     * @return array An array with validation status and message
     */
    public static function validatePassword($password)
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Log security-related events
     *
     * @param string $type The type of event (e.g., 'login_attempt', 'password_reset')
     * @param array $data Additional data to log
     * @return void
     */
    public static function logSecurityEvent($type, array $data = [])
    {
        $data = array_merge([
            'timestamp' => time(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'type' => $type,
        ], $data);

        // Log to the security log file
        logger()->channel('security')->info(json_encode($data));
    }

    /**
     * Check if a file upload is safe
     *
     * @param object $file The uploaded file object
     * @param array $allowedExtensions Array of allowed file extensions
     * @param int $maxSize Maximum file size in bytes
     * @return array An array with validation status and message
     */
    public static function validateFileUpload($file, array $allowedExtensions, $maxSize = 2097152) // 2MB default
    {
        if (!$file->isValid()) {
            return [
                'valid' => false,
                'message' => 'Invalid file upload'
            ];
        }

        $extension = strtolower($file->getClientOriginalExtension());

        // Check file extension
        if (!in_array($extension, $allowedExtensions)) {
            return [
                'valid' => false,
                'message' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedExtensions)
            ];
        }

        // Check file size
        if ($file->getSize() > $maxSize) {
            return [
                'valid' => false,
                'message' => 'File size exceeds the maximum allowed size of ' . self::formatBytes($maxSize)
            ];
        }

        return [
            'valid' => true,
            'message' => 'File is valid'
        ];
    }

    /**
     * Format bytes to human-readable format
     *
     * @param int $bytes The size in bytes
     * @param int $precision The precision to use
     * @return string The formatted size
     */
    private static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
