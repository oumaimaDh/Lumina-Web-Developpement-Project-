<?php

class Notification {
    private ?int $id_notification;
    private string $message;
    private int $id_case;
    private string $created_at;
    private int $is_read;

    public function __construct(?int $id_notification, string $message, int $id_case, string $created_at, int $is_read = 0) {
        $this->id_notification = $id_notification;
        $this->message = $message;
        $this->id_case = $id_case;
        $this->created_at = $created_at;
        $this->is_read = $is_read;
    }

    // Getters
    public function getIdNotification(): ?int { return $this->id_notification; }
    public function getMessage(): string { return $this->message; }
    public function getIdCase(): int { return $this->id_case; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getIsRead(): int { return $this->is_read; }

    // Setters
    public function setIdNotification(?int $id_notification): self { 
        $this->id_notification = $id_notification; 
        return $this; 
    }
    public function setMessage(string $message): self { 
        $this->message = $message; 
        return $this; 
    }
    public function setIdCase(int $id_case): self { 
        $this->id_case = $id_case; 
        return $this; 
    }
    public function setCreatedAt(string $created_at): self { 
        $this->created_at = $created_at; 
        return $this; 
    }
    public function setIsRead(int $is_read): self { 
        $this->is_read = $is_read; 
        return $this; 
    }

    // CRUD Methods
    public function create(): void {
        $pdo = config::getConnexion();
        $query = "INSERT INTO notification (message, id_case, created_at, is_read) 
                  VALUES (:message, :id_case, :created_at, :is_read)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'message' => $this->message,
            'id_case' => $this->id_case,
            'created_at' => $this->created_at,
            'is_read' => $this->is_read
        ]);
    }

    public static function getAll(): array {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM notification ORDER BY created_at DESC";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUnreadCount(): int {
        $pdo = config::getConnexion();
        $query = "SELECT COUNT(*) as count FROM notification WHERE is_read = 0";
        $stmt = $pdo->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public static function getUnread(): array {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM notification WHERE is_read = 0 ORDER BY created_at DESC";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function markAsRead(int $id_notification): void {
        $pdo = config::getConnexion();
        $query = "UPDATE notification SET is_read = 1 WHERE id_notification = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id_notification]);
    }

    public static function markAllAsRead(): void {
        $pdo = config::getConnexion();
        $query = "UPDATE notification SET is_read = 1 WHERE is_read = 0";
        $stmt = $pdo->query($query);
    }

    public static function delete(int $id_notification): void {
        $pdo = config::getConnexion();
        $query = "DELETE FROM notification WHERE id_notification = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id_notification]);
    }

    public static function getById(int $id_notification): ?Notification {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM notification WHERE id_notification = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id_notification]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        return new Notification(
            $data['id_notification'],
            $data['message'],
            $data['id_case'],
            $data['created_at'],
            $data['is_read']
        );
    }
}

