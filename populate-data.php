<?php
require_once __DIR__ . '/bootstrap.php';

echo "<h1>Populate Sample Data</h1>";

try {
    // Create more users
    echo "<h2>Creating sample users...</h2>";
    
    $users = [
        ['john_doe', 'john@email.com', 'John', 'Doe', 'password123', 'user'],
        ['jane_smith', 'jane@email.com', 'Jane', 'Smith', 'password123', 'user'],
        ['tech_adept', 'tech@mechanicus.com', 'Tech', 'Adept', 'password123', 'admin'],
        ['servitor_01', 'servitor@mechanicus.com', 'Servitor', 'Alpha', 'password123', 'user'],
        ['magos_biologis', 'magos@mechanicus.com', 'Magos', 'Biologis', 'password123', 'admin']
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, first_name, last_name, password, role) 
        VALUES (?, ?, ?, ?, ?, ?)
        ON CONFLICT (username) DO NOTHING
    ");
    
    foreach ($users as $user) {
        $hashedPassword = password_hash($user[4], PASSWORD_DEFAULT);
        $stmt->execute([$user[0], $user[1], $user[2], $user[3], $hashedPassword, $user[5]]);
        echo "<p>✅ Created user: {$user[0]} ({$user[2]} {$user[3]})</p>";
    }
    
    // Create some sample orders
    echo "<h2>Creating sample orders...</h2>";
    
    // Get some products first
    $stmt = $pdo->query("SELECT id, name, price FROM products LIMIT 3");
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        echo "<p style='color: red;'>No products found to create orders</p>";
    } else {
        $sampleOrders = [
            [
                'full_name' => 'John Doe',
                'email' => 'john@email.com', 
                'address' => '123 Imperial Ave',
                'city' => 'Hive City',
                'postal_code' => '12345',
                'payment_method' => 'Credit Card',
                'subtotal' => 599.98,
                'tax' => 59.99,
                'shipping' => 50.00,
                'total' => 709.97
            ],
            [
                'full_name' => 'Jane Smith',
                'email' => 'jane@email.com',
                'address' => '456 Tech Street', 
                'city' => 'Forge World',
                'postal_code' => '67890',
                'payment_method' => 'PayPal',
                'subtotal' => 1299.99,
                'tax' => 129.99,
                'shipping' => 75.00,
                'total' => 1504.98
            ],
            [
                'full_name' => 'Tech Adept',
                'email' => 'tech@mechanicus.com',
                'address' => '789 Machine Ave',
                'city' => 'Mars',
                'postal_code' => '00001',
                'payment_method' => 'Bank Transfer',
                'subtotal' => 399.99,
                'tax' => 39.99,
                'shipping' => 25.00,
                'total' => 464.98
            ]
        ];
        
        $orderStmt = $pdo->prepare("
            INSERT INTO orders (user_id, full_name, email, address, city, postal_code, payment_method, subtotal, tax, shipping, total, order_date, status)
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'completed')
            RETURNING id
        ");
        
        $itemStmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($sampleOrders as $i => $order) {
            $orderStmt->execute([
                $order['full_name'],
                $order['email'],
                $order['address'],
                $order['city'],
                $order['postal_code'],
                $order['payment_method'],
                $order['subtotal'],
                $order['tax'],
                $order['shipping'],
                $order['total']
            ]);
            
            $result = $orderStmt->fetch(PDO::FETCH_ASSOC);
            $orderId = $result['id'];
            
            // Add some items to each order
            $product = $products[$i % count($products)];
            $itemStmt->execute([
                $orderId,
                $product['id'],
                $product['name'],
                2, // quantity
                $product['price']
            ]);
            
            echo "<p>✅ Created order #{$orderId} for {$order['full_name']} - Total: ₱{$order['total']}</p>";
        }
    }
    
    echo "<h2 style='color: green;'>✅ Sample data created successfully!</h2>";
    
    // Show final counts
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $orderCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $productCount = $stmt->fetchColumn();
    
    echo "<h3>Final counts:</h3>";
    echo "<p>Users: $userCount</p>";
    echo "<p>Orders: $orderCount</p>";
    echo "<p>Products: $productCount</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
