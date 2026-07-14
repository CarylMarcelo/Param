<header class="navbar">
    <div class="logo">
        <img src="images/logo-header.png" alt="Param. Logo" class="img-logo">
    </div>
    <nav class="nav-links">
        <a href="../landing.php" title="Back to PARAM landing page">&larr; Main Site</a>
        <a href="../landing.php#featured">Featured</a>
        <a href="shop.php" <?php if(basename($_SERVER['PHP_SELF']) == 'shop.php') echo 'class="active-link"'; ?>>Shop</a>
        <a href="AboutUs.php" <?php if(basename($_SERVER['PHP_SELF']) == 'AboutUs.php') echo 'class="active-link"'; ?>>About Us</a>
        <a href="ContactUs.php" <?php if(basename($_SERVER['PHP_SELF']) == 'ContactUs.php') echo 'class="active-link"'; ?>>Contact Us</a>
    </nav>
    <div class="nav-icons">
        <!-- Search Icon -->
        <a href="#" title="Search" id="open-search">
            <img src="images/search.png" alt="Search" class="custom-icon">
        </a>
        
        <!-- Favorites Icon -->
        <a href="favorites.php" title="Favorites" <?php if(basename($_SERVER['PHP_SELF']) == 'favorites.php') echo 'class="active-icon"'; ?>>
            <img src="images/heart.png" alt="Favorites" class="custom-icon">
        </a>
        
        <!-- Cart Icon -->
        <a href="cart.php" title="Cart" class="cart-link <?php if(basename($_SERVER['PHP_SELF']) == 'cart.php') echo 'active-icon'; ?>">
            <img src="images/shopping-cart.png" alt="Cart" class="custom-icon">
        </a>
        
        <!-- Profile Icon -->
        <a href="Profile.php" title="Profile" <?php if(basename($_SERVER['PHP_SELF']) == 'Profile.php') echo 'class="active-icon"'; ?>>
            <img src="images/user.png" alt="Profile" class="custom-icon">
        </a>
    </div>
</header>
