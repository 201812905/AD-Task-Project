<?php
require_once __DIR__ . '/bootstrap.php';

echo "<h1>User Account Search</h1>";

try {
    // Search for any users with 'jm' in username
    echo "<h2>Searching for JM-related accounts:</h2>";
    $stmt = $pdo->prepare("SELECT id, username, first_name, last_name, email, role, created_at FROM users WHERE username ILIKE '%jm%' OR first_name ILIKE '%jm%' OR last_name ILIKE '%jm%'");
    $stmt->execute();
    $jmUsers = $stmt->fetchAll();
    
    if (empty($jmUsers)) {
        echo "<p style='color: orange;'>❌ No JM-related accounts found</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Username</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>";
        foreach ($jmUsers as $user) {
            echo "<tr>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['first_name']} {$user['last_name']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$user['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Show all current users
    echo "<h2>All current users:</h2>";
    $stmt = $pdo->prepare("SELECT username, first_name, last_name, email, role, created_at FROM users ORDER BY username");
    $stmt->execute();
    $allUsers = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Username</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>";
    foreach ($allUsers as $user) {
        echo "<tr>";
        echo "<td><strong>{$user['username']}</strong></td>";
        echo "<td>{$user['first_name']} {$user['last_name']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['role']}</td>";
        echo "<td>{$user['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Option to recreate jm1 account
    echo "<h2>Recreate jm1 account?</h2>";
    echo "<form method='POST'>";
    echo "<p>If you want to recreate the jm1 account, click the button below:</p>";
    echo "<button type='submit' name='create_jm1' style='padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer;'>Recreate jm1 Account</button>";
    echo "</form>";
    
    // Handle form submission
    if (isset($_POST['create_jm1'])) {
        echo "<h3>Creating jm1 account...</h3>";
        
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT); // Default password
        
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, first_name, last_name, password, role) 
            VALUES (?, ?, ?, ?, ?, ?)
            ON CONFLICT (username) DO UPDATE SET
                email = EXCLUDED.email,
                first_name = EXCLUDED.first_name,
                last_name = EXCLUDED.last_name,
                password = EXCLUDED.password,
                role = EXCLUDED.role
        ");
        
        $stmt->execute(['jm1', 'jm1@email.com', 'JM', 'Rivera', $hashedPassword, 'user']);
        
        echo "<p style='color: green;'>✅ Account 'jm1' created successfully!</p>";
        echo "<p><strong>Username:</strong> jm1</p>";
        echo "<p><strong>Password:</strong> password123</p>";
        echo "<p><strong>Email:</strong> jm1@email.com</p>";
        echo "<p><strong>Role:</strong> user</p>";
        
        // Refresh the page to show updated user list
        echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
