<?php
/**
 * Database Connection Configuration
 * Connects to PostgreSQL database using Docker configuration
 */

// Database configuration from docker-compose.yaml
$host = 'host.docker.internal';
$dbname = 'project_db_pg';  
$user = 'user';      
$pass = 'password';   
$port = '5112';
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Set timezone
    $pdo->exec("SET timezone = 'Asia/Manila'");
    
    error_log("✅ Database connected successfully to $dbname");
    
} catch (PDOException $e) {
    error_log("❌ DB Connection Failed: " . $e->getMessage());
    
    // In production, you might want to show a generic error message
    // For development, we'll show the actual error
    if (php_sapi_name() === 'cli') {
        echo "Database connection failed: " . $e->getMessage() . "\n";
        echo "Make sure PostgreSQL is running and accessible.\n";
        echo "Check docker-compose.yaml configuration.\n";
    }
    
    // Don't die immediately, let the application handle it gracefully
    $pdo = null;
}
