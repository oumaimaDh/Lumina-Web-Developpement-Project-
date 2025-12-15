<!DOCTYPE html>
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
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
      .button-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%; /* Ensure it takes full height to center vertically if needed */
      }
      .action-button {
        width: 180px; /* Fixed width for consistency */
        text-align: center;
      }
    </style>
<!--

TemplateMo 582 Tale SEO Agency

https://templatemo.com/tm-582-tale-seo-agency

-->
  </head>

<body>



  <!-- ***** Pre-Header Area Start ***** -->

  <!-- ***** Pre-Header Area End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class=" header-sticky">
    <nav class="navbar">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Lumina Logo">
        </div>
        <ul class="nav-links">
            <li><a href="../home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="socialcase.php"><i class="fas fa-hands-helping"></i> Social Cases</a></li>
            <li class="dropdown">
                <a href="jobdepartment.php" class="dropdown-toggle"><i class="fas fa-briefcase"></i> Job Department <i class="fas fa-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="jobdepartment.php#employment-service"><i class="fas fa-search"></i> Job Search</a></li>
                    <li><a href="jobdepartment.php#associations"><i class="fas fa-users"></i> Associations</a></li>
                    <li><a href="jobdepartment.php#application-tracking"><i class="fas fa-tasks"></i> Application Tracking</a></li>
                </ul>
            </li>
            <li><a href="events.php"><i class="fas fa-calendar-alt"></i> Our Events</a></li>
            <li><a href="forum.php"><i class="fas fa-comments"></i> Forum</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle"><i class="fas fa-layer-group"></i> Pages <i class="fas fa-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="faqs.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
                </ul>
            </li>
        </ul>
        <div class="nav-actions">
            <button class="theme-toggle" id="theme-toggle">
                <i class="fas fa-sun"></i>
            </button>
            <button class="btn btn-outline"><i class="fas fa-sign-in-alt"></i> Login</button>
            <button class="btn btn-primary"><i class="fas fa-user-plus"></i> Sign Up</button>
        </div>
    </nav>
  </header>
  <!-- ***** Header Area End ***** -->

 




  
 
  




  
  <style>
    .associations-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      padding: 20px;
      justify-content: center;
    }
    .association-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: calc(33% - 20px);
      box-sizing: border-box;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .association-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .card-header {
      position: relative;
      height: 120px;
      overflow: hidden;
    }
    .association-banner {
      width: 100%;
      height: 100%;
    }
    .banner-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .association-logo {
      position: absolute;
      bottom: -30px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 4px solid white;
      overflow: hidden;
      background: white;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .logo-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .card-body {
      padding: 50px 20px 20px 20px;
      text-align: center;
    }
    .association-type {
      font-size: 12px;
      color: #666;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 5px;
    }
    .association-name {
      margin: 10px 0;
      color: #333;
      font-size: 18px;
      font-weight: 600;
    }
    .association-rating {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      margin: 10px 0;
    }
    .stars {
      color: #FFD700;
    }
    .rating-value {
      font-weight: 600;
      color: #333;
      margin-left: 5px;
    }
    .association-meta {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin: 15px 0;
      font-size: 14px;
      color: #666;
    }
    .meta-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .meta-item i {
      color: #007bff;
    }
    .association-info {
      margin: 8px 0;
      font-size: 14px;
      color: #555;
      text-align: left;
    }
    #category-map {
      height: 500px;
      width: 100%;
      border-radius: 8px;
      margin: 20px 0;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      position: relative;
    }
    .map-section {
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin: 20px 0;
    }
    .map-loading {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(255, 255, 255, 0.95);
      padding: 15px 25px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .map-loading.hidden {
      display: none;
    }
    .spinner {
      border: 3px solid #f3f3f3;
      border-top: 3px solid #FFEB3B;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
  <br><br><br><br><br>
  <main class="main-content">
    <div class="container">
      <div class="map-section">
        <h2>Map of Associations in this Category</h2>
        <div id="category-map">
          <div class="map-loading" id="map-loading">
            <div class="spinner"></div>
            <span>Loading association locations...</span>
          </div>
        </div>
      </div>
    </div>
    <section class="associations-list-section">
      <h2>Associations in this Category</h2>
      <div class="associations-container">
        <?php
        // Define base path
        $basePath = realpath(dirname(__DIR__) . '/..');
        if (!$basePath) {
            $basePath = dirname(dirname(__DIR__));
        }
        require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/config.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/socialcasecontroller.php';

        $socialCaseController = new SocialCaseController();
        $associations = $socialCaseController->getAllAssociations();
        $categories = $socialCaseController->getAllCategories();

        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category['id_category']] = $category['name'];
        }

        $filteredAssociations = array_filter($associations, function($association) {
            return $association['id_category'] == 1;
        });

        // Helper functions for social case categories
        function getLogoBySocialCategory($categoryId) {
            $logos = [
                1 => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=80&h=80&fit=crop&crop=center', // Financial & Poverty
                2 => 'https://images.unsplash.com/photo-1551076805-e1869033e561?w=80&h=80&fit=crop&crop=center', // Health & Medical
                3 => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=80&h=80&fit=crop&crop=center', // Domestic & Family
                4 => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=80&h=80&fit=crop&crop=center' // Animal Welfare
            ];
            return $logos[$categoryId] ?? 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=80&h=80&fit=crop&crop=center';
        }

        function getBannerBySocialCategory($categoryId) {
            $banners = [
                1 => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=400&h=120&fit=crop&crop=center', // Financial & Poverty
                2 => 'https://i.pinimg.com/736x/46/3e/e8/463ee85d7aecb285c201b3ef94549e03.jpg', // Health & Medical
                3 => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=400&h=120&fit=crop&crop=center', // Domestic & Family
                4 => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=400&h=120&fit=crop&crop=center' // Animal Welfare
            ];
            return $banners[$categoryId] ?? 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=400&h=120&fit=crop&crop=center';
        }

        // Convert filtered associations to JSON for JavaScript
        $associationsJson = json_encode(array_values($filteredAssociations));

        if (!empty($filteredAssociations)) {
            foreach ($filteredAssociations as $association) {
                $categoryName = $categoryMap[$association['id_category']] ?? 'Unknown';
                $logo = getLogoBySocialCategory($association['id_category']);
                $banner = getBannerBySocialCategory($association['id_category']);
                $rating = isset($association['rating']) ? floatval($association['rating']) : 4.0;
                
                echo '<div class="association-card">';
                echo '<div class="card-header">';
                echo '<div class="association-banner"><img src="' . htmlspecialchars($banner) . '" alt="Banner" class="banner-img"></div>';
                echo '<div class="association-logo"><img src="' . htmlspecialchars($logo) . '" alt="Logo" class="logo-img"></div>';
                echo '</div>';
                echo '<div class="card-body">';
                echo '<div class="association-type">' . htmlspecialchars($categoryName) . '</div>';
                echo '<h3 class="association-name">' . htmlspecialchars($association['name']) . '</h3>';
                echo '<div class="association-rating">';
                echo '<div class="stars">';
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= floor($rating)) {
                        echo '<i class="fas fa-star"></i>';
                    } elseif ($i == ceil($rating) && $rating % 1 >= 0.5) {
                        echo '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        echo '<i class="far fa-star"></i>';
                    }
                }
                echo '</div>';
                echo '<span class="rating-value">' . number_format($rating, 1) . '</span>';
                echo '</div>';
                echo '<div class="association-meta">';
                echo '<span class="meta-item"><i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($association['location']) . '</span>';
                echo '<span class="meta-item"><i class="fas fa-phone"></i> ' . htmlspecialchars($association['phone']) . '</span>';
                echo '</div>';
                echo '<p class="association-info"><strong>Email:</strong> ' . htmlspecialchars($association['email']) . '</p>';
                echo '<p class="association-info"><strong>Availability:</strong> ' . ($association['availabelity'] ? '<span style="color: green;">Available</span>' : '<span style="color: red;">Not Available</span>') . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No associations found in this category.</p>';
        }
        ?>
      </div>
    </section>
  </main>
 <footer>
    <div class="container">
      <div class="col-lg-12">
        <p>Copyright © 2036<a href="#">Lumina Project</a>All rights reserved
        
        <br>
Design:<a href="https://templatemo.com" target="_blank">Lumina Team</a></p>
      </div>
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
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="assets/js/map-utils.js"></script>
  <script>
    // Initialize map for category page
    document.addEventListener('DOMContentLoaded', async function() {
      const associations = <?php echo $associationsJson; ?>;
      const loadingDiv = document.getElementById('map-loading');
      const map = initTunisiaMap('category-map', 33.8869, 10.1775, 7);
      
      if (!associations || associations.length === 0) {
        loadingDiv.innerHTML = '<span>No associations found in this category.</span>';
        setTimeout(() => loadingDiv.classList.add('hidden'), 2000);
        return;
      }
      
      // Geocode and add markers for each association
      const markers = [];
      const failed = [];
      let processed = 0;
      
      console.log('Total associations to process:', associations.length);
      console.log('Associations data:', associations);
      
      // Process associations with a small delay to avoid rate limiting
      for (let i = 0; i < associations.length; i++) {
        const assoc = associations[i];
        try {
          // Update loading message
          loadingDiv.innerHTML = `
            <div class="spinner"></div>
            <span>Loading locations... (${i + 1}/${associations.length})</span>
          `;
          
          // Check if location exists
          if (!assoc.location || assoc.location.trim() === '') {
            console.warn(`Association "${assoc.name}" has no location`);
            failed.push({name: assoc.name, reason: 'No location provided'});
            continue;
          }
          
          const coords = await getLocationCoordinates(assoc.location);
          if (coords && !isNaN(coords.lat) && !isNaN(coords.lng)) {
            const popupContent = `
              <strong>${assoc.name}</strong><br>
              Phone: ${assoc.phone}<br>
              Location: ${assoc.location}<br>
              Email: ${assoc.email}<br>
              Availability: ${assoc.availabelity ? 'Available' : 'Not Available'}
            `;
            const marker = addAssociationMarker(map, coords.lat, coords.lng, assoc.name, popupContent);
            markers.push(marker);
            processed++;
            console.log(`✓ Added marker for: ${assoc.name} at ${coords.lat}, ${coords.lng}`);
          } else {
            console.warn(`✗ Failed to get coordinates for: ${assoc.name} (Location: "${assoc.location}")`);
            failed.push({name: assoc.name, location: assoc.location, reason: 'Could not geocode location'});
          }
          
          // Small delay between requests to respect rate limits (only for geocoding, not coordinate parsing)
          if (i < associations.length - 1) {
            await new Promise(resolve => setTimeout(resolve, 300));
          }
        } catch (error) {
          console.error('Error processing association:', assoc.name, error);
          failed.push({name: assoc.name, location: assoc.location, reason: error.message});
        }
      }
      
      // Show summary
      console.log(`\n=== Summary ===`);
      console.log(`Total associations: ${associations.length}`);
      console.log(`Successfully added: ${markers.length}`);
      console.log(`Failed: ${failed.length}`);
      if (failed.length > 0) {
        console.log('Failed associations:', failed);
      }
      
      // Hide loading indicator and show summary
      if (markers.length > 0) {
        loadingDiv.innerHTML = `
          <div style="color: #4CAF50;">✓ Loaded ${markers.length} of ${associations.length} associations</div>
        `;
        setTimeout(() => loadingDiv.classList.add('hidden'), 2000);
        
        // Fit map to show all markers
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
      } else {
        loadingDiv.innerHTML = `
          <div style="color: #f44336;">
            <strong>No markers could be loaded.</strong><br>
            <small>Failed: ${failed.length} associations</small><br>
            <small>Please check association locations in the database.</small>
          </div>
        `;
        loadingDiv.classList.remove('hidden');
      }
      
      // Log failed associations for debugging
      if (failed.length > 0 && markers.length > 0) {
        console.warn('Some associations could not be displayed:', failed);
      }
    });
  </script>
  <script src="socialcase.js"></script>


  </body>

</html>

