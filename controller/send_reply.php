<?php
require_once __DIR__ . '/../model/ResponseDAO.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Invalid request']);
    exit;
}

$id_question = $_POST['id_question'] ?? null;
$content = trim($_POST['content'] ?? '');

if (!$id_question || empty($content)) {
    echo json_encode(['success' => false, 'msg' => 'All fields required']);
    exit;
}

try {
    $dao = new ResponseDAO();
    $id = $dao->create($id_question, $content);
    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
}