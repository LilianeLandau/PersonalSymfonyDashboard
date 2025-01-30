import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    // Graphique circulaire
    const ctx = document.getElementById('statsChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Catégories', 'Produits', 'Utilisateurs'],
            datasets: [{
                data: statsData, // Ces données seront définies dans le template
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribution des données'
                }
            }
        }
    });

    // Graphique en barres
    const ctxBar = document.getElementById('statsBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Catégories', 'Produits', 'Utilisateurs'],
            datasets: [{
                label: 'Nombre d\'entrées',
                data: statsData,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Statistiques en barres'
                }
            }
        }
    });
});