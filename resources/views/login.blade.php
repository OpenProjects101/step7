<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/app.css'])
        <title>ユーザーログイン画面</title>
    </head>
    <body>
        @if (session('message'))
            <div class="alert">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
            </div>
        @endif

            <form action="{{ route('login.submit') }}" method="POST">
            @csrf
                <div class="container login">ユーザーログイン画面</div>
                    <div class="login form-group">
                        <input type="text" name="email" placeholder="メールアドレス" />
                        <input type="password" name="password" placeholder="パスワード" />
                    </div>
                    <div class="login form-group button">
                        <button type="button" onclick="window.location='{{ route('showRegister') }}'" class="login-btn register">新規登録</button>
                        <button type="submit" class="login-btn login">ログイン</button>
                    </div>
                </div>
            </form>
    </body>
</html>