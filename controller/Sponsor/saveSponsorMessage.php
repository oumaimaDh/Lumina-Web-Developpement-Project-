<?php
require_once "../../model/SponsorModel.php";

$model = new SponsorModel();

$sponsor_id = $_POST["sponsor_id"] ?? null;
$message    = $_POST["message"] ?? null;

if (!$sponsor_id || !$message) {
    echo "missing_fields";
    exit;
}

if ($model->saveMessage($sponsor_id, $message)) {
    echo "success";
} else {
    echo "error";
}
?>