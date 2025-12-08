<?php
// viewj/Backjob/index.php - Fichier principal Jobs
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure les contrôleurs
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\OfferController.php';
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\AssociationController.php';
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\InterviewController.php';
// Initialiser les contrôleurs
$offerController = new OfferController();
$associationController = new AssociationController();
$interviewController = new InterviewController();
// Gérer les actions CRUD
$action = $_GET['action'] ?? '';
$view = $_GET['view'] ?? 'categories';

// TRAITEMENT DES ACTIONS CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CREATE - Créer une nouvelle offre
    if (isset($_POST['create_offer'])) {
        $offerData = [
            'association_id' => $_POST['association_id'],
            'title' => $_POST['job-title'],
            'location' => $_POST['job-location'],
            'salary_min' => $_POST['salary-min'],
            'salary_max' => $_POST['salary-max'],
            'expiration_date' => $_POST['expiration-date'],
            'description' => $_POST['job-description'],
            'contract_types' => json_encode($_POST['contract-type'] ?? []),
            'skills' => json_encode(explode(',', $_POST['skills_input'] ?? '')),
            'status' => 'active'
        ];
        
        if ($offerController->addOffer($offerData)) {
            header('Location: index.php?view=offers');
            exit;
        }
    }
    
    // UPDATE - Mettre à jour une offre
    if (isset($_POST['update_offer'])) {
        $id = $_POST['offer_id'];
        $success = $offerController->updateOffer(
            $id,
            $_POST['job-title'],
            $_POST['job-location'],
            $_POST['salary-min'],
            $_POST['salary-max'],
            $_POST['expiration-date'],
            $_POST['job-description'],
            json_encode($_POST['contract-type'] ?? []),
            json_encode(explode(',', $_POST['skills_input'] ?? '')),
            $_POST['status'] ?? 'active'
        );
        
        if ($success) {
            header('Location: index.php?view=offers');
            exit;
        }
    }
}

// DELETE - Supprimer une offre
if ($action === 'delete_offer' && isset($_GET['id'])) {
    if ($offerController->deleteOffer($_GET['id'])) {
        header('Location: index.php?view=offers');
        exit;
    }
}

// Récupérer les données
$offers = $offerController->getOffers();
$associations = $associationController->getAssociations();

// Données des associations par catégorie
$categories = ['health', 'restaurants', 'education', 'construction', 'commerce', 'other'];
$associationsByCategory = [];
foreach ($categories as $category) {
    $associationsByCategory[$category] = $associationController->getAssociationsByCategory($category);
}

// Variable pour l'édition
$editingOffer = null;
if (isset($_GET['edit_id'])) {
    $editingOffer = $offerController->getOfferById($_GET['edit_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina - Job Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="job.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
                <span class="logo-text">Lumina</span>
            </div>

            <nav class="sidebar-nav">
                <a href="../../index.php?section=dashboard" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="../../index.php?section=social" class="nav-item">
                    <i class="fas fa-heart"></i>
                    <span>Social Cases</span>
                </a>
                <a href="../../index.php?section=associations" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Associations</span>
                </a>
                <a href="index.php" class="nav-item active">
                    <i class="fas fa-briefcase"></i>
                    <span>Jobs</span>
                </a>
                <a href="../../index.php?section=forum" class="nav-item">
                    <i class="fas fa-comments"></i>
                    <span>Forum</span>
                </a>
                <a href="../../index.php?section=settings" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
                <div class="header-logo">
                    <img src="logo.png.png" alt="Lumina Logo" class="logo-image">
                </div>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search jobs, associations..." class="search-input" id="globalSearch">
                </div>
                <div class="header-date">
                    <p id="currentDate"><?= date('l, F j, Y') ?></p>
                </div>
                <div class="header-actions">
                    <button class="icon-btn" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot" id="notificationDot"></span>
                    </button>
                    <div class="user-profile" onclick="toggleUserMenu()">
                        <div class="user-info">
                            <p class="user-name">Stella Walton</p>
                            <p class="user-role">Administrator</p>
                        </div>
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Inclure bjob.php (section jobs principale) -->
            <?php include 'bjob.php'; ?>
        </main>
    </div>

    <!-- Scripts JavaScript -->
    <script src="script.js"></script>
    <script src="scriptjob.js"></script>
</body>
</html>