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
    <title>Set up Param account</title>
</head>
<body>
    <main>
        <h1>Set up your Param account</h1>

        <?php if ($passwordWasSaved): ?>
            <p>
                Your password has been saved.
                <a href="login.php">Continue to login</a>.
            </p>
        <?php else: ?>
            <?php if ($errorMessage): ?>
                <p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <?php if ($setupToken): ?>
                <form method="post">
                    <input
                        type="hidden"
                        name="token"
                        value="<?= htmlspecialchars($setupTokenValue, ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <label>
                        New password
                        <input
                            type="password"
                            name="password"
                            minlength="10"
                            required
                        >
                    </label>

                    <label>
                        Confirm password
                        <input
                            type="password"
                            name="password_confirmation"
                            minlength="10"
                            required
                        >
                    </label>

                    <button type="submit">Save password</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>
