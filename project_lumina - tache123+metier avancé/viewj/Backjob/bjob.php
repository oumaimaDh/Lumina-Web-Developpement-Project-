<?php
// viewj/Backjob/bjob.php - Section Jobs principale
?>
<div id="jobs-tab" class="tab-content active">
    <div class="jobs-container">
        <div class="job-header">
            <h1>Job Admin Dashboard</h1>
            
            <div class="nav-buttons">
                <button class="nav-btn" onclick="window.location.href='index.php?view=categories'">
                    <i class="fas fa-th-large"></i> Browse Categories
                </button>
                <button class="nav-btn" onclick="window.location.href='index.php?view=offers'">
                    <i class="fas fa-briefcase"></i> View All Offers
                </button>
                <button class="nav-btn" onclick="window.location.href='index.php?view=applications'">
                    <i class="fas fa-users"></i> Manage Applications
                </button>
                <!-- AJOUTER LE BOUTON CALENDRIER ICI -->
                <button class="nav-btn" onclick="window.location.href='index.php?view=calendar'">
                    <i class="fas fa-calendar-alt"></i> Interview Calendar
                </button>
            </div>
        </div>

        <!-- Inclure la vue appropriée -->
        <?php 
        switch($view) {
            case 'associations':
                include 'associations.php';
                break;
            case 'create_offer':
            case 'edit_offer':
                include 'offer_form.php';
                break;
            case 'offers':
                include 'offers.php';
                break;
            case 'applications':
                include 'applications.php';
                break;
            case 'calendar': // AJOUTER CE CAS
                include 'calendar_section.php';
                break;
            default:
                include 'categories.php';
                break;
        }
        ?>
    </div>
</div>