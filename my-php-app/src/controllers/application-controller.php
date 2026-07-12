<?php
require_once __DIR__ . '/../config/database.php';
class ApplicationController
{
    public static function all(): array
    {
        return getDbConnection()->query("SELECT sa.application_id, CONCAT_WS(' ', sa.first_name, sa.middle_name, sa.last_name, sa.suffix) complete_name, sa.email, sa.phone, r.role_name requested_role, sa.reason, sa.experience, sa.availability, sa.status, sa.created_at FROM staff_applications sa JOIN roles r ON r.role_id = sa.requested_role_id ORDER BY FIELD(sa.status, 'pending', 'approved', 'rejected'), sa.created_at DESC")->fetchAll();
    }
    public static function review(int $id, string $status, int $actorId): array
    {
        if (!in_array($status, ['approved', 'rejected'], true)) { http_response_code(422); return ['error' => 'Review status must be approved or rejected']; }
        $db = getDbConnection();
        $lookup = $db->prepare('SELECT email, status FROM staff_applications WHERE application_id = :id'); $lookup->execute(['id' => $id]); $application = $lookup->fetch();
        if (!$application) { http_response_code(404); return ['error' => 'Application not found']; }
        if ($application['status'] !== 'pending') { http_response_code(422); return ['error' => 'Application was already reviewed']; }
        $createdUserId = null; $accountSetup = null;
        if ($status === 'approved') {
            $details = $db->prepare("SELECT CONCAT_WS(' ', first_name, middle_name, last_name, suffix) name, email, r.role_name role FROM staff_applications sa JOIN roles r ON r.role_id = sa.requested_role_id WHERE application_id = :id");
            $details->execute(['id' => $id]); $applicant = $details->fetch();
            $accountSetup = AdminController::createUser(['name' => $applicant['name'], 'email' => $applicant['email'], 'role' => $applicant['role'], 'status' => 'Active'], $actorId);
            $createdUserId = $accountSetup['user_id'];
        }
        $update = $db->prepare('UPDATE staff_applications SET status = :status, reviewed_by = :reviewer, reviewed_at = NOW(), created_user_id = :created_user WHERE application_id = :id');
        $update->execute(['status' => $status, 'reviewer' => $actorId, 'created_user' => $createdUserId, 'id' => $id]);
        $audit = $db->prepare("INSERT INTO audit_logs (user_id, action_name, table_name, record_id, details) VALUES (:user, 'application.review', 'staff_applications', :record, :details)");
        $audit->execute(['user' => $actorId, 'record' => $id, 'details' => ucfirst($status) . ' staff application: ' . $application['email']]);
        return ['success' => true, 'account_setup' => $accountSetup];
    }
}
