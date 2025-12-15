<?php
// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
require_once $basePath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'socialcasecontroller.php';

if (isset($_GET['id'])) {
    $id_association = $_GET['id'];

    $socialCaseController = new SocialCaseController();
    $socialCaseController->deleteAssociation($id_association);

    header('Location: listassociations.php');
    exit();
} else {
    echo "Association ID not provided.";
}
?>