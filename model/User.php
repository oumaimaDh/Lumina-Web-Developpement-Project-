<?php
require_once __DIR__ . '/../config/db.php';

class User
{
    private $pdo;
    private $table = "user"; // ✅ IMPORTANT

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->connect();
    }

    // ✅ Get all users
    public function getAll() {
        $stmt = $this->pdo->prepare(
            "SELECT user_id, username, email FROM {$this->table}"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Créer un utilisateur (sans rôle)
    public function create($username, $email, $password) {
        $query = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

    // Login classique (plain text pour l'instant – on corrigera plus tard si tu veux)
    public function login($email, $password) {
        $query = "SELECT * FROM user WHERE email = :email AND password = :password";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Trouver par email
    public function findByEmail($email) {
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($user_id) {
        $query = "SELECT * FROM user WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // === REMEMBER ME - FONCTIONS CONSERVÉES ===
    public function setRememberToken($userId, $token) {
        $hashedToken = hash('sha256', $token);
        $query = "UPDATE user SET remember_token = :remember_token WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':remember_token', $hashedToken);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    public function getUserByRememberToken($token) {
        $hashedToken = hash('sha256', $token);
        $query = "SELECT * FROM user WHERE remember_token = :remember_token";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':remember_token', $hashedToken);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vider le token lors du logout
    public function clearRememberToken($userId) {
        $query = "UPDATE user SET remember_token = NULL WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // Changer le mot de passe
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE user SET password = :password WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // Vérifier si email existe déjà
    public function emailExists($email) {
        $query = "SELECT user_id FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Delete a user by ID
    public function delete($id) {
    $query = "DELETE FROM {$this->table} WHERE user_id = :id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}
public function update($id, $username, $email) {
    $stmt = $this->pdo->prepare(
        "UPDATE user SET username = ?, email = ? WHERE user_id = ?"
    );
    return $stmt->execute([$username, $email, $id]);
}

}



?>