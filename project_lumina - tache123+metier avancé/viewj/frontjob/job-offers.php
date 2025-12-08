 <!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Offers - Lumina</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/job-offers.css?v=validation2">
    <!-- Add these to the head section -->
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
   <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>

<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// TRAITEMENT DE LA SOUMISSION D'APPLICATION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_application') {
    // Inclure les contrôleurs
    require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\OfferController.php';
    require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\AssociationController.php';
    require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\ApplicationController.php';

   
    try {
        // Create uploads directory if it doesn't exist
        $uploadDir = 'C:\xampp\htdocs\project_lumina - tache1+tache2 +tache3/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Handle file upload
        $cv_filename = '';
        if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
            $cvFile = $_FILES['cv_file'];
            $fileExtension = pathinfo($cvFile['name'], PATHINFO_EXTENSION);
            $allowedTypes = ['pdf', 'doc', 'docx'];
            
            if (in_array(strtolower($fileExtension), $allowedTypes)) {
                $filename = 'cv_' . time() . '_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $filename;
                
                if (move_uploaded_file($cvFile['tmp_name'], $filePath)) {
                    $cv_filename = $filename;
                }
            }
        }

        // Préparer les données de l'application
        $applicationData = [
            'offer_id' => $_POST['offer_id'],
            'association_id' => $_POST['association_id'],
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'profession' => $_POST['profession'],
            'desired_salary' => $_POST['desired_salary'],
            'preferred_location' => $_POST['preferred_location'],
            'skills' => $_POST['skills'],
            'experience' => $_POST['experience'],
            'experience_level' => $_POST['experience_level'],
            'cv_filename' => $cv_filename,
            'cover_letter' => $_POST['cover_letter'] ?? '',
            'status' => 'submitted'
        ];

        // Ajouter l'application
        $applicationController = new ApplicationController();
        $success = $applicationController->addApplication($applicationData);

        if ($success) {
            echo "success";
        } else {
            echo "error";
        }
        
        exit; // Arrêter l'exécution après le traitement

    } catch (Exception $e) {
        echo "error: " . $e->getMessage();
        exit;
    }
}

// Le reste de votre code PHP existant continue ici...
$associationId = isset($_GET['association']) ? intval($_GET['association']) : 1;

// Inclure les contrôleurs
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\OfferController.php';
    require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\AssociationController.php';
    require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\ApplicationController.php';

   
// Initialiser les contrôleurs
$offerController = new OfferController();
$associationController = new AssociationController();

// Récupérer l'ID de l'association depuis l'URL
$associationId = isset($_GET['association']) ? intval($_GET['association']) : 1;

// Récupérer les données
$association = $associationController->getAssociationById($associationId);
$allOffers = $offerController->getOffers();

// Filtrer les offres pour cette association
$offers = array_filter($allOffers, function($offer) use ($associationId) {
    return $offer['association_id'] == $associationId;
});

// Si l'association n'existe pas, rediriger
if (!$association) {
    header('Location: jobdepartment.php');
    exit;
}

// Fonctions helper pour formater les données
function getLogoByCategory($category) {
    $logos = [
        'health' => 'https://images.unsplash.com/photo-1551076805-e1869033e561?w=80&h=80&fit=crop&crop=center',
        'restaurants' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=80&h=80&fit=crop&crop=center',
        'education' => 'https://i.pinimg.com/736x/96/71/87/9671871b39fbaf71c4f65cefbbf8da3a.jpg',
        'construction' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=80&h=80&fit=crop&crop=center',
        'commerce' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=80&h=80&fit=crop&crop=center',
        'other' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=80&h=80&fit=crop&crop=center'
    ];
    return $logos[$category] ?? $logos['other'];
}

function getBannerByCategory($category) {
    $banners = [
        'health' => 'https://i.pinimg.com/736x/46/3e/e8/463ee85d7aecb285c201b3ef94549e03.jpg',
        'restaurants' => 'https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=400&h=120&fit=crop&crop=center',
        'education' => 'https://i.pinimg.com/736x/8a/b0/2d/8ab02d16fe2c7ff0c84c186b4198f9ac.jpg',
        'construction' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&h=120&fit=crop&crop=center',
        'commerce' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=400&h=120&fit=crop&crop=center',
        'other' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=400&h=120&fit=crop&crop=center'
    ];
    return $banners[$category] ?? $banners['other'];
}

function extractRating($ratingText) {
    if (preg_match('/(\d+\.\d+)/', $ratingText, $matches)) {
        return floatval($matches[1]);
    }
    return 4.0;
}

function getSectorByCategory($category) {
    $sectors = [
        'health' => 'Healthcare Services',
        'restaurants' => 'Hospitality & Food Services',
        'education' => 'Education & Training',
        'construction' => 'Construction & Engineering',
        'commerce' => 'Retail & Commerce',
        'other' => 'Various Services'
    ];
    return $sectors[$category] ?? 'Professional Services';
}

function getTypeByCategory($category) {
    $types = [
        'health' => 'Hospital & Health',
        'restaurants' => 'Café & Restaurant',
        'education' => 'Education Center',
        'construction' => 'Construction Company',
        'commerce' => 'Retail Store',
        'other' => 'Service Company'
    ];
    return $types[$category] ?? 'Company';
}

// Préparer les données pour JavaScript
$offersData = [];
foreach ($offers as $offer) {
    $offersData[] = [
        'id' => $offer['id'],
        'title' => $offer['title'],
        'location' => $offer['location'],
        'salary_min' => $offer['salary_min'],
        'salary_max' => $offer['salary_max'],
        'description' => $offer['description'],
        'contract_types' => json_decode($offer['contract_types'], true) ?? [],
        'skills' => json_decode($offer['skills'], true) ?? [],
        'expiration_date' => $offer['expiration_date'],
        'status' => $offer['status']
    ];
}

$associationData = [
    'id' => $association['id'],
    'name' => $association['name'],
    'category' => $association['category'],
    'location' => $association['location'],
    'description' => $association['description'],
    'rating' => extractRating($association['rating']),
    'active_offers' => $association['active_offers'],
    'logo' => getLogoByCategory($association['category']),
    'banner' => getBannerByCategory($association['category']),
    'sector' => getSectorByCategory($association['category']),
    'type' => getTypeByCategory($association['category'])
];
?>

    <!-- BACK BUTTON -->
    <div class="back-container">
        <button class="back-btn" onclick="window.location.href='jobdepartment.php'">
            <i class="fas fa-arrow-left"></i>
            Back to Job Search
        </button>
        <div class="theme-toggle-container">
            <button class="theme-toggle" id="theme-toggle">
                <i class="fas fa-sun"></i>
            </button>
        </div>
    </div>

    <!-- HEADER SECTION -->
    <section class="offers-header">
        <div class="header-content">
            <!-- Company Information -->
            <div class="company-info">
                <div class="company-card">
                    <div class="company-logo">
                        <img src="<?php echo htmlspecialchars($associationData['logo']); ?>" alt="<?php echo htmlspecialchars($associationData['name']); ?>">
                    </div>
                    <h1 class="company-name"><?php echo htmlspecialchars($associationData['name']); ?></h1>
                    <p class="company-tagline">Discover amazing career opportunities</p>
                    
                    <div class="company-stats">
                        <div class="stat-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="stat-content">
                                <span class="stat-value"><?php echo htmlspecialchars($associationData['location']); ?></span>
                                <span class="stat-label">Location</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-star"></i>
                            <div class="stat-content">
                                <span class="stat-value"><?php echo number_format($associationData['rating'], 1); ?>/5</span>
                                <span class="stat-label"><?php echo floor($associationData['rating'] * 50); ?> reviews</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-briefcase"></i>
                            <div class="stat-content">
                                <span class="stat-value"><?php echo count($offers); ?></span>
                                <span class="stat-label">Open positions</span>
                            </div>
                        </div>
                    </div>

                    <div class="company-description">
                        <p><?php echo htmlspecialchars($associationData['description']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="header-map">
                <div class="map-container-header">
                    <div id="association-map"></div>
                </div>
                <div class="location-details-header">
                    <div class="location-info-header">
                        <h4><i class="fas fa-map-marker-alt"></i> Visit Us</h4>
                        
                        <div class="location-meta">
                            <div class="meta-item-header">
                                <i class="fas fa-location-arrow"></i>
                                <div class="meta-content">
                                    <h5>Address</h5>
                                    <p id="location-address"><?php echo htmlspecialchars($associationData['location']); ?></p>
                                </div>
                            </div>
                            
                            <div class="meta-item-header">
                                <i class="fas fa-clock"></i>
                                <div class="meta-content">
                                    <h5>Business Hours</h5>
                                    <p id="business-hours">Mon-Fri: 8AM-6PM</p>
                                </div>
                            </div>
                            
                            <div class="meta-item-header">
                                <i class="fas fa-phone"></i>
                                <div class="meta-content">
                                    <h5>Contact</h5>
                                    <p id="contact-info">+216 70 123 456</p>
                                </div>
                            </div>
                            
                            <div class="meta-item-header">
                                <i class="fas fa-subway"></i>
                                <div class="meta-content">
                                    <h5>Transport</h5>
                                    <p id="transportation">Metro & Bus</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="header-contact-actions">
                            <a href="#" class="header-contact-btn primary" id="directions-btn">
                                <i class="fas fa-route"></i> Get Directions
                            </a>
                            <a href="#" class="header-contact-btn" id="contact-btn">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-animation"></div>
    </section>

    <!-- JOB OFFERS GRID -->
    <section class="offers-grid">
        <div class="container">
            <div class="section-header">
                <h2>Available Positions</h2>
                <p>Find the perfect role that matches your skills and aspirations</p>
            </div>

            <div class="filters-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search positions..." id="search-offers">
                </div>
                <div class="filter-options">
                    <select id="filter-salary">
                        <option value="">All Salaries</option>
                        <option value="1000-1500">1000-1500 DT</option>
                        <option value="1500-2000">1500-2000 DT</option>
                        <option value="2000+">2000+ DT</option>
                    </select>
                    <select id="filter-location">
                        <option value="">All Locations</option>
                        <option value="tunis">Tunis</option>
                        <option value="sfax">Sfax</option>
                        <option value="sousse">Sousse</option>
                        <option value="remote">Remote</option>
                    </select>
                    <select id="filter-contract">
                        <option value="">All Contract Types</option>
                        <option value="cdi">CDI</option>
                        <option value="cdd">CDD</option>
                        <option value="freelance">Freelance</option>
                        <option value="stage">Stage</option>
                    </select>
                </div>
            </div>

            <div class="offers-container" id="offers-container">
                <?php if (empty($offers)): ?>
                    <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                        <i class="fas fa-briefcase" style="font-size: 3rem; color: #aaa; margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--text); margin-bottom: 0.5rem;">No Job Offers Available</h3>
                        <p style="color: #aaa;">Check back later for new opportunities at this company.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($offers as $offer): ?>
                    <div class="offer-card">
                        <div class="offer-header">
                            <div class="offer-main">
                                <h3 class="offer-title"><?php echo htmlspecialchars($offer['title']); ?></h3>
                                <div class="offer-salary"><?php echo $offer['salary_min']; ?>-<?php echo $offer['salary_max']; ?> DT/month</div>
                            </div>
                            <div class="offer-profession"><?php echo htmlspecialchars($offer['title']); ?></div>
                        </div>
                        
                        <div class="offer-meta">
                            <span class="meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($offer['location']); ?></span>
                            <span class="meta-item"><i class="fas fa-file-contract"></i> <?php echo implode(', ', json_decode($offer['contract_types'], true) ?? []); ?></span>
                            <span class="meta-item"><i class="fas fa-clock"></i> <?php echo date('M j, Y', strtotime($offer['expiration_date'])); ?></span>
                        </div>
                        
                        <p class="offer-description"><?php echo htmlspecialchars($offer['description']); ?></p>
                        
                        <div class="offer-skills">
                            <?php 
                            $skills = json_decode($offer['skills'], true) ?? [];
                            foreach ($skills as $skill): 
                            ?>
                                <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="offer-footer">
                            <div class="offer-deadline">
                                <i class="fas fa-hourglass-end"></i>
                                Apply before <?php echo date('M j, Y', strtotime($offer['expiration_date'])); ?>
                            </div>
                            <button class="btn btn-primary apply-btn" data-offer-id="<?php echo $offer['id']; ?>">
                                <i class="fas fa-paper-plane"></i> Postuler
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- APPLICATION MODAL -->
<div class="modal-overlay" id="application-modal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Application Form</h2>
            <button class="close-modal" id="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form class="application-form" id="application-form" enctype="multipart/form-data">
            <input type="hidden" id="selected-offer-id" name="offer_id">
            <input type="hidden" name="association_id" value="<?php echo $associationId; ?>">
            
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name" name="full_name" placeholder="Your first and last name">
                        <small class="field-msg" id="full-name-msg"></small>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" placeholder="+216 XXXXXXXX">
                        <small class="field-msg" id="phone-msg"></small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="your@email.com">
                        <small class="field-msg" id="email-msg"></small>
                    </div>
                    <div class="form-group">
                        <label for="profession">Profession/Desired Position</label>
                        <input type="text" id="profession" name="profession" placeholder="Developer, Accountant...">
                        <small class="field-msg" id="profession-msg"></small>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-briefcase"></i> Job Preferences</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="desired-salary">Desired Salary [DT/month]</label>
                        <input type="text" id="desired-salary" name="desired_salary" placeholder="Ex: 1800 (≥ 300)">
                        <small class="field-msg" id="desired-salary-msg"></small>
                    </div>
                    <div class="form-group">
                        <label for="preferred-location">Preferred Location</label>
                        <select id="preferred-location" name="preferred_location">
                            <option value="">Select location</option>
                            <option value="tunis">Tunis</option>
                            <option value="sfax">Sfax</option>
                            <option value="sousse">Sousse</option>
                            <option value="remote">Remote</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        <small class="field-msg" id="preferred-location-msg"></small>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-laptop-code"></i> Skills & Experience</h3>
                <div class="form-group">
                    <label for="skills">Skills</label>
                    <textarea id="skills" name="skills" placeholder="JavaScript, React, Communication, Project Management..." rows="3"></textarea>
                    <small class="field-msg" id="skills-msg"></small>
                </div>
                <div class="form-group">
                    <label for="experience">Professional Experience</label>
                    <textarea id="experience" name="experience" placeholder="Briefly describe your background, previous positions, and key achievements..." rows="4"></textarea>
                    <small class="field-msg" id="experience-msg"></small>
                </div>
                <div class="form-group">
                    <label>Expérience requise</label>
                    <div class="checkbox-group" id="experience-level">
                        <label><input type="radio" name="experience_level" id="exp-beginner" value="beginner"> débutant</label>
                        <label><input type="radio" name="experience_level" id="exp-intermediate" value="intermediate"> intermédiaire</label>
                        <label><input type="radio" name="experience_level" id="exp-expert" value="expert"> expert</label>
                    </div>
                    <small class="field-msg" id="experience-level-msg"></small>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-file-upload"></i> Documents</h3>
                <div class="form-group">
                    <label>Upload CV (PDF, DOC, DOCX)</label>
                    <div class="file-upload">
                        <input type="file" id="cv-upload" name="cv_file">
                        <label for="cv-upload" class="file-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Choose File</span>
                        </label>
                        <span class="file-name">No file selected</span>
                    </div>
                    <small class="field-msg" id="cv-upload-msg"></small>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="cover-letter" name="cover_letter_check">
                    <label for="cover-letter">Cover Letter (Optional)</label>
                </div>
                <div class="form-group" id="cover-letter-field" style="display: none;">
                    <label for="interest">Why are you interested in this position?</label>
                    <textarea id="interest" name="cover_letter" placeholder="Explain why you're interested in this position and how your skills match the requirements..." rows="4"></textarea>
                    <small class="field-msg" id="interest-msg"></small>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" id="cancel-application">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- SUCCESS TOAST -->
    <div class="toast" id="success-toast">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <span>Application submitted successfully!</span>
        </div>
    </div>

   <script>
    // Pass PHP data to JavaScript
    const associationData = <?php echo json_encode($associationData); ?>;
    const offersData = <?php echo json_encode($offersData); ?>;
    const associationId = <?php echo $associationId; ?>;

    // Association location data with coordinates
    const associationLocations = {
        1: { // Tech Innovators Tunisia
            lat: 36.8381,
            lng: 10.2409,
            address: "Lac 2, 2035 Tunis, Tunisia",
            businessHours: "Monday - Friday: 8:00 AM - 6:00 PM\nSaturday: 9:00 AM - 1:00 PM",
            contact: "Phone: +216 70 123 456\nEmail: contact@techinnovators.tn",
            transportation: "Metro: Line 4 - Lac 2 Station\nBus: Lines 12, 28, 45"
        },
        2: { // Café Culture
            lat: 35.8254,
            lng: 10.6360,
            address: "Medina, 4000 Sousse, Tunisia",
            businessHours: "Monday - Sunday: 7:00 AM - 11:00 PM",
            contact: "Phone: +216 73 123 456\nEmail: hello@cafeculture.tn",
            transportation: "Bus: Lines 5, 12, 25\nTaxi: Available 24/7"
        },
        3: { // Medical Center Plus
            lat: 36.8625,
            lng: 10.1956,
            address: "Charguia 1, 2035 Ariana, Tunisia",
            businessHours: "24/7 Emergency Services\nConsultation: 8:00 AM - 8:00 PM",
            contact: "Phone: +216 71 123 456\nEmergency: +216 98 123 456",
            transportation: "Metro: Line 4 - Charguia Station\nBus: Lines 3, 7, 15"
        },
        4: { // EduFuture Academy
            lat: 36.8083,
            lng: 10.0972,
            address: "University Campus, 2010 Manouba, Tunisia",
            businessHours: "Monday - Friday: 8:00 AM - 6:00 PM\nSaturday: 9:00 AM - 1:00 PM",
            contact: "Phone: +216 70 234 567\nEmail: admissions@edufuture.tn",
            transportation: "Train: TGM Line\nBus: University Shuttle"
        },
        5: { // BuildPro Construction
            lat: 36.7433,
            lng: 10.2353,
            address: "Industrial Zone, 2013 Ben Arous, Tunisia",
            businessHours: "Monday - Friday: 7:00 AM - 5:00 PM\nSaturday: 8:00 AM - 12:00 PM",
            contact: "Phone: +216 70 345 678\nEmail: projects@buildpro.tn",
            transportation: "Bus: Industrial Zone Express\nCompany Shuttle Available"
        },
        6: { // MarketPlace Retail
            lat: 35.7780,
            lng: 10.8262,
            address: "City Center, 5000 Monastir, Tunisia",
            businessHours: "Monday - Sunday: 9:00 AM - 9:00 PM",
            contact: "Phone: +216 73 234 567\nEmail: info@marketplace.tn",
            transportation: "Bus: All city lines\nParking: Available"
        },
        7: { // Digital Solutions SARL
            lat: 34.7406,
            lng: 10.7603,
            address: "City Center, 3000 Sfax, Tunisia",
            businessHours: "Monday - Friday: 8:00 AM - 6:00 PM\nRemote Support: 24/7",
            contact: "Phone: +216 74 123 456\nEmail: support@digitalsolutions.tn",
            transportation: "Bus: Lines 10, 15, 22\nMetro: City Center Station"
        },
        8: { // Green Leaf Restaurant
            lat: 36.4000,
            lng: 10.6167,
            address: "Tourist Zone, 8050 Hammamet, Tunisia",
            businessHours: "Monday - Sunday: 11:00 AM - 11:00 PM",
            contact: "Phone: +216 72 234 567\nEmail: reservations@greenleaf.tn",
            transportation: "Tourist Bus: Beach Line\nTaxi: Available 24/7"
        },
        9: { // Tunisian Telecom
            lat: 36.8008,
            lng: 10.1800,
            address: "Centre Urbain Nord, 1080 Tunis, Tunisia",
            businessHours: "Monday - Friday: 8:30 AM - 5:30 PM\nCustomer Service: 24/7",
            contact: "Phone: 1298\nEmail: contact@tunisietelecom.tn",
            transportation: "Metro: Republic Station\nBus: All major lines"
        },
        10: { // PharmaCare Tunisia
            lat: 36.9000,
            lng: 9.9667,
            address: "Industrial Zone, 2020 Sidi Thabet, Tunisia",
            businessHours: "Monday - Friday: 8:00 AM - 5:00 PM",
            contact: "Phone: +216 70 456 789\nEmail: info@pharmacare.tn",
            transportation: "Company Shuttle\nBus: Industrial Zone Line"
        },
        11: { // Tunisian Airlines
            lat: 36.8510,
            lng: 10.2272,
            address: "Tunis-Carthage Airport, 1080 Tunis, Tunisia",
            businessHours: "24/7 Operations\nTicket Office: 6:00 AM - 10:00 PM",
            contact: "Phone: +216 70 100 100\nEmail: contact@tunisair.com.tn",
            transportation: "Airport Shuttle\nTaxi: Available 24/7\nParking: Available"
        },
        12: { // Banque de Tunisie
            lat: 36.8000,
            lng: 10.1833,
            address: "Avenue Habib Bourguiba, 1000 Tunis, Tunisia",
            businessHours: "Monday - Friday: 8:00 AM - 4:00 PM\nATM: 24/7",
            contact: "Phone: +216 71 100 100\nEmail: contact@bdtexpress.tn",
            transportation: "Metro: Republic Station\nBus: All city lines"
        }
    };

    // DOM Elements
    const offersContainer = document.getElementById('offers-container');
    const applicationModal = document.getElementById('application-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelApplication = document.getElementById('cancel-application');
    const applicationForm = document.getElementById('application-form');
    const successToast = document.getElementById('success-toast');
    const coverLetterCheckbox = document.getElementById('cover-letter');
    const coverLetterField = document.getElementById('cover-letter-field');
    const cvUpload = document.getElementById('cv-upload');
    const fileName = document.querySelector('.file-name');

    let currentOfferId = null;

    // ========== MAP FUNCTIONS ==========
    // Initialize map in header
    function initMap() {
        const locationData = associationLocations[associationId] || associationLocations[1];
        
        // Create map
        const map = L.map('association-map').setView([locationData.lat, locationData.lng], 15);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);
        
        // Add custom marker icon
        const customIcon = L.divIcon({
            html: '<i class="fas fa-map-marker-alt" style="color: #7B8AB6; font-size: 2rem;"></i>',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            className: 'custom-marker'
        });
        
        // Add marker with custom icon
        const marker = L.marker([locationData.lat, locationData.lng], { icon: customIcon }).addTo(map);
        
        // Add popup
        marker.bindPopup(`
            <div style="text-align: center; padding: 0.5rem;">
                <strong style="color: #7B8AB6;">${associationData.name}</strong><br>
                <small>${locationData.address}</small>
            </div>
        `).openPopup();
        
        // Update location details
        updateLocationDetails(locationData);
    }

    // Update location information in header
    function updateLocationDetails(locationData) {
        document.getElementById('location-address').textContent = locationData.address.split(',')[0];
        document.getElementById('business-hours').textContent = locationData.businessHours.split('\n')[0];
        document.getElementById('contact-info').textContent = locationData.contact.split('\n')[0].replace('Phone: ', '');
        document.getElementById('transportation').textContent = locationData.transportation.split('\n')[0].replace('Metro: ', '');
        
        // Update directions button
        const directionsBtn = document.getElementById('directions-btn');
        directionsBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${locationData.lat},${locationData.lng}`;
        
        // Update contact button
        const contactBtn = document.getElementById('contact-btn');
        var m = locationData.contact.match(/Email:\s*([^\n]+)/);
        var email = (m && m[1]) ? m[1] : 'info@company.tn';
        contactBtn.href = `mailto:${email}`;
    }

    // Event listeners setup
    function setupEventListeners() {
        // Apply button clicks
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('apply-btn') || e.target.closest('.apply-btn')) {
                const button = e.target.classList.contains('apply-btn') ? e.target : e.target.closest('.apply-btn');
                currentOfferId = button.dataset.offerId;
                document.getElementById('selected-offer-id').value = currentOfferId;
                openApplicationModal();
            }
        });

        // Modal controls
        closeModal.addEventListener('click', closeApplicationModal);
        cancelApplication.addEventListener('click', closeApplicationModal);
        applicationModal.addEventListener('click', (e) => {
            if (e.target === applicationModal) {
                closeApplicationModal();
            }
        });

        // Cover letter toggle
        coverLetterCheckbox.addEventListener('change', () => {
            coverLetterField.style.display = coverLetterCheckbox.checked ? 'block' : 'none';
        });

        // File upload display
        cvUpload.addEventListener('change', (e) => {
            var f = e.target && e.target.files && e.target.files[0];
            fileName.textContent = (f && f.name) ? f.name : 'No file selected';
        });

        // Form submission
        applicationForm.addEventListener('submit', handleApplicationSubmit);

        // Live validation bindings
        bindValidation();

        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        themeToggle.innerHTML = savedTheme === 'dark' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
        
        themeToggle.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            const newTheme = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            themeToggle.innerHTML = newTheme === 'dark' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
        });
    }

    // Modal functions
    function openApplicationModal() {
        applicationModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        bindValidation();
    }

    function closeApplicationModal() {
        applicationModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        applicationForm.reset();
        fileName.textContent = 'No file selected';
        coverLetterField.style.display = 'none';
    }

    // Validation helpers
    function setFieldState(el, ok, msgId, msg){
        var msgEl = document.getElementById(msgId);
        if(!msgEl || !el) return ok;
        el.classList.remove('is-valid','is-invalid');
        msgEl.classList.remove('success','error');
        if(ok){
            el.classList.add('is-valid');
            msgEl.classList.add('success');
            msgEl.textContent = msg || 'Valid input';
        } else {
            el.classList.add('is-invalid');
            msgEl.classList.add('error');
            msgEl.textContent = msg || 'Invalid input';
        }
        return ok;
    }

    function attachLiveValidation(id, fn){
        var el = document.getElementById(id);
        if(!el) return;
        ['keyup','blur'].forEach(function(ev){ el.addEventListener(ev, fn); });
    }

    function bindValidation(){
        attachLiveValidation('full-name', validateFullName);
        attachLiveValidation('phone', validatePhone);
        var phoneInput = document.getElementById('phone');
        if(phoneInput){
            if(!phoneInput.value){ phoneInput.value = '+216'; }
            phoneInput.addEventListener('input', enforceTunisiaPhoneFormat);
            phoneInput.addEventListener('focus', function(){ if(!phoneInput.value) phoneInput.value = '+216'; });
            phoneInput.addEventListener('blur', enforceTunisiaPhoneFormat);
        }
        attachLiveValidation('email', validateEmail);
        attachLiveValidation('profession', validateProfession);
        attachLiveValidation('desired-salary', validateSalary);
        document.getElementById('preferred-location').addEventListener('change', validateLocation);
        attachLiveValidation('skills', validateSkills);
        attachLiveValidation('experience', validateExperience);
        var cvUpload = document.getElementById('cv-upload');
        if(cvUpload){ cvUpload.addEventListener('change', validateCV); }
        var coverLetter = document.getElementById('cover-letter');
        if(coverLetter){ coverLetter.addEventListener('change', function(){ if(this.checked){ attachLiveValidation('interest', validateInterest); } }); }
        ['exp-beginner','exp-intermediate','exp-expert'].forEach(function(id){
            var el = document.getElementById(id);
            if(el){ el.addEventListener('change', validateExperienceLevel); }
        });
    }

    function validateFullName(){
        var el = document.getElementById('full-name');
        var v = (el.value || '').trim();
        var onlyLettersAndSpaces = /^[A-Za-z ]+$/.test(v);
        var parts = v.split(/\s+/).filter(Boolean);
        var hasTwoWords = parts.length >= 2 && parts.every(function(p){ return /^[A-Za-z]{2,}$/.test(p); });
        var ok = onlyLettersAndSpaces && hasTwoWords;
        return setFieldState(el, ok, 'full-name-msg', ok ? 'Valid input' : 'Letters only, at least two words');
    }

    function enforceTunisiaPhoneFormat(){
        var el = document.getElementById('phone');
        if(!el) return;
        var v = (el.value || '').replace(/\s|-/g,'');
        if(!v.startsWith('+216')){ v = '+216' + v.replace(/^\+?216?/, ''); }
        var digits = v.replace(/^\+216/, '').replace(/\D/g,'').slice(0,8);
        el.value = '+216' + digits;
    }

    function validatePhone(){
        var el = document.getElementById('phone');
        var v = (el.value || '').trim();
        var ok = /^\+216\d{8}$/.test(v);
        return setFieldState(el, ok, 'phone-msg', ok ? 'Valid input' : 'Format: +216 followed by 8 digits');
    }

    function validateEmail(){
        var el = document.getElementById('email');
        var v = (el.value || '').trim();
        var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
        return setFieldState(el, ok, 'email-msg', ok ? 'Valid input' : 'Invalid email (e.g., name@example.com)');
    }

    function validateProfession(){
        var el = document.getElementById('profession');
        var v = (el.value || '').trim();
        var ok = v.length >= 2;
        return setFieldState(el, ok, 'profession-msg', ok ? 'Valid input' : 'Please specify the position');
    }

    function validateSalary(){
        var el = document.getElementById('desired-salary');
        var v = parseFloat(el.value);
        var ok = !isNaN(v) && v >= 300;
        return setFieldState(el, ok, 'desired-salary-msg', ok ? 'Valid input' : 'Minimum salary 300 DT');
    }

    function validateLocation(){
        var el = document.getElementById('preferred-location');
        var ok = !!el.value;
        return setFieldState(el, ok, 'preferred-location-msg', ok ? 'Valid input' : 'Please select a location');
    }

    function validateSkills(){
        var el = document.getElementById('skills');
        var v = (el.value || '').trim();
        var ok = v.length >= 10;
        return setFieldState(el, ok, 'skills-msg', ok ? 'Valid input' : 'Add more details (≥10 characters)');
    }

    function validateExperience(){
        var el = document.getElementById('experience');
        var v = (el.value || '').trim();
        var ok = v.length >= 20;
        return setFieldState(el, ok, 'experience-msg', ok ? 'Valid input' : 'Add more information (≥20 characters)');
    }

    function validateExperienceLevel(){
        var b = document.getElementById('exp-beginner');
        var i = document.getElementById('exp-intermediate');
        var e = document.getElementById('exp-expert');
        var ok = (b && b.checked) || (i && i.checked) || (e && e.checked);
        var group = document.getElementById('experience-level');
        if(group){
            group.classList.toggle('is-invalid', !ok);
            group.classList.toggle('is-valid', ok);
        }
        var msg = document.getElementById('experience-level-msg');
        if(msg){
            msg.classList.remove('success','error');
            msg.classList.add(ok ? 'success' : 'error');
            msg.textContent = ok ? 'Experience level selected' : 'Please select one experience level';
        }
        return ok;
    }

    function validateCV(){
        var el = document.getElementById('cv-upload');
        var files = el.files;
        var ok = files && files.length > 0 && /\.(pdf|docx?|PDF|DOCX?)$/.test(files[0].name);
        return setFieldState(el, ok, 'cv-upload-msg', ok ? 'Valid input' : 'Upload a CV (PDF/DOC/DOCX)');
    }

    function validateInterest(){
        var el = document.getElementById('interest');
        var v = (el.value || '').trim();
        var ok = v.length >= 10;
        return setFieldState(el, ok, 'interest-msg', ok ? 'Valid input' : 'Explain your interest (≥10 characters)');
    }

    function validateInterestOptional(){
        var checkbox = document.getElementById('cover-letter');
        if(!checkbox || !checkbox.checked) return true;
        return validateInterest();
    }

    // Form submission handler
    async function handleApplicationSubmit(e) {
        e.preventDefault();
        var r1 = validateFullName();
        var r2 = validatePhone();
        var r3 = validateEmail();
        var r4 = validateProfession();
        var r5 = validateSalary();
        var r6 = validateLocation();
        var r7 = validateSkills();
        var r8 = validateExperience();
        var r9 = validateExperienceLevel();
        var r10 = validateCV();
        var r11 = validateInterestOptional();
        var ok = r1 && r2 && r3 && r4 && r5 && r6 && r7 && r8 && r9 && r10 && r11;
        if(!ok){
            var firstInvalid = applicationForm.querySelector('.is-invalid');
            if(firstInvalid) firstInvalid.focus();
            return;
        }

        // Create FormData for file upload
        const formData = new FormData(applicationForm);
        formData.append('action', 'submit_application');

        // Show loading state
        const submitBtn = applicationForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('job-offers.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            if (result.includes('success')) {
                showSuccessToast();
                closeApplicationModal();
            } else {
                alert('Error submitting application. Please try again.');
            }
        } catch (error) {
            console.error('Submission error:', error);
            alert('An error occurred while submitting the application.');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Success toast
    function showSuccessToast() {
        successToast.classList.add('show');
        setTimeout(() => {
            successToast.classList.remove('show');
        }, 3000);
    }

    // ========== FILTERING AND SEARCH FUNCTIONALITY ==========

    // Filter and search elements
    const searchInput = document.getElementById('search-offers');
    const salaryFilter = document.getElementById('filter-salary');
    const locationFilter = document.getElementById('filter-location');
    const contractFilter = document.getElementById('filter-contract');

    // Function to filter offers based on criteria
    function filterOffers() {
        const searchTerm = searchInput.value.toLowerCase();
        const salaryRange = salaryFilter.value;
        const locationValue = locationFilter.value;
        const contractValue = contractFilter.value;

        const offerCards = document.querySelectorAll('.offer-card');
        let visibleCount = 0;

        offerCards.forEach(card => {
            const title = card.querySelector('.offer-title').textContent.toLowerCase();
            const description = card.querySelector('.offer-description').textContent.toLowerCase();
            const location = card.querySelector('.meta-item:nth-child(1)').textContent.toLowerCase();
            const salaryText = card.querySelector('.offer-salary').textContent;
            const contractTypes = Array.from(card.querySelectorAll('.meta-item:nth-child(2)')).map(item => 
                item.textContent.toLowerCase()
            ).join(' ');

            // Search filter
            const matchesSearch = !searchTerm || 
                title.includes(searchTerm) || 
                description.includes(searchTerm);

            // Location filter
            const matchesLocation = !locationValue || 
                location.includes(locationValue);

            // Contract type filter
            const matchesContract = !contractValue || 
                contractTypes.includes(contractValue.toLowerCase());

            // Salary filter
            let matchesSalary = true;
            if (salaryRange) {
                const salaryMatch = salaryText.match(/(\d+\.?\d*)-(\d+\.?\d*)/);
                if (salaryMatch) {
                    const minSalary = parseFloat(salaryMatch[1]);
                    const maxSalary = parseFloat(salaryMatch[2]);
                    
                    switch (salaryRange) {
                        case '1000-1500':
                            matchesSalary = minSalary >= 1000 && maxSalary <= 1500;
                            break;
                        case '1500-2000':
                            matchesSalary = minSalary >= 1500 && maxSalary <= 2000;
                            break;
                        case '2000+':
                            matchesSalary = minSalary >= 2000;
                            break;
                    }
                } else {
                    matchesSalary = true;
                }
            }

            // Show/hide card based on all filters
            if (matchesSearch && matchesLocation && matchesContract && matchesSalary) {
                card.style.display = 'block';
                visibleCount++;
                card.style.animation = 'cardAppear 0.5s ease-out';
            } else {
                card.style.display = 'none';
            }
        });

        // Show no results message if needed
        showNoResultsMessage(visibleCount === 0);
    }

    // Function to show/hide no results message
    function showNoResultsMessage(show) {
        let noResults = document.querySelector('.no-results');
        
        if (show && !noResults) {
            noResults = document.createElement('div');
            noResults.className = 'no-results';
            noResults.style.cssText = 'grid-column: 1 / -1; text-align: center; padding: 3rem;';
            noResults.innerHTML = `
                <i class="fas fa-briefcase" style="font-size: 3rem; color: #aaa; margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text); margin-bottom: 0.5rem;">No Job Offers Match Your Criteria</h3>
                <p style="color: #aaa;">Try adjusting your filters or search terms.</p>
            `;
            document.getElementById('offers-container').appendChild(noResults);
        } else if (!show && noResults) {
            noResults.remove();
        }
    }

    // Function to reset filters
    function resetFilters() {
        searchInput.value = '';
        salaryFilter.value = '';
        locationFilter.value = '';
        contractFilter.value = '';
        filterOffers();
    }

    // Add reset button to filters (optional)
    function addResetButton() {
        const filtersSection = document.querySelector('.filters-section');
        const resetBtn = document.createElement('button');
        resetBtn.type = 'button';
        resetBtn.className = 'btn btn-outline';
        resetBtn.innerHTML = '<i class="fas fa-redo"></i> Reset';
        resetBtn.addEventListener('click', resetFilters);
        
        const filterOptions = document.querySelector('.filter-options');
        filterOptions.appendChild(resetBtn);
    }

    // Initialize filtering functionality
    function initializeFiltering() {
        // Add event listeners for filters
        searchInput.addEventListener('input', debounce(filterOffers, 300));
        salaryFilter.addEventListener('change', filterOffers);
        locationFilter.addEventListener('change', filterOffers);
        contractFilter.addEventListener('change', filterOffers);
        
        // Add reset button
        addResetButton();
    }

    // Debounce function for search input
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Enhanced search with better matching
    function enhanceSearch() {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const offerCards = document.querySelectorAll('.offer-card');
            
            offerCards.forEach(card => {
                const title = card.querySelector('.offer-title');
                const description = card.querySelector('.offer-description');
                const skills = card.querySelector('.offer-skills');
                
                const cardText = [
                    title.textContent,
                    description.textContent,
                    skills.textContent
                ].join(' ').toLowerCase();
                
                // Highlight matching terms
                highlightText(title, term);
                highlightText(description, term);
                
                if (term && cardText.includes(term)) {
                    card.style.border = '2px solid var(--primary)';
                    card.style.transform = 'translateY(-5px)';
                } else {
                    card.style.border = '1px solid var(--border)';
                    card.style.transform = 'translateY(0)';
                }
            });
        });
    }

    // Function to highlight search terms
    function highlightText(element, term) {
        if (!term) return;
        
        const text = element.textContent;
        const regex = new RegExp(`(${term})`, 'gi');
        const highlighted = text.replace(regex, '<mark class="search-highlight">$1</mark>');
        element.innerHTML = highlighted;
    }

    // Add CSS for search highlighting
    function addSearchHighlightStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .search-highlight {
                background-color: #FFEB3B;
                color: #333;
                padding: 0.1rem 0.2rem;
                border-radius: 3px;
                font-weight: 600;
            }
            
            [data-theme='dark'] .search-highlight {
                background-color: #FFA000;
                color: #000;
            }
            
            .filter-active {
                border-color: var(--primary) !important;
                box-shadow: 0 0 0 2px rgba(123, 138, 182, 0.3) !important;
            }
        `;
        document.head.appendChild(style);
    }

    // Update your initializePage function to include filtering
    function initializePage() {
        setupEventListeners();
        setTimeout(initMap, 100);
        initializeFiltering();
        enhanceSearch();
        addSearchHighlightStyles();
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePage);
    } else {
        initializePage();
    }
     
    // Fade-in on scroll
    const fadeElements = document.querySelectorAll('.fade-in');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1 });
    
    fadeElements.forEach(el => observer.observe(el));
</script>
</body>
</html>