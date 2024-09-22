<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>DLH - Kota Cilegon</title>
    <link rel="icon" href="{{ asset('image/tickets.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="wrapbg">


        <section class="db-header">
            <div class="db-overlay">
                <div class="db-h-warna">
                    <div class="db-h-ctn">
                        <div class="hd-kiri">
                            <img src="image/cilegon.png" alt="">
                            <img src="image/dlh.png" alt="">
                            <p>DLH KOTA CILEGON</p>
                        </div>
                        <div class="hd-kanan">
                            <a href="{{route('login')}}">
                                <p>LOGIN</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="db-area">
            <div class="db-card">
                <div class="db-card-hd">
                    <p>Selamat Datang</p>
                </div>
                <div class="db-card-ctn">

                    <img src="image/baja.jpg" alt="">
                    <p>Sistem ini dikembangkan untuk management karcis DLH Kota Cilegon Banten</p>

                </div>
            </div>

            <div class="db-card">
                <div class="db-card-hd">
                    <p>Menu</p>
                </div>
                <div class="db-card-ctn">
                    <div class="tab-container">
                        <ul class="tab-list">
                            <li class="tab-item active" data-tab-target="#tab1">Data</li>
                            <li class="tab-item " data-tab-target="#tab2">Grafik</li>
                            <li class="tab-item" data-tab-target="#tab3">Kolektor</li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="card-tab">
                                    <div class="data-tab">
                                        <div class="pertama">
                                            <i class="fa-solid fa-box"></i>
                                            <p>{{$totalStok}}</p>
                                        </div>
                                        <div class="kedua">
                                            <p>Stok Karcis Masuk</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-tab">
                                    <div class="data-tab">
                                        <div class="pertama">
                                            <i class="fa-solid fa-box-open"></i>
                                            <p>{{$totalStok2}}</p>
                                        </div>
                                        <div class="kedua">
                                            <p>Stok Karcis Keluar</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-tab">
                                    <div class="data-tab">
                                        <div class="pertama">
                                            <i class="fa-solid fa-credit-card"></i>
                                            <p>{{ 'Rp ' . number_format($totalHarga, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="kedua">
                                            <p>Total Karcis Masuk</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-tab">
                                    <div class="data-tab">
                                        <div class="pertama">
                                            <i class="fa-regular fa-credit-card"></i>
                                            <p>{{ 'Rp ' . number_format($totalHarga2, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="kedua">
                                            <p>Total Karcis Keluar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="dt-graph">
                                    <p>Grafik Karcis Tersisa Setiap Jenis (Lembar)</p>
                                    <div class="grafik">
                                        <canvas id="myChart3" class="canvas" height="300"></canvas>
                                    </div>
                                </div>

                                <div class="dt-graph">
                                    <p>Grafik Karcis Masuk</p>
                                    <div class="grafik">
                                        <canvas id="myChart2" class="canvas" height="300"></canvas>
                                    </div>
                                </div>

                                
                                <div class="dt-graph">
                                    <p>Grafik Karcis Keluar</p>
                                    <div class="grafik">
                                    <canvas id="myChart" class="canvas" height="300"></canvas>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="tab3">

                            @foreach ($kolektor as $klt)
                                <div class="ov-kolek">
                                    <div class="dt-kolek">
                                        <div class="dt-image">
                                            <img src="image/profil.png" alt="">
                                        </div>
                                        <div class="dt-ket">
                                            <div class="dt-ket-header">
                                                <p>Data Kolektor</p>
                                            </div>
                                            <div class="p-ket">
                                                <p>Nama : {{ $klt->nama }}</p>
                                                <p>Nip : {{ $klt->nip }}</p>
                                                <p>Area : {{ str_replace('|', ', ', $klt->area) }}</p>
                                                <p>Status : {{ $klt->status }}</p>
                                                <p>Masa : {{ $klt->masa }}</p>
                                                <p>Keterangan : {{ $klt->keterangan }}</p>
                                            </div>
                                        </div>
                                        <div class="sr-tugas">
                                            <img src="image/surat.png" alt="">
                                            @if ($klt->file)
                                            <a href="{{ asset('storage/' . $klt->file) }}" target="_blank">
                                                <p>Lihat Surat Tugas</p><i
                                                    class="fa-solid fa-square-arrow-up-right"></i>
                                            </a>
                                        @else
                                        <a href="" >
                                                <p>Tidak Ada Surat Tugas</p><i
                                                    class="fa-solid fa-square-arrow-up-right"></i>
                                            </a>
                                        @endif

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </section>


        <section class="db-footer">
            <div class="cr-footer">
                <p>Copyright@2024 by Robby S.</p>
            </div>
        </section>

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
                    borderColor: 'rgba(54, 162, 235, 1)',
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
            backgroundColor: 'rgba(255, 159, 64, 0.2)', 
                    borderColor: 'rgba(255, 159, 64, 1)',
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


    document.addEventListener('DOMContentLoaded', function () {
                const tabs = document.querySelectorAll('.tab-item');
                const tabPanes = document.querySelectorAll('.tab-pane');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function () {
                        const target = document.querySelector(tab.dataset.tabTarget);

                        tabs.forEach(t => t.classList.remove('active'));
                        tabPanes.forEach(pane => pane.classList.remove('active'));

                        tab.classList.add('active');
                        target.classList.add('active');
                    });
                });

                
            });


        </script>

        <script src="{{ asset('js/script.js') }}"></script>
    </div>
</body>

</html>