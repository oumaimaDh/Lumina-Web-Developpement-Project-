<?php
require_once __DIR__ . '/../config/db.php';

class ParticipantModel {

    public $pdo;


    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();   // Consistent with SponsorModel & EventModel
    }
    public function getPDO() {
    return $this->pdo;
}

    /**
     * GET ALL PARTICIPANTS WITH EVENT TITLE
     */
    public function getParticipants() {
        try {
            $sql = "SELECT p.*, e.title AS event_title
                    FROM participants p
                    LEFT JOIN events e ON p.event_id = e.id
                    ORDER BY p.id DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching participants: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ADD NEW PARTICIPANT
     */
public function addParticipant($data) {
    try {

        // Show which database we're writing into
        error_log("DB USED: " . $this->pdo->query("SELECT DATABASE()")->fetchColumn());

        $sql = "INSERT INTO participants (firstName, lastName, email, phone, event_id)
                VALUES (:firstName, :lastName, :email, :phone, :event_id)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(":firstName", $data["firstName"]);
        $stmt->bindParam(":lastName",  $data["lastName"]);
        $stmt->bindParam(":email",     $data["userEmail"]);
        $stmt->bindParam(":phone",     $data["userPhone"]);
        $stmt->bindParam(":event_id",  $data["event_id"], PDO::PARAM_INT);

        $result = $stmt->execute();

        // LOG EVERYTHING
        error_log("INSERT SQL RESULT: " . ($result ? "SUCCESS" : "FAIL"));

        if (!$result) {
            error_log("INSERT ERROR INFO: " . print_r($stmt->errorInfo(), true));
        }

        return $result;

    } catch (PDOException $e) {
        error_log("PDO ERROR: " . $e->getMessage());
        return false;
    }
}

    /**
     * DELETE PARTICIPANT BY ID
     */
    public function deleteParticipant($id) {
        try {
            $sql = "DELETE FROM participants WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);

        } catch (PDOException $e) {
            error_log("Error deleting participant: " . $e->getMessage());
            return false;
        }
    }

    /**
     * GET A SINGLE PARTICIPANT BY ID
     */
    public function getParticipantById($id) {
        try {
            $sql = "SELECT * FROM participants WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching participant: " . $e->getMessage());
            return null;
        }
    }

    /**
     * UPDATE PARTICIPANT
     */
    public function updateParticipant($id, $first, $last, $email, $phone, $event_id) {
    try {
        $sql = "UPDATE participants
                SET firstName = :first,
                    lastName = :last,
                    email = :email,
                    phone = :phone,
                    event_id = :event_id
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ":first" => $first,
            ":last" => $last,
            ":email" => $email,
            ":phone" => $phone,
            ":event_id" => $event_id,
            ":id" => $id
        ]);

    } catch (Exception $e) {
        return false;
    }
}


}
?>
