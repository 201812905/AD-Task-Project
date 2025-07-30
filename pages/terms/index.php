<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../utils/auth.util.php';

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Terms of Service - Mechanicus Health Emporium</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <script src="../../assets/js/cart.js"></script>
</head>
<body>
  <header class="header">
    <div class="top-bar">
      <div class="title">MECHANICUS HEALTH EMPORIUM</div>
      <a href="../../pages/cart/index.php" class="cart" id="cart-link">Sacred Cart: ₱0.00</a>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="../../pages/home/index.php">Sacred Home</a></li>
        <li><a href="../../pages/products/index.php">Blessed Products</a></li>
        <li><a href="../../pages/about/index.php">The Sacred Creed</a></li>
        <li><a href="../../pages/delivery/index.php">Imperial Delivery</a></li>
        <li><a href="../../pages/privacy/index.php">Privacy Protocols</a></li>
        <li><a href="../../pages/terms/index.php" class="active">Terms of Service</a></li>
        <li><a href="../../pages/faq/index.php">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <main class="main-content">
    <section class="page-header">
      <div class="white-box">
        <h1>Terms of Service</h1>
        <p>Sacred contracts blessed by Imperial law</p>
      </div>
    </section>

    <section class="terms-content">
      <div class="terms-section white-box">
        <h2><i class="fas fa-scroll"></i> Sacred Agreement</h2>
        <p>By using the Mechanicus Health Emporium services, you agree to these terms blessed by Imperial decree and sanctified by the Omnissiah's wisdom.</p>
      </div>

      <div class="terms-section white-box">
        <h2><i class="fas fa-handshake"></i> User Responsibilities</h2>
        <ul>
          <li>Provide accurate information for blessed transactions</li>
          <li>Use products according to sacred instructions</li>
          <li>Report any heretical malfunction of blessed items</li>
          <li>Maintain respect for the machine spirits in our systems</li>
        </ul>
      </div>

      <div class="terms-section white-box">
        <h2><i class="fas fa-balance-scale"></i> Imperial Warranty</h2>
        <p>All products are blessed with Imperial warranty covering defects in sacred craftsmanship. Warranty duration varies by product type and blessing level applied during manufacture.</p>
      </div>
    </section>
  </main>

  <style>
    .page-header {
      margin-bottom: 40px;
      text-align: center;
    }

    .page-header h1 {
      font-family: 'Cinzel', serif;
      font-size: 3em;
      color: var(--imperial-red);
      margin-bottom: 10px;
    }

    .terms-content {
      max-width: 1000px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .terms-section {
      padding: 40px;
    }

    .terms-section h2 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .terms-section h2 i {
      color: var(--primary-gold);
    }

    .terms-section ul {
      margin-left: 20px;
    }

    .terms-section li {
      margin-bottom: 10px;
      line-height: 1.6;
    }
  </style>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
