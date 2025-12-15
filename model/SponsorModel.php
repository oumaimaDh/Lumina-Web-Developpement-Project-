<?php
require_once __DIR__ . '/../config/db.php';

class SponsorModel {

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }

    // ---------------------------
    // INSERT SPONSOR
    // ---------------------------
    public function addSponsor($name, $email, $phone, $type, $eventId, $notes) {

    // If event_id is not a number → treat it as NULL
    if (!is_numeric($eventId)) {
        $eventId = null;
    }

    $sql = "INSERT INTO sponsors 
            (sponsor_name, contact_email, contact_phone, sponsorship_type, event_id, contribution_notes)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$name, $email, $phone, $type, $eventId, $notes]);
}


    // ---------------------------
    // GET ALL SPONSORS
    // ---------------------------
    public function getSponsors() {
        $sql = "SELECT * FROM sponsors ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---------------------------
    // GET SPONSOR BY ID
    // ---------------------------
    public function getSponsorById($id) {
        $sql = "SELECT * FROM sponsors WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ---------------------------
    // UPDATE SPONSOR
    // ---------------------------
    public function updateSponsor($id, $name, $email, $phone, $type, $eventId, $notes) {
        $sql = "UPDATE sponsors 
                SET sponsor_name=?, contact_email=?, contact_phone=?, sponsorship_type=?, event_id=?, contribution_notes=?
                WHERE id=?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $email, $phone, $type, $eventId, $notes, $id]);
    }

    // ---------------------------
    // DELETE SPONSOR
    // ---------------------------
    public function deleteSponsor($id) {
        $sql = "DELETE FROM sponsors WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
  public function saveMessage($sponsor_id, $message) {
    try {
        $sql = "INSERT INTO sponsor_messages (sponsor_id, message, created_at)
                VALUES (:sponsor_id, :message, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":sponsor_id", $sponsor_id);
        $stmt->bindParam(":message", $message);

        return $stmt->execute();

    } catch (Exception $e) {
        return false;
    }
}



}
?>