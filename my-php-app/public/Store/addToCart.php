<?php
session_start();
require_once 'includes/db.php';
$pdo = getDbConnection();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to add items to your cart.'); window.location.href='login.php';</script>";
    exit;
}

if (isset($_POST['product_id'], $_POST['size'], $_POST['color'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = (int) $_POST['product_id'];
    $size = $_POST['size'];
    $color = $_POST['color'];

    $variant_stmt = $pdo->prepare("SELECT variant_id FROM product_variants WHERE product_id = ? AND size = ? AND color = ?");
    $variant_stmt->execute([$product_id, $size, $color]);
    $variant = $variant_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$variant) {
        echo "<script>alert('Selected variant is unavailable.'); window.location.href='shop.php';</script>";
        exit;
    }
    $variant_id = $variant['variant_id'];

    $cart_stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ? AND status = 'active'");
    $cart_stmt->execute([$user_id]);
    $cart = $cart_stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart) {
        $cart_id = $cart['cart_id'];
    } else {
        $insert_cart = $pdo->prepare("INSERT INTO carts (user_id, status) VALUES (?, 'active')");
        $insert_cart->execute([$user_id]);
        $cart_id = $pdo->lastInsertId();
    }

    $item_stmt = $pdo->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = ? AND variant_id = ?");
    $item_stmt->execute([$cart_id, $variant_id]);
    $existing_item = $item_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        $update_stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?");
        $update_stmt->execute([$existing_item['cart_item_id']]);
    } else {
        $insert_item = $pdo->prepare("INSERT INTO cart_items (cart_id, variant_id, quantity) VALUES (?, ?, 1)");
        $insert_item->execute([$cart_id, $variant_id]);
    }

    // --- CHECK WHICH BUTTON WAS CLICKED ---
    if (isset($_POST['action']) && $_POST['action'] === 'checkout') {
        // Redirect to checkout if "Buy Now" was clicked
        header("Location: checkout.php?variant_id=" . $variant_id);
        exit;
    } else {
        // Go to cart if "Add to Cart" was clicked
        header("Location: cart.php");
        exit;
    }

} else {
    header("Location: shop.php");
    exit;
}
?>