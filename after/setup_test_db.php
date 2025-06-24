<?php

// Script to create and setup the testing database

// Get the database settings from .env.testing
$envFile = __DIR__ . '/.env.testing';
$envSettings = parse_env_file($envFile);

// Extract database settings
$connection = $envSettings['DB_CONNECTION'] ?? 'mysql';
$host = $envSettings['DB_HOST'] ?? '127.0.0.1';
$port = $envSettings['DB_PORT'] ?? '3306';
$username = $envSettings['DB_USERNAME'] ?? 'root';
$password = $envSettings['DB_PASSWORD'] ?? '';
$dbname = $envSettings['DB_DATABASE'] ?? 'mytravelv2_testing';

echo "Setting up testing environment with database: $dbname\n";
echo "Database connection: $connection\n";

if ($connection === 'mysql') {
    try {
        // Connect to MySQL without specifying a database
        $dsn = "mysql:host=$host;port=$port";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create the database if it doesn't exist
        echo "Creating database $dbname if it doesn't exist...\n";
        $pdo->exec("DROP DATABASE IF EXISTS `$dbname`;");
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

        echo "Database '$dbname' created successfully.\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
} elseif ($connection === 'sqlite') {
    $databasePath = __DIR__ . '/database/' . $dbname;
    if (file_exists($databasePath)) {
        unlink($databasePath);
    }
    touch($databasePath);
    echo "SQLite database file created at: $databasePath\n";
} else {
    echo "Unsupported database connection: $connection\n";
    echo "This script only supports MySQL and SQLite\n";
    exit(1);
}

// Now run the migrations
echo "Running migrations...\n";
passthru('php artisan migrate:fresh --env=testing --seed');

echo "\nDatabase '$dbname' is now ready for testing.\n";
echo "Run tests using: php artisan test\n";

// Helper function to parse .env file
function parse_env_file($file) {
    $settings = [];

    if (!file_exists($file)) {
        echo "Warning: Environment file not found: $file\n";
        return $settings;
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments and empty lines
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        // Split by first = sign
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove quotes if they exist
        if (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
            $value = substr($value, 1, -1);
        }

        $settings[$key] = $value;
    }

    return $settings;
}
