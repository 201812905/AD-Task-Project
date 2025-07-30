<?php
require_once 'bootstrap.php';

echo "<h2>🧹 Product Cleanup - Remove All Duplicates</h2>";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'cleanup_all') {
        echo "<h3>Cleaning up ALL duplicate products...</h3>";
        
        try {
            // First, let's see what we have
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM products');
            $stmt->execute();
            $totalBefore = $stmt->fetchColumn();
            echo "<p>Products before cleanup: <strong>$totalBefore</strong></p>";
            
            // Delete ALL products first to start clean
            $stmt = $pdo->prepare('DELETE FROM products');
            $result = $stmt->execute();
            
            if ($result) {
                echo "<p style='color: orange;'>✓ All products deleted successfully!</p>";
                
                // Now add back just the original 4 products
                $originalProducts = [
                    [
                        'name' => 'Sacred Health Tonic',
                        'description' => 'Blessed healing potion infused with the Omnissiah\'s wisdom',
                        'price' => 299.99,
                        'stock_quantity' => 50,
                        'category' => 'Remedies',
                        'image_url' => '/assets/img/products/tonic.jpg'
                    ],
                    [
                        'name' => 'Imperial Medkit',
                        'description' => 'Complete field medical kit approved by the Adeptus Mechanicus',
                        'price' => 1999.99,
                        'stock_quantity' => 25,
                        'category' => 'Medical Equipment',
                        'image_url' => '/assets/img/products/medkit.jpg'
                    ],
                    [
                        'name' => 'Tech-Priest Vitamins',
                        'description' => 'Essential nutrients for maintaining sacred flesh and blessed machinery',
                        'price' => 499.99,
                        'stock_quantity' => 100,
                        'category' => 'Supplements',
                        'image_url' => '/assets/img/products/vitamins.jpg'
                    ],
                    [
                        'name' => 'Omnissiah Pain Relief',
                        'description' => 'Divine analgesic for both organic and mechanical ailments',
                        'price' => 399.99,
                        'stock_quantity' => 75,
                        'category' => 'Remedies',
                        'image_url' => '/assets/img/products/pain-relief.jpg'
                    ]
                ];
                
                $insertStmt = $pdo->prepare("
                    INSERT INTO products (name, description, price, stock_quantity, category, image_url, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'active')
                ");
                
                foreach ($originalProducts as $product) {
                    $insertStmt->execute([
                        $product['name'],
                        $product['description'],
                        $product['price'],
                        $product['stock_quantity'],
                        $product['category'],
                        $product['image_url']
                    ]);
                    echo "<p style='color: green;'>✓ Added: {$product['name']}</p>";
                }
                
                // Check final count
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM products');
                $stmt->execute();
                $totalAfter = $stmt->fetchColumn();
                
                echo "<hr>";
                echo "<p style='color: green; font-size: 18px;'><strong>🎉 CLEANUP COMPLETE!</strong></p>";
                echo "<p>Products after cleanup: <strong>$totalAfter</strong></p>";
                echo "<p>Removed: <strong>" . ($totalBefore - $totalAfter) . "</strong> duplicate products</p>";
                
            } else {
                echo "<p style='color: red;'>❌ Failed to clean up products.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error during cleanup: " . $e->getMessage() . "</p>";
        }
    }
    
} else {
    // Show current status and cleanup option
    try {
        echo "<h3>Current Product Status:</h3>";
        
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM products');
        $stmt->execute();
        $totalProducts = $stmt->fetchColumn();
        
        echo "<p>Total products in database: <strong style='color: red; font-size: 20px;'>$totalProducts</strong></p>";
        
        if ($totalProducts > 10) {
            echo "<div style='background: #ffeeee; border: 2px solid #ff0000; padding: 20px; margin: 20px 0;'>";
            echo "<h3 style='color: red;'>⚠️ TOO MANY PRODUCTS DETECTED!</h3>";
            echo "<p>You have $totalProducts products, which is way too many. This is clearly a duplication issue.</p>";
            echo "<p><strong>Recommended action:</strong> Clean up all products and start fresh with just the original 4 products.</p>";
            echo "<p><a href='?action=cleanup_all' style='background: red; color: white; padding: 15px 30px; text-decoration: none; font-weight: bold; border-radius: 5px;'>🧹 CLEAN UP ALL PRODUCTS NOW</a></p>";
            echo "</div>";
        } else {
            echo "<p style='color: green;'>✓ Product count looks normal.</p>";
        }
        
        // Show some sample products
        echo "<h3>Sample Products:</h3>";
        $stmt = $pdo->prepare('SELECT name, category, price, created_at FROM products ORDER BY created_at DESC LIMIT 20');
        $stmt->execute();
        $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Name</th><th>Category</th><th>Price</th><th>Created</th></tr>";
        
        foreach ($samples as $product) {
            echo "<tr>";
            echo "<td>{$product['name']}</td>";
            echo "<td>{$product['category']}</td>";
            echo "<td>{$product['price']}</td>";
            echo "<td>{$product['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        if ($totalProducts > 20) {
            echo "<p><em>... and " . ($totalProducts - 20) . " more products</em></p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<hr>
<p><a href="pages/admin/index.php">⚙️ Admin Panel</a> | <a href="pages/products/index.php">🛒 Products Page</a> | <a href="debug-products.php">🔍 Debug Products</a></p>
