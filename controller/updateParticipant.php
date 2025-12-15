<?php
require_once __DIR__ . '/../model/ParticipantModel.php';

if (!isset($_POST['id'])) {
    echo "Missing ID";
    exit;
}

$id = $_POST['id'];
$first = $_POST['firstName'];
$last = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$event_id = $_POST['event_id'];

$model = new ParticipantModel();

$ok = $model->updateParticipant($id, $first, $last, $email, $phone, $event_id);

echo $ok ? "success" : "error";
?>