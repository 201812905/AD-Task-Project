<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering and session
ob_start();
session_start();

require_once 'bootstrap.php';
require_once 'utils/auth.util.php';

$error = '';
$success = '';

// Check if user is already logged in
if (isAuthenticated()) {
    header("Location: pages/home/");
    exit;
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "❌ Username and password are required";
    } else {
        try {
            // Use the global $pdo from bootstrap.php
            global $pdo;
            
            // Get user from database
            $stmt = $pdo->prepare("
                SELECT id, username, email, first_name, middle_name, last_name, password, role 
                FROM users 
                WHERE username = :username
            ");
            
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful - create session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'] ?? '',
                    'first_name' => $user['first_name'],
                    'middle_name' => $user['middle_name'] ?? '',
                    'last_name' => $user['last_name'],
                    'role' => $user['role'],
                    'logged_in' => true,
                ];
                
                // Log successful login
                error_log("Successful login: " . $username . " (Role: " . $user['role'] . ")");
                
                // Redirect to home page
                if (ob_get_level()) {
                    ob_end_clean();
                }
                header("Location: pages/home/");
                exit;
                
            } else {
                $error = "❌ Invalid username or password";
                error_log("Failed login attempt for username: " . $username);
            }
            
        } catch (PDOException $e) {
            error_log("Login database error: " . $e->getMessage());
            $error = "❌ System error occurred. Please try again later.";
        }
    }
}

// Clear any previous output
if (ob_get_level()) {
    ob_clean();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacred Login - Mechanicus Health Emporium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="top-bar">
            <div class="title">MECHANICUS HEALTH EMPORIUM</div>
        </div>
    </header>

    <!-- LOGIN FORM -->
    <main class="main-content">
        <section class="login-section">
            <div class="white-box login-box">
                <h2><i class="fas fa-cog"></i> Sacred Access Portal</h2>
                <p>Enter your blessed credentials to access the Mechanicus systems</p>
                
                <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="index.php" class="login-form">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user-circle"></i> Username</label>
                        <input type="text" id="username" name="username" required 
                               placeholder="Enter your blessed username" 
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Sacred Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter your sacred password">
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i> Initiate Sacred Login
                    </button>
                </form>
                
                <div class="login-info">
                    <h4>Test Accounts:</h4>
                    <div class="test-accounts">
                        <div class="account">
                            <strong>Admin:</strong> admin / admin123
                        </div>
                        <div class="account">
                            <strong>User:</strong> john.smith / password123
                        </div>
                        <div class="account">
                            <strong>User:</strong> jm / password123
                        </div>
                    </div>
                </div>
                
                <p class="signup-link">
                    Need blessed access? <a href="signup.php">Create Sacred Account</a>
                </p>
                
                <div class="guest-access">
                    <p><a href="pages/products/" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> Browse as Guest
                    </a></p>
                </div>
            </div>
        </section>
    </main>

    <style>
        .login-section {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
            padding: 40px 20px;
        }

        .login-box {
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }

        .login-box h2 {
            font-family: 'Cinzel', serif;
            color: var(--imperial-red);
            font-size: 2em;
            margin-bottom: 10px;
        }

        .login-box p {
            color: var(--dark-bronze);
            margin-bottom: 30px;
        }

        .login-form {
            text-align: left;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--imperial-red);
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid var(--primary-gold);
            border-radius: 25px;
            font-size: 16px;
            background: rgba(245, 245, 220, 0.5);
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--imperial-red);
            box-shadow: 0 0 15px rgba(139, 0, 0, 0.3);
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 16px;
            margin: 5px;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--imperial-red), var(--dark-bronze));
            color: white;
            width: 100%;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--primary-gold), #f0d000);
            color: var(--dark-bronze);
        }

        .btn:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .error-message, .success-message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .error-message {
            background: rgba(220, 53, 69, 0.1);
            border: 2px solid #dc3545;
            color: #dc3545;
        }

        .success-message {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid #28a745;
            color: #28a745;
        }

        .login-info {
            background: rgba(245, 245, 220, 0.3);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }

        .login-info h4 {
            color: var(--imperial-red);
            margin-bottom: 15px;
            text-align: center;
        }

        .test-accounts {
            display: grid;
            gap: 8px;
        }

        .account {
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        .account strong {
            color: var(--imperial-red);
        }

        .signup-link {
            margin-top: 20px;
            font-size: 0.9em;
        }

        .signup-link a {
            color: var(--imperial-red);
            text-decoration: