<?php
// Central PDO connection for the whole app.
// Edit these four constants to match your local MySQL setup.

define('DB_HOST', 'localhost');
define('DB_NAME', 'param_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Returns a shared PDO instance. Safe to call from anywhere
 * (models, controllers, api.php) - opens one connection per request.
 */
function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Database connection failed',
                'detail' => $e->getMessage(),
            ]);
            exit;
        }
    }

    return $pdo;
}
