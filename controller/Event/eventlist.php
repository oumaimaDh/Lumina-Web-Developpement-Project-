<?php
require_once "../../config/db.php";
require_once "../../model/EventModel.php";

$db = new Database();
$pdo = $db->connect();

$model = new EventModel($pdo);
$events = $model->getAllEvents();

header("Content-Type: application/json");
echo json_encode($events);
?>