<?php
require_once "../../config/db.php";

$db = new Database();
$pdo = $db->connect();

$id = $_GET["id"] ?? null;

if (!$id) {
    echo "missing_id";
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM sponsors WHERE id = ?");
    $stmt->execute([$id]);

    echo "success";
} 
catch (Exception $e) {
    echo "error: " . $e->getMessage();
}
?>