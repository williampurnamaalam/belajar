<?php


use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AreakerjaController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LoginContoller;
use Illuminate\Support\Facades\Route;

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
});

Route::get('/login', [LoginContoller::class, 'showLogin'])->name('login');
Route::post('/login', [LoginContoller::class, 'login']);
Route::post('/logout', [LoginContoller::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function (){

    //profile
    Route::get('/profile',[KaryawanController::class,'showprofile'])->name('profile');
    Route::get('/profile/edit',[KaryawanController::class,'editProfile'])->name('profile.edit');
    Route::put('/profile/update',[KaryawanController::class,'updateProfile'])->name('profile.update');


    //Dasboard
    Route::get('/dashboard', [LoginContoller::class,'dashboard'])->name('dashboard');

    //management karyawan
    Route::resource('karyawan', KaryawanController::class);
    Route::get('/karyawan', [KaryawanController::class,'index'])->name('karyawan');
    Route::get('/karyawan/create', [KaryawanController::class,'create']);
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('tambah_karyawan');
    Route::get('/karyawan/{id}', [KaryawanController::class, 'show'])->name('karyawan.show');

    //jabatan page
    Route::resource('jabatan', JabatanController::class);
    Route::get('/jabatan', [JabatanController::class,'index'])->name('jabatan');

    //divisi page
    Route::resource('divisi', DivisiController::class);
    Route::get('/divisi', [DivisiController::class,'index'])->name('divisi');
    
    //role page
    Route::resource('role', RoleController::class);
    Route::get('/role', [RoleController::class,'index'])->name('role');
    
    //Area Kerja
    Route::resource('/areakerja', AreakerjaController::class);
    Route::get('/areakerja',[AreakerjaController::class,'index'])->name('areakerja');
    Route::get('/areakerja/{id}/insert', [AreaKerjaController::class, 'insert'])->name('areakerja.insert');
    Route::post('/areakerja/{id}/store-team', [AreaKerjaController::class, 'storeTeam'])->name('areakerja.storeTeam');

    //Absensi 
    Route::get('/absensi',[AbsensiController::class,'index'])->name('absensi');
    
});
