<?php
// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/socialcasecontroller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/model/socialcasemodel.php';

$socialCaseController = new SocialCaseController();
$association = null;
// Get all 9 categories from database (1-4 for social case, 5-9 for job department)
$db = Config::getConnexion();
$sql = "SELECT id_category, name FROM category ORDER BY id_category";
try {
    $query = $db->prepare($sql);
    $query->execute();
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    // Fallback to social case categories only if error
    $categories = $socialCaseController->getAllCategories();
}

if (isset($_GET['id'])) {
    $id_association = $_GET['id'];
    $associationData = $socialCaseController->getAssociationById($id_association);

    if ($associationData) {
        $association = new Association(
            $associationData['id_association'],
            $associationData['name'],
            $associationData['phone'],
            $associationData['location'],
            $associationData['email'],
            (int)$associationData['availabelity'],
            $associationData['id_category']
        );
        $association->setIdAssociation($associationData['id_association']);
    } else {
        echo "<p>Association not found.</p>";
        exit();
    }
} else if (isset($_POST['id_association'])) {
    // Handle update submission
    $id_association = $_POST['id_association'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $availabelity = (int)$_POST['availabelity'];
    $id_category = $_POST['id_category'];

    $updatedAssociation = new Association($id_association, $name, $phone, $location, $email, $availabelity, $id_category);
    $updatedAssociation->setIdAssociation($id_association);

    $socialCaseController->updateAssociation($updatedAssociation);

    header('Location: listassociations.php');
    exit();
} else {
    echo "<p>No association ID provided for editing.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Association</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Map container styles */
        #location-map {
            height: 300px !important;
            width: 100% !important;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1;
            position: relative;
        }

        /* Ensure Leaflet map tiles are visible */
        .leaflet-container {
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        .leaflet-tile-container {
            z-index: 1;
        }
    </style>
    <script src="scriptmenna.js" defer></script>
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
                <a href="indexb.php" class="nav-item" data-tab="dashboard">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="social_case.php" class="nav-item" data-tab="social">
                    <i class="fas fa-heart"></i>
                    <span>Social Cases</span>
                </a>
                <a href="listassociations.php" class="nav-item active" data-tab="associations">
                    <i class="fas fa-users"></i>
                    <span>Associations</span>
                </a>
                <a href="#" class="nav-item" data-tab="jobs">
                    <i class="fas fa-briefcase"></i>
                    <span>Jobs</span>
                </a>
                <a href="#" class="nav-item" data-tab="forum">
                    <i class="fas fa-comments"></i>
                    <span>Forum</span>
                </a>
                <a href="#" class="nav-item" data-tab="settings">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Version Info -->
            <div class="version-info">
                <p>Version 23.0.0</p>
                <p class="version-date">2025</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header with Logo -->
            <header class="dashboard-header">
                <div class="header-logo">
                    <img src="logo.png.png" alt="Lumina Logo" class="logo-image">
                </div>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search jobs, associations..." class="search-input" id="globalSearch">
                </div>
                <div class="header-date">
                    <p id="currentDate"></p>
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

            <section class="edit-association-section" style="background-color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 15px; padding: 20px;">
                <h2>Edit Association</h2>
                <?php if ($association): ?>
                <form action="editassociation.php" method="POST" id="editAssociationForm">
                    <input type="hidden" name="id_association" value="<?php echo htmlspecialchars($association->getIdAssociation()); ?>">
                    
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($association->getName()); ?>">
                        <div class="error-message" id="nameError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($association->getPhone()); ?>">
                        <div class="error-message" id="phoneError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <div id="location-map" style="height: 300px; width: 100%; border-radius: 8px; margin: 10px 0; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);"></div>
                        <div style="margin-top: 10px; padding: 10px; background: #f6e7fe; border-radius: 8px;">
                            <p><strong>Selected Location:</strong> <span id="selected-location">Click on the map to select location</span></p>
                            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($association->getLocation()); ?>" style="display: none;">
                            <input type="hidden" name="loc_lat" id="loc_lat" value="">
                            <input type="hidden" name="loc_lng" id="loc_lng" value="">
                        </div>
                        <div class="error-message" id="locationError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($association->getEmail()); ?>">
                        <div class="error-message" id="emailError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="availabelity">Availabelity:</label>
                        <select id="availabelity" name="availabelity">
                            <option value="1" <?php echo ($association->getAvailabelity() == 1) ? 'selected' : ''; ?>>Available</option>
                            <option value="0" <?php echo ($association->getAvailabelity() == 0) ? 'selected' : ''; ?>>Not Available</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_category">Category:</label>
                        <select id="id_category" name="id_category">
                            <?php foreach ($categories as $category): 
                                $categoryType = in_array($category['id_category'], [1, 2, 3, 4]) ? ' (Social Case)' : ' (Job Department)';
                            ?>
                                <option value="<?php echo htmlspecialchars($category['id_category']); ?>"
                                    <?php echo ($association->getIdCategory() == $category['id_category']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']) . $categoryType; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="error-message" id="categoryError"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Association</button>
                </form>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../front/assets/js/map-utils.js"></script>
    <script>
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        function toggleNotifications() {
            alert('Notifications panel to be implemented');
        }
        
        function toggleUserMenu() {
            alert('User menu to be implemented');
        }

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
            
            // Wait a bit to ensure container is fully rendered
            setTimeout(() => {
                try {
                    const locationMap = initTunisiaMap('location-map', 33.8869, 10.1775, 7);
                    let selectedMarker = null;
                    const currentLocation = document.getElementById('location').value;
                    
                    // Force map to invalidate size after initialization
                    setTimeout(() => {
                        locationMap.invalidateSize();
                    }, 100);
                    
                    // Try to parse existing location or geocode it
                    if (currentLocation) {
                        let coords = parseCoordinates(currentLocation);
                        if (!coords) {
                            coords = await geocodeAddress(currentLocation);
                        }
                        
                        if (coords) {
                            selectedMarker = L.marker([coords.lat, coords.lng], {draggable: true}).addTo(locationMap);
                            selectedMarker.bindPopup('Association location').openPopup();
                            locationMap.setView([coords.lat, coords.lng], 13);
                            document.getElementById('selected-location').textContent = currentLocation;
                            document.getElementById('loc_lat').value = coords.lat;
                            document.getElementById('loc_lng').value = coords.lng;
                        }
                    }
                    
                    locationMap.on('click', async function(e) {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;
                        
                        if (selectedMarker) {
                            locationMap.removeLayer(selectedMarker);
                        }
                        
                        selectedMarker = L.marker([lat, lng], {draggable: true}).addTo(locationMap);
                        selectedMarker.bindPopup('Association location').openPopup();
                        
                        const address = await reverseGeocode(lat, lng);
                        
                        document.getElementById('location').value = address;
                        document.getElementById('loc_lat').value = lat;
                        document.getElementById('loc_lng').value = lng;
                        document.getElementById('selected-location').textContent = address;
                        
                        selectedMarker.on('dragend', async function(e) {
                            const newLat = e.target.getLatLng().lat;
                            const newLng = e.target.getLatLng().lng;
                            const newAddress = await reverseGeocode(newLat, newLng);
                            document.getElementById('location').value = newAddress;
                            document.getElementById('loc_lat').value = newLat;
                            document.getElementById('loc_lng').value = newLng;
                            document.getElementById('selected-location').textContent = newAddress;
                        });
                    });
                } catch (error) {
                    console.error('Error initializing map:', error);
                    document.getElementById('location-map').innerHTML = '<p style="padding: 20px; color: red;">Error loading map. Please refresh the page.</p>';
                }
            }, 100);
        });
    </script>
</body>
</html>