<?php
require_once "../../config/db.php";
require_once "../../model/EventModel.php";

$db = new Database();
$conn = $db->connect();
$model = new EventModel($conn);

$id = $_GET["id"] ?? null;

if (!$id) {
    echo "missing_id";
    exit;
}

$ok = $model->deleteEvent($id);

echo $ok ? "success" : "error";
?>
