<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Event dan Tiket')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f5f7; color: #222; }
        nav { background: #1f3a5f; padding: 10px 20px; display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }
        nav a, nav span, nav button { color: #fff; text-decoration: none; font-size: 14px; }
        nav button { background: #c0392b; border: 0; padding: 6px 10px; border-radius: 4px; cursor: pointer; }
        nav .brand { font-weight: bold; font-size: 16px; }
        nav .spacer { flex: 1; }
        nav form { margin: 0; }
        main { max-width: 900px; margin: 20px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 6px; padding: 14px 16px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .alert { padding: 10px 14px; border-radius: 4px; margin-bottom: 12px; }
        .alert.ok { background: #d4edda; color: #155724; }
        .alert.err { background: #f8d7da; color: #721c24; }
        .alert ul { margin: 0; padding-left: 18px; }
        .badge { display: inline-block; background: #e1e8f0; border-radius: 10px; padding: 2px 8px; font-size: 12px; }
        label { display: block; margin: 10px 0 4px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #bbb; border-radius: 4px; }
        input[type=checkbox] { width: auto; }
        button, .btn { background: #1f3a5f; color: #fff; border: 0; padding: 8px 14px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        .btn.danger { background: #c0392b; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 14px; }
        form.inline { display: inline; }
    </style>
</head>
<body>
<nav>
    <a class="brand" href="{{ url('/events') }}">Event dan Tiket</a>
    <a href="{{ url('/events') }}">Event</a>
    @auth
        @if (Route::has('ticket.mine'))
            <a href="{{ route('ticket.mine') }}">Tiket Saya</a>
        @endif
        @if (Route::has('registration.mine'))
            <a href="{{ route('registration.mine') }}">Pendaftaran Saya</a>
        @endif
        @if (auth()->user()->isAdmin())
            @if (Route::has('event.create'))
                <a href="{{ route('event.create') }}">Buat Event</a>
            @endif
        @endif
        <span class="spacer"></span>
        <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    @else
        <span class="spacer"></span>
        <a href="{{ route('login') }}">Masuk</a>
        <a href="{{ route('register') }}">Daftar</a>
    @endauth
</nav>
<main>
    @if (session('status'))
        <div class="alert ok">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert err">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert err">
            <ul>
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</main>
</body>
</html>