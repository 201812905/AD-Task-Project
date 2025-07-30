<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

// Check if user is authenticated
$loggedIn = isAuthenticated();
$user = $loggedIn ? getAuthenticatedUser() : null;

if (!$loggedIn || !$user) {
    header("Location: /index.php");
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $host = $typeConfig['pgHost'];
        $port = $typeConfig['pgPort'];
        $db_username = $typeConfig['pgUser'];
        $db_password = $typeConfig['pgPass'];
        $dbname = $typeConfig['pgDb'];
        
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $db_username, $db_password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        if (isset($_POST['update_profile'])) {
            $checkStmt = $pdo->prepare("
                SELECT COUNT(*) FROM users 
                WHERE (username = :username OR email = :email) 
                AND id != :user_id
            ");
            $checkStmt->execute([
                ':username' => $_POST['username'],
                ':email' => $_POST['email'],
                ':user_id' => $user['id']
            ]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $message = "Username or email already exists for another user.";
                $messageType = "error";
            } else {
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET username = :username, 
                        email = :email, 
                        first_name = :first_name, 
                        last_name = :last_name, 
                        created_at = created_at
                    WHERE id = :user_id
                ");
                
                $result = $stmt->execute([
                    ':username' => $_POST['username'],
                    ':email' => $_POST['email'],
                    ':first_name' => $_POST['first_name'],
                    ':last_name' => $_POST['last_name'],
                    ':user_id' => $user['id']
                ]);
                
                if ($result) {
                    $message = "Profile updated successfully!";
                    $messageType = "success";
                    
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $_POST['username'],
                        'email' => $_POST['email'],
                        'first_name' => $_POST['first_name'],
                        'last_name' => $_POST['last_name'],
                        'role' => $user['role'],
                        'created_at' => $user['created_at']
                    ];
                    $user = $_SESSION['user'];
                } else {
                    $message = "Failed to update profile.";
                    $messageType = "error";
                }
            }
        }
        
        if (isset($_POST['change_password'])) {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if (strlen($newPassword) < 6) {
                $message = "New password must be at least 6 characters long.";
                $messageType = "error";
            } elseif ($newPassword !== $confirmPassword) {
                $message = "New passwords do not match.";
                $messageType = "error";
            } else {
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :user_id");
                $stmt->execute([':user_id' => $user['id']]);
                $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($currentPassword, $dbUser['password'])) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("
                        UPDATE users 
                        SET password = :password 
                        WHERE id = :user_id
                    ");
                    
                    $result = $stmt->execute([
                        ':password' => $hashedPassword, 
                        ':user_id' => $user['id']
                    ]);
                    
                    if ($result) {
                        $message = "Password changed successfully!";
                        $messageType = "success";
                    } else {
                        $message = "Failed to change password.";
                        $messageType = "error";
                    }
                } else {
                    $message = "Current password is incorrect.";
                    $messageType = "error";
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Profile update database error: " . $e->getMessage());
        $message = "Database error occurred. Please try again later.";
        $messageType = "error";
    } catch (Exception $e) {
        error_log("Profile update error: " . $e->getMessage());
        $message = "An error occurred. Please try again later.";
        $messageType = "error";
    }
}

if (ob_get_level()) {
    ob_clean();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacred Profile - Mechanicus Health Emporium</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="profile-page">
    <header class="header">
        <div class="top-bar">
            <div class="title">SACRED PROFILE</div>
            <a href="/pages/home/index.php" class="cart">
                <i class="fas fa-home"></i> Return Home
            </a>
        </div>
        <nav class="nav-bar">
            <ul>
        <li><a href="/pages/home/index.php">Sacred Home</a></li>
        <li><a href="/pages/products/index.php">Blessed Products</a></li>
        <li><a href="/pages/about/index.php">The Sacred Creed</a></li>
        <li><a href="/pages/delivery/index.php">Imperial Delivery</a></li>
        <li><a href="/pages/privacy/index.php">Privacy Protocols</a></li>
        <li><a href="/pages/terms/index.php">Terms of Service</a></li>
        <li><a href="/pages/faq/index.php">Sacred Knowledge</a></li>
        <li><a href="/pages/profile/index.php" class="active">Profile</a></li>
                <?php if ($user['role'] === 'admin'): ?>
                    <li><a href="/pages/admin/index.php">Admin Panel</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="profile-container">
        <div class="profile-header">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h1>Sacred Profile</h1>
                <h3><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h3>
                <p>Role: <?= htmlspecialchars($user['role']) ?></p>
                <p>Member since: <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></p>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Profile Information Section -->
        <div class="profile-section">
            <h2><i class="fas fa-user-edit"></i> Profile Information</h2>
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                </div>
                
                <button type="submit" name="update_profile" class="profile-btn">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password Section -->
        <div class="profile-section">
            <h2><i class="fas fa-lock"></i> Change Password</h2>
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
                
                <button type="submit" name="change_password" class="profile-btn">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.layout.php'; ?>
</body>
</html>

<?php
ob_end_flush();
?>