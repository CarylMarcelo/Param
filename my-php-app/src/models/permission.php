<?php
require_once __DIR__ . '/../config/database.php';

class Permission
{
    // All permission_keys granted to a role (used by rbacmiddleware).
    public static function forRole(int $roleId): array
    {
        $stmt = getDbConnection()->prepare(
            'SELECT p.permission_key
             FROM role_permissions rp
             JOIN permissions p ON p.permission_id = rp.permission_id
             WHERE rp.role_id = :role_id'
        );
        $stmt->execute(['role_id' => $roleId]);
        return array_column($stmt->fetchAll(), 'permission_key');
    }

    public static function roleHasPermission(int $roleId, string $permissionKey): bool
    {
        $stmt = getDbConnection()->prepare(
            'SELECT 1
             FROM role_permissions rp
             JOIN permissions p ON p.permission_id = rp.permission_id
             WHERE rp.role_id = :role_id AND p.permission_key = :key
             LIMIT 1'
        );
        $stmt->execute(['role_id' => $roleId, 'key' => $permissionKey]);
        return (bool) $stmt->fetchColumn();
    }
}
