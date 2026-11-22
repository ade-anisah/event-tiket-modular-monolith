@extends('layouts.app')

@section('title', 'Tiket Saya')

@section('content')
    <h1>Tiket Saya</h1>
    <table>
        <thead>
            <tr><th>Kode tiket</th><th>Event</th><th>Jenis</th><th>Harga</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse ($tickets as $tiket)
                <tr>
                    <td><strong>{{ $tiket->kode }}</strong></td>
                    <td>{{ $judulEvent[$tiket->ticketType->event_id] ?? '-' }}</td>
                    <td>{{ $tiket->ticketType->nama }}</td>
                    <td>Rp {{ number_format($tiket->harga_snapshot, 0, ',', '.') }}</td>
                    <td>{{ $tiket->status }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Anda belum memiliki tiket.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection