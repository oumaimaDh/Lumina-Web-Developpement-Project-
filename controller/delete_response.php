<?php
require_once __DIR__ . '/../model/ResponseDAO.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Invalid request']);
    exit;
}

$id_response = $_POST['id_response'] ?? null;

if (!$id_response) {
    echo json_encode(['success' => false, 'msg' => 'ID required']);
    exit;
}

try {
    $dao = new ResponseDAO();
    $dao->delete($id_response);
    echo json_encode(['success' => true, 'msg' => 'Deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
}