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

echo "Dropping old tables…\n";

// Drop tables in reverse dependency order to avoid foreign key conflicts
$tablesToDrop = [
    'order_items',    // Depends on orders
    'orders',         // Depends on users
    'users'           // Base table
];

foreach ($tablesToDrop as $table) {
    try {
        $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
        echo "✅ Dropped table {$table}\n";
    } catch (PDOException $e) {
        echo "⚠️  Warning dropping {$table}: " . $e->getMessage() . "\n";
    }
}

// Define models in correct dependency order
$models = [
    'users.model.sql',          // No dependencies - create first
    'orders.model.sql',         // Depends on users
    'order_items.model.sql'     // Depends on orders (note: corrected filename)
];

foreach ($models as $filename) {
    $path = DATABASE_PATH . '/' . $filename;
    
    if (!file_exists($path)) {
        echo "⚠️  Warning: Model file not found: {$filename}\n";
        continue;
    }
    
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

echo "✅ Migration complete!\n";