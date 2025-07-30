document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
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

    function addToCartWithProduct(productId, quantity = 1) {
        if (typeof window.MechaCart === 'undefined') {
            console.error('MechaCart not available');
            return false;
        }

        if (typeof window.productsData === 'undefined') {
            console.error('Products data not available');
            return false;
        }

        const cart = window.MechaCart.getCart();
        const product = window.productsData[productId];
        
        if (!product) {
            console.error('Product not found:', productId);
            return false;
        }

        if (cart[productId]) {
            cart[productId].quantity += quantity;
        } else {
            cart[productId] = {
                product: product,
                quantity: quantity
            };
        }

        window.MechaCart.saveCart(cart);
        return true;
    }

    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const originalText = this.textContent;
            
            this.textContent = 'Adding...';
            this.disabled = true;

            if (addToCartWithProduct(productId, 1)) {
                this.textContent = 'Added!';
                this.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                
                if (window.productsData && window.productsData[productId]) {
                    const productName = window.productsData[productId].name;
                    showNotification(`${productName} added to Sacred Cart!`, 'success');
                } else {
                    showNotification('Product added to Sacred Cart!', 'success');
                }
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.background = 'linear-gradient(135deg, var(--primary-gold), var(--dark-bronze))';
                    this.disabled = false;
                }, 2000);
            } else {
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
