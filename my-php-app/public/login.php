<?php
require_once __DIR__ . '/../src/middleware/authentication.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user     = User::findByEmail($email);

    if ($user && $user['status'] === 'active' && User::verifyPassword($user, $password)) {
        loginUser($user);
        $destinations = [
            'Administrator' => 'AdminDashboard/admin.php',
            'Delivery' => 'DeliveryDashboard/delivery.php',
            'Customer Service' => 'CustomerServiceDashboard/support.php',
        ];
        header('Location: ' . ($destinations[$user['role_name']] ?? 'index.php'));
        exit;
    }
    $error = 'Invalid email or password.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Param - Login</title>
    <style>
        body { font-family: system-ui, sans-serif; display: flex; justify-content: center; margin-top: 10vh; }
        form { display: flex; flex-direction: column; gap: 12px; width: 280px; }
        input { padding: 8px; font-size: 1rem; }
        button { padding: 8px; font-size: 1rem; cursor: pointer; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <form method="post">
        <h2>Param Login</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log in</button>
    </form>
</body>
</html>
