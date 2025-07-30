<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering immediately to prevent any content from being displayed
ob_start();

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../utils/auth.util.php';
require_once __DIR__ . '/../../layouts/main.layout.php';

// Check if the user is logged in
$loggedIn = isAuthenticated();
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$user = $loggedIn ? getAuthenticatedUser() : null;

// Clear any previous output that might have been generated
if (ob_get_level()) {
    ob_clean();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sacred Home - Mechanicus Health Emporium</title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <!-- Custom Styles -->
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <script src="../../assets/js/cart.js"></script>
</head>
<body class="home-page">
  <!-- HEADER -->
  <header class="header">
    <div class="top-bar">
      <div class="title">MECHANICUS HEALTH EMPORIUM</div>
      <a href="../../pages/cart/index.php" class="cart" id="cart-link">Sacred Cart: ₱0.00</a>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="../../pages/home/index.php" class="active">Sacred Home</a></li>
        <li><a href="../../pages/products/index.php">Blessed Products</a></li>
        <li><a href="../../pages/about/index.php">The Sacred Creed</a></li>
        <li><a href="../../pages/delivery/index.php">Imperial Delivery</a></li>
        <li><a href="../../pages/privacy/index.php">Privacy Protocols</a></li>
        <li><a href="../../pages/terms/index.php">Terms of Service</a></li>
        <li><a href="../../pages/faq/index.php">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <!-- Welcome Section -->
    <section class="welcome-section">
      <div class="white-box bg-coggers-light">
        <h2>Welcome back, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>!</h2>
        <p>Role: <?= htmlspecialchars($user['role']) ?></p>
        <a href="../../logout.php" class="btn btn-secondary">Sacred Logout</a>
      </div>
    </section>

    <section class="banner">
      <div class="banner-left white-box bg-smoke-red"></div>
      <div class="banner-center white-box bg-coggers">
        <h1>BLESSED HEALING</h1>
        <p>For the Emperor's Glory!</p>
        <div class="action-buttons">
          <a href="../../pages/products/index.php" class="btn btn-primary">Browse Sacred Products</a>
          <a href="../../pages/about/index.php" class="btn btn-secondary">Learn Our Creed</a>
        </div>
      </div>
      <div class="banner-right white-box bg-smoke-red"></div>
    </section>

    <section class="promo">
      <div class="promo-left bg-smoke-orange white-box steam-effect"></div>
      <div class="promo-right bg-smoke-orange white-box steam-effect"></div>
    </section>

    <section class="brands">
      <div class="brand bg-coggers-light white-box">Omnissiah Medical™</div>
      <div class="brand bg-coggers-light white-box">AdMech Diagnostics™</div>
      <div class="brand bg-coggers-light white-box">Imperial Remedies™</div>
      <div class="brand bg-coggers-light white-box">Tech-Priest Solutions™</div>
    </section>
  </main>
</body>
</html>

<?php
// Clean up output buffer and display content
ob_end_flush();
?>
