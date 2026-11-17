import Chart from 'chart.js/auto';

const elCa = document.getElementById('chart-ca');
const elCmd = document.getElementById('chart-commandes');
const elMois = document.getElementById('chart-mois');
const elTop = document.getElementById('chart-top');
const elPeu = document.getElementById('liste-peu');

if (!elCa || !elCmd || !elTop) {
    // Page stats non affichée : ne rien faire.
} else {
    const periode = document.getElementById('periode');
    let charts = [];

    async function charger() {
        charts.forEach(c => c.destroy());
        charts = [];

        const res = await fetch(`/admin/stats/donnees?jours=${periode.value}`, {
            headers: { 'Accept': 'application/json' },
        });
        if (!res.ok) return;
        const d = await res.json();

        document.getElementById('kpi-ca').textContent = d.ca_total + ' FCFA';
        document.getElementById('kpi-commandes').textContent = d.nb_commandes;
        document.getElementById('kpi-attente').textContent = d.nb_en_attente;

        charts.push(new Chart(elCa, {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [{
                    label: 'Chiffre d\'affaires (FCFA)',
                    data: d.ca,
                    borderColor: '#d97706',
                    backgroundColor: 'rgba(217,119,6,0.1)',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: { responsive: true, plugins: { legend: { display: false } } },
        }));

        charts.push(new Chart(elCmd, {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [{
                    label: 'Commandes',
                    data: d.commandes,
                    backgroundColor: '#4f46e5',
                }],
            },
            options: { responsive: true, plugins: { legend: { display: false } } },
        }));

        if (elMois) {
            charts.push(new Chart(elMois, {
                type: 'bar',
                data: {
                    labels: d.labels_mois,
                    datasets: [{
                        label: 'Chiffre d\'affaires (FCFA)',
                        data: d.ca_mois,
                        backgroundColor: '#4f46e5',
                    }],
                },
                options: { responsive: true, plugins: { legend: { display: false } } },
            }));
        }

        if (elPeu) {
            elPeu.innerHTML = d.plats_peu.map(p =>
                `<li class="py-2 flex justify-between"><span>${p.nom}</span><span class="font-semibold text-gray-500">${p.qte} vendus</span></li>`
            ).join('') || '<li class="py-2 text-gray-500">Aucune donnée.</li>';
        }

        charts.push(new Chart(elTop, {
            type: 'bar',
            data: {
                labels: d.top_plats.map(p => p.nom),
                datasets: [{
                    label: 'Quantité vendue',
                    data: d.top_plats.map(p => p.qte),
                    backgroundColor: '#059669',
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
            },
        }));
    }

    periode.addEventListener('change', charger);
    charger();
}
