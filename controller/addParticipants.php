<?php
require_once "../config/db.php";
require_once "../model/ParticipantModel.php";

// Don't set any headers - let it be silent
// header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Don't output anything, just exit
    exit;
}

$fname    = trim($_POST["firstName"] ?? "");
$lname    = trim($_POST["lastName"] ?? "");
$email    = trim($_POST["userEmail"] ?? "");
$phone    = trim($_POST["userPhone"] ?? "");
$event_id = intval($_POST["event_id"] ?? 0);

// Validate silently
if ($fname === "" || $lname === "" || $email === "" || $phone === "" || $event_id === 0) {
    exit;
}

try {
    $model = new ParticipantModel();
    $data = [
        "firstName" => $fname,
        "lastName"  => $lname,
        "userEmail" => $email,
        "userPhone" => $phone,
        "event_id"  => $event_id
    ];

    $result = $model->addParticipant($data);
    // Don't output anything - just exit silently
} catch (Exception $e) {
    // Don't output anything - just exit silently
}

exit;
?>