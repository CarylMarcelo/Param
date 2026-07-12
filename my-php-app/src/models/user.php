<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    public static function findByEmail(string $email): ?array
    {
        $statement = getDbConnection()->prepare(
            'SELECT users.*, roles.role_name
             FROM users
             JOIN roles ON roles.role_id = users.role_id
             WHERE users.email = :email
             LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public static function findById(int $userId): ?array
    {
        $statement = getDbConnection()->prepare(
            'SELECT users.*, roles.role_name
             FROM users
             JOIN roles ON roles.role_id = users.role_id
             WHERE users.user_id = :user_id
             LIMIT 1'
        );
        $statement->execute(['user_id' => $userId]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public static function all(): array
    {
        $query = 'SELECT
                    users.user_id,
                    users.first_name,
                    users.last_name,
                    users.email,
                    users.status,
                    roles.role_name
                  FROM users
                  JOIN roles ON roles.role_id = users.role_id
                  ORDER BY users.created_at DESC';

        return getDbConnection()->query($query)->fetchAll();
    }

    public static function create(array $userData): int
    {
        $database = getDbConnection();
        $statement = $database->prepare(
            'INSERT INTO users (
                first_name,
                last_name,
                email,
                password_hash,
                role_id,
                status
             ) VALUES (
                :first_name,
                :last_name,
                :email,
                :password_hash,
                :role_id,
                :status
             )'
        );
        $statement->execute([
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'password_hash' => password_hash(
                $userData['password'],
                PASSWORD_DEFAULT
            ),
            'role_id' => $userData['role_id'],
            'status' => $userData['status'] ?? 'active',
        ]);

        return (int) $database->lastInsertId();
    }

    public static function update(int $userId, array $userData): bool
    {
        $statement = getDbConnection()->prepare(
            'UPDATE users
             SET first_name = :first_name,
                 last_name = :last_name,
                 email = :email,
                 role_id = :role_id,
                 status = :status
             WHERE user_id = :user_id'
        );

        return $statement->execute([
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'role_id' => $userData['role_id'],
            'status' => $userData['status'],
            'user_id' => $userId,
        ]);
    }

    public static function delete(int $userId): bool
    {
        $statement = getDbConnection()->prepare(
            'DELETE FROM users WHERE user_id = :user_id'
        );

        return $statement->execute(['user_id' => $userId]);
    }

    public static function setPassword(int $userId, string $password): bool
    {
        $statement = getDbConnection()->prepare(
            'UPDATE users
             SET password_hash = :password_hash,
                 must_change_password = 0
             WHERE user_id = :user_id'
        );

        return $statement->execute([
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'user_id' => $userId,
        ]);
    }

    public static function verifyPassword(array $user, string $password): bool
    {
        return password_verify($password, $user['password_hash']);
    }
}
