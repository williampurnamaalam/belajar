<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AreakerjaController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DanaController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LoginContoller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginContoller::class, 'showLogin'])->name('login');
Route::post('/login', [LoginContoller::class, 'login']);
Route::post('/logout', [LoginContoller::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    // 1. DASHBOARD & PROFILE (Bisa diakses semua role yang login)
    Route::get('/dashboard', [LoginContoller::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [KaryawanController::class, 'showprofile'])->name('profile');
    Route::get('/profile/edit', [KaryawanController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [KaryawanController::class, 'updateProfile'])->name('profile.update');

    // 2. ABSENSI 
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::put('/absensi/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    // 3. Cuti
    Route::get('/persetujuan/cuti', [CutiController::class, 'index'])->name('cuti.index');
    Route::post('/persetujuan/cuti', [CutiController::class, 'store'])->name('cuti.store');
    // 4. Lembur
    Route::get('/pengajuan-lembur', [LemburController::class, 'index'])->name('lembur.index');
    Route::post('/pengajuan-lembur', [LemburController::class, 'store'])->name('lembur.store');


    // --------------------------------------------------------------------
    // 3. KHUSUS ADMIN, HRD, & MANAGER (Master Data & Approval)
    // --------------------------------------------------------------------
    Route::middleware(['checkrole:admin,hrd,manager'])->group(function () {
        
        // Management Karyawan
        Route::resource('karyawan', KaryawanController::class);
        Route::get('/karyawan',[ KaryawanController::class,'index'])->name('karyawan');
        Route::post('/karyawan/tambah', [KaryawanController::class, 'store'])->name('tambah_karyawan');

        

        // Jabatan & Divisi
        Route::resource('jabatan', JabatanController::class);
        Route::get('/jabatan',[ JabatanController::class,'index'])->name('jabatan');
        Route::resource('divisi', DivisiController::class);
        Route::get('/divisi',[ DivisiController::class,'index'])->name('divisi');
        
        // Role Management
        Route::resource('role', RoleController::class);
        Route::get('/role',[ RoleController::class,'index'])->name('role');
        
        // Area Kerja
        Route::resource('areakerja', AreakerjaController::class);
        Route::get('/areakerja',[ AreakerjaController::class,'index'])->name('areakerja');
        Route::get('/areakerja/{id}/insert', [AreakerjaController::class, 'insert'])->name('areakerja.insert');
        Route::post('/areakerja/{id}/store-team', [AreakerjaController::class, 'storeTeam'])->name('areakerja.storeTeam');
        Route::put('/areakerja/{id}/update-ip', [AreakerjaController::class, 'updateIp'])->name('areakerja.update_ip');

        // Approval Cuti 
        Route::get('/persetujuan/admin/cuti', [CutiController::class, 'adminIndex'])->name('cuti.admin');
        Route::put('/persetujuan/proses/{id}', [CutiController::class, 'approveReject'])->name('cuti.proses');

        // Approval Cuti 
        Route::get('/persetujuan/admin/dana', [DanaController::class, 'adminIndex'])->name('dana.admin');

        // approval lembur
        Route::get('/admin/persetujuan-lembur', [LemburController::class, 'adminIndex'])->name('admin.lembur.index');
        Route::put('/admin/persetujuan-lembur/{id}', [LemburController::class, 'approveReject'])->name('admin.lembur.approve');

        // data presensi
        Route::prefix('presensi')->group(function () {
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::get('/area/{area_id}', [PresensiController::class, 'daftarKaryawan'])->name('presensi.karyawan');
        Route::get('/detail/{user_id}', [PresensiController::class, 'detailKaryawan'])->name('presensi.detail');
        });

    });

});
