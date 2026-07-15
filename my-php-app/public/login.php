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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARAM | Staff Login</title>
    <link rel="stylesheet" href="staff-login.css">
</head>
<body>
    <main class="login-page">
        <a class="back-link" href="landing.php">&larr; Back to home</a>

        <section class="login-card" aria-labelledby="login-title">
            <div class="brand-panel">
                <img class="brand-logo" src="images/logo-header.png" alt="PARAM">
                <p class="brand-label">PARAM Staff Portal</p>
                <h1>Welcome back!</h1>
                <p class="brand-copy">Access the tools you need to manage products, customer concerns, deliveries, and daily operations.</p>
                <div class="role-list" aria-label="Authorized staff roles">
                    <span>Administrator</span>
                    <span>Customer Service</span>
                    <span>Delivery</span>
                </div>
            </div>

            <div class="form-panel">
                <p class="form-label-top">Secure access</p>
                <h2 id="login-title">Staff Login</h2>
                <p class="form-intro">Enter the account details assigned to you.</p>

                <form class="login-form" method="post">
                    <?php if ($error): ?>
                        <p class="error-message" role="alert"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <div class="field-group">
                        <label for="email">Email address</label>
                        <input id="email" type="email" name="email" placeholder="name@param.test" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" required>
                    </div>

                    <div class="field-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                    </div>

                    <button class="login-button" type="submit">Log in</button>
                </form>

                <p class="staff-note">For authorized PARAM staff members only.</p>
            </div>
        </section>
    </main>
</body>
</html>
