<?php
require_once __DIR__ . '/../model/ResponseDAO.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Invalid request']);
    exit;
}

$id = $_POST['id_reponse'] ?? null;
$content = trim($_POST['content'] ?? '');

if (!$id || empty($content)) {
    echo json_encode(['success' => false, 'msg' => 'All fields required']);
    exit;
}

try {
    $dao = new ResponseDAO();
    $dao->update($id, $content);
    echo json_encode(['success' => true, 'msg' => 'Updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
}