<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\RuterController;
use App\Http\Controllers\CetakLaporanController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [Dashboard::class, 'dashboard_view'])->name('Pengunjung');


//Auth
//Login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {

    //Dashboard
    Route::get('Admdashboard', [Dashboard::class, 'dashboard_admin'])->name('Admdashboard');
    Route::get('Admtrdashboard', [Dashboard::class, 'dashboard_admin'])->name('Admtrdashboard');
    Route::get('cetak-masuk/{id}', [CetakLaporanController::class, 'cetak_satuan'])->name('cetak-masuk');
    Route::get('cetak-keluar/{id}', [CetakLaporanController::class, 'cetak_keluar'])->name('cetak-keluar');



        //Kelola Karcis
        Route::prefix('karcis')->group(function () {
            Route::get('/masuk', [RuterController::class, 'datakarcisin'])->name('dataKarcisIn');
            Route::get('/keluar', [RuterController::class, 'datakarcisout'])->name('dataKarcisOut');
            Route::get('/stok', [RuterController::class, 'datakarcisstok'])->name('dataKarcisStok');
    
        });

            //Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/karcis', [RuterController::class, 'datalaporan'])->name('dataLaporan');
        Route::get('/cetak-global', [CetakLaporanController::class, 'cetak_bln_global'])->name('cetak.export1');
        Route::get('/cetak-global2', [CetakLaporanController::class, 'cetak_bln_global_pdf'])->name('cetak.export2');
        Route::get('/cetak-global3', [CetakLaporanController::class, 'cetak_klt_global'])->name('cetak.export3');
        Route::get('/cetak-global4', [CetakLaporanController::class, 'cetak_klt_global_pdf'])->name('cetak.export4');
        Route::get('/cetak-global5', [CetakLaporanController::class, 'cetak_msk_global'])->name('cetak.export5');
        Route::get('/cetak-global6', [CetakLaporanController::class, 'cetak_msk_global_pdf'])->name('cetak.export6');
        Route::post('/cetak-global7', [CetakLaporanController::class, 'cetak_ft_ara_global'])->name('cetak.export7');
        Route::post('/cetak-global8', [CetakLaporanController::class, 'cetak_ft_ara_global_pdf'])->name('cetak.export8');
        Route::post('/cetak-global9', [CetakLaporanController::class, 'cetak_ft_bln_global'])->name('cetak.export9');
        Route::post('/cetak-global0', [CetakLaporanController::class, 'cetak_ft_bln_global_pdf'])->name('cetak.export0');

    });

        //Edit Profil
        Route::prefix('profil')->group(function () {
            Route::get('/edit', [RuterController::class, 'dataprofil'])->name('dataProfil');
            Route::post('/update', [RuterController::class, 'editUser'])->name('editProfil');
    
    
        });

});

//Administrator
Route::middleware('isAdministrator')->group(function () {

    //Kelola Data
    Route::prefix('kelola')->group(function () {
        Route::get('/pengguna', [RuterController::class, 'datauser'])->name('dataUsers');
        Route::get('/kolektor', [RuterController::class, 'datakolektor'])->name('dataKolektor');
        Route::get('/jenis', [RuterController::class, 'datajeniskarcis'])->name('dataJenis');
        Route::get('/area', [RuterController::class, 'dataareakarcis'])->name('dataArea');

    });

});




