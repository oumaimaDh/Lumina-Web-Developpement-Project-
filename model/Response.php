<?php
class Response {
    private ?int $id = null;
    private int $questionId;
    private string $content;
    private int $likes = 0;

    public function __construct(int $questionId, string $content) {
        $this->questionId = $questionId;
        $this->content = $content;
    }

    public function getId(): ?int { return $this->id; }
    public function getQuestionId(): int { return $this->questionId; }
    public function getContent(): string { return $this->content; }
    public function getLikes(): int { return $this->likes; }

    public function setId(int $id): void { $this->id = $id; }
}