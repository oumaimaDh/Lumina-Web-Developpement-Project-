<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 2px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-accept {
            background-color: #28a745;
            color: white;
        }
        
        .btn-accept:hover {
            background-color: #218838;
        }
        
        .btn-accept:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-reject:hover {
            background-color: #c82333;
        }
        
        .btn-reject:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
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
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        /* Add these styles to fix layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f5f5f7;
            overflow-x: auto;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
        }
        
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }
        
        table tr:hover {
            background-color: #f8f9fa;
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

<!-- MAIN CONTENT AREA - This was missing -->
<main class="main-content">
    <div id="social" class="tab-content active">
        <h1 style="margin-bottom: 20px; color: #333;">Social Cases Management</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Case</th>
                        <th>Category</th>
                        <th>Association</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
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
          require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetMVC/model/socialcasemodel.php';

          $socialCaseController = new SocialCaseController();
          $socialCases = $socialCaseController->getAllSocialCases();

          $categories_data = $socialCaseController->getAllCategories();
          $categories_map = [];
          foreach ($categories_data as $category) {
              $categories_map[$category['id_category']] = $category['name'];
          }

          $associations_data = $socialCaseController->getAllAssociations();
          $associations_map = [];
          foreach ($associations_data as $association) {
              $associations_map[$association['id_association']] = $association['name'];
          }

          foreach ($socialCases as $case) {
              $statusClass = 'status-' . strtolower($case['status']);
              $isAccepted = $case['status'] === 'Accepted';
              $isRejected = $case['status'] === 'Rejected';
              
              echo '<tr>';
              echo '<td>' . htmlspecialchars($case['id_case']) . '</td>';
              echo '<td>' . htmlspecialchars($categories_map[$case['id_category']] ?? 'Unknown Category') . '</td>';
              echo '<td>' . htmlspecialchars($associations_map[$case['id_association']] ?? 'Unknown Association') . '</td>';
              echo '<td>' . htmlspecialchars($case['submited_date']) . '</td>';
              echo '<td><span class="' . $statusClass . '">' . htmlspecialchars($case['status']) . '</span></td>';
              echo '<td>';
              echo '<div class="action-buttons">';
              
              // Accept button - always visible but disabled if already accepted
              echo '<button type="button" class="btn btn-accept" ';
              echo $isAccepted ? 'disabled' : 'onclick="updateStatus(' . $case['id_case'] . ', \'Accepted\')"';
              echo '>Accept</button>';
              
              // Reject button - always visible but disabled if already rejected
              echo '<button type="button" class="btn btn-reject" ';
              echo $isRejected ? 'disabled' : 'onclick="updateStatus(' . $case['id_case'] . ', \'Rejected\')"';
              echo '>Reject</button>';
              
              echo '</div>';
              echo '</td>';
              echo '</tr>';
          }
          ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</div> <!-- Close dashboard-container -->

    <script src="script.js"></script>
    <script>
    function updateStatus(caseId, newStatus) {
        if (confirm('Are you sure you want to ' + newStatus.toLowerCase() + ' this case?')) {
            // Create a form to submit the data
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'update_status.php';
            
            // Add case ID
            const caseIdInput = document.createElement('input');
            caseIdInput.type = 'hidden';
            caseIdInput.name = 'id_case';
            caseIdInput.value = caseId;
            form.appendChild(caseIdInput);
            
            // Add new status
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = newStatus;
            form.appendChild(statusInput);
            
            // Add to document and submit
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Set current date
    document.getElementById('currentDate').textContent = new Date().toLocaleDateString();
    
    // Notification count is now shown in the icon
    
    function toggleUserMenu() {
        alert('User menu to be implemented');
    }
    </script>
</body>
</html>