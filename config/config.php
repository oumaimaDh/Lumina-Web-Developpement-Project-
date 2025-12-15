<?php
// Application Configuration
define('APP_NAME', 'Lumina Forum');
define('APP_VERSION', '1.0.0');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ilyesm');
define('DB_USER', 'root');
define('DB_PASS', '');

// Paths
define('BASE_URL', 'http://localhost/lumina_project/');
define('FORUM_URL', BASE_URL . 'frontoffice/');

// Timezone
date_default_timezone_set('Africa/Tunis');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);