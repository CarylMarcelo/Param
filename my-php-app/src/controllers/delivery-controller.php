<?php

require_once __DIR__ . '/../config/database.php';

class DeliveryController
{
    public static function assignedTo(int $deliveryUserId): array
    {
        $query = "SELECT
                    deliveries.delivery_id,
                    deliveries.order_id,
                    CONCAT_WS(' ', customers.first_name, customers.middle_name, customers.last_name, customers.suffix) AS customer_name,
                    orders.delivery_address_snapshot,
                    CASE
                        WHEN contacts.contact_number IS NULL THEN NULL
                        WHEN CHAR_LENGTH(contacts.contact_number) <= 4 THEN contacts.contact_number
                        ELSE CONCAT(
                            REPEAT('*', CHAR_LENGTH(contacts.contact_number) - 4),
                            RIGHT(contacts.contact_number, 4)
                        )
                    END AS masked_phone_number,
                    deliveries.delivery_status,
                    deliveries.delivery_notes,
                    deliveries.proof_image_path,
                    deliveries.assigned_at,
                    deliveries.delivered_at
                  FROM deliveries
                  JOIN orders ON orders.order_id = deliveries.order_id
                  JOIN users customers ON customers.user_id = orders.user_id
                  LEFT JOIN user_contacts contacts ON contacts.contact_id = (
                      SELECT contact_id
                      FROM user_contacts
                      WHERE user_id = customers.user_id
                      ORDER BY is_primary DESC, contact_id ASC
                      LIMIT 1
                  )
                  WHERE deliveries.assigned_to_user_id = :delivery_user_id
                  ORDER BY
                    FIELD(
                        deliveries.delivery_status,
                        'pending',
                        'assigned',
                        'picked_up',
                        'in_transit',
                        'delivered',
                        'failed'
                    ),
                    deliveries.created_at DESC";

        $statement = getDbConnection()->prepare($query);
        $statement->execute(['delivery_user_id' => $deliveryUserId]);

        return $statement->fetchAll();
    }

    public static function summary(int $deliveryUserId): array
    {
        $statement = getDbConnection()->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(delivery_status = 'delivered') AS delivered,
                SUM(delivery_status IN ('pending', 'assigned', 'picked_up', 'in_transit')) AS active,
                SUM(delivery_status = 'failed') AS failed
             FROM deliveries
             WHERE assigned_to_user_id = :delivery_user_id"
        );
        $statement->execute(['delivery_user_id' => $deliveryUserId]);

        $summary = $statement->fetch() ?: [
            'total' => 0,
            'delivered' => 0,
            'active' => 0,
            'failed' => 0,
        ];

        return array_map('intval', $summary);
    }

    public static function update(
        int $deliveryId,
        int $deliveryUserId,
        array $input
    ): array {
        $allowedStatuses = [
            'pending',
            'assigned',
            'picked_up',
            'in_transit',
            'delivered',
            'failed',
        ];
        $status = strtolower((string) ($input['status'] ?? ''));

        if (!in_array($status, $allowedStatuses, true)) {
            http_response_code(422);
            return ['error' => 'Invalid delivery status'];
        }

        $database = getDbConnection();
        $statement = $database->prepare(
            "UPDATE deliveries
             SET delivery_status = :status,
                 delivery_notes = :notes,
                 proof_image_path = :proof_image_path,
                 delivered_at = CASE
                     WHEN :delivered_status = 'delivered'
                     THEN COALESCE(delivered_at, NOW())
                     ELSE NULL
                 END
             WHERE delivery_id = :delivery_id
               AND assigned_to_user_id = :delivery_user_id"
        );
        $statement->execute([
            'status' => $status,
            'notes' => trim((string) ($input['notes'] ?? '')) ?: null,
            'proof_image_path' => trim((string) ($input['proof'] ?? '')) ?: null,
            'delivered_status' => $status,
            'delivery_id' => $deliveryId,
            'delivery_user_id' => $deliveryUserId,
        ]);

        if ($statement->rowCount() === 0 && !self::isAssignedToUser($deliveryId, $deliveryUserId)) {
            http_response_code(404);
            return ['error' => 'Assigned delivery not found'];
        }

        self::recordAudit($deliveryUserId, $deliveryId, $status);

        return ['success' => true];
    }

    private static function isAssignedToUser(int $deliveryId, int $deliveryUserId): bool
    {
        $statement = getDbConnection()->prepare(
            'SELECT 1
             FROM deliveries
             WHERE delivery_id = :delivery_id
               AND assigned_to_user_id = :delivery_user_id'
        );
        $statement->execute([
            'delivery_id' => $deliveryId,
            'delivery_user_id' => $deliveryUserId,
        ]);

        return (bool) $statement->fetchColumn();
    }

    private static function recordAudit(
        int $deliveryUserId,
        int $deliveryId,
        string $status
    ): void {
        $statement = getDbConnection()->prepare(
            "INSERT INTO audit_logs (
                user_id,
                action_name,
                table_name,
                record_id,
                details
             ) VALUES (
                :user_id,
                'delivery.update',
                'deliveries',
                :delivery_id,
                :details
             )"
        );
        $statement->execute([
            'user_id' => $deliveryUserId,
            'delivery_id' => $deliveryId,
            'details' => 'Updated assigned delivery #' . $deliveryId
                . ' to ' . str_replace('_', ' ', $status),
        ]);
    }
}
