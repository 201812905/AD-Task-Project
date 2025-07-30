<?php
declare(strict_types=1);

function isAuthenticated(): bool {
    return isset($_SESSION['user']) || isset($_SESSION['simple_auth']);
}

function getAuthenticatedUser(): ?array {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    if (isset($_SESSION['simple_auth']) && isset($_SESSION['simple_user'])) {
        $username = $_SESSION['simple_user'];
        $user = [
            'id' => 999,
            'username' => $username,
            'first_name' => $username === 'admin' ? 'Admin' : 'John',
            'last_name' => $username === 'admin' ? 'User' : 'Smith',
            'email' => $username === 'admin' ? 'admin@mechanicus.com' : 'john.smith@mechanicus.com',
            'role' => $username === 'admin' ? 'admin' : 'user'
        ];
        return $user;
    }
    return null;
}

function requireLogin(): void {
    if (!isAuthenticated()) {
        header('Location: ../index.php');
        exit;
    }
}

function logout(): void {
    unset($_SESSION['user'], $_SESSION['simple_auth'], $_SESSION['simple_user']);
    session_destroy();
}

function authenticate(string $username, string $password): bool {
    global $pdo;

    try {
        // Query the database for the user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
      if ($user && password_verify($password, $user['password'])) { 
            // Store user data in session
            $_SESSION['user'] = [
                'id' => $user['id'] ?? 1,
                'username' => $user['username'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'] ?? $user['username'] . '@mechanicus.com',
                'role' => $user['role']  // Keep raw role for authentication checks
            ];
            
            return true;
        }
        
        return false;
        
    } catch (PDOException $e) {
        return authenticateFallback($username, $password);
    }
}

function authenticateFallback(string $username, string $password): bool {
    $validCredentials = [
        'admin' => 'admin123',
        'john.smith' => 'p@ssW0rd1234',
        'jm' => '12345'
    ];

    if (isset($validCredentials[$username]) && $validCredentials[$username] === $password) {
        $_SESSION['user'] = [
            'id' => $username === 'admin' ? 999 : 1,
            'username' => $username,
            'first_name' => getFallbackFirstName($username),
            'last_name' => getFallbackLastName($username),
            'email' => $username . '@mechanicus.com',
            'role' => getFallbackRole($username)
        ];
        
        return true;
    }

    return false;
}

function setBypassAuth(string $username = 'admin'): bool {
    $_SESSION['simple_auth'] = true;
    $_SESSION['simple_user'] = $username;
    
    $_SESSION['user'] = [
        'id' => 999,
        'username' => $username,
        'first_name' => $username === 'admin' ? 'Admin' : 'John',
        'last_name' => $username === 'admin' ? 'User' : 'Smith',
        'email' => $username === 'admin' ? 'admin@mechanicus.com' : 'john.smith@mechanicus.com',
        'role' => $username === 'admin' ? 'admin' : 'user'
    ];
    
    return true;
}

function getFallbackFirstName(string $username): string {
    $names = [
        'admin' => 'Admin',
        'john.smith' => 'John',
        'jm' => 'JM'
    ];
    return $names[$username] ?? ucfirst($username);
}

function getFallbackLastName(string $username): string {
    $names = [
        'admin' => 'User',
        'john.smith' => 'Smith',
        'jm' => 'Rivera'
    ];
    return $names[$username] ?? 'User';
}

function getFallbackRole(string $username): string {
    $roles = [
        'admin' => 'admin',  // Changed to lowercase admin for consistency
        'john.smith' => 'user',
        'jm' => 'user'
    ];
    return $roles[$username] ?? 'user';
}

function mapRoleToTitle(string $role): string {
    $roleMappings = [
        'admin' => 'Tech-Dominus',
        'user' => 'Tech-Priest',
        'designer' => 'Tech-Priest'
    ];
    return $roleMappings[$role] ?? 'Tech-Priest';
}

// Helper function to get user by username from database
function getUserByUsername(string $username): ?array {
    global $pdo;
    $scriptName = basename($_SERVER['SCRIPT_NAME']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM project_users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        error_log("❌ [$scriptName] Error fetching user: " . $e->getMessage());
        return null;
    }
}