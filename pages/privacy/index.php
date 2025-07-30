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
  <title>Privacy Protocols - Mechanicus Health Emporium</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <script src="../../assets/js/cart.js"></script>
  <script src="../../assets/js/loading.js"></script>
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
        <li><a href="../../pages/privacy/index.php" class="active">Privacy Protocols</a></li>
        <li><a href="../../pages/terms/index.php">Terms of Service</a></li>
        <li><a href="../../pages/faq/index.php">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <main class="main-content">
    <section class="page-header">
      <div class="white-box">
        <h1>Privacy Protocols</h1>
        <p>Your data protected by the Omnissiah's sacred encryption</p>
      </div>
    </section>

    <section class="privacy-content">
      <div class="privacy-section white-box">
        <h2><i class="fas fa-shield-alt"></i> Data Protection Sacred Rites</h2>
        <p>The Mechanicus Health Emporium is committed to protecting your personal data through sacred machine spirit encryption and blessed security protocols. Your information is treated with the reverence due to sacred knowledge.</p>
      </div>

      <div class="privacy-section white-box">
        <h2><i class="fas fa-database"></i> Information We Collect</h2>
        <ul>
          <li>Personal identification data for blessed transactions</li>
          <li>Medical supply preferences and sacred order history</li>
          <li>Communication records with our Tech-Priest support staff</li>
          <li>Machine spirit interaction logs for service improvement</li>
        </ul>
      </div>

      <div class="privacy-section white-box">
        <h2><i class="fas fa-lock"></i> Sacred Security Measures</h2>
        <p>All data is protected by:</p>
        <ul>
          <li>256-bit sacred encryption protocols</li>
          <li>Machine spirit authentication systems</li>
          <li>Blessed firewall protection from corrupt data</li>
          <li>Regular security blessings performed by Tech-Priests</li>
        </ul>
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

    .privacy-content {
      max-width: 1000px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .privacy-section {
      padding: 40px;
    }

    .privacy-section h2 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .privacy-section h2 i {
      color: var(--primary-gold);
    }

    .privacy-section ul {
      margin-left: 20px;
    }

    .privacy-section li {
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
