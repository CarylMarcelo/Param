<?php
require_once 'includes/db.php';

$query = "SELECT p.product_id, p.product_name, p.image_path, MIN(v.price) as display_price 
          FROM products p
          LEFT JOIN product_variants v ON p.product_id = v.product_id
          WHERE p.status = 'active'
          GROUP BY p.product_id";

$stmt = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Ultimate Fashion Destination</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/shop.css">
</head>

<body>

    <main class="store-container">
        <?php include 'includes/header.php'; ?>

        <section class="product-section">
            <h2 class="section-title">Our Products</h2>

            <div class="shop-controls">
                <div class="filter-options">
                    <span class="control-label">Filter:</span>
                    <button class="filter-pill active" type="button">All</button>
                    <button class="filter-pill" type="button">Women</button>
                    <button class="filter-pill" type="button">Men</button>
                    <button class="filter-pill" type="button">Kids</button>
                </div>

                <div class="sort-options">
                    <span class="control-label">Sort:</span>
                    <select class="sort-dropdown">
                        <option value="featured">Featured</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="product-grid">
                
                <?php foreach ($products as $item): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites" data-id="<?php echo $item['product_id']; ?>">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>

                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                        <p class="product-price">₱<?php echo number_format($item['display_price'], 2); ?></p>
                        <button class="btn-cart" data-id="<?php echo $item['product_id']; ?>">Add to Cart</button>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>
