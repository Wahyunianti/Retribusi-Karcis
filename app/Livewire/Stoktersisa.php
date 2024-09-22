<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User as UserKelola;
use App\Models\Masuk;
use App\Models\Area;
use App\Models\Jenis;
use App\Models\Keluar;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Stoktersisa extends Component
{
    public $data;
    public $data2;
    public $data3;

    use WithPagination;

    public $selectedJenis = '';
    public $startDate = '';
    public $endDate = '';
    public $jenis = [];
    public $months = [];
    public $selectedMonth;
    public function render()
    {
        return view('livewire.stoktersisa', [
            'karcis' => $this->data,
            'karcis_jns' => $this->data2,
            'karcis_hts' => $this->data3,
            'jenis' => $this->jenis
        ]);
    }

    public function mount()
    {
        $this->jenis = Jenis::all();
        $this->getData();
        $this->getData2();
        $this->getData3();
        $this->months = [
            ['id' => '01', 'nama' => 'Januari'],
            ['id' => '02', 'nama' => 'Februari'],
            ['id' => '03', 'nama' => 'Maret'],
            ['id' => '04', 'nama' => 'April'],
            ['id' => '05', 'nama' => 'Mei'],
            ['id' => '06', 'nama' => 'Juni'],
            ['id' => '07', 'nama' => 'Juli'],
            ['id' => '08', 'nama' => 'Agustus'],
            ['id' => '09', 'nama' => 'September'],
            ['id' => '10', 'nama' => 'Oktober'],
            ['id' => '11', 'nama' => 'November'],
            ['id' => '12', 'nama' => 'Desember'],
        ];

    }

    public function cariData()
    {
        $this->getData();
    }

    public function getData()
    {
        $query = DB::table(DB::raw("
        (
            SELECT 
                jenis_id, 
                DATE_FORMAT(tgl_masuk, '%m') AS bulan, 
                DATE_FORMAT(tgl_masuk, '%Y') AS tahun, 
                jml AS stok,
                total
            FROM 
                karcis_masuk
            UNION ALL
            SELECT 
                jenis_id, 
                DATE_FORMAT(tgl_ambil, '%m') AS bulan, 
                DATE_FORMAT(tgl_ambil, '%Y') AS tahun, 
                -jml AS stok,
                -total
            FROM 
                karcis_keluar
        ) AS combined
    "))
        ->join('jenis', 'combined.jenis_id', '=', 'jenis.id')
        ->select(
            'jenis.nama AS jenis_karcis',
            'combined.bulan',
            'combined.tahun',
            DB::raw("
                CASE 
                    WHEN combined.bulan = '01' THEN 'Januari'
                    WHEN combined.bulan = '02' THEN 'Februari'
                    WHEN combined.bulan = '03' THEN 'Maret'
                    WHEN combined.bulan = '04' THEN 'April'
                    WHEN combined.bulan = '05' THEN 'Mei'
                    WHEN combined.bulan = '06' THEN 'Juni'
                    WHEN combined.bulan = '07' THEN 'Juli'
                    WHEN combined.bulan = '08' THEN 'Agustus'
                    WHEN combined.bulan = '09' THEN 'September'
                    WHEN combined.bulan = '10' THEN 'Oktober'
                    WHEN combined.bulan = '11' THEN 'November'
                    WHEN combined.bulan = '12' THEN 'Desember'
                END AS nama_bulan
            "),
            DB::raw('SUM(combined.stok) AS total_stok'),
            DB::raw('SUM(combined.total) AS total_amount')
        )
        ->groupBy('jenis.nama', 'combined.bulan', 'combined.tahun')
        ->orderBy('combined.bulan', 'desc')
        ->orderBy('jenis.nama');
    
    if ($this->selectedJenis) {
        $query->where('jenis.id', $this->selectedJenis);
    }
    
    if ($this->selectedMonth) {
        $query->where('combined.bulan', $this->selectedMonth);
    }
    
    $this->data = $query->get();
    
    }


    public function getData2()
    {
        $query = DB::table(DB::raw("
    (
        SELECT 
            jenis_id, 
            jml AS stok,
            total
        FROM 
            karcis_masuk
        UNION ALL
        SELECT 
            jenis_id, 
            -jml AS stok,
            -total
        FROM 
            karcis_keluar
    ) AS combined
"))
            ->join('jenis', 'combined.jenis_id', '=', 'jenis.id')
            ->select(
                'jenis.nama AS jenis_karcis',
                DB::raw('SUM(combined.stok) AS stok'),
                DB::raw('SUM(combined.total) AS total')
            )
            ->groupBy('jenis.nama')
            ->orderBy('jenis.nama');

        $this->data2 = $query->get();
    }

    public function getData3()
    {
        $karcis = DB::select("
            SELECT
                km.tgl_masuk AS tanggal,
                'karcis masuk' AS karcis,
                j.nama AS jenis_karcis,
                km.jml AS jumlah,
                km.total,
                CONCAT(km.penyetok, ' (penyetok)') AS penanggung_jawab
            FROM
                karcis_masuk km
            JOIN jenis j ON km.jenis_id = j.id
    
            UNION ALL
    
            SELECT
                kk.tgl_ambil AS tanggal,
                'karcis keluar' AS karcis,
                j.nama AS jenis_karcis,
                kk.jml AS jumlah,
                kk.total,
                CONCAT(kk.kolektor, ' (kolektor)') AS penanggung_jawab
            FROM
                karcis_keluar kk
            JOIN jenis j ON kk.jenis_id = j.id
    
            ORDER BY tanggal DESC, karcis, jenis_karcis
        ");
    
        $this->data3 = $karcis;
    }
}
