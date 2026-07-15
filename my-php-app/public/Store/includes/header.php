<?php
$path = isset($path) ? $path : '';
$current_page = basename($_SERVER['PHP_SELF']);

// --- FETCH CART & FAVORITES COUNT ---
$cart_count = 0;
$fav_count = 0;

if (isset($_SESSION['user_id']) && isset($pdo)) {
    $user_id = $_SESSION['user_id'];

    // 1. Get total quantity of items in the active cart
    try {
        $cart_stmt = $pdo->prepare("
            SELECT SUM(quantity) 
            FROM cart_items ci 
            JOIN carts c ON ci.cart_id = c.cart_id 
            WHERE c.user_id = ? AND c.status = 'active'
        ");
        $cart_stmt->execute([$user_id]);
        $cart_count = $cart_stmt->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        $cart_count = 0;
    }

    // 2. Get total number of favorites
    try {
        $fav_stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ?");
        $fav_stmt->execute([$user_id]);
        $fav_count = $fav_stmt->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        $fav_count = 0;
    }
}
?>

<header class="navbar">
    <div class="logo">
        <img src="<?php echo $path; ?>images/logo-header.png" alt="Param. Logo" class="img-logo">
    </div>
    <nav class="nav-links">
        <a href="<?php echo $path; ?>../landing.php" <?php if ($current_page == 'landing.php')
               echo 'class="active-link"'; ?>>Home</a>
        <a href="<?php echo $path; ?>shop.php" <?php if ($current_page == 'shop.php')
               echo 'class="active-link"'; ?>>Shop</a>
        <a href="<?php echo $path; ?>AboutUs.php" <?php if ($current_page == 'AboutUs.php')
               echo 'class="active-link"'; ?>>About Us</a>
        <a href="<?php echo $path; ?>ContactUs.php" <?php if ($current_page == 'ContactUs.php')
               echo 'class="active-link"'; ?>>Contact Us</a>
    </nav>
    <div class="nav-icons">
        <!-- Search Icon -->
        <a href="#" title="Search" id="open-search">
            <img src="<?php echo $path; ?>images/search.png" alt="Search" class="custom-icon">
        </a>

        <!-- Favorites Icon -->
        <a href="<?php echo $path; ?>favorites.php" title="Favorites"
            class="icon-wrapper <?php if ($current_page == 'favorites.php')
                echo 'active-icon'; ?>">
            <img src="<?php echo $path; ?>images/heart.png" alt="Favorites" class="custom-icon">
            <?php if ($fav_count > 0): ?>
                <span class="nav-badge"><?php echo $fav_count; ?></span>
            <?php endif; ?>
        </a>

        <!-- Cart Icon -->
        <a href="<?php echo $path; ?>cart.php" title="Cart"
            class="icon-wrapper cart-link <?php if ($current_page == 'cart.php')
                echo 'active-icon'; ?>">
            <img src="<?php echo $path; ?>images/shopping-cart.png" alt="Cart" class="custom-icon">
            <?php if ($cart_count > 0): ?>
                <span class="nav-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>

        <!-- Profile Icon -->
        <a href="<?php echo $path; ?>Profile.php" title="Profile" <?php if ($current_page == 'Profile.php')
               echo 'class="active-icon"'; ?>>
            <img src="<?php echo $path; ?>images/user.png" alt="Profile" class="custom-icon">
        </a>
    </div>
</header>