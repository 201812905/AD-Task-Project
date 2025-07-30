<?php
require_once __DIR__ . '/bootstrap.php';

echo "<h1>Complete Database Reset</h1>";

try {
    echo "<h2>Dropping all tables...</h2>";
    
    // Drop all tables in correct order (to handle foreign key constraints)
    $pdo->exec("DROP TABLE IF EXISTS order_items CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS orders CASCADE");  
    $pdo->exec("DROP TABLE IF EXISTS products CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS users CASCADE");
    
    echo "<p>✅ All tables dropped</p>";
    
    echo "<h2>Creating tables with correct schema...</h2>";
    
    // Create users table
    $pdo->exec("
        CREATE TABLE users (
            id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
            username VARCHAR(225) NOT NULL UNIQUE,
            email VARCHAR(225) UNIQUE,
            first_name VARCHAR(225) NOT NULL,
            middle_name VARCHAR(225),
            last_name VARCHAR(225) NOT NULL,
            password VARCHAR(225) NOT NULL,
            role VARCHAR(225) NOT NULL DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✅ Users table created</p>";
    
    // Create products table
    $pdo->exec("
        CREATE TABLE products (
            id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10, 2) NOT NULL,
            stock_quantity INTEGER NOT NULL DEFAULT 0,
            category VARCHAR(100),
            image_url VARCHAR(500),
            status VARCHAR(50) NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✅ Products table created</p>";
    
    // Create orders table  
    $pdo->exec("
        CREATE TABLE orders (
            id SERIAL PRIMARY KEY,
            user_id UUID REFERENCES users(id) ON DELETE SET NULL,
            full_name TEXT NOT NULL,
            email TEXT NOT NULL,
            address TEXT NOT NULL,
            city TEXT NOT NULL,
            postal_code TEXT NOT NULL,
            payment_method TEXT NOT NULL,
            subtotal NUMERIC(10,2) NOT NULL,
            tax NUMERIC(10,2) NOT NULL,
            shipping NUMERIC(10,2) NOT NULL,
            total NUMERIC(10,2) NOT NULL,
            status VARCHAR(50) DEFAULT 'pending',
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✅ Orders table created</p>";
    
    // Create order_items table
    $pdo->exec("
        CREATE TABLE order_items (
            id SERIAL PRIMARY KEY,
            order_id INT REFERENCES orders(id) ON DELETE CASCADE,
            product_id UUID REFERENCES products(id) ON DELETE SET NULL,
            product_name TEXT NOT NULL,
            quantity INT NOT NULL,
            price NUMERIC(10,2) NOT NULL
        )
    ");
    echo "<p>✅ Order_items table created</p>";
    
    // Insert sample products
    $pdo->exec("
        INSERT INTO products (name, description, price, stock_quantity, category, image_url) VALUES
        ('Sacred Health Tonic', 'Blessed healing potion infused with the Omnissiah''s wisdom', 299.99, 50, 'Remedies', '/assets/img/products/tonic.jpg'),
        ('Imperial Medkit', 'Complete field medical kit approved by the Adeptus Mechanicus', 1999.99, 25, 'Medical Equipment', '/assets/img/products/medkit.jpg'),
        ('Tech-Priest Vitamins', 'Essential nutrients for maintaining sacred flesh and blessed machinery', 499.99, 100, 'Supplements', '/assets/img/products/vitamins.jpg'),
        ('Omnissiah Pain Relief', 'Divine analgesic for both organic and mechanical ailments', 399.99, 75, 'Remedies', '/assets/img/products/pain-relief.jpg'),
        ('Sacred Thermometer', 'Precision temperature measurement device', 99.99, 80, 'Equipment', '/assets/img/products/thermometer.jpg'),
        ('Blessed Bandages', 'Sterile bandages blessed by the Machine God', 149.99, 200, 'Medical Equipment', '/assets/img/products/bandages.jpg')
    ");
    echo "<p>✅ Sample products inserted</p>";
    
    // Insert a test admin user
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (username, email, first_name, last_name, password, role) VALUES
        ('admin', 'admin@mechanicus.com', 'Tech', 'Priest', '$hashedPassword', 'admin')
    ");
    echo "<p>✅ Admin user created (username: admin, password: admin123)</p>";
    
    echo "<h2>Verification:</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $productCount = $stmt->fetchColumn();
    echo "<p>Products in database: $productCount</p>";
    
    $stmt = $pdo->query("SELECT id, name FROM products LIMIT 3");
    $products = $stmt->fetchAll();
    echo "<p>Sample product IDs:</p><ul>";
    foreach ($products as $product) {
        echo "<li>{$product['id']} - {$product['name']}</li>";
    }
    echo "</ul>";
    
    echo "<h2 style='color: green;'>✅ Database reset complete!</h2>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
