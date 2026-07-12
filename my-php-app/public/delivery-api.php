<?php
require_once __DIR__ . '/../src/middleware/authentication.php';
require_once __DIR__ . '/../src/middleware/rbacmiddleware.php';
require_once __DIR__ . '/../src/controllers/delivery-controller.php';
header('Content-Type: application/json');
set_exception_handler(function (): void { http_response_code(500); echo json_encode(['error' => 'Server request failed']); });
$user = requireLoginOrJson401(); $resource = $_GET['resource'] ?? ''; $method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'HEAD'], true)) requireValidCsrfToken();
if ($resource === 'summary' && $method === 'GET') { requirePermission($user, 'deliveries.view_assigned'); echo json_encode(DeliveryController::summary((int) $user['user_id'])); }
elseif ($resource === 'deliveries' && $method === 'GET') { requirePermission($user, 'deliveries.view_assigned'); requirePermission($user, 'deliveries.view_limited_customer_info'); echo json_encode(DeliveryController::assignedTo((int) $user['user_id'])); }
elseif ($resource === 'deliveries' && $method === 'PUT' && isset($_GET['id'])) { requirePermission($user, 'deliveries.update_status'); $input = json_decode(file_get_contents('php://input'), true) ?? []; echo json_encode(DeliveryController::update((int) $_GET['id'], (int) $user['user_id'], $input)); }
else { http_response_code(404); echo json_encode(['error' => 'Unknown resource']); }
