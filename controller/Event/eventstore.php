<?php
require_once "../../config/db.php";
require_once "../../model/EventModel.php";

$db = new Database();
$pdo = $db->connect();

$event = new EventModel($pdo);

$data = [
    "title"       => $_POST["title"]       ?? null,
    "description" => $_POST["description"] ?? null,
    "date"        => $_POST["date"]        ?? null,
    "deadline"    => $_POST["deadline"]    ?? null,
    "location"    => $_POST["location"]    ?? null,
    "status"      => $_POST["status"]      ?? null,
    "category"    => $_POST["category"]    ?? null
];

if (!$data["title"] || !$data["date"]) {
    die("missing_fields");
}


$event->createEvent($data);

// Redirect to dashboard
header("Location: ../../view/BackOffice/src/Code/index.php");
exit;
