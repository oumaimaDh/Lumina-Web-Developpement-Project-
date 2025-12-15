<?php
require_once "../config/db.php";
require_once "../model/ParticipantModel.php";

if (!isset($_POST["id"])) die("Invalid request");

$db = new Database();
$pdo = $db->connect();
$model = new ParticipantModel($pdo);

$model->deleteParticipant($_POST["id"]);

header("Location: ../view/BackOffice/src/Code/index.php?tab=participants");
exit;
