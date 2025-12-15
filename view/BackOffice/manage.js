console.log("Manage Users + Real-time Search (NO ROLE NEEDED) - FIXED VERSION");

let allUsers = []; // We keep full list in memory for instant filtering

async function loadUsers() {
    try {
        console.log("Loading users...");
        const res = await fetch('users.php');
        const text = await res.text(); // Get raw response first
        console.log("Raw response:", text.substring(0, 200)); // Log first 200 chars
        
        let data;
        try {
            data = JSON.parse(text);
        } catch (parseError) {
            console.error("Failed to parse JSON. Response might be HTML/error:", text.substring(0, 500));
            
            // Try to extract JSON if it's wrapped in HTML
            const jsonMatch = text.match(/\{.*\}/s);
            if (jsonMatch) {
                try {
                    data = JSON.parse(jsonMatch[0]);
                    console.log("Extracted JSON from response");
                } catch (e) {
                    throw new Error("Server returned non-JSON response: " + text.substring(0, 100));
                }
            } else {
                throw new Error("Server returned HTML error. Check PHP configuration.");
            }
        }

        if (data.success) {
            allUsers = data.users;
            applyFilters(); // Apply both search and filter
            document.getElementById('clearBtn').style.display = 'none';
            console.log(`Loaded ${allUsers.length} users`);
        } else {
            alert("Failed to load users: " + (data.message || "Unknown error"));
        }
    } catch (e) {
        console.error("Error loading users:", e);
        alert("Error loading users: " + e.message);
    }
}

function renderTable(users) {
    const tbody = document.getElementById('usersTableBody');

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align:center; color:#999; padding:40px;">
                    <i class="fas fa-search" style="font-size:24px; margin-bottom:10px; display:block;"></i>
                    No users found
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = users.map(u => `
        <tr>
            <td>${u.user_id}</td>
            <td>${u.username}</td>
            <td>${u.email}</td>
            <td class="actions-cell">
                <button class="btn-edit" onclick="editUser(${u.user_id})">
                    Edit
                </button>
                <button class="btn-delete" onclick="deleteUser(${u.user_id})">
                    Delete
                </button>
            </td>
        </tr>
    `).join('');
}

async function loadUsers() {
    try {
        const res = await fetch('users.php');
        const data = await res.json();
        if (data.success) {
            document.getElementById('usersTableBody').innerHTML = data.users.map(u => `
                <tr>
                    <td>${u.user_id}</td>
                    <td>${u.username}</td>
                    <td>${u.email}</td>
                    <td>
                        <button onclick="editUser(${u.user_id})">Edit</button>
                        <button onclick="deleteUser(${u.user_id})" style="background:red;color:white;">Delete</button>
                    </td>
                </tr>
            `).join('');
        }
    } catch(e) { console.error(e); }
}

async function deleteUser(id) {
    if (!confirm("Supprimer ?")) return;
    
    const res = await fetch('users.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id: id })
    });
    
    const result = await res.json();
    alert(result.success ? "Supprimé !" : "Échec");
    loadUsers();
}

function editUser(id) {
    location.href = `Edit-User.html?id=${id}`;
}


// ──────────────────────────────
//  REAL-TIME SEARCH + FILTERING
// ──────────────────────────────
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
    
    // Show clear button if there's any filter applied
    if (query !== '' || filterType !== 'all') {
        clearBtn.style.display = 'inline-flex';
    } else {
        clearBtn.style.display = 'none';
    }
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterDropdown').value = 'all';
    document.getElementById('clearBtn').style.display = 'none';
    renderTable(allUsers);
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('filterDropdown').addEventListener('change', applyFilters);

// Load users when page opens
loadUsers();