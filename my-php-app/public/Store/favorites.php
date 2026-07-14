<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Your Favorites</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/favorites.css">
</head>

<body>

    <main class="store-container">
        <?php include 'includes/header.php'; ?>


        <section class="product-section">
            <h2 class="section-title">Your Favorites</h2>

            <div class="product-grid">

                <div class="product-card">
                    <img src="images/prod10.avif" alt="Ribbed Henley Neck T-Shirt" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Ribbed Henley Neck T-Shirt | Long Sleeve</h3>
                        <p class="product-price">₱990.00</p>
                        <div class="favorite-actions">
                            <button class="btn-cart">Add to Cart</button>
                            <button class="btn-remove">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <img src="images/prod19.avif" alt="Cargo Shorts" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Cargo Shorts</h3>
                        <p class="product-price">₱1,290.00</p>
                        <div class="favorite-actions">
                            <button class="btn-cart">Add to Cart</button>
                            <button class="btn-remove">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <img src="images/prod14.avif" alt="Ultra Stretch Active Shorts" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Ultra Stretch Active Shorts</h3>
                        <p class="product-price">₱1,490.00</p>
                        <div class="favorite-actions">
                            <button class="btn-cart">Add to Cart</button>
                            <button class="btn-remove">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="product-card">
                    <img src="images/prod20.avif" alt="Tank Top" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Tank Top</h3>
                        <p class="product-price">₱790.00</p>
                        <div class="favorite-actions">
                            <button class="btn-cart">Add to Cart</button>
                            <button class="btn-remove">Remove</button>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>


    <?php include 'includes/footer.php'; ?>