<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">

    <title>Lumina - Employment Department</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatomo-tale-seo-agency.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="CSS/job.css">
</head>

<body>

<?php
// Inclure les contrôleurs
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\AssociationController.php';

// Initialiser le contrôleur
$associationController = new AssociationController();

// Récupérer les associations depuis la base de données
$associations = $associationController->getAssociations();

// Fonctions helper pour formater les données (définies avant utilisation)
function extractRating($ratingText) {
    if (preg_match('/(\d+\.\d+)/', $ratingText, $matches)) {
        return floatval($matches[1]);
    }
    return 4.0;
}

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

// Convertir le format des données pour le JavaScript
$associationsData = [];
foreach ($associations as $assoc) {
    $associationsData[] = [
        'id' => $assoc['id'],
        'name' => $assoc['name'],
        'category' => $assoc['category'],
        'location' => $assoc['location'],
        'description' => $assoc['description'],
        'rating' => extractRating($assoc['rating']),
        'active_offers' => $assoc['active_offers'],
        'logo' => getLogoByCategory($assoc['category']),
        'banner' => getBannerByCategory($assoc['category']),
        'sector' => getSectorByCategory($assoc['category']),
        'type' => getTypeByCategory($assoc['category'])
    ];
}
?>

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

  <!-- Animated Face and Speech Bubble - LEFT SIDE -->
  <div class="assistant-container">
      <div class="speech-bubble" id="speech-bubble">
          Hello! Welcome to Lumina Job Portal! 🌟
      </div>
      <div class="animated-face" id="animated-face">
          <div class="face-inner">
              <div class="eyes">
                  <div class="eye"></div>
                  <div class="eye"></div>
              </div>
              <div class="mouth"></div>
          </div>
      </div>
  </div>
  
  <!-- EMPLOYMENT SERVICE: Contains ALL employment content -->
  <section id="employment-service" class="service-emploi">
      <!-- Former "Home" Hero Content — now inside Employment Service -->
      <section class="hero">
          <div class="hero-content">
              <div class="hero-text">
                  <h1 class="hero-title fade-in">Find Your Dream Job in Tunisia</h1>
                  <p class="hero-subtitle fade-in delay-1">Lumina revolutionizes job searching with an intelligent and secure system that connects talent with serious companies.</p>
              </div>
              <div class="hero-image">
                  <div class="floating-card" id="flip-card">
                      <div class="card-front">
                          <div class="card-icon"><i class="fas fa-user-graduate"></i></div>
                          <h3 class="card-title">For Job Seekers</h3>
                          <p class="card-text">Our advanced matching algorithm analyzes your skills, experience, and aspirations...</p>
                      </div>
                      <div class="card-back">
                          <div class="card-icon"><i class="fas fa-building"></i></div>
                          <h3 class="card-title">For Companies</h3>
                          <p class="card-text">Post your jobs after rigorous validation...</p>
                      </div>
                  </div>
              </div>
          </div>
      </section>
              
      <!-- CATEGORIES FILTER -->
      <section class="categories-section" id="job-search">
          <div class="container">
              <h2 class="section-title fade-in">Browse by Category</h2>
              <div class="categories-filter animated" id="categories-filter">
                  <button class="category-btn active" data-category="all">All</button>
                  <button class="category-btn" data-category="health">🏥 Health & Hospitals</button>
                  <button class="category-btn" data-category="restaurants">☕ Cafés & Restaurants</button>
                  <button class="category-btn" data-category="education">🎓 Education</button>
                  <button class="category-btn" data-category="construction">🏗️ Construction</button>
                  <button class="category-btn" data-category="commerce">🛍️ Commerce & Services</button>
                  <button class="category-btn" data-category="other">💼 Other Services</button>
              </div>
          </div>
      </section>

      <!-- ASSOCIATIONS GRID -->
      <a id="associations"></a>
      <section class="associations-grid animated" id="associations-grid">
          <div class="container">
              <div class="grid-header">
                  <h2 class="section-title fade-in">Featured Associations</h2>
                  <div class="sort-options">
                      <select id="sort-associations">
                          <option value="rating">Highest Rated</option>
                          <option value="name">Name A-Z</option>
                          <option value="newest">Newest</option>
                      </select>
                  </div>
              </div>
              
              <div class="associations-container animated" id="associations-container">
                  <!-- Associations will be dynamically loaded here -->
              </div>
              
              <div class="load-more">
                  <button id="load-more-btn" class="btn btn-outline">Load More Associations</button>
              </div>
          </div>
      </section>

      <!-- ASSOCIATION TEMPLATE (Hidden) -->
      <template id="association-template">
          <div class="association-card" data-category="" data-rating="">
              <div class="card-header">
                  <div class="association-banner">
                      <img src="" alt="Banner" class="banner-img">
                  </div>
                  <div class="association-logo">
                      <img src="" alt="Logo" class="logo-img">
                  </div>
              </div>
              <div class="card-body">
                  <div class="association-type"></div>
                  <h3 class="association-name"></h3>
                  <p class="association-sector"></p>
                  <div class="association-rating">
                      <div class="stars"></div>
                      <span class="rating-value"></span>
                      <span class="review-count"></span>
                  </div>
                  <div class="association-meta">
                      <span class="meta-item"><i class="fas fa-map-marker-alt"></i> <span class="location"></span></span>
                      <span class="meta-item"><i class="fas fa-briefcase"></i> <span class="open-positions"></span> positions</span>
                  </div>
              </div>
              <div class="card-footer">
                  <button class="btn btn-primary view-opportunities">
                      <i class="fas fa-eye"></i> View Opportunities
                  </button>
              </div>
          </div>
      </template>

      <!-- APPLICATION TRACKING SECTION - NEW VERSION -->
      <section class="application-tracker" id="application-tracking">
          <h2>Your Application Tracking</h2>

          <div class="tracker-container">
              <div>
                  <!-- Filters -->
                  <div class="tracker-filters">
                      <button class="filter-btn active" data-filter="all">All</button>
                      <button class="filter-btn" data-filter="submitted">Submitted</button>
                      <button class="filter-btn" data-filter="viewed">Viewed</button>
                      <button class="filter-btn" data-filter="interview">Interviews</button>
                  </div>

                  <!-- Application Timeline -->
                  <div class="tracker-timeline">
                      <!-- Your exact data preserved -->
                      <div class="timeline-item submitted" data-status="submitted">
                          <div class="timeline-marker"></div>
                          <div class="timeline-content">
                              <h4>Application Submitted</h4>
                              <p>Frontend Developer - Startup XYZ</p>
                              <span class="timeline-date">Nov 15 2024 - 14:30</span>
                              <div class="company-info">
                                  <small><i class="fas fa-building"></i> Tech Startup · Tunis</small>
                              </div>
                          </div>
                      </div>

                      <div class="timeline-item viewed" data-status="viewed">
                          <div class="timeline-marker"></div>
                          <div class="timeline-content">
                              <h4>CV Viewed</h4>
                              <p>The recruiter has seen your profile</p>
                              <span class="timeline-date">Nov 16 2024 - 09:15</span>
                              <div class="company-info">
                                  <small><i class="fas fa-building"></i> Startup XYZ · Recruiter: Sarah M.</small>
                              </div>
                          </div>
                      </div>

                      <div class="timeline-item interview upcoming" data-status="interview">
                          <div class="timeline-marker"></div>
                          <div class="timeline-content">
                              <h4>Interview Scheduled</h4>
                              <p>Technical interview with development team</p>
                              <span class="timeline-date">Nov 18 2024 - 10:00 (Teams)</span>
                              <button class="calendar-btn">
                                  <i class="fas fa-calendar-plus"></i> Add to Calendar
                              </button>
                          </div>
                      </div>

                      <div class="timeline-item submitted" data-status="submitted">
                          <div class="timeline-marker"></div>
                          <div class="timeline-content">
                              <h4>Application Submitted</h4>
                              <p>Data Analyst - Bank ABC</p>
                              <span class="timeline-date">Nov 14 2024 - 11:20</span>
                              <div class="company-info">
                                  <small><i class="fas fa-building"></i> Banking Sector · Lac 2</small>
                              </div>
                          </div>
                      </div>

                      <div class="timeline-item viewed" data-status="viewed">
                          <div class="timeline-marker"></div>
                          <div class="timeline-content">
                              <h4>CV Viewed</h4>
                              <p>Your profile interests the recruiter</p>
                              <span class="timeline-date">Nov 17 2024 - 16:45</span>
                              <div class="company-info">
                                  <small><i class="fas fa-building"></i> Bank ABC · HR Department</small>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Actions -->
                  <div class="tracker-actions">
                      <button class="action-btn secondary">
                          <i class="fas fa-download"></i> Export Tracking
                      </button>
                  </div>
              </div>

              <!-- Statistics -->
              <div class="application-stats">
                  <div class="stat-card">
                      <div class="stat-number">12</div>
                      <div class="stat-label">Applications Sent</div>
                      <div class="stat-trend up">+2 this week</div>
                  </div>

                  <div class="stat-card">
                      <div class="stat-number">5</div>
                      <div class="stat-label">Scheduled Interviews</div>
                      <div class="stat-trend up">+1 today</div>
                  </div>

                  <div class="stat-card">
                      <div class="stat-number">2</div>
                      <div class="stat-label">Offers Received</div>
                      <div class="stat-trend neutral">Pending</div>
                  </div>

                  <div class="stat-card">
                      <div class="stat-number">63%</div>
                      <div class="stat-label">Response Rate</div>
                      <div class="stat-trend up">+5% vs last month</div>
                  </div>
              </div>
          </div>
      </section>
  </section>

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

  <script>
      // Pass PHP data to JavaScript
      const associationsData = <?php echo json_encode($associationsData); ?>;

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

      // Theme toggle
      const themeToggle = document.getElementById('theme-toggle');
      const html = document.documentElement;
      
      // Cute Face Assistant
      const speechBubble = document.getElementById('speech-bubble');
      const animatedFace = document.getElementById('animated-face');

      // Array of inclusive messages for all Lumina departments
      const cuteMessages = [
          "Welcome to Lumina! How can we help you today? 🌟",
          "Need support? We're here for you 24/7! 💙",
          "Your safety and well-being are our priority! 🛡️",
          "Looking for job opportunities? Check our listings! 💼",
          "Facing domestic violence? We provide confidential support! 🏠",
          "Join our community events and meet new people! 🎉",
          "Financial assistance programs available! 💰",
          "Mental health support is just a click away! 🧠",
          "Career counseling and training programs available! 📚",
          "Emergency housing assistance for those in need! 🏠",
          "Legal aid services for vulnerable individuals! ⚖️",
          "Food distribution every Wednesday at our centers! 🍎",
          "Healthcare referrals and medical assistance! 🏥",
          "Youth empowerment programs starting soon! 🌱",
          "Elderly care and companionship services! 👵👴",
          "Disability support and accessibility resources! ♿",
          "Educational scholarships for deserving students! 🎓",
          "Small business grants for entrepreneurs! 💼",
          "Community gardening projects - get involved! 🌻",
          "Crisis intervention available 24/7! 🆘",
          "Family counseling and mediation services! 👨‍👩‍👧‍👦",
          "Substance abuse recovery programs! 💊",
          "Veteran support services and benefits! 🇹🇳",
          "Women's empowerment workshops every month! 💪",
          "Child protection and welfare services! 👶"
      ];

      // Change the speech bubble message every 8 seconds
      let messageIndex = 0;
      function changeMessage() {
          speechBubble.textContent = cuteMessages[messageIndex];
          messageIndex = (messageIndex + 1) % cuteMessages.length;
          
          // Add a subtle animation when changing messages
          speechBubble.style.animation = 'none';
          setTimeout(() => {
              speechBubble.style.animation = 'bubbleFloat 4s ease-in-out infinite';
          }, 10);
      }

      // Initial message change
      changeMessage();

      // Set interval to change messages
      setInterval(changeMessage, 8000);

      // Make the face clickable to immediately change message
      animatedFace.addEventListener('click', changeMessage);

      // Load saved theme
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

      // Scroll effects
      window.addEventListener('scroll', () => {
          document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 50);
      });

      // Card flip
      const flipCard = document.getElementById('flip-card');
      setInterval(() => flipCard.classList.toggle('flipped'), 5000);

      // Fade-in on scroll
      const fadeElements = document.querySelectorAll('.fade-in');
      const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
              if (entry.isIntersecting) entry.target.classList.add('visible');
          });
      }, { threshold: 0.1 });
      fadeElements.forEach(el => observer.observe(el));

      // Script for Application Tracking section - NEW
      document.addEventListener('DOMContentLoaded', function () {
          // Timeline elements animation
          const timelineItems = document.querySelectorAll('.timeline-item');

          const timelineObserver = new IntersectionObserver((entries) => {
              entries.forEach((entry, index) => {
                  if (entry.isIntersecting) {
                      setTimeout(() => {
                          entry.target.classList.add('visible');
                      }, index * 200);
                  }
              });
          }, { threshold: 0.1 });

          timelineItems.forEach(item => timelineObserver.observe(item));

          // Application filtering
          const filterButtons = document.querySelectorAll('.filter-btn');

          filterButtons.forEach(button => {
              button.addEventListener('click', function () {
                  // Remove active class from all buttons
                  filterButtons.forEach(btn => btn.classList.remove('active'));
                  // Add active class to clicked button
                  this.classList.add('active');

                  const filter = this.getAttribute('data-filter');

                  timelineItems.forEach(item => {
                      if (filter === 'all') {
                          item.style.display = 'flex';
                      } else {
                          const status = item.getAttribute('data-status');
                          if (status === filter) {
                              item.style.display = 'flex';
                          } else {
                              item.style.display = 'none';
                          }
                      }
                  });
              });
          });

          // Calendar functionality
          const calendarBtn = document.querySelector('.calendar-btn');
          calendarBtn.addEventListener('click', function () {
              const eventTitle = 'Technical Interview - Startup XYZ';
              const eventDate = '2024-11-18T10:00:00';
              const eventDescription = 'Interview for Frontend Developer position';

              // Create calendar event (ICS format)
              const icsContent = [
                  'BEGIN:VCALENDAR',
                  'VERSION:2.0',
                  'BEGIN:VEVENT',
                  `SUMMARY:${eventTitle}`,
                  `DTSTART:${formatDateForICS(eventDate)}`,
                  `DTEND:${formatDateForICS('2024-11-18T11:00:00')}`,
                  `DESCRIPTION:${eventDescription}`,
                  'LOCATION:Microsoft Teams',
                  'END:VEVENT',
                  'END:VCALENDAR'
              ].join('\n');

              // Download ICS file
              const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
              const link = document.createElement('a');
              link.href = URL.createObjectURL(blob);
              link.download = 'interview-startup-xyz.ics';
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);

              // User feedback
              const originalText = calendarBtn.innerHTML;
              calendarBtn.innerHTML = '<i class="fas fa-check"></i> Added to Calendar!';
              calendarBtn.style.background = 'linear-gradient(135deg,#2ecc71,#27ae60)';
              setTimeout(() => {
                  calendarBtn.innerHTML = originalText;
                  calendarBtn.style.background = '';
              }, 3000);
          });

          function formatDateForICS(dateString) {
              const date = new Date(dateString);
              return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
          }

          // Real-time statistics update (simulation)
          function updateStats() {
              const stats = document.querySelectorAll('.stat-number');
              stats.forEach(stat => {
                  const currentValue = parseInt(stat.textContent);
                  if (typeof currentValue === 'number' && !isNaN(currentValue)) {
                      // Counting animation (for demonstration)
                      let start = 0;
                      const end = currentValue;
                      const duration = 2000;
                      const step = end / (duration / 16);

                      const timer = setInterval(() => {
                          start += step;
                          if (start >= end) {
                              stat.textContent = end;
                              clearInterval(timer);
                          } else {
                              stat.textContent = Math.floor(start);
                          }
                      }, 16);
                  }
              });
          }

          // Start statistics animation
          setTimeout(updateStats, 1000);
      });
              
      // DOM Elements
      const associationsContainer = document.getElementById('associations-container');
      const categoryFilter = document.getElementById('categories-filter');
      const sortSelect = document.getElementById('sort-associations');
      const loadMoreBtn = document.getElementById('load-more-btn');
      const template = document.getElementById('association-template');

      let currentCategory = 'all';
      let currentSort = 'rating';
      let displayedCount = 8;

      // Initialize associations
      function initAssociations() {
          renderAssociations();
          setupEventListeners();
      }

      // Render associations based on filters
      function renderAssociations() {
          associationsContainer.innerHTML = '';
          
          let filteredAssociations = filterAssociations();
          
          if (filteredAssociations.length === 0) {
              associationsContainer.innerHTML = `
                  <div class="no-results">
                      <i class="fas fa-search"></i>
                      <h3>No Associations Found</h3>
                      <p>Try adjusting your search or filter criteria</p>
                  </div>
              `;
              loadMoreBtn.style.display = 'none';
              return;
          }
          
          filteredAssociations = sortAssociations(filteredAssociations);
          const associationsToShow = filteredAssociations.slice(0, displayedCount);

          associationsToShow.forEach(association => {
              const card = createAssociationCard(association);
              associationsContainer.appendChild(card);
          });

          // Show/hide load more button
          loadMoreBtn.style.display = filteredAssociations.length > displayedCount ? 'block' : 'none';
      }

      // Filter associations by category and search
      function filterAssociations() {
          return associationsData.filter(association => {
              const categoryMatch = currentCategory === 'all' || association.category === currentCategory;
              return categoryMatch;
          });
      }

      // Sort associations
      function sortAssociations(associations) {
          return associations.sort((a, b) => {
              switch(currentSort) {
                  case 'name':
                      return a.name.localeCompare(b.name);
                  case 'newest':
                      return b.id - a.id;
                  case 'rating':
                  default:
                      return b.rating - a.rating;
              }
          });
      }

      // Create association card from template - ANIMATED VERSION
      function createAssociationCard(association) {
          const card = template.content.cloneNode(true).querySelector('.association-card');
          
          // Add animated class
          card.classList.add('animated');
          
          card.setAttribute('data-category', association.category);
          card.setAttribute('data-rating', association.rating);
          
          // Set card content
          card.querySelector('.banner-img').src = association.banner;
          card.querySelector('.logo-img').src = association.logo;
          card.querySelector('.association-type').textContent = association.type;
          card.querySelector('.association-name').textContent = association.name;
          card.querySelector('.association-sector').textContent = association.sector;
          card.querySelector('.location').textContent = association.location;
          card.querySelector('.open-positions').textContent = association.active_offers;
          
          // Set rating stars
          const starsContainer = card.querySelector('.stars');
          starsContainer.innerHTML = generateStars(association.rating);
          
          card.querySelector('.rating-value').textContent = association.rating;
          card.querySelector('.review-count').textContent = `(${Math.floor(association.rating * 50)})`;
          
          // Set up view opportunities button
          const viewBtn = card.querySelector('.view-opportunities');
          viewBtn.addEventListener('click', () => {
              // Navigate to job offers page with association ID
              window.location.href = `job-offers.php?association=${association.id}`;
          });
          
          return card;
      }

      // Generate star rating HTML
      function generateStars(rating) {
          const fullStars = Math.floor(rating);
          const hasHalfStar = rating % 1 >= 0.5;
          let starsHTML = '';
          
          for (let i = 1; i <= 5; i++) {
              if (i <= fullStars) {
                  starsHTML += '<i class="fas fa-star"></i>';
              } else if (i === fullStars + 1 && hasHalfStar) {
                  starsHTML += '<i class="fas fa-star-half-alt"></i>';
              } else {
                  starsHTML += '<i class="far fa-star"></i>';
              }
          }
          
          return starsHTML;
      }

      // Event listeners setup
      function setupEventListeners() {
          // Category filter
          categoryFilter.addEventListener('click', (e) => {
              if (e.target.classList.contains('category-btn')) {
                  document.querySelectorAll('.category-btn').forEach(btn => {
                      btn.classList.remove('active');
                  });
                  e.target.classList.add('active');
                  currentCategory = e.target.dataset.category;
                  displayedCount = 8;
                  renderAssociations();
              }
          });

          // Sort select
          sortSelect.addEventListener('change', (e) => {
              currentSort = e.target.value;
              renderAssociations();
          });

          // Load more
          loadMoreBtn.addEventListener('click', () => {
              displayedCount += 8;
              renderAssociations();
          });
      }

      // Initialize when DOM is loaded
      document.addEventListener('DOMContentLoaded', initAssociations);
  </script>
</body>
</html>