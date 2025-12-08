// script.js - Main dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
});

function initDashboard() {
    initTabNavigation();
    updateDate();
    console.log('Dashboard initialized');
}

function initTabNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked nav item
            this.classList.add('active');
        });
    });
}

function updateDate() {
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        dateElement.textContent = new Date().toLocaleDateString('en-US', options);
    }
}

// Placeholder functions for dashboard features
function toggleNotifications() {
    console.log('Notifications toggled');
    alert('Notifications feature would open here');
}

function toggleUserMenu() {
    console.log('User menu toggled');
    alert('User menu would open here');
}

// Debug function pour vérifier la section jobs
function debugJobsSection() {
    console.log('Debug: Checking Jobs Section');
    
    // Vérifier si la section jobs est active
    const jobsTab = document.getElementById('jobs-tab');
    if (jobsTab && jobsTab.classList.contains('active')) {
        console.log('Jobs section is active in DOM');
        
        // Forcer l'affichage de la première section
        const firstSection = document.querySelector('.jobs-section');
        if (firstSection) {
            firstSection.classList.add('active');
            console.log('First jobs section activated');
        }
    }
}

// Appeler la fonction debug au chargement
document.addEventListener('DOMContentLoaded', function() {
    debugJobsSection();
});