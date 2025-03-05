document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('visitChart').getContext('2d');

    console.log('Visit Dates: ', visitDates);
    console.log('Visit Durations: ', visitDurations);

    var visitChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: visitDates,
            datasets: [{
                label: 'Durasi Kunjungan (detik)',
                data: visitDurations,
                borderColor: 'hsl(308, 89.90%, 72.90%)',
                backgroundColor: 'rgb(255, 65, 176)',
                fill: false,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Durasi (detik)'
                    }
                }
            }
        }
    });
});
