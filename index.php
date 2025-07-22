<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'bootstrap.php';
require_once 'utils/auth.util.php';
require_once 'layouts/main.layout.php';

// Check if user is already logged in
$loggedIn = isAuthenticated();
if ($loggedIn) {
    // Clear any output buffer and redirect to home
    if (ob_get_level()) {
        ob_end_clean();
    }
    header("Location: pages/home/");
    exit;
}

$error = '';
$success = '';
$showLoginForm = true;

// Check if REQUEST_METHOD is set (this avoids CLI warnings)
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for bypass button
    if (isset($_POST['bypass'])) {
        $_SESSION['simple_auth'] = true;
        $_SESSION['simple_user'] = 'admin';
        $success = "🚀 Bypass activated! You are now logged in as admin.";
        $showLoginForm = false;
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Debug output
        error_log("Login attempt - Username: " . $username . ", Password length: " . strlen($password));
        
        if (authenticate($username, $password)) {
            error_log("Authentication successful for: " . $username);
            // Instead of redirect, show success message with manual navigation
            $success = "✅ Login successful! Welcome " . htmlspecialchars($username) . "!";
            $showLoginForm = false;
            
            // Also set a cookie or session flag for simple auth check
            $_SESSION['simple_auth'] = true;
            $_SESSION['simple_user'] = $username;
        } else {
            error_log("Authentication failed for: " . $username);
            $error = "❌ Invalid credentials. Debug: Username='$username', Password length=" . strlen($password);
        }
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sacred Login - Mechanicus Health Emporium</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
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
        <h2><i class="fas fa-cog"></i> Sacred Authentication</h2>
        <p>Enter your blessed credentials to access the Sacred Emporium</p>
        
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
        <div class="navigation-links">
          <p><a href="pages/home/" class="btn">🏠 Go to Homepage</a></p>
          <p><a href="pages/products/" class="btn">🛍️ View Products</a></p>
          <p><a href="pages/cart/" class="btn">🛒 View Cart</a></p>
        </div>
        <?php endif; ?>
        
        <?php if ($showLoginForm): ?>
        <form method="post" class="login-form">
          <div class="form-group">
            <label for="username"><i class="fas fa-user"></i> Username</label>
            <input type="text" id="username" name="username" required placeholder="Enter your username">
          </div>
          <div class="form-group">
            <label for="password"><i class="fas fa-lock"></i> Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
          </div>
          <button type="submit" class="btn btn-login">
            <i class="fas fa-sign-in-alt"></i> Sacred Login
          </button>
        </form>
        
        <!-- Development Bypass Button -->
        <div class="bypass-section">
          <hr style="margin: 20px 0; border: 1px solid #ccc;">
          <p style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Development Mode:</p>
          <form method="post" style="margin: 0;">
            <button type="submit" name="bypass" value="1" class="btn btn-bypass">
              <i class="fas fa-rocket"></i> Quick Bypass (Dev)
            </button>
          </form>
        </div>
        
        <div class="login-info">
          <h4>Test Credentials:</h4>
          <p><strong>Admin:</strong> username: admin, password: password</p>
          <p><strong>User:</strong> username: john.smith, password: p@ssW0rd1234</p>
        </div>
        
        <p class="signup-link">
          Don't have blessed access? <a href="signup.php">Request Sacred Account</a>
        </p>
        <?php endif; ?>
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
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: var(--imperial-red);
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid var(--primary-gold);
      border-radius: 25px;
      font-size: 16px;
      background: rgba(245, 245, 220, 0.5);
      box-sizing: border-box;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--imperial-red);
      box-shadow: 0 0 10px rgba(139, 0, 0, 0.3);
    }

    .btn {
      padding: 12px 30px;
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
      margin-bottom: 10px;
    }

    .btn-bypass {
      background: linear-gradient(135deg, var(--primary-gold), #f0d000);
      color: var(--dark-bronze);
      width: 100%;
    }

    .btn:hover {
      transform: scale(1.02);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
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

    .navigation-links {
      margin-top: 20px;
    }

    .navigation-links .btn {
      display: block;
      margin-bottom: 10px;
      background: linear-gradient(135deg, var(--primary-gold), #f0d000);
      color: var(--dark-bronze);
    }

    .login-info {
      background: rgba(245, 245, 220, 0.3);
      padding: 15px;
      border-radius: 10px;
      margin: 20px 0;
      text-align: left;
      font-size: 0.9em;
    }

    .login-info h4 {
      color: var(--imperial-red);
      margin-bottom: 10px;
    }

    .signup-link {
      margin-top: 20px;
      font-size: 0.9em;
    }

    .signup-link a {
      color: var(--imperial-red);
      text-decoration: none;
      font-weight: bold;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }

    .bypass-section {
      margin: 20px 0;
    }

    @media (max-width: 768px) {
      .login-box {
        margin: 20px;
        padding: 30px 20px;
      }
    }
  </style>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
