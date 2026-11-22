@extends('layouts.app')

@section('title', $event->judul)

@section('content')
    <h1>{{ $event->judul }}</h1>
    <div class="card">
        <p><strong>Lokasi:</strong> {{ $event->lokasi }}</p>
        <p><strong>Waktu:</strong> {{ $event->mulai_at->format('d M Y H:i') }} sampai {{ $event->selesai_at->format('d M Y H:i') }}</p>
        <p><strong>Status:</strong> <span class="badge">{{ $event->status }}</span></p>
        <p>
            @foreach ($event->categories as $kategori)
                <span class="badge">{{ $kategori->nama }}</span>
            @endforeach
        </p>
        <p>{{ $event->deskripsi }}</p>
        <p>Peserta terdaftar: {{ $event->peserta_terdaftar }}</p>

        @if (Route::has('ticket.catalog'))
            <p><a class="btn" href="{{ route('ticket.catalog', $event->id) }}">Lihat dan beli tiket</a></p>
        @endif

        @auth
            @if (auth()->user()->isAdmin())
                <p>
                    <a class="btn" href="{{ route('event.edit', $event) }}">Ubah event</a>
                </p>
                <form class="inline" method="POST" action="{{ route('event.destroy', $event) }}"
                      onsubmit="return confirm('Hapus event ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="danger" type="submit">Hapus event</button>
                </form>
            @endif
        @endauth
    </div>
@endsection