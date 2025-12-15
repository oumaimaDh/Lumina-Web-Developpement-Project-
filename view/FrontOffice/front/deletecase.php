<?php
// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
require_once $basePath . DIRECTORY_SEPARATOR . 'config.php';
require_once $basePath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'socialcasecontroller.php';

if (isset($_GET['id'])) {
    $id_case = $_GET['id'];
    $socialCaseController = new SocialCaseController();
    $socialCaseController->deleteSocialCase($id_case);
}

header('Location: listcases.php');
exit;
?>