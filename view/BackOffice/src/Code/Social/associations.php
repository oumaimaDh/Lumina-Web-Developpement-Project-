<!DOCTYPE html>
<?php
// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
// In associations.php line 8, replace with:
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/socialcasecontroller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/model/socialcasemodel.php';

$socialCaseController = new SocialCaseController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_association'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $availabelity = isset($_POST['availabelity']) ? (int)$_POST['availabelity'] : 0;
    $id_category = $_POST['id_category'];

    $association = new Association(null, $name, $phone, $location, $email, $availabelity, $id_category);
    $socialCaseController->addAssociation($association);

    // Redirect to prevent form resubmission
    header('Location: associations.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f5f5f7;
            overflow-x: auto;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #718096;
            font-size: 1.1em;
        }
        
        .recent-activities {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #edf2f7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .activity-time {
            color: #718096;
            font-size: 0.9em;
        }

        /* Styles for Add Association Form */
        .add-association-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .add-association-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .add-association-form .form-group {
            margin-bottom: 15px;
        }

        .add-association-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .add-association-form input[type="text"],
        .add-association-form input[type="email"],
        .add-association-form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box; /* Ensures padding doesn't increase width */
        }

        .add-association-form .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .add-association-form .btn-primary:hover {
            background-color: #45a049;
        }

        /* Styles for Associations List */
        .associations-list-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .associations-list-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .associations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .associations-table th,
        .associations-table td {
            border: 1px solid #eee;
            padding: 12px 15px;
            text-align: left;
        }

        .associations-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            color: #666;
        }

        .associations-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .associations-table tr:hover {
            background-color: #f1f1f1;
        }

        .associations-table .btn-edit,
        .associations-table .btn-delete {
            display: inline-block;
            padding: 6px 12px;
            margin-right: 5px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
        }

        .associations-table .btn-edit {
            background-color: #007bff;
        }

        .associations-table .btn-edit:hover {
            background-color: #0056b3;
        }

        .associations-table .btn-delete {
            background-color: #dc3545;
        }

        .associations-table .btn-delete:hover {
            background-color: #c82333;
        }

        /* Map container styles */
        #location-map {
            height: 300px !important;
            width: 100% !important;
            min-height: 300px !important;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1;
            position: relative;
            background-color: #f0f0f0;
        }

        /* Ensure Leaflet map tiles are visible */
        .leaflet-container {
            height: 100% !important;
            width: 100% !important;
            min-height: 300px !important;
            z-index: 1;
            position: relative;
        }

        .leaflet-tile-container {
            z-index: 1;
        }
        
        .leaflet-tile-container img {
            max-width: none !important;
        }
    </style>
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

<!-- MAIN CONTENT AREA -->
<main class="main-content">
    <h1 style="margin-bottom: 20px; color: #333;">Associations Management</h1>
    
    <section class="add-association-section">
        <h2>Add New Association</h2>
        <form action="" method="POST" class="add-association-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <div id="location-map" style="height: 300px !important; width: 100% !important; min-height: 300px !important; border-radius: 8px; margin: 10px 0; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: #e8e8e8; position: relative; z-index: 1;"></div>
                <div style="margin-top: 10px; padding: 10px; background: #f6e7fe; border-radius: 8px;">
                    <p><strong>Selected Location:</strong> <span id="selected-location">Click on the map to select location</span></p>
                    <input type="hidden" name="location" id="location" value="">
                    <input type="hidden" name="loc_lat" id="loc_lat" value="">
                    <input type="hidden" name="loc_lng" id="loc_lng" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="availabelity">Availabelity:</label>
                <select id="availabelity" name="availabelity">
                    <option value="1">Available</option>
                    <option value="0">Not Available</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id_category">Category:</label>
                <select id="id_category" name="id_category">
                    <?php
                    // Get all 9 categories from database (1-4 for social case, 5-9 for job department)
                    $db = Config::getConnexion();
                    $sql = "SELECT id_category, name FROM category ORDER BY id_category";
                    try {
                        $query = $db->prepare($sql);
                        $query->execute();
                        $allCategories = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($allCategories as $category) {
                            $categoryType = in_array($category['id_category'], [1, 2, 3, 4]) ? ' (Social Case)' : ' (Job Department)';
                            echo '<option value="' . htmlspecialchars($category['id_category']) . '">' . htmlspecialchars($category['name']) . $categoryType . '</option>';
                        }
                    } catch(Exception $e) {
                        // Fallback to social case categories only if error
                        $categories = $socialCaseController->getAllCategories();
                        foreach ($categories as $category) {
                            echo '<option value="' . htmlspecialchars($category['id_category']) . '">' . htmlspecialchars($category['name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="add_association" class="btn-primary">Add Association</button>
            <a href="listassociations.php" class="btn-primary" style="margin-left: 10px; background-color: #007bff;">View Associations</a>
        </form>
    </section>

</main>

</div> <!-- Close dashboard-container -->

    <!-- Leaflet CSS (already in head, but ensure it's loaded) -->
    <!-- Leaflet JS - Load before map-utils -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Map utilities - Load after Leaflet -->
    <script src="../../../../FrontOffice/front/assets/js/map-utils.js"></script>
    <!-- Other scripts -->
    <script src="scriptmenna.js"></script>
    <script>
        // Set current date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Notification count is now shown in the icon
        
        function toggleUserMenu() {
            alert('User menu to be implemented');
        }

        // Update active nav item
        document.addEventListener('DOMContentLoaded', async function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === currentPage) {
                    item.classList.add('active');
                }
            });
            
            // Initialize map for location selection
            const mapContainer = document.getElementById('location-map');
            if (!mapContainer) {
                console.error('Map container not found');
                return;
            }
            
            // Show loading message
            mapContainer.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #666;"><p>Loading map...</p></div>';
            
            // Wait a bit for scripts to fully load
            setTimeout(() => {
                try {
                    // Check if Leaflet and map-utils are loaded
                    if (typeof L === 'undefined') {
                        console.error('Leaflet library not loaded');
                        mapContainer.innerHTML = '<p style="padding: 20px; color: red;">Map library not loaded. Please refresh the page.</p>';
                        return;
                    }
                    
                    if (typeof initTunisiaMap === 'undefined') {
                        console.error('map-utils.js not loaded. Check console for errors.');
                        mapContainer.innerHTML = '<p style="padding: 20px; color: red;">Map utilities not loaded. Please check the browser console and refresh the page.</p>';
                        return;
                    }
                    
                    console.log('Initializing map...');
                    // Clear loading message
                    mapContainer.innerHTML = '';
                    
                    // Initialize map
                    const locationMap = initTunisiaMap('location-map', 33.8869, 10.1775, 7);
                    let selectedMarker = null;
                    
                    console.log('Map initialized successfully');
                    
                    // Force map to invalidate size after a short delay
                    setTimeout(() => {
                        locationMap.invalidateSize();
                        console.log('Map size invalidated');
                    }, 300);
                
                locationMap.on('click', async function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    
                    if (selectedMarker) {
                        locationMap.removeLayer(selectedMarker);
                    }
                    
                    selectedMarker = L.marker([lat, lng], {draggable: true}).addTo(locationMap);
                    selectedMarker.bindPopup('Association location').openPopup();
                    
                    try {
                        const address = await reverseGeocode(lat, lng);
                        document.getElementById('location').value = address;
                        document.getElementById('loc_lat').value = lat;
                        document.getElementById('loc_lng').value = lng;
                        document.getElementById('selected-location').textContent = address;
                    } catch (error) {
                        console.error('Geocoding error:', error);
                        document.getElementById('location').value = lat + ', ' + lng;
                        document.getElementById('loc_lat').value = lat;
                        document.getElementById('loc_lng').value = lng;
                        document.getElementById('selected-location').textContent = lat + ', ' + lng;
                    }
                    
                    selectedMarker.on('dragend', async function(e) {
                        const newLat = e.target.getLatLng().lat;
                        const newLng = e.target.getLatLng().lng;
                        try {
                            const newAddress = await reverseGeocode(newLat, newLng);
                            document.getElementById('location').value = newAddress;
                            document.getElementById('loc_lat').value = newLat;
                            document.getElementById('loc_lng').value = newLng;
                            document.getElementById('selected-location').textContent = newAddress;
                        } catch (error) {
                            console.error('Geocoding error:', error);
                            document.getElementById('location').value = newLat + ', ' + newLng;
                            document.getElementById('loc_lat').value = newLat;
                            document.getElementById('loc_lng').value = newLng;
                            document.getElementById('selected-location').textContent = newLat + ', ' + newLng;
                        }
                    });
                    });
                } catch (error) {
                    console.error('Error initializing map:', error);
                    mapContainer.innerHTML = '<p style="padding: 20px; color: red;">Error loading map: ' + error.message + '. Please refresh the page.</p>';
                }
            }, 100);
        });
    </script>
</body>
</html>