<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Process checkout form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerData = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
    ];
    
    // Validate input
    $errors = [];
    if (empty($customerData['name'])) $errors[] = 'Name is required';
    if (empty($customerData['email']) || !filter_var($customerData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    if (empty($customerData['phone'])) $errors[] = 'Phone number is required';
    
    if (empty($errors)) {
        $_SESSION['checkout_data'] = $customerData;
        header('Location: payment.php');
        exit;
    }
}

$customerData = $_SESSION['checkout_data'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Divine Liquors</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    
    <main class="checkout-page">
        <div class="checkout-container">
            <div class="checkout-steps">
                <div class="step active">
                    <span>1</span>
                    <p>Customer Info</p>
                </div>
                <div class="step">
                    <span>2</span>
                    <p>Payment</p>
                </div>
                <div class="step">
                    <span>3</span>
                    <p>Confirmation</p>
                </div>
            </div>
            
            <form method="POST" class="checkout-form">
                <h1>Customer Information</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?= htmlspecialchars($customerData['name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($customerData['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required 
                           value="<?= htmlspecialchars($customerData['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Delivery Address (Optional)</label>
                    <textarea id="address" name="address"><?= htmlspecialchars($customerData['address'] ?? '') ?></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="cart.php" class="btn-back">Back to Cart</a>
                    <button type="submit" class="btn-continue">Continue to Payment</button>
                </div>
            </form>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>