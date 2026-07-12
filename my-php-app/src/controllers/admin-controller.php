<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/role.php';
require_once __DIR__ . '/../services/email-service.php';

// Backs the "Admin Users" and "Audit Log" sections of AdminDashboard.
class AdminController
{
    public static function listUsers(): array
    {
        return User::all();
    }

    public static function listRoles(): array
    {
        return Role::all();
    }

    public static function createUser(array $input, int $actorId): array
    {
        self::validateUserInput($input);
        $role = Role::findByName($input['role'] ?? '');
        if (!$role) {
            http_response_code(422);
            return ['error' => 'Unknown role'];
        }

        [$first, $last] = self::splitName($input['name'] ?? '');

        $userId = User::create([
            'first_name' => $first,
            'last_name'  => $last,
            'email'      => $input['email'] ?? '',
            'password'   => bin2hex(random_bytes(8)), // temp password; user resets on first login
            'role_id'    => $role['role_id'],
            'status'     => strtolower($input['status'] ?? 'active'),
        ]);

        $plainToken = bin2hex(random_bytes(32));
        $tokenStatement = getDbConnection()->prepare(
            "INSERT INTO auth_tokens (
                user_id,
                token_type,
                token_hash,
                expires_at
             ) VALUES (
                :user_id,
                'account_setup',
                :token_hash,
                DATE_ADD(NOW(), INTERVAL 24 HOUR)
             )"
        );
        $tokenStatement->execute(['user_id' => $userId, 'token_hash' => hash('sha256', $plainToken)]);
        $setupUrl = self::accountSetupUrl($plainToken);
        $emailSent = false; $emailError = null;
        try {
            $emailSent = EmailService::sendAccountSetup((string) ($input['email'] ?? ''), trim((string) ($input['name'] ?? '')), $setupUrl);
            if (!$emailSent) $emailError = 'SMTP is not configured';
        } catch (Throwable $exception) {
            $emailError = $exception->getMessage();
        }

        self::logAudit(
            $actorId,
            'user.create',
            'users',
            $userId,
            sprintf('Created user: %s <%s> (%s)', trim(($input['name'] ?? '')), $input['email'] ?? '', $role['role_name'])
        );
        return ['user_id' => $userId, 'email_sent' => $emailSent, 'email_error' => $emailError, 'setup_url' => $emailSent ? null : $setupUrl];
    }

    public static function updateUser(int $userId, array $input, int $actorId): array
    {
        self::validateUserInput($input);
        $role = Role::findByName($input['role'] ?? '');
        if (!$role) {
            http_response_code(422);
            return ['error' => 'Unknown role'];
        }

        [$first, $last] = self::splitName($input['name'] ?? '');

        User::update($userId, [
            'first_name' => $first,
            'last_name'  => $last,
            'email'      => $input['email'] ?? '',
            'role_id'    => $role['role_id'],
            'status'     => strtolower($input['status'] ?? 'active'),
        ]);

        self::logAudit(
            $actorId,
            'user.update',
            'users',
            $userId,
            sprintf(
                'Updated user: %s <%s> (%s, %s)',
                trim(($input['name'] ?? '')),
                $input['email'] ?? '',
                $role['role_name'],
                strtolower($input['status'] ?? 'active')
            )
        );
        return ['success' => true];
    }

    public static function deleteUser(int $userId, int $actorId): array
    {
        if ($userId === $actorId) {
            http_response_code(422);
            return ['error' => 'You cannot delete your own account'];
        }
        $deletedUser = User::findById($userId);
        if (!$deletedUser) {
            http_response_code(404);
            return ['error' => 'User not found'];
        }
        User::delete($userId);
        self::logAudit(
            $actorId,
            'user.delete',
            'users',
            $userId,
            sprintf('Deleted user: %s %s <%s>', $deletedUser['first_name'], $deletedUser['last_name'], $deletedUser['email'])
        );
        return ['success' => true];
    }

    public static function listAudit(): array
    {
        $stmt = getDbConnection()->query(
            "SELECT al.audit_log_id, al.created_at, al.action_name, al.details,
                    COALESCE(CONCAT_WS(' ', u.first_name, u.last_name), 'System Admin') AS actor
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.user_id
             ORDER BY al.created_at DESC
             LIMIT 200"
        );
        return $stmt->fetchAll();
    }

    public static function clearAudit(): array
    {
        getDbConnection()->exec('DELETE FROM audit_logs');
        self::logAudit(null, 'audit_log.clear', 'audit_logs', null, 'Cleared via Admin Dashboard');
        return ['success' => true];
    }

    public static function logAudit(?int $userId, string $action, ?string $table, ?int $recordId, ?string $details): void
    {
        $stmt = getDbConnection()->prepare(
            'INSERT INTO audit_logs (user_id, action_name, table_name, record_id, details)
             VALUES (:user_id, :action_name, :table_name, :record_id, :details)'
        );
        $stmt->execute([
            'user_id'     => $userId,
            'action_name' => $action,
            'table_name'  => $table,
            'record_id'   => $recordId,
            'details'     => $details,
        ]);
    }

    private static function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);
        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    private static function validateUserInput(array $input): void
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $status = strtolower((string) ($input['status'] ?? 'active'));
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(422);
            throw new InvalidArgumentException('A name and valid email address are required');
        }
        if (!in_array($status, ['active', 'inactive'], true)) {
            http_response_code(422);
            throw new InvalidArgumentException('Invalid account status');
        }
    }

    private static function accountSetupUrl(string $token): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/public/api.php'));
        return sprintf('%s://%s%s/setup-account.php?token=%s', $scheme, $host, $directory, urlencode($token));
    }
}
