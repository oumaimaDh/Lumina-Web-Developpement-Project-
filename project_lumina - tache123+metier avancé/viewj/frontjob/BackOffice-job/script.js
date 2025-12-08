// Sidebar functionality
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

// Enhanced dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-btn');
    
    dropdowns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('active');
            const container = this.nextElementSibling;
            container.classList.toggle('show');
        });
    });

    // Search functionality for Manage.html
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Modal functionality
    const editModal = document.getElementById('editModal');
    if (editModal) {
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === editModal) {
                closeEditModal();
            }
        });
    }

    // Chart initialization (keep your existing chart code)
    const canvas1 = document.getElementById('myChart');
    const canvas2 = document.getElementById('userChart');
    
    // Left bar chart
    if (canvas1) {
        const ctx1 = canvas1.getContext('2d');
        const myChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 19000, 20000, 30000, 32000, 34000],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Right doughnut chart
    if (canvas2) {
        const ctx2 = canvas2.getContext('2d');
        const userChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Help Seekers', 'Associations', 'Volunteers'],
                datasets: [{
                    data: [50, 30, 20],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Segoe UI',
                                size: 13
                            }
                        }
                    }
                }
            }
        });
    }
});
// Sidebar toggle function
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
    document.body.classList.toggle('sidebar-open');
}

// Redirect to Edit-User page
function editUser(userId) {
    // In a real application, you might pass the user ID as a parameter
    window.location.href = 'Edit-User.html?id=' + userId;
}

// Delete user function
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        // In a real application, you would make an API call here
        alert('User ' + userId + ' deleted successfully!');
        // Refresh the page or remove the row from the table
        location.reload();
    }
}

// Toggle block user function
function toggleBlockUser(userId) {
    // In a real application, you would make an API call here
    alert('User ' + userId + ' block status toggled!');
    // Refresh the page or update the status in the table
    location.reload();
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent.toLowerCase();
            if (cellText.includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
});
