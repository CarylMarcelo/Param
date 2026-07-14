<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Your Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>

    <main class="store-container">
        <?php include 'includes/header.php'; ?>


        <section class="cart-section">
            <h2 class="section-title">Shopping Cart</h2>

            <div class="cart-layout">

                <div class="cart-items">

                    <div class="cart-item">
                        <img src="images/prod1.avif" alt="Kids Parka" class="cart-item-img">
                        <div class="cart-item-details">
                            <div class="cart-item-header">
                                <div>
                                    <h3 class="cart-item-title">Kids Pocketable UV Protection Parka</h3>
                                    <p class="cart-item-meta">Color: Light Blue | Size: 130</p>
                                </div>
                                <p class="cart-item-price">₱1,490.00</p>
                            </div>
                            <div class="cart-item-actions">
                                <label for="qty1">Qty:</label>
                                <input type="number" id="qty1" class="quantity-input" value="1" min="1">
                                <button class="btn-remove">Remove</button>
                            </div>
                        </div>
                    </div>

                    <div class="cart-item">
                        <img src="images/prod4.avif" alt="Washable Polo" class="cart-item-img">
                        <div class="cart-item-details">
                            <div class="cart-item-header">
                                <div>
                                    <h3 class="cart-item-title">Washable 3D Knit Polo</h3>
                                    <p class="cart-item-meta">Color: Blue | Size: M</p>
                                </div>
                                <p class="cart-item-price">₱2,490.00</p>
                            </div>
                            <div class="cart-item-actions">
                                <label for="qty2">Qty:</label>
                                <input type="number" id="qty2" class="quantity-input" value="1" min="1">
                                <button class="btn-remove">Remove</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <div class="summary-row">
                        <span>Subtotal (2 Items)</span>
                        <span>₱3,980.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Calculated at checkout</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span>₱3,980.00</span>
                    </div>

                    <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                </div>

            </div>
        </section>

    </main>


    <?php include 'includes/footer.php'; ?>