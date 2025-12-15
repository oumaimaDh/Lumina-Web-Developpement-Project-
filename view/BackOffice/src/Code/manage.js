console.log("Manage Users + Real-time Search - ENHANCED VISUAL VERSION");

let allUsers = [];

// ==============================
// INJECT ENHANCED STYLES
// ==============================
function injectEnhancedStyles() {
    const styles = `
    /* Enhanced Action Buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .action-btn:hover::before {
        width: 200px;
        height: 200px;
    }
    
    .action-btn i {
        font-size: 13px;
    }
    
    /* Enhanced Edit Button */
    .edit-action {
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }
    
    .edit-action:hover {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.35);
    }
    
    .edit-action:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
    }
    
    /* Enhanced Delete Button */
    .delete-action {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    }
    
    .delete-action:hover {
        background: linear-gradient(135deg, #DC2626, #B91C1C);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
    }
    
    .delete-action:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
    }
    
    /* Button Container */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        align-items: center;
    }
    
    /* Row Hover Effect */
    .user-row:hover .action-btn {
        transform: scale(1.02);
    }
    
    /* Compact buttons for small screens */
    @media (max-width: 768px) {
        .action-btn {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .action-btn i {
            font-size: 12px;
        }
        
        .action-btn span {
            display: none; /* Hide text on mobile */
        }
        
        .action-btn i {
            margin: 0;
        }
    }
    
    /* Animation for button appearance */
    @keyframes buttonFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .action-btn {
        animation: buttonFadeIn 0.3s ease forwards;
    }
    
    /* Staggered animation */
    .edit-action {
        animation-delay: 0.1s;
    }
    
    .delete-action {
        animation-delay: 0.2s;
    }
    
    /* Toast animations */
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    `;
    
    const styleElement = document.createElement('style');
    styleElement.textContent = styles;
    document.head.appendChild(styleElement);
}

// ==============================
// LOAD USERS
// ==============================
async function loadUsers() {
    try {
        const tbody = document.getElementById('usersTableBody');
        
        // Show loading state
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align:center; padding:40px;">
                    <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Loading users...</p>
                    </div>
                </td>
            </tr>
        `;

        const res = await fetch('../../../../controller/User/users.php');
        const data = await res.json();
        
        if (data.success) {
            allUsers = data.users;
            console.log(`✅ Loaded ${allUsers.length} users`);
            applyFilters(); // Apply any existing filters
        } else {
            throw new Error(data.message || 'Failed to load users');
        }
    } catch(e) {
        console.error('❌ Error loading users:', e);
        const tbody = document.getElementById('usersTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align:center; color:#dc2626; padding:30px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Error loading users</p>
                    <button onclick="loadUsers()" style="
                        margin-top: 10px;
                        padding: 8px 16px;
                        background: #3B82F6;
                        color: white;
                        border: none;
                        border-radius: 6px;
                        cursor: pointer;
                    ">
                        <i class="fas fa-redo"></i> Retry
                    </button>
                </td>
            </tr>`;
    }
}

// ==============================
// RENDER TABLE WITH ENHANCED BUTTONS
// ==============================
function renderTable(users) {
    const tbody = document.getElementById('usersTableBody');

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align:center; color:#6b7280; padding:40px;">
                    <i class="fas fa-users-slash" style="font-size:32px; margin-bottom:10px; opacity:0.3; display:block;"></i>
                    <p style="font-weight:500; margin-bottom:5px;">No users found</p>
                    <small>Try adjusting your search or filter</small>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = users.map(u => `
        <tr class="user-row">
            <td>${u.user_id}</td>
            <td>${u.username}</td>
            <td>${u.email}</td>
            <td class="actions-cell">
                <div class="table-actions">
                    <!-- Enhanced Edit Button -->
                    <button 
                        class="action-btn edit-action"
                        onclick="editUser(${u.user_id})"
                        title="Edit user">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </button>
                    
                    <!-- Enhanced Delete Button -->
                    <button 
                        class="action-btn delete-action"
                        onclick="deleteUser(${u.user_id}, '${u.username}')"
                        title="Delete user">
                        <i class="fas fa-trash-alt"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// ==============================
// EDIT USER
// ==============================
function editUser(id) {
    // Open the Edit-User.html page with the user ID
    window.location.href = `Edit-User.html?id=${id}`;
}

// ==============================
// DELETE USER WITH CONFIRMATION
// ==============================
async function deleteUser(id, username = '') {
    // Create beautiful confirmation modal
    const modal = document.createElement('div');
    modal.className = 'delete-confirmation-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeIn 0.3s ease;
        backdrop-filter: blur(4px);
    `;

    modal.innerHTML = `
        <div class="modal-content" style="
            background: white;
            padding: 2rem;
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            animation: slideUp 0.3s ease;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            text-align: center;
        ">
            <div style="
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #ef4444, #dc2626);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                color: white;
                font-size: 32px;
                box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
            ">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <h3 style="color: #1f2937; margin-bottom: 0.5rem; font-weight: 600;">
                Delete User
            </h3>
            
            <p style="color: #6b7280; margin-bottom: 1.5rem; line-height: 1.5;">
                Are you sure you want to delete 
                <strong style="color: #1f2937;">${username || 'this user'}</strong>?
                <br>
                <small style="color: #ef4444; font-weight: 500; display: block; margin-top: 5px;">
                    This action cannot be undone.
                </small>
            </p>
            
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button id="cancelDelete" style="
                    padding: 10px 24px;
                    background: #f3f4f6;
                    color: #374151;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 600;
                    transition: all 0.2s ease;
                ">
                    Cancel
                </button>
                
                <button id="confirmDelete" style="
                    padding: 10px 24px;
                    background: linear-gradient(135deg, #ef4444, #dc2626);
                    color: white;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    transition: all 0.2s ease;
                    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
                ">
                    <i class="fas fa-trash-alt"></i>
                    Delete User
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Add modal button hover styles
    const modalStyle = document.createElement('style');
    modalStyle.textContent = `
        #cancelDelete:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }
        
        #confirmDelete:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }
        
        #confirmDelete:active {
            transform: translateY(0);
        }
    `;
    document.head.appendChild(modalStyle);

    // Event listeners
    document.getElementById('cancelDelete').addEventListener('click', () => {
        modal.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            modal.remove();
            modalStyle.remove();
        }, 300);
    });

    document.getElementById('confirmDelete').addEventListener('click', async () => {
        try {
            const res = await fetch('../../../../controller/User/users.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', id: id })
            });
            
            const result = await res.json();
            
            if (result.success) {
                showToast('User deleted successfully!', 'success');
                loadUsers();
            } else {
                showToast(result.message || 'Delete failed', 'error');
            }
        } catch (err) {
            console.error('Delete error:', err);
            showToast('Network error', 'error');
        }
        
        modal.remove();
        modalStyle.remove();
    });

    // Close on click outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                modal.remove();
                modalStyle.remove();
            }, 300);
        }
    });
}

// ==============================
// FILTERING & SEARCH
// ==============================
function applyFilters() {
    const query = document.getElementById('searchInput').value.trim().toLowerCase();
    const filterType = document.getElementById('filterDropdown').value;
    const clearBtn = document.getElementById('clearBtn');

    let filtered = allUsers;

    // Apply search filter
    if (query !== '') {
        filtered = filtered.filter(user =>
            user.username.toLowerCase().includes(query) ||
            user.email.toLowerCase().includes(query)
        );
    }

    // Apply dropdown filter
    switch(filterType) {
        case 'active':
            filtered = filtered.filter(user => user.active === true || user.active === '1');
            break;
        case 'recent':
            filtered = filtered.filter(user => {
                if (!user.created_at) return false;
                try {
                    const created = new Date(user.created_at);
                    const now = new Date();
                    const daysDiff = (now - created) / (1000 * 60 * 60 * 24);
                    return daysDiff <= 7; // Last 7 days
                } catch (e) {
                    return false;
                }
            });
            break;
        case 'verified':
            filtered = filtered.filter(user => user.verified === true || user.verified === '1');
            break;
        // 'all' shows all users
    }

    renderTable(filtered);
    
    // Show/hide clear button
    if (query !== '' || filterType !== 'all') {
        clearBtn.style.display = 'inline-flex';
    } else {
        clearBtn.style.display = 'none';
    }
    
    // Update result count
    updateResultCount(filtered.length);
}

function updateResultCount(count) {
    let counter = document.getElementById('resultCount');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'resultCount';
        counter.className = 'result-count';
        counter.style.cssText = `
            position: absolute;
            right: 120px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
            background: rgba(0, 0, 0, 0.05);
            padding: 2px 8px;
            border-radius: 4px;
        `;
        document.querySelector('.search-container').appendChild(counter);
    }
    counter.innerHTML = `<small>${count} user${count !== 1 ? 's' : ''} found</small>`;
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterDropdown').value = 'all';
    document.getElementById('clearBtn').style.display = 'none';
    renderTable(allUsers);
    updateResultCount(allUsers.length);
}

// ==============================
// TOAST NOTIFICATION
// ==============================
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'enhanced-toast';
    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: ${type === 'success' ? '#10B981' : type === 'error' ? '#EF4444' : '#3B82F6'};
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        animation: slideInRight 0.4s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        max-width: 350px;
        border-left: 4px solid ${type === 'success' ? '#059669' : type === 'error' ? '#DC2626' : '#2563EB'};
    `;
    
    const icon = type === 'success' ? 'check-circle' : 
                 type === 'error' ? 'exclamation-circle' : 'info-circle';
    
    toast.innerHTML = `<i class="fas fa-${icon}"></i> ${message}`;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.4s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 400);
    }, 3000);
}

// ==============================
// INITIALIZATION
// ==============================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🏁 DOM loaded, initializing...');
    
    // Inject enhanced styles
    injectEnhancedStyles();
    
    // Load users
    loadUsers();
    
    // Setup search and filter
    const searchInput = document.getElementById('searchInput');
    const filterDropdown = document.getElementById('filterDropdown');
    const clearBtn = document.getElementById('clearBtn');
    const refreshBtn = document.querySelector('.btn-primary[onclick="loadUsers()"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    
    if (filterDropdown) {
        filterDropdown.addEventListener('change', applyFilters);
    }
    
    if (clearBtn) {
        clearBtn.addEventListener('click', clearSearch);
    }
    
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadUsers();
        });
    }
});

// ==============================
// GLOBAL FUNCTIONS
// ==============================
window.editUser = editUser;
window.deleteUser = deleteUser;
window.loadUsers = loadUsers;
window.clearSearch = clearSearch;