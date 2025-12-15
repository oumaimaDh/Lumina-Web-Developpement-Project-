<?php
require_once __DIR__ . '/../model/QuestionDAO.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Invalid request']);
    exit;
}

$id = $_POST['id_question'] ?? null;
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$content = trim($_POST['content'] ?? '');

if (!$id || empty($title) || empty($category) || empty($content)) {
    echo json_encode(['success' => false, 'msg' => 'All fields required']);
    exit;
}

try {
    $dao = new QuestionDAO();
    $dao->update($id, $title, $category, $content);
    echo json_encode(['success' => true, 'msg' => 'Updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
}