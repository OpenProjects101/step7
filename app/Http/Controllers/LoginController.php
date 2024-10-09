<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('login');
    }

    public function login(
        Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email','regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],  
            'password' => ['required','regex:/^[a-zA-Z0-9]+$/'],
        ], [
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードは必須です。', 
            'email.regex' => 'メールアドレスは半角英数字のみを使用してください。',
            'password.regex' => 'パスワードは半角英数字のみを使用してください。',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('products');
        }
    
        return redirect() -> back()->withErrors([
            'email' => 'ログイン情報が間違っています',
        ]);
    }
}