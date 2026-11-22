@extends('layouts.app')

@section('title', 'Pendaftaran Saya')

@section('content')
    <h1>Pendaftaran Saya</h1>
    <table>
        <thead>
            <tr><th>Kode</th><th>Event</th><th>Jenis tiket</th><th>Jumlah</th><th>Total</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse ($rows as $baris)
                <tr>
                    <td>{{ $baris['registrasi']->kode_pendaftaran }}</td>
                    <td>{{ $baris['event'] }}</td>
                    <td>{{ $baris['tiket'] }}</td>
                    <td>{{ $baris['registrasi']->jumlah }}</td>
                    <td>Rp {{ number_format($baris['registrasi']->total, 0, ',', '.') }}</td>
                    <td>{{ $baris['registrasi']->status }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Anda belum memiliki pendaftaran.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection