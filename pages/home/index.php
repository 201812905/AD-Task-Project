<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/auth.util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/layouts/main.layout.php';

$loggedIn = isAuthenticated();
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$user = $loggedIn ? getAuthenticatedUser() : null;

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

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <link rel="stylesheet" href="/assets/css/style.css" />
  <script src="/assets/js/cart.js"></script>
  <script src="/assets/js/loading.js"></script>
</head>
<body class="home-page">
  <script>

    document.addEventListener('DOMContentLoaded', function() {
      if (window.showLoading) {
        window.showLoading();

        setTimeout(function() {
          window.hideLoading();
        }, 1000);
      }
    });
  </script>
  <header class="header">
    <div class="top-bar">
      <div class="title">MECHANICUS HEALTH EMPORIUM</div>
      <a href="/pages/cart/index.php" class="cart" id="cart-link">Sacred Cart: ₱0.00</a>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="/pages/home/index.php" class="active">Sacred Home</a></li>
        <li><a href="/pages/products/index.php">Blessed Products</a></li>
        <li><a href="/pages/about/index.php">The Sacred Creed</a></li>
        <li><a href="/pages/delivery/index.php">Imperial Delivery</a></li>
        <li><a href="/pages/privacy/index.php">Privacy Protocols</a></li>
        <li><a href="/pages/terms/index.php">Terms of Service</a></li>
        <li><a href="/pages/faq/index.php">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <main class="main-content">
    <section class="welcome-section">
      <div class="white-box bg-coggers-light">
        <h2>Welcome back, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>!</h2>
        <p>Role: <?= htmlspecialchars($user['role']) ?></p>
        <a href="/logout.php" class="btn btn-secondary">Sacred Logout</a>
      </div>
    </section>

    <section class="banner">
      <div class="banner-left white-box bg-smoke-red"></div>
      <div class="banner-center white-box bg-coggers">
        <h1>BLESSED HEALING</h1>
        <p>For the Emperor's Glory!</p>
        <div class="action-buttons">
          <a href="/pages/products/index.php" class="btn btn-primary">Browse Sacred Products</a>
          <a href="/pages/about/index.php" class="btn btn-secondary">Learn Our Creed</a>
        </div>
      </div>
      <div class="banner-right white-box bg-smoke-red"></div>
    </section>

    <section class="promo">
      <div class="promo-left bg-asset3 white-box steam-effect"></div>
      <div class="promo-right bg-asset6 white-box steam-effect"></div>
    </section>

    <section class="brands">
      <div class="brand bg-asset1 white-box">Omnissiah Medical™</div>
      <div class="brand bg-asset2 white-box">AdMech Diagnostics™</div>
      <div class="brand bg-asset4 white-box">Imperial Remedies™</div>
      <div class="brand bg-asset5 white-box">Tech-Priest Solutions™</div>
    </section>

    <section class="team-section">
      <div class="team-header white-box bg-steamhealth">
        <h2>Meet the Sacred Tech-Priests</h2>
        <p>The blessed servants behind the Mechanicus Health Emporium</p>
      </div>
      
      <div class="team-grid">
        <div class="team-member white-box bg-dragonite">
          <div class="member-image">
            <img src="/assets/img/aldous.jpg" alt="Aldous Damaso">
          </div>
          <h3>Aldous Damaso</h3>
          <h4>Lead Tech-Priest Biologis</h4>
          <p>Master of sacred medical knowledge and blessed healing arts.</p>
        </div>

        <div class="team-member white-box bg-dragonite">
          <div class="member-image">
            <img src="/assets/img/van.jpg" alt="Van Navarez">
          </div>
          <h3>Van Navarez</h3>
          <h4>Tech-Priest Logis</h4>
          <p>Guardian of sacred supply chains and blessed logistics.</p>
        </div>

        <div class="team-member white-box bg-nyebe">
          <div class="member-image">
            <img src="/assets/img/jm.jpg" alt="JM Rivera">
          </div>
          <h3>JM Rivera</h3>
          <h4>Tech-Priest Manufactorum</h4>
          <p>Master of blessed production and sacred manufacturing.</p>
        </div>

        <div class="team-member white-box bg-smoke-orange">
          <div class="member-image">
            <img src="/assets/img/oyo.jpg" alt="Oyo DeLemos">
          </div>
          <h3>Oyo DeLemos</h3>
          <h4>Tech-Priest Communis</h4>
          <p>Keeper of sacred communications and customer relations.</p>
        </div>
      </div>
    </section>
  </main>

</body>
</html>

<?php
ob_end_flush();
?>
