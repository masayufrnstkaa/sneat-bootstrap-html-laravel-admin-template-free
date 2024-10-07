<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\GratifikasiOnlineCtrl;
use App\Http\Controllers\ElearningController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\MriController;
use App\Http\Controllers\KEBController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [App\Http\Controllers\CustomAuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('custom-login', [App\Http\Controllers\CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('registration', [App\Http\Controllers\CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [App\Http\Controllers\CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [App\Http\Controllers\CustomAuthController::class, 'signOut'])->name('signout');

//untuk ganti password
Route::get('/gantiPassword', [HomeController::class, 'gantiPassword'])->name('auth');
Route::post('/gantiPassword/proses', [HomeController::class, 'gantiPasswordProses'])->name('auth');

//last seen
Route::get('/users', [UserController::class, 'index'])->name('manajemen')->middleware('auth');

// Route::get('/', [HomeController::class, 'index'])->name('/index');
Route::redirect('/', '/dashboard')->middleware('auth');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/monitoringLaporan', [HomeController::class, 'monitoringLaporan'])->name('monitoringLaporan')->middleware('auth');

//untukManajemenSekuriti
Route::get('/manajemenSekuriti', [HomeController::class, 'manajemenSekuriti'])->name('sekuriti')->middleware('auth');
Route::post('/manajemenSekuritiProses', [HomeController::class, 'manajemenSekuritiProses'])->name('sekuriti')->middleware('auth');

//untuk manajemen menu
Route::get('/manajemenMenu', [HomeController::class, 'manajemenMenu'])->name('manajemen')->middleware('auth');
Route::get('/manajemenMenu/tambah', [HomeController::class, 'manajemenMenuTambah'])->name('manajemen')->middleware('auth');
Route::post('/manajemenMenu/tambah/proses', [HomeController::class, 'manajemenMenuTambahProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenMenu/edit/{id}', [HomeController::class, 'manajemenMenuEdit'])->name('manajemen')->middleware('auth');
Route::post('/manajemenMenu/edit/proses', [HomeController::class, 'manajemenMenuEditProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenMenu/hapus/{id}', [HomeController::class, 'manajemenMenuHapus'])->name('manajemen')->middleware('auth');

//untuk manajemen submenu
Route::get('/manajemenSubmenu', [HomeController::class, 'manajemenSubmenu'])->name('manajemen')->middleware('auth');
Route::get('/manajemenSubmenu/tambah', [HomeController::class, 'manajemenSubmenuTambah'])->name('manajemen')->middleware('auth');
Route::post('/manajemenSubmenu/tambah/proses', [HomeController::class, 'manajemenSubmenuTambahProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenSubmenu/edit/{id}', [HomeController::class, 'manajemenSubmenuEdit'])->name('manajemen')->middleware('auth');
Route::post('/manajemenSubmenu/edit/proses', [HomeController::class, 'manajemenSubmenuEditProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenSubmenu/hapus/{id}', [HomeController::class, 'manajemenSubmenuHapus'])->name('manajemen')->middleware('auth');

//untuk manajemen role
Route::get('/manajemenRole', [HomeController::class, 'manajemenRole'])->name('manajemen')->middleware('auth');
Route::get('/manajemenRole/tambah', [HomeController::class, 'manajemenRoleTambah'])->name('manajemen')->middleware('auth');
Route::post('/manajemenRole/tambah/proses', [HomeController::class, 'manajemenRoleTambahProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenRole/edit/{id}', [HomeController::class, 'manajemenRoleEdit'])->name('manajemen')->middleware('auth');
Route::post('/manajemenRole/edit/proses', [HomeController::class, 'manajemenRoleEditProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenRole/hapus/{id}', [HomeController::class, 'manajemenRoleHapus'])->name('manajemen')->middleware('auth');

//untuk manajemen user
Route::get('/manajemenUser', [HomeController::class, 'manajemenUser'])->name('manajemen')->middleware('auth');
Route::get('/manajemenUser/tambah', [HomeController::class, 'manajemenUserTambah'])->name('manajemen')->middleware('auth');
Route::post('/manajemenUser/tambah/proses', [HomeController::class, 'manajemenUserTambahProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenUser/edit/{id}', [HomeController::class, 'manajemenUserEdit'])->name('manajemen')->middleware('auth');
Route::post('/manajemenUser/edit/proses', [HomeController::class, 'manajemenUserEditProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenUser/hapus/{id}', [HomeController::class, 'manajemenUserHapus'])->name('manajemen')->middleware('auth');
Route::get('/manajemenUser/bukaBlokir/{id}', [HomeController::class, 'manajemenUserBukaBlokir'])->name('manajemen');
Route::get('/manajemenUser/bukaIPNyangkut/{id}', [HomeController::class, 'manajemenUserBukaIPNyangkut'])->name('manajemen');

//untuk log activity
Route::get('/manajemenUserActivity', [HomeController::class, 'manajemenUserLog'])->name('manajemen')->middleware('auth');

//master unit kerja
Route::get('/manajemenUnker', [HomeController::class, 'manajemenUnker'])->name('manajemen')->middleware('auth');
Route::get('/manajemenUnker/tambah', [HomeController::class, 'manajemenUnkerTambah'])->name('manajemen')->middleware('auth');
Route::post('/manajemenUnker/tambah/proses', [HomeController::class, 'manajemenUnkerTambahProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenUnker/edit/{id}', [HomeController::class, 'manajemenUnkerEdit'])->name('manajemen')->middleware('auth');
Route::post('/manajemenUnker/edit/proses', [HomeController::class, 'manajemenUnkerEditProses'])->name('manajemen')->middleware('auth');
Route::get('/manajemenUnker/hapus/{id}', [HomeController::class, 'manajemenUnkerHapus'])->name('manajemen')->middleware('auth');
