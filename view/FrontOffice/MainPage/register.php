<?php
// view/FrontOffice/register.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'];
    $password = $_POST['password'];
   
    // Utilise le NOM comme username au lieu de l'email
    $username = !empty($name) ? $name : $email;

    $userModel = new User();
    
    if ($userModel->findByEmail($email)) {
        echo "ERROR: Email déjà utilisé";
        exit();
    }

    // Utilise le nom comme username
    if ($userModel->create($username, $email, $password)) {
        echo "SUCCESS: Inscription réussie";
    } else {
        echo "ERROR: Échec de l'inscription";
    }
}
?>