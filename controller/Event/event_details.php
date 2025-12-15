<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

// DB + Models
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../model/EventModel.php";
require_once __DIR__ . "/../../model/ParticipantModel.php";
require_once __DIR__ . "/../../model/SponsorModel.php";

// Connect DB
$db = new Database();
$pdo = $db->connect();

// Validate ID
if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing ID"]);
    exit;
}

$eventId = intval($_GET['id']);

// Load event info
$eventModel = new EventModel($pdo);
$event = $eventModel->getEventById($eventId);

if (!$event) {
    echo json_encode(["error" => "Event not found"]);
    exit;
}

// Load participants
$participantModel = new ParticipantModel($pdo);
$participants = $participantModel->getParticipants();

$filteredParticipants = array_filter($participants, function ($p) use ($eventId) {
    return $p["event_id"] == $eventId;
});

// Load sponsors
$sponsorModel = new SponsorModel($pdo);
$sponsors = $sponsorModel->getSponsors();

$filteredSponsors = array_filter($sponsors, function ($s) use ($eventId) {
    return $s["event_id"] == $eventId;
});

// Return JSON
echo json_encode([
    "event" => $event,
    "participants_count" => count($filteredParticipants),
    "sponsors" => array_values($filteredSponsors)
]);

exit;
?>
