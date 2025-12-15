<?php
require_once "../../config/db.php";
require_once "../../model/User.php";

class UserController {

    private $userModel;

    public function __construct() {
        $db = new Database();
        $pdo = $db->connect();
        $this->userModel = new User($pdo);
    }

    // Return all users as JSON
    public function getUsers() {
        $users = $this->userModel->getAll();

        header("Content-Type: application/json");
        echo json_encode($users);
        exit;
    }
}

// ROUTER
$action = $_GET["action"] ?? null;

$controller = new UserController();

if ($action === "getUsers") {
    $controller->getUsers();
}
?>