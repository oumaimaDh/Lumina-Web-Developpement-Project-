<?php
class Question {
    private ?int $id = null;
    private string $title;
    private string $category;
    private string $content;

    public function __construct(string $title, string $category, string $content) {
        $this->title = $title;
        $this->category = $category;
        $this->content = $content;
    }

    public function getTitle(): string { return $this->title; }
    public function getCategory(): string { return $this->category; }
    public function getContent(): string { return $this->content; }
}