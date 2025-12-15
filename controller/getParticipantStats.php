<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../../config/db.php";

$db  = new Database();
$pdo = $db->connect();

// Count all participants in table
$sql = "SELECT COUNT(*) AS total FROM participants";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$total = (int)$row['total'];

header("Content-Type: application/json");
echo json_encode([
    "total" => $total
]);
?>