<?php
require_once __DIR__ . '/../src/models/user.php';
$token = (string) ($_GET['token'] ?? $_POST['token'] ?? ''); $error = ''; $success = false;
$stmt = getDbConnection()->prepare("SELECT token_id, user_id FROM auth_tokens WHERE token_type = 'account_setup' AND token_hash = :hash AND used_at IS NULL AND expires_at > NOW() LIMIT 1");
$stmt->execute(['hash' => hash('sha256', $token)]); $setupToken = $token !== '' ? $stmt->fetch() : false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? ''); $confirmation = (string) ($_POST['password_confirmation'] ?? '');
    if (!$setupToken) $error = 'This setup link is invalid or has expired.';
    elseif (strlen($password) < 10) $error = 'Password must contain at least 10 characters.';
    elseif ($password !== $confirmation) $error = 'Passwords do not match.';
    else {
        $db = getDbConnection(); $db->beginTransaction(); User::setPassword((int) $setupToken['user_id'], $password);
        $used = $db->prepare('UPDATE auth_tokens SET used_at = NOW() WHERE token_id = :id AND used_at IS NULL');
        $used->execute(['id' => $setupToken['token_id']]); $db->commit(); $success = true;
    }
} elseif (!$setupToken) $error = 'This setup link is invalid or has expired.';
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Set up Param account</title></head><body><main>
<h1>Set up your Param account</h1>
<?php if ($success): ?><p>Your password has been saved. <a href="login.php">Continue to login</a>.</p>
<?php else: ?><?php if ($error): ?><p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<?php if ($setupToken): ?><form method="post"><input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
<label>New password <input type="password" name="password" minlength="10" required></label>
<label>Confirm password <input type="password" name="password_confirmation" minlength="10" required></label>
<button type="submit">Save password</button></form><?php endif; ?><?php endif; ?>
</main></body></html>
