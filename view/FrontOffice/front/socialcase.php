<!DOCTYPE html>
<script>
// BLOCK ALL COOKIES from being set
Object.defineProperty(document, 'cookie', {
    get: function() { return ''; },
    set: function() { return false; }
});

// Clear existing cookies
document.cookie.split(";").forEach(function(c) {
    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
});

// Check if user is logged in via localStorage

</script>

<?php
session_start();
// Define base path - go up from view/front to root
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}

include_once __DIR__ . '/../../../config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/socialcasecontroller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/model/socialcasemodel.php';
$socialCaseController = new SocialCaseController();

$categories = [];
$associations = [];

try {
    $pdo = config::getConnexion();

    // Fetch categories (only 1-4 for social case)
    $stmt_categories = $pdo->query("SELECT id_category, name FROM category WHERE id_category IN (1, 2, 3, 4) ORDER BY id_category");
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

    // Fetch associations with category and rating (only categories 1-4)
    $stmt_associations = $pdo->query("SELECT id_association, name, id_category, rating FROM association WHERE id_category IN (1, 2, 3, 4)");
    $associations = $stmt_associations->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Database error: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['loc']) || trim($_POST['loc']) === '') {
        die('Error: Please select a location on the map before submitting.');
    }
    
    // Ensure location is text, not coordinates
    $location = trim($_POST['loc']);
    // Check if location looks like coordinates (contains only numbers, commas, spaces, and dots)
    if (preg_match('/^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/', $location)) {
        // If it's coordinates, reject the submission - location must be selected from map
        die('Erreur: Veuillez sélectionner un emplacement sur la carte. Les coordonnées ne sont pas acceptées. / Error: Please select a location on the map. Coordinates are not accepted.');
    }
    
    // Ensure location is not empty
    if (empty($location)) {
        die('Erreur: Veuillez sélectionner un emplacement sur la carte avant de soumettre. / Error: Please select a location on the map before submitting.');
    }
    
    // Get selected association ID from form
    $selectedAssociationId = isset($_POST['assoc']) && !empty($_POST['assoc']) ? (int)$_POST['assoc'] : null;
    
    // If no association selected, get first available association ID
    if (!$selectedAssociationId) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT id_association FROM association ORDER BY id_association ASC LIMIT 1");
            $firstAssociation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($firstAssociation) {
                $selectedAssociationId = (int)$firstAssociation['id_association'];
            } else {
                // No associations exist - create a default "Unassigned" association
                $catStmt = $pdo->query("SELECT id_category FROM category ORDER BY id_category ASC LIMIT 1");
                $firstCategory = $catStmt->fetch(PDO::FETCH_ASSOC);
                $defaultCategoryId = $firstCategory ? (int)$firstCategory['id_category'] : 1;
                
                $insertStmt = $pdo->prepare("INSERT INTO association (name, phone, location, email, availabelity, id_category) 
                                            VALUES ('Unassigned', 'N/A', 'N/A', 'N/A', 0, ?)");
                $insertStmt->execute([$defaultCategoryId]);
                $selectedAssociationId = $pdo->lastInsertId();
            }
        } catch (Exception $e) {
            $selectedAssociationId = 1;
        }
    }
    
    // Get association name for notification
    $associationName = 'Unknown';
    try {
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("SELECT name FROM association WHERE id_association = ?");
        $stmt->execute([$selectedAssociationId]);
        $assocData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($assocData) {
            $associationName = $assocData['name'];
        }
    } catch (Exception $e) {
        // Use default name
    }
    
    $newSocialCase = new SocialCase(
        null, // id_case is auto-incremented
        $_POST['name'],
        $_POST['phone'],
        $_POST['email'],
        
        $_POST['desc'],
        $location, // Use validated location (text only, not coordinates)
        date('Y-m-d'), // submited_date, using current date
        date('Y-m-d'), // updated_date, using current date
        'Pending', // status, default to Pending
        (int)$_POST['catg'],
        $selectedAssociationId // id_association - use selected association
    );
    
    // Store association name for notification
    $_SESSION['association_name_for_notification'] = $associationName;
    $case_id = $socialCaseController->addSocialCase($newSocialCase, $associationName);
    header('Location: listcases.php'); // Redirect to a list of cases or a success page
    exit;
}
?>

<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <title>Tale SEO Agency CSS Template by TemplateMo website</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-tale-seo-agency.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="CSS/job.css">
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
      #location-map {
        height: 400px;
        width: 100%;
        border-radius: 8px;
        margin: 10px 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .map-container {
        margin: 20px 0;
      }
      .location-info {
        margin-top: 10px;
        padding: 10px;
        background: #f6e7fe;
        border-radius: 8px;
      }
      .nearest-associations {
        margin-top: 20px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      .association-item {
        padding: 10px;
        margin: 5px 0;
        background: #f9f9f9;
        border-radius: 5px;
        border-left: 3px solid #007bff;
      }
    </style>
<!--

TemplateMo 582 Tale SEO Agency

https://templatemo.com/tm-582-tale-seo-agency

-->
  </head>

<body>

  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Pre-Header Area Start ***** -->
  <div class="pre-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-sm-9">
          <div class="left-info">
            <ul>
              <li><a href="#"><i class="fa fa-phone"></i>+216 98 105 537</a></li>
              <li><a href="#"><i class="fa fa-envelope"></i>Lumina@email.com</a></li>
              <li><a href="#"><i class="fa fa-map-marker"></i>Tunisia Ariana El Ghazela</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-sm-3">
          <div class="social-icons">
            <ul>
              <li><a href="#"><i class="fab fa-facebook"></i></a></li>
              <li><a href="#"><i class="fab fa-twitter"></i></a></li>
              <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
              <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ***** Pre-Header Area End ***** -->

  <!-- ***** Header Area Start ***** -->
  <nav class="navbar">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Lumina Logo">
        </div>
        <ul class="nav-links">
            <li><a href="../newindex.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="socialcase.php"><i class="fas fa-hands-helping"></i> Get Help</a></li>
            <li class="dropdown">
                <a href="jobdepartment.php" class="dropdown-toggle"><i class="fas fa-briefcase"></i> Jobs<i class="fas fa-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="jobdepartment.php#employment-service"><i class="fas fa-search"></i> Job Search</a></li>
                    <li><a href="jobdepartment.php#associations"><i class="fas fa-users"></i> Associations</a></li>
                    <li><a href="jobdepartment.php#application-tracking"><i class="fas fa-tasks"></i> Application Tracking</a></li>
                </ul>
            </li>
            <li><a href="../event.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
            <li><a href="forum.php"><i class="fas fa-comments"></i> Forum</a></li>
            
            <li><a href="../contact.html"><i class="fas fa-comments"></i> Contact</a></li>
          
        </ul>
        <div class="nav-actions">
            <button class="theme-toggle" id="theme-toggle">
                <i class="fas fa-sun"></i>
            </button>
        </div>
    </nav>
  <!-- ***** Header Area End ***** -->

 




  <div class="container" style="margin-top: 200px;">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-3">
            <div class="item" style="background-color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 15px; padding: 20px; text-align: center; aspect-ratio: 1 / 1;">
              <div class="header-text">
                <h2 style="font-weight: bold;"> Financial & Poverty Issues <br> Category 1 </h2>
                <p>Associations in this category help individuals and families facing economic hardship.
They provide food aid, financial assistance, job support, and resources to improve living conditions.</p>
                <div class="type-button">
                  <a href="categorie1.php" class="btn btn-primary">View Details</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="item" style="background-color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 15px; padding: 20px; text-align: center; aspect-ratio: 1 / 1;">
              <div class="header-text">
                <h2 style="font-weight: bold;">Health & Medical Problems <br>Category 2</h2>
                <p>These associations support people with medical needs or disabilities.
They offer access to treatment, medicines, emergency help, and health awareness programs.</p>
                <div class="type-button" style="margin-top: 31px;">
                  <a href="categorie2.php" class="btn btn-primary">View Details</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="item" style="background-color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 15px; padding: 20px; text-align: center; aspect-ratio: 1 / 1;">
              <div class="header-text">
                <h2 style="font-weight: bold;"> Domestic & Family Issues <br> Category 3</h2>
                <p>Organizations in this field assist with family conflicts, domestic violence, childcare, and vulnerable individuals.
They work to ensure safety, counseling, and social reintegration.</p>
                <div class="type-button" style="margin-top: 32px;">
                  <a href="categorie3.php" class="btn btn-primary">View Details</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="item" style="background-color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 15px; padding: 20px; text-align: center; aspect-ratio: 1 / 1;">
              <div class="header-text">
                <h2 style="font-weight: bold;">Animal Welfare & Rescue<br> Category 4</h2>
                <p>Associations in this category help animals in need, especially those abandoned or injured in the streets.
They provide rescue, medical care, adoption services, and support for owners who can’t care for their animals.</p>
                <div class="type-button" style="margin-top: 3px;">
                  <a href="categorie4.php" class="btn btn-primary">View Details</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="contact-us section" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="contact-us-content">
            <div class="row">
              <div class="col-lg-4">
                <div class="map-container">
                  <h4>Select Your Location (Required)</h4>
                  <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Cliquez sur la carte pour sélectionner votre emplacement / Click on the map to select your location</p>
                  <div id="location-map"></div>
                  <div class="location-info">
                    <p><strong>Selected Location:</strong> <span id="selected-location" style="color: #007bff; font-weight: bold;">Click on the map to select location</span></p>
                    <!-- Note: Location input fields are now inside the form below -->
                  </div>
                  <div class="nearest-associations" id="nearest-associations" style="display: none;">
                    <h5>Nearest Associations to Your Location:</h5>
                    <div id="associations-list"></div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <form id="contact-form" action="" method="post" onsubmit="return validateForm()">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="section-heading">
                        <h2><em>Enter Your</em>  Case </h2>
                      </div>
                    </div>
                    <div >
                      <fieldset>
                        <input type="name" name="name" id="name" placeholder="Your Name..." autocomplete="on" >
                      </fieldset>
                    </div>
                    <div >
                      <fieldset>
                        <input type="phone" name="phone" id="phone" placeholder="Your phone number..." autocomplete="on" >
                      </fieldset>
                    </div>
                    <div >
                      <fieldset>
                        <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your E-mail..." >
                      </fieldset>
                    </div>
                    <div >
                      <fieldset>
                        <select name="catg" id="catg" style="border-radius: 20px; background-color:#f6e7fe; width: 650px; height: 40px;  border:0px;">
                            <option value="">Choose Your Probleme categorie</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id_category']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                            
                        </select>
                      </fieldset>
                    </div>
                    <br><br>
                    <div >
                      <fieldset>
                        <select name="assoc" id="assoc" style="border-radius: 20px; background-color:#f6e7fe; width: 650px; height: 40px; border:0px;" disabled>
                            <option value="">First select a category</option>
                        </select>
                      </fieldset>
                    </div>
                    <br><br>
                    <div >
                      <fieldset>
                        <textarea name="desc" id="desc" placeholder="Description"></textarea>
                      </fieldset>

                    </div>
                     <!-- Location is now selected via map above -->
                    <!-- Hidden location fields - must be inside form to be submitted -->
                    <input type="hidden" name="loc" id="loc" value="">
                    <input type="hidden" name="loc_lat" id="loc_lat" value="">
                    <input type="hidden" name="loc_lng" id="loc_lng" value="">
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="col-lg-6">
                          <fieldset>
                            <button type="submit" id="form-submit" class="btn btn-primary">Submit</button>
                          </fieldset>
                        </div>
                        <div class="col-lg-6">
                          <fieldset>
                            <a href="listcases.php" class="btn btn-primary" id="view-case-button">View Your Case</a>
                          </fieldset>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                <div class="more-info">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="info-item">
                        <i class="fa fa-phone"></i>
                        <h4><a href="#">216 98 105 537</a></h4>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="info-item">
                        <i class="fa fa-envelope"></i>
                        <h4><a href="#">Lumina@gmail.com</a></h4>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="info-item">
                        <i class="fa fa-map-marker"></i>
                        <h4><a href="#">Tunisia</a></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <footer class="footer">
      <!-- Floating Elements -->
      <div class="floating-elements">
          <div class="floating-element"><i class="fas fa-heart"></i></div>
          <div class="floating-element"><i class="fas fa-star"></i></div>
          <div class="floating-element"><i class="fas fa-circle"></i></div>
          <div class="floating-element"><i class="fas fa-square"></i></div>
          <div class="floating-element"><i class="fas fa-bolt"></i></div>
      </div>

      <div class="footer-content">
          <div class="footer-column">
              <h3>Lumina</h3>
              <p>Innovative platform for national solidarity <br>in Tunisia, efficiently connecting <br>citizens with the resources they need.</p>
          </div>
          <div class="footer-column">
              <h3>Contact</h3>
              <ul class="footer-links">
                  <li><a href="#"><i class="fas fa-map-marker-alt"></i> Tunis, Tunisia</a></li>
                  <li><a href="mailto:contact@lumina.tn"><i class="fas fa-envelope"></i> contact@lumina.tn</a></li>
                  <li><a href="tel:+21670123456"><i class="fas fa-phone"></i>+216 70 123 456</a></li>
              </ul>
          </div>
          <div class="footer-column">
              <h3>Social Networks</h3>
              <ul class="footer-links social-links">
                  <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                  <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                  <li><a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                  <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
              </ul>
          </div>
      </div>
      <div class="footer-bottom">
          <p>&copy; 2025 Lumina - Tunisian National Solidarity Platform. All rights reserved.</p>
      </div>
  </footer>



  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/tabs.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var themeToggle = document.getElementById('theme-toggle');
      if(!themeToggle) return;
      var html = document.documentElement;
      var savedTheme = localStorage.getItem('theme') || 'light';
      html.setAttribute('data-theme', savedTheme);
      themeToggle.innerHTML = savedTheme === 'dark' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
      themeToggle.addEventListener('click', function(){
        var current = html.getAttribute('data-theme');
        var next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        themeToggle.innerHTML = next === 'dark' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
      });
    });
  </script>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          var dropdowns = document.querySelectorAll('.navbar .dropdown');
          dropdowns.forEach(function(dropdown){
              var toggle = dropdown.querySelector('.dropdown-toggle');
              var menu = dropdown.querySelector('.dropdown-menu');
              if(!toggle || !menu) return;
              toggle.addEventListener('click', function(e){
                  e.preventDefault(); e.stopPropagation();
                  var isShowing = menu.classList.contains('show');
                  document.querySelectorAll('.navbar .dropdown-menu.show').forEach(function(m){ m.classList.remove('show'); });
                  document.querySelectorAll('.navbar .dropdown-toggle .fa-chevron-down.rotate').forEach(function(c){ c.classList.remove('rotate'); });
                  if(!isShowing){
                      menu.classList.add('show');
                      var chevron = toggle.querySelector('.fa-chevron-down');
                      if(chevron) chevron.classList.add('rotate');
                  }
              });
              document.addEventListener('click', function(e){
                  if(!dropdown.contains(e.target)){
                      menu.classList.remove('show');
                      var chevron = toggle.querySelector('.fa-chevron-down');
                      if(chevron) chevron.classList.remove('rotate');
                  }
              });
              menu.addEventListener('click', function(e){ e.stopPropagation(); });
          });
      });

      document.addEventListener('DOMContentLoaded', function() {
          var links = document.querySelectorAll('.nav-links > li > a, .navbar .dropdown-menu a');
          function clearActive() {
              document.querySelectorAll('.nav-links li.active').forEach(function(li){ li.classList.remove('active'); });
              document.querySelectorAll('.navbar .dropdown-menu a.active, .nav-links a.active').forEach(function(a){ a.classList.remove('active'); });
          }
          function markActive(href) {
              var target = null;
              links.forEach(function(a){
                  var ah = a.getAttribute('href') || '';
                  if(ah === href) target = a;
                  else if(!target && ah && href.indexOf(ah.split('#')[0]) > -1) target = a;
              });
              if(target) {
                  clearActive();
                  target.classList.add('active');
                  var li = target.closest('li');
                  if(li && li.parentElement && li.parentElement.classList.contains('nav-links')) li.classList.add('active');
              }
          }
          links.forEach(function(a){
              a.addEventListener('click', function(){
                  var href = a.getAttribute('href') || '';
                  markActive(href);
              });
          });
          markActive(location.pathname + location.hash);
      });
  </script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="assets/js/map-utils.js"></script>
  <script>
    // Get associations data from PHP
    const allAssociations = <?php echo json_encode($associations); ?>;
    const allCategories = <?php echo json_encode($categories); ?>;
    
    let selectedLocation = null;
    let selectedMarker = null;
    let locationMap = null;
    
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize map for location selection
      locationMap = initTunisiaMap('location-map', 33.8869, 10.1775, 7);
      
      // Add click event to map
      locationMap.on('click', async function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Remove previous marker if exists
        if (selectedMarker) {
          locationMap.removeLayer(selectedMarker);
        }
        
        // Add new marker
        selectedMarker = L.marker([lat, lng], {draggable: true}).addTo(locationMap);
        selectedMarker.bindPopup('Your selected location').openPopup();
        
        // Get address from coordinates
        const address = await reverseGeocode(lat, lng);
        
        // Ensure address is text, not coordinates (safety check)
        let locationText = address;
        if (address && /^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/.test(address)) {
            // If reverse geocoding returned coordinates, use a fallback
            locationText = 'Location sélectionnée, Tunisie';
        }
        
        selectedLocation = {lat: lat, lng: lng, address: locationText};
        
        // Update form fields - only store text address in 'loc', not coordinates
        document.getElementById('loc').value = locationText;
        document.getElementById('loc_lat').value = lat;
        document.getElementById('loc_lng').value = lng;
        document.getElementById('selected-location').textContent = locationText;
        
        // Allow marker dragging
        selectedMarker.on('dragend', async function(e) {
          const newLat = e.target.getLatLng().lat;
          const newLng = e.target.getLatLng().lng;
          const newAddress = await reverseGeocode(newLat, newLng);
          
          // Ensure address is text, not coordinates (safety check)
          let locationText = newAddress;
          if (newAddress && /^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/.test(newAddress)) {
            // If reverse geocoding returned coordinates, use a fallback
            locationText = 'Location sélectionnée, Tunisie';
          }
          
          selectedLocation = {lat: newLat, lng: newLng, address: locationText};
          document.getElementById('loc').value = locationText;
          document.getElementById('loc_lat').value = newLat;
          document.getElementById('loc_lng').value = newLng;
          document.getElementById('selected-location').textContent = locationText;
          updateNearestAssociations();
        });
        
        // Update nearest associations
        updateNearestAssociations();
      });
      
      // Watch for category selection to filter associations dropdown
      const catgElement = document.getElementById('catg');
      const assocSelect = document.getElementById('assoc');
      
      if (catgElement && assocSelect) {
        catgElement.addEventListener('change', function() {
          const selectedCategoryId = this.value;
          
          // Clear current options
          assocSelect.innerHTML = '';
          
          if (selectedCategoryId === '') {
            assocSelect.disabled = true;
            assocSelect.innerHTML = '<option value="">First select a category</option>';
          } else {
            assocSelect.disabled = false;
            assocSelect.innerHTML = '<option value="">Choose an association</option>';
            
            // Filter associations by selected category
            const filteredAssociations = allAssociations.filter(assoc => 
              assoc.id_category == selectedCategoryId
            );
            
            // Add filtered associations to dropdown
            filteredAssociations.forEach(assoc => {
              const option = document.createElement('option');
              option.value = assoc.id_association;
              option.textContent = assoc.name;
              assocSelect.appendChild(option);
            });
            
            if (filteredAssociations.length === 0) {
              assocSelect.innerHTML = '<option value="">No associations available for this category</option>';
              assocSelect.disabled = true;
            }
          }
          
          // Also update nearest associations on map if location is selected
          if (selectedLocation) {
            updateNearestAssociations();
          }
        });
      }
    });
    
    // Form validation
    function validateForm() {
      const locationInput = document.getElementById('loc');
      if (!locationInput) {
        alert('Erreur: Le formulaire n\'est pas prêt. / Error: Form is not ready.');
        return false;
      }
      const location = locationInput.value;
      if (!location || location.trim() === '' || /^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/.test(location.trim())) {
        alert('Veuillez sélectionner un emplacement sur la carte avant de soumettre. / Please select a location on the map before submitting.');
        return false;
      }
      return true;
    }
    
    async function updateNearestAssociations() {
      if (!selectedLocation) return;
      
      const selectedCategory = document.getElementById('catg').value;
      let filteredAssociations = allAssociations;
      
      // Filter by category if selected
      if (selectedCategory) {
        filteredAssociations = allAssociations.filter(a => a.id_category == selectedCategory);
      }
      
      // Geocode associations and calculate distances
      const associationsWithDistance = [];
      for (const assoc of filteredAssociations) {
        const coords = await getLocationCoordinates(assoc.location);
        if (coords) {
          const distance = calculateDistance(
            selectedLocation.lat, 
            selectedLocation.lng, 
            coords.lat, 
            coords.lng
          );
          associationsWithDistance.push({
            ...assoc,
            distance: distance,
            lat: coords.lat,
            lng: coords.lng
          });
        }
      }
      
      // Sort by distance
      associationsWithDistance.sort((a, b) => a.distance - b.distance);
      
      // Display nearest associations (top 5)
      const nearest = associationsWithDistance.slice(0, 5);
      const container = document.getElementById('associations-list');
      const nearestDiv = document.getElementById('nearest-associations');
      
      if (nearest.length > 0) {
        container.innerHTML = '';
        nearest.forEach(assoc => {
          const categoryName = allCategories.find(c => c.id_category == assoc.id_category)?.name || 'Unknown';
          const item = document.createElement('div');
          item.className = 'association-item';
          item.innerHTML = `
            <strong>${assoc.name}</strong><br>
            <small>Distance: ${assoc.distance.toFixed(2)} km</small><br>
            <small>Location: ${assoc.location}</small><br>
            <small>Category: ${categoryName}</small><br>
            <small>Phone: ${assoc.phone}</small>
          `;
          container.appendChild(item);
        });
        nearestDiv.style.display = 'block';
      } else {
        nearestDiv.style.display = 'none';
      }
    }
  </script>
  <script src="socialcase.js"></script>


  </body>

</html>
