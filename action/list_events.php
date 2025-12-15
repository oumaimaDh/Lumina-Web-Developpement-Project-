<?php
require_once "../model/EventC.php";

$eventC = new EventC();
$events = $eventC->listEvents();

echo json_encode($events);
