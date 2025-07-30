<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../utils/auth.util.php';
require_once __DIR__ . '/../../utils/cart.util.php';
require_once __DIR__ . '/../../layouts/main.layout.php';

// Check if the user is logged in
$loggedIn = isAuthenticated();
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
$user = $loggedIn ? getAuthenticatedUser() : null;

$products = getProducts();

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Blessed Products - Mechanicus Health Emporium</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="/assets/css/style.css" />
  <script src="/assets/js/cart.js"></script>
  <script src="/assets/js/loading.js"></script>
  <script>
    window.productsData = <?= json_encode($products) ?>;
    
    // Clean up any invalid cart items when page loads
    if (typeof Storage !== 'undefined' && localStorage.getItem('mechaCart')) {
        const cart = JSON.parse(localStorage.getItem('mechaCart'));
        const validCart = {};
        let cleaned = false;
        
        for (const [productId, item] of Object.entries(cart)) {
            if (window.productsData[productId]) {
                validCart[productId] = item;
            } else {
                console.log('Removed invalid product from cart:', productId);
                cleaned = true;
            }
        }
        
        if (cleaned) {
            localStorage.setItem('mechaCart', JSON.stringify(validCart));
            console.log('Cart cleaned up - removed invalid products');
        }
    }
  </script>
  <script src="/assets/js/products.js"></script>
</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <div class="top-bar">
      <div class="title">MECHANICUS HEALTH EMPORIUM</div>
      <a href="/pages/cart/index.php" class="cart" id="cart-link">Sacred Cart: <span id="cart-total"><?= formatPrice(getCartTotal()) ?></span> (<span id="cart-count"><?= getCartItemCount() ?></span>)</a>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="/pages/home/index.php">Sacred Home</a></li>
        <li><a href="/pages/products/index.php" class="active">Blessed Products</a></li>
        <li><a href="/pages/about/index.php">The Sacred Creed</a></li>
        <li><a href="/pages/delivery/index.php">Imperial Delivery</a></li>
        <li><a href="/pages/privacy/index.php">Privacy Protocols</a></li>
        <li><a href="/pages/terms/index.php">Terms of Service</a></li>
        <li><a href="/pages/faq/index.php">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <section class="page-header">
      <div class="white-box bg-smoke-red">
        <h1>Blessed Products</h1>
        <p>Sacred healing supplies blessed by the Omnissiah</p>
      </div>
    </section>

    <section class="product-filters">
      <div class="white-box">
        <h3>Filter Products</h3>
        <div class="filter-buttons">
          <button class="filter-btn active" data-category="all">All Products</button>
          <button class="filter-btn" data-category="medicine">Sacred Medicine</button>
          <button class="filter-btn" data-category="equipment">Medical Equipment</button>
          <button class="filter-btn" data-category="supplements">Blessed Supplements</button>
          <button class="filter-btn" data-category="tools">Medical Tools</button>
        </div>
      </div>
    </section>

    <section class="products-grid">
      <?php foreach ($products as $product): ?>
      <div class="product-card white-box" data-category="<?= htmlspecialchars($product['category']) ?>">
        <div class="product-image">
          <i class="<?= htmlspecialchars($product['icon']) ?>"></i>
        </div>
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p><?= htmlspecialchars($product['description']) ?></p>
        <div class="product-price"><?= formatPrice($product['price']) ?></div>
        <button class="btn btn-add-cart" data-product-id="<?= $product['id'] ?>">Add to Sacred Cart</button>
      </div>
      <?php endforeach; ?>
    </section>
  </main>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.layout.php'; ?>

</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
