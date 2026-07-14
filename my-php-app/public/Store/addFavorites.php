<?php
require_once 'includes/db.php';
session_start();

if (isset($_POST['product_id']) && isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['product_id']]);
}
?>