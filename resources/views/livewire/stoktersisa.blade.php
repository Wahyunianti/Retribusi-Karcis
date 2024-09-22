<div class="main">


    <div class="card-isi">
        <div class="header-card">
            <p>Tabel Data Stok Tersisa Perjenis (global)</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="content-table">
                <div class="table-container">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Stok Tersisa</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karcis_jns as $kcs)
                                                        <tr>
                                                            <td>{{ $kcs->jenis_karcis}}</td>
                                                            <td><?php    
                                                            $a = $kcs->stok;
                                $bagian = 100;

                                $hasilBagi = $a / $bagian;
                                $sisa = $a % $bagian;

                                $k = intval($hasilBagi);

                                if ($sisa != 0) {
                                    $hasil = $k . " buku ".  $sisa . " lembar" ;
                                }  else {
                                    $hasil = $hasilBagi . " buku " ;
                                }

                                echo $hasil;?></td>
                                                            <td>{{ 'Rp ' . number_format($kcs->total, 0, ',', '.') }}</td>
                                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card-isi">
        <div class="header-card">
            <p>Tabel Data History Penginputan</p>
            <hr />
        </div>
        <div class="content-card">
            <div class="content-table">
                <div class="table-container">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Karcis</th>
                                <th>Jenis Karcis</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Penanggung Jawab</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karcis_hts as $kcs)
                                                        <tr>
                                                            <td>{{ $kcs->tanggal}}</td>
                                                            <td>
                                                                <div class="bagian-warna {{ $kcs->karcis == 'karcis masuk' ? 'hijau' : 'merah' }}">
                                                                    <p>{{ $kcs->karcis}}</p>
                                                                </div>
                                                            </td>
                                                            <td>{{ $kcs->jenis_karcis}}</td>
                                                            <td><?php    
                                                            $a = $kcs->jumlah;
                                $bagian = 100;

                                $hasilBagi = $a / $bagian;
                                $sisa = $a % $bagian;

                                $k = intval($hasilBagi);

                                if ($sisa != 0) {
                                    $hasil = $k . " buku ".  $sisa . " lembar" ;
                                }  else {
                                    $hasil = $hasilBagi . " buku " ;
                                }

                                echo $hasil;?></td>
                                                            <td>{{ 'Rp ' . number_format($kcs->total, 0, ',', '.') }}</td>
                                                            <td>{{ $kcs->penanggung_jawab}}</td>

                                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <footer>
            <p>Copyright @2024 . Robby S</p>
        </footer>
    </div>
</div>