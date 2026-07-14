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

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod5.avif" alt="KIDS AIRism Cotton Graphic Crew Neck T-Shirt"
                            class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">KIDS AIRism Cotton Graphic Crew Neck T-Shirt</h3>
                        <p class="product-price">₱490.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod6.avif" alt="KIDS AIRism Cotton Crew Neck T-shirt | Long Sleeve"
                            class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">KIDS AIRism Cotton Crew Neck T-shirt | Long Sleeve</h3>
                        <p class="product-price">₱590.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod7.avif" alt="KIDS Baggy Cargo Half Pants" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">KIDS Baggy Cargo Half Pants</h3>
                        <p class="product-price">₱790.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod8.avif" alt="KIDS Wide Fit Straight Jeans" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">KIDS Wide Fit Straight Jeans</h3>
                        <p class="product-price">₱1,290.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod9.avif" alt="Graphic T-Shirt" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Graphic T-Shirt</h3>
                        <p class="product-price">₱990.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod10.avif" alt="Ribbed Henley Neck T-Shirt | Long Sleeve"
                            class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Ribbed Henley Neck T-Shirt | Long Sleeve</h3>
                        <p class="product-price">₱990.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod11.avif" alt="Pleated Skort" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Pleated Skort</h3>
                        <p class="product-price">₱1,290.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod12.avif" alt="AIRism Cotton Short Sleeve T Dress" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">AIRism Cotton Short Sleeve T Dress</h3>
                        <p class="product-price">₱1,290.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod13.avif" alt="Milano Ribbed Shirt Collar Cardigan" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Milano Ribbed Shirt Collar Cardigan</h3>
                        <p class="product-price">₱2,490.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod14.avif" alt="Ultra Stretch Active Shorts" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Ultra Stretch Active Shorts</h3>
                        <p class="product-price">₱1,490.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod15.avif" alt="AIRism Cotton Oversized Striped T-Shirt"
                            class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">AIRism Cotton Oversized Striped T-Shirt</h3>
                        <p class="product-price">₱790.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod16.avif" alt="Knitted V Neck Cardigan" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Knitted V Neck Cardigan</h3>
                        <p class="product-price">₱1,990.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod17.avif" alt="Ultra Stretch Sweat Shorts" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Ultra Stretch Sweat Shorts</h3>
                        <p class="product-price">₱790.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod18.avif" alt="Straight Jeans Selvedge" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Straight Jeans Selvedge</h3>
                        <p class="product-price">₱1,990.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod19.avif" alt="Cargo Shorts" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Cargo Shorts</h3>
                        <p class="product-price">₱1,290.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image-container">
                        <img src="images/prod20.avif" alt="Tank Top" class="product-image">
                        <button class="btn-favorite-card" title="Add to Favorites">
                            <img src="images/heart.png" alt="Favorite" class="heart-icon">
                        </button>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Tank Top</h3>
                        <p class="product-price">₱790.00</p>
                        <button class="btn-cart">Add to Cart</button>
                    </div>
                </div>

            </div>
        </section>

    </main>


    <?php include 'includes/footer.php'; ?>