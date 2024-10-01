<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{


  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'username' => ['required', 'string'],
      'password' => ['required', 'string'],
    ];
  }

  // fungsi untuk mengelola logic login
  public function authenticate()
  {
    if (!Auth::attempt($this->only('username', 'password'), $this->filled('remember'))) {
      throw ValidationException::withMessages([
        'username' => 'Username atau password salah',
      ]);
    }
  }
}
