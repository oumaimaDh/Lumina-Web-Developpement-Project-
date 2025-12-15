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

        /* Styles for Associations List */
        .associations-list-section {
            /* Removed styling for the section box */
        }

        .associations-list-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .associations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .associations-table th,
        .associations-table td {
            border: 1px solid #eee;
            padding: 12px 15px;
            text-align: left;
        }

        .associations-table th {
            background-color: #f8f8f8;
            font-weight: bold;
            color: #666;
        }

        .associations-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .associations-table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
    padding: 0.7rem 1.5rem;
    border-radius: 50px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 200px;
  
}

.btn-primary {
     background-color: #4CAF50; /* Default primary color */
    color: white;
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--btn-outline-color);
    color: var(--btn-outline-color);
}

.btn i {
    margin-right: 8px;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

      

        .associations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .association-box {
            background: white;
            padding: 25px;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .association-box h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .association-box p {
            margin-bottom: 8px;
            color: #555;
        }

        .association-box .actions {
            margin-top: 15px;
            display: flex;
            gap: 10px; /* Adds space between buttons */
        }

        .association-box .actions .btn-edit,
        .association-box .actions .btn-delete {
            margin-right: 10px;
        }

        /* Styles for the filter controls container */
        .filter-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Styles for search input and select dropdowns */
        .filter-controls input[type="text"],
        .filter-controls select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            flex-grow: 1;
            min-width: 150px;
        }

        /* Styles for the filter button */
        .filter-controls button {
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        /* Hover effect for the filter button */
        .filter-controls button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="dashboard-container">

<aside class="sidebar">

    <div class="sidebar-bg-blur sidebar-bg-blur-1"></div>
    <div class="sidebar-bg-blur sidebar-bg-blur-2"></div>

  
    <div class="logo-container">
        <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
        </svg>
        <span class="logo-text">Lumina</span>
    </div>

 
<nav class="sidebar-nav">
<!-- SIMPLE DASHBOARD BUTTON -->
<button class="nav-item" data-tab="dashboard"  onclick="window.location.href='../index.php'">

<div class="nav-gradient-overlay"></div>
<div class="nav-icon-wrapper">
<i class="fas fa-home nav-icon"></i>
</div>
<span class="nav-label">Dashboard</span>
<div class="nav-star">
<svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
</svg>
</div>
<div class="hover-trail"></div>
</button>

        <div class="nav-group">
            <button class="nav-item" data-tab="events" data-has-submenu="true"  onclick="window.location.href='../index.php'">
                <div class="particle-container">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <div class="nav-gradient-overlay"></div>
                
                <!-- Icon -->
                <div class="nav-icon-wrapper">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                
                <span class="nav-label">Events</span>

                <div class="nav-star">
                    <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="hover-trail"></div>
                
                <svg class="dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>

            <div class="submenu expanded">
                <button class="nav-item nav-sub-item" data-tab="participants"  onclick="window.location.href='../index.php'">
                    <div class="nav-gradient-overlay"></div>
                    <div class="nav-icon-wrapper">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <span class="nav-label">Participants</span>
                    <div class="nav-star">
                        <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="hover-trail"></div>
                </button>

                <button class="nav-item nav-sub-item" data-tab="sponsors"  onclick="window.location.href='../index.php'">
                    <div class="nav-gradient-overlay"></div>
                    <div class="nav-icon-wrapper">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </div>
                    <span class="nav-label">Sponsors</span>
                    <div class="nav-star">
                        <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="hover-trail"></div>
                </button>
            </div>
        </div>

        <!-- Admin Tasks -->
        <button class="nav-item" data-tab="tasks"  onclick="window.location.href='../index.php'">
            <div class="nav-gradient-overlay"></div>
            <div class="nav-icon-wrapper">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <span class="nav-label">Admin Tasks</span>
            <div class="nav-star">
                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="hover-trail"></div>
        </button>

        <button class="nav-item" data-tab="social-case"onclick="window.location.href='../Social/social_case.php'">
        <div class="nav-gradient-overlay"></div>
        <div class="nav-icon-wrapper">
        <i class="fas fa-users nav-icon"></i>
        </div>
        <span class="nav-label">Social Case</span>
        <div class="nav-star">
        <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        </div>
        <div class="hover-trail"></div>
<       </button>


     <!-- Associations -->
     <button class="nav-item" data-tab="tasks" onclick="window.location.href='../Social/associations.php'">
            <div class="nav-gradient-overlay"></div>
            <div class="nav-icon-wrapper">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <span class="nav-label">Associations</span>
            <div class="nav-star">
                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="hover-trail"></div>
        </button>


<button class="nav-item " data-tab="job" onclick="window.location.href='index.php'">
<div class="nav-gradient-overlay"></div>
<div class="nav-icon-wrapper">
<i class="fas fa-briefcase nav-icon"></i>
</div>
<span class="nav-label">Jobs</span>
<div class="nav-star">
<svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
</svg>
</div>
<div class="hover-trail"></div>
</button>
<button class="nav-item" data-tab="forum"  onclick="window.location.href='../../../index.php'">
<div class="nav-gradient-overlay"></div>
<div class="nav-icon-wrapper">
<i class="fas fa-comments nav-icon"></i>
</div>
<span class="nav-label">Forum</span>
<div class="nav-star">
<svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
</svg>
</div>
<div class="hover-trail"></div>
</button>


        <!-- Settings -->
        <button class="nav-item" data-tab="settings"  onclick="window.location.href='../index.php'">
            <div class="nav-gradient-overlay"></div>
            <div class="nav-icon-wrapper">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6m0 6v6m9-9h-6m-6 0H3m15.364 6.364l-4.243-4.243m-6.364 0L3.636 17.364m12.728 0l-4.243-4.243m-6.364 0L3.636 6.636"></path>
                </svg>
            </div>
            <span class="nav-label">Settings</span>
            <div class="nav-star">
                <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div class="hover-trail"></div>
        </button>
    </nav>

    <!-- Help Section -->
    <div class="help-section">
        <div class="help-bg-glow"></div>
        
        <div class="help-star-container">
            <svg class="help-star" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            <div class="pulse-ring pulse-ring-1"></div>
            <div class="pulse-ring pulse-ring-2"></div>
        </div>
        
        <h4 class="help-title">Need Help?</h4>
        <p class="help-text">Check our documentation or contact support</p>
        
        <!-- Floating mini stars -->
        <svg class="mini-star mini-star-1" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        <svg class="mini-star mini-star-2" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        <svg class="mini-star mini-star-3" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
    </div>

    <!-- Bottom decorative bar -->
    <div class="sidebar-bottom-bar"></div>
</aside>

        </main>
    </div>

           
            <section class="associations-list-section">
                <?php
                // Include necessary files for database connection and controller
                // Define base path if not already defined
                if (!isset($basePath)) {
                    $basePath = realpath(dirname(__DIR__) . '/..');
                    if (!$basePath) {
                        $basePath = dirname(dirname(__DIR__));
                    }
                }
                require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/config.php';
                require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/controller/socialcasecontroller.php';

                // Instantiate the controller
                $socialCaseController = new SocialCaseController();
                // Fetch all categories to populate the category filter dropdown
                $categories = $socialCaseController->getAllCategories();

                // Get search and filter parameters from the URL, if they exist
                $search = $_GET['search'] ?? '';
                $category_id = $_GET['category_id'] ?? null;
                $availabelity = $_GET['availabelity'] ?? null;

                // Fetch associations based on the applied filters
                $associations = $socialCaseController->getFilteredAssociations($search, $category_id, $availabelity);

                // Create a map for category IDs to names for display
                $categoryMap = [];
                foreach ($categories as $category) {
                    $categoryMap[$category['id_category']] = $category['name'];
                }
                ?>
                <h2>All Associations</h2>
                <!-- Filter Controls Section -->
                <div class="filter-controls">
                    <!-- Search Input Field -->
                    <input type="text" id="search-input" placeholder="Search by name, location, or email...">
                    <!-- Category Filter Dropdown -->
                    <select id="category-filter">
                        <option value="">All Categories</option>
                        <?php
                        // Populate category options dynamically from the database
                        foreach ($categories as $category) {
                            echo '<option value="' . htmlspecialchars($category['id_category']) . '">' . htmlspecialchars($category['name']) . '</option>';
                        }
                        ?>
                    </select>
                    <!-- Availability Filter Dropdown -->
                    <select id="availabelity-filter">
                        <option value="">All Availabilities</option>
                        <option value="1">Available</option>
                        <option value="0">Not Available</option>
                    </select>
                    <!-- Filter Button -->
                    <button id="filter-button" class="btn btn-primary">Filter</button>
                </div>
                <div class="associations-grid">
                    <?php

                    if (!empty($associations)) {
                        foreach ($associations as $association) {
                            echo '<div class="association-box">';
                            echo '<h3>' . htmlspecialchars($association['name']) . '</h3>';
                            echo '<p><strong>ID:</strong> ' . htmlspecialchars($association['id_association']) . '</p>';
                            echo '<p><strong>Phone:</strong> ' . htmlspecialchars($association['phone']) . '</p>';
                            echo '<p><strong>Location:</strong> ' . htmlspecialchars($association['location']) . '</p>';
                            echo '<p><strong>Email:</strong> ' . htmlspecialchars($association['email']) . '</p>';
                            echo '<p><strong>Availabelity:</strong> ' . ($association['availabelity'] ? 'Available' : 'Not Available') . '</p>';
                            $categoryName = $categoryMap[$association['id_category']] ?? 'Unknown';
                            echo '<p><strong>Category:</strong> ' . htmlspecialchars($categoryName) . '</p>';
                            echo '<div class="actions">';
                            echo '<a href="editassociation.php?id=' . htmlspecialchars($association['id_association']) . '" class="btn btn-primary">Edit</a>';
                            echo '<a href="deleteassociation.php?id=' . htmlspecialchars($association['id_association']) . '"class="btn btn-primary" onclick="return confirm(\'Are you sure you want to delete this association?\');">Delete</a>';
                            echo '<a href="view_association_cases.php?id=' . htmlspecialchars($association['id_association']) . '" class="btn btn-primary" style="background-color: #17a2b8;">View Cases</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No associations found.</p>';
                    }
                    ?>
                </div>
            </section>
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

            // Set current filter values if they exist in the URL on page load
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search')) {
                document.getElementById('search-input').value = urlParams.get('search');
            }
            if (urlParams.has('category_id')) {
                document.getElementById('category-filter').value = urlParams.get('category_id');
            }
            if (urlParams.has('availabelity')) {
                document.getElementById('availabelity-filter').value = urlParams.get('availabelity');
            }
        });

        // Event listeners for filter controls
        document.getElementById('filter-button').addEventListener('click', applyFilters);
        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        document.getElementById('category-filter').addEventListener('change', applyFilters);
        document.getElementById('availabelity-filter').addEventListener('change', applyFilters);

        // Function to apply filters and update the URL
        function applyFilters() {
            const search = document.getElementById('search-input').value;
            const category_id = document.getElementById('category-filter').value;
            const availabelity = document.getElementById('availabelity-filter').value;

            // Construct new URL with updated search parameters
            const url = new URL(window.location.href);
            url.searchParams.set('search', search);
            url.searchParams.set('category_id', category_id);
            url.searchParams.set('availabelity', availabelity);
            // Redirect to the new URL to apply filters
            window.location.href = url.toString();
        }
    </script>
</body>
</html>