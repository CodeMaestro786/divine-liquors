<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Verify order exists and belongs to customer
$orderId = intval($_GET['order_id'] ?? 0);
$order = getOrderById($orderId);

if (!$order || empty($_SESSION['checkout_data']) || $order['payment_status'] !== 'pending') {
    header('Location: cart.php');
    exit;
}

// Prepare PayFast parameters
$customerData = $_SESSION['checkout_data'];
$cartItems = $_SESSION['cart'] ?? [];
$merchantId = PF_MERCHANT_ID;
$merchantKey = PF_MERCHANT_KEY;
$returnUrl = SITE_URL . '/confirmation.php?order_id=' . $orderId;
$cancelUrl = SITE_URL . '/cart.php';
$notifyUrl = SITE_URL . '/api/payfast_notify.php';

$nameFirst = explode(' ', $customerData['name'])[0];
$nameLast = explode(' ', $customerData['name'])[1] ?? '';

$pfData = [
    'merchant_id' => $merchantId,
    'merchant_key' => $merchantKey,
    'return_url' => $returnUrl,
    'cancel_url' => $cancelUrl,
    'notify_url' => $notifyUrl,
    'name_first' => $nameFirst,
    'name_last' => $nameLast,
    'email_address' => $customerData['email'],
    'cell_number' => $customerData['phone'],
    'm_payment_id' => $orderId,
    'amount' => number_format($order['total_amount'], 2),
    'item_name' => 'Divine Liquors Order #' . $orderId,
    'item_description' => 'Purchase of premium spirits and beverages',
    'custom_int1' => $orderId,
];

// Generate signature
$pfOutput = '';
foreach ($pfData as $key => $val) {
    if (!empty($val)) {
        $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
    }
}
$passphrase = PF_PASSPHRASE;
if (empty($passphrase)) {
    $pfOutput = substr($pfOutput, 0, -1);
} else {
    $pfOutput .= 'passphrase=' . urlencode($passphrase);
}

$pfData['signature'] = md5($pfOutput);
$pfHost = PF_TEST_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to PayFast...</title>
</head>
<body>
    <form action="https://<?= $pfHost ?>/eng/process" method="post" name="payfast" id="payfast">
        <?php foreach ($pfData as $name => $value): ?>
            <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
        <?php endforeach; ?>
    </form>
    
    <script>
        document.getElementById('payfast').submit();
    </script>
    
    <p>If you are not automatically redirected to PayFast, please <a href="#" onclick="document.forms.payfast.submit()">click here</a>.</p>
</body>
</html>