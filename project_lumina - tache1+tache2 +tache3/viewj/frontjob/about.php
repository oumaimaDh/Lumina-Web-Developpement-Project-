<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <title>Tale SEO Agency - About Page</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-tale-seo-agency.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="CSS/job.css">
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
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
  <div class="pre-header" id="top">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-sm-9">
          <div class="left-info">
            <ul>
              <li><a href="#"><i class="fa fa-phone"></i>+000 1234 5678</a></li>
              <li><a href="#"><i class="fa fa-envelope"></i>infocompany@email.com</a></li>
              <li><a href="#"><i class="fa fa-map-marker"></i>St. London 54th Bull</a></li>
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
    <div class="container">
        <div class="row">
            <div class="col-12">
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
                                <li><a href="jobdepartment.php#job-search"><i class="fas fa-search"></i> Job Search</a></li>
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
                        <button class="theme-toggle" id="theme-toggle"><i class="fas fa-sun"></i></button>
                        <button class="btn btn-outline"><i class="fas fa-sign-in-alt"></i> Login</button>
                        <button class="btn btn-primary"><i class="fas fa-user-plus"></i> Sign Up</button>
                    </div>
                </nav>
            </div>
        </div>
  </div>
  </header>
  <!-- ***** Header Area End ***** -->

  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 align-self-center">
          <div class="caption  header-text">
            <h6>SEO DIGITAL AGENCY</h6>
            <div class="line-dec"></div>
            <h4>Discover More <em>About Us</em></h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod tempor incididunt ut labore et dolore.</p>
            <div class="main-button"><a href="#">Discover More</a></div>
            <span>or</span>
            <div class="second-button"><a href="#">Check our FAQs</a></div>
          </div>
        </div>
        <div class="col-lg-5 align-self-center">
          <img src="assets/images/about-us-image.jpg" alt="">
  </div>
  </div>
  </div>
  </div>

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
  </script>

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

  <div class="video-info section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="video-thumb">
            <img src="assets/images/video-thumb.jpg" alt="">
            <a href="http://youtube.com" target="_blank"><i class="fa fa-play"></i></a>
          </div>
        </div>
        <div class="col-lg-6 align-self-center">
          <div class="section-heading">
            <h2>Detailed Information On What We Do &amp; Who We Are</h2>
            <div class="line-dec"></div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod tempor incididunt ut labore et dolore dolor.</p>
          </div>
          <div class="skills">
            <div class="skill-slide marketing">
              <div class="fill"></div>
              <h6>SEO Marketing</h6>
              <span>90%</span>
            </div>
            <div class="skill-slide digital">
              <div class="fill"></div>
              <h6>Digital Marketing</h6>
              <span>80%</span>
            </div>
            <div class="skill-slide media">
              <div class="fill"></div>
              <h6>Social Media Management</h6>
              <span>95%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="happy-clients section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Our 4 Steps <em>To Success</em> &amp; <span>Happy Clients</span></h2>
            <div class="line-dec"></div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers.</p>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="naccs">
            <div class="tabs">
              <div class="row">
                <div class="col-lg-12">
                  <div class="menu">
                    <div class="active"><span>Project Introduction</span></div>
                    <div><span>Work Development</span></div>
                    <div><span>Data Analysis</span></div>
                    <div class="last-item"><span>Project Finishing</span></div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <ul class="nacc">
                    <li class="active">
                      <div>
                        <div class="row">
                          <div class="col-lg-7">
                            <h4>Best CSS Templates for you</h4>
                            <div class="line-dec"></div>
                            <p>Tale is the best SEO agency website template using Bootstrap v5.2.2 CSS for your company. It is a free download provided by TemplateMo. There are 3 HTML pages, <a href="index..php">Home</a>, <a href="about.php">About</a>, and <a href="faqs.php">FAQ</a>.</p>
                            <div class="info">
                              <span>Website Design</span>
                              <span>User Interface</span>
                              <span>User Experience</span>
                              <span class="last-span">Digital Agency</span>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod tempor incididunt ut labore et dolore dolor dolor sit amet, consectetur adipicing elit, sed doers eiusmod.</p>
                          </div>
                          <div class="col-lg-5 align-self-center">
                            <img src="assets/images/happyclient-01.jpg" alt="">
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="row">
                          <div class="col-lg-7">
                            <h4>Detailed Information On What We Do</h4>
                            <div class="line-dec"></div>
                            <p>You are free to use this template for any purpose. You are not allowed to redistribute the downloadable ZIP file of Tale SEO Template on any other template website. Please contact us. Thank you.</p>
                            <div class="info">
                              <span>HTML CSS</span>
                              <span>Bootstrap 5</span>
                              <span>TemplateMo</span>
                              <span class="last-span">Development</span>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod tempor incididunt ut labore et dolore dolor dolor sit amet, consectetur adipicing elit, sed doers eiusmod.</p>
                          </div>
                          <div class="col-lg-5 align-self-center">
                            <img src="assets/images/happyclient-01.jpg" alt="">
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="row">
                          <div class="col-lg-7">
                            <h4>Responsive HTML CSS Templates</h4>
                            <div class="line-dec"></div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod kent tempor incididunt ut labore et dolore dolor.</p>
                            <div class="info">
                              <span>SEO Trend</span>
                              <span>Digital Agency</span>
                              <span>Best Template</span>
                              <span class="last-span">Development</span>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod tempor incididunt ut labore et dolore dolor dolor sit amet, consectetur adipicing elit, sed doers eiusmod.</p>
                          </div>
                          <div class="col-lg-5 align-self-center">
                            <img src="assets/images/happyclient-01.jpg" alt="">
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="row">
                          <div class="col-lg-7">
                            <h4>Detailed Information about SEO Techniques</h4>
                            <div class="line-dec"></div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod kent tempor incididunt ut labore et dolore dolor.</p>
                            <div class="info">
                              <span>Data Analysis</span>
                              <span>SEO Trend</span>
                              <span>Templates</span>
                              <span class="last-span">Research</span>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers eiusmod tempor incididunt ut labore et dolore dolor dolor sit amet, consectetur adipicing elit, sed doers eiusmod.</p>
                          </div>
                          <div class="col-lg-5 align-self-center">
                            <img src="assets/images/happyclient-01.jpg" alt="">
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="cta section">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <h4>Are You Ready To Work &amp; Develop With Us ?<br>Don't Hesitate &amp; Contact Us !</h4>
        </div>
        <div class="col-lg-4">
          <div class="main-button">
            <a href="#">Contact Us Now!</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="col-lg-12">
        <p>Copyright © 2036 <a href="#">Tale SEO Agency</a>. All rights reserved. 
        
        <br>Design: <a href="https://templatemo.com" target="_blank">TemplateMo</a></p>
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