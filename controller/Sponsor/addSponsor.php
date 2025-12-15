<?php
require_once __DIR__ . '/../../model/SponsorModel.php';


$model = new SponsorModel();

$name  = $_POST['sponsor_name'] ?? null;
$email = $_POST['contact_email'] ?? null;
$phone = $_POST['contact_phone'] ?? null;
$type  = $_POST['sponsorship_type'] ?? null;
$event = $_POST['event_id'] ?? null;
$notes = $_POST['contribution_notes'] ?? null;

if (!$name || !$email || !$phone || !$type) {
    echo "missing_fields";
    exit;
}

$ok = $model->addSponsor($name, $email, $phone, $type, $event, $notes);

echo $ok ? "success" : "error";
