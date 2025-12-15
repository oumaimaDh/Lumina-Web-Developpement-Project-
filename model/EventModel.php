<?php

class EventModel {

     private $pdo;
    private $table = "events";

    // FIXED CONSTRUCTOR — USE GIVEN CONNECTION
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getEventById($id) {
        $sql = "SELECT * FROM events WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllEvents() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY date ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEvent($data) {
        $sql = "INSERT INTO events (title, description, date, deadline, location, status, category)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data["title"],
            $data["description"],
            $data["date"],
            $data["deadline"],
            $data["location"],
            $data["status"],
            $data["category"]
        ]);
    }
    public function deleteEvent($id) {
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$id]);
}

    public function updateEvent($id, $title, $description, $date, $deadline, $location, $status, $category) {
        $sql = "UPDATE events SET 
                    title = ?,
                    description = ?,
                    date = ?,
                    deadline = ?,
                    location = ?,
                    status = ?,
                    category = ?
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $title,
            $description,
            $date,
            $deadline,
            $location,
            $status,
            $category,
            $id
        ]);
    }
public function getEventDetails($id) {
    // Get event
    $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get participants
    $stmt = $this->pdo->prepare("
        SELECT firstName, lastName 
        FROM participants 
        WHERE event_id = ?
    ");
    $stmt->execute([$id]);
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get sponsors
    $stmt = $this->pdo->prepare("
        SELECT sponsor_name, sponsorship_type 
        FROM sponsors 
        WHERE event_id = ?
    ");
    $stmt->execute([$id]);
    $sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        "event" => $event,
        "participants" => $participants,
        "sponsors" => $sponsors
    ];
}


}
