<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/app.css'])
        <title>ユーザー新規登録画面</title>
    </head>
    <body>
        <div class="container register">ユーザー新規登録画面</div>

            @if ($errors->any())
                <div class="alert">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                </div>
            @endif

            <form action="{{ route('postRegister') }}" method="post">
            @csrf
                <div class="register form-group">
                     <input type="text" name="name" placeholder="ユーザー名" />
                    <input type="text" name="email" placeholder="メールアドレス" />
                    <input type="password" name="password" placeholder="パスワード" />
                    <input type="password" name="password_confirmation" placeholder="パスワード再確認" />
                </div>

                <div class="register form-group button">
                    <button type="submit" class="register-btn register">新規登録</button>
                    <button type="button" onClick="history.back()" class="back-btn register">戻る</button>
                </div>
            </form>
        </div>  
    </body>
</html>