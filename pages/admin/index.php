<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/auth.util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/admin.util.php';

$loggedIn = isAuthenticated();
$user = $loggedIn ? getAuthenticatedUser() : null;

if (!$loggedIn || !$user || !is_array($user) || $user['role'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

// Initialize admin utility
try {
    $adminUtil = new AdminUtil();
    $adminUtil->initializeTables();
} catch (Exception $e) {
    die("Database initialization failed. Please check the logs.");
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        switch ($_POST['action']) {
            case 'get_products':
                $products = $adminUtil->getAllProducts();
                echo json_encode(['success' => true, 'data' => $products]);
                break;
                
            case 'add_product':
                $result = $adminUtil->addProduct($_POST);
                echo json_encode(['success' => $result]);
                break;
                
            case 'update_product':
                $result = $adminUtil->updateProduct($_POST['id'], $_POST);
                echo json_encode(['success' => $result]);
                break;
                
            case 'delete_product':
                $result = $adminUtil->deleteProduct($_POST['id']);
                echo json_encode(['success' => $result]);
                break;
                
            case 'get_users':
                $users = $adminUtil->getAllUsers();
                echo json_encode(['success' => true, 'data' => $users]);
                break;
                
            case 'add_user':
                $result = $adminUtil->addUser($_POST);
                echo json_encode(['success' => $result]);
                break;
                
            case 'update_user':
                $result = $adminUtil->updateUser($_POST['id'], $_POST);
                echo json_encode(['success' => $result]);
                break;
                
            case 'delete_user':
                $result = $adminUtil->deleteUser($_POST['id']);
                echo json_encode(['success' => $result]);
                break;
                
            case 'ban_user':
                $result = $adminUtil->banUser($_POST['id']);
                echo json_encode(['success' => $result]);
                break;
                
            case 'unban_user':
                $result = $adminUtil->unbanUser($_POST['id']);
                echo json_encode(['success' => $result]);
                break;
                
            case 'get_orders':
                $orders = $adminUtil->getAllOrders();
                echo json_encode(['success' => true, 'data' => $orders]);
                break;
                
            case 'get_stats':
                $stats = $adminUtil->getStats();
                echo json_encode(['success' => true, 'data' => $stats]);
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
</head>
<body>
    <header class="header">
        <div class="top-bar">
            <div class="title">SACRED ADMINISTRATION</div>
            <div class="nav-links">
                <a href="/pages/home/index.php" class="nav-link">
                    <i class="fas fa-home"></i> Return Home
                </a>
                <a href="/logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
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

        <div class="admin-stats" id="adminStats">
            <div class="stat-card">
                <h3><i class="fas fa-box"></i> Products</h3>
                <div class="stat-number" id="statProducts">0</div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-users"></i> Users</h3>
                <div class="stat-number" id="statUsers">0</div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-shopping-cart"></i> Orders</h3>
                <div class="stat-number" id="statOrders">0</div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-exclamation-triangle"></i> Low Stock</h3>
                <div class="stat-number" id="statLowStock">0</div>
            </div>
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
            
            <div id="productMessages"></div>
            
            <form class="admin-form" id="productForm">
                <input type="hidden" id="productId" name="id">
                <input type="text" id="productName" name="name" placeholder="Product Name" required>
                <input type="number" id="productPrice" name="price" step="0.01" placeholder="Price" required>
                <input type="number" id="productStock" name="stock_quantity" placeholder="Stock Quantity" required>
                <input type="text" id="productCategory" name="category" placeholder="Category">
                <input type="url" id="productImage" name="image_url" placeholder="Image URL">
                <select id="productStatus" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <textarea id="productDescription" name="description" placeholder="Description"></textarea>
                <button type="submit" class="admin-btn form-grid-full">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </form>

            <div class="loading" id="productsLoading" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading products...
            </div>
            
            <table class="admin-table" id="productsTable" style="display: none;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Users Section -->
        <div class="admin-section" id="users">
            <h2><i class="fas fa-users"></i> User Management</h2>
            
            <div id="userMessages"></div>
            
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
                    <option value="banned">Banned</option>
                </select>
                <button type="submit" class="admin-btn form-grid-full">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </form>

            <div class="loading" id="usersLoading" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading users...
            </div>
            
            <table class="admin-table" id="usersTable" style="display: none;">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Orders Section -->
        <div class="admin-section" id="orders">
            <h2><i class="fas fa-shopping-cart"></i> Order Management</h2>
            
            <div class="loading" id="ordersLoading" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Loading orders...
            </div>
            
            <table class="admin-table" id="ordersTable" style="display: none;">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script src="/assets/js/admin.js"></script>
</body>
</html>

<?php
ob_end_flush();
?>
