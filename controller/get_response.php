<?php
require_once __DIR__ . '/../model/ResponseDAO.php';

header('Content-Type: application/json');

$id_question = $_GET['id'] ?? null;

if (!$id_question) {
    echo json_encode(['error' => 'ID required']);
    exit;
}

try {
    $dao = new ResponseDAO();
    $responses = $dao->findByQuestionId($id_question);
    echo json_encode($responses);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}