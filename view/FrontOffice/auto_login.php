<?php
// auto_login.php - FIXED VERSION
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/User.php';

// Get current page
$currentPage = $_SERVER['REQUEST_URI'] ?? '';
$isOnLoginPage = strpos($currentPage, 'login.html') !== false;

// ONLY auto-login if NOT on login page
if ($isOnLoginPage) {
    echo "NOT_LOGGED_IN";
    exit;
}

session_start();

if (isset($_COOKIE['remember_token'])) {
    $userModel = new User();
    $user = $userModel->getUserByRememberToken($_COOKIE['remember_token']);
    
    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        
        $userData = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        
        $isAdmin = ($user['role'] === 'admin' || $user['email'] === 'admin@lumina.com');
        
        echo "SUCCESS:" . ($isAdmin ? 'ADMIN' : 'USER') . ":" . json_encode($userData);
        exit;
    }
}

echo "NOT_LOGGED_IN";
?>