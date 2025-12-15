<?php
require_once "../config/db.php";
require_once "../model/ParticipantModel.php";

header("Content-Type: application/json");

try {
    $model = new ParticipantModel();
    $pdo = $model->getPDO();  // use getter to avoid private property error

    $sql = "SELECT 
                p.id,
                p.firstName,
                p.lastName,
                p.email,
                p.phone,
                p.event_id,      -- Matches your table structure
                p.created_at,
                e.title AS event_title
            FROM participants p
            LEFT JOIN events e ON p.event_id = e.id
            ORDER BY p.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($participants);

} catch (Exception $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
