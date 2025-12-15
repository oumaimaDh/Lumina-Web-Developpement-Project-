<?php
require_once "../../../config/db.php";

header("Content-Type: application/json");

$db = new Database();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['id'] ?? null;
    $progress = $_POST['progress'] ?? null;
    
    if (!$taskId || !$progress) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET progress = ? WHERE id = ?");
        $stmt->execute([$progress, $taskId]);
        
        echo json_encode(['success' => true, 'message' => 'Progress updated']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>