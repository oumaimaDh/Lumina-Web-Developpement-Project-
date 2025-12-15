<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #718096;
            font-size: 1.1em;
        }
        
        .recent-activities {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #edf2f7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .activity-time {
            color: #718096;
            font-size: 0.9em;
        }
    </style>
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
                <a href="indexb.php" class="nav-item active" data-tab="dashboard">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="social_case.php" class="nav-item" data-tab="social">
                    <i class="fas fa-heart"></i>
                    <span>Social Cases</span>
                </a>
                <a href="associations.php" class="nav-item" data-tab="associations">
                    <i class="fas fa-users"></i>
                    <span>Associations</span>
                </a>
                <a href="notifications.php" class="nav-item" data-tab="notifications">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
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
                <!-- Logo -->
                <div class="header-logo">
                    <img src="logo.png.png" 
                         alt="Lumina Logo" 
                         class="logo-image">
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search jobs, associations..." class="search-input" id="globalSearch">
                </div>

                <!-- Date -->
                <div class="header-date">
                    <p id="currentDate"></p>
                </div>

                <!-- Actions -->
                <div class="header-actions">
                    <!-- Notifications -->
                    <a href="notifications.php" class="icon-btn" style="position: relative; text-decoration: none; color: inherit;">
                        <i class="fas fa-bell"></i>
                        <?php
                        try {
                            // Define base path if not already defined
                            if (!isset($basePath)) {
                                $basePath = realpath(dirname(__DIR__) . '/..');
                                if (!$basePath) {
                                    $basePath = dirname(dirname(__DIR__));
                                }
                            }
                            require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/notificationcontroller.php';
                            $notificationController = new NotificationController();
                            $unreadCount = $notificationController->getUnreadCount();
                            if ($unreadCount > 0) {
                                echo '<span class="notification-dot" style="position: absolute; top: 0; right: 0; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">' . $unreadCount . '</span>';
                            }
                        } catch (Exception $e) {
                            // Table doesn't exist yet - no badge shown
                        }
                        ?>
                    </a>

                    <!-- User Profile -->
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

           
        </main>
    </div>

    <script>
        // Set current date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Notification count is now shown in the icon
        
        function toggleUserMenu() {
            alert('User menu to be implemented');
        }

        // Update active nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === currentPage) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>