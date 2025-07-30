<?php

require_once __DIR__ . '/../database/database.php';

class AdminUtil {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        if (!$this->pdo) {
            throw new Exception("Database connection not available");
        }
    }
    
    public function getAllProducts() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching products: " . $e->getMessage());
            return [];
        }
    }
    
    public function addProduct($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO products (name, description, price, stock_quantity, category, image_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $data['name'],
                $data['description'] ?? '',
                $data['price'],
                $data['stock_quantity'],
                $data['category'] ?? '',
                $data['image_url'] ?? '',
                $data['status'] ?? 'active'
            ]);
        } catch (PDOException $e) {
            error_log("Error adding product: " . $e->getMessage());
            throw new Exception("Failed to add product: " . $e->getMessage());
        }
    }
    
    public function updateProduct($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE products 
                SET name=?, description=?, price=?, stock_quantity=?, category=?, image_url=?, status=?, updated_at=CURRENT_TIMESTAMP 
                WHERE id=?
            ");
            
            return $stmt->execute([
                $data['name'],
                $data['description'] ?? '',
                $data['price'],
                $data['stock_quantity'],
                $data['category'] ?? '',
                $data['image_url'] ?? '',
                $data['status'] ?? 'active',
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            throw new Exception("Failed to update product: " . $e->getMessage());
        }
    }
    
    public function deleteProduct($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id=?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            throw new Exception("Failed to delete product: " . $e->getMessage());
        }
    }
    
    public function getProduct($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching product: " . $e->getMessage());
            return null;
        }
    }
    
    public function getAllUsers() {
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, email, first_name, middle_name, last_name, role, created_at, updated_at FROM users ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
            return [];
        }
    }
    
    public function addUser($data) {
        try {
            // Check if username already exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$data['username']]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Username already exists");
            }
            
            // Check if email already exists (if provided)
            if (!empty($data['email'])) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute([$data['email']]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Email already exists");
                }
            }
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, first_name, middle_name, last_name, password, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $data['username'],
                $data['email'] ?? null,
                $data['first_name'],
                $data['middle_name'] ?? null,
                $data['last_name'],
                $hashedPassword,
                $data['role'] ?? 'user'
            ]);
        } catch (PDOException $e) {
            error_log("Error adding user: " . $e->getMessage());
            throw new Exception("Failed to add user: " . $e->getMessage());
        }
    }
    
    public function updateUser($id, $data) {
        try {
            // Check if username already exists for other users
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
            $stmt->execute([$data['username'], $id]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Username already exists");
            }
            
            // Check if email already exists for other users (if provided)
            if (!empty($data['email'])) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$data['email'], $id]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Email already exists");
                }
            }
            
            if (!empty($data['password'])) {
                // Update with new password
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare("
                    UPDATE users 
                    SET username=?, email=?, first_name=?, middle_name=?, last_name=?, password=?, role=?, updated_at=CURRENT_TIMESTAMP 
                    WHERE id=?
                ");
                
                return $stmt->execute([
                    $data['username'],
                    $data['email'] ?? null,
                    $data['first_name'],
                    $data['middle_name'] ?? null,
                    $data['last_name'],
                    $hashedPassword,
                    $data['role'] ?? 'user',
                    $id
                ]);
            } else {
                // Update without changing password
                $stmt = $this->pdo->prepare("
                    UPDATE users 
                    SET username=?, email=?, first_name=?, middle_name=?, last_name=?, role=?, updated_at=CURRENT_TIMESTAMP 
                    WHERE id=?
                ");
                
                return $stmt->execute([
                    $data['username'],
                    $data['email'] ?? null,
                    $data['first_name'],
                    $data['middle_name'] ?? null,
                    $data['last_name'],
                    $data['role'] ?? 'user',
                    $id
                ]);
            }
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            throw new Exception("Failed to update user: " . $e->getMessage());
        }
    }
    
    public function deleteUser($id) {
        try {
            // Prevent deletion of the last admin user
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
            $stmt->execute();
            $adminCount = $stmt->fetchColumn();
            
            // Check if this user is an admin
            $stmt = $this->pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $userRole = $stmt->fetchColumn();
            
            if ($userRole === 'admin' && $adminCount <= 1) {
                throw new Exception("Cannot delete the last admin user");
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id=?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            throw new Exception("Failed to delete user: " . $e->getMessage());
        }
    }
    
    public function getUser($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, email, first_name, middle_name, last_name, role, created_at, updated_at FROM users WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return null;
        }
    }
    
    public function banUser($id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET role = 'banned', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error banning user: " . $e->getMessage());
            throw new Exception("Failed to ban user: " . $e->getMessage());
        }
    }
    
    public function unbanUser($id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET role = 'user', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error unbanning user: " . $e->getMessage());
            throw new Exception("Failed to unban user: " . $e->getMessage());
        }
    }
    
    public function getAllOrders() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, u.username, u.first_name, u.last_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.order_date DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching orders: " . $e->getMessage());
            return [];
        }
    }
    
    public function getOrderItems($orderId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT oi.*, p.name as product_name 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?
            ");
            $stmt->execute([$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching order items: " . $e->getMessage());
            return [];
        }
    }
    
    public function getPdo() {
        return $this->pdo;
    }
    
    public function initializeTables() {
        try {
            // Create tables if they don't exist (structure only, no sample data)
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                    username VARCHAR(50) UNIQUE NOT NULL,
                    email VARCHAR(100),
                    first_name VARCHAR(100),
                    middle_name VARCHAR(100),
                    last_name VARCHAR(100),
                    password_hash VARCHAR(255) NOT NULL,
                    role VARCHAR(20) NOT NULL DEFAULT 'user',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ");
            
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS products (
                    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                    name VARCHAR(255) UNIQUE NOT NULL,
                    description TEXT,
                    price DECIMAL(10, 2) NOT NULL,
                    stock_quantity INTEGER NOT NULL DEFAULT 0,
                    category VARCHAR(100),
                    image_url VARCHAR(500),
                    status VARCHAR(50) NOT NULL DEFAULT 'active',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ");
            
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS orders (
                    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                    user_id UUID,
                    total DECIMAL(10, 2),
                    status VARCHAR(50) DEFAULT 'pending',
                    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    shipping_address TEXT,
                    payment_method VARCHAR(50),
                    FOREIGN KEY (user_id) REFERENCES users(id)
                );
            ");
            
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS order_items (
                    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
                    order_id UUID NOT NULL,
                    product_id UUID NOT NULL,
                    quantity INTEGER NOT NULL,
                    price DECIMAL(10, 2) NOT NULL,
                    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                    FOREIGN KEY (product_id) REFERENCES products(id)
                );
            ");
            
            // Create default admin user if not exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
            $stmt->execute();
            
            if ($stmt->fetchColumn() == 0) {
                $this->addUser([
                    'username' => 'admin',
                    'email' => 'admin@mechanicus.com',
                    'first_name' => 'Administrator',
                    'last_name' => 'User',
                    'password' => 'admin123',
                    'role' => 'admin'
                ]);
            }
            
            // Add sample products only if products table is empty
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products");
            $stmt->execute();
            
            if ($stmt->fetchColumn() == 0) {
                $sampleProducts = [
                    [
                        'name' => 'Sacred Healing Elixir',
                        'description' => 'Blessed by the Emperor\'s grace, cures minor ailments',
                        'price' => 299.99,
                        'stock_quantity' => 50,
                        'category' => 'Medicine',
                        'image_url' => '/assets/img/products/healing-elixir.jpg'
                    ],
                    [
                        'name' => 'Omnissiah\'s Diagnostic Tool',
                        'description' => 'Advanced medical scanner blessed with machine spirit',
                        'price' => 1999.99,
                        'stock_quantity' => 25,
                        'category' => 'Equipment',
                        'image_url' => '/assets/img/products/diagnostic-tool.jpg'
                    ],
                    [
                        'name' => 'Imperial Combat Stim',
                        'description' => 'Emergency healing injection for battlefield wounds',
                        'price' => 599.99,
                        'stock_quantity' => 75,
                        'category' => 'Medicine',
                        'image_url' => '/assets/img/products/combat-stim.jpg'
                    ],
                    [
                        'name' => 'Blessed Vitamins',
                        'description' => 'Essential nutrients blessed for optimal health',
                        'price' => 149.99,
                        'stock_quantity' => 100,
                        'category' => 'Supplements',
                        'image_url' => '/assets/img/products/vitamins.jpg'
                    ],
                    [
                        'name' => 'Medicae\'s Surgery Kit',
                        'description' => 'Complete surgical tools blessed by the Omnissiah',
                        'price' => 2499.99,
                        'stock_quantity' => 15,
                        'category' => 'Tools',
                        'image_url' => '/assets/img/products/surgery-kit.jpg'
                    ],
                    [
                        'name' => 'Sacred Thermometer',
                        'description' => 'Precision temperature measurement device',
                        'price' => 99.99,
                        'stock_quantity' => 80,
                        'category' => 'Equipment',
                        'image_url' => '/assets/img/products/thermometer.jpg'
                    ]
                ];
                
                foreach ($sampleProducts as $product) {
                    $this->addProduct($product);
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error initializing tables: " . $e->getMessage());
            return false;
        }
    }
    
    public function getStats() {
        try {
            $stats = [];
            
            // Product count
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE status = 'active'");
            $stmt->execute();
            $stats['products'] = $stmt->fetchColumn();
            
            // User count
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role != 'banned'");
            $stmt->execute();
            $stats['users'] = $stmt->fetchColumn();
            
            // Order count
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders");
            $stmt->execute();
            $stats['orders'] = $stmt->fetchColumn();
            
            // Low stock products
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE stock_quantity < 10 AND status = 'active'");
            $stmt->execute();
            $stats['low_stock'] = $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error fetching stats: " . $e->getMessage());
            return ['products' => 0, 'users' => 0, 'orders' => 0, 'low_stock' => 0];
        }
    }
}
