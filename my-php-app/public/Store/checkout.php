<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Checkout</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>

    <main class="store-container">
        <?php 
        $path = ''; 
        include 'includes/header.php'; 
        ?>

        <section class="checkout-section">
            <h2 class="section-title">Checkout</h2>

            <div class="checkout-layout">

                <div class="checkout-form-area">
                    <form action="payment.php" method="POST">

                        <div class="form-section">
                            <h3 class="form-title">Contact Information</h3>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input"
                                    placeholder="Enter your email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="09XX XXX XXXX"
                                    required>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="form-title">Shipping Address</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname">First Name</label>
                                    <input type="text" id="fname" name="fname" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <input type="text" id="lname" name="lname" class="form-input" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Street Address</label>
                                <input type="text" id="address" name="address" class="form-input"
                                    placeholder="House number and street name" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label for="zip">ZIP Code</label>
                                    <input type="text" id="zip" name="zip" class="form-input" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="form-title">Payment Method</h3>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="card" checked>
                                <span class="payment-label">Credit / Debit Card</span>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="gcash">
                                <span class="payment-label">GCash</span>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cod">
                                <span class="payment-label">Cash on Delivery (COD)</span>
                            </label>
                        </div>

                    </form>
                </div>

                <div class="checkout-summary">
                    <h3 class="summary-title">Your Order</h3>

                    <div class="summary-items">
                        <div class="summary-item">
                            <img src="images/prod1.avif" alt="Kids Parka" class="summary-item-img">
                            <div class="summary-item-details">
                                <p class="summary-item-name">Kids Pocketable UV Protection Parka</p>
                                <p class="summary-item-meta">Qty: 1</p>
                            </div>
                            <p class="summary-item-price">₱1,490.00</p>
                        </div>
                        <div class="summary-item">
                            <img src="images/prod4.avif" alt="Washable Polo" class="summary-item-img">
                            <div class="summary-item-details">
                                <p class="summary-item-name">Washable 3D Knit Polo</p>
                                <p class="summary-item-meta">Qty: 1</p>
                            </div>
                            <p class="summary-item-price">₱2,490.00</p>
                        </div>
                    </div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₱3,980.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Standard Shipping</span>
                        <span>₱150.00</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span>₱4,130.00</span>
                    </div>

                    <a href="payment.php" class="btn-place-order">Place Order</a>
                </div>

            </div>
        </section>

    </main>

<?php 
$path = ''; 
include 'includes/footer.php'; 
?>