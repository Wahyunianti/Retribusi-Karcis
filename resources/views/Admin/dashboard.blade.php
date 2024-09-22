@extends('Layout.sidebar')

@section('content')
<div class="main">
    <div class="card-isi">
        <div class="container-boxes">
            <a href="{{route('dataKarcisStok')}}">

                <div class="boxes">
                    <img class="godong" src="{{asset('image/godong.gif')}}" alt="">
                    <div class="no-boxes">
                        <i class="fa-solid fa-box"></i>
                        <p>{{$totalStok}}</p>
                    </div>
                    <p>Stok Karcis Masuk</p>
                </div>
            </a>
            <a href="{{route('dataKarcisStok')}}">
                <div class="boxes">
                <img class="godong" src="{{asset('image/godong.gif')}}" alt="">
                    <div class="no-boxes">
                        <i class="fa-solid fa-box-open"></i>
                        <p>{{$totalStok2}}</p>
                    </div>
                    <p>Stok Karcis Keluar</p>
                </div>
            </a>
            <a href="{{route('dataKarcisIn')}}">
                <div class="boxes">
                <img class="godong" src="{{asset('image/godong.gif')}}" alt="">
                    <div class="no-boxes">
                        <i class="fa-solid fa-credit-card"></i>
                        <p>{{ 'Rp ' . number_format($totalHarga, 0, ',', '.') }}</p>
                    </div>
                    <p>Total Karcis Masuk</p>
                </div>
            </a>
            <a href="{{route('dataKarcisOut')}}">
                <div class="boxes">
                <img class="godong" src="{{asset('image/godong.gif')}}" alt="">
                    <div class="no-boxes">
                        <i class="fa-regular fa-credit-card"></i>
                        <p>{{ 'Rp ' . number_format($totalHarga2, 0, ',', '.') }}</p>
                    </div>
                    <p>Total Karcis Keluar</p>
                </div>
            </a>
        </div>
    </div>

    <div class="card-isi">
        <div class="header-card">
            <p>Grafik Karcis Tersisa Setiap Jenis (Lembar)</p>
            <hr>
        </div>
        <div class="content-card">

            <div class="grafik">
                <canvas id="myChart3" class="canvas" height="300"></canvas>
            </div>

        </div>
    </div>

    <div class="card-isi">
        <div class="header-card">
            <p>Grafik Karcis Masuk</p>
            <hr>
        </div>
        <div class="content-card">

            <div class="grafik">
                <canvas id="myChart2" class="canvas" height="300"></canvas>
            </div>

        </div>
    </div>

    <div class="card-isi">
        <div class="header-card">
            <p>Grafik Karcis Keluar</p>
            <hr>
        </div>
        <div class="content-card">

            <div class="grafik">
                <canvas id="myChart" class="canvas" height="300"></canvas>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Data untuk myChart
        var chartData = @json($chartData);

        var months = chartData.map(function (data) {
            return data.nama_bulan;
        });

        var totals = chartData.map(function (data) {
            return data.total_per_month;
        });

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Karcis Keluar per Bulan',
                    data: totals,
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', 
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 8
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 8
                            }
                        }
                    }
                },
                plugins: {
                    datalabels: {
                        color: 'black',
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                        },
                        font: {
                            size: 8 
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Data untuk myChart2
        var chartData2 = @json($chartData2);

        var months2 = chartData2.map(function (data) {
            return data.nama_bulan2;
        });

        var totals2 = chartData2.map(function (data) {
            return data.total_per_month2;
        });

        var ctx2 = document.getElementById('myChart2').getContext('2d');
        var myChart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: months2,
                datasets: [{
                    label: 'Total Karcis Masuk per Bulan',
                    data: totals2,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(677, 192, 235, 0.5)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 8
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 8
                            }
                        }
                    }
                },
                plugins: {
                    datalabels: {
                        color: 'black',
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                        },
                        font: {
                            size: 8 
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


                // Data untuk myChart3
                var chartData3 = @json($chartData3);

var jenis = chartData3.map(function (data) {
    return data.jenis_karcis;
});

function formatStok(stok) {
            var buku = Math.floor(stok / 100);
            var lembar = stok % 100;
            if (lembar === 0) {
                return buku + ' buku';
            } else {
                return buku + ' buku ' + lembar + ' lembar';
            }
        }

        var stok = chartData3.map(function (data) {
            return formatStok(data.stok);
        });

var ctx3 = document.getElementById('myChart3').getContext('2d');
var myChart3 = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: jenis,
        datasets: [{
            label: 'Total Stok Tersisa',
            data: chartData3.map(function(data) { return data.stok; }),
            backgroundColor: 'rgba(255, 159, 64, 0.2)', // Orange background color
                    borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', 
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                            font: {
                                size: 8
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 8
                            }
                        }
                    }
        },
                plugins: {
                    datalabels: {
                        color: 'black',
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value) {
                            return value;
                        },
                        font: {
                            size: 8 
                        }
                    }
                }
    },
    plugins: [ChartDataLabels]
});
    });
</script>
@endsection