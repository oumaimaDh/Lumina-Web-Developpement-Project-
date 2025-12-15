<?php
require_once __DIR__ . '/../config/Database.php';

class StatisticsController {
    private PDO $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getForumStatistics(): array {
        $totalQuestions = $this->conn->query("SELECT COUNT(*) FROM questions")->fetchColumn();
        $totalResponses = $this->conn->query("SELECT COUNT(*) FROM forum_responses")->fetchColumn(); // FIXED
        
        $questionLikes = $this->conn->query("SELECT SUM(likes) FROM questions")->fetchColumn() ?: 0;
        $responseLikes = $this->conn->query("SELECT SUM(likes) FROM forum_responses")->fetchColumn() ?: 0; // FIXED
        $totalLikes = $questionLikes + $responseLikes;
        
        $topQuestionStmt = $this->conn->query(
            "SELECT title, likes FROM questions ORDER BY likes DESC LIMIT 1"
        );
        $topQuestion = $topQuestionStmt->fetch() ?: ['title' => 'No questions yet', 'likes' => 0];
        
        $categoriesStmt = $this->conn->query(
            "SELECT category, COUNT(*) as total FROM questions GROUP BY category"
        );
        $categories = $categoriesStmt->fetchAll();

        return [
            'totalQuestions' => $totalQuestions,
            'totalResponses' => $totalResponses,
            'totalLikes' => $totalLikes,
            'topQuestion' => $topQuestion,
            'categories' => $categories
        ];
    }
}