<?php
require_once __DIR__ . '/../config/database.php';

// Data access for the users table (joined with roles for display/API use).
class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = getDbConnection()->prepare(
            'SELECT u.*, r.role_name FROM users u
             JOIN roles r ON u.role_id = r.role_id
             WHERE u.email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $userId): ?array
    {
        $stmt = getDbConnection()->prepare(
            'SELECT u.*, r.role_name FROM users u
             JOIN roles r ON u.role_id = r.role_id
             WHERE u.user_id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function all(): array
    {
        $stmt = getDbConnection()->query(
            'SELECT u.user_id, u.first_name, u.last_name, u.email, u.status, r.role_name
             FROM users u
             JOIN roles r ON u.role_id = r.role_id
             ORDER BY u.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $stmt = getDbConnection()->prepare(
            'INSERT INTO users (first_name, last_name, email, password_hash, role_id, status)
             VALUES (:first_name, :last_name, :email, :password_hash, :role_id, :status)'
        );
        $stmt->execute([
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'email'          => $data['email'],
            'password_hash'  => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id'        => $data['role_id'],
            'status'         => $data['status'] ?? 'active',
        ]);
        return (int) getDbConnection()->lastInsertId();
    }

    public static function update(int $userId, array $data): bool
    {
        $stmt = getDbConnection()->prepare(
            'UPDATE users SET first_name = :first_name, last_name = :last_name,
             email = :email, role_id = :role_id, status = :status
             WHERE user_id = :id'
        );
        return $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'role_id'    => $data['role_id'],
            'status'     => $data['status'],
            'id'         => $userId,
        ]);
    }

    public static function delete(int $userId): bool
    {
        $stmt = getDbConnection()->prepare('DELETE FROM users WHERE user_id = :id');
        return $stmt->execute(['id' => $userId]);
    }

    public static function verifyPassword(array $user, string $password): bool
    {
        return password_verify($password, $user['password_hash']);
    }
}
