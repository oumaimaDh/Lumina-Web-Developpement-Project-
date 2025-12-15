<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina - Association Cases</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cases-container {
            padding: 20px;
        }
        
        .cases-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .cases-header h1 {
            color: #2d3748;
            font-size: 2em;
        }
        
        .back-button {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .back-button:hover {
            background-color: #5a6268;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            background-color: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }
        
        tr:hover {
            background-color: #f7fafc;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-accepted {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        
        .no-cases {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }
        
        .no-cases i {
            font-size: 4em;
            margin-bottom: 20px;
            color: #cbd5e0;
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
                <a href="indexb.php" class="nav-item" data-tab="dashboard">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="social_case.php" class="nav-item" data-tab="social">
                    <i class="fas fa-heart"></i>
                    <span>Social Cases</span>
                </a>
                <a href="listassociations.php" class="nav-item active" data-tab="associations">
                    <i class="fas fa-users"></i>
                    <span>Associations</span>
                </a>
                <a href="notifications.php" class="nav-item" data-tab="notifications">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </nav>

            <div class="version-info">
                <p>Version 23.0.0</p>
                <p class="version-date">2025</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="header-logo">
                    <img src="logo.png.png" alt="Lumina Logo" class="logo-image">
                </div>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
                <div class="header-date">
                    <p id="currentDate"></p>
                </div>
                <div class="header-actions">
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

            <!-- Cases Content -->
            <div class="cases-container">
                <?php
                // Define base path if not already defined
                if (!isset($basePath)) {
                    $basePath = realpath(dirname(__DIR__) . '/..');
                    if (!$basePath) {
                        $basePath = dirname(dirname(__DIR__));
                    }
                }
                require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/config.php';
                require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/socialcasecontroller.php';
                
                $associationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                
                if ($associationId <= 0) {
                    echo '<div class="no-cases">';
                    echo '<i class="fas fa-exclamation-triangle"></i>';
                    echo '<h2>Invalid Association ID</h2>';
                    echo '<p><a href="listassociations.php" class="back-button">Back to Associations</a></p>';
                    echo '</div>';
                } else {
                    $socialCaseController = new SocialCaseController();
                    
                    // Get association info
                    $association = $socialCaseController->getAssociationById($associationId);
                    
                    if (!$association) {
                        echo '<div class="no-cases">';
                        echo '<i class="fas fa-exclamation-triangle"></i>';
                        echo '<h2>Association Not Found</h2>';
                        echo '<p><a href="listassociations.php" class="back-button">Back to Associations</a></p>';
                        echo '</div>';
                    } else {
                        // Get all cases for this association
                        $allCases = $socialCaseController->getAllSocialCases();
                        $associationCases = array_filter($allCases, function($case) use ($associationId) {
                            return $case['id_association'] == $associationId;
                        });
                        
                        // Get categories for display
                        $categories = $socialCaseController->getAllCategories();
                        $categoryMap = [];
                        foreach ($categories as $category) {
                            $categoryMap[$category['id_category']] = $category['name'];
                        }
                        
                        echo '<div class="cases-header">';
                        echo '<h1><i class="fas fa-list"></i> Cases for Association: ' . htmlspecialchars($association['name']) . '</h1>';
                        echo '<a href="listassociations.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to Associations</a>';
                        echo '</div>';
                        
                        if (empty($associationCases)) {
                            echo '<div class="no-cases">';
                            echo '<i class="fas fa-inbox"></i>';
                            echo '<h2>No cases found</h2>';
                            echo '<p>This association has no assigned cases yet.</p>';
                            echo '</div>';
                        } else {
                            echo '<div class="table-container">';
                            echo '<table>';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Case ID</th>';
                            echo '<th>Name</th>';
                            echo '<th>Phone</th>';
                            echo '<th>Email</th>';
                            echo '<th>Category</th>';
                            echo '<th>Location</th>';
                            echo '<th>Description</th>';
                            echo '<th>Submitted Date</th>';
                            echo '<th>Status</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            
                            foreach ($associationCases as $case) {
                                $statusClass = 'status-' . strtolower($case['status']);
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($case['id_case']) . '</td>';
                                echo '<td>' . htmlspecialchars($case['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($case['phone']) . '</td>';
                                echo '<td>' . htmlspecialchars($case['email']) . '</td>';
                                echo '<td>' . htmlspecialchars($categoryMap[$case['id_category']] ?? 'Unknown') . '</td>';
                                echo '<td>' . htmlspecialchars($case['location']) . '</td>';
                                echo '<td>' . htmlspecialchars(substr($case['description'], 0, 50)) . (strlen($case['description']) > 50 ? '...' : '') . '</td>';
                                echo '<td>' . htmlspecialchars($case['submited_date']) . '</td>';
                                echo '<td><span class="' . $statusClass . '">' . htmlspecialchars($case['status']) . '</span></td>';
                                echo '</tr>';
                            }
                            
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
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

        function toggleUserMenu() {
            alert('User menu to be implemented');
        }
    </script>
</body>
</html>

