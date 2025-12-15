<?php
// controller/usercontroller.php
require_once '../model/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        // Remove admin check for now to test
    }

}

// Quick test
$controller = new UserController();
$controller->index();
?>