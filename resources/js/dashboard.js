import Chart from 'chart.js/auto';

const el = document.getElementById('chart-ventes');

if (el && window.DASH_VENTES) {
    new Chart(el, {
        type: 'bar',
        data: {
            labels: window.DASH_VENTES.labels,
            datasets: [{
                label: 'Chiffre d\'affaires (FCFA)',
                data: window.DASH_VENTES.data,
                backgroundColor: '#d97706',
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } },
        },
    });
}
