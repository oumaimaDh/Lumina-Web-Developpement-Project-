<?php
require_once __DIR__ . '/../config/Database.php';

class DashboardController {
    private PDO $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getOverviewStats(): array {
        // Get total counts
        $totalQuestions = $this->conn->query("SELECT COUNT(*) FROM questions")->fetchColumn();
        $totalResponses = $this->conn->query("SELECT COUNT(*) FROM forum_responses")->fetchColumn(); // FIXED
        $totalUsers = 150; // Example - you can add users table later
        
        $questionLikes = $this->conn->query("SELECT SUM(likes) FROM questions")->fetchColumn() ?: 0;
        $responseLikes = $this->conn->query("SELECT SUM(likes) FROM forum_responses")->fetchColumn() ?: 0; // FIXED
        $totalLikes = $questionLikes + $responseLikes;

        return [
            'totalQuestions' => $totalQuestions,
            'totalResponses' => $totalResponses,
            'totalUsers' => $totalUsers,
            'totalLikes' => $totalLikes
        ];
    }

    public function getRecentActivity(): array {
        // Get 5 most recent questions
        $sql = "SELECT id_question, title, date_question 
                FROM questions 
                ORDER BY date_question DESC 
                LIMIT 5";
        
        return $this->conn->query($sql)->fetchAll();
    }
}