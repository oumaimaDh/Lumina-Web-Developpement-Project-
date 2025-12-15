<?php
// changepwd.php → نسخة plain text 100%، زي اللي عندك في الداتابيز
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../../model/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "ERROR: Invalid request";
    exit;
}

$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword     = $_POST['newPassword'] ?? '';
$userId          = $_POST['userId'] ?? '';

if (empty($currentPassword) || empty($newPassword) || empty($userId)) {
    echo "ERROR: Missing fields";
    exit;
}

$userModel = new User();
$user = $userModel->findById($userId);

if (!$user) {
    echo "ERROR: User not found";
    exit;
}

// مقارنة مباشرة (لأن كلمات المرور عندك plain text)
if ($user['password'] !== $currentPassword) {
    echo "ERROR: Current password is incorrect";
    exit;
}

// تحديث كلمة المرور كـ plain text (زي ما هي في الداتابيز)
$query = "UPDATE user SET password = ? WHERE user_id = ?";
$stmt = $pdo->prepare($query);
$result = $stmt->execute([$newPassword, $userId]);

if ($result) {
    echo "SUCCESS: Password updated successfully";
} else {
    echo "ERROR: Failed to update password";
}
?>