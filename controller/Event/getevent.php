<?php
require_once "../../config/db.php";
require_once "../../model/EventModel.php";

$db = new Database();
$pdo = $db->connect();
$model = new EventModel($pdo);

$id = $_GET["id"] ?? null;

if (!$id) {
    echo json_encode(["error" => "Missing ID"]);
    exit;
}

$event = $model->getEventById($id);

if (!$event) {
    echo json_encode(["error" => "Event not found"]);
    exit;
}

echo json_encode($event);
?>