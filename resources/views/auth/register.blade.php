@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <h1>Daftar Akun Peserta</h1>
    <div class="card">
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>

            <label for="password">Password (minimal 8 karakter)</label>
            <input id="password" type="password" name="password" required>

            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>

            <p><button type="submit">Daftar</button></p>
        </form>
        <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </div>
@endsection