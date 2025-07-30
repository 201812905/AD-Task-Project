<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../utils/auth.util.php';
require_once __DIR__ . '/../../layouts/main.layout.php';

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sacred Knowledge - Mechanicus Health Emporium</title>
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
        <li><a href="../../pages/terms/index.php">Terms of Service</a></li>
        <li><a href="../../pages/faq/index.php" class="active">Sacred Knowledge</a></li>
      </ul>
    </nav>
  </header>

  <main class="main-content">
    <section class="page-header">
      <div class="white-box">
        <h1>Sacred Knowledge</h1>
        <p>Frequently Asked Questions - Blessed by the Omnissiah</p>
      </div>
    </section>

    <section class="faq-content">
      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-question-circle"></i> How do I place a sacred order?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Browse our blessed products, add them to your Sacred Cart, and proceed through our secured checkout process. The Omnissiah's blessing ensures your transaction is protected by sacred encryption.</p>
        </div>
      </div>

      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-shipping-fast"></i> What are your delivery times?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Imperial courier networks deliver within 3-5 Terran standard days for most sectors. Emergency medical supplies can be delivered within 24 hours via priority blessing protocols.</p>
        </div>
      </div>

      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-shield-alt"></i> Are your products blessed and certified?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>All products undergo sacred blessing rituals performed by certified Tech-Priests. Each item meets Imperial health standards and carries the blessing of the Omnissiah for optimal healing properties.</p>
        </div>
      </div>

      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-undo"></i> What is your return policy?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Items may be returned within 30 Terran days if unopened and in original blessed packaging. Medical devices require re-blessing upon return. Refunds are processed through the Imperial Treasury within 7-10 days.</p>
        </div>
      </div>

      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-credit-card"></i> What payment methods do you accept?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>We accept Imperial Credits, Thrones, Sacred Banking transfers, and blessed cryptocurrency. All transactions are secured by machine spirit encryption protocols for maximum protection.</p>
        </div>
      </div>

      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-phone"></i> How can I contact customer support?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Our Tech-Priest support staff are available 24/7 via vox-communication at 1-800-OMNISSIAH or through our sacred message system. Response times are typically within 2-4 hours.</p>
        </div>
      </div>

      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-globe"></i> Do you deliver to all Imperial worlds?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>We deliver to all worlds within the Imperium's reach, including frontier worlds and space stations. Delivery to war zones requires special clearance from the Adeptus Administratum.</p>
        </div>
      </div>

      <div class="faq-item white-box">
        <div class="faq-question">
          <h3><i class="fas fa-medkit"></i> Are there any side effects to blessed medicines?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Our blessed medicines are purified of all corrupting influences. Some users may experience enhanced spiritual awareness and improved machine interface capabilities as beneficial side effects of the Omnissiah's blessing.</p>
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

    .faq-content {
      max-width: 900px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .faq-item {
      padding: 0;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .faq-question {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      background: linear-gradient(135deg, rgba(218, 165, 32, 0.1), rgba(139, 68, 68, 0.1));
    }

    .faq-question:hover {
      background: linear-gradient(135deg, rgba(218, 165, 32, 0.2), rgba(139, 68, 68, 0.2));
    }

    .faq-question h3 {
      font-family: 'Cinzel', serif;
      color: var(--imperial-red);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .faq-question h3 i {
      color: var(--primary-gold);
    }

    .faq-question > i {
      color: var(--primary-gold);
      transition: transform 0.3s ease;
    }

    .faq-item.active .faq-question > i {
      transform: rotate(180deg);
    }

    .faq-answer {
      padding: 0 30px;
      max-height: 0;
      overflow: hidden;
      transition: all 0.3s ease;
      background: rgba(245, 245, 220, 0.5);
    }

    .faq-item.active .faq-answer {
      padding: 25px 30px;
      max-height: 200px;
    }

    .faq-answer p {
      margin: 0;
      line-height: 1.6;
      color: var(--dark-bronze);
    }

    @media (max-width: 768px) {
      .faq-question {
        padding: 20px;
      }

      .faq-question h3 {
        font-size: 1.1em;
      }

      .faq-item.active .faq-answer {
        padding: 20px;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const faqItems = document.querySelectorAll('.faq-item');

      faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', function() {
          // Close all other items
          faqItems.forEach(otherItem => {
            if (otherItem !== item) {
              otherItem.classList.remove('active');
            }
          });
          
          // Toggle current item
          item.classList.toggle('active');
        });
      });
    });
  </script>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
