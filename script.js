document.addEventListener('DOMContentLoaded', () => {
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

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

const dropdowns = document.querySelectorAll('.dropdown-btn');

dropdowns.forEach(btn => {
  btn.addEventListener('click', () => {
    btn.classList.toggle('active'); // Add subtle visual cue
    const container = btn.nextElementSibling;
    container.style.display = container.style.display === 'block' ? 'none' : 'block';
  });
});


