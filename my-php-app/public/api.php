<?php
// Single JSON entry point for the Admin Dashboard front-end.
// Example calls the JS makes:
//   GET  /api.php?resource=summary
//   GET  /api.php?resource=users
//   POST /api.php?resource=users            body: {name,email,role,status}
//   PUT  /api.php?resource=users&id=3        body: {name,email,role,status}
//   DELETE /api.php?resource=users&id=3
//   GET/POST/DELETE  /api.php?resource=stock(&id=)
//   GET  /api.php?resource=report
//   GET/POST/DELETE  /api.php?resource=audit(&id=)

require_once __DIR__ . '/../src/middleware/authentication.php';
require_once __DIR__ . '/../src/middleware/rbacmiddleware.php';
require_once __DIR__ . '/../src/controllers/admin-controller.php';
require_once __DIR__ . '/../src/controllers/product-controller.php';

header('Content-Type: application/json');

$user     = requireLoginOrJson401();
$resource = $_GET['resource'] ?? '';
$method   = $_SERVER['REQUEST_METHOD'];
$id       = isset($_GET['id']) ? (int) $_GET['id'] : null;
$input    = json_decode(file_get_contents('php://input'), true) ?? [];

if (!in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
    requireValidCsrfToken();
}

switch ($resource) {
    case 'summary':
        requirePermission($user, 'reports.inventory.view');
        echo json_encode(ProductController::summary());
        break;

    case 'users':
        if ($method === 'GET') {
            requirePermission($user, 'users.manage');
            echo json_encode(AdminController::listUsers());
        } elseif ($method === 'POST') {
            requirePermission($user, 'users.manage');
            echo json_encode(AdminController::createUser($input, (int) $user['user_id']));
        } elseif ($method === 'PUT' && $id) {
            requirePermission($user, 'users.manage');
            echo json_encode(AdminController::updateUser($id, $input, (int) $user['user_id']));
        } elseif ($method === 'DELETE' && $id) {
            requirePermission($user, 'users.manage');
            echo json_encode(AdminController::deleteUser($id, (int) $user['user_id']));
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Bad request']);
        }
        break;

    case 'roles':
        requirePermission($user, 'users.manage');
        echo json_encode(AdminController::listRoles());
        break;

    case 'stock':
        if ($method === 'GET') {
            requirePermission($user, 'inventory.manage');
            echo json_encode(ProductController::listStock());
        } elseif ($method === 'POST') {
            requirePermission($user, 'inventory.manage');
            echo json_encode(ProductController::createStockItem($input));
        } elseif ($method === 'DELETE' && $id) {
            requirePermission($user, 'inventory.manage');
            echo json_encode(ProductController::deleteStockItem($id));
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Bad request']);
        }
        break;

    case 'report':
        requirePermission($user, 'reports.inventory.view');
        echo json_encode(ProductController::inventoryReport());
        break;

    case 'audit':
        if ($method === 'GET') {
            requirePermission($user, 'reports.audit_logs.view');
            echo json_encode(AdminController::listAudit());
        } elseif ($method === 'DELETE') {
            requirePermission($user, 'reports.audit_logs.view');
            echo json_encode(AdminController::clearAudit());
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Bad request']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Unknown resource']);
}
