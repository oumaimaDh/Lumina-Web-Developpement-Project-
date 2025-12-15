<?php
require_once "../../../../config/db.php";
require_once "../../../../model/ParticipantModel.php";

$db = new Database();
$pdo = $db->connect();
$model = new ParticipantModel($pdo);

// PARTICIPANTS (keep same)
$participants = $model->getParticipants();


// ⭐ ADD THIS — EVENTS CONNECTION
require_once "../../../../model/EventModel.php";
$eventModel = new EventModel($pdo);
$events = $eventModel->getAllEvents();

// EDIT SPONSOR (keep same)
if (isset($_GET['edit_sponsor'])) {
    $id = $_GET['edit_sponsor'];

    require_once "../../../../config/db.php";
    $database = new Database();
    $pdo = $database->connect();

    $stmt = $pdo->prepare("SELECT * FROM sponsors WHERE id = ?");
    $stmt->execute([$id]);
    $editSponsor = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<script>window.onload = function(){ openModal('addSponsorModal'); }</script>";

// Handle job CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CREATE - Create new job offer
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
            header('Location: index.php');
            exit;
        }
    }
    
    // UPDATE - Update job offer
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
            header('Location: index.php');
            exit;
        }
    }
}

// Handle job deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete_offer' && isset($_GET['id'])) {
    if ($offerController->deleteOffer($_GET['id'])) {
        header('Location: index.php');
        exit;
    }
}

// Get category data for jobs
$dbCategories = [];
$categoryMap = [
    5 => 'health',
    6 => 'education',
    7 => 'commerce',
    8 => 'construction',
    9 => 'restaurant'
];

// Try to fetch categories from database
try {
    $sql = "SELECT id_category, name FROM category WHERE id_category IN (5, 6, 7, 8, 9) ORDER BY id_category";
    $query = $pdo->prepare($sql);
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

// Get associations by category
$associationsByCategory = [];
foreach ($dbCategories as $dbCat) {
    $categoryId = $dbCat['id_category'];
    $categoryName = $categoryMap[$categoryId] ?? 'other';
    $associationsByCategory[$categoryName] = $associationController->getAssociationsByCategory($categoryName);
}

// Variable for job editing
$editingOffer = null;
if (isset($_GET['edit_id'])) {
    $editingOffer = $offerController->getOfferById($_GET['edit_id']);
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina - Event Management Dashboard V23</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="theme.css"> 
     <!-- Add this with other CSS links -->
<link rel="stylesheet" href="views/backoffice/jobs/job.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
<button class="nav-item" data-tab="dashboard"  onclick="window.location.href='index.php'">

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
        <button class="nav-item" data-tab="events" data-has-submenu="true">
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
            <button class="nav-item nav-sub-item" data-tab="participants"  >
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

            <button class="nav-item nav-sub-item" data-tab="sponsors" >
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
    <button class="nav-item" data-tab="tasks" >
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

    <button class="nav-item" data-tab="social-case"onclick="window.location.href='Social/social_case.php'">
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
 <button class="nav-item" data-tab="tasks" onclick="window.location.href='Social/associations.php'">
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


<button class="nav-item " data-tab="job" onclick="window.location.href='Job/index.php'">
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
<button class="nav-item" data-tab="forum"  onclick="window.location.href='../../index.php'">
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
    <button class="nav-item" data-tab="settings" >
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
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header with Logo -->
            <header class="dashboard-header">
                <!-- Logo -->
                <div class="header-logo">
                    <img src="../assets/logo.png" 
                         alt="Lumina Logo" 
                         class="logo-image">
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search events, participants..." class="search-input" id="globalSearch">
                </div>

                <!-- Date -->
                <div class="header-date">
                    <p id="currentDate"></p>
                </div>

                <!-- Actions -->
                <div class="header-actions">
                    <!-- Notifications -->
                    <button class="icon-btn" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot" id="notificationDot"></span>
                    </button>

                    <!-- User Profile -->
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
<div id="dashboard-content" class="tab-content">
       <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="analytics-header">
            <div class="header-content">
                <h1><i class="fas fa-chart-line"></i> Analytics Dashboard</h1>
            </div>
            <div class="header-actions">
                <select class="period-select">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option selected>Last 3 Months</option>
                    <option>Last Year</option>
                </select>
            </div>
        </div>

        <!-- Quote Cards Section - Three cute card stacks -->
        <div class="quote-cards-container">
            <!-- First Quote Stack -->
            <div class="card-stack-container">
                <div class="card-stack opened">
                    <div class="quote-card">
                        <p class="quote-text"></p>
                    </div>
                    <div class="quote-card">
                        <p class="quote-text">"3 events created 2 hours ago"</p>
                    </div>
                   
                </div>
                <nav class="nav-circle opened">
                    <button onclick="toggleStack(this)">
                        <i class="fa-regular fa-clone"></i>
                    </button>
                </nav>
            </div>
            
            
            <!-- Second Quote Stack -->
            <div class="card-stack-container">
                <div class="card-stack opened">
                    <div class="quote-card">
                        <p class="quote-text"></p>
                    </div>
                    <div class="quote-card">
                        <p class="quote-text">"5 user verifications awaiting approval"</p>
                    </div>
                </div>
                <nav class="nav-circle opened">
                    <button onclick="toggleStack(this)">
                        <i class="fa-regular fa-clone"></i>
                    </button>
                </nav>
            </div>
            
            <!-- Third Quote Stack -->
            <div class="card-stack-container">
                <div class="card-stack opened">
                    <div class="quote-card">
                        <p class="quote-text"></p>
                    </div>
                    <div class="quote-card">
                        <p class="quote-text">"3 new association requests                        </p>
                    </div>
                </div>
                <nav class="nav-circle opened">
                    <button onclick="toggleStack(this)">
                        <i class="fa-regular fa-clone"></i>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Compact Stats Row -->
        <div class="compact-stats">
            <div class="stat-item">
                <div class="stat-main">
                    <span class="stat-value">1,247</span>
                    <span class="stat-label">Total Users</span>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i> 12%
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-main">
                    <span class="stat-value">89</span>
                    <span class="stat-label">Associations</span>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i> 5%
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-main">
                    <span class="stat-value">23</span>
                    <span class="stat-label">Pending</span>
                </div>
                <div class="stat-trend negative">
                    <i class="fas fa-arrow-down"></i> 8%
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-main">
                    <span class="stat-value">68%</span>
                    <span class="stat-label">Engagement</span>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i> 3%
                </div>
            </div>
        </div>

        <!-- Rest of your existing content remains the same -->
        <!-- Main Charts Grid -->
        <div class="charts-grid">
            <!-- Row 1 -->
            <div class="chart-wrapper">
                <div class="chart-header">
                    <h3>User Growth</h3>
                    <div class="chart-controls">
                        <button class="control-btn"><i class="fas fa-expand"></i></button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <div class="chart-wrapper">
                <div class="chart-header">
                    <h3>User Distribution</h3>
                    <div class="chart-controls">
                        <button class="control-btn"><i class="fas fa-download"></i></button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="chart-wrapper">
                <div class="chart-header">
                    <h3>Platform Activity</h3>
                    <div class="chart-controls">
                        <button class="control-btn"><i class="fas fa-refresh"></i></button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <div class="chart-wrapper">
                <div class="chart-header">
                    <h3>Regional Data</h3>
                    <div class="chart-controls">
                        <button class="control-btn"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="regionalChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Insights -->
        <div class="insights-section">
            <h3><i class="fas fa-lightbulb"></i> Quick Insights</h3>
            <div class="insights-grid">
                <div class="insight-item">
                    <i class="fas fa-user-check insight-icon"></i>
                    <div class="insight-content">
                        <h4>User Engagement Up</h4>
                        <p>15% increase in volunteer signups this month</p>
                    </div>
                </div>
                
                <div class="insight-item">
                    <i class="fas fa-clock insight-icon"></i>
                    <div class="insight-content">
                        <h4>Response Time</h4>
                        <p>Average request response: 2.3 hours</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Users Section -->
    <div class="main-content">
        <h1><i class="fas fa-users"></i> Manage Users</h1>

        <!-- Search & Filter Bar (NO ROLE NEEDED) -->
<div class="search-container">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Search by username or email..." autocomplete="off">
    </div>

    <!-- ADDED: Filter dropdown -->
    <select class="filter-dropdown" id="filterDropdown">
        <option value="all"><i class="fas fa-users"></i> All Users</option>
        <option value="active"><i class="fas fa-check-circle"></i> Active Only</option>
        <option value="recent"><i class="fas fa-clock"></i> Recently Added</option>
        <option value="verified"><i class="fas fa-shield-check"></i> Verified</option>
    </select>

    <button class="btn btn-primary" onclick="loadUsers()">
        <i class="fas fa-refresh"></i> Refresh
    </button>

    <button class="btn btn-secondary" onclick="clearSearch()" id="clearBtn" style="display:none;">
        <i class="fas fa-times"></i> Clear
    </button>
</div>

        <!-- Users Table -->
        <div class="table-container">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Les users seront chargés ici par JavaScript -->
                    <tr>
                        <td colspan="5" style="text-align: center;">
                            <i class="fas fa-spinner fa-spin"></i> Loading users...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>  
  <!-- Add scripts -->
    <script>
        // Function to toggle card stack
        function toggleStack(button) {
            const navCircle = button.parentElement;
            const cardStack = navCircle.previousElementSibling;
            
            navCircle.classList.toggle('opened');
            cardStack.classList.toggle('opened');
            
            // Change icon based on state
            const icon = button.querySelector('i');
            if (navCircle.classList.contains('opened')) {
                icon.className = 'fa-regular fa-clone';
            } else {
                icon.className = 'fa-regular fa-square';
            }
        }
    </script>   
            <!-- Events Tab -->
            <div id="events-content" class="tab-content">
                <div class="section-header">
                    <h2>Events Management</h2>
                    <div class="header-actions-group">
                       <select id="eventFilter" class="filter-select" onchange="filterEvents()">
    <option value="all">All Events</option>
    <option value="upcoming">Upcoming</option>
    <option value="in-progress">In Progress</option>
    <option value="completed">Completed</option>
</select>

                        <button class="btn-primary" onclick="showModal('createEventModal')">
                            <i class="fas fa-plus"></i>
                            Create Event
                        </button>
                    </div>
                </div>
                <div id="eventsGrid" class="events-grid"></div>
            </div>

           <div id="participants-content" class="tab-content">

<div class="welcome-card">
   <h2>Participant Management</h2>
</div>
<h2 class="section-title">Participant Statistics</h2>

<div class="stats-grid">

    <div class="stat-card animate-stat">
        <div class="stat-icon users"></div>
        <div class="stat-info">
            <h3>Total Participants</h3>
            <p id="statTotal" class="stat-value">0</p>
        </div>
    </div>

    <div class="stat-card animate-stat">
        <div class="stat-icon chart"></div>
        <div class="stat-info">
            <h3>Registered Today</h3>
            <p id="statToday" class="stat-value">0</p>
        </div>
    </div>

</div>

    <?php
    // Group by eventTitle
    $groups = [];
    foreach ($participants as $p) {
        $groups[$p["event_title"]][] = $p;
    }

    foreach ($groups as $event => $list):
    ?>
        <h3 style="margin-top:20px;"><?= $event ?></h3>
        <div class="widget-card">

            <?php foreach ($list as $p): ?>
                <div class="participant-item" 
                     style="padding:1rem;display:flex;justify-content:space-between;align-items:center;">

                    <div>
                        <strong><?= $p["firstName"] . " " . $p["lastName"] ?></strong><br>
                        <?= $p["email"] ?><br>
                        <?= $p["phone"] ?><br>
                        <small><?= $p["created_at"] ?></small>
                    </div>

                    <div style="display:flex;gap:10px;">
                        <form action="../../../../controller/deleteParticipant.php" method="POST">

                            <input type="hidden" name="id" value="<?= $p["id"] ?>">
                            <button class="btn-secondary" type="submit">Delete</button>
                        </form>

                        <form action="../../../../controller/updateParticipant.php" method="POST">
                            <input type="hidden" name="id" value="<?= $p["id"] ?>">

                             <button type="button" class="btn-primary"
          onclick="openEditModal(
                '<?= $p['id'] ?>',
                '<?= htmlspecialchars($p['firstName'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($p['lastName'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($p['email'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($p['phone'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($p['event_id'], ENT_QUOTES) ?>'
            )">
            Edit
        </button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>
</div>
<?php
if (isset($_GET['edit'])) {

    $editId = $_GET['edit'];

    // Load the participant data directly
    $stmt = $pdo->prepare("SELECT * FROM participants WHERE id = ?");
    $stmt->execute([$editId]);
    $participant = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($participant):
?>
    <div class="edit-box">
        <h2>Edit Participant</h2>

        <form action="../controller/updateParticipant.php" method="POST">

            <input type="hidden" name="id" value="<?= $participant['id'] ?>">

            <label>First Name:</label>
            <input type="text" name="firstName" value="<?= $participant['firstName'] ?>">

            <label>Last Name:</label>
            <input type="text" name="lastName" value="<?= $participant['lastName'] ?>">

            <label>Email:</label>
            <input type="email" name="email" value="<?= $participant['email'] ?>">

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= $participant['phone'] ?>">

            <label>Event Title:</label>
            <input type="text" name="event_id" value="<?= $participant['event_id'] ?>">

            <button type="submit" class="btn-primary">Save</button>
            <a href="index.php" class="btn-secondary">Cancel</a>

        </form>
    </div>

<?php
    endif;
}
?>
<!-- ========== PARTICIPANTS TAB (UPDATED WITH PHP + MODAL EDIT) ========== -->
            <div id="participants-content" class="tab-content">
                <div class="section-header">
                    <h2>Participant Management</h2>
                    <div class="header-actions-group">
                        <input type="text"
                               id="participantSearch"
                               class="search-input-small"
                               placeholder="Search participants..."
                               oninput="searchParticipants()">
                        <button class="btn-secondary" onclick="exportParticipants()">
                            <i class="fas fa-download"></i>
                            Export List
                        </button>
                        <button class="btn-primary" onclick="filterParticipants()">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                    </div>
                </div>

                <div id="participantsContainer">
                    <?php if (empty($groupedParticipants)): ?>
                        <p class="empty-state">No participants registered yet.</p>
                    <?php else: ?>
                        <?php foreach ($groupedParticipants as $eventTitle => $list): ?>
                            <div class="event-participant-card">
                                <div class="event-participant-header">
                                    <h3 class="event-title">
                                        <?= htmlspecialchars($eventTitle, ENT_QUOTES, 'UTF-8') ?>
                                    </h3>
                                    <span class="badge">
                                        <?= count($list) ?> participant(s)
                                    </span>
                                </div>

                                <div class="table-wrapper">
                                    <table class="participants-table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Registered At</th>
                                            <th style="text-align:right;">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($list as $p): ?>
                                            <tr id="participant-row-<?= $p['id'] ?>">

                                                <td>
                                                    <?= htmlspecialchars($p['firstName'] . ' ' . $p['lastName'], ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td><?= htmlspecialchars($p['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($p['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($p['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td>
                                                    <div class="table-actions">
                                                        <!-- EDIT BUTTON opens MODAL -->
                                                    <button class="edit-btn"
                                                        onclick="openEditParticipantModal(
                                                            '<?= $row['id'] ?>',
                                                            '<?= $row['firstName'] ?>',
                                                            '<?= $row['lastName'] ?>',
                                                            '<?= $row['email'] ?>',
                                                            '<?= $row['phone'] ?>',
                                                                '<?= $row['event_id'] ?>'
                                                                    )">
                                                                                        Edit
                                                                                                                </button>


                                                        <!-- DELETE BUTTON posts to PHP -->
                                                        <form action="../../../../controller/deleteParticipant.php"
                                                              method="POST"
                                                              style="display:inline-block"
                                                              onsubmit="return confirm('Delete this participant?');">
                                                            <input type="hidden" name="id"
                                                                   value="<?= $p['id'] ?>">
                                                           <button class="btn-danger" onclick="deleteParticipant(<?= $p['id'] ?>)">Delete</button>

                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

<div id="editParticipantModal" class="modal">
    <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header">
            <h2><i class="fas fa-user-edit"></i> Edit Participant</h2>
            <button class="close-modal" onclick="closeEditParticipantModal()">&times;</button>
        </div>
    
    <!-- FORM -->
        <form id="editParticipantForm" class="modal-form" onsubmit="return false;">


            <input type="hidden" id="edit_id">

            <!-- NAME ROW -->
            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" id="edit_firstName" name="firstName" required>
                </div>

                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" id="edit_lastName" name="lastName" required>
                </div>
            </div>

            <!-- CONTACT ROW -->
            <div class="form-row">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Phone *</label>
                    <input type="text" id="edit_phone" name="phone" required>
                </div>
            </div>

            <!-- EVENT TITLE -->
            <div class="form-group">
                <label>Event Title *</label>
                <input type="text" id="edit_event_id" name="event_id" required>
            </div>

            <!-- ACTIONS -->
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeEditParticipantModal()">Cancel</button>
                <button type="button" class="btn-primary" onclick="saveParticipantChanges()">
    Save Changes
</button>

            </div>

        </form>
    </div>
</div>


            <!-- Sponsors Tab -->
            <div id="sponsors-content" class="tab-content">
                <div class="section-header">
                    <h2>Sponsor Management</h2>
                    <div class="header-actions-group">
                        <select id="sponsorTypeFilter" class="filter-select" onchange="filterSponsors()">
    <option value="all">All Types</option>
    <option value="financial">Financial</option>
    <option value="media">Media</option>
    <option value="equipment">Equipment</option>
    <option value="other">Other</option>
</select>

                        <button class="btn-primary" onclick="showModal('addSponsorModal')">
                            <i class="fas fa-plus"></i>
                            Add Sponsor
                        </button>
                    </div>
                </div>
                <div id="sponsorsGrid" class="sponsors-grid">
                    <!-- Sponsors will be populated by JavaScript -->
                </div>
            </div>

            <!-- Tasks Tab -->
            <div id="tasks-content" class="tab-content">
                <div class="section-header">
                    <h2>Admin Tasks Management</h2>
                    <div class="header-actions-group">
                        <input type="text" id="taskSearch" class="search-input-small" placeholder="Search tasks..." oninput="searchTasks()">
                        <select id="taskPriorityFilter" class="filter-select" onchange="filterTasks()">
                            <option value="all">All Priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                        <button class="btn-primary" onclick="showModal('createTaskModal')">
                            <i class="fas fa-plus"></i>
                            Create Task
                        </button>
                    </div>
                </div>
                
                <!-- Task Stats -->
                <div class="task-stats-grid">
                    <div class="task-stat-card todo">
                        <p>To Do List</p>
                        <h3 id="todoCount">0</h3>
                    </div>
                    <div class="task-stat-card in-progress">
                        <p>In Progress</p>
                        <h3 id="inProgressCount">0</h3>
                    </div>
                    <div class="task-stat-card in-review">
                        <p>In Review</p>
                        <h3 id="inReviewCount">0</h3>
                    </div>
                    <div class="task-stat-card done">
                        <p>Done</p>
                        <h3 id="doneCount">0</h3>
                    </div>
                </div>

                <!-- Kanban Board -->
                <div class="kanban-board">
                    <div class="kanban-column">
                        <div class="column-header todo">
                            <h3>To Do List</h3>
                            <span class="task-count" id="todoColumnCount">0</span>
                        </div>
                        <div class="tasks-container" id="todoTasks"></div>
                    </div>
                    <div class="kanban-column">
                        <div class="column-header in-progress">
                            <h3>In Progress</h3>
                            <span class="task-count" id="inProgressColumnCount">0</span>
                        </div>
                        <div class="tasks-container" id="inProgressTasks"></div>
                    </div>
                    <div class="kanban-column">
                        <div class="column-header in-review">
                            <h3>In Review</h3>
                            <span class="task-count" id="inReviewColumnCount">0</span>
                        </div>
                        <div class="tasks-container" id="inReviewTasks"></div>
                    </div>
                    <div class="kanban-column">
                        <div class="column-header done">
                            <h3>Done</h3>
                            <span class="task-count" id="doneColumnCount">0</span>
                        </div>
                        <div class="tasks-container" id="doneTasks"></div>
                    </div>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div id="analytics-content" class="tab-content">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Analytics & Insights</h3>
                </div>
                <div id="analyticsContent">
                    <!-- Analytics will be populated by JavaScript -->
                </div>
            </div>
<!-- ================= ADMIN DASHBOARD SETTINGS ================= -->
<!-- ================= ADMIN DASHBOARD SETTINGS ================= -->
<div id="settings-content" class="tab-content">

    <!-- HEADER -->
    <div class="section-header">
        <h2><i class="fas fa-sliders-h"></i> Dashboard Settings</h2>
        <p class="muted-text">
            Control how the admin dashboard behaves and displays data
        </p>
    </div>

    <!-- ================= ADMIN ACCOUNT ================= -->
    <div class="settings-section">
        <h3><i class="fas fa-user-shield"></i> Admin Account</h3>

        <div class="settings-card">
            <form id="adminAccountForm" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Admin Name</label>
                        <input type="text" id="adminName" name="name" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="adminEmail" name="email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Change Password</label>
                    <input type="password" id="adminPassword" name="password" placeholder="Enter new password">
                    <small class="form-text text-muted">Leave blank to keep current password</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= DASHBOARD DISPLAY ================= -->
    <div class="settings-section">
        <h3><i class="fas fa-th-large"></i> Dashboard Display</h3>

        <div class="settings-grid">
            <div class="settings-card">
                <h4><i class="fas fa-home"></i> Default Landing Page</h4>
                <p class="muted-text">Page shown after login</p>
                <select class="filter-select" id="defaultLandingPage">
                    <option value="dashboard">Dashboard</option>
                    <option value="events">Events</option>
                    <option value="participants">Participants</option>
                    <option value="sponsors">Sponsors</option>
                    <option value="tasks">Tasks</option>
                    <option value="settings">Settings</option>
                </select>
            </div>

            <div class="settings-card">
                <h4><i class="fas fa-chart-bar"></i> Visible Widgets</h4>
                <label><input type="checkbox" class="widget-checkbox" value="analytics"> Analytics Overview</label><br>
                <label><input type="checkbox" class="widget-checkbox" value="pending"> Pending Requests</label><br>
                <label><input type="checkbox" class="widget-checkbox" value="recent"> Recent Activity</label><br>
                <label><input type="checkbox" class="widget-checkbox" value="stats"> Statistics</label>
            </div>

            <div class="settings-card">
                <h4><i class="fas fa-compress"></i> Compact Mode</h4>
                <p class="muted-text">Reduce spacing for dense data</p>
                <div class="toggle-container">
                    <label class="switch">
                        <input type="checkbox" id="compactModeToggle">
                        <span class="slider"></span>
                    </label>
                    <span id="compactModeStatus">Disabled</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= DATA MANAGEMENT ================= -->
    <div class="settings-section">
        <h3><i class="fas fa-database"></i> Data Management</h3>

        <div class="settings-grid">
            <div class="settings-card">
                <h4><i class="fas fa-users"></i> Participants</h4>
                <p class="muted-text">Registration handling</p>
                <select class="filter-select" id="participantRegistration">
                    <option value="manual">Manual Approval</option>
                    <option value="auto">Auto Approve</option>
                </select>
            </div>

            <div class="settings-card">
                <h4><i class="fas fa-calendar-check"></i> Events</h4>
                <p class="muted-text">Archive completed events</p>
                <select class="filter-select" id="eventArchiveDays">
                    <option value="0">Never</option>
                    <option value="7">After 7 days</option>
                    <option value="30">After 30 days</option>
                    <option value="90">After 90 days</option>
                </select>
            </div>

            <div class="settings-card">
                <h4><i class="fas fa-handshake"></i> Sponsors</h4>
                <p class="muted-text">Validation workflow</p>
                <select class="filter-select" id="sponsorValidation">
                    <option value="admin">Admin Approval</option>
                    <option value="auto">Auto Approve</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ================= NOTIFICATIONS ================= -->
    <div class="settings-section">
        <h3><i class="fas fa-bell"></i> Notifications</h3>

        <div class="settings-card">
            <label><input type="checkbox" class="notification-checkbox" value="new_participant"> New participant registered</label><br>
            <label><input type="checkbox" class="notification-checkbox" value="sponsor_request"> Sponsor request received</label><br>
            <label><input type="checkbox" class="notification-checkbox" value="task_update"> Task status updated</label><br>
            <label><input type="checkbox" class="notification-checkbox" value="event_deadline"> Event deadline reminder</label><br>
            <label><input type="checkbox" class="notification-checkbox" value="payment_received"> Payment received</label>
        </div>
    </div>

    <!-- ================= ACCESS CONTROL ================= -->
    <div class="settings-section">
        <h3><i class="fas fa-user-cog"></i> Admin Permissions</h3>

        <div class="settings-card">
            <ul class="settings-list">
                <li>
                    <span><i class="fas fa-calendar"></i> Manage Events</span>
                    <label class="switch">
                        <input type="checkbox" id="permissionEvents">
                        <span class="slider"></span>
                    </label>
                </li>
                <li>
                    <span><i class="fas fa-users"></i> Manage Participants</span>
                    <label class="switch">
                        <input type="checkbox" id="permissionParticipants">
                        <span class="slider"></span>
                    </label>
                </li>
                <li>
                    <span><i class="fas fa-handshake"></i> Manage Sponsors</span>
                    <label class="switch">
                        <input type="checkbox" id="permissionSponsors">
                        <span class="slider"></span>
                    </label>
                </li>
                <li>
                    <span><i class="fas fa-tasks"></i> Manage Tasks</span>
                    <label class="switch">
                        <input type="checkbox" id="permissionTasks">
                        <span class="slider"></span>
                    </label>
                </li>
                <li>
                    <span><i class="fas fa-chart-bar"></i> View Analytics</span>
                    <label class="switch">
                        <input type="checkbox" id="permissionAnalytics">
                        <span class="slider"></span>
                    </label>
                </li>
            </ul>
        </div>
    </div>
<!-- Theme Selector -->
<div class="settings-section">
    <h3><i class="fas fa-palette"></i> Theme & Appearance</h3>
    <p class="muted-text">Change the visual appearance of your dashboard</p>

    <div class="theme-selector">
        <!-- Default Theme -->
        <div class="theme-option" data-theme="default">
            <div class="theme-preview">
                <div class="theme-image" style="background: linear-gradient(135deg, #3B82F6, #10B981, #8B5CF6);"></div>
                <div class="theme-overlay">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="theme-info">
                <h4>Default</h4>
                <p class="theme-description">Clean light theme with blue accents</p>
                <div class="theme-colors">
                    <span class="color-dot" style="background: #3B82F6;"></span>
                    <span class="color-dot" style="background: #10B981;"></span>
                    <span class="color-dot" style="background: #8B5CF6;"></span>
                </div>
            </div>
            <button class="theme-select-btn btn-secondary" onclick="selectTheme('default')">
                Select
            </button>
        </div>

        <!-- Dark Theme -->
        <div class="theme-option" data-theme="dark">
            <div class="theme-preview">
                <div class="theme-image" style="background: linear-gradient(135deg, #1F2937, #374151, #4B5563);"></div>
                <div class="theme-overlay">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="theme-info">
                <h4>Dark Mode</h4>
                <p class="theme-description">Easy on the eyes for long sessions</p>
                <div class="theme-colors">
                    <span class="color-dot" style="background: #1F2937;"></span>
                    <span class="color-dot" style="background: #374151;"></span>
                    <span class="color-dot" style="background: #4B5563;"></span>
                </div>
            </div>
            <button class="theme-select-btn btn-secondary" onclick="selectTheme('dark')">
                Select
            </button>
        </div>

        <!-- Modern Theme -->
        <div class="theme-option" data-theme="modern">
            <div class="theme-preview">
                <div class="theme-image" style="background: linear-gradient(135deg, #7C3AED, #EC4899, #F59E0B);"></div>
                <div class="theme-overlay">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="theme-info">
                <h4>Modern</h4>
                <p class="theme-description">Vibrant colors with rounded elements</p>
                <div class="theme-colors">
                    <span class="color-dot" style="background: #7C3AED;"></span>
                    <span class="color-dot" style="background: #EC4899;"></span>
                    <span class="color-dot" style="background: #F59E0B;"></span>
                </div>
            </div>
            <button class="theme-select-btn btn-secondary" onclick="selectTheme('modern')">
                Select
            </button>
        </div>
    </div>

    <!-- Accent Color Picker -->
    <div class="accent-color-picker">
        <h4><i class="fas fa-fill-drip"></i> Accent Color</h4>
        <p class="muted-text">Choose your primary accent color</p>
        <div class="color-options">
            <button class="color-option" data-color="#3B82F6" style="background: #3B82F6;" onclick="selectAccentColor('#3B82F6')">
                <i class="fas fa-check"></i>
            </button>
            <button class="color-option" data-color="#10B981" style="background: #10B981;" onclick="selectAccentColor('#10B981')">
                <i class="fas fa-check"></i>
            </button>
            <button class="color-option" data-color="#8B5CF6" style="background: #8B5CF6;" onclick="selectAccentColor('#8B5CF6')">
                <i class="fas fa-check"></i>
            </button>
            <button class="color-option" data-color="#EC4899" style="background: #EC4899;" onclick="selectAccentColor('#EC4899')">
                <i class="fas fa-check"></i>
            </button>
            <button class="color-option" data-color="#F59E0B" style="background: #F59E0B;" onclick="selectAccentColor('#F59E0B')">
                <i class="fas fa-check"></i>
            </button>
            <button class="color-option" data-color="#EF4444" style="background: #EF4444;" onclick="selectAccentColor('#EF4444')">
                <i class="fas fa-check"></i>
            </button>
        </div>
        <div class="custom-color">
            <label>Custom Color:</label>
            <input type="color" id="customAccentColor" value="#3B82F6" onchange="selectAccentColor(this.value)">
            <span id="customColorValue">#3B82F6</span>
        </div>
    </div>
</div>
    <!-- ================= SYSTEM ================= -->
    <div class="settings-section">
        <h3><i class="fas fa-cogs"></i> System Tools</h3>

        <div class="settings-grid">
            <div class="settings-card">
                <h4><i class="fas fa-sync-alt"></i> Refresh Dashboard</h4>
                <p class="muted-text">Clear cached data</p>
                <button class="btn-secondary" onclick="clearDashboardCache()">
                    <i class="fas fa-broom"></i> Clear Cache
                </button>
            </div>

            <div class="settings-card">
                <h4><i class="fas fa-file-export"></i> Export Reports</h4>
                <p class="muted-text">CSV / Excel formats</p>
                <button class="btn-secondary" onclick="exportReports()">
                    <i class="fas fa-download"></i> Export Data
                </button>
            </div>

            <div class="settings-card">
                <h4><i class="fas fa-database"></i> Backup Database</h4>
                <p class="muted-text">Create system backup</p>
                <button class="btn-secondary" onclick="backupDatabase()">
                    <i class="fas fa-save"></i> Create Backup
                </button>
            </div>
        </div>
    </div>

    <!-- Save All Settings Button -->
    <div class="settings-actions">
        <button class="btn-primary" onclick="saveAllSettings()">
            <i class="fas fa-save"></i> Save All Settings
        </button>
        <button class="btn-secondary" onclick="resetToDefaults()">
            <i class="fas fa-undo"></i> Reset to Defaults
        </button>
    </div>
</div>


    <!-- Modals -->
    <!-- Create Event Modal -->
    <div id="createEventModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-plus"></i> Create New Event</h3>
                <button class="close-modal" onclick="closeModal('createEventModal')">&times;</button>
            </div>
           <form id="createEventForm" class="modal-form" action="../../../../controller/Event/eventstore.php" method="POST">
                <div class="form-group">
                    <label>Event Title *</label>
                    <input type="text" name="title" required placeholder="e.g., Annual Tech Conference">
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" required rows="3" placeholder="Provide event details..."></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Event Date *</label>
                        <input type="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label>Registration Deadline *</label>
                        <input type="date" name="deadline" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Location *</label>
                        <input type="text" name="location" required placeholder="Event venue">
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" required>
                            <option value="upcoming">Upcoming</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="Culture">Culture</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Food & Beverage">Food & Beverage</option>
                        <option value="Education">Education</option>
                        <option value="Art">Art</option>
                        <option value="Technology">Technology</option>
                        <option value="General">General</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('createEventModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Save Event</button>
                </div>
                                        </form>
                                            
        </div>
    <div id="events-content" class="tab-content">

    <div class="section-header">
        <h2>Events</h2>
        <button class="btn-primary" onclick="showModal('createEventModal')">
            + Create Event
        </button>
    </div>

    <!-- EVENT CARDS FROM DATABASE -->
    <div class="event-grid">
        <?php foreach ($events as $e): ?>
            <div class="event-card">

                <h3><?= $e["title"] ?></h3>
                <p><?= $e["description"] ?></p>
                <p>📅 <?= $e["date"] ?></p>
                <p>📍 <?= $e["location"] ?></p>
                <span class="badge"><?= $e["status"] ?></span>
                <span class="category"><?= $e["category"] ?></span>

                <div class="event-actions">
      <button class="btn-secondary" onclick="openEditEventModal(<?= $e['id'] ?>)">


    <i class="fas fa-edit"></i> Edit
</button>


                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>

</div>
</div>
<!-- EDIT EVENT MODAL -->
<div id="editEventModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Event</h3>
            <button class="close-modal" onclick="closeModal('editEventModal')">&times;</button>
        </div>
        
        <form id="editEventForm" class="modal-form">
            <!-- ⚠️ CRITICAL: Must have name="id" ⚠️ -->
            <input type="hidden" id="edit_event_id" name="event_id" value="">
            
            <div class="form-group">
                <label>Event Title *</label>
                <input type="text" id="edit_event_title" name="title" required>
            </div>
            
            <div class="form-group">
                <label>Description *</label>
                <textarea id="edit_event_description" name="description" rows="3" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Event Date *</label>
                    <input type="date" id="edit_event_date" name="date" required>
                </div>
                <div class="form-group">
                    <label>Registration Deadline *</label>
                    <input type="date" id="edit_event_deadline" name="deadline" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" id="edit_event_location" name="location" required>
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select id="edit_event_status" name="status" required>
                        <option value="upcoming">Upcoming</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Category *</label>
                <select id="edit_event_category" name="category" required>
                    <option value="Culture">Culture</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Food & Beverage">Food & Beverage</option>
                    <option value="Education">Education</option>
                    <option value="Art">Art</option>
                    <option value="Technology">Technology</option>
                    <option value="General">General</option>
                </select>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('editEventModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="eventDetailsModal">
    <div class="modal-content">
            <div class="modal-header">
        <h2 id="detail_title"></h2>
        </div>
<div class="form-group">
        <p><strong>Description:</strong> <span id="detail_description"></span></p>
        </div>
        <div class="form-group">
        <p><strong>Date:</strong> <span id="detail_date"></span></p>
        </div>
        <div class="form-group">
        <p><strong>Location:</strong> <span id="detail_location"></span></p>
        </div>
        <div class="form-group">
        <p><strong>Category:</strong> <span id="detail_category"></span></p>
        </div>
        <div class="form-group">
        <p><strong>Status:</strong> <span id="detail_status"></span></p>
        </div>
<div class="form-group">
        <hr>

        <h3>Participants (<span id="detail_participants_count"></span>)</h3>

        <hr>
        </div>
<div class="form-group">
        <h3>Sponsors</h3>
        <ul id="detail_sponsors_list"></ul>
</div>
        <button onclick="closeModal('eventDetailsModal')" class="btn-secondary">Close</button>
    </div>
</div>


    <!-- Create Task Modal -->
<div id="createTaskModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-check-square"></i> Create New Task</h3>
            <button class="close-modal" onclick="closeModal('createTaskModal')">&times;</button>
        </div>

        <form id="createTaskForm" class="modal-form">

            <div class="form-group">
                <label>Task Title *</label>
                <input type="text" name="title" required 
                       placeholder="e.g., Design event landing page">
            </div>

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" required rows="3" 
                          placeholder="Provide task details..."></textarea>
            </div>

            <!-- STATUS / PRIORITY / CATEGORY -->
            <div class="form-row">

                <div class="form-group">
                    <label>Status *</label>
                    <select name="status_id" required>
                        <option value="1">To Do</option>
                        <option value="2">In Progress</option>
                        <option value="3">In Review</option>
                        <option value="4">Done</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Priority *</label>
                    <select name="priority_id" required>
                        <option value="1">Low</option>
                        <option value="2" selected>Medium</option>
                        <option value="3">High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" required>
                        <option value="1">Planning</option>
                        <option value="2">Design</option>
                        <option value="3">Development</option>
                        <option value="4">Marketing</option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label>Progress: <span id="progressValue">0</span>%</label>
                <input type="range" name="progress" min="0" max="100" value="0"
                       step="5" oninput="updateProgressValue(this.value)">
            </div>

            <div class="form-group">
                <label>Assignees (comma separated initials)</label>
                <input type="text" name="assignees" placeholder="e.g., JS, AD, MK">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('createTaskModal')">
                    Cancel
                </button>
                <button type="submit" class="btn-primary">Create Task</button>
            </div>

        </form>
    </div>
</div>

    <!-- Add Sponsor Modal -->
<div id="addSponsorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-handshake"></i> Add New Sponsor</h3>
            <button class="close-modal" onclick="closeModal('addSponsorModal')">&times;</button>
        </div>

        <form id="addSponsorForm" class="modal-form">
            <div class="form-group">
                <label>Sponsor Name *</label>
                <input type="text" id="sponsor_name" name="sponsor_name" required placeholder="Company/Organization name">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Contact Email *</label>
                    <input type="email" id="contact_email" name="contact_email" required placeholder="contact@company.com">
                </div>
                <div class="form-group">
                    <label>Contact Phone *</label>
                    <input type="tel" id="contact_phone" name="contact_phone" required placeholder="+216 XX XXX XXX">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sponsorship Type *</label>
                    <select id="sponsorship_type" name="sponsorship_type" required>
                        <option value="Financial">Financial</option>
                        <option value="Media">Media</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Event *</label>
                    <input type="text" id="event_id" name="event_id" required placeholder="Associated event">
                </div>
            </div>

            <div class="form-group">
                <label>Contribution Notes</label>
                <textarea id="contribution_notes" name="contribution_notes" rows="3" placeholder="Details about sponsorship..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('addSponsorModal')">Cancel</button>

                <!-- CORRECT BUTTON FOR ADD -->
                <button type="submit" class="btn-primary">Save changes</button>
            </div>

        </form>
    </div>
</div>
<div id="editSponsorModal" class="modal">
    <div class="modal-content">
        <h2>Edit Sponsor</h2>

        <form id="editSponsorForm" method="POST" action="../../../../controller/Sponsor/updateSponsor.php">
            <input type="hidden" name="id" id="edit_sponsor_id">
            <div class="form-group">
            <label>Name:</label>
            <input type="text" name="sponsor_name" id="edit_sponsor_name" required>
            </div>
            <div class="form-group">
            <label>Email:</label>
            <input type="email" name="contact_email" id="edit_sponsor_email" required>
            </div>
            <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="contact_phone" id="edit_sponsor_phone" required>
            </div>
            <div class="form-group">
            <label>Type:</label>
            <select id="edit_sponsor_type" name="sponsorship_type">
                <option value="Financial">Financial</option>
                <option value="Media">Media</option>
                <option value="Equipment">Equipment</option>
                <option value="Other">Other</option>
            </select>
            </div>
            <div class="form-group">
            <label>Notes:</label>
            <textarea name="contribution_notes" id="edit_sponsor_notes"></textarea>
            </div>
            <div class="form-group">
            <label>Event ID:</label>
            <input type="number" name="event_id" id="edit_sponsor_event_id" required>
            </div>
             <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('addSponsorModal')">Cancel</button>
            <button type="submit" class="btn-primary">Save Changes</button>
           </div>
        </form>
    </div>
</div>

<!-- CONTACT SPONSOR MODAL -->
<div id="contactSponsorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-envelope"></i> Contact Sponsor</h3>
            <button class="close-modal" onclick="closeModal('contactSponsorModal')">&times;</button>
        </div>

        <form id="contactSponsorForm">

            <input type="hidden" id="contact_sponsor_id">

            <div class="form-group">
                <label>Message / Reason</label>
                <textarea id="contact_sponsor_reason" rows="3" required></textarea>
            </div>

            <div class="modal-actions">
                <button class="btn-primary" onclick="sendSponsorContact()">Send Message</button>
                <button type="button" class="btn-danger" onclick="cancelSponsorAction()">Cancel Sponsor</button>
            </div>

        </form>
    </div>
</div>

    <!-- Notification Panel --> 
    <div id="notificationPanel" class="notification-panel">
        <div class="notification-header">
            <h4>Notifications</h4>
            <button onclick="toggleNotifications()" class="close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="notification-list">
            <div class="notification-item unread">
                <i class="fas fa-user-plus"></i>
                <div>
                    <p>New participant registered for Tech Innovation Conference</p>
                    <span>5 minutes ago</span>
                </div>
            </div>
            <div class="notification-item unread">
                <i class="fas fa-handshake"></i>
                <div>
                    <p>Sponsor contract signed: Tunisie Telecom</p>
                    <span>1 hour ago</span>
                </div>
            </div>
            <div class="notification-item">
                <i class="fas fa-calendar-check"></i>
                <div>
                    <p>Event deadline approaching: Cultural Heritage Summit</p>
                    <span>2 hours ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- User Menu -->
    <div id="userMenu" class="user-menu">
        <div class="user-menu-header">
            <div class="user-avatar-large">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <p class="user-menu-name">Stella Walton</p>
                <p class="user-menu-email">stella.walton@lumina.com</p>
            </div>
        </div>
        <div class="user-menu-items">
            <a href="#" class="user-menu-item">
                <i class="fas fa-user-circle"></i>
                <span>Profile Settings</span>
            </a>
            <a href="#" class="user-menu-item">
                <i class="fas fa-cog"></i>
                <span>Preferences</span>
            </a>
            <a href="#" class="user-menu-item">
                <i class="fas fa-question-circle"></i>
                <span>Help & Support</span>
            </a>
            <div class="user-menu-divider"></div>
            <a href="#" class="user-menu-item logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
    <!-- ===================== -->
<!-- EDIT USER MODAL -->
<!-- ===================== -->
<div id="editUserModal" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h2><i class="fas fa-user-edit"></i> Edit User</h2>
            <button class="close-modal" onclick="closeEditUserModal()">&times;</button>
        </div>

        <form id="editUserForm" class="modal-form" onsubmit="return false;">
            <input type="hidden" id="edit_user_id">

            <div class="form-row">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" id="edit_username" required>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="edit_email" required>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeEditUserModal()">
                    Cancel
                </button>
                <button type="button" class="btn-primary" onclick="saveUserChanges()">
                    Save Changes
                </button>
            </div>
        </form>

    </div>
</div>
    </div>
    
    <script src="script.js"></script>
    <script src="charts.js"></script>
    <script src="manage.js"></script>
    
</body>
</html>
