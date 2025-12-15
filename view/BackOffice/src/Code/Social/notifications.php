<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina - Notifications</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .notifications-container {
            padding: 20px;
            width: 100%;
            text-align: left;
        }
        
        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
        }
        
        .notifications-header h1 {
            margin: 0;
            text-align: left;
        }
        
        .notifications-list {
            width: 100%;
            margin-top: 0;
            text-align: left;
            clear: both;
        }
        
        .notifications-header h1 {
            color: #2d3748;
            font-size: 2em;
        }
        
        .mark-all-read-btn {
            background-color: #4299e1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .mark-all-read-btn:hover {
            background-color: #3182ce;
        }
        
        .notification-item {
            background: white;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
            border-left: 4px solid #4299e1;
            border-bottom: 1px solid #e2e8f0;
            width: 100%;
            box-sizing: border-box;
        }
        
        .notification-item.unread {
            background: #ebf8ff;
            border-left-color: #3182ce;
            font-weight: 500;
        }
        
        .notification-item:hover {
            background: #f7fafc;
        }
        
        .notification-content {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notification-message {
            font-size: 14px;
            color: #2d3748;
            font-weight: 400;
            flex: 1;
        }
        
        .notification-case-link {
            color: #4299e1;
            text-decoration: none;
            font-size: 14px;
        }
        
        .notification-case-link:hover {
            text-decoration: underline;
        }
        
        .notification-time {
            color: #718096;
            font-size: 12px;
            white-space: nowrap;
            min-width: 180px;
        }
        
        .notification-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-left: 15px;
        }
        
        .notification-badge {
            background-color: #4299e1;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            margin-left: 8px;
        }
        
        .btn-mark-read {
            background-color: #48bb78;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
        }
        
        .btn-mark-read:hover {
            background-color: #38a169;
        }
        
        .btn-delete {
            background-color: #f56565;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
        }
        
        .btn-delete:hover {
            background-color: #e53e3e;
        }
        
        .no-notifications {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }
        
        .no-notifications i {
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
                <a href="associations.php" class="nav-item" data-tab="associations">
                    <i class="fas fa-users"></i>
                    <span>Associations</span>
                </a>
                <a href="notifications.php" class="nav-item active" data-tab="notifications">
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
                            require_once $basePath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'notificationcontroller.php';
                            $notificationController = new NotificationController();
                            $unreadCount = $notificationController->getUnreadCount();
                            if ($unreadCount > 0) {
                                echo '<span class="notification-dot" style="position: absolute; top: 0; right: 0; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">' . $unreadCount . '</span>';
                            }
                        } catch (Exception $e) {
                            // Table doesn't exist yet - show setup link
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

            <!-- Notifications Content -->
            <div class="notifications-container">
                <div class="notifications-header">
                    <h1><i class="fas fa-bell"></i> Notifications</h1> 
                    <?php 
                    try {
                        $notificationController = new NotificationController();
                        $unreadCount = $notificationController->getUnreadCount();
                        if ($unreadCount > 0): 
                    ?>
                    <button class="mark-all-read-btn" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                    <?php 
                        endif;
                        $notifications = $notificationController->getAllNotifications();
                    } catch (Exception $e) {
                        // Table doesn't exist
                        echo '<div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin-bottom: 20px;">';
                        echo '<h3 style="color: #856404; margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> Notification table not found</h3>';
                        echo '<p style="color: #856404;">Please run the setup script to create the notification table:</p>';
                        echo '<a href="setup_notifications.php" style="display: inline-block; background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-top: 10px;">';
                        echo '<i class="fas fa-cog"></i> Setup Notifications Table</a>';
                        echo '</div>';
                        $notifications = [];
                        $unreadCount = 0;
                    }
                    ?>
                </div>
                
                <?php if (empty($notifications)): ?>
                    <div class="no-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <h2>No notifications yet</h2>
                        <p>You'll see notifications here when new cases are submitted.</p>
                    </div>
                <?php else: ?>
                    <div class="notifications-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?php echo $notification['is_read'] == 0 ? 'unread' : ''; ?>">
                            <div class="notification-content">
                                <div class="notification-message">
                                    <?php echo htmlspecialchars($notification['message']); ?>
                                    <?php if ($notification['is_read'] == 0): ?>
                                        <span class="notification-badge">NEW</span>
                                    <?php endif; ?>
                                    <?php if ($notification['id_case'] && $notification['id_case'] > 0): ?>
                                        <a href="social_case.php" class="notification-case-link" style="margin-left: 10px;">
                                            <i class="fas fa-external-link-alt"></i> View Case #<?php echo $notification['id_case']; ?>
                                        </a>
                                    <?php elseif ($notification['id_case'] == 0): ?>
                                        <span style="color: #4299e1; margin-left: 10px;"><i class="fas fa-handshake"></i> Association Request</span>
                                    <?php endif; ?>
                                </div>
                                <div class="notification-time">
                                    <i class="far fa-clock"></i> <?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?>
                                </div>
                            </div>
                            <div class="notification-actions">
                                <?php if ($notification['is_read'] == 0): ?>
                                    <button class="btn-mark-read" onclick="markAsRead(<?php echo $notification['id_notification']; ?>)">
                                        <i class="fas fa-check"></i> Mark as Read
                                    </button>
                                <?php endif; ?>
                                <button class="btn-delete" onclick="deleteNotification(<?php echo $notification['id_notification']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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

        function markAsRead(id) {
            fetch('mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error marking notification as read');
                }
            });
        }

        function markAllAsRead() {
            fetch('mark_all_notifications_read.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error marking all notifications as read');
                }
            });
        }

        function deleteNotification(id) {
            if (confirm('Are you sure you want to delete this notification?')) {
                fetch('delete_notification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting notification');
                    }
                });
            }
        }

        function toggleUserMenu() {
            alert('User menu to be implemented');
        }
    </script>
</body>
</html>

