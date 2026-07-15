<?php
session_start();
require_once 'includes/db.php';

// 1. THIS IS THE FIX: Get the database connection using the new function
$pdo = getDbConnection();

// --- TEMPORARY BYPASS FOR TESTING ---
// Act as the dummy Customer (ID: 999)
$_SESSION['user_id'] = 999;

/* Commented out until the login system is ready
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
*/

// --- GET USER'S FAVORITED PRODUCTS ---
$user_favorites = [];
if (isset($_SESSION['user_id'])) {
    $fav_stmt = $pdo->prepare("SELECT product_id FROM favorites WHERE user_id = ?");
    $fav_stmt->execute([$_SESSION['user_id']]);
    $user_favorites = $fav_stmt->fetchAll(PDO::FETCH_COLUMN);
}

$cat = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
$sort = $_GET['sort'] ?? 'featured';

$query = "SELECT p.product_id, p.product_name, p.image_path, MIN(v.price) as display_price 
          FROM products p
          LEFT JOIN product_variants v ON p.product_id = v.product_id
          WHERE p.status = 'active'";

if ($cat > 0) {
    $query .= " AND p.category_id = :cat";
}

$query .= " GROUP BY p.product_id";

if ($sort == 'price_asc')
    $query .= " ORDER BY display_price ASC";
if ($sort == 'price_desc')
    $query .= " ORDER BY display_price DESC";

$stmt = $pdo->prepare($query);

if ($cat > 0) {
    $stmt->bindParam(':cat', $cat, PDO::PARAM_INT);
}

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
        <?php
        $path = '';
        include 'includes/header.php';
        ?>

        <section class="product-section">
            <h2 class="section-title">Our Products</h2>

            <div class="shop-controls">
                <div class="filter-options">
                    <span class="control-label">Filter:</span>
                    <?php
                    $cats = ['All' => 0, 'Kids' => 1, 'Women' => 2, 'Men' => 3, 'Unisex' => 4];

                    $active_cat = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
                    ?>

                    <?php foreach ($cats as $name => $id): ?>
                        <a href="?cat=<?php echo $id; ?>"
                            class="filter-pill <?php echo ($active_cat == $id) ? 'active' : ''; ?>">
                            <?php echo $name; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="sort-options">
                    <span class="control-label">Sort:</span>
                    <select class="sort-dropdown"
                        onchange="window.location.href='?cat=<?php echo $_GET['cat'] ?? 0; ?>&sort='+this.value">
                        <option value="featured">Featured</option>
                        <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="product-grid">

                <?php foreach ($products as $item): ?>
                    <?php
                    $is_faved = in_array($item['product_id'], $user_favorites);
                    ?>

                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-image">

                            <button class="btn-favorite-card <?php echo $is_faved ? 'is-favorited' : ''; ?>"
                                title="Add to Favorites" data-id="<?php echo $item['product_id']; ?>">
                                <img src="images/<?php echo $is_faved ? 'love.png' : 'heart.png'; ?>" alt="Favorite"
                                    class="heart-icon">
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

    <?php
    $path = '';
    include 'includes/footer.php';
    ?>

    <div id="cartModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div id="modalBody">
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-favorite-card').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                const btn = this;

                fetch('addFavorites.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'product_id=' + productId
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {

                            const heartIcon = btn.querySelector('.heart-icon');

                            if (data.is_favorited) {
                                btn.classList.add('is-favorited');
                                if (heartIcon) heartIcon.src = 'images/love.png';
                            } else {
                                btn.classList.remove('is-favorited');
                                if (heartIcon) heartIcon.src = 'images/heart.png';
                            }

                            const favLink = document.querySelector('a[title="Favorites"]');
                            if (favLink) {
                                let badge = favLink.querySelector('.badge');

                                if (data.new_count > 0) {
                                    if (!badge) {
                                        badge = document.createElement('span');
                                        badge.className = 'badge';
                                        favLink.appendChild(badge);
                                    }
                                    badge.innerText = data.new_count;
                                } else {
                                    if (badge) {
                                        badge.remove();
                                    }
                                }
                            }

                            if (heartIcon) {
                                heartIcon.style.transform = 'scale(1.3)';
                                setTimeout(() => { heartIcon.style.transform = 'scale(1)'; }, 200);
                            }

                        } else if (data.status === 'error' && data.message === 'not_logged_in') {
                            window.location.href = 'login.php';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('cartModal');
            const closeBtn = document.querySelector('.close-btn');

            document.querySelectorAll('.btn-cart').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-id');

                    fetch('getProductDetails.php?id=' + productId)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('modalBody').innerHTML = html;
                            modal.style.display = 'block';
                        });
                });
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    modal.style.display = 'none';
                });
            }

            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>