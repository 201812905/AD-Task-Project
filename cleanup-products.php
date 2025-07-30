<?php
require_once 'bootstrap.php';

echo "<h2>Product Cleanup Tool</h2>";

if (isset($_GET['cleanup']) && $_GET['cleanup'] === 'true') {
    echo "<h3>Cleaning up duplicate products...</h3>";
    
    try {
        // Find duplicates by name and keep only the oldest one
        $stmt = $pdo->prepare("
            DELETE FROM products 
            WHERE id NOT IN (
                SELECT MIN(id) 
                FROM (SELECT id, name FROM products) AS temp_products 
                GROUP BY name
            )
        ");
        $result = $stmt->execute();
        
        if ($result) {
            echo "<p style='color: green;'>✓ Duplicate products cleaned up successfully!</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to clean up duplicates.</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error cleaning up: " . $e->getMessage() . "</p>";
    }
}

try {
    echo "<h3>Current Product Status:</h3>";
    
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
        echo "<p><a href='?cleanup=true' style='background: red; color: white; padding: 10px; text-decoration: none;'>🧹 Clean Up Duplicates</a></p>";
    }
    
    // Show all products
    echo "<h3>All products in database:</h3>";
    $stmt = $pdo->prepare('SELECT id, name, category, price, status, created_at FROM products ORDER BY name, created_at');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Status</th><th>Created</th></tr>";
    
    $prevName = '';
    foreach ($products as $product) {
        $style = ($product['name'] === $prevName) ? "background-color: #ffcccc;" : "";
        echo "<tr style='$style'>";
        echo "<td>{$product['id']}</td>";
        echo "<td>{$product['name']}</td>";
        echo "<td>{$product['category']}</td>";
        echo "<td>{$product['price']}</td>";
        echo "<td>{$product['status']}</td>";
        echo "<td>{$product['created_at']}</td>";
        echo "</tr>";
        $prevName = $product['name'];
    }
    echo "</table>";
    
    echo "<p><strong>Total products: " . count($products) . "</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<p><a href="debug-products.php">🔍 View Products Debug</a> | <a href="pages/admin/index.php">⚙️ Admin Panel</a> | <a href="pages/products/index.php">🛒 Products Page</a></p>
