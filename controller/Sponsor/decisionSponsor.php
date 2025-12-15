<?php
require_once "../../config/db.php";

$db = new Database();
$pdo = $db->connect();

$id     = $_POST["id"];
$reason = $_POST["reason"] ?? "";

// APPROVE ACTION (keep sponsor in dashboard & DB)
if (isset($_POST["approve"])) {

    $stmt = $pdo->prepare("UPDATE sponsors SET contract_status='Approved', decision_reason=? WHERE id=?");
    $stmt->execute([$reason, $id]);

    header("Location: ../../view/BackOffice/src/Code/index.php?decision=approved");
    exit;
}

// CANCEL ACTION (delete sponsor)
if (isset($_POST["cancel"])) {

    $stmt = $pdo->prepare("DELETE FROM sponsors WHERE id=?");
    $stmt->execute([$id]);

    header("Location: ../../view/BackOffice/src/Code/index.php?decision=cancelled");
    exit;
}
?>
