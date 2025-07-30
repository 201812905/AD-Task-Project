<?php
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json');

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Read and decode JSON body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit;
}

// Extract data
$orderId = $data['orderId'] ?? null;
$orderDate = $data['orderDate'] ?? date('c');
$items = $data['items'] ?? [];
$customer = $data['customerInfo'] ?? [];
$totals = $data['totals'] ?? [];

$fullName = $customer['name'] ?? '';
$email = $customer['email'] ?? '';
$address = $customer['address'] ?? '';
$city = $customer['city'] ?? '';
$postal = $customer['postal'] ?? '';
$paymentMethod = $customer['payment'] ?? '';

// Extract totals
$subtotal = $totals['subtotal'] ?? 0;
$tax = $totals['tax'] ?? 0;
$shipping = $totals['shipping'] ?? 0;
$total = $totals['total'] ?? 0;

// Validate required fields
if (!$orderId || !$fullName || !$email || !$address || !$city || !$postal || empty($items)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Insert into orders table
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, full_name, email, address, city, postal_code, payment_method, subtotal, tax, shipping, total, order_date)
        VALUES (:user_id, :full_name, :email, :address, :city, :postal_code, :payment_method, :subtotal, :tax, :shipping, :total, :order_date)
        RETURNING id
    ");

    $stmt->execute([
        ':user_id' => null,
        ':full_name' => $fullName,
        ':email' => $email,
        ':address' => $address,
        ':city' => $city,
        ':postal_code' => $postal,
        ':payment_method' => $paymentMethod,
        ':subtotal' => $subtotal,
        ':tax' => $tax,
        ':shipping' => $shipping,
        ':total' => $total,
        ':order_date' => $orderDate
    ]);

    // Get the inserted order's ID
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $orderDbId = (int)$result['id'];

    // Insert order items
    $itemStmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
        VALUES (:order_id, :product_id, :product_name, :quantity, :price)
    ");

    foreach ($items as $productId => $item) {
        $product = $item['product'] ?? null;
        $quantity = $item['quantity'] ?? 0;

        if (!$product || $quantity <= 0) {
            continue;
        }

        $itemStmt->execute([
            ':order_id' => $orderDbId,
            ':product_id' => (string)$product['id'], // Ensure UUID is string
            ':product_name' => $product['name'],
            ':quantity' => (int)$quantity,
            ':price' => (float)$product['price']
        ]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'order_id' => $orderDbId]);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log('❌ Order save failed: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>