<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <title>Tale SEO Agency - FAQ Page</title>

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
  <!-- ***** Header Area End ***** -->

  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 align-self-center">
          <div class="caption  header-text">
            <h6>SEO DIGITAL AGENCY</h6>
            <div class="line-dec"></div>
            <h4>Most Frequently Asked <em>Questions</em> Here <em>?</em></h4>
          </div>
        </div>
        <div class="col-lg-5">
          <img src="assets/images/faqs-image.jpg" alt="">
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

  <div class="happy-steps">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Our 4 Steps To Success &amp; Happy Clients</h2>
        </div>
        <div class="col-lg-12">
          <div class="steps">
            <div class="row">
              <div class="col-lg-3">
                <div class="item">
                  <img src="assets/images/services-01.jpg" alt="" style="max-width: 66px; border-radius: 50%; margin: 0 auto;">
                  <h4>Project Introduction</h4>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="item">
                  <img src="assets/images/services-02.jpg" alt="" style="max-width: 66px; border-radius: 50%; margin: 0 auto;">
                  <h4>Work Development</h4>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="item">
                  <img src="assets/images/services-03.jpg" alt="" style="max-width: 66px; border-radius: 50%; margin: 0 auto;">
                  <h4>Data Analysis</h4>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="item last-item">
                  <img src="assets/images/services-04.jpg" alt="" style="max-width: 66px; border-radius: 50%; margin: 0 auto;">
                  <h4>Project Finishing</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="most-asked section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Most <em>Frequently</em> Asked <span>Questions</span> ?</h2>
            <div class="line-dec"></div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doers.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="accordions is-first-expanded">
            <article class="accordion">
              <div class="accordion-head">
                  <span>Sartorial Butcher Humblebrag</span>
                  <span class="icon">
                      <i class="icon fa fa-chevron-right"></i>
                  </span>
              </div>
              <div class="accordion-body">
                  <div class="content">
                      <p>Tale is the best SEO agency website template using Bootstrap v5.2.2 CSS for your company. It is a free download provided by TemplateMo. There are 3 HTML pages, <a href="index.php">Home</a>, <a href="about.php">About</a>, and <a href="faqs.php">FAQ</a>.</p>
                  </div>
              </div>
          </article>
          <article class="accordion">
            <div class="accordion-head">
                <span>Jean Shorts Microdosing</span>
                <span class="icon">
                    <i class="icon fa fa-chevron-right"></i>
                </span>
            </div>
            <div class="accordion-body">
                <div class="content">
                    <p>You are free to use this template for any purpose. You are not allowed to redistribute the downloadable ZIP file of Tale SEO Template on any other template website. Please contact us. Thank you.
                    <br><br>
                    Semiotics blog cray letterpress lo-fi vexillologist before they sold out swag YOLO schlitz. Coloring book roof party gentrify brunch.</p>
                </div>
            </div>
          </article>
          <article class="accordion">
            <div class="accordion-head">
                <span>Waistcoat Aesthetic Polaroid</span>
                <span class="icon">
                    <i class="icon fa fa-chevron-right"></i>
                </span>
            </div>
            <div class="accordion-body">
                <div class="content">
                    <p>Semiotics blog cray letterpress lo-fi vexillologist before they sold out swag YOLO schlitz. Coloring book roof party gentrify brunch.<br><br>
                    Fingerstache cronut taxidermy, echo park quinoa tumblr activated charcoal before they sold out.</p>
                </div>
            </div>
          </article>
          <article class="accordion">
            <div class="accordion-head">
                <span>Dolores Accordion HTML5</span>
                <span class="icon">
                    <i class="icon fa fa-chevron-right"></i>
                </span>
            </div>
            <div class="accordion-body">
                <div class="content">
                  <p>Pickled succulents bitters  belly direct trade, shaman iceland raw denim kombucha cray offal. Food truck swag hell of tumblr poutine tilde live-edge shorts microdosing fixie succulents, viral everyday carry tattooed.</p>
                </div>
            </div>
          </article>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="get-free-quote">
          <form id="free-quote" method="submit" role="search" action="#">
            <div class="row">
              <div class="col-lg-12">
                <div class="section-heading">
                  <h2>Get a <em>Free Quote</em> Now</h2>
                </div>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your E-mail" required="">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <input type="website" name="website" id="website" placeholder="Website URL" autocomplete="on" required>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <input type="phone-number" name="phone-number" id="phone-number" placeholder="Phone Number" autocomplete="on" required>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <input type="full-name" name="full-name" id="full-name" placeholder="Full Name" autocomplete="on" >
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button">Get Your Free Quote</button>
                </fieldset>
              </div>
            </div>
          </form>
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