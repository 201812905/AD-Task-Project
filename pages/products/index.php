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
  <link rel="stylesheet" href="../../assets/css/style.css" />
  <script src="../../assets/js/cart.js"></script>
  <script src="../../assets/js/loading.js"></script>
</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <div class="top-bar">
      <div class="title">MECHANICUS HEALTH EMPORIUM</div>
      <a href="../../pages/cart/index.php" class="cart" id="cart-link">Sacred Cart: <span id="cart-total"><?= formatPrice(getCartTotal()) ?></span> (<span id="cart-count"><?= getCartItemCount() ?></span>)</a>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="../../pages/home/index.php">Sacred Home</a></li>
        <li><a href="../../pages/products/index.php" class="active">Blessed Products</a></li>
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

    .product-filters {
      margin-bottom: 40px;
      text-align: center;
    }

    .filter-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 20px;
    }

    .filter-btn {
      padding: 10px 20px;
      border: 2px solid var(--primary-gold);
      background: transparent;
      color: var(--dark-bronze);
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: bold;
    }

    .filter-btn:hover,
    .filter-btn.active {
      background: var(--primary-gold);
      color: white;
      transform: scale(1.05);
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .product-card {
      text-align: center;
      padding: 30px;
      transition: all 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .product-image {
      font-size: 4em;
      color: var(--primary-gold);
      margin-bottom: 20px;
    }

    .product-card h3 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      margin-bottom: 15px;
      font-size: 1.5em;
    }

    .product-card p {
      color: var(--dark-bronze);
      margin-bottom: 20px;
      line-height: 1.5;
    }

    .product-price {
      font-size: 1.5em;
      font-weight: bold;
      color: var(--imperial-red);
      margin-bottom: 20px;
    }

    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      transition: all 0.3s ease;
    }

    .btn-add-cart {
      background: linear-gradient(135deg, var(--primary-gold), var(--dark-bronze));
      color: white;
    }

    .btn-add-cart:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .product-card.hidden {
      display: none;
    }

    @media (max-width: 768px) {
      .filter-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .products-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <script>
    // Product filtering functionality
    document.addEventListener('DOMContentLoaded', function() {
      const filterButtons = document.querySelectorAll('.filter-btn');
      const productCards = document.querySelectorAll('.product-card');

      filterButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all buttons
          filterButtons.forEach(btn => btn.classList.remove('active'));
          // Add active class to clicked button
          this.classList.add('active');

          const category = this.getAttribute('data-category');

          productCards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
              card.classList.remove('hidden');
            } else {
              card.classList.add('hidden');
            }
          });
        });
      });

      // Client-side cart functionality using shared cart system
      
      // Product data for JavaScript
      const products = <?= json_encode($products) ?>;
      
      // Enhanced add to cart function with product data
      function addToCartWithProduct(productId, quantity = 1) {
        const cart = MechaCart.getCart();
        const product = products[productId];
        
        if (!product) return false;

        if (cart[productId]) {
          cart[productId].quantity += quantity;
        } else {
          cart[productId] = {
            product: product,
            quantity: quantity
          };
        }

        MechaCart.saveCart(cart);
        return true;
      }

      // Override the cart totals function to use our product data
      const originalGetTotals = MechaCart.getCartTotals;
      MechaCart.getCartTotals = function() {
        return originalGetTotals.call(this, products);
      };

      // Add to cart button functionality
      const addToCartButtons = document.querySelectorAll('.btn-add-cart');
      addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
          const productId = parseInt(this.getAttribute('data-product-id'));
          const originalText = this.textContent;
          
          // Show loading state
          this.textContent = 'Adding...';
          this.disabled = true;

          // Add to cart with full product data
          if (addToCartWithProduct(productId, 1)) {
            // Show success feedback
            this.textContent = 'Added!';
            this.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            
            // Show notification
            const productName = products[productId].name;
            showNotification(`${productName} added to Sacred Cart!`, 'success');
            
            setTimeout(() => {
              this.textContent = originalText;
              this.style.background = 'linear-gradient(135deg, var(--primary-gold), var(--dark-bronze))';
              this.disabled = false;
            }, 2000);
          } else {
            // Show error
            this.textContent = 'Error!';
            this.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
            showNotification('Failed to add item to cart', 'error');
            
            setTimeout(() => {
              this.textContent = originalText;
              this.style.background = 'linear-gradient(135deg, var(--primary-gold), var(--dark-bronze))';
              this.disabled = false;
            }, 2000);
          }
        });
      });
    });

    // Notification system
    function showNotification(message, type) {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.textContent = message;
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        border-radius: 5px;
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        animation: slideIn 0.3s ease;
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    }

    // Add CSS for notifications
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
      }
      @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  </script>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.layout.php'; ?>

</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
