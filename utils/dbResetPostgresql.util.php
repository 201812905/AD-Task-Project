<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Bootstrap for BASE_PATH, etc.
require_once 'bootstrap.php';

// 3) Env setter
$typeConfig = require_once UTILS_PATH . '/envSetter.util.php';

// PostgreSQL config
$host = $typeConfig['pgHost'];
$port = $typeConfig['pgPort'];
$username = $typeConfig['pgUser'];
$password = $typeConfig['pgPass'];
$dbname = $typeConfig['pgDb'];

// Connect to PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Drop existing tables in reverse order (to handle foreign keys)
echo "Dropping existing tables...\n";
$tablesToDrop = ['orders_items', 'orders', 'users'];
foreach ($tablesToDrop as $table) {
    try {
        $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
        echo "✅ Dropped table {$table}\n";
    } catch (PDOException $e) {
        echo "⚠️  Warning dropping {$table}: " . $e->getMessage() . "\n";
    }
}

// Define the correct order for table creation (dependencies first)
$orderedFiles = [
    'users.model.sql',           // No dependencies
    'orders.model.sql',          // Depends on users
    'order_items.model.sql'      // Depends on orders
];

// Build full paths and check if files exist
$modelFiles = [];
foreach ($orderedFiles as $filename) {
    $fullPath = DATABASE_PATH . '/' . $filename;
    if (file_exists($fullPath)) {
        $modelFiles[] = $fullPath;
    } else {
        echo "⚠️  Warning: Expected file not found: $filename\n";
    }
}

// Also check for any additional .model.sql files not in our list
$allModelFiles = glob(DATABASE_PATH . '/*.model.sql');
foreach ($allModelFiles as $file) {
    $filename = basename($file);
    if (!in_array($filename, $orderedFiles) && !in_array($file, $modelFiles)) {
        echo "⚠️  Found additional model file: $filename (adding to end)\n";
        $modelFiles[] = $file;
    }
}

if (empty($modelFiles)) {
    echo "⚠️  No .model.sql files found in " . DATABASE_PATH . "\n";
    echo "Available files:\n";
    $allFiles = glob(DATABASE_PATH . '/*');
    foreach ($allFiles as $file) {
        echo "  - " . basename($file) . "\n";
    }
    echo "✅ PostgreSQL reset complete (no schemas to apply)!\n";
    exit(0);
}

echo "Found " . count($modelFiles) . " model files:\n";
foreach ($modelFiles as $file) {
    echo "  - " . basename($file) . "\n";
}

foreach ($modelFiles as $path) {
    $filename = basename($path);
    echo "Applying schema from {$path}…\n";

    $sql = file_get_contents($path);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read {$path}");
    }

    try {
        $pdo->exec($sql);
        echo "✅ Creation Success from {$path}\n";
    } catch (PDOException $e) {
        echo "❌ Creation Failed from {$path}: " . $e->getMessage() . "\n";
        throw $e;
    }
}

echo "✅ PostgreSQL reset complete!\n";