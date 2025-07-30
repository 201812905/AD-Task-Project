window.MechaCart = {
    getCart: function() {
        const cart = localStorage.getItem('mechaCart');
        return cart ? JSON.parse(cart) : {};
    },

    saveCart: function(cart) {
        localStorage.setItem('mechaCart', JSON.stringify(cart));
        this.updateAllCartDisplays();
    },

    addToCart: function(productId, quantity = 1) {
        const cart = this.getCart();
        
        if (cart[productId]) {
            cart[productId].quantity += quantity;
        } else {
            cart[productId] = {
                quantity: quantity,
                product: null
            };
        }

        this.saveCart(cart);
        return true;
    },

    removeFromCart: function(productId) {
        const cart = this.getCart();
        if (cart[productId]) {
            delete cart[productId];
            this.saveCart(cart);
        }
    },

    updateQuantity: function(productId, quantity) {
        const cart = this.getCart();
        if (quantity <= 0) {
            this.removeFromCart(productId);
        } else if (cart[productId]) {
            cart[productId].quantity = quantity;
            this.saveCart(cart);
        }
    },

    getCartTotals: function(products = null) {
        const cart = this.getCart();
        let totalItems = 0;
        let totalPrice = 0;

        for (const [productId, item] of Object.entries(cart)) {
            totalItems += item.quantity;
            
            if (products && products[productId]) {
                totalPrice += products[productId].price * item.quantity;
            } else if (item.product && item.product.price) {
                totalPrice += item.product.price * item.quantity;
            }
        }

        return {
            totalItems: totalItems,
            totalPrice: totalPrice,
            formattedPrice: '₱' + totalPrice.toFixed(2)
        };
    },

    clearCart: function() {
        localStorage.removeItem('mechaCart');
        this.updateAllCartDisplays();
    },

    updateAllCartDisplays: function() {
        const cartLink = document.getElementById('cart-link');
        if (cartLink) {
            const totals = this.getCartTotals();
            cartLink.innerHTML = `Sacred Cart: ${totals.formattedPrice} (${totals.totalItems})`;
        }

        const cartCount = document.getElementById('cart-count');
        const cartTotal = document.getElementById('cart-total');
        
        if (cartCount || cartTotal) {
            const totals = this.getCartTotals();
            if (cartCount) cartCount.textContent = totals.totalItems;
            if (cartTotal) cartTotal.textContent = totals.formattedPrice;
        }

        document.dispatchEvent(new CustomEvent('cartUpdated', { 
            detail: { cart: this.getCart(), totals: this.getCartTotals() }
        }));
    },

    init: function() {
        this.updateAllCartDisplays();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    MechaCart.init();
});
