<?php
require_once __DIR__ . '/bootstrap.php';

echo "<h1>Database Debug - Users and Orders</h1>";

try {
    // Check users
    echo "<h2>Users in database:</h2>";
    $stmt = $pdo->query("SELECT id, username, first_name, last_name, email, role FROM users ORDER BY username");
    $users = $stmt->fetchAll();
    
    if (empty($users)) {
        echo "<p>No users found</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td style='font-size: 12px; font-family: monospace;'>{$user['id']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['first_name']} {$user['last_name']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check orders
    echo "<h2>Orders in database:</h2>";
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
    $orders = $stmt->fetchAll();
    
    if (empty($orders)) {
        echo "<p>No orders found</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Customer</th><th>Email</th><th>Total</th><th>Date</th><th>Status</th></tr>";
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>{$order['id']}</td>";
            echo "<td>{$order['full_name']}</td>";
            echo "<td>{$order['email']}</td>";
            echo "<td>₱{$order['total']}</td>";
            echo "<td>{$order['order_date']}</td>";
            echo "<td>{$order['status']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check order items
    echo "<h2>Order items in database:</h2>";
    $stmt = $pdo->query("SELECT * FROM order_items");
    $items = $stmt->fetchAll();
    
    if (empty($items)) {
        echo "<p>No order items found</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Order ID</th><th>Product ID</th><th>Product Name</th><th>Quantity</th><th>Price</th></tr>";
        foreach ($items as $item) {
            echo "<tr>";
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['order_id']}</td>";
            echo "<td style='font-size: 10px;'>{$item['product_id']}</td>";
            echo "<td>{$item['product_name']}</td>";
            echo "<td>{$item['quantity']}</td>";
            echo "<td>₱{$item['price']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test admin getAllOrders function
    echo "<h2>Testing AdminUtil::getAllOrders():</h2>";
    require_once __DIR__ . '/utils/admin.util.php';
    $adminUtil = new AdminUtil();
    $adminOrders = $adminUtil->getAllOrders();
    
    echo "<p>AdminUtil returned " . count($adminOrders) . " orders</p>";
    if (!empty($adminOrders)) {
        echo "<pre>" . print_r($adminOrders, true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
