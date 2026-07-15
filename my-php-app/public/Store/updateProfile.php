<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId)
        die("Unauthorized");

    $fullname = $_POST['fullname'];
    $nameParts = explode(' ', $fullname, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '';
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $db = getDbConnection();

    $newProfilePic = null;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/Final Project_PARAM/my-php-app/public/store/uploads/';

            $newFileName = "user_" . $userId . "_" . time() . "." . $ext;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
                $newProfilePic = $newFileName;

                $stmt = $db->prepare("SELECT profile_pic FROM users WHERE user_id = ?");
                $stmt->execute([$userId]);
                $oldPic = $stmt->fetchColumn();
                if ($oldPic && file_exists($uploadDir . $oldPic)) {
                    unlink($uploadDir . $oldPic);
                }
            } else {
                die("Error: Failed to move uploaded file. Check folder permissions for: " . $uploadDir);
            }
        } else {
            die("Error: Invalid file type.");
        }
    }

    if ($newProfilePic) {
        $stmt = $db->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, address=?, profile_pic=? WHERE user_id=?");
        $stmt->execute([$firstName, $lastName, $email, $phone, $address, $newProfilePic, $userId]);
        $_SESSION['user_avatar'] = 'uploads/' . $newProfilePic;
    } else {
        $stmt = $db->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, address=? WHERE user_id=?");
        $stmt->execute([$firstName, $lastName, $email, $phone, $address, $userId]);
    }

    header("Location: Profile.php?success=1");
    exit();
}
?>