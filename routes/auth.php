<?php

use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {

  // route untuk menampilkan halaman login
  Route::get('/auth/login', [LoginBasic::class, 'index'])->name('login');

  // route untuk menerima data login
  Route::post('/auth/login', [LoginBasic::class, 'store'])->name('login.store');



  Route::get('/auth/register', [RegisterBasic::class, 'index'])->name('register');
  Route::get('/auth/forgot-password', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
});
