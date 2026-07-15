<?php
session_start();
require_once 'includes/db.php';

// --- TEMPORARY BYPASS FOR TESTING ---
$_SESSION['user_id'] = 999;
$user_id = $_SESSION['user_id'];

// Check if a specific variant_id was passed (for "Buy Now")
$variant_id = isset($_GET['variant_id']) ? (int) $_GET['variant_id'] : null;

if ($variant_id) {
    // FETCH ONLY THE SPECIFIC ITEM
    $query = "SELECT 1 as quantity, v.size, v.color, v.price, p.product_name, p.image_path 
              FROM product_variants v
              JOIN products p ON v.product_id = p.product_id
              WHERE v.variant_id = :variant_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['variant_id' => $variant_id]);
} else {
    // FETCH FULL CART (DEFAULT)
    $query = "SELECT ci.quantity, v.size, v.color, v.price, p.product_name, p.image_path 
              FROM cart_items ci
              JOIN carts c ON ci.cart_id = c.cart_id
              JOIN product_variants v ON ci.variant_id = v.variant_id
              JOIN products p ON v.product_id = p.product_id
              WHERE c.user_id = :user_id AND c.status = 'active'";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
}
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = 150.00;
$total = $subtotal + $shipping;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Checkout</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>

    <main class="store-container">
        <?php
        $path = '';
        include 'includes/header.php';
        ?>

        <section class="checkout-section">
            <h2 class="section-title">Checkout</h2>

            <form action="payment.php" method="POST" id="checkout-form">
                <div class="checkout-layout">

                    <div class="checkout-form-area">
                        <div class="form-section">
                            <h3 class="form-title">Contact Information</h3>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input"
                                    placeholder="Enter your email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="09XX XXX XXXX"
                                    required>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="form-title">Shipping Address</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname">First Name</label>
                                    <input type="text" id="fname" name="fname" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <input type="text" id="lname" name="lname" class="form-input" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Street Address</label>
                                <input type="text" id="address" name="address" class="form-input"
                                    placeholder="House number and street name" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label for="zip">ZIP Code</label>
                                    <input type="text" id="zip" name="zip" class="form-input" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="form-title">Payment Method</h3>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="card" checked required>
                                <span class="payment-label">Credit / Debit Card</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="gcash" required>
                                <span class="payment-label">GCash</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cod" required>
                                <span class="payment-label">Cash on Delivery (COD)</span>
                            </label>
                        </div>
                    </div>

                    <div class="checkout-summary">
                        <h3 class="summary-title">Your Order</h3>

                        <div class="summary-items">
                            <?php if (empty($cart_items)): ?>
                                <p>Your cart is empty.</p>
                            <?php else: ?>
                                <?php foreach ($cart_items as $item): ?>
                                    <div class="summary-item">
                                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>"
                                            alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                            class="summary-item-img">
                                        <div class="summary-item-details">
                                            <p class="summary-item-name"><?php echo htmlspecialchars($item['product_name']); ?>
                                            </p>
                                            <p class="summary-item-meta">Color: <?php echo htmlspecialchars($item['color']); ?>
                                                | Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                            <p class="summary-item-meta">Qty: <?php echo $item['quantity']; ?></p>
                                        </div>
                                        <p class="summary-item-price">
                                            ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₱<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Standard Shipping</span>
                            <span>₱<?php echo number_format($shipping, 2); ?></span>
                        </div>

                        <div class="summary-total">
                            <span>Total</span>
                            <span>₱<?php echo number_format($total, 2); ?></span>
                        </div>

                        <button type="submit" class="btn-place-order">Place Order</button>
                    </div>

                </div>
            </form>
        </section>
    </main>

    <?php
    $path = '';
    include 'includes/footer.php';
    ?>