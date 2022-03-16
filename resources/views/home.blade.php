<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ホーム</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <x-alert type="danger" :session="session('danger')"/>
        <x-alert type="success" :session="session('success')"/>
        <h3>プロフィール</h3>
        <ul>
            <li>名前：{{ Auth::user()->name }}</li>
            <li>メールアドレス：{{ Auth::user()->email }}</li>
        </ul>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <input class="btn btn-danger" type="submit" value="ログアウト">
        </form>
    </div>
</body>
</html>