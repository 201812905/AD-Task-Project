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
  <title>Imperial Delivery - Mechanicus Health Emporium</title>
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
        <li><a href="../../pages/delivery/index.php" class="active">Imperial Delivery</a></li>
        <li><a href="../../pages/privacy/index.php">Privacy Protocols</a></li>
        <li><a href="../../pages/terms/index.php">Terms of Service</a></li>
        <li><a href="../../pages/faq/index.php">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <main class="main-content">
    <section class="page-header">
      <div class="white-box">
        <h1>Imperial Delivery</h1>
        <p>Swift and secure delivery across the Imperium</p>
      </div>
    </section>

    <section class="delivery-content">
      <div class="delivery-section white-box">
        <h2><i class="fas fa-rocket"></i> Delivery Options</h2>
        <div class="delivery-options">
          <div class="delivery-option">
            <h4>Standard Imperial Courier</h4>
            <p><strong>₱99.00</strong> - 3-5 Terran days</p>
            <p>Reliable delivery via established Imperial trade routes</p>
          </div>
          <div class="delivery-option">
            <h4>Priority Sacred Dispatch</h4>
            <p><strong>₱199.00</strong> - 1-2 Terran days</p>
            <p>Expedited delivery with blessing protocols</p>
          </div>
          <div class="delivery-option">
            <h4>Emergency Medical Supply</h4>
            <p><strong>₱399.00</strong> - 24 hours</p>
            <p>Critical medical supplies via fast courier ships</p>
          </div>
        </div>
      </div>

      <div class="delivery-section white-box">
        <h2><i class="fas fa-map"></i> Delivery Zones</h2>
        <p>We deliver to all Imperial territories and beyond:</p>
        <ul class="zone-list">
          <li><i class="fas fa-globe"></i> Core Imperial Worlds</li>
          <li><i class="fas fa-space-shuttle"></i> Frontier Systems</li>
          <li><i class="fas fa-satellite"></i> Space Stations & Orbital Platforms</li>
          <li><i class="fas fa-shield-alt"></i> Military Installations (with clearance)</li>
          <li><i class="fas fa-industry"></i> Forge Worlds & Mining Colonies</li>
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

    .delivery-content {
      max-width: 1000px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 40px;
    }

    .delivery-section {
      padding: 40px;
    }

    .delivery-section h2 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .delivery-options {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }

    .delivery-option {
      padding: 25px;
      border: 2px solid var(--primary-gold);
      border-radius: 15px;
      background: linear-gradient(135deg, rgba(218, 165, 32, 0.1), rgba(139, 68, 68, 0.1));
    }

    .delivery-option h4 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      margin-bottom: 15px;
    }

    .zone-list {
      list-style: none;
      padding: 0;
    }

    .zone-list li {
      padding: 15px 0;
      border-bottom: 1px solid rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .zone-list li i {
      color: var(--primary-gold);
      width: 20px;
    }
  </style>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
