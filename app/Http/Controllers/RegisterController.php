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
            'name' => ['required','string','unique:users', 'regex:/^[\p{L}\p{N}]+$/u'],
            'email' => ['required','string','email','unique:users','regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => ['required','string','confirmed','regex:/^[a-zA-Z0-9]+$/'],
        ], [
            'name.required' => 'ユーザー名は必須です。',
            'email.required' => 'メールアドレスは必須です。',
            'password.required' => 'パスワードは必須です。',
            'password.confirmed' => 'パスワードが一致しません。',
            'name.regex' => 'ユーザー名は半角英数字・全角英数字のみを使用してください。',
            'email.regex' => 'メールアドレスは半角英数字のみを使用してください。',
            'password.regex' => 'パスワードは半角英数字のみを使用してください。',
        ]);

        User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $request->session()->flash('message', 'ユーザーを登録しました');
        return redirect('/login');
    }
}