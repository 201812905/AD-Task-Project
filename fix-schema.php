<?php
require_once __DIR__ . '/bootstrap.php';

echo "<h1>Database Schema Fix</h1>";

try {
    // Check current schema
    echo "<h2>Current Schema Check:</h2>";
    
    // Check products table
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'products' AND column_name = 'id'");
    $productIdType = $stmt->fetch();
    echo "<p>Products.id type: " . ($productIdType['data_type'] ?? 'NOT FOUND') . "</p>";
    
    // Check order_items table
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'order_items' AND column_name = 'product_id'");
    $orderItemProductIdType = $stmt->fetch();
    echo "<p>Order_items.product_id type: " . ($orderItemProductIdType['data_type'] ?? 'NOT FOUND') . "</p>";
    
    // Check orders table
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'orders' AND column_name = 'id'");
    $orderIdType = $stmt->fetch();
    echo "<p>Orders.id type: " . ($orderIdType['data_type'] ?? 'NOT FOUND') . "</p>";
    
    // If there's a mismatch, we need to recreate tables
    if ($productIdType && $productIdType['data_type'] !== 'uuid') {
        echo "<h2>Fixing Products Table Schema:</h2>";
        
        // Drop and recreate products table
        $pdo->exec("DROP TABLE IF EXISTS order_items CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS products CASCADE");
        
        // Recreate with correct schema
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
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
        
        // Insert sample data
        $pdo->exec("
            INSERT INTO products (name, description, price, stock_quantity, category, image_url) VALUES
            ('Sacred Health Tonic', 'Blessed healing potion infused with the Omnissiah''s wisdom', 299.99, 50, 'Remedies', '/assets/img/products/tonic.jpg'),
            ('Imperial Medkit', 'Complete field medical kit approved by the Adeptus Mechanicus', 1999.99, 25, 'Medical Equipment', '/assets/img/products/medkit.jpg'),
            ('Tech-Priest Vitamins', 'Essential nutrients for maintaining sacred flesh and blessed machinery', 499.99, 100, 'Supplements', '/assets/img/products/vitamins.jpg'),
            ('Omnissiah Pain Relief', 'Divine analgesic for both organic and mechanical ailments', 399.99, 75, 'Remedies', '/assets/img/products/pain-relief.jpg'),
            ('Sacred Thermometer', 'Precision temperature measurement device', 99.99, 80, 'Equipment', '/assets/img/products/thermometer.jpg'),
            ('Blessed Bandages', 'Sterile bandages blessed by the Machine God', 149.99, 200, 'Medical Equipment', '/assets/img/products/bandages.jpg')
            ON CONFLICT (id) DO NOTHING
        ");
        
        // Recreate order_items table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS order_items (
                id SERIAL PRIMARY KEY,
                order_id INT REFERENCES orders(id) ON DELETE CASCADE,
                product_id UUID REFERENCES products(id) ON DELETE SET NULL,
                product_name TEXT NOT NULL,
                quantity INT NOT NULL,
                price NUMERIC(10,2) NOT NULL
            )
        ");
        
        echo "<p style='color: green;'>✅ Database schema fixed successfully!</p>";
    } else {
        echo "<p style='color: green;'>✅ Database schema is correct!</p>";
    }
    
    // Show current products
    echo "<h2>Current Products:</h2>";
    $stmt = $pdo->query("SELECT id, name, price FROM products LIMIT 5");
    $products = $stmt->fetchAll();
    foreach ($products as $product) {
        echo "<p>ID: {$product['id']} - Name: {$product['name']} - Price: {$product['price']}</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
