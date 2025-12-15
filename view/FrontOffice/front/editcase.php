<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
require_once $basePath . DIRECTORY_SEPARATOR . 'config.php';
require_once $basePath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'socialcasecontroller.php';
require_once $basePath . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'socialcasemodel.php';

$socialCaseController = new SocialCaseController();
$socialCase = null;
$categories = [];
$associations = [];

try {
    $pdo = config::getConnexion();

    // Fetch categories
    $stmt_categories = $pdo->query("SELECT id_category, name FROM category");
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

    // Fetch associations
    $stmt_associations = $pdo->query("SELECT id_association, name FROM association");
    $associations = $stmt_associations->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Database error: ' . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id_case = (int)$_GET['id'];
    $socialCase = $socialCaseController->getSocialCaseById($id_case);
    
    if (!$socialCase) {
        header('Location: listcases.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_case'])) {
    $id_case = (int)$_POST['id_case'];
    
    // Get the case data again for the update
    $socialCaseData = $socialCaseController->getSocialCaseById($id_case);
    
    if (is_array($socialCaseData)) {
        // If it's an array, create object from array data
        $updatedSocialCase = new SocialCase(
            $id_case,
            $_POST['name'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['desc'], // description
            $_POST['loc'],  // location
            $socialCaseData['submited_date'] ?? date('Y-m-d'), // submited_date
            date('Y-m-d'), // updated_date
            'Pending',     // status
            (int)$_POST['catg'], // id_category
            (int)$_POST['assoc'] // id_association
        );
    } else {
        // If it's an object, use object methods
        $updatedSocialCase = new SocialCase(
            $id_case,
            $_POST['name'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['desc'], // description
            $_POST['loc'],  // location
            $socialCaseData->getSubmitedDate(), // submited_date
            date('Y-m-d'), // updated_date
            'Pending',     // status
            (int)$_POST['catg'], // id_category
            (int)$_POST['assoc'] // id_association
        );
    }
    
    $socialCaseController->updateSocialCase($updatedSocialCase);
    header('Location: listcases.php');
    exit;
}
?>
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
        height: 100%;
      }
      .action-button {
        width: 180px;
        text-align: center;
      }
      .form-container {
        background-color: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-radius: 15px;
        padding: 40px;
        margin-top: 30px;
      }
      .form-field {
        margin-bottom: 25px;
      }
      .form-field input,
      .form-field select,
      .form-field textarea {
        width: 100%;
        padding: 12px 20px;
        border: 1px solid #ddd;
        border-radius: 25px;
        background-color: #f6e7fe;
        font-size: 14px;
      }
      .form-field textarea {
        height: 120px;
        resize: vertical;
      }
      .submit-btn {
        background: linear-gradient(45deg, #6a11cb, #2575fc);
        border: none;
        border-radius: 25px;
        padding: 12px 40px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
      }
      .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
           <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
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
          <div class="form-container">
            <div class="row">
              <div class="col-lg-12 text-center mb-5">
                <div class="section-heading">
                  <h2><em>Edit Your</em> Case</h2>
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-lg-8">
                <?php if ($socialCase): ?>
                <form id="contact-form" action="" method="post">
                  <input type="hidden" name="id_case" value="<?php 
                      if (is_array($socialCase)) {
                          echo $socialCase['id_case'];
                      } else {
                          echo $socialCase->getIdCase();
                      }
                  ?>">
                  
                  <div class="form-field">
                    <input type="text" name="name" id="name" placeholder="Your Name..." autocomplete="on" value="<?php 
                        if (is_array($socialCase)) {
                            echo htmlspecialchars($socialCase['name'] ?? '');
                        } else {
                            echo htmlspecialchars($socialCase->getName());
                        }
                    ?>" required>
                  </div>
                  
                  <div class="form-field">
                    <input type="tel" name="phone" id="phone" placeholder="Your phone number..." autocomplete="on" value="<?php 
                        if (is_array($socialCase)) {
                            echo htmlspecialchars($socialCase['phone'] ?? '');
                        } else {
                            echo htmlspecialchars($socialCase->getPhone());
                        }
                    ?>" required>
                  </div>
                  
                  <div class="form-field">
                    <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your E-mail..." value="<?php 
                        if (is_array($socialCase)) {
                            echo htmlspecialchars($socialCase['email'] ?? '');
                        } else {
                            echo htmlspecialchars($socialCase->getEmail());
                        }
                    ?>" required>
                  </div>
                  
                  <div class="form-field">
                    <select name="catg" id="catg" required>
                      <option value="">Choose Your Problem Category</option>
                      <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id_category']; ?>" 
                          <?php 
                              if (is_array($socialCase)) {
                                  echo ($socialCase['id_category'] == $category['id_category']) ? 'selected' : '';
                              } else {
                                  echo ($socialCase->getIdCategory() == $category['id_category']) ? 'selected' : '';
                              }
                          ?>>
                          <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  
                  <div class="form-field">
                    <select name="assoc" id="assoc" required>
                      <option value="">Choose an Association</option>
                      <?php foreach ($associations as $association): ?>
                        <option value="<?php echo $association['id_association']; ?>" 
                          <?php 
                              if (is_array($socialCase)) {
                                  echo ($socialCase['id_association'] == $association['id_association']) ? 'selected' : '';
                              } else {
                                  echo ($socialCase->getIdAssociation() == $association['id_association']) ? 'selected' : '';
                              }
                          ?>>
                          <?php echo htmlspecialchars($association['name']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  
                  <div class="form-field">
                    <textarea name="desc" id="desc" placeholder="Description..." required><?php 
                        if (is_array($socialCase)) {
                            echo htmlspecialchars($socialCase['description'] ?? '');
                        } else {
                            echo htmlspecialchars($socialCase->getDescription());
                        }
                    ?></textarea>
                  </div>
                  
                  <div class="form-field">
                    <input type="text" name="loc" id="loc" placeholder="Location..." value="<?php 
                        if (is_array($socialCase)) {
                            echo htmlspecialchars($socialCase['location'] ?? '');
                        } else {
                            echo htmlspecialchars($socialCase->getLocation());
                        }
                    ?>" required>
                  </div>
                  
                  <div class="form-field text-center">
                    <button type="submit" class="submit-btn">Update Case</button>
                  </div>
                </form>
                
                <div class="text-center mt-4">
                  <a href="listcases.php" class="btn btn-outline-secondary">← Back to Cases List</a>
                </div>
                
                <?php else: ?>
                <div class="alert alert-danger text-center">
                  <strong>Error:</strong> Social case not found or invalid ID.
                </div>
                <div class="text-center mt-4">
                  <a href="listcases.php" class="btn btn-outline-secondary">← Back to Cases List</a>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

  </body>
</html>