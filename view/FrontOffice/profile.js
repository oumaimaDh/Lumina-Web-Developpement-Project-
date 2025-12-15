document.addEventListener('DOMContentLoaded', function() {
    loadUserProfile();
});

function loadUserProfile() {
    // Vérifier d'abord si authManager est disponible
    if (typeof authManager === 'undefined') {
        console.error('Auth manager not available');
        redirectToLogin();
        return;
    }

    const user = authManager.getUser();
    
    if (!user) {
        console.log('No user found, redirecting to login');
        redirectToLogin();
        return;
    }

    console.log('Loading profile for user:', user);

    // Update profile page with user data
    try {
        document.getElementById('profileUsername').textContent = user.username || 'Unknown';
        document.getElementById('profileEmail').textContent = user.email || 'Unknown';
        document.getElementById('profileRole').textContent = user.role || 'user';
        
        document.getElementById('infoUserId').textContent = user.user_id || 'N/A';
        document.getElementById('infoUsername').textContent = user.username || 'Unknown';
        document.getElementById('infoEmail').textContent = user.email || 'Unknown';
        document.getElementById('infoRole').textContent = user.role || 'user';

        // Set member since
        document.getElementById('memberSince').textContent = new Date().toLocaleDateString();
    } catch (error) {
        console.error('Error updating profile page:', error);
    }
}

function redirectToLogin() {
    setTimeout(() => {
        window.location.href = 'login.html';
    }, 1000);
}

function editProfile() {
    alert('Edit profile functionality coming soon!');
}

function changePassword() {
    alert('Change password functionality coming soon!');
}

function viewActivity() {
    alert('View activity functionality coming soon!');
}

// Fonction de logout pour le bouton
function logout() {
    if (typeof authManager !== 'undefined') {
        authManager.logout();
    } else {
        // Fallback
        localStorage.removeItem('currentUser');
        window.location.href = 'login.html';
    }
}