<?php
session_start();
require_once __DIR__ . '/../../model/User.php';

if (isset($_SESSION['user_id'])) {
    $userModel = new User();
    $userModel->setRememberToken($_SESSION['user_id'], null); // Clear token
    setcookie('remember_token', '', time() - 3600, "/");
}

session_destroy();
header("Location: login.html");
exit;
?>