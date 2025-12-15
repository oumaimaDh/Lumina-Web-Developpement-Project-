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
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            datasets: [{
                label: 'New Users',
                data: [85, 112, 98, 145, 167, 189, 210, 245],
                borderColor: '#667eea', // Vibrant blue
                backgroundColor: 'rgba(102, 126, 234, 0.15)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 10
            }, {
                label: 'Active Users',
                data: [65, 89, 120, 156, 178, 210, 235, 268],
                borderColor: '#764ba2', // Rich violet
                backgroundColor: 'rgba(118, 75, 162, 0.15)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#764ba2',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 10
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
                            size: 11,
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        color: '#243B53'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(102, 126, 234, 0.95)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    titleFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                        weight: 'bold'
                    },
                    bodyFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                    },
                    borderColor: '#764ba2',
                    borderWidth: 2
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(102, 126, 234, 0.1)'
                    },
                    ticks: {
                        color: '#4E5F7C',
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(102, 126, 234, 0.1)'
                    },
                    ticks: {
                        color: '#4E5F7C',
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
}

function initDistributionChart() {
    const ctx = document.getElementById('distributionChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Help Seekers', 'Volunteers', 'Associations', 'Admins'],
            datasets: [{
                data: [40, 35, 20, 5],
                backgroundColor: [
                    '#667eea', // Vibrant blue
                    '#764ba2', // Rich violet
                    '#f093fb', // Bright pink-purple
                    '#4facfe'  // Electric blue
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4,
                hoverOffset: 15,
                hoverBackgroundColor: [
                    '#5a6fd8', // Darker blue on hover
                    '#6a4190', // Darker violet on hover
                    '#e081f0', // Darker pink on hover
                    '#3d9bf7'  // Darker electric blue on hover
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 15,
                        font: {
                            size: 11,
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        color: '#243B53',
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(102, 126, 234, 0.95)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    titleFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                        weight: 'bold'
                    },
                    bodyFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                    },
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}%`;
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1500
            }
        }
    });
}

function initActivityChart() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Posts', 'Events', 'Donations', 'Volunteer', 'Messages'],
            datasets: [{
                label: 'Monthly Activity',
                data: [1250, 890, 456, 1234, 678],
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',   // Vibrant blue
                    'rgba(118, 75, 162, 0.8)',    // Rich violet
                    'rgba(240, 147, 251, 0.8)',   // Bright pink-purple
                    'rgba(79, 172, 254, 0.8)',    // Electric blue
                    'rgba(160, 184, 216, 0.8)'    // Soft blue
                ],
                borderColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#4facfe',
                    '#a0b8d8'
                ],
                borderWidth: 2,
                borderRadius: 12,
                borderSkipped: false,
                hoverBackgroundColor: [
                    '#5a6fd8',
                    '#6a4190',
                    '#e081f0',
                    '#3d9bf7',
                    '#90a8d0'
                ],
                hoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(102, 126, 234, 0.95)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    titleFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                        weight: 'bold'
                    },
                    bodyFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(102, 126, 234, 0.15)'
                    },
                    ticks: {
                        color: '#4E5F7C',
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#4E5F7C',
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                            size: 11
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutBounce'
            }
        }
    });
}

function initRegionalChart() {
    const ctx = document.getElementById('regionalChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['North Region', 'South Region', 'East Region', 'West Region', 'Central'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    'rgba(102, 126, 234, 0.9)',   // Vibrant blue
                    'rgba(118, 75, 162, 0.9)',    // Rich violet
                    'rgba(240, 147, 251, 0.9)',   // Bright pink-purple
                    'rgba(79, 172, 254, 0.9)',    // Electric blue
                    'rgba(160, 184, 216, 0.9)'    // Soft blue
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4,
                hoverOffset: 20,
                hoverBackgroundColor: [
                    'rgba(90, 111, 216, 1)',
                    'rgba(106, 65, 144, 1)',
                    'rgba(224, 129, 240, 1)',
                    'rgba(61, 155, 247, 1)',
                    'rgba(144, 168, 208, 1)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 15,
                        font: {
                            size: 11,
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        },
                        color: '#243B53',
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(102, 126, 234, 0.95)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    titleFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                        weight: 'bold'
                    },
                    bodyFont: {
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                    },
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}%`;
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1500,
                easing: 'easeOutBounce'
            }
        }
    });
}

// Add some interactive features
function addChartInteractivity() {
    // Add click events to charts for more interactivity
    const charts = document.querySelectorAll('canvas');
    charts.forEach(chart => {
        chart.style.cursor = 'pointer';
        chart.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

// Initialize interactivity after charts are loaded
setTimeout(addChartInteractivity, 1000);

// Export functions for potential use in other modules
window.chartUtils = {
    updateChartData: function(chart, newData) {
        chart.data.datasets[0].data = newData;
        chart.update();
    },
    initGrowthChart,
    initDistributionChart,
    initActivityChart,
    initRegionalChart,
    addChartInteractivity
};