@extends('layouts.app')

@section('title', 'Tiket '.$event->judul)

@section('content')
    <h1>{{ $event->judul }}</h1>
    <p>{{ $event->lokasi }} | {{ $event->mulaiAt->format('d M Y H:i') }}</p>
    <h2>Pilih Tiket</h2>

    @forelse ($types as $type)
        <div class="card">
            <h3>
                {{ $type->nama }}
                <span class="badge">Rp {{ number_format($type->harga, 0, ',', '.') }}</span>
            </h3>
            <p>
                Sisa kuota: {{ $type->sisa() }} dari {{ $type->kuota }}
                | Maksimal {{ $type->maks_per_pembelian }} tiket per pembelian
            </p>

            @auth
                @if (Route::has('registration.purchase'))
                    <form method="POST" action="{{ route('registration.purchase') }}">
                        @csrf
                        <input type="hidden" name="ticket_type_id" value="{{ $type->id }}">
                        <label for="jumlah-{{ $type->id }}">Jumlah</label>
                        <input id="jumlah-{{ $type->id }}" type="number" name="jumlah" value="1" min="1">
                        <p><button type="submit">Beli Tiket</button></p>
                    </form>
                @endif
            @else
                <p><a href="{{ route('login') }}">Masuk</a> untuk membeli tiket.</p>
            @endauth
        </div>
    @empty
        <p>Belum ada jenis tiket untuk event ini.</p>
    @endforelse
@endsection