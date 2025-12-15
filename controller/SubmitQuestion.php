<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Question.php';
require_once __DIR__ . '/../model/QuestionDAO.php';
require_once __DIR__ . '/../helpers/botHelper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$content = trim($_POST['content'] ?? '');

if (empty($title) || empty($category) || empty($content)) {
    die("All fields required!");
}

try {
    // Create question
    $question = new Question($title, $category, $content);
    $dao = new QuestionDAO();
    $id_question = $dao->create($question);

    if ($id_question) {
        // Get PDO connection for bot
        $db = new Database();
        $pdo = $db->connect();

        // Send to OpenAI bot
        $botResponse = sendToBot($content, $id_question, $pdo);
        
        
    }

    // Redirect
    header("Location: ../view/forum.php?submitted=true");
    exit;
    
} catch (Exception $e) {
    error_log("❌ Error: " . $e->getMessage());
    die("Error submitting question");
}
?>