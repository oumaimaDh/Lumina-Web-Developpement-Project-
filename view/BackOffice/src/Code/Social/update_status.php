<?php
// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
require_once $basePath . DIRECTORY_SEPARATOR . 'config.php';
require_once $basePath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'socialcasecontroller.php';
require_once $basePath . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'socialcasemodel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_case']) && isset($_POST['status'])) {
    $socialCaseController = new SocialCaseController();
    $caseId = (int)$_POST['id_case'];
    $newStatus = $_POST['status'];
    
    // Get the current case data
    $currentCase = $socialCaseController->getSocialCaseById($caseId);
    
    if ($currentCase) {
        // Determine if it's an array or object
        if (is_array($currentCase)) {
            // Create updated SocialCase object from array data
            $updatedSocialCase = new SocialCase(
                $caseId,
                $currentCase['name'],
                $currentCase['phone'],
                $currentCase['email'],
                
                $currentCase['description'],
                $currentCase['location'],
                $currentCase['submited_date'],
                date('Y-m-d'), // updated_date
                $newStatus, // new status
                $currentCase['id_category'],
                $currentCase['id_association']
            );
        } else {
            // Create updated SocialCase object from object data
            $updatedSocialCase = new SocialCase(
                $caseId,
                $currentCase->getName(),
                $currentCase->getPhone(),
                $currentCase->getEmail(),
                
                $currentCase->getDescription(),
                $currentCase->getLocation(),
                $currentCase->getSubmitedDate(),
                date('Y-m-d'), // updated_date
                $newStatus, // new status
                $currentCase->getIdCategory(),
                $currentCase->getIdAssociation()
            );
        }
        
        // Update the case
        $result = $socialCaseController->updateSocialCase($updatedSocialCase);
        
        if ($result) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?success=Status updated successfully');
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=Failed to update status');
        }
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=Case not found');
    }
    exit;
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=Invalid request');
    exit;
}
?>