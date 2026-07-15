<?php

require_once __DIR__ . '/../src/models/user.php';

$setupTokenValue = (string) ($_GET['token'] ?? $_POST['token'] ?? '');
$errorMessage = '';
$passwordWasSaved = false;
$setupToken = findValidSetupToken($setupTokenValue);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

    if (!$setupToken) {
        $errorMessage = 'This setup link is invalid or has expired.';
    } elseif (strlen($password) < 10) {
        $errorMessage = 'Password must contain at least 10 characters.';
    } elseif ($password !== $passwordConfirmation) {
        $errorMessage = 'Passwords do not match.';
    } else {
        saveNewPassword($setupToken, $password);
        $passwordWasSaved = true;
    }
} elseif (!$setupToken) {
    $errorMessage = 'This setup link is invalid or has expired.';
}

function findValidSetupToken(string $plainToken): array|false
{
    if ($plainToken === '') {
        return false;
    }

    $statement = getDbConnection()->prepare(
        "SELECT token_id, user_id
         FROM auth_tokens
         WHERE token_type = 'account_setup'
           AND token_hash = :token_hash
           AND used_at IS NULL
           AND expires_at > NOW()
         LIMIT 1"
    );
    $statement->execute([
        'token_hash' => hash('sha256', $plainToken),
    ]);

    return $statement->fetch();
}

function saveNewPassword(array $setupToken, string $password): void
{
    $database = getDbConnection();
    $database->beginTransaction();

    try {
        User::setPassword((int) $setupToken['user_id'], $password);

        $statement = $database->prepare(
            'UPDATE auth_tokens
             SET used_at = NOW()
             WHERE token_id = :token_id
               AND used_at IS NULL'
        );
        $statement->execute([
            'token_id' => $setupToken['token_id'],
        ]);

        $database->commit();
    } catch (Throwable $error) {
        $database->rollBack();
        throw $error;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PARAM | Set Up Account</title>
    <link rel="stylesheet" href="setup-account.css">
</head>
<body>
    <main class="setup-page">
        <a class="back-link" href="landing.php">&larr; Back to home</a>

        <section class="setup-card" aria-labelledby="setup-title">
            <div class="brand-panel">
                <img class="brand-logo" src="images/logo-header.png" alt="PARAM">
                <p class="brand-label">PARAM Account Access</p>
                <h1>Start securely</h1>
                <p class="brand-copy">
                    Create the password you will use to access your assigned
                    PARAM account and workspace.
                </p>

                <ul class="security-list">
                    <li>Private account setup</li>
                    <li>One-time secure link</li>
                    <li>Role-based access</li>
                </ul>
            </div>

            <div class="form-panel">
                <?php if ($passwordWasSaved): ?>
                    <div class="status-panel success-panel">
                        <p class="form-label-top">Account ready</p>
                        <h2 id="setup-title">Password saved</h2>
                        <p class="form-intro">
                            Your account setup is complete. You can now sign in
                            using your email address and new password.
                        </p>
                        <a class="primary-link" href="login.php">Continue to login</a>
                    </div>
                <?php else: ?>
                    <p class="form-label-top">Account security</p>
                    <h2 id="setup-title">Set your password</h2>
                    <p class="form-intro">
                        Choose a password with at least 10 characters, then
                        enter it again to confirm.
                    </p>

                    <?php if ($errorMessage): ?>
                        <p class="error-message" role="alert">
                            <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($setupToken): ?>
                        <form class="setup-form" method="post">
                            <input
                                type="hidden"
                                name="token"
                                value="<?= htmlspecialchars($setupTokenValue, ENT_QUOTES, 'UTF-8') ?>"
                            >

                            <div class="field-group">
                                <label for="password">New password</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    minlength="10"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>

                            <div class="field-group">
                                <label for="password-confirmation">Confirm password</label>
                                <input
                                    id="password-confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    minlength="10"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>

                            <button class="setup-button" type="submit">Save password</button>
                        </form>
                    <?php else: ?>
                        <a class="secondary-link" href="login.php">Return to login</a>
                    <?php endif; ?>

                    <p class="setup-note">
                        This setup link expires after 24 hours and can only be
                        used once.
                    </p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
