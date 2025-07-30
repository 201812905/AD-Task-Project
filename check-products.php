<?php
require_once 'bootstrap.php';

try {
    echo "Checking for duplicate products...\n";
    
    // Check for duplicates by name
    $stmt = $pdo->prepare('SELECT name, COUNT(*) as count FROM products GROUP BY name HAVING COUNT(*) > 1');
    $stmt->execute();
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($duplicates)) {
        echo "✓ No duplicate product names found in database.\n";
    } else {
        echo "❌ Duplicate products found:\n";
        foreach ($duplicates as $dup) {
            echo "- {$dup['name']} (appears {$dup['count']} times)\n";
        }
    }
    
    // Show all products
    echo "\nAll products in database:\n";
    $stmt = $pdo->prepare('SELECT id, name, category, price, status FROM products ORDER BY name');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as $product) {
        echo "- ID: {$product['id']}, Name: {$product['name']}, Category: {$product['category']}, Price: {$product['price']}, Status: {$product['status']}\n";
    }
    
    echo "\nTotal products: " . count($products) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
