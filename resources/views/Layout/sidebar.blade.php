<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>DLH - Kota Cilegon</title>
    <link rel="icon" href="{{ asset('image/tickets.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    
    <div id="preloader">
        <div class="loader">
        </div>
    </div>

    <section class="content-isi">
        <div class="sidebar">
            <div class="list-menu">
                <div class="profil">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                            style="object-fit: cover; object-position: center top; width: 70px; height: 70px; margin-right: 5px; border-radius: 50%; border: 2px solid #fff;">

                    @else
                        <img src="{{ asset('image/male_user.png') }}" alt="">


                    @endif                     
                    <p>{{ auth()->user()->nama }} <a href="{{ route('dataProfil') }}"><i style="margin-left: 5px;"
                                class="fa-solid fa-pencil"></i></a> </p>
                </div>
                <div class="list-item">
                    <a class="menu-item {{ request()->routeIs('Admtrdashboard') ? 'active' : '' }}"
                        href="{{ route('Admtrdashboard') }}">Dashboard</a>
                    @if (auth()->user()->isAdministrator())                    
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                            class="menu-item dropdown-toggle {{ request()->is('kelola*') ? 'active' : '' }}">Data<i
                                class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-ctn">
                            <a class="dropdown-item {{ request()->routeIs('dataJenis') ? 'active' : '' }}"
                                href="{{ route('dataJenis') }}">Jenis Karcis</a>
                            <a class="dropdown-item {{ request()->routeIs('dataArea') ? 'active' : '' }}"
                                href="{{ route('dataArea') }}">Area Karcis</a>
                            <a class="dropdown-item {{ request()->routeIs('dataUsers') ? 'active' : '' }}"
                                href="{{ route('dataUsers') }}">Pengguna</a>
                            <a class="dropdown-item {{ request()->routeIs('dataKolektor') ? 'active' : '' }}"
                                href="{{ route('dataKolektor') }}">Kolektor</a>

                        </div>
                    </div>
                    @endif
                    @if (!(auth()->user()->isKepala()))                    
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                            class="menu-item dropdown-toggle {{ request()->is('karcis*') ? 'active' : '' }}">Karcis <i
                                class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-ctn">
                            <a class="dropdown-item {{ request()->routeIs('dataKarcisIn') ? 'active' : '' }}"
                                href="{{ route('dataKarcisIn') }}">Karcis Masuk</a>
                            <a class="dropdown-item {{ request()->routeIs('dataKarcisOut') ? 'active' : '' }}"
                                href="{{ route('dataKarcisOut') }}">Karcis Keluar</a>
                            <a class="dropdown-item {{ request()->routeIs('dataKarcisStok') ? 'active' : '' }}"
                                href="{{ route('dataKarcisStok') }}">Stok Tersisa</a>
                        </div>
                    </div>
                    @endif
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                            class="menu-item dropdown-toggle {{ request()->is('laporan*') ? 'active' : '' }}">Cetak
                            Laporan<i class="fas fa-chevron-right"></i></a>
                        <div class="dropdown-ctn">
                            <a class="dropdown-item {{ request()->routeIs('dataLaporan') ? 'active' : '' }}"
                                href="{{ route('dataLaporan') }}">Karcis</a>
                        </div>
                    </div>
                </div>
                <div class="logoutt ">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="button-lg">
                            <p>Logout</p><img src="{{ asset('image/Logout.png')}}" alt="">
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="sidebar-mobile">
            <div class="menu-mobile">
                <div class="container-mobile">
                    <a class="menu-item {{ request()->routeIs('Admtrdashboard') ? 'active' : '' }}"
                        href="{{ route('Admtrdashboard') }}"><i class="fa-solid fa-house"></i>
                        <p>Dashboard</p>
                    </a>
                    @if (auth()->user()->isAdministrator())                    

                    <a class="menu-item {{ request()->is('kelola*') ? 'active' : '' }}" id="dataMenu" href=""><i
                            class="fa-solid fa-folder-open"></i>
                        <p>Data</p>
                    </a>
                    @endif

                    @if (!(auth()->user()->isKepala()))                    

                    <a class="menu-item {{ request()->is('karcis*') ? 'active' : '' }}" id="karcisMenu" href=""><i
                            class="fa-solid fa-ticket"></i>
                        <p>Karcis</p>
                    </a>
                    @endif

                    <a class="menu-item {{ request()->routeIs('dataLaporan') ? 'active' : '' }}"
                        href="{{ route('dataLaporan') }}" id="laporan"><i class="fa-solid fa-print"></i>
                        <p>Laporan</p>
                    </a>
                    <a class="menu-item {{ request()->is('profil*') ? 'active' : '' }}" href="" id="userMenu"><i class="fa-solid fa-user"></i>
                        <p>User</p>
                    </a>
                </div>
            </div>
            <div class="menu-hover">              

                <div class="keloladata">
                    <a href="{{ route('dataJenis') }}">Data Jenis Karcis</a>
                    <a href="{{ route('dataArea') }}">Data Area Karcis</a>
                    <a href="{{ route('dataUsers') }}">Data Pengguna</a>
                    <a href="{{ route('dataKolektor') }}">Data Kolektor</a>
                </div>

                <div class="karcisdata">
                    <a href="{{ route('dataKarcisIn') }}">Karcis Masuk</a>
                    <a href="{{ route('dataKarcisOut') }}">Karcis Keluar</a>
                    <a href="{{ route('dataKarcisStok') }}">Stok Tersisa</a>
                </div>

                <div class="userdata">
                    <a href="{{ route('dataProfil') }}">Edit Profil</a>
                    <a href="#" id="logout-link">
                        Logout <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        @yield('content')


    </section>
    <script>
    document.getElementById('userMenu').addEventListener('click', function(event) {
    event.preventDefault();
    const kelolaDataDiv = document.querySelector('.menu-hover .keloladata');
    const karcisDataDiv = document.querySelector('.menu-hover .karcisdata');
    const userDataDiv = document.querySelector('.menu-hover .userdata');


    if (userDataDiv.classList.contains('show')) {
        userDataDiv.classList.remove('show');
    } else {
        userDataDiv.classList.add('show');
        kelolaDataDiv.classList.remove('show');
        karcisDataDiv.classList.remove('show');
    }
});

document.getElementById('logout-link').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('logout-form').submit();
});
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>