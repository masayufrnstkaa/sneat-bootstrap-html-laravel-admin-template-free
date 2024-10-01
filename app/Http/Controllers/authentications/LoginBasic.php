<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  // fungsi untuk mengembalikan view login
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }


  // fungsi untuk menerima username dan password untuk login
  public function store(LoginRequest $request)
  {

    $request->authenticate();


    $request->session()->regenerate();

    return redirect()->intended(route('dashboard-analytics', absolute: true));
  }

  // fungsi untuk logout
  public function destroy(Request $request)
  {


    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }
}
