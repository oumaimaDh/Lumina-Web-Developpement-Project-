<?php
require_once __DIR__ . '/../model/QuestionDAO.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Invalid request']);
    exit;
}

$id_question = $_POST['id_question'] ?? null;

if (!$id_question) {
    echo json_encode(['success' => false, 'msg' => 'ID required']);
    exit;
}

try {
    $dao = new QuestionDAO();
    $dao->delete($id_question);
    echo json_encode(['success' => true, 'msg' => 'Deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
}