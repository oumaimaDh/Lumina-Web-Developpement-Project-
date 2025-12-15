<?php
require_once __DIR__ . '/../config/Database.php';

class ResponseDAO {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Get responses by question ID
     */
    public function findByQuestionId($questionId) {
        try {
            // ✅ Using YOUR table name: forum_responses
            $sql = "SELECT * FROM forum_responses 
                    WHERE ID_QUESTION = :id 
                    ORDER BY date_response DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $questionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in findByQuestionId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create new response
     */
    public function create($questionId, $content) {
        try {
            // ✅ Using YOUR table structure
            $sql = "INSERT INTO forum_responses 
                    (ID_QUESTION, ID_USER, CONTENT, date_response, likes) 
                    VALUES (:id_question, 1, :content, NOW(), 0)";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id_question' => $questionId,
                ':content' => $content
            ]);
            
            if ($result) {
                return (int) $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in create response: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update response
     */
    public function update($id, $content) {
        try {
            $sql = "UPDATE forum_responses 
                    SET CONTENT = :content 
                    WHERE ID_RESPONSE = :id";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':content' => $content
            ]);
        } catch (PDOException $e) {
            error_log("Error in update response: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete response
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM forum_responses WHERE ID_RESPONSE = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error in delete response: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Increment likes
     */
    public function incrementLikes($id) {
        try {
            $sql = "UPDATE forum_responses SET likes = likes + 1 WHERE ID_RESPONSE = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            // Get updated like count
            $sql = "SELECT likes FROM forum_responses WHERE ID_RESPONSE = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in incrementLikes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Count responses for a question
     */
    public function countByQuestionId($questionId) {
        try {
            $sql = "SELECT COUNT(*) FROM forum_responses WHERE ID_QUESTION = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $questionId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Count total responses
     */
    public function countAll() {
        try {
            $sql = "SELECT COUNT(*) FROM forum_responses";
            return (int) $this->conn->query($sql)->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in countAll: " . $e->getMessage());
            return 0;
        }
    }
}
?>