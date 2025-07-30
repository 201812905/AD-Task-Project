<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../utils/auth.util.php';
require_once __DIR__ . '/../../layouts/main.layout.php';

// Check if the user is logged in
$loggedIn = isAuthenticated();
$user = $loggedIn ? getAuthenticatedUser() : null;

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Confirmed - Mechanicus Health Emporium</title>
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
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Sacred Order Confirmed!</h1>
        <p>The Omnissiah has blessed your transaction</p>
      </div>
    </section>

    <section class="order-details">
      <div class="order-info white-box">
        <h2>Order Information</h2>
        <div id="order-details-content">
          <p>Loading order details...</p>
        </div>
        
        <div class="order-status">
          <h3>Current Status: <span class="status-preparing">Preparing for Sacred Shipment</span></h3>
          <div class="status-timeline">
            <div class="status-step active">
              <i class="fas fa-check"></i>
              <span>Order Confirmed</span>
            </div>
            <div class="status-step active">
              <i class="fas fa-cog"></i>
              <span>Preparing</span>
            </div>
            <div class="status-step">
              <i class="fas fa-shipping-fast"></i>
              <span>Shipped</span>
            </div>
            <div class="status-step">
              <i class="fas fa-home"></i>
              <span>Delivered</span>
            </div>
          </div>
        </div>
        
        <div class="order-actions">
          <a href="../../pages/products/index.php" class="btn btn-secondary">Continue Sacred Shopping</a>
                    <a href="../../pages/home/index.php" class="btn btn-primary">Return to Sacred Home</a>
        </div>
      </div>
    </section>
  </main>

  <style>
    .page-header {
      margin-bottom: 40px;
      text-align: center;
    }

    .success-icon {
      font-size: 4em;
      color: #28a745;
      margin-bottom: 20px;
    }

    .page-header h1 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      font-size: 2.5em;
      margin-bottom: 10px;
    }

    .order-details {
      max-width: 800px;
      margin: 0 auto;
    }

    .order-info {
      padding: 30px;
    }

    .order-info h2 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      border-bottom: 2px solid var(--primary-gold);
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .order-summary-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }

    .order-summary-item:last-child {
      border-bottom: none;
    }

    .item-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .item-info i {
      font-size: 1.5em;
      color: var(--primary-gold);
    }

    .customer-info {
      background: rgba(245, 245, 220, 0.3);
      padding: 20px;
      border-radius: 10px;
      margin: 20px 0;
    }

    .customer-info h4 {
      color: var(--imperial-red);
      margin-bottom: 10px;
    }

    .order-totals {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid var(--primary-gold);
    }

    .total-line {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .total-line.final {
      font-weight: bold;
      font-size: 1.2em;
      color: var(--imperial-red);
    }

    .order-status {
      margin: 30px 0;
      padding: 20px;
      background: rgba(245, 245, 220, 0.3);
      border-radius: 10px;
    }

    .status-preparing {
      color: var(--primary-gold);
      font-weight: bold;
    }

    .status-timeline {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
      position: relative;
    }

    .status-timeline::before {
      content: '';
      position: absolute;
      top: 20px;
      left: 0;
      right: 0;
      height: 2px;
      background: #ddd;
      z-index: 1;
    }

    .status-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      background: white;
      padding: 0 10px;
      z-index: 2;
      position: relative;
    }

    .status-step i {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #ddd;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      margin-bottom: 10px;
    }

    .status-step.active i {
      background: var(--primary-gold);
    }

    .status-step span {
      font-size: 0.9em;
      text-align: center;
    }

    .order-actions {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin-top: 30px;
    }

    .btn {
      padding: 15px 30px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      transition: all 0.3s ease;
      font-size: 16px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--imperial-red), var(--dark-bronze));
      color: white;
    }

    .btn-secondary {
      background: linear-gradient(135deg, var(--primary-gold), #f0d000);
      color: var(--dark-bronze);
    }

    .btn:hover {
      transform: scale(1.02);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
      .status-timeline {
        flex-wrap: wrap;
        gap: 20px;
      }

      .order-actions {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Display order details
      function displayOrderDetails() {
        const orderData = localStorage.getItem('currentOrder');
        const orderDetailsDiv = document.getElementById('order-details-content');
        
        if (!orderData) {
          orderDetailsDiv.innerHTML = `
            <p>No order found. <a href="../../pages/products/">Continue shopping</a></p>
          `;
          return;
        }
        
        const order = JSON.parse(orderData);
        const orderDate = new Date(order.orderDate);
        
        let orderHtml = `
          <div class="order-header">
            <p><strong>Order ID:</strong> ${order.orderId}</p>
            <p><strong>Order Date:</strong> ${orderDate.toLocaleDateString()}</p>
          </div>
          
          <div class="customer-info">
            <h4>Shipping Information</h4>
            <p><strong>Name:</strong> ${order.customerInfo.name}</p>
            <p><strong>Email:</strong> ${order.customerInfo.email}</p>
            <p><strong>Address:</strong> ${order.customerInfo.address}</p>
            <p><strong>City:</strong> ${order.customerInfo.city} ${order.customerInfo.postal}</p>
            <p><strong>Payment Method:</strong> ${order.customerInfo.payment.charAt(0).toUpperCase() + order.customerInfo.payment.slice(1)}</p>
          </div>
          
          <h4>Ordered Items</h4>
        `;
        
        let subtotal = 0;
        const cartEntries = Object.entries(order.items);
        
        cartEntries.forEach(([productId, item]) => {
          if (item.product) {
            const itemTotal = item.product.price * item.quantity;
            subtotal += itemTotal;
            
            orderHtml += `
              <div class="order-summary-item">
                <div class="item-info">
                  <i class="${item.product.icon || 'fas fa-box'}"></i>
                  <div>
                    <strong>${item.product.name}</strong><br>
                    <small>${item.product.description}</small><br>
                    <small>Quantity: ${item.quantity} × ₱${item.product.price.toFixed(2)}</small>
                  </div>
                </div>
                <span><strong>₱${itemTotal.toFixed(2)}</strong></span>
              </div>
            `;
          }
        });
        
        const tax = subtotal * 0.12;
        const shipping = subtotal > 0 ? 99 : 0;
        const total = subtotal + tax + shipping;
        
        orderHtml += `
          <div class="order-totals">
            <div class="total-line">
              <span>Subtotal:</span>
              <span>₱${subtotal.toFixed(2)}</span>
            </div>
            <div class="total-line">
              <span>Tax (12%):</span>
              <span>₱${tax.toFixed(2)}</span>
            </div>
            <div class="total-line">
              <span>Shipping:</span>
              <span>₱${shipping.toFixed(2)}</span>
            </div>
            <div class="total-line final">
              <span>Total:</span>
              <span>₱${total.toFixed(2)}</span>
            </div>
          </div>
        `;
        
        orderDetailsDiv.innerHTML = orderHtml;
        
        // Clear the order data after displaying (one-time view)
        // localStorage.removeItem('currentOrder');
      }
      
      // Initialize order display
      displayOrderDetails();
    });
  </script>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.layout.php'; ?>

</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
