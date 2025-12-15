/* ==========================================
   LUMINA DASHBOARD V23 - JAVASCRIPT
   Event Management System with Download Templates
   ========================================== */

// ====================================
// FILE DOWNLOAD FUNCTIONS
// ====================================

function downloadFile(filename) {
    const content = getFileContent(filename);
    const blob = new Blob([content], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showToast(`${filename} downloaded successfully!`, 'success');
}

function downloadAllFiles() {
    showToast('Preparing files for download...', 'info');
    
    setTimeout(() => {
        downloadFile('index.html');
    }, 300);
    
    setTimeout(() => {
        downloadFile('styles.css');
    }, 600);
    
    setTimeout(() => {
        downloadFile('script.js');
    }, 900);
    
    setTimeout(() => {
        showToast('All files downloaded successfully!', 'success');
    }, 1200);
}

function getFileContent(filename) {
    // Return the current page source for HTML
    if (filename === 'index.html') {
        return document.documentElement.outerHTML;
    }
    
    // For CSS and JS, return the content as is (would need server-side implementation for production)
    if (filename === 'styles.css') {
        return document.querySelector('link[href="styles.css"]') ? 
            '/* Download the styles.css file from the server */' : 
            '/* styles.css content */';
    }
    
    if (filename === 'script.js') {
        return document.querySelector('script[src="script.js"]') ? 
            '/* Download the script.js file from the server */' : 
            '/* script.js content */';
    }
    
    return '';
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

function showToast(message, type = 'info') {
    // Create toast element
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

// ====================================
// DATA MODELS
// ====================================

const eventsData = [
    {
        id: '1',
        title: 'Cultural Heritage Summit 2025',
        description: 'Annual summit exploring Tunisia\'s rich cultural heritage with international experts and local communities.',
        date: '2025-03-15',
        location: 'Tunis Convention Center',
        status: 'upcoming',
        deadline: '2025-03-10',
        participantCount: 156,
        sponsorCount: 8,
        assignedManager: 'Stella Walton',
        category: 'Culture'
    },
    {
        id: '2',
        title: 'Sahara Desert Expedition',
        description: 'A guided tour through the stunning landscapes of the Tunisian Sahara with professional guides.',
        date: '2025-04-20',
        location: 'Tozeur Desert Region',
        status: 'upcoming',
        deadline: '2025-04-15',
        participantCount: 45,
        sponsorCount: 5,
        assignedManager: 'Ahmed Ben Salem',
        category: 'Adventure'
    },
    {
        id: '3',
        title: 'Mediterranean Food Festival',
        description: 'Celebrate the diverse flavors of Mediterranean cuisine with renowned chefs and food enthusiasts.',
        date: '2025-02-28',
        location: 'Sousse Beachfront',
        status: 'in-progress',
        deadline: '2025-02-25',
        participantCount: 230,
        sponsorCount: 12,
        assignedManager: 'Stella Walton',
        category: 'Food & Beverage'
    },
    {
        id: '4',
        title: 'Ancient Ruins Workshop',
        description: 'Archaeological workshop at the historic ruins of Carthage for students and history enthusiasts.',
        date: '2025-05-10',
        location: 'Carthage Archaeological Site',
        status: 'upcoming',
        deadline: '2025-05-05',
        participantCount: 80,
        sponsorCount: 4,
        assignedManager: 'Leila Mansour',
        category: 'Education'
    },
    {
        id: '5',
        title: 'Medina Art Exhibition',
        description: 'Contemporary art exhibition showcasing talented local Tunisian artists and their unique perspectives.',
        date: '2025-01-20',
        location: 'Tunis Medina Cultural Center',
        status: 'completed',
        deadline: '2025-01-15',
        participantCount: 180,
        sponsorCount: 6,
        assignedManager: 'Youssef Trabelsi',
        category: 'Art'
    },
    {
        id: '6',
        title: 'Tech Innovation Conference 2025',
        description: 'Annual technology and innovation conference featuring startups and industry leaders.',
        date: '2025-06-12',
        location: 'Tunis Tech Hub',
        status: 'upcoming',
        deadline: '2025-06-05',
        participantCount: 320,
        sponsorCount: 15,
        assignedManager: 'Stella Walton',
        category: 'Technology'
    }
];

const participantsData = [
    {
        id: '1',
        name: 'Sarah Martinez',
        email: 'sarah.m@example.com',
        phone: '+216 98 123 456',
        eventId: '1',
        eventName: 'Cultural Heritage Summit 2025',
        status: 'approved',
        joinDate: '2025-02-10'
    },
    {
        id: '2',
        name: 'Ahmed Ben Ali',
        email: 'ahmed.b@example.com',
        phone: '+216 22 345 678',
        eventId: '3',
        eventName: 'Mediterranean Food Festival',
        status: 'approved',
        joinDate: '2025-02-08'
    },
    {
        id: '3',
        name: 'Marie Dubois',
        email: 'marie.d@example.com',
        phone: '+33 6 12 34 56 78',
        eventId: '1',
        eventName: 'Cultural Heritage Summit 2025',
        status: 'pending',
        joinDate: '2025-02-12'
    },
    {
        id: '4',
        name: 'John Smith',
        email: 'john.s@example.com',
        phone: '+1 555 123 4567',
        eventId: '6',
        eventName: 'Tech Innovation Conference 2025',
        status: 'approved',
        joinDate: '2025-02-05'
    },
    {
        id: '5',
        name: 'Fatima Zahra',
        email: 'fatima.z@example.com',
        phone: '+212 6 12 34 56 78',
        eventId: '2',
        eventName: 'Sahara Desert Expedition',
        status: 'pending',
        joinDate: '2025-02-13'
    },
    {
        id: '6',
        name: 'Marco Rossi',
        email: 'marco.r@example.com',
        phone: '+39 333 123 4567',
        eventId: '3',
        eventName: 'Mediterranean Food Festival',
        status: 'approved',
        joinDate: '2025-02-11'
    },
    {
        id: '7',
        name: 'Amina Khelifi',
        email: 'amina.k@example.com',
        phone: '+216 55 789 012',
        eventId: '4',
        eventName: 'Ancient Ruins Workshop',
        status: 'pending',
        joinDate: '2025-02-14'
    },
    {
        id: '8',
        name: 'David Chen',
        email: 'david.c@example.com',
        phone: '+86 138 0000 0000',
        eventId: '6',
        eventName: 'Tech Innovation Conference 2025',
        status: 'approved',
        joinDate: '2025-02-09'
    }
];

const sponsorsData = [
    {
        id: '1',
        name: 'Tunisie Telecom',
        type: 'financial',
        contactEmail: 'partnership@tunisietelecom.tn',
        contactPhone: '+216 71 000 000',
        eventId: '6',
        eventName: 'Tech Innovation Conference 2025',
        contributionNotes: 'Gold sponsor - 50,000 TND contribution',
        contractStatus: 'Signed'
    },
    {
        id: '2',
        name: 'Mosaique FM',
        type: 'media',
        contactEmail: 'contact@mosaiquefm.net',
        contactPhone: '+216 71 111 111',
        eventId: '3',
        eventName: 'Mediterranean Food Festival',
        contributionNotes: 'Media coverage and promotional broadcasts',
        contractStatus: 'Signed'
    },
    {
        id: '3',
        name: 'Tunisia Airlines',
        type: 'financial',
        contactEmail: 'corporate@tunisair.com.tn',
        contactPhone: '+216 70 123 456',
        eventId: '1',
        eventName: 'Cultural Heritage Summit 2025',
        contributionNotes: 'Travel sponsor - discounted flights for international guests',
        contractStatus: 'Pending'
    },
    {
        id: '4',
        name: 'TechGear Solutions',
        type: 'equipment',
        contactEmail: 'sales@techgear.tn',
        contactPhone: '+216 98 222 333',
        eventId: '6',
        eventName: 'Tech Innovation Conference 2025',
        contributionNotes: 'Providing AV equipment and tech displays',
        contractStatus: 'Signed'
    },
    {
        id: '5',
        name: 'Express FM',
        type: 'media',
        contactEmail: 'redaction@expressfm.net',
        contactPhone: '+216 71 222 222',
        eventId: '1',
        eventName: 'Cultural Heritage Summit 2025',
        contributionNotes: 'Radio coverage and interviews',
        contractStatus: 'Signed'
    },
    {
        id: '6',
        name: 'Sahara Tours Co.',
        type: 'financial',
        contactEmail: 'info@saharatours.tn',
        contactPhone: '+216 76 333 444',
        eventId: '2',
        eventName: 'Sahara Desert Expedition',
        contributionNotes: 'Transportation and logistics support',
        contractStatus: 'Signed'
    },
    {
        id: '7',
        name: 'Olivia Olive Oil',
        type: 'other',
        contactEmail: 'marketing@olivia.tn',
        contactPhone: '+216 73 444 555',
        eventId: '3',
        eventName: 'Mediterranean Food Festival',
        contributionNotes: 'Product samples and tasting booth',
        contractStatus: 'Signed'
    },
    {
        id: '8',
        name: 'Heritage Foundation Tunisia',
        type: 'financial',
        contactEmail: 'grants@heritage.tn',
        contactPhone: '+216 71 555 666',
        eventId: '4',
        eventName: 'Ancient Ruins Workshop',
        contributionNotes: 'Educational grant - 20,000 TND',
        contractStatus: 'Signed'
    }
];

let tasksData = [
    {
        id: 1,
        title: 'Search inspirations for upcoming event',
        description: 'Research venues and themes for the annual conference',
        status: 'todo',
        priority: 'high',
        category: 'Planning',
        assignees: ['JS', 'AD', 'MK'],
        progress: 82,
        comments: 12,
        attachments: 8
    },
    {
        id: 2,
        title: 'Order mobile app design',
        description: 'Design onboarding screens for the event mobile app',
        status: 'todo',
        priority: 'medium',
        category: 'Design',
        assignees: ['AD'],
        progress: 0,
        comments: 0,
        attachments: 0
    },
    {
        id: 3,
        title: 'Make user flow of event booking app',
        description: 'Create wireframes and user journey maps',
        status: 'todo',
        priority: 'high',
        category: 'Design',
        assignees: ['MK', 'JS'],
        progress: 25,
        comments: 12,
        attachments: 0
    },
    {
        id: 4,
        title: 'Webby product task and the tasks in website',
        description: 'Update product listings and task management features',
        status: 'in-progress',
        priority: 'medium',
        category: 'Development',
        assignees: ['JS', 'AD', 'MK'],
        progress: 45,
        comments: 8,
        attachments: 3
    },
    {
        id: 5,
        title: 'Design CRM shop project page responsive website',
        description: 'Make the CRM dashboard mobile responsive',
        status: 'in-progress',
        priority: 'high',
        category: 'Design',
        assignees: ['AD', 'MK'],
        progress: 65,
        comments: 12,
        attachments: 6
    },
    {
        id: 6,
        title: 'Crypto product landing page create in webflow',
        description: 'Build a landing page for crypto products using Webflow',
        status: 'in-review',
        priority: 'medium',
        category: 'Marketing',
        assignees: ['JS', 'AD'],
        progress: 90,
        comments: 12,
        attachments: 0
    },
    {
        id: 7,
        title: 'Network video platform web app design and develop',
        description: 'Complete design and development of video streaming platform',
        status: 'in-review',
        priority: 'high',
        category: 'Development',
        assignees: ['MK', 'JS'],
        progress: 85,
        comments: 12,
        attachments: 0
    },
    {
        id: 8,
        title: 'Affiliate product full service',
        description: 'Complete affiliate program integration',
        status: 'done',
        priority: 'high',
        category: 'Marketing',
        assignees: ['MK', 'JS', 'AD'],
        progress: 100,
        comments: 7,
        attachments: 2
    }
];

// ====================================
// TAB NAVIGATION
// ====================================

function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected tab
    const selectedTab = document.getElementById(`${tabName}-content`);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Add active class to selected nav item
    const selectedNav = document.querySelector(`[data-tab="${tabName}"]`);
    if (selectedNav) {
        selectedNav.classList.add('active');
    }
    
    // Render content for specific tabs
    if (tabName === 'events') {
        renderEvents();
    } else if (tabName === 'participants') {
        renderParticipants();
    } else if (tabName === 'sponsors') {
        renderSponsors();
    } else if (tabName === 'tasks') {
        renderTasks();
    } else if (tabName === 'analytics') {
        renderAnalytics();
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

// Close modal when clicking outside
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
    
    // Close user menu if open
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
    
    // Close notification panel if open
    const notifPanel = document.getElementById('notificationPanel');
    if (notifPanel && notifPanel.classList.contains('active')) {
        notifPanel.classList.remove('active');
    }
}

// Close panels when clicking outside
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

function renderUpcomingEvents() {
    const container = document.getElementById('upcomingEventsList');
    if (!container) return;
    
    const upcomingEvents = eventsData.filter(e => e.status === 'upcoming').slice(0, 3);
    
    container.innerHTML = upcomingEvents.map(event => `
        <div class="event-card">
            <div class="event-header">
                <div>
                    <h3 class="event-title">${event.title}</h3>
                    <p class="event-description">${event.description}</p>
                </div>
                <span class="badge status-${event.status}">${event.status}</span>
            </div>
            <div class="event-meta">
                <div class="event-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>${new Date(event.date).toLocaleDateString()}</span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${event.location}</span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-users"></i>
                    <span>${event.participantCount} participants</span>
                </div>
            </div>
            <div class="event-actions">
                <button class="btn-secondary" onclick="editEvent('${event.id}')"><i class="fas fa-edit"></i> Edit</button>
                <button class="btn-secondary" onclick="viewEvent('${event.id}')"><i class="fas fa-eye"></i> View</button>
            </div>
        </div>
    `).join('');
}

function renderEvents() {
    const container = document.getElementById('eventsGrid');
    if (!container) return;
    
    const filter = document.getElementById('eventFilter')?.value || 'all';
    const filtered = filter === 'all' ? eventsData : eventsData.filter(e => e.status === filter);
    
    container.innerHTML = filtered.map(event => `
        <div class="event-card">
            <div class="event-header">
                <div>
                    <h3 class="event-title">${event.title}</h3>
                    <p class="event-description">${event.description}</p>
                </div>
                <span class="badge status-${event.status}">${event.status}</span>
            </div>
            <div class="event-meta">
                <div class="event-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>${new Date(event.date).toLocaleDateString()}</span>
                    <span style="color: #F59E0B; font-size: 0.8rem; margin-left: 8px;">
                        Deadline: ${new Date(event.deadline).toLocaleDateString()}
                    </span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${event.location}</span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-users"></i>
                    <span>${event.participantCount} participants</span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-handshake"></i>
                    <span>${event.sponsorCount} sponsors</span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-tag"></i>
                    <span>${event.category}</span>
                </div>
            </div>
            <div class="event-actions">
                <button class="btn-secondary" onclick="editEvent('${event.id}')"><i class="fas fa-edit"></i> Edit</button>
                <button class="btn-secondary" onclick="viewEvent('${event.id}')"><i class="fas fa-eye"></i> Details</button>
                <button class="btn-secondary" onclick="contactEvent('${event.id}')"><i class="fas fa-envelope"></i> Contact</button>
            </div>
        </div>
    `).join('');
}

function filterEvents() {
    renderEvents();
}

function editEvent(id) {
    showToast('Edit event feature coming soon!', 'info');
}

function viewEvent(id) {
    showToast('View event details feature coming soon!', 'info');
}

function contactEvent(id) {
    showToast('Contact event participants feature coming soon!', 'info');
}

function renderRecentParticipants() {
    const container = document.getElementById('recentParticipantsList');
    if (!container) return;
    
    const recent = participantsData.slice(0, 5);
    
    container.innerHTML = recent.map(participant => {
        const initials = participant.name.split(' ').map(n => n[0]).join('');
        return `
            <div class="participant-item">
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

function renderParticipants() {
    const container = document.getElementById('participantsContainer');
    if (!container) return;
    
    const pending = participantsData.filter(p => p.status === 'pending');
    
    let html = '';
    
    // Pending approval section
    if (pending.length > 0) {
        html += `
            <div class="widget-card" style="background: rgba(245, 158, 11, 0.1); border-color: #F59E0B;">
                <h3 style="color: #243B53; margin-bottom: 1rem;">Pending Approval (${pending.length})</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    ${pending.map(p => `
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: white; border-radius: 0.5rem;">
                            <div>
                                <p style="color: #243B53; font-weight: 600;">${p.name}</p>
                                <p style="color: #4E5F7C; font-size: 0.9rem;">${p.eventName}</p>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn-primary" style="background: #10B981;" onclick="approveParticipant('${p.id}')">
                                    <i class="fas fa-check-circle"></i> Approve
                                </button>
                                <button class="btn-secondary" style="color: #EF4444; border-color: #EF4444;" onclick="rejectParticipant('${p.id}')">
                                    <i class="fas fa-times-circle"></i> Reject
                                </button>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    // All participants
    html += `
        <div class="widget-card" style="margin-top: 1.5rem;">
            <h3 style="color: #243B53; margin-bottom: 1rem;">All Participants</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                ${participantsData.map(p => {
                    const initials = p.name.split(' ').map(n => n[0]).join('');
                    return `
                        <div class="participant-item" style="padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #243B53, #4E5F7C); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                    ${initials}
                                </div>
                                <div>
                                    <p style="color: #243B53; font-weight: 600;">${p.name}</p>
                                    <p style="color: #4E5F7C; font-size: 0.9rem;">${p.email}</p>
                                    <p style="color: #6B85A8; font-size: 0.8rem;">${p.eventName}</p>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <span class="badge participant-${p.status}">${p.status}</span>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn-link" onclick="callParticipant('${p.id}')"><i class="fas fa-phone"></i></button>
                                    <button class="btn-link" onclick="emailParticipant('${p.id}')"><i class="fas fa-envelope"></i></button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('')}
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

function searchParticipants() {
    const search = document.getElementById('participantSearch').value.toLowerCase();
    if (!search) {
        renderParticipants();
        return;
    }
    
    showToast(`Searching for: ${search}`, 'info');
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
        renderParticipants();
        renderRecentParticipants();
        updateStatistics();
        showToast(`${participant.name} has been approved!`, 'success');
    }
}

function rejectParticipant(id) {
    const participant = participantsData.find(p => p.id === id);
    if (participant) {
        participant.status = 'rejected';
        renderParticipants();
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

function renderSponsors() {
    const container = document.getElementById('sponsorsGrid');
    if (!container) return;
    
    const filter = document.getElementById('sponsorTypeFilter')?.value || 'all';
    const filtered = filter === 'all' ? sponsorsData : sponsorsData.filter(s => s.type === filter);
    
    container.innerHTML = filtered.map(sponsor => `
        <div class="event-card">
            <div class="event-header">
                <div>
                    <h3 class="event-title">${sponsor.name}</h3>
                    <p class="event-description">${sponsor.eventName}</p>
                </div>
                <span class="badge sponsor-${sponsor.type}">${sponsor.type}</span>
            </div>
            <div class="event-meta">
                <div class="event-meta-item">
                    <i class="fas fa-envelope"></i>
                    <span>${sponsor.contactEmail}</span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-phone"></i>
                    <span>${sponsor.contactPhone}</span>
                </div>
                <div class="event-meta-item">
                    <i class="fas fa-file-contract"></i>
                    <span>Status: ${sponsor.contractStatus}</span>
                </div>
            </div>
            <p style="color: #4E5F7C; font-size: 0.85rem; margin: 1rem 0; padding-top: 0.75rem; border-top: 1px solid #E5E7EB;">
                ${sponsor.contributionNotes}
            </p>
            <div class="event-actions">
                <button class="btn-secondary" onclick="editSponsor('${sponsor.id}')"><i class="fas fa-edit"></i> Edit</button>
                <button class="btn-secondary" onclick="viewSponsor('${sponsor.id}')"><i class="fas fa-eye"></i> Details</button>
                <button class="btn-secondary" onclick="contactSponsor('${sponsor.id}')"><i class="fas fa-envelope"></i> Contact</button>
            </div>
        </div>
    `).join('');
}

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

function renderTasks() {
    const statusGroups = {
        'todo': tasksData.filter(t => t.status === 'todo'),
        'in-progress': tasksData.filter(t => t.status === 'in-progress'),
        'in-review': tasksData.filter(t => t.status === 'in-review'),
        'done': tasksData.filter(t => t.status === 'done')
    };
    
    // Update counts
    document.getElementById('todoCount').textContent = statusGroups['todo'].length;
    document.getElementById('inProgressCount').textContent = statusGroups['in-progress'].length;
    document.getElementById('inReviewCount').textContent = statusGroups['in-review'].length;
    document.getElementById('doneCount').textContent = statusGroups['done'].length;
    
    document.getElementById('todoColumnCount').textContent = statusGroups['todo'].length;
    document.getElementById('inProgressColumnCount').textContent = statusGroups['in-progress'].length;
    document.getElementById('inReviewColumnCount').textContent = statusGroups['in-review'].length;
    document.getElementById('doneColumnCount').textContent = statusGroups['done'].length;
    
    // Render tasks in each column
    Object.keys(statusGroups).forEach(status => {
        const container = document.getElementById(`${status}Tasks`);
        if (!container) return;
        
        container.innerHTML = statusGroups[status].map(task => renderTaskCard(task)).join('');
    });
}

function renderTaskCard(task) {
    const priorityClass = `priority-${task.priority}`;
    const categoryColors = {
        'Planning': 'background: rgba(168, 85, 247, 0.1); color: #A855F7;',
        'Design': 'background: rgba(59, 130, 246, 0.1); color: #3B82F6;',
        'Development': 'background: rgba(16, 185, 129, 0.1); color: #10B981;',
        'Marketing': 'background: rgba(236, 72, 153, 0.1); color: #EC4899;'
    };
    
    const totalDots = 10;
    const filledDots = Math.round((task.progress / 100) * totalDots);
    
    const progressDots = Array.from({ length: totalDots }, (_, i) => 
        `<div class="progress-dot ${i < filledDots ? 'filled' : ''}"></div>`
    ).join('');
    
    const assignees = task.assignees.map(a => 
        `<div class="task-assignee">${a}</div>`
    ).join('');
    
    return `
        <div class="task-card ${priorityClass}" onclick="viewTask(${task.id})">
            <div class="task-card-header">
                <div class="task-badges">
                    <span class="badge" style="${categoryColors[task.category] || ''}">${task.category}</span>
                    <span class="badge" style="font-size: 0.7rem;">${task.priority}</span>
                </div>
                <button class="btn-link" onclick="event.stopPropagation(); editTask(${task.id})">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            <h4 class="task-title">${task.title}</h4>
            <p class="task-description">${task.description}</p>
            <div class="task-progress">
                <div class="progress-dots">${progressDots}</div>
                <span class="progress-text">${task.progress}%</span>
            </div>
            <div class="task-footer">
                <div class="task-assignees">${assignees}</div>
                <div class="task-stats">
                    ${task.comments > 0 ? `
                        <div class="task-stat">
                            <i class="fas fa-comment"></i>
                            <span>${task.comments}</span>
                        </div>
                    ` : ''}
                    ${task.attachments > 0 ? `
                        <div class="task-stat">
                            <i class="fas fa-paperclip"></i>
                            <span>${task.attachments}</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

function searchTasks() {
    const search = document.getElementById('taskSearch').value.toLowerCase();
    if (!search) {
        renderTasks();
        return;
    }
    
    showToast(`Searching for: ${search}`, 'info');
}

function filterTasks() {
    showToast('Filter tasks feature coming soon!', 'info');
}

function viewTask(id) {
    const task = tasksData.find(t => t.id === id);
    if (task) {
        showToast(`Viewing task: ${task.title}`, 'info');
    }
}

function editTask(id) {
    const task = tasksData.find(t => t.id === id);
    if (task) {
        showToast(`Edit task: ${task.title}`, 'info');
    }
}

function deleteTask(id) {
    if (confirm('Are you sure you want to delete this task?')) {
        tasksData = tasksData.filter(t => t.id !== id);
        renderTasks();
        showToast('Task deleted successfully!', 'success');
    }
}

function renderAnalytics() {
    const container = document.getElementById('analyticsContent');
    if (!container) return;
    
    const totalParticipants = participantsData.length;
    const approvedParticipants = participantsData.filter(p => p.status === 'approved').length;
    const pendingParticipants = participantsData.filter(p => p.status === 'pending').length;
    const totalSponsorsSum = sponsorsData.length;
    
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
        html += '<div class="calendar-day"></div>';
    }
    
    // Days of month
    const eventDays = [15, 20, 28]; // Sample event days based on our data
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = day === today.getDate();
        const hasEvent = eventDays.includes(day);
        const classes = `calendar-day ${isToday ? 'today' : ''} ${hasEvent ? 'has-event' : ''}`;
        html += `<div class="${classes}">${day}</div>`;
    }
    
    html += '</div>';
    container.innerHTML = html;
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
            eventsData.push(newEvent);
            closeModal('createEventModal');
            this.reset();
            renderEvents();
            renderUpcomingEvents();
            updateStatistics();
            showToast('Event created successfully!', 'success');
        });
    }
    
    // Create Task Form
    const createTaskForm = document.getElementById('createTaskForm');
    if (createTaskForm) {
        createTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const assignees = formData.get('assignees') 
                ? formData.get('assignees').split(',').map(a => a.trim()) 
                : [];
            
            const newTask = {
                id: tasksData.length + 1,
                title: formData.get('title'),
                description: formData.get('description'),
                status: formData.get('status'),
                priority: formData.get('priority'),
                category: formData.get('category'),
                assignees: assignees,
                progress: parseInt(formData.get('progress')),
                comments: 0,
                attachments: 0
            };
            tasksData.push(newTask);
            closeModal('createTaskModal');
            this.reset();
            renderTasks();
            showToast('Task created successfully!', 'success');
        });
    }
    
    // Add Sponsor Form
    const addSponsorForm = document.getElementById('addSponsorForm');
    if (addSponsorForm) {
        addSponsorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const newSponsor = {
                id: String(sponsorsData.length + 1),
                name: formData.get('name'),
                type: formData.get('type'),
                contactEmail: formData.get('email'),
                contactPhone: formData.get('phone'),
                eventName: formData.get('event'),
                contributionNotes: formData.get('notes') || 'No notes provided',
                contractStatus: 'Pending'
            };
            sponsorsData.push(newSponsor);
            closeModal('addSponsorModal');
            this.reset();
            renderSponsors();
            updateStatistics();
            showToast('Sponsor added successfully!', 'success');
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
    
    // Initial render
    renderUpcomingEvents();
    renderRecentParticipants();
    renderCalendar();
    renderEvents();
    renderParticipants();
    renderSponsors();
    renderTasks();
    renderAnalytics();
    updateStatistics();
    
    // Show dashboard tab by default
    switchTab('dashboard');
    
    console.log('✨ Lumina Dashboard V23 initialized successfully!');
    console.log('📊 Total Events:', eventsData.length);
    console.log('👥 Total Participants:', participantsData.length);
    console.log('🤝 Total Sponsors:', sponsorsData.length);
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
