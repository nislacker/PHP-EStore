<canvas id="myChart" width="800" height="600"></canvas>
<script src="/template/js/Chart.min.js"></script>
<script>
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $usersIdsStr; ?>,
            datasets: [{
                data: <?= $usersAvgTimesOnlineStr; ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
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
            responsive: false,
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Среднее время нахождения пользователя на сайте',
                fontSize: 16,
                fontColor: 'rgba(255,0,0,0.9)',
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Минуты',
                        fontSize: 16,
                        fontColor: 'rgba(255,0,0,0.9)',
                    },
                }],
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'id пользователя',
                        fontSize: 16,
                        fontColor: 'rgba(255,0,0,0.9)',
                    },
                }],

            },
            // legend: {
            //     text: 'id',
            //     display: true,
            //     labels: {
            //         fontColor: 'rgb(255, 99, 132)'
            //     }
            // }
        }
    });
</script>