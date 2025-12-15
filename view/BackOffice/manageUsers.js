// view/BackOffice/manageUsers.js - VERSION CORRIGÉE AVEC POST
console.log('🚀 manageUsers.js loaded successfully');

let allUsers = [];

// Charger les users
async function loadUsers() {
    try {
        console.log('📡 Loading users...');
        showLoadingState();
        
        const response = await fetch('apiusers.php');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        console.log('✅ Users loaded:', data);
        
        if (data.success) {
            allUsers = data.users;
            console.log(`📊 Found ${allUsers.length} users`);
            renderUsers(allUsers);
        } else {
            throw new Error(data.message || 'Failed to load users');
        }
    } catch (error) {
        console.error('❌ Error:', error);
        showErrorState('Failed to load users: ' + error.message);
    }
}

function showLoadingState() {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="5" style="text-align: center;">
                <i class="fas fa-spinner fa-spin"></i> Loading users...
            </td>
        </tr>
    `;
}

function showErrorState(message) {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="5" style="text-align: center; color: red;">
                <i class="fas fa-exclamation-triangle"></i> ${message}
            </td>
        </tr>
    `;
}

// Afficher les users
function renderUsers(users) {
    const tbody = document.getElementById('usersTableBody');
    
    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5">No users found</td></tr>';
        return;
    }
    
    tbody.innerHTML = users.map(user => `
        <tr>
            <td>${user.user_id}</td>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td>
                <button class="btn btn-edit" onclick="editUser(${user.user_id})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-delete" onclick="deleteUser(${user.user_id})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    `).join('');
}

async function deleteUser(userId) {
    if (!confirm('Supprimer cet utilisateur ?')) return;

    try {
        const response = await fetch('apiusers.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: userId })
        });

        const result = await response.json();
        
        if (result.success) {
            alert('Utilisateur supprimé !');
            loadUsers();
        } else {
            alert('Échec suppression');
        }
    } catch (err) {
        console.error(err);
        alert('Erreur réseau');
    }
}
// Recherche
function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        
        if (term === '') {
            renderUsers(allUsers);
            return;
        }
        
        const filtered = allUsers.filter(user => 
            user.username.toLowerCase().includes(term) ||
            user.email.toLowerCase().includes(term) 
        );
        
        renderUsers(filtered);
    });
}

// Éditer
function editUser(userId) {
    window.location.href = `Edit-User.html?id=${userId}`;
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('🏁 DOM loaded, initializing...');
    loadUsers();
    setupSearch();
});

// Fonctions globales
window.deleteUser = deleteUser;
window.editUser = editUser;
window.loadUsers = loadUsers;