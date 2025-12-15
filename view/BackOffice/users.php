<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../../model/User.php';

$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['success' => true, 'users' => $userModel->getAll()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    
    if ($data['action'] ?? '' === 'delete' && !empty($data['id'])) {
        $id = (int)$data['id'];
        $deleted = $userModel->delete($id);   // ← retourne true/false
        
        echo json_encode(['success' => $deleted]);
    } else {
        echo json_encode(['success' => false, 'message' => 'action ou id manquant']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);