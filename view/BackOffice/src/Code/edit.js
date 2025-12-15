// view/BackOffice/edit-user.js
let currentUserId = null;

// Charger les données du user
async function loadUserData() {
    const urlParams = new URLSearchParams(window.location.search);
    currentUserId = urlParams.get('id');
    
    if (!currentUserId) {
        alert('No user ID provided');
        window.location.href = 'index.php';
        return;
    }
    
    try {
        const response = await fetch(`../../../../controller/User/editUser.php?id=${currentUserId}`);
        const data = await response.json();
        
        console.log('Full API response:', data);
        
        if (data.success) {
            populateForm(data.user);
        } else {
            alert('Error: ' + data.message);
            window.location.href = 'index.php';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Network error loading user data');
    }
}

// Remplir le formulaire avec les données
function populateForm(user) {
    console.log('Complete user object from API:', user);
    
    // Use the CORRECT lowercase field names from your database
    document.getElementById('userId').value = user.user_id || '';
    document.getElementById('username').value = user.username || '';
    document.getElementById('email').value = user.email || '';
    
    
    // Final check of all form values
    console.log('=== FINAL FORM VALUES ===');
    console.log('User ID:', document.getElementById('userId').value);
    console.log('Username:', document.getElementById('username').value);
    console.log('Email:', document.getElementById('email').value);
}

// Sauvegarder les modifications
async function saveUserChanges(userData) {
    try {
        const response = await fetch('../../../../controller/User/editUser.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(userData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('successModal').classList.add('show');
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Network error saving changes');
    }
}

// Modal functions
function closeSuccessModal() {
    document.getElementById('successModal').classList.remove('show');
    window.location.href = 'index.php';
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    loadUserData();
    
    // Reset button
    document.getElementById('resetBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset all changes?')) {
            loadUserData(); // Recharger les données originales
        }
    });
    
    // Form submission
    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = {
            id: currentUserId,
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
        };
        
        await saveUserChanges(formData);
    });
});