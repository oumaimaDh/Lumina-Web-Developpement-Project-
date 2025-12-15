<?php
require_once __DIR__ . '/../model/QuestionDAO.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
    exit;
}

$id_question = $_POST['id_question'] ?? null;

if (!$id_question) {
    echo json_encode(['status' => 'error', 'msg' => 'ID required']);
    exit;
}

try {
    $dao = new QuestionDAO();
    $likes = $dao->incrementLikes($id_question);
    echo json_encode(['status' => 'success', 'likes' => $likes]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}