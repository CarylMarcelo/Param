<?php
require_once 'includes/db.php';

$query = "SELECT p.product_id, p.product_name, p.image_path, MIN(v.price) as display_price 
          FROM products p
          LEFT JOIN product_variants v ON p.product_id = v.product_id
          WHERE p.product_id IN (1, 2, 3, 4)
          GROUP BY p.product_id
          ORDER BY FIELD(p.product_id, 1, 2, 3, 4)";

$stmt = $pdo->prepare($query);
$stmt->execute();
$top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Ultimate Fashion Destination</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>

    <main class="store-container">
        <?php include 'includes/header.php'; ?>

        <section class="hero-full">
            <img src="images/hero.jpg" alt="Celebrate in Style 2025/2026 Collection" class="hero-full-img">

            <a href="shop.php" class="btn-shop-overlay">Shop Now</a>
        </section>

        <section class="product-section">
            <h2 class="section-title">Our Top Seller Products</h2>

            <div class="product-grid">

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod1.avif" alt="Kids Pocketable UV Protection Parka" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>

                    <div class="product-info">
                        <h3 class="product-title">Kids Pocketable UV Protection Parka</h3>
                        <p class="product-price">₱1,490.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod2.avif" alt="Nylon Culotte" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>

                    <div class="product-info">
                        <h3 class="product-title">Nylon Culotte</h3>
                        <p class="product-price">₱1,990.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod3.avif" alt="Washed Cotton Boxy T-Shirt" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>

                    <div class="product-info">
                        <h3 class="product-title">Washed Cotton Boxy T-Shirt</h3>
                        <p class="product-price">₱590.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod4.avif" alt="Washable 3D Knit Polo" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>

                    <div class="product-info">
                        <h3 class="product-title">Washable 3D Knit Polo</h3>
                        <p class="product-price">₱2,490.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>
