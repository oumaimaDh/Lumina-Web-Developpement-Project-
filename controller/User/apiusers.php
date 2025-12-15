<?php
// delete-user.php → FONCTIONNE À 100% MÊME SUR OVH/IONOS/000WEBHOST

// === CORS IMMÉDIAT ===
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Lecture du JSON
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit;
}

require_once "../../config/db.php";
require_once __DIR__ . '/../../model/User.php';

$user = new User();
$deleted = $user->delete($id);

echo json_encode(['success' => (bool)$deleted]);

exit;