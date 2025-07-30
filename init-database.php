<?php
/**
 * Database Migration and Seeder Script
 * Run this file to set up the database with tables and initial data
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/database/database.php';

if (!isset($pdo)) {
    die("Database connection not available. Please check your database configuration.");
}

echo "Starting database initialization...\n";

try {
    // Create users table
    echo "Creating users table...\n";
    $sql = file_get_contents(__DIR__ . '/database/users.model.sql');
    $pdo->exec($sql);
    echo "✓ Users table created\n";
    
    // Create products table
    echo "Creating products table...\n";
    $sql = file_get_contents(__DIR__ . '/database/products.model.sql');
    $pdo->exec($sql);
    echo "✓ Products table created\n";
    
    // Create orders table
    echo "Creating orders table...\n";
    $sql = file_get_contents(__DIR__ . '/database/orders.model.sql');
    $pdo->exec($sql);
    echo "✓ Orders table created\n";
    
    // Create order_items table
    echo "Creating order_items table...\n";
    $sql = file_get_contents(__DIR__ . '/database/order_items.model.sql');
    $pdo->exec($sql);
    echo "✓ Order items table created\n";
    
    // Check if admin user exists, if not create one
    echo "Setting up admin user...\n";
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    
    if ($stmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, first_name, last_name, password, role) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'admin',
            'admin@mechanicus.com',
            'Administrator',
            'User',
            $hashedPassword,
            'admin'
        ]);
        echo "✓ Admin user created (username: admin, password: admin123)\n";
    } else {
        echo "✓ Admin user already exists\n";
    }
    
    // Check if test user exists, if not create one
    echo "Setting up test user...\n";
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['john.smith']);
    
    if ($stmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('p@ssW0rd1234', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, first_name, last_name, password, role) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'john.smith',
            'john.smith@mechanicus.com',
            'John',
            'Smith',
            $hashedPassword,
            'user'
        ]);
        echo "✓ Test user created (username: john.smith, password: p@ssW0rd1234)\n";
    } else {
        echo "✓ Test user already exists\n";
    }
    
    // Add more sample products if they don't exist
    echo "Adding sample products...\n";
    $sampleProducts = [
        [
            'name' => 'Servo-Skull Repair Kit',
            'description' => 'Complete maintenance kit for servo-skull units, blessed by the Mechanicus',
            'price' => 799.99,
            'stock_quantity' => 30,
            'category' => 'Equipment',
            'image_url' => '/assets/img/products/servo-skull-kit.jpg'
        ],
        [
            'name' => 'Incense of Machine Blessing',
            'description' => 'Sacred incense to appease the machine spirits during maintenance rituals',
            'price' => 149.99,
            'stock_quantity' => 200,
            'category' => 'Rituals',
            'image_url' => '/assets/img/products/incense.jpg'
        ],
        [
            'name' => 'Cogitator Interface Enhancer',
            'description' => 'Improves neural link efficiency with machine systems',
            'price' => 2999.99,
            'stock_quantity' => 10,
            'category' => 'Augmentation',
            'image_url' => '/assets/img/products/cogitator.jpg'
        ]
    ];
    
    foreach ($sampleProducts as $product) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name = ?");
        $stmt->execute([$product['name']]);
        
        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("
                INSERT INTO products (name, description, price, stock_quantity, category, image_url) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $product['name'],
                $product['description'],
                $product['price'],
                $product['stock_quantity'],
                $product['category'],
                $product['image_url']
            ]);
            echo "✓ Added product: " . $product['name'] . "\n";
        }
    }
    
    echo "\nDatabase initialization completed successfully!\n";
    echo "You can now access the admin panel with:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
