<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Keluar;
use App\Models\Kolektor;
use App\Models\Masuk;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{

    protected $totalStok ;
    protected $totalStok2 ;
    protected $totalHarga ;
    protected $totalHarga2 ;

    protected $chartData ;
    protected $chartData2 ;
    protected $chartData3 ;

    protected $kolektor ;


    public function __construct()
    {
        $this->chartData = Keluar::select(
            DB::raw('MONTH(tgl_ambil) as month'),
            DB::raw('SUM(total) as total_per_month'),
            DB::raw("
                CASE 
                    WHEN MONTH(tgl_ambil) = '01' THEN 'Januari'
                    WHEN MONTH(tgl_ambil) = '02' THEN 'Februari'
                    WHEN MONTH(tgl_ambil) = '03' THEN 'Maret'
                    WHEN MONTH(tgl_ambil) = '04' THEN 'April'
                    WHEN MONTH(tgl_ambil) = '05' THEN 'Mei'
                    WHEN MONTH(tgl_ambil) = '06' THEN 'Juni'
                    WHEN MONTH(tgl_ambil) = '07' THEN 'Juli'
                    WHEN MONTH(tgl_ambil) = '08' THEN 'Agustus'
                    WHEN MONTH(tgl_ambil) = '09' THEN 'September'
                    WHEN MONTH(tgl_ambil) = '10' THEN 'Oktober'
                    WHEN MONTH(tgl_ambil) = '11' THEN 'November'
                    WHEN MONTH(tgl_ambil) = '12' THEN 'Desember'
                END AS nama_bulan
            ")
        )
        ->groupBy(DB::raw('month'), DB::raw('nama_bulan'))
        ->orderBy(DB::raw('MONTH(tgl_ambil)'))
        ->get();

        $this->chartData2 = Masuk::select(
            DB::raw('MONTH(tgl_masuk) as month2'),
            DB::raw('SUM(total) as total_per_month2'),
            DB::raw("
                CASE 
                    WHEN MONTH(tgl_masuk) = '01' THEN 'Januari'
                    WHEN MONTH(tgl_masuk) = '02' THEN 'Februari'
                    WHEN MONTH(tgl_masuk) = '03' THEN 'Maret'
                    WHEN MONTH(tgl_masuk) = '04' THEN 'April'
                    WHEN MONTH(tgl_masuk) = '05' THEN 'Mei'
                    WHEN MONTH(tgl_masuk) = '06' THEN 'Juni'
                    WHEN MONTH(tgl_masuk) = '07' THEN 'Juli'
                    WHEN MONTH(tgl_masuk) = '08' THEN 'Agustus'
                    WHEN MONTH(tgl_masuk) = '09' THEN 'September'
                    WHEN MONTH(tgl_masuk) = '10' THEN 'Oktober'
                    WHEN MONTH(tgl_masuk) = '11' THEN 'November'
                    WHEN MONTH(tgl_masuk) = '12' THEN 'Desember'
                END AS nama_bulan2
            ")
        )
        ->groupBy(DB::raw('month2'), DB::raw('nama_bulan2'))
        ->orderBy(DB::raw('MONTH(tgl_masuk)'))
        ->get();

        $this->chartData3 = DB::table('jenis as j')
        ->select('j.nama as jenis_karcis', DB::raw('SUM(combined.stok) as stok'))
        ->join(DB::raw('(SELECT jenis_id, jml as stok FROM karcis_masuk
                        UNION ALL
                        SELECT jenis_id, -jml as stok FROM karcis_keluar) as combined'), 
               'combined.jenis_id', '=', 'j.id')
        ->groupBy('j.nama')
        ->orderBy('stok', 'asc')
        ->get();

        $jmlKM = DB::table('karcis_masuk as km')
            ->selectRaw('COALESCE(SUM(km.jml), 0) AS jumlah')
            ->selectRaw('COALESCE(SUM(km.total), 0) AS total')
            ->first();
        $jmlKK = DB::table('karcis_keluar as kk')
            ->selectRaw('COALESCE(SUM(kk.jml), 0) AS jumlah')
            ->selectRaw('COALESCE(SUM(kk.total), 0) AS total')
            ->first();
        $ttlKM = ($jmlKM->jumlah);
        $ttlKM2 = ($jmlKK->jumlah);

        $ttlHG = ($jmlKM->total);
        $ttlHG2 = ($jmlKK->total);

        $this->totalStok = $ttlKM;
        $this->totalStok2 = $ttlKM2;
        $this->totalHarga = $ttlHG;
        $this->totalHarga2 = $ttlHG2;

        $this->kolektor = Kolektor::all();
    }

    public function dashboard_view(){
        return view('dashboard_viewer', [
            "chartData" => $this->chartData,
            "chartData2" => $this->chartData2,
            "chartData3" => $this->chartData3,
            "totalStok" => $this->totalStok,
            "totalStok2" => $this->totalStok2,
            "totalHarga" => $this->totalHarga,
            "totalHarga2" => $this->totalHarga2,
            "kolektor" => $this->kolektor
        ]);
    }

    public function dashboard_admin(){
        return view('Admin.dashboard', [
            "chartData" => $this->chartData,
            "chartData2" => $this->chartData2,
            "chartData3" => $this->chartData3,
            "totalStok" => $this->totalStok,
            "totalStok2" => $this->totalStok2,
            "totalHarga" => $this->totalHarga,
            "totalHarga2" => $this->totalHarga2
        ]);
     }

}