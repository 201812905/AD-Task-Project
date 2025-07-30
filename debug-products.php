<?php
require_once 'bootstrap.php';

echo "<h2>Product Duplication Debug</h2>";

try {
    echo "<h3>Checking for duplicate products...</h3>";
    
    // Check for duplicates by name
    $stmt = $pdo->prepare('SELECT name, COUNT(*) as count FROM products GROUP BY name HAVING COUNT(*) > 1');
    $stmt->execute();
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($duplicates)) {
        echo "<p style='color: green;'>✓ No duplicate product names found in database.</p>";
    } else {
        echo "<p style='color: red;'>❌ Duplicate products found:</p><ul>";
        foreach ($duplicates as $dup) {
            echo "<li>{$dup['name']} (appears {$dup['count']} times)</li>";
        }
        echo "</ul>";
    }
    
    // Show all products
    echo "<h3>All products in database:</h3>";
    $stmt = $pdo->prepare('SELECT id, name, category, price, status FROM products ORDER BY name');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Status</th></tr>";
    
    foreach ($products as $product) {
        echo "<tr>";
        echo "<td>{$product['id']}</td>";
        echo "<td>{$product['name']}</td>";
        echo "<td>{$product['category']}</td>";
        echo "<td>{$product['price']}</td>";
        echo "<td>{$product['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p><strong>Total products: " . count($products) . "</strong></p>";
    
    // Test the getProducts() function
    require_once 'utils/cart.util.php';
    echo "<h3>Testing getProducts() function:</h3>";
    $cartProducts = getProducts();
    echo "<p>Cart products count: " . count($cartProducts) . "</p>";
    
    foreach ($cartProducts as $id => $product) {
        echo "<div style='border: 1px solid #ccc; margin: 5px; padding: 10px;'>";
        echo "<strong>ID:</strong> {$product['id']}<br>";
        echo "<strong>Name:</strong> {$product['name']}<br>";
        echo "<strong>Category:</strong> {$product['category']}<br>";
        echo "<strong>Price:</strong> {$product['price']}<br>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
