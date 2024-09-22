@extends('Layout.sidebar')

@section('content')
<div class="main">
    <div class="card-isi">
        <div class="header-card">
            <p>Data Laporan Karcis Global</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="content-table">
                <div class="table-container">
                    <table class="responsive-table">
                        <tbody>
                            <td>
                                <div class="bagian-warna kuning">
                                    <p>
                                        Laporan Karcis Tersisa Perbulan 2024</p>
                                </div>
                            </td>
                            <td>
                                <div class="aksi">
                                    <a href="{{ route('cetak.export1') }}" class="button-xr unduh"><i
                                            class="fa-solid fa-file-excel"></i> unduh .xls</a>
                                    <a href="{{ route('cetak.export2') }}" target="blank"
                                        class="button-xr unduh pdf-a"><i class="fa-solid fa-file-pdf"></i> unduh
                                        .pdf</a>
                                </div>
                            </td>
                        </tbody>
                        <tbody>
                            <td>
                                <div class="bagian-warna merah">
                                    <p>
                                        Laporan Karcis Keluar All Year</p>
                                </div>
                            </td>
                            <td>
                                <div class="aksi">
                                    <a href="{{ route('cetak.export3') }}" class="button-xr unduh"><i
                                            class="fa-solid fa-file-excel"></i> unduh .xls</a>
                                    <a href="{{ route('cetak.export4') }}" target="blank"
                                        class="button-xr unduh pdf-a"><i class="fa-solid fa-file-pdf"></i> unduh
                                        .pdf</a>
                                </div>
                            </td>
                        </tbody>
                        <tbody>
                            <td>
                                <div class="bagian-warna hijau">
                                    <p>
                                        Laporan Karcis Masuk All Year</p>
                                </div>
                            </td>
                            <td>
                                <div class="aksi">
                                    <a href="{{ route('cetak.export5') }}" class="button-xr unduh"><i
                                            class="fa-solid fa-file-excel"></i> unduh .xls</a>
                                    <a href="{{ route('cetak.export6') }}" target="blank"
                                        class="button-xr unduh pdf-a"><i class="fa-solid fa-file-pdf"></i> unduh
                                        .pdf</a>
                                </div>
                            </td>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="card-isi">
        <div class="header-card">
            <p>Filter Laporan Karcis PerWilayah PerJenis</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="cari-data">
                <form id="exportForm" action="{{ route('cetak.export7') }}" method="POST">
                    @csrf
                    <select class="input-t" name="area_id">
                        <option value="">Pilih Area</option>
                        @foreach($area as $ara)
                            <option value="{{ $ara->id }}">{{ $ara->nama }}</option>
                        @endforeach
                    </select>
                    <select class="input-t" name="jenis_id">
                        <option value="">Pilih Jenis</option>
                        @foreach($jenis as $jns)
                            <option value="{{ $jns->id }}">{{ $jns->nama }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="button-xr unduh filter"><i class="fa-solid fa-file-excel"></i> unduh
                    </button>
                    <button type="button" class="button-xr unduh pdf-a filter" id="unduhPDF"><i
                            class="fa-solid fa-file-pdf"></i>
                        unduh
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card-isi">
        <div class="header-card">
            <p>Filter Laporan Karcis PerBulan PerJenis</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="cari-data">
                <form id="formBulan" action="{{ route('cetak.export9') }}" method="POST">
                    @csrf
                    <select class="input-t" name="bulan_id">
                        <option value="">Pilih Bulan</option>
                        @foreach($months as $month)
                            <option value="{{ $month['id'] }}">{{ $month['nama'] }}</option>
                        @endforeach
                    </select>
                    <select class="input-t" name="jenis_id">
                        <option value="">Pilih Jenis</option>
                        @foreach($jenis as $jns)
                            <option value="{{ $jns->id }}">{{ $jns->nama }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="button-xr unduh filter"><i class="fa-solid fa-file-excel"></i> unduh
                    </button>
                    <button type="button" id="unduhBln" class="button-xr unduh pdf-a filter"><i class="fa-solid fa-file-pdf"></i>
                        unduh
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <footer>
            <p>Copyright @2024 . Robby S</p>
        </footer>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#unduhPDF').on('click', function () {
            $('#exportForm').attr('action', "{{ route('cetak.export8') }}");
            $('#exportForm').submit();
        });
    });

    $(document).ready(function () {
        $('#unduhBln').on('click', function () {
            $('#formBulan').attr('action', "{{ route('cetak.export0') }}");
            $('#formBulan').submit();
        });
    });
</script>
@endsection