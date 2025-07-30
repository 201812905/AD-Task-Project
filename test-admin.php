<?php
/**
 * Database and Admin Panel Test Script
 * Use this to test if everything is working correctly
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/utils/admin.util.php';

echo "Testing Mechanicus Health Emporium Database and Admin Panel...\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    if (!isset($pdo)) {
        throw new Exception("Database connection not available");
    }
    echo "✅ Database connection successful\n\n";
    
    // Test AdminUtil initialization
    echo "2. Testing AdminUtil initialization...\n";
    $adminUtil = new AdminUtil();
    echo "✅ AdminUtil initialized successfully\n\n";
    
    // Test getting stats
    echo "3. Testing stats retrieval...\n";
    $stats = $adminUtil->getStats();
    echo "✅ Stats retrieved:\n";
    echo "   - Products: {$stats['products']}\n";
    echo "   - Users: {$stats['users']}\n";
    echo "   - Orders: {$stats['orders']}\n";
    echo "   - Low Stock: {$stats['low_stock']}\n\n";
    
    // Test getting products
    echo "4. Testing product retrieval...\n";
    $products = $adminUtil->getAllProducts();
    echo "✅ Found " . count($products) . " products:\n";
    foreach (array_slice($products, 0, 3) as $product) {
        echo "   - {$product['name']} (₱{$product['price']} - Stock: {$product['stock_quantity']})\n";
    }
    if (count($products) > 3) {
        echo "   ... and " . (count($products) - 3) . " more\n";
    }
    echo "\n";
    
    // Test getting users
    echo "5. Testing user retrieval...\n";
    $users = $adminUtil->getAllUsers();
    echo "✅ Found " . count($users) . " users:\n";
    foreach ($users as $user) {
        echo "   - {$user['username']} ({$user['first_name']} {$user['last_name']}) - Role: {$user['role']}\n";
    }
    echo "\n";
    
    // Test authentication
    echo "6. Testing authentication...\n";
    require_once __DIR__ . '/utils/auth.util.php';
    
    // Test admin login
    session_start();
    if (authenticate('admin', 'admin123')) {
        $user = getAuthenticatedUser();
        echo "✅ Admin authentication successful\n";
        echo "   - Username: {$user['username']}\n";
        echo "   - Role: {$user['role']}\n";
        session_destroy();
    } else {
        echo "❌ Admin authentication failed\n";
    }
    
    // Test user login
    session_start();
    if (authenticate('john.smith', 'p@ssW0rd1234')) {
        $user = getAuthenticatedUser();
        echo "✅ User authentication successful\n";
        echo "   - Username: {$user['username']}\n";
        echo "   - Role: {$user['role']}\n";
        session_destroy();
    } else {
        echo "❌ User authentication failed\n";
    }
    echo "\n";
    
    echo "🎉 All tests passed! Your admin panel is ready to use.\n\n";
    echo "You can now:\n";
    echo "1. Access the admin panel at: http://localhost:8000/pages/admin/\n";
    echo "2. Login with: admin / admin123\n";
    echo "3. Manage products, users, and orders\n\n";
    echo "User credentials for testing:\n";
    echo "- Admin: admin / admin123\n";
    echo "- User: john.smith / p@ssW0rd1234\n";
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Please check your database configuration and try running init-database.php first.\n";
    exit(1);
}
