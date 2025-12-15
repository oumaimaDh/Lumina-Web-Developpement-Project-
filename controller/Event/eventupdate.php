<?php
require_once "../../config/db.php";
require_once "../../model/EventModel.php";

$db = new Database();
$pdo = $db->connect();
$model = new EventModel($pdo);

// Get POST
$id          = $_POST["id"] ?? null;
$title       = $_POST["title"] ?? null;
$description = $_POST["description"] ?? null;
$date        = $_POST["date"] ?? null;
$deadline    = $_POST["deadline"] ?? null;
$location    = $_POST["location"] ?? null;
$status      = $_POST["status"] ?? null;
$category    = $_POST["category"] ?? null;

// Required fields
if (!$id || !$title) {
    echo "missing_fields";
    exit;
}

$updated = $model->updateEvent($id, $title, $description, $date, $deadline, $location, $status, $category);

echo $updated ? "success" : "error";
?>