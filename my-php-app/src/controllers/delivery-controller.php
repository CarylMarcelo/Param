<?php
require_once __DIR__ . '/../config/database.php';
class DeliveryController
{
    public static function assignedTo(int $userId): array
    {
        $stmt = getDbConnection()->prepare("SELECT d.delivery_id, d.order_id, CONCAT_WS(' ', c.first_name, c.middle_name, c.last_name, c.suffix) customer_name, o.delivery_address_snapshot, CASE WHEN uc.contact_number IS NULL THEN NULL WHEN CHAR_LENGTH(uc.contact_number) <= 4 THEN uc.contact_number ELSE CONCAT(REPEAT('*', CHAR_LENGTH(uc.contact_number) - 4), RIGHT(uc.contact_number, 4)) END masked_phone_number, d.delivery_status, d.delivery_notes, d.proof_image_path, d.assigned_at, d.delivered_at FROM deliveries d JOIN orders o ON o.order_id = d.order_id JOIN users c ON c.user_id = o.user_id LEFT JOIN user_contacts uc ON uc.contact_id = (SELECT contact_id FROM user_contacts WHERE user_id = c.user_id ORDER BY is_primary DESC, contact_id ASC LIMIT 1) WHERE d.assigned_to_user_id = :user ORDER BY FIELD(d.delivery_status, 'pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'failed'), d.created_at DESC");
        $stmt->execute(['user' => $userId]); return $stmt->fetchAll();
    }
    public static function summary(int $userId): array
    {
        $stmt = getDbConnection()->prepare("SELECT COUNT(*) total, SUM(delivery_status = 'delivered') delivered, SUM(delivery_status IN ('pending','assigned','picked_up','in_transit')) active, SUM(delivery_status = 'failed') failed FROM deliveries WHERE assigned_to_user_id = :user");
        $stmt->execute(['user' => $userId]); $row = $stmt->fetch();
        return array_map('intval', $row ?: ['total' => 0, 'delivered' => 0, 'active' => 0, 'failed' => 0]);
    }
    public static function update(int $deliveryId, int $userId, array $input): array
    {
        $allowed = ['pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'failed'];
        $status = strtolower((string) ($input['status'] ?? ''));
        if (!in_array($status, $allowed, true)) { http_response_code(422); return ['error' => 'Invalid delivery status']; }
        $db = getDbConnection();
        $stmt = $db->prepare("UPDATE deliveries SET delivery_status = :status, delivery_notes = :notes, proof_image_path = :proof, delivered_at = CASE WHEN :status_check = 'delivered' THEN COALESCE(delivered_at, NOW()) ELSE NULL END WHERE delivery_id = :id AND assigned_to_user_id = :user");
        $stmt->execute(['status' => $status, 'notes' => trim((string) ($input['notes'] ?? '')) ?: null, 'proof' => trim((string) ($input['proof'] ?? '')) ?: null, 'status_check' => $status, 'id' => $deliveryId, 'user' => $userId]);
        if ($stmt->rowCount() === 0) { $check = $db->prepare('SELECT 1 FROM deliveries WHERE delivery_id = :id AND assigned_to_user_id = :user'); $check->execute(['id' => $deliveryId, 'user' => $userId]); if (!$check->fetchColumn()) { http_response_code(404); return ['error' => 'Assigned delivery not found']; } }
        $audit = $db->prepare("INSERT INTO audit_logs (user_id, action_name, table_name, record_id, details) VALUES (:user, 'delivery.update', 'deliveries', :record, :details)");
        $audit->execute(['user' => $userId, 'record' => $deliveryId, 'details' => 'Updated assigned delivery #' . $deliveryId . ' to ' . str_replace('_', ' ', $status)]);
        return ['success' => true];
    }
}
