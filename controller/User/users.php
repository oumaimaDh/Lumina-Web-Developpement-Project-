<?php
// ⚠️ MUST BE FIRST — NO SPACES, NO BOM ABOVE THIS LINE
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../model/User.php';

try {
    $user = new User();

    // DELETE (AJAX)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['id'])) {
            $user->delete($input['id']);
            echo json_encode(["success" => true]);
            exit;
        }

        echo json_encode(["success" => false, "message" => "Missing ID"]);
        exit;
    }

    // GET USERS
    $users = $user->getAll();

    echo json_encode([
        "success" => true,
        "users" => $users
    ]);
    exit;

} catch (Throwable $e) {
    // NEVER echo HTML here
    echo json_encode([
        "success" => false,
        "message" => "Server error"
    ]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if ($input['action'] === 'update') {
        $user->update(
            $input['id'],
            $input['username'],
            $input['email']
        );
        echo json_encode(["success" => true]);
        exit;
    }
}

?>