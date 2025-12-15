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
      .filter-controls {
          display: flex;
          gap: 10px;
          margin-bottom: 20px;
          align-items: center;
          flex-wrap: wrap;
      }

      .filter-controls input[type="text"],
      .filter-controls select {
          padding: 8px;
          border: 1px solid #ccc;
          border-radius: 4px;
          font-size: 14px;
          flex-grow: 1;
          min-width: 150px;
      }

      .filter-controls button {
          padding: 8px 15px;
          border-radius: 4px;
          border: none;
          background-color: #007bff;
          color: white;
          cursor: pointer;
          font-size: 14px;
      }

      .filter-controls button:hover {
          background-color: #0056b3;
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
  <header class="header-area header-sticky">
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

 




  
  <div class="contact-us section" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="white-box" style="background-color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 15px; padding: 20px;">
            <!-- Content for the white box goes here -->
            <h2>Your Case Details</h2>
            <div class="filter-controls">
                <input type="text" id="search-input" placeholder="Search by name, email, or description...">
                <select id="sort-date-filter">
                    <option value="">Sort by Date</option>
                    <option value="asc">Oldest First</option>
                    <option value="desc">Newest First</option>
                </select>
                <select id="status-filter">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <button id="filter-button" class="btn btn-primary">Filter</button>
            </div>
            <div class="table-responsive">
                  <?php
                  // Define base path
                  $basePath = realpath(dirname(__DIR__) . '/..');
                  if (!$basePath) {
                      $basePath = dirname(dirname(__DIR__));
                  }
                  require_once $basePath . DIRECTORY_SEPARATOR . 'config.php';
                  require_once $basePath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'socialcasecontroller.php';

                  $socialCaseController = new SocialCaseController();
                  $socialCases = $socialCaseController->getAllSocialCases();

                  $search = $_GET['search'] ?? '';
                  $sort_date = $_GET['sort_date'] ?? '';
                  $status = $_GET['status'] ?? '';

                  $filteredCases = [];
                  foreach ($socialCases as $case) {
                      $matchSearch = true;
                      if ($search) {
                          $searchLower = strtolower($search);
                          if (
                              stripos($case['name'], $searchLower) === false &&
                              stripos($case['email'], $searchLower) === false &&
                              stripos($case['description'], $searchLower) === false
                          ) {
                              $matchSearch = false;
                          }
                      }

                      $matchStatus = true;
                      if ($status && $case['status'] !== $status) {
                          $matchStatus = false;
                      }

                      if ($matchSearch && $matchStatus) {
                          $filteredCases[] = $case;
                      }
                  }

                  if ($sort_date) {
                      usort($filteredCases, function($a, $b) use ($sort_date) {
                          $dateA = strtotime($a['submited_date']);
                          $dateB = strtotime($b['submited_date']);
                          if ($sort_date === 'asc') {
                              return $dateA - $dateB;
                          } else {
                              return $dateB - $dateA;
                          }
                      });
                  }

                  foreach ($filteredCases as $case) {
                  ?>
                  <div class="case-card mb-4 p-4 border rounded shadow-sm">
                    <h4 class="mb-3">Case ID: <?php echo htmlspecialchars($case['id_case']); ?></h4>
                    <table class="table table-bordered table-sm">
                      <tbody>
                        <tr><th>Name</th><td><?php echo htmlspecialchars($case['name']); ?></td></tr>
                        <tr><th>Phone</th><td><?php echo htmlspecialchars($case['phone']); ?></td></tr>
                        <tr><th>Email</th><td><?php echo htmlspecialchars($case['email']); ?></td></tr>
                        <tr><th>Description</th><td><?php echo htmlspecialchars($case['description']); ?></td></tr>
                        <tr><th>Submitted Date</th><td><?php echo htmlspecialchars($case['submited_date']); ?></td></tr>
                        <tr><th>Updated Date</th><td><?php echo htmlspecialchars($case['updated_date']); ?></td></tr>
                        <tr><th>Category</th><td><?php echo htmlspecialchars($case['category_name']); ?></td></tr>
                        <tr><th>Association</th><td><?php echo htmlspecialchars($case['association_name']); ?></td></tr>
                        <tr><th>Status</th><td><?php echo htmlspecialchars($case['status']); ?></td></tr>
                      </tbody>
                    </table>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                      <a href="editcase.php?id=<?php echo $case['id_case']; ?>" class="btn btn-primary action-button">Edit</a>
                      <button type="button" class="btn btn-danger action-button" onclick="confirmDelete(<?php echo $case['id_case']; ?>)">Delete</button>
                    </div>
                  </div>
                  <?php
                  }
                  ?>

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
  <script src="socialcase.js"></script>
  <script>
    function confirmDelete(id_case) {
      if (confirm("Are you sure you want to delete this case?")) {
        window.location.href = 'deletecase.php?id=' + id_case;
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            document.getElementById('search-input').value = urlParams.get('search');
        }
        if (urlParams.has('sort_date')) {
            document.getElementById('sort-date-filter').value = urlParams.get('sort_date');
        }
        if (urlParams.has('status')) {
            document.getElementById('status-filter').value = urlParams.get('status');
        }
    });

    document.getElementById('filter-button').addEventListener('click', applyFilters);
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
    document.getElementById('sort-date-filter').addEventListener('change', applyFilters);
    document.getElementById('status-filter').addEventListener('change', applyFilters);

    function applyFilters() {
        const search = document.getElementById('search-input').value;
        const sort_date = document.getElementById('sort-date-filter').value;
        const status = document.getElementById('status-filter').value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('sort_date', sort_date);
        url.searchParams.set('status', status);
        window.location.href = url.toString();
    }
  </script>


  </body>

</html>
