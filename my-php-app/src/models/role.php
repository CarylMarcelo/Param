<?php
require_once __DIR__ . '/../config/database.php';

class Role
{
    public static function all(): array
    {
        $stmt = getDbConnection()->query('SELECT role_id, role_name, description FROM roles ORDER BY role_id');
        return $stmt->fetchAll();
    }

    public static function findByName(string $roleName): ?array
    {
        $stmt = getDbConnection()->prepare('SELECT * FROM roles WHERE role_name = :name LIMIT 1');
        $stmt->execute(['name' => $roleName]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $roleId): ?array
    {
        $stmt = getDbConnection()->prepare('SELECT * FROM roles WHERE role_id = :id LIMIT 1');
        $stmt->execute(['id' => $roleId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
