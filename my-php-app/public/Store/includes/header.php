<?php
$path = isset($path) ? $path : '';
$current_page = basename($_SERVER['PHP_SELF']);
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
        <a href="<?php echo $path; ?>favorites.php" title="Favorites" <?php if ($current_page == 'favorites.php')
               echo 'class="active-icon"'; ?>>
            <img src="<?php echo $path; ?>images/heart.png" alt="Favorites" class="custom-icon">
        </a>

        <!-- Cart Icon -->
        <a href="<?php echo $path; ?>cart.php" title="Cart"
            class="cart-link <?php if ($current_page == 'cart.php')
                echo 'active-icon'; ?>">
            <img src="<?php echo $path; ?>images/shopping-cart.png" alt="Cart" class="custom-icon">
        </a>

        <!-- Profile Icon -->
        <a href="<?php echo $path; ?>Profile.php" title="Profile" <?php if ($current_page == 'Profile.php')
               echo 'class="active-icon"'; ?>>
            <img src="<?php echo $path; ?>images/user.png" alt="Profile" class="custom-icon">
        </a>
    </div>
</header>