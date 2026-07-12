<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/role.php';

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

        self::logAudit($actorId, 'user.create', 'users', $userId, 'Created via Admin Dashboard');
        return ['user_id' => $userId];
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

        self::logAudit($actorId, 'user.update', 'users', $userId, 'Updated via Admin Dashboard');
        return ['success' => true];
    }

    public static function deleteUser(int $userId, int $actorId): array
    {
        if ($userId === $actorId) {
            http_response_code(422);
            return ['error' => 'You cannot delete your own account'];
        }
        User::delete($userId);
        self::logAudit($actorId, 'user.delete', 'users', $userId, 'Deleted via Admin Dashboard');
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
}
