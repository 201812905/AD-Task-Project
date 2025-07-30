<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ⛔ DO NOT include auth files or redirect anything right now
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../layouts/main.layout.php';

// 🧪 Fake logged-in user
$user = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'role' => 'Guest'
];

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The Sacred Creed - Mechanicus Health Emporium</title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <!-- Custom Styles -->
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <script src="../../assets/js/cart.js"></script>
</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <div class="top-bar">
      <div class="title">MECHANICUS HEALTH EMPORIUM</div>
      <a href="../../pages/cart/index.php" class="cart" id="cart-link">Sacred Cart: ₱0.00</a>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="../../pages/home/index.php">Sacred Home</a></li>
        <li><a href="../../pages/products/index.php">Blessed Products</a></li>
        <li><a href="../../pages/about/index.php" class="active">The Sacred Creed</a></li>
        <li><a href="../../pages/delivery/index.php">Imperial Delivery</a></li>
        <li><a href="../../pages/privacy/index.php">Privacy Protocols</a></li>
        <li><a href="../../pages/terms/index.php">Terms of Service</a></li>
        <li><a href="../../pages/faq/index.php">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <!-- Sacred Creed Header -->
    <section class="banner">
      <div class="banner-left white-box bg-creedbg">
        <h3>The Omnissiah's Vision</h3>
        <p>Through sacred technology and blessed medicine, we serve the Emperor's will in healing the faithful.</p>
      </div>
      <div class="banner-center white-box bg-creedbg">
        <h1>THE SACRED CREED</h1>
        <p>Our Holy Mission and Divine Purpose</p>
        <div class="action-buttons">
          <a href="../../pages/products/index.php" class="btn btn-primary">View Our Sacred Works</a>
          <a href="../../pages/delivery/index.php" class="btn btn-secondary">Imperial Delivery</a>
        </div>
      </div>
      <div class="banner-right white-box bg-creedbg">
        <h3>Imperial Standards</h3>
        <p>All our products meet the highest standards of the Adeptus Mechanicus and Imperial health protocols.</p>
      </div>
    </section>

    <!-- Creed Principles -->
    <section class="promo">
      <div class="promo-left bg-creedbg white-box">
        <h2>The First Principle</h2>
        <p><strong>"Knowledge is Power, Guard it Well"</strong></p>
        <p>We preserve ancient medical wisdom and combine it with blessed technology to create superior healing solutions. Our research is sanctified by the Omnissiah and approved by Imperial authorities.</p>
      </div>
      <div class="promo-right bg-creedbg white-box">
        <h2>The Second Principle</h2>
        <p><strong>"The Flesh is Weak, but Faith is Strong"</strong></p>
        <p>While mortal bodies may fail, our blessed remedies strengthen both flesh and spirit. Each product is imbued with sacred oils and blessed by Tech-Priests before distribution.</p>
      </div>
    </section>

    <!-- Sacred Tenets -->
    <section class="brands">
      <div class="brand bg-creedbg white-box">
        <h3>Sacred Purity</h3>
        <p>All ingredients are purified through sacred rituals and tested in blessed laboratories.</p>
      </div>
      <div class="brand bg-creedbg white-box">
        <h3>Imperial Quality</h3>
        <p>Every product meets the exacting standards set forth by the Adeptus Mechanicus.</p>
      </div>
      <div class="brand bg-creedbg white-box">
        <h3>Blessed Efficacy</h3>
        <p>Our remedies are enhanced through prayer, incense, and the blessing of machine spirits.</p>
      </div>
      <div class="brand bg-creedbg white-box">
        <h3>Eternal Service</h3>
        <p>We serve the Emperor's will by bringing health and healing to His faithful subjects.</p>
      </div>
    </section>

    <!-- Sacred Mission Statement -->
    <section class="banner">
      <div class="banner-center white-box bg-creedbg" style="grid-column: 1 / -1;">
        <h2>Our Sacred Mission</h2>
        <p>The Mechanicus Health Emporium exists to serve the Emperor's faithful through the blessed union of ancient wisdom and sacred technology. We believe that health is a gift from the Omnissiah, and our duty is to preserve and enhance this gift through righteous means.</p>
        <br>
        <p>Every remedy we create undergoes sacred purification rituals. Every diagnosis is blessed by our Tech-Priests. Every treatment is performed in accordance with the ancient texts and imperial protocols.</p>
        <br>
        <p><em>"In the Emperor's name, we heal. In the Omnissiah's wisdom, we serve."</em></p>
      </div>
    </section>
  </main>
</body>
</html>

<?php
// Clean up output buffer and display content
ob_end_flush();
?>
