
const eventsNavButton = document.querySelector('.nav-item[data-tab="events"]');
const eventSubmenu = eventsNavButton.parentElement.querySelector('.submenu');
const dropdownArrow = eventsNavButton.querySelector('.dropdown-arrow');

let isSubmenuOpen = false; 
eventSubmenu.style.display = "none";
eventSubmenu.classList.remove("expanded");
eventsNavButton.addEventListener("click", function(e) {
    e.preventDefault(); 

    isSubmenuOpen = !isSubmenuOpen;

    if (isSubmenuOpen) {
        eventSubmenu.style.display = "block";
        eventSubmenu.classList.add("expanded");
        if (dropdownArrow) dropdownArrow.style.transform = "rotate(180deg)";
    } else {
        eventSubmenu.style.display = "none";
        eventSubmenu.classList.remove("expanded");
        if (dropdownArrow) dropdownArrow.style.transform = "rotate(0deg)";
    }
});
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: ${type === 'success' ? '#10B981' : type === 'error' ? '#EF4444' : '#3B82F6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
    `;
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
    toast.innerHTML = `<span style="font-size: 1.25rem;">${icon}</span> ${message}`;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

 // =============================
    // SPONSOR PART
// =============================
async function renderSponsors() {
    const container = document.getElementById("sponsorsGrid");
    if (!container) return;
    let dbSponsors = [];
    try {
        const res = await fetch("../../../../controller/Sponsor/getSponsors.php");

        dbSponsors = await res.json();
    } catch (err) {
        console.error("Sponsor DB load error:", err);
    }
    const dbConverted = dbSponsors.map(s => ({
        id: s.id,
        name: s.sponsor_name,
        type: s.sponsorship_type,
        badgeType: s.sponsorship_type.toLowerCase(),
        contactEmail: s.contact_email,
        contactPhone: s.contact_phone,
        eventId: s.event_id,
        eventName: "New Sponsor Event",
        contributionNotes: s.contribution_notes || "",
        contractStatus: "Pending"
    }));
   const finalSponsors = [...dbConverted];
    container.innerHTML = finalSponsors
        .map(s => `
            <div class="event-card">
                <div class="event-header">
                    <div>
                        <h3 class="event-title">${s.name}</h3>
                        <p class="event-description">${s.eventName}</p>
                    </div>
                    <span class="badge sponsor-${s.badgeType}">${s.type}</span>
                </div>

                <div class="event-meta">
                    <div class="event-meta-item">
                        <i class="fas fa-envelope"></i>
                        <span>${s.contactEmail}</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="fas fa-phone"></i>
                        <span>${s.contactPhone}</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="fas fa-file-contract"></i>
                        <span>Status: ${s.contractStatus}</span>
                    </div>
                </div>

                <p class="notes">${s.contributionNotes}</p>

                <div class="event-actions">
                    <button class="btn-secondary" onclick="openEditSponsorModal('${s.id}')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-secondary" onclick="deleteSponsor('${s.id}')">
    <i class="fas fa-times-circle"></i> Cancel Sponsor
</button>

 <button class="btn-secondary" onclick="openContactSponsorModal('${s.id}')">
    <i class="fas fa-envelope"></i> Contact
</button>

                </div>
            </div>
            
        `)
        .join("");
}
function openContactSponsorModal(id) {
    document.getElementById("contact_sponsor_id").value = id;
    showModal("contactSponsorModal");
}
async function approveSponsorAction() {
    const id = document.getElementById("contact_sponsor_id").value;
    const reason = document.getElementById("contact_sponsor_reason").value;

    // 1️⃣ Save contact message
    let saveRes = await fetch("../../../../controller/Sponsor/saveSponsorMessage.php", {
        method: "POST",
        body: new URLSearchParams({
            sponsor_id: id,
            message: reason,
            action: "approve"
        })
    });

    let saveTxt = await saveRes.text();
    if (!saveTxt.includes("success")) {
        showToast("Error saving message: " + saveTxt, "error");
        return; 
    }

    // 2️⃣ Approve sponsor
    let approveRes = await fetch("../../../../controller/Sponsor/approveSponsor.php", {
        method: "POST",
        body: new URLSearchParams({
            id: id,
            reason: reason
        })
    });

    let approveTxt = await approveRes.text();

    if (approveTxt.includes("success")) {
        closeModal("contactSponsorModal");
        showToast("Sponsor approved!", "success");
        switchTab("sponsors");
    } else {
        showToast("ERROR: " + approveTxt, "error");
    }
}

async function cancelSponsorAction() {
    const id = document.getElementById("contact_sponsor_id").value;
    const reason = document.getElementById("contact_sponsor_reason").value;

    // 1️⃣ Save contact message
    let saveRes = await fetch("../../../../controller/Sponsor/saveSponsorMessage.php", {
        method: "POST",
        body: new URLSearchParams({
            sponsor_id: id,
            message: reason,
            action: "cancel"
        })
    });

    let saveTxt = await saveRes.text();
    if (!saveTxt.includes("success")) {
        showToast("Error saving message: " + saveTxt, "error");
        return;
    }

    // 2️⃣ Cancel sponsor
    let cancelRes = await fetch(
        "../../../../controller/Sponsor/deleteSponsor.php?id=" + id +
        "&reason=" + encodeURIComponent(reason)
    );
    let cancelTxt = await cancelRes.text();

    if (cancelTxt.includes("success")) {
        closeModal("contactSponsorModal");
        showToast("Sponsor cancelled!", "success");
        switchTab("sponsors");
    } else {
        showToast("ERROR: " + cancelTxt, "error");
    }
}
async function sendSponsorContact() {
    const id = document.getElementById("contact_sponsor_id").value;
    const message = document.getElementById("contact_sponsor_reason").value;

    let res = await fetch("../../../../controller/Sponsor/saveSponsorMessage.php", {
        method: "POST",
        body: new URLSearchParams({
            sponsor_id: id,
            message: message
        })
    });

    let txt = await res.text();

    if (txt.includes("success")) {
        closeModal("contactSponsorModal");
        showToast("Message saved!", "success");
    } else {
        showToast("Error: " + txt, "error");
    }
}


function toggleSponsorContact(id) {
    let box = document.getElementById("contact-box-" + id);
    if (!box) return;
    box.style.display = box.style.display === "none" ? "block" : "none";
}
function approveSponsor(id) {
    let reason = document.getElementById("reason-" + id).value;

    fetch("../../../../controller/Sponsor/approveSponsor.php", {
        method: "POST",
        body: new URLSearchParams({
            id: id,
            reason: reason
        })
    })
    .then(res => res.text())
    .then(txt => {
        if (txt.includes("success")) {
            showToast("Sponsor approved!", "success");
            switchTab('sponsors');
        } else {
            showToast("ERROR: " + txt, "error");
        }
    });
}

function openEditSponsorModal(id) {

    // PURE PHP - NO JSON
    fetch("../../../../controller/Sponsor/getSponsors.php?id=" + id, {
        method: "GET"
    })
    .then(response => response.text())
    .then(data => {

        let s = data.split("|");

        document.getElementById("edit_sponsor_id").value = s[0];
        document.getElementById("edit_sponsor_name").value = s[1];
        let type = s[2].trim().toLowerCase();
if (type === "equipement" || type === "equipment") {
    type = "Equipment";
} else if (type === "financial") {
    type = "Financial";
} else if (type === "media") {
    type = "Media";
} else if (type === "other") {
    type = "Other";
}

document.getElementById("edit_sponsor_type").value = type;

        document.getElementById("edit_sponsor_email").value = s[3];
        document.getElementById("edit_sponsor_phone").value = s[4];
        document.getElementById("edit_sponsor_notes").value = s[5];
        document.getElementById("edit_sponsor_event_id").value = s[6];

        showModal("editSponsorModal");
    });
}

document.getElementById("addSponsorForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    let formData = new FormData(document.getElementById("addSponsorForm"));

    let res = await fetch("../../../../controller/Sponsor/addSponsor.php", {
        method: "POST",
        body: formData
    });

    let txt = await res.text();

    if (txt.includes("success")) {

        closeModal("createSponsorModal"); // ✅ FIXED

        await renderSponsors(); // 🔥 update dashboard immediately

        showToast("Sponsor added successfully!", "success");

        document.getElementById("addSponsorForm").reset();
    } 
    else {
        showToast("ERROR: " + txt, "error");
    }
});

async function saveSponsorChanges() {
    let formData = new FormData();
    formData.append("id", document.getElementById("edit_sponsor_id").value);
    formData.append("sponsor_name", document.getElementById("edit_sponsor_name").value);
    formData.append("sponsorship_type", document.getElementById("edit_sponsor_type").value);
    formData.append("contact_email", document.getElementById("edit_sponsor_email").value);
    formData.append("contact_phone", document.getElementById("edit_sponsor_phone").value);
    formData.append("contribution_notes", document.getElementById("edit_sponsor_notes").value);
    formData.append("contract_status", document.getElementById("edit_sponsor_status").value);
    formData.append("event_id", document.getElementById("edit_sponsor_event_id").value);

    let res = await fetch("../../../../controller/Sponsor/updateSponsor.php", {
        method: "POST",
        body: formData
    });

    let txt = await res.text();

  if (txt.includes("success")) {
   

    closeModal("editSponsorModal");
    switchTab('sponsors');
    renderSponsors();     
    showToast("Sponsor updated!", "success");
}}
async function deleteSponsor(id) {
    const res = await fetch("../../../../controller/Sponsor/deleteSponsor.php?id=" + id);
    const txt = await res.text();

    if (txt.includes("success")) {
        showToast("Sponsor cancelled!", "success");

        // Remove sponsor card without refreshing
        await renderSponsors();
    } else {
        showToast("Error deleting sponsor: " + txt, "error");
    }
}
let realSponsors = []; // filled from sponsorlist.php
function filterSponsors() {
    const filter = document.getElementById("sponsorTypeFilter").value;
    const container = document.getElementById("sponsorsGrid");

    if (!container) return;

    container.innerHTML = ""; // Clear existing sponsors

    let filtered = realSponsors;

    // Match the lowercase version
    if (filter !== "all") {
        filtered = realSponsors.filter(s => s.badgeType === filter.toLowerCase());
    }

    // Render cards
    filtered.forEach(s => {
        container.innerHTML += `
            <div class="event-card">
                <div class="event-header">
                    <div>
                        <h3 class="event-title">${s.name}</h3>
                        <p class="event-description">Event ID: ${s.eventId}</p>
                    </div>
                    <span class="badge sponsor-${s.badgeType}">
                        ${s.type}
                    </span>
                </div>

                <div class="event-meta">
                    <div class="event-meta-item">
                        <i class="fas fa-envelope"></i>
                        <span>${s.contactEmail}</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="fas fa-phone"></i>
                        <span>${s.contactPhone}</span>
                    </div>
                </div>

                <p class="notes">${s.contributionNotes}</p>

                <div class="event-actions">
                    <button class="btn-secondary" onclick="openEditSponsorModal('${s.id}')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-danger" onclick="deleteSponsor('${s.id}')">
                        <i class="fas fa-times-circle"></i> Delete
                    </button>
                </div>
            </div>
        `;
    });
}



// ====================================
// PARTICIPANTS PART
// ====================================

// Handle participant edit form submission
document.addEventListener('DOMContentLoaded', function() {
    const editParticipantForm = document.getElementById('editParticipantForm');
    
    if (editParticipantForm) {
        editParticipantForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const participantId = formData.get('id');
            
            try {
                const response = await fetch("../../../../controller/updateParticipant.php", {
                    method: "POST",
                    body: formData
                });
                
                const result = await response.text();
                
if (result.includes("success")) {

    // Update table row
    updateParticipantRow(participantId, formData);

    // Update dashboard card
    updateDashboardParticipant(participantId, formData);

    // Update dashboard data source
    let p = participantsData.find(x => x.id == participantId);
    if (p) {
        p.firstName = formData.get("firstName");
        p.lastName  = formData.get("lastName");
        p.email     = formData.get("email");
        p.phone     = formData.get("phone");
        p.event_id  = formData.get("event_id");
    }

    // Live dashboard updates
    renderRecentParticipants();
    updateParticipantStats();

    showToast('Participant updated successfully!', 'success');
    closeModal('editParticipantModal');
}

 else {
                    showToast('Error updating participant: ' + result, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Network error while updating participant', 'error');
            }
        });
    }
});
function updateDashboardParticipant(id, formData) {
    const card = document.getElementById("participant-card-" + id);
    if (!card) return;

    card.querySelector(".participant-name").textContent =
        formData.get("firstName") + " " + formData.get("lastName");

    card.querySelector(".participant-event").textContent =
        formData.get("event_id");
}


function updateParticipantRow(id, formData) {
    const row = document.getElementById(`participant-row-${id}`);
    if (!row) return;

    const cells = row.cells;

    // Update columns
    cells[0].textContent = formData.get("firstName");
    cells[1].textContent = formData.get("lastName");
    cells[2].textContent = formData.get("email");
    cells[3].textContent = formData.get("phone");
    cells[4].textContent = formData.get("event_id");
}


// Function to handle participant deletion (optional enhancement)
async function deleteParticipant(id) {
    if (!confirm('Are you sure you want to delete this participant?')) return;
    
    try {
        const formData = new FormData();
        formData.append('id', id);
        
        const response = await fetch("../../../../controller/deleteParticipant.php", {
            method: "POST",
            body: formData
        });
        
        const result = await response.text();
        
if (result.includes("success")) {

    // Update table row
    updateParticipantRow(participantId, formData);

    // Update dashboard card
    updateDashboardParticipant(participantId, formData);

    // Update dashboard data source
    let p = participantsData.find(x => x.id == participantId);
    if (p) {
        p.firstName = formData.get("firstName");
        p.lastName  = formData.get("lastName");
        p.email     = formData.get("email");
        p.phone     = formData.get("phone");
        p.event_id  = formData.get("event_id");
    }

    // Live dashboard updates
    renderRecentParticipants();
    updateParticipantStats();

    showToast('Participant updated successfully!', 'success');
    closeModal('editParticipantModal');
}

 else {
            showToast('Error deleting participant: ' + result, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Network error while deleting participant', 'error');
    }
}
async function renderParticipants() {
    let response = await fetch("../../../../controller/getParticipants.php");
    let participants = await response.json();

    const container = document.getElementById("participantsTableBody");
    container.innerHTML = "";

    participants.forEach(p => {
        container.innerHTML += `
            <tr id="participant-row-${p.id}">
                <td>${p.firstName}</td>
                <td>${p.lastName}</td>
                <td>${p.email}</td>
                <td>${p.phone}</td>
                <td>${p.event_id}</td>
                <td>
                    <button class="btn-primary" onclick="openEditModal(
                        '${p.id}',
                        '${p.firstName}',
                        '${p.lastName}',
                        '${p.email}',
                        '${p.phone}',
                        '${p.event_id}'
                    )">Edit</button>
                </td>
            </tr>
        `;
    });
}

function closeEditModal() {
    document.getElementById("editParticipantModal").classList.remove("active");
}
function closeBanner() {
    const banner = document.querySelector('.download-banner');
    if (banner) {
        banner.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => {
            banner.style.display = 'none';
        }, 300);
    }
}

function closeEditParticipantModal() {
    document.getElementById("editParticipantModal").classList.remove("active");
}

async function deleteParticipant(id) {
    if (!confirm("Delete participant?")) return;

    let response = await fetch("../../../../controller/deleteParticipant.php?id=" + id, {
        method: "GET"
    });

    let result = await response.text();

    if (result.includes("success")) {

        // Remove row without refreshing
        const row = document.getElementById("participant-row-" + id);
        if (row) row.remove();

        showToast("Participant deleted!", "success");

    } else {
        showToast("Error deleting participant: " + result, "error");
    }
}
async function saveParticipantChanges() {

    let id = document.getElementById("edit_id").value;
    let first = document.getElementById("edit_firstName").value;
    let last = document.getElementById("edit_lastName").value;
    let email = document.getElementById("edit_email").value;
    let phone = document.getElementById("edit_phone").value;
    let event_id = document.getElementById("edit_event_id").value;
    let formData = new FormData();

    formData.append("id", id);
    formData.append("firstName", first);
    formData.append("lastName", last);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("event_id", event_id);

    const response = await fetch("../../../../controller/updateParticipant.php", {
        method: "POST",
        body: formData
    });

    const result = await response.text();

    if (result.includes("success")) {

        // 🔥 UPDATE CARD VIEW (your nice dashboard design)
        let card = document.getElementById("participant-card-" + id);
        if (card) {
            let container = card.querySelector("div");
            container.innerHTML = `
                <strong>${first} ${last}</strong><br>
                ${email}<br>
                ${phone}<br>
                <small>${new Date().toISOString().slice(0,19).replace("T"," ")}</small>
            `;
        }

   
        closeEditParticipantModal();
        showToast("Participant updated!", "success");
    } 
    else {
        alert(result);
    }
}
function openEditModal(id, firstName, lastName, email, phone, eventId) {

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_firstName").value = firstName;
    document.getElementById("edit_lastName").value = lastName;
    document.getElementById("edit_email").value = email;
    document.getElementById("edit_phone").value = phone;
    document.getElementById("edit_event_id").value = eventId;

    showModal('editParticipantModal');
}
document.addEventListener("DOMContentLoaded", async () => {

    // Load REAL participants from PHP
    try {
        participantsData = await fetch("../../../../controller/getParticipants.php")
            .then(r => r.json());

        console.log("Loaded participants:", participantsData.length);
    } catch (e) {
        console.error("Failed to load participants:", e);
        participantsData = [];
    }


});


// ====================================
// SIDE_NAV
// ====================================
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const selectedTab = document.getElementById(`${tabName}-content`);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
        const selectedNav = document.querySelector(`[data-tab="${tabName}"]`);
    if (selectedNav) {
        selectedNav.classList.add('active');
    }
        if (tabName === 'events') {
    renderEvents();
     renderCalendar(); 

    } else if (tabName === 'participants') {
        renderParticipants();
    } else if (tabName === 'sponsors') {
        renderSponsors();
    } else if (tabName === 'analytics') {
        renderAnalytics();
    }
    if (tabName === 'tasksTab') {
    loadTasksFromDB();
}


}
// ====================================
// MODAL FUNCTIONS
// ====================================

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
};

function updateProgressValue(value) {
    document.getElementById('progressValue').textContent = value;
}

// ====================================
// NOTIFICATION & USER MENU
// ====================================

function toggleNotifications() {
    const panel = document.getElementById('notificationPanel');
    if (panel) {
        panel.classList.toggle('active');
    }
    
    const userMenu = document.getElementById('userMenu');
    if (userMenu && userMenu.classList.contains('active')) {
        userMenu.classList.remove('active');
    }
}

function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    if (menu) {
        menu.classList.toggle('active');
    }
    const notifPanel = document.getElementById('notificationPanel');
    if (notifPanel && notifPanel.classList.contains('active')) {
        notifPanel.classList.remove('active');
    }
}

document.addEventListener('click', function(event) {
    const notifPanel = document.getElementById('notificationPanel');
    const userMenu = document.getElementById('userMenu');
    const notifBtn = event.target.closest('.icon-btn');
    const userProfile = event.target.closest('.user-profile');
    
    if (notifPanel && notifPanel.classList.contains('active') && !notifBtn && !notifPanel.contains(event.target)) {
        notifPanel.classList.remove('active');
    }
    
    if (userMenu && userMenu.classList.contains('active') && !userProfile && !userMenu.contains(event.target)) {
        userMenu.classList.remove('active');
    }
});

// ====================================
// RENDER FUNCTIONS
// ====================================


document.getElementById("editEventForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const res = await fetch("../../../../controller/Event/eventupdate.php", {
        method: "POST",
        body: formData
    });

    const txt = await res.text();
    console.log("EVENT UPDATE:", txt);

    if (txt.includes("success")) {
        closeModal("editEventModal");
        showToast("Event updated successfully!", "success");
        await loadRealEvents();
    } else {
        showToast("Error updating event: " + txt, "error");
    }
});

function renderRecentParticipants() {
    const container = document.getElementById('recentParticipantsList');
    if (!container) return;
    
    const recent = await (fetch("../../../../controller/getParticipants.php")).json();
    container.innerHTML = recent.map(participant => {
        const initials = participant.name.split(' ').map(n => n[0]).join('');
        return `
            <div class="participant-item" id="participant-card-${participant.id}">
                <div class="participant-info">
                    <div class="participant-avatar">${initials}</div>
                    <div class="participant-details">
                        <div class="participant-name">${participant.name}</div>
                        <div class="participant-event">${participant.eventName}</div>
                    </div>
                </div>
                <span class="badge participant-${participant.status}">${participant.status}</span>
            </div>
        `;
    }).join('');
}
function updateTotalParticipants() {
    const total = participantsData.length;
    document.getElementById("statTotal").textContent = total;
}
function updateRegisteredToday() {
    const today = new Date().toISOString().slice(0, 10);

    const count = participantsData.filter(p => {
        return p.created_at && p.created_at.startsWith(today);
    }).length;

    document.getElementById("statToday").textContent = count;
}

function filterParticipants() {
    showToast('Filter participants feature coming soon!', 'info');
}

function exportParticipants() {
    showToast('Exporting participants list...', 'success');
}

function approveParticipant(id) {
    const participant = participantsData.find(p => p.id === id);
    if (participant) {
        participant.status = 'approved';
     
        renderRecentParticipants();
        updateStatistics();
        showToast(`${participant.name} has been approved!`, 'success');
    }
}

function rejectParticipant(id) {
    const participant = participantsData.find(p => p.id === id);
    if (participant) {
        participant.status = 'rejected';
   
        renderRecentParticipants();
        updateStatistics();
        showToast(`${participant.name} has been rejected.`, 'error');
    }
}

function callParticipant(id) {
    const participant = participantsData.find(p => p.id === id);
    if (participant) {
        showToast(`Calling ${participant.name}...`, 'info');
    }
}

function emailParticipant(id) {
    const participant = participantsData.find(p => p.id === id);
    if (participant) {
        showToast(`Sending email to ${participant.name}...`, 'info');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    renderParticipants();
});

function filterSponsors() {
    renderSponsors();
}

function editSponsor(id) {
    showToast('Edit sponsor feature coming soon!', 'info');
}

function viewSponsor(id) {
    showToast('View sponsor details feature coming soon!', 'info');
}

function contactSponsor(id) {
    showToast('Contact sponsor feature coming soon!', 'info');
}



function renderAnalytics() {
    const container = document.getElementById('analyticsContent');
    if (!container) return;
    
    const totalParticipants = participantsData.length;
    const approvedParticipants = participantsData.filter(p => p.status === 'approved').length;
    const pendingParticipants = participantsData.filter(p => p.status === 'pending').length;
    
    container.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border: 2px solid rgba(16, 185, 129, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Total Events</h3>
                    <p class="stat-value">${eventsData.length}</p>
                    <p class="stat-trend positive">↑ 12.5% this month</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05)); border: 2px solid rgba(245, 158, 11, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Total Participants</h3>
                    <p class="stat-value">1,011</p>
                    <p class="stat-trend positive">↑ 25% growth</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05)); border: 2px solid rgba(59, 130, 246, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Active Sponsors</h3>
                    <p class="stat-value">50</p>
                    <p class="stat-trend positive">↑ 15.3% increase</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(168, 85, 247, 0.05)); border: 2px solid rgba(168, 85, 247, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #A855F7, #9333EA);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Approved Participants</h3>
                    <p class="stat-value">${approvedParticipants}</p>
                    <p class="stat-trend">${totalParticipants} total</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(236, 72, 153, 0.05)); border: 2px solid rgba(236, 72, 153, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #EC4899, #DB2777);">
                    <i class="fas fa-award"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Success Rate</h3>
                    <p class="stat-value">92%</p>
                    <p class="stat-trend">Event completion</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05)); border: 2px solid rgba(99, 102, 241, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366F1, #4F46E5);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Pending Reviews</h3>
                    <p class="stat-value">${pendingParticipants}</p>
                    <p class="stat-trend">Needs attention</p>
                </div>
            </div>
        </div>
        
        <div class="widget-card">
            <h3 style="color: #243B53; margin-bottom: 1rem;">Performance Overview</h3>
            <p style="color: #4E5F7C;">Analytics charts and detailed insights would be displayed here using a charting library like Chart.js or ApexCharts.</p>
            <div style="margin-top: 1.5rem; padding: 3rem; background: rgba(184, 202, 224, 0.1); border-radius: 0.75rem; text-align: center; color: #6B85A8;">
                <i class="fas fa-chart-pie" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Chart visualization placeholder</p>
            </div>
        </div>
    `;
}
function renderCalendar() {
    const container = document.getElementById('calendar');
    if (!container) return;

    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];

    // 👉 Take days from realEvents (only events in this month)
    const eventDays = realEvents
        .map(ev => {
            const d = new Date(ev.date);
            if (isNaN(d)) return null;
            if (d.getMonth() !== currentMonth || d.getFullYear() !== currentYear) return null;
            return d.getDate();
        })
        .filter(Boolean);

    let html = `
        <div class="calendar-header">
            <span>${monthNames[currentMonth]} ${currentYear}</span>
        </div>
        <div class="calendar-grid">
            <div class="calendar-day-header">Su</div>
            <div class="calendar-day-header">Mo</div>
            <div class="calendar-day-header">Tu</div>
            <div class="calendar-day-header">We</div>
            <div class="calendar-day-header">Th</div>
            <div class="calendar-day-header">Fr</div>
            <div class="calendar-day-header">Sa</div>
    `;

    // Empty cells before first day
    for (let i = 0; i < firstDay; i++) {
        html += '<div class="calendar-day empty"></div>';
    }

    // Days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = day === today.getDate();
        const hasEvent = eventDays.includes(day);

        const classes = [
            'calendar-day',
            isToday ? 'today' : '',
            hasEvent ? 'has-event' : ''
        ].join(' ').trim();

        html += `<div class="${classes}">${day}</div>`;
    }

    html += '</div>';
    container.innerHTML = html;
}


function renderAnalytics() {
    const container = document.getElementById('analyticsContent');
    if (!container) return;
    
    const totalParticipants = participantsData.length;
    const approvedParticipants = participantsData.filter(p => p.status === 'approved').length;
    const pendingParticipants = participantsData.filter(p => p.status === 'pending').length;
    
    container.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border: 2px solid rgba(16, 185, 129, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Total Events</h3>
                    <p class="stat-value">${eventsData.length}</p>
                    <p class="stat-trend positive">↑ 12.5% this month</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05)); border: 2px solid rgba(245, 158, 11, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Total Participants</h3>
                    <p class="stat-value">1,011</p>
                    <p class="stat-trend positive">↑ 25% growth</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05)); border: 2px solid rgba(59, 130, 246, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Active Sponsors</h3>
                    <p class="stat-value">50</p>
                    <p class="stat-trend positive">↑ 15.3% increase</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(168, 85, 247, 0.05)); border: 2px solid rgba(168, 85, 247, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #A855F7, #9333EA);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Approved Participants</h3>
                    <p class="stat-value">${approvedParticipants}</p>
                    <p class="stat-trend">${totalParticipants} total</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(236, 72, 153, 0.05)); border: 2px solid rgba(236, 72, 153, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #EC4899, #DB2777);">
                    <i class="fas fa-award"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Success Rate</h3>
                    <p class="stat-value">92%</p>
                    <p class="stat-trend">Event completion</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05)); border: 2px solid rgba(99, 102, 241, 0.2);">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366F1, #4F46E5);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Pending Reviews</h3>
                    <p class="stat-value">${pendingParticipants}</p>
                    <p class="stat-trend">Needs attention</p>
                </div>
            </div>
        </div>
        
        <div class="widget-card">
            <h3 style="color: #243B53; margin-bottom: 1rem;">Performance Overview</h3>
            <p style="color: #4E5F7C;">Analytics charts and detailed insights would be displayed here using a charting library like Chart.js or ApexCharts.</p>
            <div style="margin-top: 1.5rem; padding: 3rem; background: rgba(184, 202, 224, 0.1); border-radius: 0.75rem; text-align: center; color: #6B85A8;">
                <i class="fas fa-chart-pie" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Chart visualization placeholder</p>
            </div>
        </div>
    `;
}

// ====================================
// FORM HANDLERS
// ====================================

document.addEventListener('DOMContentLoaded', function() {
    // Create Event Form
    const createEventForm = document.getElementById('createEventForm');
    if (createEventForm) {
        createEventForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const newEvent = {
                id: String(eventsData.length + 1),
                title: formData.get('title'),
                description: formData.get('description'),
                date: formData.get('date'),
                deadline: formData.get('deadline'),
                location: formData.get('location'),
                status: formData.get('status'),
                participantCount: 0,
                sponsorCount: 0,
                assignedManager: 'Stella Walton',
                category: formData.get('category') || 'General'
            };
            closeModal('createEventModal');
            this.reset();
            document.getElementById("createEventForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // SEND TO PHP
    let response = await fetch("../../../../controller/Event/eventstore.php", {
        method: "POST",
        body: formData
    });

    let text = await response.text();
    console.log("EVENT SAVE:", text);

    closeModal("createEventModal");
    this.reset();

    showToast("Event saved!", "success");

    loadRealEvents(); // refresh dashboard with REAL events
});

            showToast('Event created successfully!', 'success');
        });
    }
   
    // Global search
    const globalSearch = document.getElementById('globalSearch');
    if (globalSearch) {
        globalSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            if (query.length > 2) {
                showToast(`Searching for: ${query}`, 'info');
            }
        });
    }
});

// ====================================
// ADDITIONAL FEATURES
// ====================================

function sendBulkEmail() {
    showToast('Sending bulk email to all participants...', 'success');
}

// ====================================
// UPDATE STATISTICS
// ====================================

function updateStatistics() {
    const pendingCount = participantsData.filter(p => p.status === 'pending').length;
    document.getElementById('totalEvents').textContent = eventsData.length;
    document.getElementById('pendingRequests').textContent = pendingCount;
}

// ====================================
// SET CURRENT DATE
// ====================================

function setCurrentDate() {
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        const options = {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            weekday: 'long'
        };
        dateElement.textContent = new Date().toLocaleDateString('en-US', options);
    }
}

// ====================================
// KEYBOARD SHORTCUTS
// ====================================

document.addEventListener('keydown', function(event) {
    // Ctrl + K for search
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();
        document.getElementById('globalSearch')?.focus();
    }
    
    // Esc to close modals
    if (event.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(modal => {
            modal.classList.remove('active');
        });
        document.getElementById('notificationPanel')?.classList.remove('active');
        document.getElementById('userMenu')?.classList.remove('active');
    }
});

// ====================================
// INITIALIZATION
// ====================================
document.addEventListener('DOMContentLoaded', function() {
    // Setup navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const tab = this.getAttribute('data-tab');
            switchTab(tab);
        });
    });
    
    // Set current date
    setCurrentDate();
    // Show dashboard tab by default
    switchTab('dashboard');
    
    console.log('✨ Lumina Dashboard V23 initialized successfully!');
    console.log('👥 Total Participants:', participantsData.length);
    console.log('📋 Total Tasks:', tasksData.length);
    console.log('');
    console.log('⌨️  Keyboard Shortcuts:');
    console.log('   Ctrl + K: Focus search');
    console.log('   Esc: Close modals/panels');
    console.log('');
    console.log('💾 Download template files using the banner at the top!');
});


// Add CSS animations for toast
const style = document.createElement('style');
style.textContent = `
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
`;
document.head.appendChild(style);
// ====================================
// EVENT PART
// ====================================
let realEvents = [];
const statusColors = {
    "upcoming": "#1a73e8",      // Blue
    "in-progress": "#8e44ad",   // Purple
    "completed": "#2ecc71",     // Green
    "cancelled": "#e74c3c"      // Red
};

function renderEvents() {
    const container = document.getElementById("eventsGrid");
    if (!container) return;

    container.innerHTML = "";

    realEvents.forEach(ev => {
        const color = statusColors[ev.status.toLowerCase()] || "#888"; // fallback

        container.innerHTML += `
            <div class="event-card">

                <div class="event-header">
                    <h3 class="event-title">${ev.title}</h3>

                    <span class="status-badge"
                        style="
        background-color: ${color};
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    "
                    >
                        ${ev.status}
                    </span>
                </div>

                <p class="event-description">${ev.description}</p>

                <div class="event-meta">
                    <p><i class="fas fa-calendar"></i> ${ev.date}</p>
                    <p><i class="fas fa-clock"></i> Deadline: ${ev.deadline}</p>
                    <p><i class="fas fa-map-marker-alt"></i> ${ev.location}</p>
                    <p><i class="fas fa-tag"></i> ${ev.category}</p>
                </div>

                <div class="event-actions">
                    <button class="btn-secondary" onclick="openEditEventModal(${ev.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>

                    <button class="btn-secondary" onclick="openEventDetails(${ev.id})">
                        <i class="fas fa-eye"></i> Details
                    </button>

                    <button class="btn-secondary" onclick="deleteEvent(${ev.id})">
                        <i class="fas fa-times-circle"></i> Cancel Event
                    </button>
                </div>

            </div>
        `;
    });
}

function showModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add("active");
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove("active");
    }
}
async function openEditEventModal(id) {
    console.log("📌 Opening edit modal for event ID:", id);
    
    try {
        const res = await fetch("../../../../controller/Event/getevent.php?id=" + id);
        const ev = await res.json();

        console.log("📌 EVENT DATA RECEIVED:", ev);
        
        // Fill ALL fields BEFORE showing modal
        document.getElementById("edit_event_id").value = ev.id;
        document.getElementById("edit_event_title").value = ev.title;
        document.getElementById("edit_event_description").value = ev.description;
        document.getElementById("edit_event_date").value = ev.date;
        document.getElementById("edit_event_deadline").value = ev.deadline;
        document.getElementById("edit_event_location").value = ev.location;
        document.getElementById("edit_event_status").value = ev.status;
        document.getElementById("edit_event_category").value = ev.category;

        console.log("✅ ID field set to:", document.getElementById("edit_event_id").value);
        
        // Show modal
        showModal("editEventModal");
    } catch (error) {
        console.error("❌ Error loading event:", error);
        showToast("Failed to load event data", "error");
    }
}

document.getElementById("editEventForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    // Get the ID directly from the field
    const eventId = document.getElementById("edit_event_id").value;
    console.log("📌 Event ID captured:", eventId);
    
    // DEBUG: Check what FormData will capture
    console.log("🔍 Checking ALL fields with name='id':", 
        Array.from(document.querySelectorAll('[name="id"]')).map(f => ({
            id: f.id,
            value: f.value,
            form: f.form?.id
        }))
    );
    
    // Method 1: Manual FormData (RECOMMENDED)
    const formData = new FormData();
    formData.append("id", eventId);
    formData.append("title", document.getElementById("edit_event_title").value);
    formData.append("description", document.getElementById("edit_event_description").value);
    formData.append("date", document.getElementById("edit_event_date").value);
    formData.append("deadline", document.getElementById("edit_event_deadline").value);
    formData.append("location", document.getElementById("edit_event_location").value);
    formData.append("status", document.getElementById("edit_event_status").value);
    formData.append("category", document.getElementById("edit_event_category").value);
    
    console.log("📌 FormData contents:", Object.fromEntries(formData));

    try {
        const res = await fetch("../../../../controller/Event/eventupdate.php", {
            method: "POST",
            body: formData
        });

        const text = await res.text();
        console.log("📌 UPDATE RESPONSE:", text);

        if (text.includes("success")) {
            closeModal("editEventModal");
            showToast("Event updated successfully!", "success");
            await loadRealEvents(); // Refresh the events list
        } else if (text.includes("missing_fields")) {
            console.error("❌ PHP says missing fields!");
            showToast("Error: Missing required fields (ID is empty)", "error");
            
            // Debug: Check the hidden field one more time
            console.log("🔍 Debug - Hidden field value:", document.getElementById("edit_event_id").value);
            console.log("🔍 Debug - Hidden field HTML:", document.getElementById("edit_event_id").outerHTML);
        } else {
            showToast("Error updating event: " + text, "error");
        }
    } catch (error) {
        console.error("❌ Network error:", error);
        showToast("Network error while updating event", "error");
    }
});

// ALSO ADD THIS DEBUG FUNCTION TO CHECK FOR DUPLICATE ID FIELDS:
function checkDuplicateIdFields() {
    const idFields = document.querySelectorAll('[id*="id"], [name*="id"]');
    console.log("🔍 Checking for duplicate ID fields:", idFields.length);
    
    idFields.forEach(field => {
        console.log({
            id: field.id,
            name: field.name,
            value: field.value,
            type: field.type,
            tag: field.tagName,
            parentForm: field.form?.id || 'no form'
        });
    });
}

// Run this check when page loads
document.addEventListener("DOMContentLoaded", function() {
    console.log("🚀 Page loaded - checking for duplicate ID fields...");
    checkDuplicateIdFields();
    
    // Also check when opening modal
    const originalShowModal = window.showModal;
    window.showModal = function(modalId) {
        if (modalId === "editEventModal") {
            console.log("🔍 Opening edit event modal - checking fields...");
            checkDuplicateIdFields();
        }
        return originalShowModal(modalId);
    };
});

async function deleteEvent(id) {
    if (!confirm("Are you sure you want to delete this event?")) return;

    try {
        const response = await fetch(
            "../../../../controller/Event/event_delete.php?id=" + id
        );

        const result = await response.text();
        console.log("DELETE RESPONSE:", result);

        if (result.includes("success")) {
            showToast("Event deleted successfully!", "success");
            await loadRealEvents(); // refresh dashboard
        } else {
            showToast("Failed to delete event: " + result, "error");
        }
    } catch (error) {
        console.error("DELETE ERROR:", error);
        showToast("Server error during deletion", "error");
    }
}

async function loadRealEvents() {
    try {
        let response = await fetch("../../../../controller/Event/eventlist.php");
        realEvents = await response.json();

        renderEvents();  
        filterEvents();  // <--- ADD THIS
    } catch (e) {
        console.error("Failed to load events", e);
        realEvents = [];
    }
}


document.addEventListener("DOMContentLoaded", () => {
    updateParticipantStats();
    renderCalendar();
    loadRealEvents(); // Loads events from database
});

document.getElementById("createEventForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    let response = await fetch("../../../../controller/Event/eventstore.php", {
        method: "POST",
        body: formData
    });
    let txt = await response.text();
    console.log("Event Saved:", txt);

    closeModal("createEventModal");
    this.reset();
    showToast("Event created!", "success");
    await loadRealEvents(); 
});
function editEvent(id) {
    
    openEditEventModal(id);
}
async function openEventDetails(id) {
    try {
        const raw = await fetch(url);
        const txt = await raw.text();

       

        let data;
        try {
            data = JSON.parse(txt);
        } catch (e) {
            console.error("❌ JSON PARSE ERROR:", e);
            showToast("Server sent invalid JSON", "error");
            return;
        }

        if (data.error) {
            console.error("❌ SERVER ERROR:", data.error);
            showToast("Failed to load details", "error");
            return;
        }

        // Fill event modal
        document.getElementById("detail_title").innerText = data.event.title;
        document.getElementById("detail_description").innerText = data.event.description;
        document.getElementById("detail_date").innerText = data.event.date;
        document.getElementById("detail_location").innerText = data.event.location;
        document.getElementById("detail_category").innerText = data.event.category;
        document.getElementById("detail_status").innerText = data.event.status;

        document.getElementById("detail_participants_count").innerText =
            data.participants_count;

        const sponsorList = document.getElementById("detail_sponsors_list");
        sponsorList.innerHTML = "";

        const sponsors = Array.isArray(data.sponsors) ? data.sponsors : [];

sponsors.forEach(s => {
    sponsorList.innerHTML += `<li>${s.sponsor_name}</li>`;
});

        showModal("eventDetailsModal");

    } catch (err) {
        console.error("❌ DETAILS ERR:", err);
        showToast("Error loading details", "error");
    }
}
async function openEventDetails(id) {
    try {
        const url = "../../../../controller/Event/event_details.php?id=" + id;

        
        const raw = await fetch(url);
        const txt = await raw.text();

        let data;
        try {
            data = JSON.parse(txt);
        } catch (e) {
            console.error("❌ JSON PARSE ERROR:", e);
            showToast("Server sent invalid JSON", "error");
            return;
        }

        if (data.error) {
            console.error("❌ SERVER ERROR:", data.error);
            showToast("Failed to load details", "error");
            return;
        }

        // Fill modal
        document.getElementById("detail_title").innerText = data.event.title;
        document.getElementById("detail_description").innerText = data.event.description;
        document.getElementById("detail_date").innerText = data.event.date;
        document.getElementById("detail_location").innerText = data.event.location;
        document.getElementById("detail_category").innerText = data.event.category;
        document.getElementById("detail_status").innerText = data.event.status;

        document.getElementById("detail_participants_count").innerText =
            data.participants_count;

        const sponsorList = document.getElementById("detail_sponsors_list");
        sponsorList.innerHTML = "";

        const sponsors = Array.isArray(data.sponsors) ? data.sponsors : [];

        sponsors.forEach(s => {
            sponsorList.innerHTML += `<li>${s.sponsor_name}</li>`;
        });

        showModal("eventDetailsModal");

    } catch (err) {
        console.error("❌ DETAILS ERR:", err);
        alert("DETAILS ERR: " + err);
        showToast("Error loading details", "error");
    }
}

function filterEvents() {
    const filter = document.getElementById("eventFilter").value;

    const container = document.getElementById("eventsGrid");
    container.innerHTML = ""; // clear grid

    let filteredEvents = realEvents;

    if (filter !== "all") {
        filteredEvents = realEvents.filter(ev => ev.status === filter);
    }

    // Re-render only filtered events
    filteredEvents.forEach(ev => {
        container.innerHTML += `
            <div class="event-card">

                <div class="event-header">
                    <h3 class="event-title">${ev.title}</h3>
                    <span class="status-badge ${ev.status.toLowerCase()}">
                        ${ev.status}
                    </span>
                </div>

                <p class="event-description">${ev.description}</p>

                <div class="event-meta">
                    <p><i class="fas fa-calendar"></i> ${ev.date}</p>
                    <p><i class="fas fa-clock"></i> Deadline: ${ev.deadline}</p>
                    <p><i class="fas fa-map-marker-alt"></i> ${ev.location}</p>
                    <p><i class="fas fa-tag"></i> ${ev.category}</p>
                </div>

                <div class="event-actions">
                    <button class="btn-secondary" onclick="openEditEventModal(${ev.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>

                    <button class="btn-secondary" onclick="openEventDetails(${ev.id})">
                        <i class="fas fa-eye"></i> Details
                    </button>

                    <button class="btn-secondary" onclick="deleteEvent(${ev.id})">
                        <i class="fas fa-times-circle"></i> Cancel Event
                    </button>
                </div>

            </div>
        `;
    });
}

///////////////////////////////////////////////////////////
document.addEventListener("DOMContentLoaded", animateCountUp);
function animateCount(id) {
    let el = document.getElementById(id);
    let target = parseInt(el.getAttribute("data-target"));
    let current = 0;
    let duration = 800;
    let stepTime = Math.max(Math.floor(duration / target), 20);

    let timer = setInterval(() => {
        current++;
        el.textContent = current;

        if (current >= target) {
            el.textContent = target;
            clearInterval(timer);
        }
    }, stepTime);
}

document.addEventListener("DOMContentLoaded", () => {
    animateCount("statTotal");
    animateCount("statToday"); // if you have this too
});
async function updateParticipantStats() {
    let participants = await fetch("../../../../controller/getParticipants.php")
        .then(r => r.json());

    // Total
    document.getElementById("statTotal").innerText = participants.length;

    // Today
    let today = new Date().toISOString().slice(0, 10);
    let todayCount = participants.filter(p => p.created_at.startsWith(today)).length;
    document.getElementById("statToday").innerText = todayCount;

    // By event
    let eventsDiv = document.getElementById("participantsByEvent");
    eventsDiv.innerHTML = "";

    let grouped = {};

    participants.forEach(p => {
        let event = p.event_title || "Unknown Event";
        if (!grouped[event]) grouped[event] = 0;
        grouped[event]++;
    });

    for (let e in grouped) {
        eventsDiv.innerHTML += `
            <div class="event-card">
                <h4>${e}</h4>
                <p>${grouped[e]} participants</p>
            </div>
        `;
    }
}
function animateCountUp() {
    document.querySelectorAll(".count-up").forEach(counter => {

        let target = parseInt(counter.dataset.target);
        if (isNaN(target)) return;

        let duration = 1800; 
        let start = 0;
        let startTime = null;

        function update(timestamp) {
            if (!startTime) startTime = timestamp;
            let progress = timestamp - startTime;

            // Ease-out effect for smoother animation
            let percentage = Math.min(progress / duration, 1);

            counter.textContent = Math.floor(percentage * target);

            if (progress < duration) {
                requestAnimationFrame(update);
            } else {
                counter.textContent = target; // ensure final value is exact
            }
        }

        requestAnimationFrame(update);
    });
}

document.addEventListener("DOMContentLoaded", animateCountUp);
////////////////////////////////////////////////////
/* =========================================================
   TASKS MODULE — FINAL WORKING VERSION
   ========================================================= */

// ------------ Helpers ------------
function convertStatus(id) {
    switch (Number(id)) {
        case 1: return "todo";
        case 2: return "in-progress";
        case 3: return "in-review";
        case 4: return "done";
        default: return "todo";
    }
}

function convertPriority(id) {
    switch (Number(id)) {
        case 1: return "low";
        case 2: return "medium";
        case 3: return "high";
        default: return "medium";
    }
}

function convertCategory(id) {
    switch (Number(id)) {
        case 1: return "Planning";
        case 2: return "Design";
        case 3: return "Development";
        case 4: return "Marketing";
        default: return "General";
    }
}
/* =========================================================
   TASK MANAGEMENT SYSTEM - CLEAN VERSION
   ========================================================= */
// ==================== INITIALIZATION ====================

function initTaskSystem() {
    console.log("📋 Initializing task system...");
    
    loadTasks();
    setupTaskEventListeners();
    renderAllTasks();
    
    console.log(`✅ Task system ready with ${tasksData.length} tasks`);
}

function loadTasks() {
    const saved = localStorage.getItem('lumina_tasks');
    if (saved) {
        tasksData = JSON.parse(saved);
    }
}

function saveTasks() {
    localStorage.setItem('lumina_tasks', JSON.stringify(tasksData));
}

// ==================== EVENT LISTENERS ====================

function setupTaskEventListeners() {
    // Create Task Form
    const createForm = document.getElementById('createTaskForm');
    if (createForm) {
        createForm.addEventListener('submit', createNewTask);
    }
    
    // Edit Task Form
    const editForm = document.getElementById('editTaskForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveTaskChanges();
        });
    }
    
    // Search
    const searchInput = document.getElementById('taskSearch');
    if (searchInput) {
        searchInput.addEventListener('input', searchTasks);
    }
    
    // Filter
    const filterSelect = document.getElementById('taskPriorityFilter');
    if (filterSelect) {
        filterSelect.addEventListener('change', filterTasks);
    }
    
    // Progress Slider
    const progressSlider = document.querySelector('[name="progress"]');
    if (progressSlider) {
        progressSlider.addEventListener('input', function() {
            document.getElementById('progressValue').textContent = this.value + '%';
        });
    }
}

// ==================== TASK CRUD OPERATIONS ====================

function createNewTask(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    
    const newTask = {
        id: tasksData.length > 0 ? Math.max(...tasksData.map(t => t.id)) + 1 : 1,
        title: formData.get('title'),
        description: formData.get('description'),
        status: convertStatus(parseInt(formData.get('status_id'))),
        priority: convertPriority(parseInt(formData.get('priority_id'))),
        category: convertCategory(parseInt(formData.get('category_id'))),
        assignees: formData.get('assignees') 
            ? formData.get('assignees').split(',').map(a => a.trim()).filter(a => a) 
            : [],
        progress: parseInt(formData.get('progress')),
        comments: 0,
        attachments: 0,
        created_at: new Date().toISOString().split('T')[0],
        updated_at: new Date().toISOString()
    };
    
    tasksData.push(newTask);
    saveTasks();
    
    showToast(`Task "${newTask.title}" created!`, 'success');
    
    closeModal('createTaskModal');
    form.reset();
    document.getElementById('progressValue').textContent = '0%';
    
    renderAllTasks();
}

function editTask(id) {
    const task = tasksData.find(t => t.id === id);
    if (!task) {
        showToast('Task not found!', 'error');
        return;
    }
    
    document.getElementById('edit_task_id').value = task.id;
    document.getElementById('edit_task_title').value = task.title;
    document.getElementById('edit_task_description').value = task.description;
    document.getElementById('edit_task_status').value = task.status;
    document.getElementById('edit_task_priority').value = task.priority;
    document.getElementById('edit_task_category').value = task.category;
    document.getElementById('edit_task_progress').value = task.progress;
    document.getElementById('edit_task_progress_value').textContent = task.progress + '%';
    document.getElementById('edit_task_assignees').value = task.assignees.join(', ');
    
    showModal('editTaskModal');
}

function saveTaskChanges() {
    const id = parseInt(document.getElementById('edit_task_id').value);
    const taskIndex = tasksData.findIndex(t => t.id === id);
    
    if (taskIndex === -1) {
        showToast('Task not found!', 'error');
        return;
    }
    
    tasksData[taskIndex] = {
        ...tasksData[taskIndex],
        title: document.getElementById('edit_task_title').value,
        description: document.getElementById('edit_task_description').value,
        status: document.getElementById('edit_task_status').value,
        priority: document.getElementById('edit_task_priority').value,
        category: document.getElementById('edit_task_category').value,
        progress: parseInt(document.getElementById('edit_task_progress').value),
        assignees: document.getElementById('edit_task_assignees').value
            .split(',')
            .map(a => a.trim())
            .filter(a => a),
        updated_at: new Date().toISOString()
    };
    
    saveTasks();
    closeModal('editTaskModal');
    renderAllTasks();
    
    showToast('Task updated!', 'success');
}

function deleteTask(id) {
    if (!confirm('Delete this task?')) return;
    
    const taskIndex = tasksData.findIndex(t => t.id === id);
    if (taskIndex === -1) return;
    
    const taskTitle = tasksData[taskIndex].title;
    tasksData.splice(taskIndex, 1);
    
    saveTasks();
    renderAllTasks();
    
    showToast(`Task "${taskTitle}" deleted!`, 'success');
}

// ==================== RENDER FUNCTIONS ====================

function renderAllTasks() {
    renderKanbanTasks();
    renderDashboardTasks();
    updateTaskStats();
}

function renderKanbanTasks() {
    const columns = {
        'todo': document.getElementById('todoTasks'),
        'in-progress': document.getElementById('inProgressTasks'),
        'in-review': document.getElementById('inReviewTasks'),
        'done': document.getElementById('doneTasks')
    };
    
    // Clear columns
    Object.values(columns).forEach(col => col && (col.innerHTML = ''));
    
    // Group tasks
    const statusGroups = {
        'todo': tasksData.filter(t => t.status === 'todo'),
        'in-progress': tasksData.filter(t => t.status === 'in-progress'),
        'in-review': tasksData.filter(t => t.status === 'in-review'),
        'done': tasksData.filter(t => t.status === 'done')
    };
    
    // Render each column
    Object.keys(statusGroups).forEach(status => {
        const column = columns[status];
        if (!column) return;
        
        const tasks = statusGroups[status];
        
        if (tasks.length === 0) {
            column.innerHTML = `
                <div class="empty-column-state">
                    <i class="fas fa-inbox"></i>
                    <p>No tasks here</p>
                </div>
            `;
        } else {
            tasks.forEach(task => {
                column.innerHTML += createTaskCardHTML(task);
            });
        }
    });
    
    // Update counts
    updateColumnCounts(statusGroups);
}

function createTaskCardHTML(task) {
    const priorityClass = `priority-${task.priority}`;
    const categoryColors = {
        'Planning': 'background: rgba(168, 85, 247, 0.1); color: #A855F7; border-color: #A855F7;',
        'Design': 'background: rgba(59, 130, 246, 0.1); color: #3B82F6; border-color: #3B82F6;',
        'Development': 'background: rgba(16, 185, 129, 0.1); color: #10B981; border-color: #10B981;',
        'Marketing': 'background: rgba(236, 72, 153, 0.1); color: #EC4899; border-color: #EC4899;',
        'General': 'background: rgba(156, 163, 175, 0.1); color: #6B7280; border-color: #6B7280;'
    };
    
    const progressDots = Array.from({ length: 10 }, (_, i) => 
        `<div class="progress-dot ${i < Math.round(task.progress / 10) ? 'filled' : ''}"></div>`
    ).join('');
    
    return `
        <div class="task-card ${priorityClass}" data-task-id="${task.id}">
            <div class="task-card-header">
                <div class="task-badges">
                    <span class="badge" style="${categoryColors[task.category] || categoryColors['General']}">
                        ${task.category}
                    </span>
                    <span class="badge priority-badge priority-${task.priority}">
                        ${task.priority}
                    </span>
                </div>
                <div class="task-actions">
                    <button class="btn-link" onclick="editTask(${task.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-link" onclick="deleteTask(${task.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <h4 class="task-title">${task.title}</h4>
            <p class="task-description">${task.description}</p>
            
            <div class="task-progress">
                <div class="progress-dots">${progressDots}</div>
                <span class="progress-text">${task.progress}%</span>
            </div>
            
            <div class="task-footer">
                <div class="task-assignees">
                    ${task.assignees.map(a => `<div class="task-assignee" title="${a}">${a}</div>`).join('')}
                </div>
                <div class="task-stats">
                    <div class="task-stat" title="Created ${formatDate(task.created_at)}">
                        <i class="fas fa-calendar"></i>
                        <span>${formatDateShort(task.created_at)}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderDashboardTasks() {
    const dashboard = document.getElementById('dashboard-content');
    if (!dashboard) return;
    
    // Create tasks section if it doesn't exist
    let tasksSection = document.querySelector('.dashboard-tasks-section');
    if (!tasksSection) {
        tasksSection = document.createElement('div');
        tasksSection.className = 'dashboard-tasks-section widget-card';
        tasksSection.innerHTML = `
            <div class="widget-header">
                <h3><i class="fas fa-tasks"></i> Recent Tasks</h3>
                <a href="javascript:switchTab('tasks')" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="dashboard-tasks-container" id="dashboardTasksContainer"></div>
        `;
        
        // Add to dashboard
        const firstSection = dashboard.querySelector('.analytics-header');
        if (firstSection) {
            firstSection.parentNode.insertBefore(tasksSection, firstSection.nextSibling);
        }
    }
    
    // Get recent tasks
    const recentTasks = [...tasksData]
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 4);
    
    const container = document.getElementById('dashboardTasksContainer');
    if (!container) return;
    
    if (recentTasks.length === 0) {
        container.innerHTML = `
            <div class="empty-dashboard-tasks">
                <i class="fas fa-clipboard-list"></i>
                <p>No tasks yet</p>
                <button class="btn-primary" onclick="showModal('createTaskModal')">
                    <i class="fas fa-plus"></i> Create Task
                </button>
            </div>
        `;
    } else {
        container.innerHTML = recentTasks.map(task => `
            <div class="dashboard-task-item" onclick="viewTaskDetails(${task.id})">
                <div class="task-item-header">
                    <div class="task-priority-indicator priority-${task.priority}"></div>
                    <span class="task-category">${task.category}</span>
                </div>
                <h4 class="task-item-title">${task.title}</h4>
                <p class="task-item-desc">${task.description.substring(0, 80)}${task.description.length > 80 ? '...' : ''}</p>
                <div class="task-item-footer">
                    <div class="task-assignees">
                        ${task.assignees.map(a => `<span class="assignee-badge">${a}</span>`).join('')}
                    </div>
                    <div class="task-progress-indicator">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${task.progress}%"></div>
                        </div>
                        <span>${task.progress}%</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
}

// ==================== HELPER FUNCTIONS ====================

function updateColumnCounts(groups) {
    const counts = {
        'todo': groups.todo.length,
        'in-progress': groups['in-progress'].length,
        'in-review': groups['in-review'].length,
        'done': groups.done.length
    };
    
    // Update column headers
    document.getElementById('todoColumnCount').textContent = counts.todo;
    document.getElementById('inProgressColumnCount').textContent = counts['in-progress'];
    document.getElementById('inReviewColumnCount').textContent = counts['in-review'];
    document.getElementById('doneColumnCount').textContent = counts.done;
    
    // Update stats cards
    document.getElementById('todoCount').textContent = counts.todo;
    document.getElementById('inProgressCount').textContent = counts['in-progress'];
    document.getElementById('inReviewCount').textContent = counts['in-review'];
    document.getElementById('doneCount').textContent = counts.done;
}

function updateTaskStats() {
    const total = tasksData.length;
    const completed = tasksData.filter(t => t.status === 'done').length;
    
    // Update any dashboard stats if they exist
    const stats = document.querySelector('.compact-stats');
    if (stats) {
        let taskStat = stats.querySelector('.task-stats-card');
        if (!taskStat) {
            taskStat = document.createElement('div');
            taskStat.className = 'stat-item task-stats-card';
            stats.appendChild(taskStat);
        }
        taskStat.innerHTML = `
            <div class="stat-main">
                <span class="stat-value">${total}</span>
                <span class="stat-label">Total Tasks</span>
            </div>
            <div class="stat-trend positive">
                ${completed} done
            </div>
        `;
    }
}

function searchTasks() {
    const term = document.getElementById('taskSearch').value.toLowerCase();
    
    if (!term) {
        renderKanbanTasks();
        return;
    }
    
    const filtered = tasksData.filter(task => 
        task.title.toLowerCase().includes(term) ||
        task.description.toLowerCase().includes(term) ||
        task.category.toLowerCase().includes(term)
    );
    
    // Simple filtering - just show matching tasks
    const columns = {
        'todo': document.getElementById('todoTasks'),
        'in-progress': document.getElementById('inProgressTasks'),
        'in-review': document.getElementById('inReviewTasks'),
        'done': document.getElementById('doneTasks')
    };
    
    Object.values(columns).forEach(col => col && (col.innerHTML = ''));
    
    filtered.forEach(task => {
        const column = columns[task.status];
        if (column) {
            column.innerHTML += createTaskCardHTML(task);
        }
    });
    
    if (term && filtered.length === 0) {
        showToast('No tasks found', 'warning');
    }
}

function filterTasks() {
    const priority = document.getElementById('taskPriorityFilter').value;
    
    if (priority === 'all') {
        renderKanbanTasks();
        return;
    }
    
    const filtered = tasksData.filter(task => task.priority === priority);
    
    const columns = {
        'todo': document.getElementById('todoTasks'),
        'in-progress': document.getElementById('inProgressTasks'),
        'in-review': document.getElementById('inReviewTasks'),
        'done': document.getElementById('doneTasks')
    };
    
    Object.values(columns).forEach(col => col && (col.innerHTML = ''));
    
    filtered.forEach(task => {
        const column = columns[task.status];
        if (column) {
            column.innerHTML += createTaskCardHTML(task);
        }
    });
    
    showToast(`Showing ${filtered.length} ${priority} priority tasks`, 'info');
}

function viewTaskDetails(id) {
    const task = tasksData.find(t => t.id === id);
    if (!task) return;
    
    // Simple alert for now - you can enhance with a modal
    alert(`Task: ${task.title}\n\nDescription: ${task.description}\n\nStatus: ${task.status}\nPriority: ${task.priority}\nProgress: ${task.progress}%`);
}

function convertStatus(id) {
    const map = {1: 'todo', 2: 'in-progress', 3: 'in-review', 4: 'done'};
    return map[id] || 'todo';
}

function convertPriority(id) {
    const map = {1: 'low', 2: 'medium', 3: 'high'};
    return map[id] || 'medium';
}

function convertCategory(id) {
    const map = {1: 'Planning', 2: 'Design', 3: 'Development', 4: 'Marketing'};
    return map[id] || 'General';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatDateShort(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric'
    });
}

function updateProgressValue(value) {
    document.getElementById('progressValue').textContent = value + '%';
}

function updateEditProgressValue(value) {
    document.getElementById('edit_task_progress_value').textContent = value + '%';
}

// ==================== INITIALIZE ====================

document.addEventListener('DOMContentLoaded', function() {
    initTaskSystem();
    
    // Handle tab switching
    const originalSwitchTab = window.switchTab;
    window.switchTab = function(tabName) {
        originalSwitchTab(tabName);
        
        if (tabName === 'tasks') {
            renderKanbanTasks();
        } else if (tabName === 'dashboard') {
            renderDashboardTasks();
        }
    };
});

// Make functions globally available
window.editTask = editTask;
window.deleteTask = deleteTask;
window.viewTaskDetails = viewTaskDetails;
window.updateProgressValue = updateProgressValue;
window.updateEditProgressValue = updateEditProgressValue;
window.saveTaskChanges = saveTaskChanges;
window.searchTasks = searchTasks;
window.filterTasks = filterTasks;

// Helper Functions
function convertStatus(id) {
    const map = {1: 'todo', 2: 'in-progress', 3: 'in-review', 4: 'done'};
    return map[id] || 'todo';
}

function convertPriority(id) {
    const map = {1: 'low', 2: 'medium', 3: 'high'};
    return map[id] || 'medium';
}

function convertCategory(id) {
    const map = {1: 'Planning', 2: 'Design', 3: 'Development', 4: 'Marketing'};
    return map[id] || 'General';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDateShort(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric'
    });
}

function updateEditProgressValue(value) {
    document.getElementById('edit_task_progress_value').textContent = value + '%';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initTaskSystem();
    
    // Also initialize when switching to tasks tab
    const originalSwitchTab = window.switchTab;
    window.switchTab = function(tabName) {
        originalSwitchTab(tabName);
        
        if (tabName === 'tasks') {
            console.log("📋 Loading tasks tab...");
            renderKanbanTasks();
        } else if (tabName === 'dashboard') {
            console.log("📊 Rendering dashboard tasks...");
            renderDashboardTasks();
        }
    };
});

// Make functions available globally
window.editTask = editTask;
window.deleteTask = deleteTask;
window.viewTaskDetails = viewTaskDetails;
window.updateProgressValue = function(value) {
    document.getElementById('progressValue').textContent = value + '%';
};
window.updateEditProgressValue = updateEditProgressValue;
window.saveTaskChanges = saveTaskChanges;

/////////////////////////////////////////////////////
// ====================================
// INITIALIZATION
// ====================================
document.addEventListener('DOMContentLoaded', function() {
    // Load settings first
    loadSettings();
    
    // Setup navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const tab = this.getAttribute('data-tab');
            
            // Check if tab is enabled
            if (currentSettings.permissions && currentSettings.permissions[tab] === false) {
                showToast(`Access to ${tab} is disabled in settings`, 'error');
                return;
            }
            
            switchTab(tab);
        });
    });
    
    // Set current date
    setCurrentDate();
    
    console.log('✨ Lumina Dashboard V23 initialized with settings!');
});
// ====================================
// SETTINGS MANAGEMENT
// ====================================
// ====================================
// SETTINGS MANAGEMENT
// ====================================

let currentSettings = {};

// Load settings from database
async function loadSettings() {
    try {
        console.log("🔄 Loading settings from database...");
        const response = await fetch("../../../../controller/Settings/getSettings.php");
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.settings) {
            currentSettings = data.settings;
            console.log("✅ Settings loaded successfully:", currentSettings);
            applySettingsToUI();
            applySettingsToDashboard();
            return true;
        } else {
            console.error("❌ Failed to load settings:", data.error);
            // Use default settings
            setDefaultSettings();
            return false;
        }
    } catch (error) {
        console.error("❌ Error loading settings:", error);
        // Use default settings
        setDefaultSettings();
        return false;
    }
}

// Set default settings if database fails
function setDefaultSettings() {
    currentSettings = {
        admin_name: "Stella Walton",
        admin_email: "stella.walton@lumina.com",
        default_landing_page: "dashboard",
        visible_widgets: ["analytics", "pending", "recent"],
        compact_mode: false,
        participant_registration: "manual",
        event_archive_days: 30,
        sponsor_validation: "admin",
        notifications: ["new_participant", "sponsor_request"],
        permissions: {
            events: true,
            participants: true,
            sponsors: true,
            tasks: true,
            analytics: true
        }
    };
    console.log("📝 Using default settings");
    applySettingsToUI();
}

// Apply settings to UI elements
function applySettingsToUI() {
    console.log("🎨 Applying settings to UI...");
    
    // Admin Account
    const adminName = document.getElementById("adminName");
    const adminEmail = document.getElementById("adminEmail");
    if (adminName) adminName.value = currentSettings.admin_name || "Stella Walton";
    if (adminEmail) adminEmail.value = currentSettings.admin_email || "stella.walton@lumina.com";
    
    // Dashboard Display
    const defaultLandingPage = document.getElementById("defaultLandingPage");
    if (defaultLandingPage) {
        defaultLandingPage.value = currentSettings.default_landing_page || "dashboard";
    }
    
    // Visible Widgets
    const widgetCheckboxes = document.querySelectorAll('.widget-checkbox');
    widgetCheckboxes.forEach(checkbox => {
        const isChecked = Array.isArray(currentSettings.visible_widgets) && 
                         currentSettings.visible_widgets.includes(checkbox.value);
        checkbox.checked = isChecked;
    });
    
    // Compact Mode Toggle
    const compactToggle = document.getElementById("compactModeToggle");
    const compactStatus = document.getElementById("compactModeStatus");
    if (compactToggle && compactStatus) {
        compactToggle.checked = Boolean(currentSettings.compact_mode);
        compactStatus.textContent = currentSettings.compact_mode ? "Enabled" : "Disabled";
        compactStatus.style.color = currentSettings.compact_mode ? "#10B981" : "#6B7280";
    }
    
    // Data Management
    const participantRegistration = document.getElementById("participantRegistration");
    const eventArchiveDays = document.getElementById("eventArchiveDays");
    const sponsorValidation = document.getElementById("sponsorValidation");
    
    if (participantRegistration) participantRegistration.value = currentSettings.participant_registration || "manual";
    if (eventArchiveDays) eventArchiveDays.value = currentSettings.event_archive_days || 30;
    if (sponsorValidation) sponsorValidation.value = currentSettings.sponsor_validation || "admin";
    
    // Notifications
    const notificationCheckboxes = document.querySelectorAll('.notification-checkbox');
    notificationCheckboxes.forEach(checkbox => {
        const isChecked = Array.isArray(currentSettings.notifications) && 
                         currentSettings.notifications.includes(checkbox.value);
        checkbox.checked = isChecked;
    });
    
    // Permissions
    const permissionEvents = document.getElementById("permissionEvents");
    const permissionParticipants = document.getElementById("permissionParticipants");
    const permissionSponsors = document.getElementById("permissionSponsors");
    const permissionTasks = document.getElementById("permissionTasks");
    const permissionAnalytics = document.getElementById("permissionAnalytics");
    
    if (permissionEvents) permissionEvents.checked = currentSettings.permissions?.events !== false;
    if (permissionParticipants) permissionParticipants.checked = currentSettings.permissions?.participants !== false;
    if (permissionSponsors) permissionSponsors.checked = currentSettings.permissions?.sponsors !== false;
    if (permissionTasks) permissionTasks.checked = currentSettings.permissions?.tasks !== false;
    if (permissionAnalytics) permissionAnalytics.checked = currentSettings.permissions?.analytics !== false;
    
    console.log("✅ Settings applied to UI");
}

// Apply settings to dashboard behavior
function applySettingsToDashboard() {
    console.log("⚙️ Applying settings to dashboard behavior...");
    
    // Apply compact mode
    const body = document.body;
    if (currentSettings.compact_mode) {
        body.classList.add('compact-mode');
        console.log("📐 Compact mode: Enabled");
    } else {
        body.classList.remove('compact-mode');
        console.log("📐 Compact mode: Disabled");
    }
    
    // Apply permissions to navigation
    const navItems = {
        events: document.querySelector('[data-tab="events"]'),
        participants: document.querySelector('[data-tab="participants"]'),
        sponsors: document.querySelector('[data-tab="sponsors"]'),
        tasks: document.querySelector('[data-tab="tasks"]'),
        analytics: document.querySelector('[data-tab="analytics"]')
    };
    
    Object.entries(navItems).forEach(([key, element]) => {
        if (element) {
            const isEnabled = currentSettings.permissions?.[key] !== false;
            
            if (isEnabled) {
                element.classList.remove('disabled');
                element.style.opacity = "1";
                element.style.pointerEvents = "auto";
                console.log(`✅ ${key} permission: Enabled`);
            } else {
                element.classList.add('disabled');
                element.style.opacity = "0.5";
                element.style.pointerEvents = "none";
                console.log(`❌ ${key} permission: Disabled`);
                
                // If disabled and currently active, switch to dashboard
                if (element.classList.contains('active')) {
                    element.classList.remove('active');
                    const dashboardTab = document.querySelector('[data-tab="dashboard"]');
                    if (dashboardTab) {
                        dashboardTab.classList.add('active');
                        const dashboardContent = document.getElementById('dashboard-content');
                        if (dashboardContent) dashboardContent.classList.add('active');
                        
                        // Hide current content
                        const currentContent = document.getElementById(`${key}-content`);
                        if (currentContent) currentContent.classList.remove('active');
                        
                        console.log(`🔄 Switched from ${key} to dashboard (permission disabled)`);
                    }
                }
            }
        }
    });
    
    // Show/hide widgets based on settings
    const widgets = {
        analytics: document.querySelector('.analytics-header'),
        pending: document.querySelector('.pending-requests'),
        recent: document.querySelector('.recent-activity'),
        stats: document.querySelector('.compact-stats')
    };
    
    Object.entries(widgets).forEach(([key, element]) => {
        if (element) {
            const shouldShow = Array.isArray(currentSettings.visible_widgets) && 
                             currentSettings.visible_widgets.includes(key);
            element.style.display = shouldShow ? 'block' : 'none';
            console.log(`📊 ${key} widget: ${shouldShow ? 'Visible' : 'Hidden'}`);
        }
    });
    
    console.log("✅ Dashboard behavior updated");
}

// Save all settings
async function saveAllSettings() {
    console.log("💾 Saving all settings...");
    
    // Collect all settings from UI
    const settings = {
        admin_name: document.getElementById("adminName")?.value || "Stella Walton",
        admin_email: document.getElementById("adminEmail")?.value || "stella.walton@lumina.com",
        default_landing_page: document.getElementById("defaultLandingPage")?.value || "dashboard",
        visible_widgets: Array.from(document.querySelectorAll('.widget-checkbox:checked'))
            .map(cb => cb.value),
        compact_mode: document.getElementById("compactModeToggle")?.checked || false,
        participant_registration: document.getElementById("participantRegistration")?.value || "manual",
        event_archive_days: parseInt(document.getElementById("eventArchiveDays")?.value) || 30,
        sponsor_validation: document.getElementById("sponsorValidation")?.value || "admin",
        notifications: Array.from(document.querySelectorAll('.notification-checkbox:checked'))
            .map(cb => cb.value),
        permissions: {
            events: document.getElementById("permissionEvents")?.checked || true,
            participants: document.getElementById("permissionParticipants")?.checked || true,
            sponsors: document.getElementById("permissionSponsors")?.checked || true,
            tasks: document.getElementById("permissionTasks")?.checked || true,
            analytics: document.getElementById("permissionAnalytics")?.checked || true
        }
    };
    
    console.log("📦 Settings to save:", settings);
    
    try {
        const response = await fetch("../../../../controller/Settings/saveSettings.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(settings)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            console.log("✅ Settings saved to database");
            currentSettings = settings;
            applySettingsToDashboard();
            showToast('✅ Settings saved successfully!', 'success');
            
            // Update the settings tab UI to reflect changes
            if (document.getElementById("settings-content").classList.contains('active')) {
                applySettingsToUI();
            }
        } else {
            console.error("❌ Server error saving settings:", result.error);
            showToast('❌ Error saving settings: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error("❌ Network error saving settings:", error);
        showToast('❌ Network error while saving settings: ' + error.message, 'error');
    }
}

// Update admin account
document.addEventListener('DOMContentLoaded', function() {
    const adminAccountForm = document.getElementById("adminAccountForm");
    if (adminAccountForm) {
        adminAccountForm.addEventListener("submit", async function(e) {
            e.preventDefault();
            
            const name = document.getElementById("adminName").value.trim();
            const email = document.getElementById("adminEmail").value.trim();
            const password = document.getElementById("adminPassword").value;
            
            // Basic validation
            if (!name || !email) {
                showToast('❌ Name and email are required', 'error');
                return;
            }
            
            if (password && password.length < 6) {
                showToast('❌ Password must be at least 6 characters', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            if (password) formData.append('password', password);
            
            showToast('🔄 Updating account...', 'info');
            
            try {
                const response = await fetch("../../../../controller/Settings/updateAdminAccount.php", {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Update local settings
                    currentSettings.admin_name = name;
                    currentSettings.admin_email = email;
                    
                    // Clear password field
                    document.getElementById("adminPassword").value = "";
                    
                    showToast('✅ Account updated successfully!', 'success');
                    
                    // Update header user info
                    const userNameElement = document.querySelector('.user-name');
                    const userRoleElement = document.querySelector('.user-role');
                    const userMenuName = document.querySelector('.user-menu-name');
                    const userMenuEmail = document.querySelector('.user-menu-email');
                    
                    if (userNameElement) userNameElement.textContent = name;
                    if (userRoleElement) userRoleElement.textContent = "Administrator";
                    if (userMenuName) userMenuName.textContent = name;
                    if (userMenuEmail) userMenuEmail.textContent = email;
                    
                } else {
                    showToast('❌ Error updating account: ' + (result.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error("❌ Error updating account:", error);
                showToast('❌ Network error while updating account', 'error');
            }
        });
    }
});

// Clear dashboard cache
async function clearDashboardCache() {
    if (!confirm("Are you sure you want to clear the dashboard cache? This will reload all data from the database.")) {
        return;
    }
    
    showToast('🔄 Clearing cache and reloading data...', 'info');
    
    try {
        // Clear browser storage
        localStorage.clear();
        sessionStorage.clear();
        console.log("🧹 Browser cache cleared");
        
        // Reload all data with error handling
        const tasks = [
            { name: 'events', func: loadRealEvents },
            { name: 'sponsors', func: renderSponsors },
            { name: 'tasks', func: loadTasksFromDB },
            { name: 'settings', func: loadSettings }
        ];
        
        for (const task of tasks) {
            try {
                console.log(`🔄 Reloading ${task.name}...`);
                await task.func();
                console.log(`✅ ${task.name} reloaded successfully`);
            } catch (error) {
                console.error(`❌ Error reloading ${task.name}:`, error);
            }
        }
        
        // Force refresh participant stats if function exists
        if (typeof updateParticipantStats === 'function') {
            try {
                await updateParticipantStats();
                console.log("✅ Participant stats updated");
            } catch (error) {
                console.error("❌ Error updating participant stats:", error);
            }
        }
        
        showToast('✅ Dashboard cache cleared and data reloaded!', 'success');
        
    } catch (error) {
        console.error("❌ Error clearing cache:", error);
        showToast('❌ Error clearing cache: ' + error.message, 'error');
    }
}

// Export reports
async function exportReports() {
    showToast('🔄 Generating report...', 'info');
    
    try {
        // Get all data in parallel
        const urls = [
            "../../../../controller/getParticipants.php",
            "../../../../controller/Event/eventlist.php",
            "../../../../controller/Sponsor/getSponsors.php"
        ];
        
        const [participantsRes, eventsRes, sponsorsRes] = await Promise.all(
            urls.map(url => fetch(url).then(res => {
                if (!res.ok) throw new Error(`Failed to fetch ${url}`);
                return res.json();
            }))
        );
        
        // Create CSV content
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Participants Report
        csvContent += "=== LUMINA DASHBOARD - PARTICIPANTS REPORT ===\n\n";
        csvContent += "ID,First Name,Last Name,Email,Phone,Event,Registered Date\n";
        participantsRes.forEach(p => {
            csvContent += `"${p.id || ''}","${p.firstName || ''}","${p.lastName || ''}","${p.email || ''}","${p.phone || ''}","${p.event_title || p.event_id || ''}","${p.created_at || ''}"\n`;
        });
        
        // Events Report
        csvContent += "\n\n=== LUMINA DASHBOARD - EVENTS REPORT ===\n\n";
        csvContent += "ID,Title,Description,Date,Deadline,Location,Status,Category\n";
        eventsRes.forEach(e => {
            // Clean description for CSV
            const cleanDesc = (e.description || '').replace(/"/g, '""').replace(/\n/g, ' ');
            csvContent += `"${e.id || ''}","${e.title || ''}","${cleanDesc}","${e.date || ''}","${e.deadline || ''}","${e.location || ''}","${e.status || ''}","${e.category || ''}"\n`;
        });
        
        // Sponsors Report
        csvContent += "\n\n=== LUMINA DASHBOARD - SPONSORS REPORT ===\n\n";
        csvContent += "ID,Sponsor Name,Contact Email,Contact Phone,Type,Event ID,Contribution Notes\n";
        sponsorsRes.forEach(s => {
            const cleanNotes = (s.contribution_notes || '').replace(/"/g, '""').replace(/\n/g, ' ');
            csvContent += `"${s.id || ''}","${s.sponsor_name || ''}","${s.contact_email || ''}","${s.contact_phone || ''}","${s.sponsorship_type || ''}","${s.event_id || ''}","${cleanNotes}"\n`;
        });
        
        // Create download link
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `lumina_report_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showToast('✅ Report exported successfully!', 'success');
        
    } catch (error) {
        console.error("❌ Export error:", error);
        showToast('❌ Error exporting report: ' + error.message, 'error');
    }
}

// Backup database (placeholder function - you'll need to implement server-side)
async function backupDatabase() {
    showToast('🔄 Creating database backup...', 'info');
    
    try {
        // This is a placeholder - you need to implement server-side backup
        // For now, we'll just show a message
        setTimeout(() => {
            showToast('✅ Database backup created successfully! (Simulated)', 'success');
        }, 1500);
        
    } catch (error) {
        console.error("❌ Backup error:", error);
        showToast('❌ Error creating backup: ' + error.message, 'error');
    }
}

// Reset settings to defaults
async function resetToDefaults() {
    if (!confirm("Are you sure you want to reset all settings to defaults? This cannot be undone.")) {
        return;
    }
    
    showToast('🔄 Resetting to default settings...', 'info');
    
    const defaultSettings = {
        admin_name: "Stella Walton",
        admin_email: "stella.walton@lumina.com",
        default_landing_page: "dashboard",
        visible_widgets: ["analytics", "pending", "recent"],
        compact_mode: false,
        participant_registration: "manual",
        event_archive_days: 30,
        sponsor_validation: "admin",
        notifications: ["new_participant", "sponsor_request"],
        permissions: {
            events: true,
            participants: true,
            sponsors: true,
            tasks: true,
            analytics: true
        }
    };
    
    try {
        const response = await fetch("../../../../controller/Settings/saveSettings.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(defaultSettings)
        });
        
        const result = await response.json();
        
        if (result.success) {
            currentSettings = defaultSettings;
            applySettingsToUI();
            applySettingsToDashboard();
            showToast('✅ Settings reset to defaults!', 'success');
        } else {
            showToast('❌ Error resetting settings: ' + result.error, 'error');
        }
    } catch (error) {
        console.error("❌ Error resetting settings:", error);
        showToast('❌ Network error while resetting settings', 'error');
    }
}

// Toggle compact mode with immediate feedback
document.addEventListener('DOMContentLoaded', function() {
    const compactToggle = document.getElementById("compactModeToggle");
    const compactStatus = document.getElementById("compactModeStatus");
    
    if (compactToggle && compactStatus) {
        compactToggle.addEventListener('change', function() {
            const isChecked = this.checked;
            
            // Update UI immediately
            compactStatus.textContent = isChecked ? "Enabled" : "Disabled";
            compactStatus.style.color = isChecked ? "#10B981" : "#6B7280";
            
            if (isChecked) {
                document.body.classList.add('compact-mode');
            } else {
                document.body.classList.remove('compact-mode');
            }
            
            // Save to current settings (will be saved when user clicks "Save All")
            currentSettings.compact_mode = isChecked;
            
            console.log(`📐 Compact mode ${isChecked ? 'enabled' : 'disabled'}`);
        });
    }
});

// Auto-save settings when changed (with debounce)
let saveTimeout;
function setupAutoSave() {
    const saveableElements = document.querySelectorAll(
        '#defaultLandingPage, #participantRegistration, #eventArchiveDays, #sponsorValidation, ' +
        '.widget-checkbox, .notification-checkbox, ' +
        '#permissionEvents, #permissionParticipants, #permissionSponsors, #permissionTasks, #permissionAnalytics'
    );
    
    saveableElements.forEach(element => {
        element.addEventListener('change', function() {
            clearTimeout(saveTimeout);
            
            // Show saving indicator
            const saveBtn = document.querySelector('.settings-actions .btn-primary');
            if (saveBtn) {
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;
                
                saveTimeout = setTimeout(async () => {
                    await saveAllSettings();
                    
                    // Restore button
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Save All Settings';
                    saveBtn.disabled = false;
                }, 1500);
            }
        });
    });
}

// Initialize settings when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 Initializing Lumina Dashboard with settings...");
    
    // Load settings first
    loadSettings().then(() => {
        console.log("✅ Settings initialization complete");
        
        // Setup auto-save
        setupAutoSave();
        
        // Apply default landing page
        if (currentSettings.default_landing_page && currentSettings.default_landing_page !== 'dashboard') {
            setTimeout(() => {
                const targetTab = currentSettings.default_landing_page;
                const targetElement = document.querySelector(`[data-tab="${targetTab}"]`);
                
                if (targetElement && !targetElement.classList.contains('disabled')) {
                    console.log(`🏠 Switching to default landing page: ${targetTab}`);
                    switchTab(targetTab);
                }
            }, 500);
        }
    }).catch(error => {
        console.error("❌ Failed to initialize settings:", error);
    });
    
    // Override switchTab to check permissions
    const originalSwitchTab = window.switchTab;
    window.switchTab = function(tabName) {
        // Check if tab is enabled
        if (currentSettings.permissions && currentSettings.permissions[tabName] === false) {
            showToast(`Access to ${tabName} is disabled in settings`, 'error');
            return;
        }
        
        // Call original function
        if (originalSwitchTab) {
            originalSwitchTab(tabName);
        }
    };
    
    console.log("✨ Lumina Dashboard V23 with Settings initialized!");
});

// Make functions available globally
window.clearDashboardCache = clearDashboardCache;
window.exportReports = exportReports;
window.backupDatabase = backupDatabase;
window.resetToDefaults = resetToDefaults;
window.saveAllSettings = saveAllSettings;
window.loadSettings = loadSettings;
// ====================================
// THEME MANAGEMENT - COMPLETE
// ====================================

// Theme configuration
const themes = {
    default: {
        name: 'Default',
        description: 'Clean light theme with blue accents',
        colors: ['#3B82F6', '#10B981', '#8B5CF6'],
        preview: 'default-preview.svg'
    },
    dark: {
        name: 'Dark Mode',
        description: 'Easy on the eyes for long sessions',
        colors: ['#1F2937', '#374151', '#4B5563'],
        preview: 'dark-preview.svg'
    },
    modern: {
        name: 'Modern',
        description: 'Vibrant colors with rounded elements',
        colors: ['#7C3AED', '#EC4899', '#F59E0B'],
        preview: 'modern-preview.svg'
    }
};

// Initialize theme management
function initThemeManagement() {
    console.log("🎨 Initializing theme management...");
    
    // Load saved theme
    loadThemeFromSettings();
    
    // Setup theme selection
    setupThemeSelection();
    
    // Setup color picker
    setupAccentColorPicker();
    
    // Add theme transition styles
    addThemeTransitionStyles();
}

// Load theme from settings
function loadThemeFromSettings() {
    const savedTheme = currentSettings.theme || 'default';
    const savedAccentColor = currentSettings.accent_color || '#3B82F6';
    
    console.log(`🎨 Loading theme: ${savedTheme}, accent: ${savedAccentColor}`);
    
    // Apply theme
    applyTheme(savedTheme);
    
    // Apply accent color
    applyAccentColor(savedAccentColor);
    
    // Update UI
    updateThemeUI(savedTheme, savedAccentColor);
}

// Apply theme to the page
function applyTheme(themeName) {
    console.log(`🎨 Applying theme: ${themeName}`);
    
    // Add theme switch animation to body
    document.body.style.animation = 'themeSwitch 0.5s ease';
    
    // Remove all theme classes
    document.body.classList.remove('theme-default', 'theme-dark', 'theme-modern');
    
    // Add current theme class
    document.body.classList.add(`theme-${themeName}`);
    
    // Set data attribute for CSS targeting
    document.body.setAttribute('data-theme', themeName);
    
    // Update current settings
    currentSettings.theme = themeName;
    
    // Apply theme-specific styles
    applyThemeSpecificStyles(themeName);
    
    // Remove animation after it completes
    setTimeout(() => {
        document.body.style.animation = '';
    }, 500);
}

// Apply accent color
function applyAccentColor(color) {
    console.log(`🎨 Applying accent color: ${color}`);
    
    // Calculate darker shade for secondary
    const darkerColor = darkenColor(color, 20);
    
    // Remove existing accent color
    document.body.style.removeProperty('--accent-primary');
    document.body.style.removeProperty('--accent-secondary');
    document.body.style.removeProperty('--accent-gradient');
    
    // Set new accent color variables
    document.body.style.setProperty('--accent-primary', color);
    document.body.style.setProperty('--accent-secondary', darkerColor);
    document.body.style.setProperty('--accent-gradient', `linear-gradient(135deg, ${color}, ${darkerColor})`);
    
    // Set data attribute for CSS targeting
    document.body.setAttribute('data-accent-color', color);
    
    // Update current settings
    currentSettings.accent_color = color;
    
    // Update all buttons with accent color
    updateAccentColorElements(color, darkerColor);
}

// Update elements with accent color
function updateAccentColorElements(primaryColor, secondaryColor) {
    // Update primary buttons
    document.querySelectorAll('.btn-primary').forEach(btn => {
        btn.style.background = `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`;
        btn.style.borderColor = primaryColor;
    });
    
    // Update active navigation items
    document.querySelectorAll('.nav-item.active').forEach(navItem => {
        navItem.style.background = `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`;
    });
    
    // Update status badges
    document.querySelectorAll('.status-badge').forEach(badge => {
        badge.style.backgroundColor = primaryColor;
    });
    
    // Update progress bars
    document.querySelectorAll('.progress-bar .progress-fill').forEach(progress => {
        progress.style.background = `linear-gradient(90deg, ${primaryColor}, ${secondaryColor})`;
    });
}

// Apply theme-specific styles
function applyThemeSpecificStyles(themeName) {
    const elements = document.querySelectorAll('.event-card, .settings-card, .stat-item, .widget-card');
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary, .nav-item');
    
    if (themeName === 'modern') {
        // Modern theme: More rounded corners and shadows
        elements.forEach(card => {
            card.style.borderRadius = '16px';
            card.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.05)';
            card.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        buttons.forEach(btn => {
            btn.style.borderRadius = '12px';
        });
        
        // Update sidebar for modern theme
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.style.borderRadius = '0 20px 20px 0';
        }
        
    } else if (themeName === 'dark') {
        // Dark theme adjustments
        elements.forEach(card => {
            card.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
        
        // Update charts for dark mode
        updateChartsForTheme('dark');
        
    } else {
        // Default theme: Reset styles
        elements.forEach(card => {
            card.style.borderRadius = '';
            card.style.boxShadow = '';
        });
        
        buttons.forEach(btn => {
            btn.style.borderRadius = '';
        });
        
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.style.borderRadius = '';
        }
        
        // Reset charts for light mode
        updateChartsForTheme('light');
    }
}

// Update charts for theme (if using Chart.js)
function updateChartsForTheme(theme) {
    if (typeof Chart === 'undefined') return;
    
    const charts = Chart.instances;
    Object.values(charts).forEach(chart => {
        if (theme === 'dark') {
            // Dark theme chart colors
            chart.options.scales.x.grid.color = 'rgba(255, 255, 255, 0.1)';
            chart.options.scales.y.grid.color = 'rgba(255, 255, 255, 0.1)';
            chart.options.scales.x.ticks.color = 'rgba(255, 255, 255, 0.7)';
            chart.options.scales.y.ticks.color = 'rgba(255, 255, 255, 0.7)';
        } else {
            // Light theme chart colors
            chart.options.scales.x.grid.color = 'rgba(0, 0, 0, 0.1)';
            chart.options.scales.y.grid.color = 'rgba(0, 0, 0, 0.1)';
            chart.options.scales.x.ticks.color = 'rgba(0, 0, 0, 0.7)';
            chart.options.scales.y.ticks.color = 'rgba(0, 0, 0, 0.7)';
        }
        chart.update();
    });
}

// Update theme UI elements
function updateThemeUI(themeName, accentColor) {
    // Update theme options
    document.querySelectorAll('.theme-option').forEach((option, index) => {
        const theme = option.dataset.theme;
        const btn = option.querySelector('.theme-select-btn');
        
        if (theme === themeName) {
            option.classList.add('selected');
            btn.innerHTML = '<i class="fas fa-check"></i> Selected';
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-primary');
            
            // Add selection animation
            option.style.animation = 'themeSelected 0.5s ease';
            setTimeout(() => {
                option.style.animation = '';
            }, 500);
        } else {
            option.classList.remove('selected');
            btn.innerHTML = 'Select';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary');
        }
        
        // Add staggered animation
        option.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Update accent color picker
    document.querySelectorAll('.color-option').forEach(option => {
        const color = option.dataset.color;
        
        if (color === accentColor) {
            option.classList.add('selected');
            option.style.transform = 'scale(1.1)';
            option.style.boxShadow = `0 0 0 3px white, 0 0 0 6px ${color}40`;
        } else {
            option.classList.remove('selected');
            option.style.transform = '';
            option.style.boxShadow = '';
        }
    });
    
    // Update custom color input
    const customColorInput = document.getElementById('customAccentColor');
    const customColorValue = document.getElementById('customColorValue');
    
    if (customColorInput && customColorValue) {
        customColorInput.value = accentColor;
        customColorValue.textContent = accentColor;
        customColorValue.style.color = accentColor;
        customColorValue.style.borderLeftColor = accentColor;
    }
}

// Setup theme selection
function setupThemeSelection() {
    document.querySelectorAll('.theme-option').forEach(option => {
        // Click on card to select
        option.addEventListener('click', function(e) {
            if (e.target.closest('.theme-select-btn')) return;
            
            const theme = this.dataset.theme;
            if (theme !== currentSettings.theme) {
                selectTheme(theme);
            }
        });
        
        // Click on button to select
        const selectBtn = option.querySelector('.theme-select-btn');
        if (selectBtn) {
            selectBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const theme = option.dataset.theme;
                if (theme !== currentSettings.theme) {
                    selectTheme(theme);
                }
            });
        }
        
        // Hover effects
        option.addEventListener('mouseenter', function() {
            if (!this.classList.contains('selected')) {
                this.style.transform = 'translateY(-4px)';
            }
        });
        
        option.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
}

// Select theme with animation
async function selectTheme(themeName) {
    console.log(`🎨 Selecting theme: ${themeName}`);
    
    // Show transition overlay
    const overlay = createThemeTransitionOverlay();
    overlay.classList.add('active');
    
    // Update UI immediately for responsiveness
    updateThemeUI(themeName, currentSettings.accent_color || '#3B82F6');
    
    // Apply theme with delay for smooth transition
    await new Promise(resolve => setTimeout(resolve, 300));
    
    try {
        applyTheme(themeName);
        
        // Save to database
        const success = await saveThemeSetting(themeName);
        
        if (success) {
            console.log(`💾 Theme saved: ${themeName}`);
            
            // Show success notification with theme color
            const themeColor = themes[themeName].colors[0];
            showToast(
                `🎨 Theme changed to "${themes[themeName].name}"`,
                'success',
                themeColor
            );
        } else {
            throw new Error('Failed to save theme');
        }
        
    } catch (error) {
        console.error("❌ Error applying theme:", error);
        showToast('❌ Failed to apply theme', 'error');
        
        // Revert to previous theme
        applyTheme(currentSettings.theme || 'default');
        updateThemeUI(currentSettings.theme || 'default', currentSettings.accent_color || '#3B82F6');
        
    } finally {
        // Hide overlay
        overlay.classList.remove('active');
        setTimeout(() => overlay.remove(), 500);
    }
}

// Setup accent color picker
function setupAccentColorPicker() {
    // Predefined color options
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function() {
            const color = this.dataset.color;
            selectAccentColor(color);
        });
        
        // Hover effect
        option.addEventListener('mouseenter', function() {
            if (!this.classList.contains('selected')) {
                this.style.transform = 'scale(1.05)';
            }
        });
        
        option.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.transform = '';
            }
        });
    });
    
    // Custom color picker
    const customColorInput = document.getElementById('customAccentColor');
    const customColorValue = document.getElementById('customColorValue');
    
    if (customColorInput && customColorValue) {
        customColorInput.addEventListener('input', function() {
            const color = this.value;
            customColorValue.textContent = color;
            selectAccentColor(color);
        });
        
        // Show color picker on value click
        customColorValue.addEventListener('click', function() {
            customColorInput.click();
        });
    }
}

// Select accent color
async function selectAccentColor(color) {
    console.log(`🎨 Selecting accent color: ${color}`);
    
    // Update UI immediately
    updateThemeUI(currentSettings.theme || 'default', color);
    
    try {
        // Apply color
        applyAccentColor(color);
        
        // Save to database
        const success = await saveAccentColorSetting(color);
        
        if (success) {
            console.log(`💾 Accent color saved: ${color}`);
            
            // Show success notification
            showToast(
                `🎨 Accent color updated`,
                'success',
                color
            );
        } else {
            throw new Error('Failed to save accent color');
        }
        
    } catch (error) {
        console.error("❌ Error applying accent color:", error);
        showToast('❌ Failed to update accent color', 'error');
        
        // Revert to previous color
        const previousColor = currentSettings.accent_color || '#3B82F6';
        applyAccentColor(previousColor);
        updateThemeUI(currentSettings.theme || 'default', previousColor);
    }
}

// Save theme setting to database
async function saveThemeSetting(themeName) {
    try {
        const response = await fetch("../../../../controller/Settings/saveSettings.php", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ theme: themeName })
        });
        
        const result = await response.json();
        return result.success === true;
        
    } catch (error) {
        console.error("❌ Error saving theme:", error);
        return false;
    }
}

// Save accent color setting to database
async function saveAccentColorSetting(color) {
    try {
        const response = await fetch("../../../../controller/Settings/saveSettings.php", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accent_color: color })
        });
        
        const result = await response.json();
        return result.success === true;
        
    } catch (error) {
        console.error("❌ Error saving accent color:", error);
        return false;
    }
}

// Create theme transition overlay
function createThemeTransitionOverlay() {
    // Remove existing overlay if any
    const existingOverlay = document.querySelector('.theme-transition-overlay');
    if (existingOverlay) existingOverlay.remove();
    
    const overlay = document.createElement('div');
    overlay.className = 'theme-transition-overlay';
    
    // Get current accent color for spinner
    const accentColor = currentSettings.accent_color || '#3B82F6';
    
    overlay.innerHTML = `
        <div class="theme-transition-content">
            <div class="loader" style="border-top-color: ${accentColor};"></div>
            <p>Applying theme changes...</p>
            <small>Please wait</small>
        </div>
    `;
    
    document.body.appendChild(overlay);
    return overlay;
}

// Add theme transition styles to head
function addThemeTransitionStyles() {
    if (document.getElementById('theme-transition-styles')) return;
    
    const style = document.createElement('style');
    style.id = 'theme-transition-styles';
    style.textContent = `
        /* Theme transition overlay */
        .theme-transition-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.92);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(8px);
        }
        
        body[data-theme="dark"] .theme-transition-overlay {
            background: rgba(17, 24, 39, 0.92);
        }
        
        .theme-transition-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        
        .theme-transition-content {
            text-align: center;
            color: #374151;
            animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body[data-theme="dark"] .theme-transition-content {
            color: #F9FAFB;
        }
        
        .theme-transition-content .loader {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top-color: #3B82F6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }
        
        body[data-theme="dark"] .theme-transition-content .loader {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .theme-transition-content p {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .theme-transition-content small {
            color: #6B7280;
            font-size: 0.9rem;
        }
        
        body[data-theme="dark"] .theme-transition-content small {
            color: #D1D5DB;
        }
        
        /* Theme selection animations */
        @keyframes themeSelected {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            50% {
                transform: scale(1.02);
                box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
        }
        
        @keyframes themeSwitch {
            0% {
                opacity: 1;
                filter: brightness(1);
            }
            50% {
                opacity: 0.8;
                filter: brightness(0.9);
            }
            100% {
                opacity: 1;
                filter: brightness(1);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Theme option animations */
        .theme-option {
            animation: slideInRight 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
            transform: translateX(20px);
        }
        
        .theme-option:nth-child(1) { animation-delay: 0.1s; }
        .theme-option:nth-child(2) { animation-delay: 0.2s; }
        .theme-option:nth-child(3) { animation-delay: 0.3s; }
        
        @keyframes slideInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Color option animation */
        .color-option {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .color-option i {
            transition: opacity 0.3s ease;
        }
        
        /* Custom color value animation */
        #customColorValue {
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-left: 3px solid;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            user-select: none;
        }
        
        #customColorValue:hover {
            transform: translateX(2px);
        }
        
        /* Theme-specific animations */
        body[data-theme="modern"] .event-card,
        body[data-theme="modern"] .settings-card {
            animation: cardFloat 3s ease-in-out infinite alternate;
        }
        
        @keyframes cardFloat {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-5px);
            }
        }
    `;
    
    document.head.appendChild(style);
}

// Helper function to darken a color
function darkenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = Math.max((num >> 16) - amt, 0);
    const G = Math.max((num >> 8 & 0x00FF) - amt, 0);
    const B = Math.max((num & 0x0000FF) - amt, 0);
    
    return "#" + (
        0x1000000 +
        (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
        (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
        (B < 255 ? B < 1 ? 0 : B : 255)
    ).toString(16).slice(1).toUpperCase();
}

// Helper function to lighten a color
function lightenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = Math.min((num >> 16) + amt, 255);
    const G = Math.min((num >> 8 & 0x00FF) + amt, 255);
    const B = Math.min((num & 0x0000FF) + amt, 255);
    
    return "#" + (
        0x1000000 +
        R * 0x10000 +
        G * 0x100 +
        B
    ).toString(16).slice(1).toUpperCase();
}

// Enhanced toast notification with theme colors
function showToast(message, type = 'info', color = null) {
    // If color is provided, use it for success/info toasts
    let toastColor;
    if (color && (type === 'success' || type === 'info')) {
        toastColor = color;
    } else {
        toastColor = type === 'success' ? '#10B981' : 
                     type === 'error' ? '#EF4444' : 
                     type === 'warning' ? '#F59E0B' : 
                     '#3B82F6';
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: ${toastColor};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        animation: toastSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        max-width: 400px;
        border-left: 4px solid ${lightenColor(toastColor, 20)};
    `;
    
    const icon = type === 'success' ? 'check-circle' : 
                 type === 'error' ? 'exclamation-circle' : 
                 type === 'warning' ? 'exclamation-triangle' : 
                 'info-circle';
    
    toast.innerHTML = `
        <i class="fas fa-${icon}" style="font-size: 1.25rem;"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Remove toast after delay
    setTimeout(() => {
        toast.style.animation = 'toastSlideOut 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 400);
    }, 3000);
    
    // Add toast animations if not already present
    if (!document.getElementById('toast-animations')) {
        const style = document.createElement('style');
        style.id = 'toast-animations';
        style.textContent = `
            @keyframes toastSlideIn {
                from {
                    opacity: 0;
                    transform: translateX(100px) scale(0.9);
                }
                to {
                    opacity: 1;
                    transform: translateX(0) scale(1);
                }
            }
            
            @keyframes toastSlideOut {
                from {
                    opacity: 1;
                    transform: translateX(0) scale(1);
                }
                to {
                    opacity: 0;
                    transform: translateX(100px) scale(0.9);
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Initialize theme management when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 Initializing theme system...");
    
    // Wait for settings to load, then initialize theme management
    const initTheme = () => {
        if (typeof currentSettings !== 'undefined') {
            initThemeManagement();
            console.log("✅ Theme system initialized");
        } else {
            setTimeout(initTheme, 100);
        }
    };
    
    // Start initialization
    setTimeout(initTheme, 300);
});

// Export functions for global access
window.applyTheme = applyTheme;
window.selectTheme = selectTheme;
window.selectAccentColor = selectAccentColor;
window.toggleCompactModeWithAnimation = toggleCompactModeWithAnimation;
window.saveAllSettingsWithAnimation = saveAllSettingsWithAnimation;
// Initialize theme system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 Initializing theme system...");
    
    // Load settings first
    loadSettings().then(() => {
        // Initialize theme management
        initThemeManagement();
        console.log("✅ Theme system initialized");
        
        // Apply saved theme immediately
        if (currentSettings.theme) {
            applyTheme(currentSettings.theme);
        }
        if (currentSettings.accent_color) {
            applyAccentColor(currentSettings.accent_color);
        }
    }).catch(error => {
        console.error("❌ Failed to initialize theme system:", error);
    });
});
// Enhanced theme functions with better visual feedback
function selectTheme(themeName) {
    console.log("🎨 Selecting theme:", themeName);
    
    // Show loading overlay
    showThemeLoading();
    
    // Update UI immediately with animation
    document.querySelectorAll('.theme-option').forEach((option, index) => {
        const theme = option.dataset.theme;
        const btn = option.querySelector('.theme-select-btn');
        
        if (theme === themeName) {
            // Animate selected theme
            option.classList.add('selected');
            option.style.animation = 'themeSelected 0.5s ease';
            btn.innerHTML = '<i class="fas fa-check"></i> Selected';
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-primary');
            
            // Add glow effect
            option.style.boxShadow = '0 0 20px rgba(59, 130, 246, 0.3)';
            
            // Add sound effect (optional)
            playThemeChangeSound();
        } else {
            option.classList.remove('selected');
            option.style.animation = '';
            option.style.boxShadow = '';
            btn.innerHTML = 'Select';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary');
        }
        
        // Stagger animations
        option.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Apply theme to body with transition
    applyThemeWithTransition(themeName);
    
    // Hide loading overlay after transition
    setTimeout(() => {
        hideThemeLoading();
    }, 500);
}

function applyThemeWithTransition(themeName) {
    // Add transition class for smooth theme change
    document.body.classList.add('theme-transitioning');
    
    // Remove all theme classes
    document.body.classList.remove('theme-default', 'theme-dark', 'theme-modern');
    
    // Add current theme class
    document.body.classList.add(`theme-${themeName}`);
    
    // Set data attribute for CSS targeting
    document.body.setAttribute('data-theme', themeName);
    
    // Update current settings
    currentSettings.theme = themeName;
    
    // Save to database
    saveThemeSetting(themeName);
    
    // Update theme-specific UI elements
    updateThemeSpecificUI(themeName);
    
    // Show success notification
    const themeNames = {
        'default': 'Default Light',
        'dark': 'Dark Mode',
        'modern': 'Modern'
    };
    
    showToast(`Theme changed to ${themeNames[themeName]}`, 'success');
    
    // Remove transition class after animation
    setTimeout(() => {
        document.body.classList.remove('theme-transitioning');
    }, 300);
}

function selectAccentColor(color) {
    console.log("🎨 Selecting accent color:", color);
    
    // Update color picker UI with animation
    document.querySelectorAll('.color-option').forEach((option, index) => {
        if (option.dataset.color === color) {
            // Animate selected color
            option.classList.add('selected');
            option.style.transform = 'scale(1.1)';
            option.style.boxShadow = `0 0 0 3px white, 0 0 0 6px ${color}40`;
            option.style.animation = 'colorSelected 0.3s ease';
            
            // Make checkmark visible with animation
            const checkIcon = option.querySelector('i');
            checkIcon.style.opacity = '1';
            checkIcon.style.transform = 'scale(1.2)';
            
            // Play sound (optional)
            playColorChangeSound();
        } else {
            option.classList.remove('selected');
            option.style.transform = '';
            option.style.boxShadow = '';
            option.style.animation = '';
            
            const checkIcon = option.querySelector('i');
            checkIcon.style.opacity = '0';
            checkIcon.style.transform = '';
        }
        
        // Stagger animations
        option.style.animationDelay = `${index * 0.05}s`;
    });
    
    // Update custom color input
    const customColorInput = document.getElementById('customAccentColor');
    const customColorValue = document.getElementById('customColorValue');
    
    if (customColorInput && customColorValue) {
        customColorInput.value = color;
        customColorValue.textContent = color;
        customColorValue.style.color = color;
        customColorValue.style.borderLeftColor = color;
        customColorValue.style.animation = 'pulse 0.5s ease';
    }
    
    // Apply accent color to CSS variables
    applyAccentColorWithEffect(color);
    
    // Update current settings
    currentSettings.accent_color = color;
    
    // Save to database
    saveAccentColorSetting(color);
    
    // Show success notification
    showToast(`Accent color updated to ${color}`, 'success', color);
}

function applyAccentColorWithEffect(color) {
    // Calculate darker shade for secondary
    const darkerColor = darkenColor(color, 20);
    const lighterColor = lightenColor(color, 20);
    
    // Apply with CSS variables
    document.documentElement.style.setProperty('--accent-primary', color);
    document.documentElement.style.setProperty('--accent-secondary', darkerColor);
    document.documentElement.style.setProperty('--accent-gradient', `linear-gradient(135deg, ${color}, ${darkerColor})`);
    
    // Apply to body for data attribute
    document.body.setAttribute('data-accent-color', color);
    
    // Update UI elements with animation
    updateAccentColorElements(color, darkerColor);
}

function updateAccentColorElements(primaryColor, secondaryColor) {
    // Update primary buttons with animation
    document.querySelectorAll('.btn-primary').forEach((btn, index) => {
        btn.style.transition = 'all 0.3s ease';
        btn.style.background = `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`;
        btn.style.borderColor = primaryColor;
        btn.style.animation = 'pulse 0.5s ease';
        btn.style.animationDelay = `${index * 0.05}s`;
    });
    
    // Update active navigation items
    document.querySelectorAll('.nav-item.active').forEach((navItem, index) => {
        navItem.style.background = `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`;
        navItem.style.animation = 'glow 0.8s ease';
        navItem.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Update status badges
    document.querySelectorAll('.status-badge').forEach((badge, index) => {
        badge.style.backgroundColor = primaryColor;
        badge.style.transition = 'all 0.3s ease';
        badge.style.animation = 'fadeIn 0.3s ease';
        badge.style.animationDelay = `${index * 0.05}s`;
    });
}

// Helper functions
function showThemeLoading() {
    let overlay = document.querySelector('.theme-loading-overlay');
    
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'theme-loading-overlay';
        overlay.innerHTML = `
            <div class="theme-loading-content">
                <div class="loader"></div>
                <p>Applying theme changes...</p>
            </div>
        `;
        document.body.appendChild(overlay);
        
        // Add CSS for overlay
        const style = document.createElement('style');
        style.textContent = `
            .theme-loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                opacity: 0;
                animation: fadeIn 0.3s ease forwards;
                backdrop-filter: blur(5px);
            }
            
            .theme-loading-content {
                text-align: center;
                color: white;
            }
            
            .theme-loading-content .loader {
                width: 50px;
                height: 50px;
                border: 3px solid rgba(255, 255, 255, 0.1);
                border-top-color: var(--accent-primary, #3B82F6);
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 1rem;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            @keyframes themeSelected {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            @keyframes colorSelected {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1.1); }
            }
            
            @keyframes glow {
                0% { box-shadow: 0 0 5px rgba(var(--accent-primary-rgb, 59, 130, 246), 0.3); }
                50% { box-shadow: 0 0 20px rgba(var(--accent-primary-rgb, 59, 130, 246), 0.5); }
                100% { box-shadow: 0 0 5px rgba(var(--accent-primary-rgb, 59, 130, 246), 0.3); }
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            .theme-transitioning * {
                transition: all 0.3s ease !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    overlay.style.display = 'flex';
}

function hideThemeLoading() {
    const overlay = document.querySelector('.theme-loading-overlay');
    if (overlay) {
        overlay.style.animation = 'fadeOut 0.3s ease forwards';
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

// Optional sound effects (commented out by default)
function playThemeChangeSound() {
    // Uncomment to enable sound effects
    /*
    const audio = new Audio('path/to/theme-change-sound.mp3');
    audio.volume = 0.1;
    audio.play().catch(e => console.log("Audio play failed:", e));
    */
}

function playColorChangeSound() {
    // Uncomment to enable sound effects
    /*
    const audio = new Audio('path/to/color-change-sound.mp3');
    audio.volume = 0.05;
    audio.play().catch(e => console.log("Audio play failed:", e));
    */
}

function updateThemeSpecificUI(themeName) {
    // Update theme-specific UI elements
    if (themeName === 'dark') {
        // Add additional dark mode effects
        addDarkModeEffects();
    } else if (themeName === 'modern') {
        // Add modern theme effects
        addModernThemeEffects();
    } else {
        // Reset to default
        removeThemeEffects();
    }
}

function addDarkModeEffects() {
    // Add subtle particle effect to sidebar
    const sidebar = document.querySelector('.sidebar');
    if (sidebar && !document.querySelector('.dark-mode-particles')) {
        const particles = document.createElement('div');
        particles.className = 'dark-mode-particles';
        particles.innerHTML = Array.from({length: 20}, () => 
            `<div class="particle" style="
                position: absolute;
                width: 2px;
                height: 2px;
                background: rgba(59, 130, 246, 0.3);
                border-radius: 50%;
                animation: float 3s infinite linear;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
            "></div>`
        ).join('');
        sidebar.appendChild(particles);
        
        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { opacity: 0; transform: translateY(0); }
                50% { opacity: 0.5; transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    }
}

function addModernThemeEffects() {
    // Add floating animation to cards
    const cards = document.querySelectorAll('.event-card, .settings-card');
    cards.forEach((card, index) => {
        card.style.animation = `float ${3 + index * 0.1}s ease-in-out infinite alternate`;
    });
    
    // Add floating animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes float {
            from { transform: translateY(0); }
            to { transform: translateY(-5px); }
        }
    `;
    document.head.appendChild(style);
}

function removeThemeEffects() {
    // Remove all theme effects
    document.querySelectorAll('.dark-mode-particles').forEach(el => el.remove());
    document.querySelectorAll('[style*="animation: float"]').forEach(el => {
        el.style.animation = '';
    });
}

// Color manipulation functions
function darkenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = Math.max((num >> 16) - amt, 0);
    const G = Math.max((num >> 8 & 0x00FF) - amt, 0);
    const B = Math.max((num & 0x0000FF) - amt, 0);
    
    return "#" + (
        0x1000000 +
        (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
        (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
        (B < 255 ? B < 1 ? 0 : B : 255)
    ).toString(16).slice(1).toUpperCase();
}

function lightenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = Math.min((num >> 16) + amt, 255);
    const G = Math.min((num >> 8 & 0x00FF) + amt, 255);
    const B = Math.min((num & 0x0000FF) + amt, 255);
    
    return "#" + (
        0x1000000 +
        R * 0x10000 +
        G * 0x100 +
        B
    ).toString(16).slice(1).toUpperCase();
}
/////////////////////////////////////////////////////////
