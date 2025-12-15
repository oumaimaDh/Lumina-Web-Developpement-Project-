<?php
require_once __DIR__ . '/../model/ResponseDAO.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'msg' => 'ID required']);
    exit;
}

try {
    $dao = new ResponseDAO();
    $likes = $dao->incrementLikes($id);
    echo json_encode(['status' => 'success', 'likes' => $likes]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}