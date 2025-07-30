<?php
require_once __DIR__ . '/vendor/autoload.php';

// Define a custom session save path
$sessionPath = '/tmp/php_sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
session_save_path($sessionPath);

// Set session cookie parameters before session_start
session_set_cookie_params([
    'lifetime' => 0,             // Session cookie (expires when browser closes)
    'path' => '/',
    'secure' => false,           // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);

// Start the session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Path constants for easier includes
define('BASE_PATH', realpath(__DIR__));
define('UTILS_PATH', realpath(BASE_PATH . '/utils'));
define('DATABASE_PATH', realpath(BASE_PATH . '/database'));
define('HANDLERS_PATH', BASE_PATH . '/handlers');
define('DUMMIES_PATH', BASE_PATH . '/staticDatas/dummies');
chdir(BASE_PATH);

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// PostgreSQL database connection
$host = $_ENV['PG_HOST'] ?? getenv('PG_HOST') ?? 'host.docker.internal';
$port = $_ENV['PG_PORT'] ?? getenv('PG_PORT') ?? '5112';
$dbname = $_ENV['PG_DB'] ?? getenv('PG_DB') ?? 'project_db_pg';
$user = $_ENV['PG_USER'] ?? getenv('PG_USER') ?? 'user';
$pass = $_ENV['PG_PASS'] ?? getenv('PG_PASS') ?? 'password';
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
