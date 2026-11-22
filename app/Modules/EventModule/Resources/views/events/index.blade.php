@extends('layouts.app')

@section('title', 'Daftar Event')

@section('content')
    <h1>Daftar Event</h1>
    @forelse ($events as $event)
        <div class="card">
            <h3>
                <a href="{{ route('event.show', $event) }}">{{ $event->judul }}</a>
                @if ($event->status !== 'published')
                    <span class="badge">{{ $event->status }}</span>
                @endif
            </h3>
            <p>{{ $event->lokasi }} | {{ $event->mulai_at->format('d M Y H:i') }}</p>
            <p>
                @foreach ($event->categories as $kategori)
                    <span class="badge">{{ $kategori->nama }}</span>
                @endforeach
            </p>
            <p>Peserta terdaftar: {{ $event->peserta_terdaftar }}</p>
        </div>
    @empty
        <p>Belum ada event.</p>
    @endforelse
@endsection