// charts-enhanced.js
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all charts
    initGrowthChart();
    initDistributionChart();
    initActivityChart();
    initRegionalChart();
});

function initGrowthChart() {
    const ctx = document.getElementById('growthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Users',
                data: [85, 112, 98, 145, 167, 189],
                borderColor: '#5f4b8b',
                backgroundColor: 'rgba(95, 75, 139, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function initDistributionChart() {
    const ctx = document.getElementById('distributionChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Help Seekers', 'Volunteers', 'Associations'],
            datasets: [{
                data: [45, 30, 25],
                backgroundColor: [
                    '#667eea',
                    '#5f4b8b',
                    '#f093fb'
                ],
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        font: {
                            size: 10
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

function initActivityChart() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Posts', 'Events', 'Donations', 'Volunteer'],
            datasets: [{
                data: [1250, 890, 456, 1234],
                backgroundColor: '#5f4b8b',
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function initRegionalChart() {
    const ctx = document.getElementById('regionalChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['North', 'South', 'East', 'West'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: [
                    '#667eea',
                    '#5f4b8b',
                    '#f093fb',
                    '#4facfe'
                ],
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
}