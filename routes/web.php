<?php


use App\Http\Controllers\KaryawanController;
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
    Route::get('/karyawan', [KaryawanController::class,'index'])->name('karyawan');
    Route::get('/karyawan/create', [KaryawanController::class,'create']);
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('tambah_karyawan');
    


});
