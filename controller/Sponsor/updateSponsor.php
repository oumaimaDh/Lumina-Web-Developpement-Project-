<?php
require_once "../../config/db.php";

$database = new Database();
$pdo = $database->connect();

// Make sure the form sent the sponsor ID
if (!isset($_POST["id"])) {
    echo "Missing sponsor ID";
    exit;
}

$id    = $_POST["id"];
$name  = $_POST["sponsor_name"];
$email = $_POST["contact_email"];
$phone = $_POST["contact_phone"];
$type  = $_POST["sponsorship_type"];
$notes = $_POST["contribution_notes"];
$event = $_POST["event_id"];

// UPDATE sponsor in database
$sql = "UPDATE sponsors SET 
            sponsor_name = ?, 
            contact_email = ?, 
            contact_phone = ?, 
            sponsorship_type = ?, 
            contribution_notes = ?, 
            event_id = ?
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([$name, $email, $phone, $type, $notes, $event, $id]);

// If update is OK, return to dashboard WITHOUT breaking affichage
if ($ok) {
    header("Location: ../../view/BackOffice/src/Code/index.php?sponsor_updated=1");
    exit;
} else {
    echo "Update failed";
}
?>
