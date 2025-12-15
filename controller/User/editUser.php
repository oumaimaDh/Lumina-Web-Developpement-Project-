<?php
// view/BackOffice/editUser.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/User.php';

header('Content-Type: application/json');

$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupérer un user spécifique
    $user_id = $_GET['id'] ?? 0;
    
    if ($user_id > 0) {
        $user = $userModel->findById($user_id);
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Modifier un user
    $input = json_decode(file_get_contents('php://input'), true);
    $user_id = $input['id'] ?? 0;
    
    if ($user_id > 0) {
        $username = $input['username'] ?? '';
        $email = $input['email'] ?? '';
        $role = $input['role'] ?? '';
        
        if ($userModel->update($user_id, $username, $email, $role)) {
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }
}
?>