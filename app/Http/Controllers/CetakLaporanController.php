<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Support\Facades\DB;
use App\Contracts\ExcelExportInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use FPDF;
use App\Models\Masuk;
use App\Models\Keluar;


use Illuminate\Support\Facades\Log;

require (app_path('Libraries/fpdf.php'));

class CetakLaporanController extends Controller
{
    const FILE_NAME = 'Laporan_Karcis_DKLH_';


    public function outputTheExcel(object $spreadsheet, string $fileName): void
    {
        $writer = new Xlsx($spreadsheet);

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . date('dmY_His') . '".xlsx');
        $writer->save('php://output');
        exit();
    }

    public static function setStyle(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'size' => 9,
                'name' => 'Arial',
            ],
            'curecy' => [
                'size' => 9,
                'name' => 'Arial',
            ]
        ];
    }


    public static function setWarna(): array
    {
        return [

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'D8E4BC'],
            ]
        ];
    }

    public function getData1()
    {
        $results = DB::table(DB::raw("
        (SELECT 
            jenis_id, 
            tgl_masuk AS tanggal, 
            total AS total,
            jml AS jumlah
        FROM karcis_masuk
        UNION ALL
        SELECT 
            jenis_id, 
            tgl_ambil AS tanggal, 
            -total AS total,
            -jml AS jumlah
        FROM karcis_keluar
        ) AS combined
    "))
            ->join('jenis AS j', 'combined.jenis_id', '=', 'j.id')
            ->select(
                'j.nama AS jenis_karcis',
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-01' THEN combined.total ELSE 0 END) AS `Januari`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-01' THEN combined.jumlah ELSE 0 END) AS `jml_Januari`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-02' THEN combined.total ELSE 0 END) AS `Februari`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-02' THEN combined.jumlah ELSE 0 END) AS `jml_Februari`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-03' THEN combined.total ELSE 0 END) AS `Maret`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-03' THEN combined.jumlah ELSE 0 END) AS `jml_Maret`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-04' THEN combined.total ELSE 0 END) AS `April`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-04' THEN combined.jumlah ELSE 0 END) AS `jml_April`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-05' THEN combined.total ELSE 0 END) AS `Mei`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-05' THEN combined.jumlah ELSE 0 END) AS `jml_Mei`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-06' THEN combined.total ELSE 0 END) AS `Juni`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-06' THEN combined.jumlah ELSE 0 END) AS `jml_Juni`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-07' THEN combined.total ELSE 0 END) AS `Juli`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-07' THEN combined.jumlah ELSE 0 END) AS `jml_Juli`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-08' THEN combined.total ELSE 0 END) AS `Agustus`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-08' THEN combined.jumlah ELSE 0 END) AS `jml_Agustus`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-09' THEN combined.total ELSE 0 END) AS `September`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-09' THEN combined.jumlah ELSE 0 END) AS `jml_September`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-10' THEN combined.total ELSE 0 END) AS `Oktober`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-10' THEN combined.jumlah ELSE 0 END) AS `jml_Oktober`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-11' THEN combined.total ELSE 0 END) AS `November`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-11' THEN combined.jumlah ELSE 0 END) AS `jml_November`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-12' THEN combined.total ELSE 0 END) AS `Desember`"),
                DB::raw("SUM(CASE WHEN DATE_FORMAT(combined.tanggal, '%Y-%m') = '2024-12' THEN combined.jumlah ELSE 0 END) AS `jml_Desember`")
            )
            ->groupBy('j.nama')
            ->orderBy('j.nama')
            ->get();

        return $results;
    }

    public function getData2()
    {
        $results = DB::table('karcis_keluar as kk')
            ->select(
                'kk.kolektor as Nama',
                'kk.tgl_ambil as Tanggal',
                'j.nama as Jenis',
                'j.harga as Nominal',
                'kk.jml as Quantity',
                DB::raw("'lembar' as Satuan"),
                'kk.total as Total',
                'a.nama as Wilayah',
                'users_id as Penyerah'
            )
            ->join('jenis as j', 'kk.jenis_id', '=', 'j.id')
            ->join('AREA as a', 'kk.area_id', '=', 'a.id')
            ->orderByDesc('kk.tgl_ambil')
            ->get();

        return $results;

    }

    public function getData3()
    {
        $results = DB::table('karcis_masuk as km')
            ->select(
                'km.penyetok as Nama',
                'km.tgl_masuk as Tanggal',
                DB::raw('j.nama as Jenis'),
                DB::raw('j.harga as Nominal'),
                'km.jml as Quantity',
                DB::raw("'lembar' as Satuan"),
                'km.total as Total',
                'users_id as Penerima'
            )
            ->join('jenis as j', 'km.jenis_id', '=', 'j.id')
            ->orderByDesc('km.tgl_masuk')
            ->get();

        return $results;

    }

    public function getData4(int $jns, int $ara)
    {
        $query = DB::table('karcis_keluar as kk')
            ->join('jenis as j', 'kk.jenis_id', '=', 'j.id')
            ->join('area as a', 'kk.area_id', '=', 'a.id')
            ->select([
                'a.nama as Area',
                'kk.tgl_ambil as Tanggal',
                'kk.kolektor as Nama',
                'j.nama as Jenis',
                'j.harga as Nominal',
                'kk.jml as Quantity',
                DB::raw("'lembar' as Satuan"),
                'kk.total as Total',
                'users_id as Penyerah'
            ]);

        if (!empty($ara)) {
            $query->where('a.id', '=', $ara);
        }

        if (!empty($jns)) {
            $query->where('j.id', '=', $jns);
        }

        $query->orderBy('kk.tgl_ambil', 'desc');

        $data = $query->get();
        return $data;

    }

    public function getData5(int $jenis, int $bulan)
    {
        $queryMasuk = DB::table('karcis_masuk as km')
            ->join('jenis as j', 'km.jenis_id', '=', 'j.id')
            ->select(
                DB::raw("'karcis masuk' as karcis"),
                'km.tgl_masuk as tanggal',
                'j.nama as jenis_karcis',
                'km.jml as jumlah',
                DB::raw("'lembar' as satuan"),
                'km.total',
                DB::raw("CONCAT(km.penyetok, ' (penyetok)') as penanggung_jawab")
            );

        $queryKeluar = DB::table('karcis_keluar as kk')
            ->join('jenis as j', 'kk.jenis_id', '=', 'j.id')
            ->select(
                DB::raw("'karcis keluar' as karcis"),
                'kk.tgl_ambil as tanggal',
                'j.nama as jenis_karcis',
                'kk.jml as jumlah',
                DB::raw("'lembar' as satuan"),
                'kk.total',
                DB::raw("CONCAT(kk.kolektor, ' (kolektor)') as penanggung_jawab")
            );

        if ($bulan) {
            $queryMasuk->whereRaw('MONTH(km.tgl_masuk) = ?', [$bulan]);
            $queryKeluar->whereRaw('MONTH(kk.tgl_ambil) = ?', [$bulan]);
        }

        if ($jenis) {
            $queryMasuk->where('j.id', $jenis);
            $queryKeluar->where('j.id', $jenis);
        }

        $query = $queryMasuk->unionAll($queryKeluar)
            ->orderBy('tanggal', 'desc')
            ->orderBy('karcis')
            ->orderBy('jenis_karcis');

        $data = $query->get();

        return $data;
    }

    //Global Karcis

    public function cetak_bln_global()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:A3');
        $sheet->mergeCells('B1:Y1');
        $sheet->mergeCells('B2:C2');
        $sheet->mergeCells('D2:E2');
        $sheet->mergeCells('F2:G2');
        $sheet->mergeCells('H2:I2');
        $sheet->mergeCells('J2:K2');
        $sheet->mergeCells('L2:M2');
        $sheet->mergeCells('N2:O2');
        $sheet->mergeCells('P2:Q2');
        $sheet->mergeCells('R2:S2');
        $sheet->mergeCells('T2:U2');
        $sheet->mergeCells('V2:W2');
        $sheet->mergeCells('X2:Y2');
        $sheet->setCellValue('A1', 'Jenis Karcis');
        $sheet->setCellValue('B1', 'Laporan Karcis Tersisa Global 2024');
        $sheet->setCellValue('B2', 'Januari');
        $sheet->setCellValue('D2', 'Februari');
        $sheet->setCellValue('F2', 'Maret');
        $sheet->setCellValue('H2', 'April');
        $sheet->setCellValue('J2', 'Mei');
        $sheet->setCellValue('L2', 'Juni');
        $sheet->setCellValue('N2', 'Juli');
        $sheet->setCellValue('P2', 'Agustus');
        $sheet->setCellValue('R2', 'September');
        $sheet->setCellValue('T2', 'Oktober');
        $sheet->setCellValue('V2', 'November');
        $sheet->setCellValue('X2', 'Desember');
        $sheet->setCellValue('B3', 'Total');
        $sheet->setCellValue('C3', 'Stok');
        $sheet->setCellValue('D3', 'Total');
        $sheet->setCellValue('E3', 'Stok');
        $sheet->setCellValue('F3', 'Total');
        $sheet->setCellValue('G3', 'Stok');
        $sheet->setCellValue('H3', 'Total');
        $sheet->setCellValue('I3', 'Stok');
        $sheet->setCellValue('J3', 'Total');
        $sheet->setCellValue('K3', 'Stok');
        $sheet->setCellValue('L3', 'Total');
        $sheet->setCellValue('M3', 'Stok');
        $sheet->setCellValue('N3', 'Total');
        $sheet->setCellValue('O3', 'Stok');
        $sheet->setCellValue('P3', 'Total');
        $sheet->setCellValue('Q3', 'Stok');
        $sheet->setCellValue('R3', 'Total');
        $sheet->setCellValue('S3', 'Stok');
        $sheet->setCellValue('T3', 'Total');
        $sheet->setCellValue('U3', 'Stok');
        $sheet->setCellValue('V3', 'Total');
        $sheet->setCellValue('W3', 'Stok');
        $sheet->setCellValue('X3', 'Total');
        $sheet->setCellValue('Y3', 'Stok');

        foreach (range('A1', 'Y1') as $paragraph) {
            $sheet->getColumnDimension($paragraph)->setAutoSize(true);
        }


        $cell = 4;
        foreach ($this->getData1() as $key => $row) {
            $sheet->setCellValue('A' . $cell, $row->jenis_karcis);
            $sheet->setCellValue('B' . $cell, $row->Januari);
            $sheet->setCellValue('C' . $cell, $row->jml_Januari);
            $sheet->setCellValue('D' . $cell, $row->Februari);
            $sheet->setCellValue('E' . $cell, $row->jml_Februari);
            $sheet->setCellValue('F' . $cell, $row->Maret);
            $sheet->setCellValue('G' . $cell, $row->jml_Maret);
            $sheet->setCellValue('H' . $cell, $row->April);
            $sheet->setCellValue('I' . $cell, $row->jml_April);
            $sheet->setCellValue('J' . $cell, $row->Mei);
            $sheet->setCellValue('K' . $cell, $row->jml_Mei);
            $sheet->setCellValue('L' . $cell, $row->Juni);
            $sheet->setCellValue('M' . $cell, $row->jml_Juni);
            $sheet->setCellValue('N' . $cell, $row->Juli);
            $sheet->setCellValue('O' . $cell, $row->jml_Juli);
            $sheet->setCellValue('P' . $cell, $row->Agustus);
            $sheet->setCellValue('Q' . $cell, $row->jml_Agustus);
            $sheet->setCellValue('R' . $cell, $row->September);
            $sheet->setCellValue('S' . $cell, $row->jml_September);
            $sheet->setCellValue('T' . $cell, $row->Oktober);
            $sheet->setCellValue('U' . $cell, $row->jml_Oktober);
            $sheet->setCellValue('V' . $cell, $row->November);
            $sheet->setCellValue('W' . $cell, $row->jml_November);
            $sheet->setCellValue('X' . $cell, $row->Desember);
            $sheet->setCellValue('Y' . $cell, $row->jml_Desember);
            $cell++;
            $sheet->getStyle('A1:Y' . ($cell - 1))->applyFromArray($this->setStyle());
            $sheet->getStyle('A1:Y3')->applyFromArray($this->setWarna());

        }

        $this->outputTheExcel($spreadsheet, self::FILE_NAME);

    }


    public function cetak_bln_global_pdf()
    {
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Total Tersisa Retribusi Karcis Perbulan 2024', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Times', '', 8);

        $pdf->SetFillColor(216, 228, 188);
        $pdf->Cell(34, 6, 'Jenis Karcis', 1, 0, 'C', true);

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        foreach ($months as $month) {
            $pdf->Cell(20, 6, $month, 1, 0, 'C', true);
        }
        $pdf->SetFont('Times', '', 7);


        $pdf->Ln();
        foreach ($this->getData1() as $row) {
            $pdf->Cell(34, 6, $row->jenis_karcis, 1, 0, 'C');

            foreach ($months as $month) {
                $value = isset($row->{$month}) ? 'Rp ' . number_format($row->{$month}, 0, ',', '.') : '-';
                $pdf->Cell(20, 6, $value, 1, 0, 'C');
            }

            $pdf->Ln();
        }

        $pdf->Ln(10);


        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Stok Tersisa Retribusi Karcis Perbulan 2024', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Times', '', 8);

        $pdf->SetFillColor(216, 228, 188);
        $pdf->Cell(34, 6, 'Jenis Karcis', 1, 0, 'C', true);

        foreach ($months as $month) {
            $pdf->Cell(20, 6, $month, 1, 0, 'C', true);
        }
        $pdf->SetFont('Times', '', 7);


        $pdf->Ln();
        foreach ($this->getData1() as $row) {
            $pdf->Cell(34, 6, $row->jenis_karcis, 1, 0, 'C');

            foreach ($months as $month) {
                $value = isset($row->{'jml_' . $month}) ? $row->{'jml_' . $month} : '-';
                $pdf->Cell(20, 6, $value, 1, 0, 'C');
            }

            $pdf->Ln();
        }

        $pdf->Ln(15);
        $pdf->SetFont('Times', '', 10);



        $pdf->Cell(0, 10, 'https://dlhkotacilegon.com', 0, 1, 'C');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf->Output('I', 'report.pdf');
        exit;
    }

    public function cetak_satuan($id)
    {
        $results = Masuk::findOrFail($id);

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Karcis Masuk Satuan' , 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Times', '', 12);

        $jumlah = number_format($results->jml, 0, ',', '.');
        $total = 'Rp. '.number_format($results->total, 0, ',', '.');


        $pdf->Cell(0, 10, 'Nama Penyetok : '.$results->penyetok , 0, 1);
        $pdf->Cell(0, 10, 'Nama Penerima : '.$results->users_id , 0, 1);
        $pdf->Cell(0, 10, 'Tanggal Masuk : '.$results->tgl_masuk , 0, 1);
        $pdf->Cell(0, 10, 'Jenis : '.$results->jenis->nama , 0, 1);
        $pdf->Cell(0, 10, 'Jumlah : '.$jumlah. ' Lembar' , 0, 1);     
        $pdf->Cell(0, 10, 'Total : '.$total.',-' , 0, 1);     
        $pdf->Cell(0, 10, 'Nomor : '.$results->nomor , 0, 1);

        $pdf->Ln(15);

        $pdf->SetFont('Times', '', 10);



        $pdf->Cell(0, 10, 'https://sande.id', 0, 1, 'C');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf->Output('I', 'karcis_masuk.pdf');
        exit;
    }

    public function cetak_keluar($id)
    {
        $results = Keluar::findOrFail($id);

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Karcis Keluar Satuan' , 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Times', '', 12);

        $jumlah = number_format($results->jml, 0, ',', '.');
        $total = 'Rp. '.number_format($results->total, 0, ',', '.');


        $pdf->Cell(0, 10, 'Nama Kolektor : '.$results->kolektor , 0, 1);
        $pdf->Cell(0, 10, 'Nama Penyerah : '.$results->users_id , 0, 1);
        $pdf->Cell(0, 10, 'Tanggal Ambil : '.$results->tgl_ambil , 0, 1);
        $pdf->Cell(0, 10, 'Jenis : '.$results->jenis->nama , 0, 1);
        $pdf->Cell(0, 10, 'Area : '.$results->area->nama , 0, 1);
        $pdf->Cell(0, 10, 'Jumlah : '.$jumlah. ' Lembar' , 0, 1);     
        $pdf->Cell(0, 10, 'Total : '.$total.',-' , 0, 1);     
        $pdf->Cell(0, 10, 'Nomor : '.$results->nomor , 0, 1);

        $pdf->Ln(15);

        $pdf->SetFont('Times', '', 10);



        $pdf->Cell(0, 10, 'https://sande.id', 0, 1, 'C');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf->Output('I', 'karcis_keluar.pdf');
        exit;
    }

    //Karcis Keluar perKolektor

    public function cetak_klt_global()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'Laporan Karcis Keluar');
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Nama Kolektor');
        $sheet->setCellValue('C2', 'Tgl Pengambilan');
        $sheet->setCellValue('D2', 'Jenis Karcis');
        $sheet->setCellValue('E2', 'Nominal Karcis');
        $sheet->setCellValue('F2', 'Quantity');
        $sheet->setCellValue('G2', 'Satuan');
        $sheet->setCellValue('H2', 'Total Nominal');
        $sheet->setCellValue('I2', 'Area/Wilayah');
        $sheet->setCellValue('J2', 'Yang Menyerahkan');

        foreach (range('A1', 'J1') as $paragraph) {
            $sheet->getColumnDimension($paragraph)->setAutoSize(true);
        }

        $cell = 3;
        foreach ($this->getData2() as $key => $row) {
            $sheet->setCellValue('A' . $cell, $key + 1);
            $sheet->setCellValue('B' . $cell, $row->Nama);
            $sheet->setCellValue('C' . $cell, $row->Tanggal);
            $sheet->setCellValue('D' . $cell, $row->Jenis);
            $sheet->setCellValue('E' . $cell, $row->Nominal);
            $sheet->setCellValue('F' . $cell, $row->Quantity);
            $sheet->setCellValue('G' . $cell, $row->Satuan);
            $sheet->setCellValue('H' . $cell, $row->Total);
            $sheet->setCellValue('I' . $cell, $row->Wilayah);
            $sheet->setCellValue('J' . $cell, $row->Penyerah);
            $cell++;
            $sheet->getStyle('A1:J' . ($cell - 1))->applyFromArray($this->setStyle());
            $sheet->getStyle('A1:J2')->applyFromArray($this->setWarna());
        }

        $this->outputTheExcel($spreadsheet, self::FILE_NAME);

    }

    public function cetak_klt_global_pdf()
    {
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Karcis Keluar', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Times', '', 8);

        $pdf->SetFillColor(216, 228, 188);
        $pdf->Cell(8, 6, 'No', 1, 0, 'C', true);

        $dtHeader = ['Nama Kolektor', 'Tgl Pengambilan', 'Jenis Karcis', 'Nominal Karcis', 'Quantity', 'Satuan', 'Total Nominal', 'Area/Wilayah', 'Yang Menyerahkan'];

        $data = ['Nama', 'Tanggal', 'Jenis', 'Nominal', 'Quantity', 'Satuan', 'Total', 'Wilayah', 'Penyerah'];

        foreach ($dtHeader as $dt) {
            $pdf->Cell(30, 6, $dt, 1, 0, 'C', true);
        }
        $pdf->SetFont('Times', '', 7);


        $pdf->Ln();
        foreach ($this->getData2() as $key => $row) {
            $pdf->Cell(8, 6, $key + 1, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Nama, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Tanggal, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Jenis, 1, 0, 'C');
            $pdf->Cell(30, 6, 'Rp ' . number_format($row->Nominal, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Quantity, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Satuan, 1, 0, 'C');
            $pdf->Cell(30, 6, 'Rp ' . number_format($row->Total, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Wilayah, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Penyerah, 1, 0, 'C');
            $pdf->Ln();
        }


        $pdf->Ln(20);
        $pdf->SetFont('Times', '', 10);



        $pdf->Cell(0, 10, 'https://dlhkotacilegon.com', 0, 1, 'C');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf->Output('I', 'karcis_keluar.pdf');
        exit;
    }

    //Karcis Masuk

    public function cetak_msk_global()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'Laporan Karcis Masuk');
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Nama Penyetok');
        $sheet->setCellValue('C2', 'Tgl');
        $sheet->setCellValue('D2', 'Jenis Karcis');
        $sheet->setCellValue('E2', 'Nominal Karcis');
        $sheet->setCellValue('F2', 'Quantity');
        $sheet->setCellValue('G2', 'Satuan');
        $sheet->setCellValue('H2', 'Total Nominal');
        $sheet->setCellValue('I2', 'Penerima');

        foreach (range('A1', 'I1') as $paragraph) {
            $sheet->getColumnDimension($paragraph)->setAutoSize(true);
        }

        $cell = 3;
        foreach ($this->getData3() as $key => $row) {
            $sheet->setCellValue('A' . $cell, $key + 1);
            $sheet->setCellValue('B' . $cell, $row->Nama);
            $sheet->setCellValue('C' . $cell, $row->Tanggal);
            $sheet->setCellValue('D' . $cell, $row->Jenis);
            $sheet->setCellValue('E' . $cell, $row->Nominal);
            $sheet->setCellValue('F' . $cell, $row->Quantity);
            $sheet->setCellValue('G' . $cell, $row->Satuan);
            $sheet->setCellValue('H' . $cell, $row->Total);
            $sheet->setCellValue('I' . $cell, $row->Penerima);
            $cell++;
            $sheet->getStyle('A1:I' . ($cell - 1))->applyFromArray($this->setStyle());
            $sheet->getStyle('A1:I2')->applyFromArray($this->setWarna());
        }

        $this->outputTheExcel($spreadsheet, self::FILE_NAME);

    }

    public function cetak_msk_global_pdf()
    {
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Karcis Masuk', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Times', '', 8);

        $pdf->SetFillColor(216, 228, 188);
        $pdf->Cell(8, 6, 'No', 1, 0, 'C', true);

        $dtHeader = ['Nama Penyetok', 'Tgl', 'Jenis Karcis', 'Nominal Karcis', 'Quantity', 'Satuan', 'Total Nominal', 'Penerima'];

        foreach ($dtHeader as $dt) {
            $pdf->Cell(34, 6, $dt, 1, 0, 'C', true);
        }
        $pdf->SetFont('Times', '', 7);


        $pdf->Ln();
        foreach ($this->getData3() as $key => $row) {
            $pdf->Cell(8, 6, $key + 1, 1, 0, 'C');
            $pdf->Cell(34, 6, $row->Nama, 1, 0, 'C');
            $pdf->Cell(34, 6, $row->Tanggal, 1, 0, 'C');
            $pdf->Cell(34, 6, $row->Jenis, 1, 0, 'C');
            $pdf->Cell(34, 6, 'Rp ' . number_format($row->Nominal, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(34, 6, $row->Quantity, 1, 0, 'C');
            $pdf->Cell(34, 6, $row->Satuan, 1, 0, 'C');
            $pdf->Cell(34, 6, 'Rp ' . number_format($row->Total, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(34, 6, $row->Penerima, 1, 0, 'C');
            $pdf->Ln();
        }


        $pdf->Ln(20);
        $pdf->SetFont('Times', '', 10);



        $pdf->Cell(0, 10, 'https://dlhkotacilegon.com', 0, 1, 'C');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf->Output('I', 'karcis_masuk.pdf');
        exit;
    }

    //Karcis Keluar : Area & Jenis

    public function cetak_ft_ara_global(Request $request)
    {
        $jns = intval(request()->input('jenis_id'));
        $ara = intval(request()->input('area_id'));

        $data = $this->getData4($jns, $ara);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'Laporan Karcis Keluar PerArea');
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Area/Wilayah');
        $sheet->setCellValue('C2', 'Tgl Pengambilan');
        $sheet->setCellValue('D2', 'Nama Kolektor');
        $sheet->setCellValue('E2', 'Jenis Karcis');
        $sheet->setCellValue('F2', 'Nominal Karcis');
        $sheet->setCellValue('G2', 'Quantity');
        $sheet->setCellValue('H2', 'Satuan');
        $sheet->setCellValue('I2', 'Total Nominal');
        $sheet->setCellValue('J2', 'Yang Menyerahkan');

        foreach (range('A1', 'J1') as $paragraph) {
            $sheet->getColumnDimension($paragraph)->setAutoSize(true);
        }

        $cell = 3;
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $cell, $key + 1);
            $sheet->setCellValue('B' . $cell, $row->Area);
            $sheet->setCellValue('C' . $cell, $row->Tanggal);
            $sheet->setCellValue('D' . $cell, $row->Nama);
            $sheet->setCellValue('E' . $cell, $row->Jenis);
            $sheet->setCellValue('F' . $cell, $row->Nominal);
            $sheet->setCellValue('G' . $cell, $row->Quantity);
            $sheet->setCellValue('H' . $cell, $row->Satuan);
            $sheet->setCellValue('I' . $cell, $row->Total);
            $sheet->setCellValue('J' . $cell, $row->Penyerah);
            $cell++;
            $sheet->getStyle('A1:J' . ($cell - 1))->applyFromArray($this->setStyle());
            $sheet->getStyle('A1:J2')->applyFromArray($this->setWarna());
        }

        $this->outputTheExcel($spreadsheet, self::FILE_NAME);

    }

    public function cetak_ft_ara_global_pdf(Request $request)
    {
        $jns = intval(request()->input('jenis_id'));
        $ara = intval(request()->input('area_id'));

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Karcis Keluar PerArea', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Times', '', 8);

        $pdf->SetFillColor(216, 228, 188);
        $pdf->Cell(8, 6, 'No', 1, 0, 'C', true);

        $dtHeader = ['Area/Wilayah', 'Tgl Pengambilan', 'Nama Kolektor', 'Jenis Karcis', 'Nominal Karcis', 'Quantity', 'Satuan', 'Total Nominal', 'Yang Menyerahkan'];
        foreach ($dtHeader as $dt) {
            $pdf->Cell(30, 6, $dt, 1, 0, 'C', true);
        }
        $pdf->SetFont('Times', '', 7);


        $pdf->Ln();
        foreach ($this->getData4($jns, $ara) as $key => $row) {
            $pdf->Cell(8, 6, $key + 1, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Area, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Tanggal, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Nama, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Jenis, 1, 0, 'C');
            $pdf->Cell(30, 6, 'Rp ' . number_format($row->Nominal, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Quantity, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Satuan, 1, 0, 'C');
            $pdf->Cell(30, 6, 'Rp ' . number_format($row->Total, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(30, 6, $row->Penyerah, 1, 0, 'C');
            $pdf->Ln();
        }


        $pdf->Ln(20);
        $pdf->SetFont('Times', '', 10);



        $pdf->Cell(0, 10, 'https://dlhkotacilegon.com', 0, 1, 'C');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf->Output('I', 'karcis_keluar_wilayah.pdf');
        exit;
    }

    //Karcis Global : Bulan

    public function cetak_ft_bln_global(Request $request)
    {
        $jns = intval(request()->input('jenis_id'));
        $ara = intval(request()->input('bulan_id'));

        $data = $this->getData5($jns, $ara);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Laporan Karcis Global Perbulan');
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Karcis');
        $sheet->setCellValue('C2', 'Tanggal Penginputan');
        $sheet->setCellValue('D2', 'Jenis Karcis');
        $sheet->setCellValue('E2', 'Jumlah');
        $sheet->setCellValue('F2', 'Satuan');
        $sheet->setCellValue('G2', 'Total');
        $sheet->setCellValue('H2', 'Penanggung Jawab');

        foreach (range('A1', 'H1') as $paragraph) {
            $sheet->getColumnDimension($paragraph)->setAutoSize(true);
        }

        $cell = 3;
        foreach ($data as $key => $row) {
            $sheet->setCellValue('A' . $cell, $key + 1);
            $sheet->setCellValue('B' . $cell, $row->karcis);
            $sheet->setCellValue('C' . $cell, $row->tanggal);
            $sheet->setCellValue('D' . $cell, $row->jenis_karcis);
            $sheet->setCellValue('E' . $cell, $row->jumlah);
            $sheet->setCellValue('F' . $cell, $row->satuan);
            $sheet->setCellValue('G' . $cell, $row->total);
            $sheet->setCellValue('H' . $cell, $row->penanggung_jawab);
            $cell++;
            $sheet->getStyle('A1:H' . ($cell - 1))->applyFromArray($this->setStyle());
            $sheet->getStyle('A1:H2')->applyFromArray($this->setWarna());
        }

        $this->outputTheExcel($spreadsheet, self::FILE_NAME);

    }

    public function cetak_ft_bln_global_pdf(Request $request)
    {
        $jns = intval(request()->input('jenis_id'));
        $ara = intval(request()->input('bulan_id'));

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 12);

        $pdf->Cell(0, 10, 'Laporan Karcis Global Perbulan', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Times', '', 8);

        $pdf->SetFillColor(216, 228, 188);
        $pdf->Cell(8, 6, 'No', 1, 0, 'C', true);

        $dtHeader = ['Karcis', 'Tanggal', 'Jenis Karcis', 'Jumlah', 'Satuan', 'Total'];
        foreach ($dtHeader as $dt) {
            $pdf->Cell(38, 6, $dt, 1, 0, 'C', true);
        }

        $pdf->Cell(40, 6, 'Penanggung Jawab', 1, 0, 'C', true);

        $pdf->SetFont('Times', '', 7);


        $pdf->Ln();
        foreach ($this->getData5($jns, $ara) as $key => $row) {
            $pdf->Cell(8, 6, $key + 1, 1, 0, 'C');
            $pdf->Cell(38, 6, $row->karcis, 1, 0, 'C');
            $pdf->Cell(38, 6, $row->tanggal, 1, 0, 'C');
            $pdf->Cell(38, 6, $row->jenis_karcis, 1, 0, 'C');
            $pdf->Cell(38, 6, $row->jumlah, 1, 0, 'C');
            $pdf->Cell(38, 6, $row->satuan, 1, 0, 'C');
            $pdf->Cell(38, 6, 'Rp ' . number_format($row->total, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(40, 6, $row->penanggung_jawab, 1, 0, 'C');
            $pdf->Ln();
        }


        $pdf->Ln(20);
        $pdf->SetFont('Times', '', 10);



        $pdf->Cell(0, 10, 'https://dlhkotacilegon.com', 0, 1, 'C');


        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf->Output('I', 'karcis_keluar_bulanan.pdf');
        exit;
    }

}