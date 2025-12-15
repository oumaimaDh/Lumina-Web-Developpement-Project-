<?php
require_once __DIR__ . '/../../model/SponsorModel.php';

$model = new SponsorModel();

$sponsor_id = $_POST['sponsor_id'] ?? null;
$message    = $_POST['message'] ?? '';
$action     = $_POST['action_type'] ?? '';

if (!$sponsor_id || !$message || !$action) {
    echo "missing_fields";
    exit;
}

$ok = $model->saveSponsorContact($sponsor_id, $message, $action);

echo $ok ? "success" : "error";
?>