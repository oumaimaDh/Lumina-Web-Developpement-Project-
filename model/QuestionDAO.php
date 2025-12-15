<?php
require_once __DIR__ . '/../config/Database.php';

class QuestionDAO {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Get all questions
     */
    public function findAll() {
        try {
            $sql = "SELECT * FROM questions ORDER BY date_question DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in findAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Find question by ID
     */
    public function findById($id) {
        try {
            $sql = "SELECT * FROM questions WHERE id_question = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in findById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Search questions
     */
    public function search($keyword) {
        try {
            $sql = "SELECT * FROM questions 
                    WHERE title LIKE :keyword 
                    OR content LIKE :keyword 
                    OR category LIKE :keyword 
                    ORDER BY date_question DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => '%' . $keyword . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in search: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create new question
     */
    public function create($question) {
        try {
            $sql = "INSERT INTO questions (title, content, category, date_question, id_user, likes, status) 
                    VALUES (:title, :content, :category, NOW(), 0, 0, 'new')";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':title' => $question->getTitle(),
                ':category' => $question->getCategory(),
                ':content' => $question->getContent()
            ]);
            
            if ($result) {
                return (int) $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update question
     */
    public function update($id, $title, $category, $content) {
        try {
            $sql = "UPDATE questions 
                    SET title = :title, category = :category, content = :content 
                    WHERE id_question = :id";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':category' => $category,
                ':content' => $content
            ]);
        } catch (PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete question (and its responses via CASCADE)
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM questions WHERE id_question = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Increment likes
     */
    public function incrementLikes($id) {
        try {
            $sql = "UPDATE questions SET likes = likes + 1 WHERE id_question = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            // Get updated like count
            $sql = "SELECT likes FROM questions WHERE id_question = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in incrementLikes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get questions by category
     */
    public function findByCategory($category) {
        try {
            $sql = "SELECT * FROM questions 
                    WHERE category = :category 
                    ORDER BY date_question DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':category' => $category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in findByCategory: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent questions
     */
    public function getRecent($limit = 10) {
        try {
            $sql = "SELECT * FROM questions 
                    ORDER BY date_question DESC 
                    LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRecent: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get most liked questions
     */
    public function getMostLiked($limit = 5) {
        try {
            $sql = "SELECT * FROM questions 
                    ORDER BY likes DESC 
                    LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getMostLiked: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count total questions
     */
    public function count() {
        try {
            $sql = "SELECT COUNT(*) FROM questions";
            return (int) $this->conn->query($sql)->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in count: " . $e->getMessage());
            return 0;
        }
    }
}
?>