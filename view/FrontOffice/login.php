<?php
// view/FrontOffice/login.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userModel = new User();
    $user = $userModel->login($email, $password);
    
    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        // Prepare user data for JavaScript
        $userData = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'email' => $user['email'],
        ];
        
        $jsonUserData = json_encode($userData);
        
        // Handle "Remember Me"
        if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
            $token = bin2hex(random_bytes(32)); // Secure random token
            $userModel->setRememberToken($user['user_id'], $token);
            
        }
        
        // Check if user is admin
        $isAdmin = ( $email === 'admin@lumina.com');
        
        if ($isAdmin) {
            echo "SUCCESS:ADMIN:" . $jsonUserData;
        } else {
            echo "SUCCESS:USER:" . $jsonUserData;
        }
    } else {
        echo "ERROR: Invalid email or password";
    }
}
?>
