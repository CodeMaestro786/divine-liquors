<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = $_POST['product_id'] ?? 0;
    
    switch ($action) {
        case 'update':
            $quantity = $_POST['quantity'] ?? 1;
            updateCartItem($productId, $quantity);
            break;
        case 'remove':
            removeFromCart($productId);
            break;
    }
    
    header('Location: cart.php');
    exit;
}

$cartItems = $_SESSION['cart'] ?? [];
$subtotal = calculateCartSubtotal($cartItems);
$tax = $subtotal * 0.15; // 15% tax
$total = $subtotal + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | Divine Liquors</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>
    
    <main class="cart-page">
        <h1>Your Shopping Cart</h1>
        
        <div class="cart-container">
            <?php if (count($cartItems) > 0): ?>
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="item-details">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="price">R <?= number_format($item['price'], 2) ?></p>
                                
                                <form method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <div class="quantity-controls">
                                        <button type="button" class="qty-btn minus">-</button>
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" 
                                               max="<?= getProductStock($item['id']) ?>">
                                        <button type="button" class="qty-btn plus">+</button>
                                    </div>
                                    <button type="submit" class="btn-update">Update</button>
                                </form>
                                
                                <form method="POST" class="remove-form">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-remove">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>R <?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (15%):</span>
                            <span>R <?= number_format($tax, 2) ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>R <?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                    <a href="products.php" class="btn-continue">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <p>Your cart is empty</p>
                    <a href="products.php" class="btn btn-primary">Browse Products</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/cart.js"></script>
</body>
</html>