<!DOCTYPE html>
<html>
<head>
    <title>Clear Cart</title>
</head>
<body>
    <h1>Clear Cart Data</h1>
    <button onclick="clearCart()">Clear Cart</button>
    <p id="status"></p>
    
    <script>
        function clearCart() {
            localStorage.removeItem('mechaCart');
            document.getElementById('status').innerHTML = '✅ Cart cleared successfully!';
        }
        
        // Show current cart
        const cart = localStorage.getItem('mechaCart');
        if (cart) {
            document.getElementById('status').innerHTML = '<strong>Current cart:</strong><br><pre>' + cart + '</pre>';
        } else {
            document.getElementById('status').innerHTML = 'Cart is empty';
        }
    </script>
</body>
</html>
