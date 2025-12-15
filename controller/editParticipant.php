<?php
require_once "../config/db.php";
require_once "../model/ParticipantModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $db = new Database();
    $pdo = $db->connect();

    $id = $_POST["id"];
    $first = $_POST["firstName"];
    $last = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $event = $_POST[" event_id"];

    $stmt = $pdo->prepare("UPDATE participants SET 
        firstName=?, 
        lastName=?, 
        email=?, 
        phone=?, 
         event_id=? 
        WHERE id=?");

    $stmt->execute([$first, $last, $email, $phone, $event_id, $id]);

    echo "success";
}
?>
