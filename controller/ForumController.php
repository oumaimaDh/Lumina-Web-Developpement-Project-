<?php
require_once __DIR__ . '/../model/QuestionDAO.php';

class ForumController {
    private QuestionDAO $questionDAO;

    public function __construct() {
        $this->questionDAO = new QuestionDAO();
    }

    public function getAllQuestions(): array {
        return $this->questionDAO->findAll();
    }

    public function getQuestionById(int $id): ?array {
        return $this->questionDAO->findById($id);
    }

    public function searchQuestions(string $keyword): array {
        return $this->questionDAO->search($keyword);
    }
}