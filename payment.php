<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Redirect if checkout data not set
if (empty($_SESSION['checkout_data']) || empty($_SESSION['cart'])) {
    header('Location: checkout.php');
    exit;
}

// Process payment selection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? '';
    $branchId = $_POST['branch_id'] ?? 0;
    
    if (in_array($paymentMethod, ['pay_now', 'pay_later']) && $branchId > 0) {
        // Create order
        $orderId = createOrder($_SESSION['checkout_data'], $_SESSION['cart'], $paymentMethod, $branchId);
        
        if ($orderId) {
            if ($paymentMethod === 'pay_now') {
                // Redirect to PayFast
                header('Location: process_payment.php?order_id=' . $orderId);
                exit;
            } else {
                // Pay Later - send OTP
                $otp = generateOTP($orderId, $_SESSION['checkout_data']['phone']);
                $_SESSION['otp_order_id'] = $orderId;
                header('Location: otp_verification.php');
                exit;
            }
        }
    }
    
    $error = "Please select a payment method and pickup branch";
}

$branches = getBranches();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Method | Divine Liquors</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/payment.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    
    <main class="payment-page">
        <div class="payment-container">
            <div class="checkout-steps">
                <div class="step completed">
                    <span>1</span>
                    <p>Customer Info</p>
                </div>
                <div class="step active">
                    <span>2</span>
                    <p>Payment</p>
                </div>
                <div class="step">
                    <span>3</span>
                    <p>Confirmation</p>
                </div>
            </div>
            
            <form method="POST" class="payment-form">
                <h1>Select Payment Method</h1>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?= $error ?></div>
                <?php endif; ?>
                
                <div class="payment-methods">
                    <div class="payment-method">
                        <input type="radio" id="pay_now" name="payment_method" value="pay_now" checked>
                        <label for="pay_now">
                            <span class="method-name">Pay Now</span>
                            <span class="method-desc">Secure online payment via PayFast</span>
                            <div class="method-icons">
                                <img src="assets/images/visa.png" alt="Visa">
                                <img src="assets/images/mastercard.png" alt="Mastercard">
                                <img src="assets/images/payfast.png" alt="PayFast">
                            </div>
                        </label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="pay_later" name="payment_method" value="pay_later">
                        <label for="pay_later">
                            <span class="method-name">Pay Later</span>
                            <span class="method-desc">Pay when you pick up your order</span>
                            <div class="method-note">
                                <i class="fas fa-info-circle"></i> OTP verification required
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="branch-selection">
                    <h2>Select Pickup Branch</h2>
                    <div class="branch-options">
                        <?php foreach ($branches as $branch): ?>
                            <div class="branch-option">
                                <input type="radio" id="branch_<?= $branch['id'] ?>" name="branch_id" 
                                       value="<?= $branch['id'] ?>" required>
                                <label for="branch_<?= $branch['id'] ?>">
                                    <h3><?= htmlspecialchars($branch['name']) ?></h3>
                                    <p><?= htmlspecialchars($branch['address']) ?></p>
                                    <p><i class="fas fa-clock"></i> <?= htmlspecialchars($branch['hours']) ?></p>
                                    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($branch['phone']) ?></p>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-items">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="summary-item">
                                <span class="item-name"><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                                <span class="item-price">R <?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="summary-total">
                        <span>Total:</span>
                        <span>R <?= number_format(calculateCartTotal($_SESSION['cart']), 2) ?></span>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="checkout.php" class="btn-back">Back</a>
                    <button type="submit" class="btn-continue">Complete Order</button>
                </div>
            </form>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>