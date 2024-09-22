<div class="main">
    <div class="card-isi">
        <div class="container-boxes">
            <div class="boxes">
                <div class="no-boxes">
                    <p>12.000.000</p>
                </div>
                <p>Stok Tersisa</p>
            </div>
            <div class="boxes">
                <div class="no-boxes">
                    <p>12.000.000</p>
                </div>
                <p>Total Tersisa</p>
            </div>
        </div>
    </div>
    <div class="card-isi">
        <div class="header-card">
            <p>Grafik Dashboard</p>
            <hr>
        </div>
        <div class="content-card">
            <div class="grafik">
            <canvas id="myChart" width="400" height="400"></canvas>
            </div>

            <div class="grafik">
                <img src="image/chart.png" alt="">
            </div>
        </div>
    </div>
    <div class="footer">
        <footer>
            <p>Copyright @2024 . Robby S</p>
        </footer>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($data['labels']),
                datasets: [{
                    label: 'Data Penjualan',
                    data: @json($data['values']),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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

        Livewire.on('refreshChart', () => {
            myChart.data.labels = @json($data['labels']);
            myChart.data.datasets[0].data = @json($data['values']);
            myChart.update();
        });
    });
</script>