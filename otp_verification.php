<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Verify order exists
if (empty($_SESSION['otp_order_id'])) {
    header('Location: cart.php');
    exit;
}

$orderId = $_SESSION['otp_order_id'];
$order = getOrderById($orderId);

if (!$order || $order['payment_method'] !== 'pay_later') {
    header('Location: cart.php');
    exit;
}

// Process OTP verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');
    
    if (verifyOTP($orderId, $otp)) {
        // OTP verified, complete order
        updateOrderStatus($orderId, 'pending');
        updateOrderPaymentStatus($orderId, 'pending');
        
        // Send confirmation
        sendOrderConfirmationEmail($orderId);
        
        // Clear session
        unset($_SESSION['otp_order_id']);
        
        // Redirect to confirmation
        header('Location: confirmation.php?order_id=' . $orderId);
        exit;
    } else {
        $error = 'Invalid OTP code. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification | Divine Liquors</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/otp.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    
    <main class="otp-page">
        <div class="otp-container">
            <h1>OTP Verification</h1>
            <p>We've sent a 6-digit verification code to your phone number ending with 
               <?= substr($order['customer_phone'], -3) ?>.</p>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" class="otp-form">
                <div class="otp-inputs">
                    <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="otp[]" maxlength="1" pattern="[0-9]" required>
                </div>
                
                <button type="submit" class="btn-verify">Verify OTP</button>
            </form>
            
            <div class="otp-resend">
                <p>Didn't receive the code?</p>
                <a href="#" id="resend-otp">Resend OTP</a>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/otp.js"></script>
</body>
</html>