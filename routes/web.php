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

    Route::get('/daftar-tugas', [AdminSdmController::class, 'daftar_tugas'])->name('admin_sdm.daftar_tugas');

    
    Route::get('/admin-sdm/upload-data', [AdminSdmController::class, 'view_upload'])->name('admin_sdm.upload');
    Route::post('/admin-sdm/generate-akun', [AdminSdmController::class, 'generateAccounts'])->name('admin_sdm.generateAccounts');
    Route::post('/admin-sdm/pegawai-impor', [AdminSdmController::class, 'import'])->name('admin_sdm.impor');
    Route::get('/admin-sdm/form_akun', [AdminSdmController::class, 'form_akun'])->name('admin_sdm.index');
    Route::post('admin-sdm/form_akun/store', [AdminSdmController::class, 'store_form'])->name('admin_sdm.store');
    Route::get('admin-sdm/users/{nip}/edit', [AdminSdmController::class, 'edit'])->name('admin_sdm.users.edit');
    Route::put('admin-sdm/users/{nip}', [AdminSdmController::class, 'update'])->name('admin_sdm.update');
    Route::delete('admin-sdm/users/{nip}', [AdminSdmController::class, 'destroy'])->name('admin_sdm.users.destroy');
    Route::get('admin-sdm/akun/export', [AdminSdmController::class, 'exportExcel'])->name('admin_sdm.akun.export');

});

Route::middleware(['auth', 'role:admin_user'])->group(function () {
    Route::get('/admin-user/dashboard', [AdminUserController::class, 'index'])->name('admin_user.dashboard');
    Route::get('/admin-user/upload-data', [AdminUserController::class, 'view_upload'])->name('admin_user.upload');
    Route::post('/admin-user/generate-akun', [AdminUserController::class, 'generateAccounts'])->name('admin_user.generateAccounts');
    Route::post('/admin-user/pegawai-impor', [AdminUserController::class, 'import'])->name('admin_user.impor');
    Route::get('/form_akun', [AdminUserController::class, 'form_akun'])->name('admin_user.index');
    Route::post('/form_akun/store', [AdminUserController::class, 'store'])->name('admin_user.store');
    Route::get('/users/{nip}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{nip}', [AdminUserController::class, 'update'])->name('admin_user.update');
    Route::delete('/users/{nip}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/akun/export', [AdminUserController::class, 'exportExcel'])->name('akun.export');
    Route::get('/daftar_tugas', [AdminUserController::class, 'index_tugas'])->name('admin_user.tugas');
    Route::get('/form_riwayatTugas', [AdminUserController::class, 'form_tugas'])->name('admin_user.riwayatTugas');
    Route::post('/form_riwayatTugas/store', [AdminUserController::class, 'tambahTugas'])->name('admin_user.storeRiwayatTugas');
    Route::get('/tugas/{id_tugas}/edit', [AdminUserController::class, 'editTugas'])->name('admin_user.editTugas');
    Route::put('/tugas/{id_tugas}', [AdminUserController::class, 'updateTugas'])->name('admin_user.updateTugas');
    Route::delete('/tugas/{id_tugas}', [AdminUserController::class, 'destroyTugas'])->name('admin_user.destroyTugas');
});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/pegawai/dashboard', function () {
        return view('dashboard.pegawai');
    })->name('pegawai.dashboard');
});

Route::middleware(['auth', 'role:pemutus'])->group(function () {
    Route::get('/pemutus/dashboard', function () {
        return view('dashboard.pemutus');
    })->name('pemutus.dashboard');
});