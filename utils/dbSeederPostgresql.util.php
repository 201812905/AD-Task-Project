<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Composer bootstrap
require_once 'bootstrap.php';

// 3) envSetter
$typeConfig = require_once UTILS_PATH . '/envSetter.util.php';

// ✅ Load your dummy users
$users = require_once DUMMIES_PATH . '/users.staticDatas.php';

// 4) Connect to PostgreSQL
$host = $typeConfig['pgHost'];
$port = $typeConfig['pgPort'];
$username = $typeConfig['pgUser'];
$password = $typeConfig['pgPass'];
$dbname = $typeConfig['pgDb'];

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Seed logic starts here...
echo "Seeding users…\n";

// Updated query to match your actual table structure (no email, includes middle_name)
$stmt = $pdo->prepare("
    INSERT INTO users (username, first_name, middle_name, last_name, password, role)
    VALUES (:username, :first_name, :middle_name, :last_name, :password, :role)
");

foreach ($users as $u) {
    try {
        $stmt->execute([
            ':username' => $u['username'],
            ':first_name' => $u['first_name'] ?? '',
            ':middle_name' => $u['middle_name'] ?? '', // Handle if not in dummy data
            ':last_name' => $u['last_name'] ?? '',
            ':password' => password_hash($u['password'], PASSWORD_DEFAULT),
            ':role' => $u['role'] ?? 'user', // Default role if not specified
        ]);
        echo "✅ Inserted user: {$u['username']}\n";
    } catch (PDOException $e) {
        echo "❌ Failed to insert {$u['username']}: " . $e->getMessage() . "\n";
        // Show what data we're trying to insert for debugging
        echo "   Data: " . json_encode([
            'username' => $u['username'],
            'first_name' => $u['first_name'] ?? '',
            'middle_name' => $u['middle_name'] ?? '',
            'last_name' => $u['last_name'] ?? '',
            'role' => $u['role'] ?? 'user'
        ]) . "\n";
    }
}

echo "✅ PostgreSQL seeding complete!\n";