@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <h1>Masuk</h1>
    <div class="card">
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <label><input type="checkbox" name="remember" value="1"> Ingat saya</label>

            <p><button type="submit">Masuk</button></p>
        </form>
        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
    </div>
@endsection