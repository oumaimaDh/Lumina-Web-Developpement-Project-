<?php
// test.php → fichier de diagnostic ultra-simple
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(200);
    exit;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'method' => $_SERVER['REQUEST_METHOD'],
    'time' => date('H:i:s'),
    'message' => 'CORS et POST marchent parfaitement ici !'
]);