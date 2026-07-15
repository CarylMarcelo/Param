<?php
session_start();
require_once 'includes/db.php';
$pdo = getDbConnection();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
}

header('Location: favorites.php');
exit;
?>