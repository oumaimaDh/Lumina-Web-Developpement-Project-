<?php
require_once "../../config/db.php";
require_once "../../model/EventModel.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "invalid_request";
    exit;
}

$id        = $_POST["id"] ?? null;
$title     = $_POST["title"] ?? null;
$desc      = $_POST["description"] ?? null;
$date      = $_POST["date"] ?? null;
$deadline  = $_POST["deadline"] ?? null;
$location  = $_POST["location"] ?? null;
$status    = $_POST["status"] ?? null;
$category  = $_POST["category"] ?? null;

if (!$id || !$title || !$desc || !$date || !$location) {
    echo "missing_fields";
    exit;
}

$db = new Database();
$pdo = $db->connect();
$model = new EventModel($pdo);

$ok = $model->updateEvent($id, $title, $desc, $date, $deadline, $location, $status, $category);

echo $ok ? "success" : "error";
?>