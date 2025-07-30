<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../utils/auth.util.php';
require_once __DIR__ . '/../../utils/cart.util.php';
require_once __DIR__ . '/../../layouts/main.layout.php';

// Check if the user is logged in
$loggedIn = isAuthenticated();
// AUTHENTICATION TEMPORARILY DISABLED FOR DEVELOPMENT
// if (!$loggedIn) {
//     header("Location: ../../login.php");
//     exit;
// }
$cartItems = getCartItems();
$cartTotal = getCartTotal();
$cartCount = getCartItemCount();

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sacred Cart - Mechanicus Health Emporium</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
      <div class="white-box">
        <h1>Sacred Cart</h1>
        <p>Your blessed healing supplies await the Emperor's approval</p>
      </div>
    </section>

    <section class="cart-content">
      <div class="cart-items white-box">
        <h2>Items in Your Sacred Cart</h2>
        
        <!-- This will be populated by JavaScript -->
        <div id="cart-display">
          <div class="empty-cart" id="empty-cart-message">
            <i class="fas fa-shopping-cart"></i>
            <p>Your cart is currently empty</p>
            <p>Browse our blessed products and add them to your cart</p>
            <a href="../../pages/products/index.php" class="btn btn-primary">Browse Products</a>
          </div>
          <div class="cart-items-list" id="cart-items-list" style="display: none;">
            <!-- Cart items will be inserted here by JavaScript -->
          </div>
        </div>
      </div>

      <div class="cart-summary white-box">
        <h3>Order Summary</h3>
        <div class="summary-line">
          <span>Subtotal:</span>
          <span id="subtotal">₱0.00</span>
        </div>
        <div class="summary-line">
          <span>Imperial Tax (12%):</span>
          <span id="tax">₱0.00</span>
        </div>
        <div class="summary-line">
          <span>Shipping:</span>
          <span id="shipping">₱0.00</span>
        </div>
        <div class="summary-line total">
          <span>Total:</span>
          <span id="total">₱0.00</span>
        </div>
        <a href="../../pages/checkout/index.php" class="btn btn-checkout" id="checkout-btn">Proceed to Checkout</a>
      </div>
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

    .cart-content {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .cart-items {
      min-height: 400px;
    }

    .empty-cart {
      text-align: center;
      padding: 60px 20px;
    }

    .empty-cart i {
      font-size: 4em;
      color: var(--primary-gold);
      margin-bottom: 20px;
    }

    .cart-summary {
      height: fit-content;
      padding: 30px;
    }

    .summary-line {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .summary-line.total {
      font-weight: bold;
      font-size: 1.2em;
      border-bottom: 2px solid var(--primary-gold);
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
      margin-top: 10px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-gold), var(--dark-bronze));
      color: white;
    }

    .btn-primary:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .btn-checkout {
      background: linear-gradient(135deg, var(--imperial-red), var(--dark-bronze));
      color: white;
      width: 100%;
    }

    .btn-checkout:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .cart-items-list {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      border: 2px solid var(--primary-gold);
      border-radius: 10px;
      background: rgba(245, 245, 220, 0.5);
    }

    .item-info {
      display: flex;
      align-items: center;
      gap: 20px;
      flex: 1;
    }

    .item-info i {
      font-size: 2em;
      color: var(--primary-gold);
    }

    .item-details h4 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      margin-bottom: 5px;
    }

    .item-details p {
      color: var(--dark-bronze);
      font-size: 0.9em;
      margin-bottom: 5px;
    }

    .item-price {
      font-weight: bold;
      color: var(--imperial-red);
    }

    .item-controls {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .quantity-controls {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .qty-btn {
      width: 30px;
      height: 30px;
      border: 1px solid var(--primary-gold);
      background: var(--primary-gold);
      color: white;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    .qty-btn:hover {
      background: var(--dark-bronze);
    }

    .quantity-input {
      width: 60px;
      text-align: center;
      padding: 5px;
      border: 1px solid var(--primary-gold);
      border-radius: 5px;
    }

    .item-total {
      font-weight: bold;
      color: var(--imperial-red);
      min-width: 100px;
      text-align: right;
    }

    .remove-btn {
      background: var(--imperial-red);
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
    }

    .remove-btn:hover {
      background: #660000;
    }

    @media (max-width: 768px) {
      .cart-content {
        grid-template-columns: 1fr;
      }

      .cart-item {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }

      .item-controls {
        justify-content: center;
      }
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Display cart items from localStorage
      function displayCart() {
        const cart = MechaCart.getCart();
        const cartItemsList = document.getElementById('cart-items-list');
        const emptyMessage = document.getElementById('empty-cart-message');
        
        // Clear current display
        cartItemsList.innerHTML = '';
        
        const cartEntries = Object.entries(cart);
        
        if (cartEntries.length === 0) {
          emptyMessage.style.display = 'block';
          cartItemsList.style.display = 'none';
          updateCartSummary(0, 0);
          return;
        }
        
        emptyMessage.style.display = 'none';
        cartItemsList.style.display = 'block';
        
        let subtotal = 0;
        
        cartEntries.forEach(([productId, item]) => {
          if (!item.product) return; // Skip items without product data
          
          const itemTotal = item.product.price * item.quantity;
          subtotal += itemTotal;
          
          const cartItemHtml = `
            <div class="cart-item" data-product-id="${productId}">
              <div class="item-info">
                <i class="${item.product.icon || 'fas fa-box'}"></i>
                <div class="item-details">
                  <h4>${item.product.name}</h4>
                  <p>${item.product.description}</p>
                  <span class="item-price">₱${item.product.price.toFixed(2)} each</span>
                </div>
              </div>
              <div class="item-controls">
                <div class="quantity-controls">
                  <button class="qty-btn minus" data-product-id="${productId}">-</button>
                  <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-product-id="${productId}">
                  <button class="qty-btn plus" data-product-id="${productId}">+</button>
                </div>
                <div class="item-total">₱${itemTotal.toFixed(2)}</div>
                <button class="remove-btn" data-product-id="${productId}">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          `;
          
          cartItemsList.innerHTML += cartItemHtml;
        });
        
        updateCartSummary(subtotal, cartEntries.length);
        attachEventListeners();
      }
      
      function updateCartSummary(subtotal, itemCount) {
        const tax = subtotal * 0.12;
        const shipping = subtotal > 0 ? 99 : 0;
        const total = subtotal + tax + shipping;
        
        document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `₱${tax.toFixed(2)}`;
        document.getElementById('shipping').textContent = `₱${shipping.toFixed(2)}`;
        document.getElementById('total').textContent = `₱${total.toFixed(2)}`;
        
        // Enable/disable checkout button based on cart contents
        const checkoutBtn = document.getElementById('checkout-btn');
        if (itemCount > 0) {
          checkoutBtn.style.opacity = '1';
          checkoutBtn.style.pointerEvents = 'auto';
          checkoutBtn.style.cursor = 'pointer';
        } else {
          checkoutBtn.style.opacity = '0.5';
          checkoutBtn.style.pointerEvents = 'none';
          checkoutBtn.style.cursor = 'not-allowed';
        }
      }
      
      function attachEventListeners() {
        // Quantity update functionality
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const qtyButtons = document.querySelectorAll('.qty-btn');
        const removeButtons = document.querySelectorAll('.remove-btn');

        // Handle quantity input changes
        quantityInputs.forEach(input => {
          input.addEventListener('change', function() {
            const newQty = parseInt(this.value);
            if (newQty > 0) {
              MechaCart.updateQuantity(this.dataset.productId, newQty);
              displayCart(); // Refresh display
            }
          });
        });

        // Handle plus/minus buttons
        qtyButtons.forEach(button => {
          button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const input = document.querySelector(`input[data-product-id="${productId}"]`);
            const currentQty = parseInt(input.value);
            
            if (this.classList.contains('plus')) {
              MechaCart.updateQuantity(productId, currentQty + 1);
            } else if (this.classList.contains('minus') && currentQty > 1) {
              MechaCart.updateQuantity(productId, currentQty - 1);
            }
            displayCart(); // Refresh display
          });
        });

        // Handle remove buttons
        removeButtons.forEach(button => {
          button.addEventListener('click', function() {
            if (confirm('Remove this item from your Sacred Cart?')) {
              MechaCart.removeFromCart(this.dataset.productId);
              displayCart(); // Refresh display
            }
          });
        });
      }
      
      // Listen for cart updates from other pages
      document.addEventListener('cartUpdated', function() {
        displayCart();
      });
      
      // Initial display
      displayCart();
    });
  </script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
