<?php
require "../../config/db.php";

$db = new Database();
$conn = $db->connect();

$id = $_POST["id"];
$reason = $_POST["reason"];

$stmt = $conn->prepare("UPDATE sponsors SET contract_status='Approved', decision_notes=? WHERE id=?");
if ($stmt->execute([$reason, $id])) {
    echo "success";
} else {
    echo "error";
}
?>