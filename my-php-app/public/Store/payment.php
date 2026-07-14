<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Param. | Payment Success</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/payment.css">
</head>

<body>

    <main class="store-container">
        <?php 
        $path = ''; 
        include 'includes/header.php'; 
        ?>

        <section class="payment-section">
            <div class="success-card">
                <div class="success-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>

                <h1 class="success-title">Order Confirmed!</h1>
                <p class="success-message">Thank you for shopping with Param. Your order has been successfully placed
                    and is now being processed.</p>

                <div class="order-details-box">
                    <div class="detail-row">
                        <span class="detail-label">Order Number:</span>
                        <span class="detail-value">#PRM-88492</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">June 22, 2026</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Amount:</span>
                        <span class="detail-value"
                            style="color: var(--accent-gold); font-weight: bold;">₱4,130.00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value">Cash on Delivery</span>
                    </div>
                </div>

                <p class="email-notice">We have sent an order confirmation email to you with the tracking details.</p>

                <a href="shop.php" class="btn-continue">Continue Shopping</a>
            </div>
        </section>

    </main>

<?php 
$path = ''; 
include 'includes/footer.php'; 
?>