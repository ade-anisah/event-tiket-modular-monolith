@extends('layouts.app')

@section('title', $event->exists ? 'Ubah Event' : 'Buat Event')

@section('content')
    <h1>{{ $event->exists ? 'Ubah Event' : 'Buat Event' }}</h1>
    <div class="card">
        <form method="POST" action="{{ $event->exists ? route('event.update', $event) : route('event.store') }}">
            @csrf
            @if ($event->exists)
                @method('PUT')
            @endif

            <label for="judul">Judul</label>
            <input id="judul" type="text" name="judul" value="{{ old('judul', $event->judul) }}" required>

            <label for="lokasi">Lokasi</label>
            <input id="lokasi" type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" required>

            <label for="mulai_at">Waktu mulai</label>
            <input id="mulai_at" type="datetime-local" name="mulai_at"
                   value="{{ old('mulai_at', optional($event->mulai_at)->format('Y-m-d\TH:i')) }}" required>

            <label for="selesai_at">Waktu selesai</label>
            <input id="selesai_at" type="datetime-local" name="selesai_at"
                   value="{{ old('selesai_at', optional($event->selesai_at)->format('Y-m-d\TH:i')) }}" required>

            <label for="status">Status</label>
            <select id="status" name="status">
                @foreach (['draft', 'published', 'closed'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $event->status) === $status)>{{ $status }}</option>
                @endforeach
            </select>

            <label>Kategori</label>
            @foreach ($categories as $kategori)
                <label style="font-weight: normal">
                    <input type="checkbox" name="category_ids[]" value="{{ $kategori->id }}"
                           @checked(in_array($kategori->id, old('category_ids', $selected)))>
                    {{ $kategori->nama }}
                </label>
            @endforeach

            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $event->deskripsi) }}</textarea>

            <p><button type="submit">Simpan</button></p>
        </form>
    </div>
@endsection