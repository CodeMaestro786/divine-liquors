<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$orderId = intval($_GET['order_id'] ?? 0);
$order = getOrderById($orderId);

if (!$order) {
    header('Location: products.php');
    exit;
}

$items = getOrderItems($orderId);
$branch = getBranchById($order['branch_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation | Divine Liquors</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/confirmation.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    
    <main class="confirmation-page">
        <div class="confirmation-container">
            <div class="confirmation-header">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Order Confirmed!</h1>
                <p>Thank you for your order, <?= htmlspecialchars($order['customer_name']) ?>!</p>
                <p>Your order #<?= $orderId ?> has been received and is being processed.</p>
            </div>
            
            <div class="order-summary">
                <h2>Order Summary</h2>
                
                <div class="order-items">
                    <?php foreach ($items as $item): ?>
                        <div class="order-item">
                            <div class="item-image">
                                <?php if ($item['image_url']): ?>
                                    <img src="<?= $item['image_url'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php else: ?>
                                    <div class="no-image"><i class="fas fa-wine-bottle"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="item-details">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="price">R <?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?></p>
                            </div>
                            <div class="item-total">
                                R <?= number_format($item['price'] * $item['quantity'], 2) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>R <?= number_format($order['total_amount'] / 1.15, 2) ?></span>
                    </div>
                    <div class="total-row">
                        <span>Tax (15%):</span>
                        <span>R <?= number_format($order['total_amount'] * 0.15, 2) ?></span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total:</span>
                        <span>R <?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="order-details">
                <div class="detail-card">
                    <h3><i class="fas fa-user"></i> Customer Information</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                </div>
                
                <div class="detail-card">
                    <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                    <p><strong>Method:</strong> 
                        <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?>
                        (<?= ucfirst($order['payment_status']) ?>)
                    </p>
                    <p><strong>Order Status:</strong> 
                        <span class="status-badge <?= $order['status'] ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </p>
                </div>
                
                <div class="detail-card">
                    <h3><i class="fas fa-store"></i> Pickup Information</h3>
                    <p><strong>Branch:</strong> <?= htmlspecialchars($branch['name']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($branch['address']) ?></p>
                    <p><strong>Hours:</strong> <?= htmlspecialchars($branch['hours']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($branch['phone']) ?></p>
                </div>
            </div>
            
            <div class="confirmation-actions">
                <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
                <a href="#" class="btn btn-primary" id="print-receipt">
                    <i class="fas fa-print"></i> Print Receipt
                </a>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        document.getElementById('print-receipt').addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    </script>
</body>
</html>