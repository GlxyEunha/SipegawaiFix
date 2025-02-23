<?php

use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RollingController;
use App\Http\Controllers\AdminSdmController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::middleware(['auth', 'role:admin_sdm'])->group(function () {
    Route::get('/admin-sdm/dashboard', [AdminSdmController::class, 'index'])->name('admin_sdm.dashboard');
    Route::get('/admin-sdm/chart', [AdminSdmController::class, 'chart'])->name('admin_sdm.chart');
    Route::get('/admin/rolling', [AdminSdmController::class, 'rolling'])->name('admin_sdm.rolling');
    Route::get('/admin/pegawai/{nip}/history', [AdminSdmController::class, 'history'])->name('pegawai.history');
    Route::get('/admin/pegawai/{nip}/rolling', [AdminSdmController::class, 'edit'])->name('pegawai.rolling.form'); // Menampilkan form rolling
    //Route::post('/admin/pegawai/{nip}/rolling', [AdminSdmController::class, 'update'])->name('pegawai.rolling'); // Simpan perubahan rolling unit
    
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    
    Route::get('/data_pegawai', [AdminSdmController::class, 'index'])->name('datapegawai.index');

    Route::get('/rolling', [RollingController::class, 'index'])->name('rolling.index');
    Route::post('/rolling/store', [RollingController::class, 'store'])->name('rolling.store');
    Route::get('/hasil', [RollingController::class, 'hasil'])->name('rolling.hasil');
    Route::post('/hasil/accept/{nip}', [RollingController::class, 'accept'])->name('rolling.accept');
    Route::get('/hasil/export', [RollingController::class, 'exportExcel'])->name('rolling.export');

    Route::get('/gaji', [GajiController::class, 'index'])->name('gaji.index');
    Route::post('/gaji/accept/{nip}', [GajiController::class, 'setujui'])->name('gaji.setujui');

    Route::get('admin_sdm/daftar-tugas', [AdminSdmController::class, 'daftar_tugas'])->name('admin_sdm.daftar_tugas');
    Route::get('admin_sdm/form_riwayatTugas', [AdminSdmController::class, 'form_tugas'])->name('admin_sdm.riwayatTugas');
    Route::post('admin_sdm/form_riwayatTugas/store', [AdminSdmController::class, 'tambahTugas'])->name('admin_sdm.storeRiwayatTugas');
    Route::get('admin_sdm/tugas/{id_tugas}/edit', [AdminSdmController::class, 'editTugas'])->name('admin_sdm.editTugas');
    Route::put('admin_sdm/tugas/{id_tugas}', [AdminSdmController::class, 'updateTugas'])->name('admin_sdm.updateTugas');
    Route::delete('admin_sdm/tugas/{id_tugas}', [AdminSdmController::class, 'destroyTugas'])->name('admin_sdm.destroyTugas');
    
    Route::get('/admin-sdm/upload-data', [AdminSdmController::class, 'view_upload'])->name('admin_sdm.upload');
    Route::post('/admin-sdm/generate-akun', [AdminSdmController::class, 'generateAccounts'])->name('admin_sdm.generateAccounts');
    Route::post('/admin-sdm/pegawai-impor', [AdminSdmController::class, 'import'])->name('admin_sdm.impor');
    Route::get('/admin-sdm/form_akun', [AdminSdmController::class, 'form_akun'])->name('admin_sdm.index');
    Route::post('admin-sdm/form_akun/store', [AdminSdmController::class, 'store_form'])->name('admin_sdm.store');
    Route::get('admin-sdm/users/{nip}/edit', [AdminSdmController::class, 'edit'])->name('admin_sdm.users.edit');
    Route::put('admin-sdm/users/{nip}', [AdminSdmController::class, 'update'])->name('admin_sdm.update');
    Route::delete('admin-sdm/users/{nip}', [AdminSdmController::class, 'destroy'])->name('admin_sdm.users.destroy');
    Route::get('admin-sdm/akun/export', [AdminSdmController::class, 'exportExcel'])->name('admin_sdm.akun.export');

    Route::get('admin-sdm/rolling/pegawai', [AdminSdmController::class, 'search_filter_Rolling'])->name('admin_sdm.rolling.search_filter');
    Route::get('admin-sdm/hasil-rolling/pegawai', [AdminSdmController::class, 'search_filter_hasilRolling'])->name('admin_sdm.hasilRolling.search_filter');

    Route::get('admin-sdm/atur_menu', [AdminSdmController::class, 'atur_menu'])->name('admin_sdm.atur_menu');
    Route::get('admin-sdm/atur_menu/{nip}/edit', [AdminSdmController::class, 'editMenu'])->name('admin_sdm.atur_menu.edit');
    Route::put('admin-sdm/atur_menu/{nip}', [AdminSdmController::class, 'updateMenu'])->name('admin_sdm.atur_menu.update');

});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    #Dashboard
    Route::get('/pegawai/dashboard', [PegawaiController::class, 'chart'])->name('pegawai.dashboard');

    #Daftar Pegawai
    Route::get('/pegawai/daftar_pegawai', [PegawaiController::class, 'index'])->name('pegawai.daftar_pegawai');
    Route::get('/pegawai/form', [PegawaiController::class, 'form_akun'])->name('pegawai.index');
    Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{nip}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{nip}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{nip}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    # Export Akun
    Route::get('/akun/export', [PegawaiController::class, 'exportExcel'])->name('akun.export');

    # Impor-Generate Akun
    Route::get('/pegawai/upload-data', [PegawaiController::class, 'view_upload'])->name('pegawai.upload');
    Route::post('/pegawai/generate-akun', [PegawaiController::class, 'generateAccounts'])->name('pegawai.generateAccounts');
    Route::post('/pegawai/pegawai-impor', [PegawaiController::class, 'import'])->name('pegawai.impor');


    Route::get('/daftar_tugas', [AdminUserController::class, 'index_tugas'])->name('admin_user.tugas');
    Route::get('/form_riwayatTugas', [AdminUserController::class, 'form_tugas'])->name('admin_user.riwayatTugas');
    Route::post('/form_riwayatTugas/store', [AdminUserController::class, 'tambahTugas'])->name('admin_user.storeRiwayatTugas');
    Route::get('/tugas/{id_tugas}/edit', [AdminUserController::class, 'editTugas'])->name('admin_user.editTugas');
    Route::put('/tugas/{id_tugas}', [AdminUserController::class, 'updateTugas'])->name('admin_user.updateTugas');
    Route::delete('/tugas/{id_tugas}', [AdminUserController::class, 'destroyTugas'])->name('admin_user.destroyTugas');
});

// Route::middleware(['auth', 'role:pegawai'])->group(function () {
//     Route::get('/pegawai/dashboard', function () {
//         return view('dashboard.pegawai');
//     })->name('pegawai.dashboard');
// });

// Route::middleware(['auth', 'role:pemutus'])->group(function () {
//     Route::get('/pemutus/dashboard', function () {
//         return view('dashboard.pemutus');
//     })->name('pemutus.dashboard');
// });