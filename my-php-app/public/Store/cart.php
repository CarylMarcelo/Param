<?php
session_start();
require_once 'includes/db.php';

// --- TEMPORARY BYPASS FOR TESTING ---
// Force the session to always act like User #1 is logged in
$_SESSION['user_id'] = 999;

/* Commented out until the login system is ready
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
*/

$user_id = $_SESSION['user_id'];

$query = "SELECT ci.cart_item_id, ci.quantity, v.size, v.color, v.price, p.product_name, p.image_path 
          FROM cart_items ci
          JOIN carts c ON ci.cart_id = c.cart_id
          JOIN product_variants v ON ci.variant_id = v.variant_id
          JOIN products p ON v.product_id = p.product_id
          WHERE c.user_id = :user_id AND c.status = 'active'";

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subtotal = 0;
$total_items = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $total_items += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Your Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>

    <main class="store-container">
        <?php 
        $path = ''; 
        include 'includes/header.php'; 
        ?>

        <section class="cart-section">
            <h2 class="section-title">Shopping Cart</h2>

            <div class="cart-layout">
                <div class="cart-items">
                    
                    <?php if (empty($cart_items)): ?>
                        <p>Your cart is currently empty.</p>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item">
                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="cart-item-img">
                                <div class="cart-item-details">
                                    <div class="cart-item-header">
                                        <div>
                                            <h3 class="cart-item-title"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                                            <p class="cart-item-meta">Color: <?php echo htmlspecialchars($item['color']); ?> | Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                        </div>
                                        <p class="cart-item-price">₱<?php echo number_format($item['price'], 2); ?></p>
                                    </div>
                                    <div class="cart-item-actions">
                                        <label for="qty_<?php echo $item['cart_item_id']; ?>">Qty:</label>
                                        <input type="number" id="qty_<?php echo $item['cart_item_id']; ?>" class="quantity-input" value="<?php echo $item['quantity']; ?>" min="1" readonly>
                                        
                                        <!-- Optional: Link to a remove_from_cart.php script -->
                                        <a href="RemoveFromCart.php?id=<?php echo $item['cart_item_id']; ?>" class="btn-remove" style="text-decoration: none;">Remove</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <div class="summary-row">
                        <span>Subtotal (<?php echo $total_items; ?> Items)</span>
                        <span>₱<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Calculated at checkout</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span>₱<?php echo number_format($subtotal, 2); ?></span>
                    </div>

                    <?php if (!empty($cart_items)): ?>
                        <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    </main>

<?php 
$path = ''; 
include 'includes/footer.php'; 
?>