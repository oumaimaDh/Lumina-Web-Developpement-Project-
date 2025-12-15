<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/StatisticsController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/model/QuestionDAO.php';

$statsController = new StatisticsController();
$stats = $statsController->getForumStatistics();

$questionDAO = new QuestionDAO();
$questions = $questionDAO->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina - Admin Dashboard</title>
    <link rel="stylesheet" href="../FrontOffice/assets/css/styles.css">
    <link rel="stylesheet" href="../FrontOffice/assets/css/moduleforum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
      
        
        <main class="main-content">
            <?php include 'partials/header.php'; ?>
            
            <div id="forum-content" class="tab-content active" data-tab="forum">
                <?php include 'sections/forum.php'; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>