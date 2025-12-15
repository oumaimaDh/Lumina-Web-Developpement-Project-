<?php
require "../../config/db.php";
$db = new Database();
$conn = $db->connect();
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $conn->prepare("SELECT * FROM sponsors WHERE id = ?");
    $stmt->execute([$id]);
    $s = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $s["id"] . "|" . $s["sponsor_name"] . "|" . $s["sponsorship_type"] . "|" .
         $s["contact_email"] . "|" . $s["contact_phone"] . "|" . 
         $s["contribution_notes"] . "|" . $s["event_id"];

    exit;
}


$stmt = $conn->prepare("SELECT * FROM sponsors ORDER BY id DESC");
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
