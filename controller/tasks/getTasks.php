<?php
require_once "../../config/db.php";
require_once "../../model/TaskModel.php";

header("Content-Type: application/json");

$db = new Database();
$pdo = $db->connect();
$model = new TaskModel($pdo);

$tasks = $model->getAllTasks();

echo json_encode($tasks);
exit;
?>