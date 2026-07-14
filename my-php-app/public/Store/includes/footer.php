<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="images/logo-footer.png" alt="Param. Logo" class="img-logo-footer">
        </div>
        <div class="footer-info">
            <p><strong>Disclaimer:</strong> This website is for educational purposes only and is a requirement for
                our final project.</p>
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