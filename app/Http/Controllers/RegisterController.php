<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller {
    public function showRegister() {
        return view('register');
    }

    public function postRegister(Request $request) {

        $request->validate([
            'name' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ], [
            'name.required' => 'ユーザー名は必須です。',
            'email.required' => 'メールアドレスは必須です。',
            'password.required' => 'パスワードは必須です。',
            'password.confirmed' => 'パスワードが一致しません。',
        ]);

        User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $request->session()->flash('message', 'ユーザーを登録しました');
        return redirect('/login');
    }
}