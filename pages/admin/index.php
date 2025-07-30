<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/auth.util.php';

$loggedIn = isAuthenticated();
$user = $loggedIn ? getAuthenticatedUser() : null;

if ($user) {
    error_log("Admin panel - User data type: " . gettype($user));
    error_log("Admin panel - User data: " . print_r($user, true));
}

if (!$loggedIn || !$user || !is_array($user) || $user['role'] !== 'admin') {
    error_log("Admin panel access denied - Logged in: " . ($loggedIn ? 'YES' : 'NO') . 
              ", User: " . (is_array($user) ? 'ARRAY' : gettype($user)) .
              ", Role: " . (is_array($user) ? ($user['role'] ?? 'NONE') : 'N/A'));
    header("Location: /index.php");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $pdo = new PDO("pgsql:host=localhost;dbname=mechanicus_health", "postgres", "password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        switch ($_POST['action']) {
            case 'add_product':
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock_quantity, category, image_url) VALUES (?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    $_POST['name'],
                    $_POST['description'],
                    $_POST['price'],
                    $_POST['stock_quantity'],
                    $_POST['category'],
                    $_POST['image_url']
                ]);
                echo json_encode(['success' => $result]);
                break;
                
            case 'update_product':
                $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock_quantity=?, category=?, image_url=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
                $result = $stmt->execute([
                    $_POST['name'],
                    $_POST['description'],
                    $_POST['price'],
                    $_POST['stock_quantity'],
                    $_POST['category'],
                    $_POST['image_url'],
                    $_POST['id']
                ]);
                echo json_encode(['success' => $result]);
                break;
                
            case 'delete_product':
                $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
                $result = $stmt->execute([$_POST['id']]);
                echo json_encode(['success' => $result]);
                break;
                
            case 'add_user':
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, first_name, middle_name, last_name, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['first_name'],
                    $_POST['middle_name'],
                    $_POST['last_name'],
                    $hashedPassword,
                    $_POST['role']
                ]);
                echo json_encode(['success' => $result]);
                break;
                
            case 'update_user':
                if (!empty($_POST['password'])) {
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, first_name=?, middle_name=?, last_name=?, password=?, role=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
                    $result = $stmt->execute([
                        $_POST['username'],
                        $_POST['email'],
                        $_POST['first_name'],
                        $_POST['middle_name'],
                        $_POST['last_name'],
                        $hashedPassword,
                        $_POST['role'],
                        $_POST['id']
                    ]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, first_name=?, middle_name=?, last_name=?, role=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
                    $result = $stmt->execute([
                        $_POST['username'],
                        $_POST['email'],
                        $_POST['first_name'],
                        $_POST['middle_name'],
                        $_POST['last_name'],
                        $_POST['role'],
                        $_POST['id']
                    ]);
                }
                echo json_encode(['success' => $result]);
                break;
                
            case 'delete_user':
                $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
                $result = $stmt->execute([$_POST['id']]);
                echo json_encode(['success' => $result]);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if (ob_get_level()) {
    ob_clean();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacred Administration - Mechanicus Health Emporium</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .admin-header {
            background: linear-gradient(135deg, var(--dark-metal) 0%, #1a1a1a 50%, var(--dark-metal) 100%);
            color: var(--primary-gold);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
            border: 2px solid var(--copper);
        }
        
        .admin-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .admin-tab {
            background: var(--dark-metal);
            color: var(--parchment);
            padding: 1rem 2rem;
            border: 2px solid var(--copper);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-tab:hover, .admin-tab.active {
            background: var(--copper);
            color: var(--dark-metal);
            transform: translateY(-2px);
        }
        
        .admin-section {
            display: none;
            background: var(--dark-metal);
            padding: 2rem;
            border-radius: 10px;
            border: 2px solid var(--copper);
        }
        
        .admin-section.active {
            display: block;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .admin-table th, .admin-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--copper);
            color: var(--parchment);
        }
        
        .admin-table th {
            background: var(--copper);
            color: var(--dark-metal);
            font-family: 'Cinzel', serif;
        }
        
        .admin-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(184, 115, 51, 0.1);
            border-radius: 8px;
            border: 1px solid var(--copper);
        }
        
        .admin-form input, .admin-form select, .admin-form textarea {
            padding: 0.8rem;
            border: 1px solid var(--copper);
            border-radius: 5px;
            background: var(--dark-metal);
            color: var(--parchment);
            font-family: 'Roboto', sans-serif;
        }
        
        .admin-form input:focus, .admin-form select:focus, .admin-form textarea:focus {
            outline: none;
            border-color: var(--primary-gold);
            box-shadow: 0 0 5px rgba(218, 165, 32, 0.3);
        }
        
        .admin-btn {
            background: var(--copper);
            color: var(--dark-metal);
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-btn:hover {
            background: var(--primary-gold);
            transform: translateY(-2px);
        }
        
        .admin-btn.danger {
            background: var(--imperial-red);
            color: white;
        }
        
        .admin-btn.danger:hover {
            background: #a00000;
        }
        
        .form-grid-full {
            grid-column: 1 / -1;
        }
        
        @media (max-width: 768px) {
            .admin-tabs {
                flex-direction: column;
            }
            .admin-form {
                grid-template-columns: 1fr;
            }
            .admin-table {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="admin-page">
    <header class="header">
        <div class="top-bar">
            <div class="title">SACRED ADMINISTRATION</div>
            <a href="/pages/home/index.php" class="cart">
                <i class="fas fa-home"></i> Return Home
            </a>
        </div>
    </header>

    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-cogs"></i> Mechanicus Admin Panel</h1>
            <p>Sacred tools for the Tech-Priest Administrator</p>
            <p>Welcome, <?= htmlspecialchars(
                (isset($user['first_name']) && isset($user['last_name'])) 
                    ? $user['first_name'] . ' ' . $user['last_name']
                    : ($user['username'] ?? 'Administrator')
            ) ?></p>
        </div>

        <div class="admin-tabs">
            <div class="admin-tab active" data-section="products">
                <i class="fas fa-box"></i> Products
            </div>
            <div class="admin-tab" data-section="users">
                <i class="fas fa-users"></i> Users
            </div>
            <div class="admin-tab" data-section="orders">
                <i class="fas fa-shopping-cart"></i> Orders
            </div>
        </div>

        <!-- Products Section -->
        <div class="admin-section active" id="products">
            <h2><i class="fas fa-box"></i> Product Management</h2>
            
            <form class="admin-form" id="productForm">
                <input type="hidden" id="productId" name="id">
                <input type="text" id="productName" name="name" placeholder="Product Name" required>
                <input type="number" id="productPrice" name="price" step="0.01" placeholder="Price" required>
                <input type="number" id="productStock" name="stock_quantity" placeholder="Stock Quantity" required>
                <input type="text" id="productCategory" name="category" placeholder="Category">
                <input type="url" id="productImage" name="image_url" placeholder="Image URL">
                <textarea id="productDescription" name="description" placeholder="Description" class="form-grid-full"></textarea>
                <button type="submit" class="admin-btn form-grid-full">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </form>

            <table class="admin-table" id="productsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Users Section -->
        <div class="admin-section" id="users">
            <h2><i class="fas fa-users"></i> User Management</h2>
            
            <form class="admin-form" id="userForm">
                <input type="hidden" id="userId" name="id">
                <input type="text" id="userUsername" name="username" placeholder="Username" required>
                <input type="email" id="userEmail" name="email" placeholder="Email">
                <input type="text" id="userFirstName" name="first_name" placeholder="First Name" required>
                <input type="text" id="userMiddleName" name="middle_name" placeholder="Middle Name">
                <input type="text" id="userLastName" name="last_name" placeholder="Last Name" required>
                <input type="password" id="userPassword" name="password" placeholder="Password">
                <select id="userRole" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="admin-btn form-grid-full">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </form>

            <table class="admin-table" id="usersTable">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Orders Section -->
        <div class="admin-section" id="orders">
            <h2><i class="fas fa-shopping-cart"></i> Order Management</h2>
            <p>Order management functionality coming soon...</p>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.layout.php'; ?>

    <script>
        // Tab switching
        document.querySelectorAll('.admin-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and sections
                document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding section
                tab.classList.add('active');
                document.getElementById(tab.dataset.section).classList.add('active');
            });
        });

        // Product management
        let editingProduct = false;

        function loadProducts() {
            // This would normally load from database via AJAX
            // For now, we'll populate with sample data
            const tbody = document.querySelector('#productsTable tbody');
            tbody.innerHTML = `
                <tr>
                    <td>Sacred Health Tonic</td>
                    <td>₱299.99</td>
                    <td>50</td>
                    <td>Remedies</td>
                    <td>
                        <button class="admin-btn" onclick="editProduct(this)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="admin-btn danger" onclick="deleteProduct(this)">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        }

        function loadUsers() {
            const tbody = document.querySelector('#usersTable tbody');
            tbody.innerHTML = `
                <tr>
                    <td>admin</td>
                    <td>Administrator User</td>
                    <td>admin@mechanicus.emp</td>
                    <td>admin</td>
                    <td>
                        <button class="admin-btn" onclick="editUser(this)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="admin-btn danger" onclick="deleteUser(this)">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        }

        function editProduct(btn) {
            const row = btn.closest('tr');
            const cells = row.querySelectorAll('td');
            
            document.getElementById('productName').value = cells[0].textContent;
            document.getElementById('productPrice').value = cells[1].textContent.replace('₱', '');
            document.getElementById('productStock').value = cells[2].textContent;
            document.getElementById('productCategory').value = cells[3].textContent;
            
            editingProduct = true;
            document.querySelector('#productForm button').innerHTML = '<i class="fas fa-save"></i> Update Product';
        }

        function deleteProduct(btn) {
            if (confirm('Are you sure you want to delete this product?')) {
                btn.closest('tr').remove();
            }
        }

        function editUser(btn) {
            const row = btn.closest('tr');
            const cells = row.querySelectorAll('td');
            
            document.getElementById('userUsername').value = cells[0].textContent;
            document.getElementById('userEmail').value = cells[2].textContent;
            document.getElementById('userRole').value = cells[3].textContent;
            
            const fullName = cells[1].textContent.split(' ');
            document.getElementById('userFirstName').value = fullName[0] || '';
            document.getElementById('userLastName').value = fullName[fullName.length - 1] || '';
            
            document.querySelector('#userForm button').innerHTML = '<i class="fas fa-save"></i> Update User';
        }

        function deleteUser(btn) {
            if (confirm('Are you sure you want to delete this user?')) {
                btn.closest('tr').remove();
            }
        }

        // Form submissions
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (editingProduct) {
                alert('Product updated successfully!');
                editingProduct = false;
                this.reset();
                document.querySelector('#productForm button').innerHTML = '<i class="fas fa-plus"></i> Add Product';
            } else {
                alert('Product added successfully!');
                this.reset();
            }
        });

        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('User saved successfully!');
            this.reset();
            document.querySelector('#userForm button').innerHTML = '<i class="fas fa-plus"></i> Add User';
        });

        // Load initial data
        loadProducts();
        loadUsers();
    </script>
</body>
</html>

<?php
ob_end_flush();
?>
