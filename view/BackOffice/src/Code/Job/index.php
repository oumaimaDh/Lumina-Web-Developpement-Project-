<?php
// viewj/Backjob/index.php - Fichier principal Jobs
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}

// Inclure les contrôleurs
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/AssociationController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/OfferController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/interviewController.php';

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

// Données des associations par catégorie (categories 5-9 from database)
require_once $basePath . DIRECTORY_SEPARATOR . '/../../../config.php';
$db = Config::getConnexion();
$sql = "SELECT id_category, name FROM category WHERE id_category IN (5, 6, 7, 8, 9) ORDER BY id_category";
$dbCategories = [];
try {
    $query = $db->prepare($sql);
    $query->execute();
    $dbCategories = $query->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    // Fallback to hardcoded categories
    $dbCategories = [
        ['id_category' => 5, 'name' => 'Health & Hospitals'],
        ['id_category' => 6, 'name' => 'Education'],
        ['id_category' => 7, 'name' => 'Commerce & Services'],
        ['id_category' => 8, 'name' => 'Construction'],
        ['id_category' => 9, 'name' => 'Restaurant']
    ];
}

// Map database categories to category names for controller
$categoryMap = [
    5 => 'health',
    6 => 'education',
    7 => 'commerce',
    8 => 'construction',
    9 => 'restaurant'
];

$associationsByCategory = [];
foreach ($dbCategories as $dbCat) {
    $categoryId = $dbCat['id_category'];
    $categoryName = $categoryMap[$categoryId] ?? 'other';
    $associationsByCategory[$categoryName] = $associationController->getAssociationsByCategory($categoryName);
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
    <link rel="stylesheet" href="../styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="job.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
          
<div class="dashboard-container">

<aside class="sidebar">

    <div class="sidebar-bg-blur sidebar-bg-blur-1"></div>
    <div class="sidebar-bg-blur sidebar-bg-blur-2"></div>

  
    <div class="logo-container">
        <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
        </svg>
        <span class="logo-text">Lumina</span>
    </div>

 
<nav class="sidebar-nav">
<!-- SIMPLE DASHBOARD BUTTON -->
<button class="nav-item" data-tab="dashboard"  onclick="window.location.href='../index.php'">

<div class="nav-gradient-overlay"></div>
<div class="nav-icon-wrapper">
<i class="fas fa-home nav-icon"></i>
</div>
<span class="nav-label">Dashboard</span>
<div class="nav-star">
<svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
</svg>
</div>
<div class="hover-trail"></div>
</button>

        <div class="nav-group">
            <button class="nav-item" data-tab="events" data-has-submenu="true"  onclick="window.location.href='../index.php'">
                <div class="particle-container">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <div class="nav-gradient-overlay"></div>
                
                <!-- Icon -->
                <div class="nav-icon-wrapper">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                
                <span class="nav-label">Events</span>

                <div class="nav-star">
                    <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="hover-trail"></div>
                
                <svg class="dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>

            <div class="submenu expanded">
                <button class="nav-item nav-sub-item" data-tab="participants"  onclick="window.location.href='../index.php'">
                    <div class="nav-gradient-overlay"></div>
                    <div class="nav-icon-wrapper">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <span class="nav-label">Participants</span>
                    <div class="nav-star">
                        <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="hover-trail"></div>
                </button>

                <button class="nav-item nav-sub-item" data-tab="sponsors"  onclick="window.location.href='../index.php'">
                    <div class="nav-gradient-overlay"></div>
                    <div class="nav-icon-wrapper">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </div>
                    <span class="nav-label">Sponsors</span>
                    <div class="nav-star">
                        <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="hover-trail"></div>
                </button>
            </div>
        </div>

        <!-- Admin Tasks -->
        <button class="nav-item" data-tab="tasks"  onclick="window.location.href='../index.php'">
            <div class="nav-gradient-overlay"></div>
            <div class="nav-icon-wrapper">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <span class="nav-label">Admin Tasks</span>
            <div class="nav-star">
                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="hover-trail"></div>
        </button>

        <button class="nav-item" data-tab="social-case"onclick="window.location.href='../Social/social_case.php'">
        <div class="nav-gradient-overlay"></div>
        <div class="nav-icon-wrapper">
        <i class="fas fa-users nav-icon"></i>
        </div>
        <span class="nav-label">Social Case</span>
        <div class="nav-star">
        <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        </div>
        <div class="hover-trail"></div>
<       </button>


     <!-- Associations -->
     <button class="nav-item" data-tab="tasks" onclick="window.location.href='../Social/associations.php'">
            <div class="nav-gradient-overlay"></div>
            <div class="nav-icon-wrapper">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <span class="nav-label">Associations</span>
            <div class="nav-star">
                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="hover-trail"></div>
        </button>


<button class="nav-item " data-tab="job" onclick="window.location.href='index.php'">
<div class="nav-gradient-overlay"></div>
<div class="nav-icon-wrapper">
<i class="fas fa-briefcase nav-icon"></i>
</div>
<span class="nav-label">Jobs</span>
<div class="nav-star">
<svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
</svg>
</div>
<div class="hover-trail"></div>
</button>
<button class="nav-item" data-tab="forum"  onclick="window.location.href='../../../index.php'">
<div class="nav-gradient-overlay"></div>
<div class="nav-icon-wrapper">
<i class="fas fa-comments nav-icon"></i>
</div>
<span class="nav-label">Forum</span>
<div class="nav-star">
<svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
</svg>
</div>
<div class="hover-trail"></div>
</button>


        <!-- Settings -->
        <button class="nav-item" data-tab="settings"  onclick="window.location.href='../index.php'">
            <div class="nav-gradient-overlay"></div>
            <div class="nav-icon-wrapper">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6m0 6v6m9-9h-6m-6 0H3m15.364 6.364l-4.243-4.243m-6.364 0L3.636 17.364m12.728 0l-4.243-4.243m-6.364 0L3.636 6.636"></path>
                </svg>
            </div>
            <span class="nav-label">Settings</span>
            <div class="nav-star">
                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="hover-trail"></div>
        </button>
    </nav>

    <!-- Help Section -->
    <div class="help-section">
        <div class="help-bg-glow"></div>
        
        <div class="help-star-container">
            <svg class="help-star" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            <div class="pulse-ring pulse-ring-1"></div>
            <div class="pulse-ring pulse-ring-2"></div>
        </div>
        
        <h4 class="help-title">Need Help?</h4>
        <p class="help-text">Check our documentation or contact support</p>
        
        <!-- Floating mini stars -->
        <svg class="mini-star mini-star-1" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        <svg class="mini-star mini-star-2" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        <svg class="mini-star mini-star-3" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
    </div>

    <!-- Bottom decorative bar -->
    <div class="sidebar-bottom-bar"></div>
</aside>
            <!-- Inclure bjob.php (section jobs principale) -->
            <?php include 'bjob.php'; ?>
        </main>
    </div>

    <!-- Scripts JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../front/assets/js/map-utils.js"></script>
    <script src="script.js"></script>
    <script src="scriptjob.js"></script>
</body>
</html>
