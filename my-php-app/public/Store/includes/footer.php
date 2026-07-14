<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="../images/logo-footer.png" alt="PARAM">
                <p>Comfortable, versatile, and timeless clothing for every member of the family.</p>
            </div>

            <div class="footer-column">
                <h3>Explore</h3>
                <a href="Store/index.php">Featured</a>
                <a href="Store/shop.php">Shop</a>
                <a href="Store/AboutUs.php">About Us</a>
                <a href="Store/ContactUs.php">Contact Us</a>
            </div>

            <div class="footer-column">
                <h3>Account</h3>
                <a href="Store/login.php">Buyer Login</a>
                <a href="Store/signup.php">Create Account</a>
                <a href="login.php">Staff Login</a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy;
                <?php echo date('Y'); ?> PARAM. All rights reserved.
            </span>
            <span>For educational purposes only.</span>
        </div>
    </div>
</footer>

<div id="search-overlay" class="search-overlay">
    <div class="search-container">
        <button id="close-search" class="btn-close-search">&times;</button>

        <form action="shop.php" method="GET" class="search-form">
            <input type="text" name="query" class="search-input"
                placeholder="Search for products, categories, or keywords..." autofocus>
            <button type="submit" class="btn-search-submit">Search</button>
        </form>
    </div>
</div>

<script>
    const searchOverlay = document.getElementById('search-overlay');
    const openSearch = document.getElementById('open-search');
    const closeSearch = document.getElementById('close-search');

    openSearch.addEventListener('click', function (e) {
        e.preventDefault();
        searchOverlay.classList.add('active');
    });

    closeSearch.addEventListener('click', function () {
        searchOverlay.classList.remove('active');
    });
</script>

</body>

</html>