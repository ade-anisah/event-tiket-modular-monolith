<?php

namespace App\Modules\RegistrationModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EventModule\Contracts\EventLookupContract;
use App\Modules\RegistrationModule\Http\Requests\PembelianTiketRequest;
use App\Modules\RegistrationModule\Models\Participant;
use App\Modules\RegistrationModule\Models\Registration;
use App\Modules\RegistrationModule\Services\RegistrationService;
use App\Modules\TicketModule\Contracts\TicketInventoryContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly EventLookupContract $events,
        private readonly TicketInventoryContract $tickets,
    ) {}

    public function store(PembelianTiketRequest $request, RegistrationService $service): RedirectResponse
    {
        $user = $request->user();

        $registration = $service->purchase(
            $user->id,
            $user->name,
            $request->integer('ticket_type_id'),
            $request->integer('jumlah'),
        );

        return redirect()->route('registration.mine')->with(
            'status',
            "Pembelian berhasil. Kode pendaftaran Anda: {$registration->kode_pendaftaran}."
        );
    }

    public function mine(Request $request): View
    {
        $peserta = Participant::where('user_id', $request->user()->id)->first();
        $daftar = $peserta ? $peserta->registrations()->latest('id')->get() : collect();

        return view('registration::registrations.mine', ['rows' => $this->susun($daftar)]);
    }

    /**
     * Lengkapi nama event dan nama jenis tiket lewat Contract (bukan lewat tabel modul lain).
     */
    private function susun(Collection $daftar): array
    {
        return $daftar->map(fn (Registration $registrasi) => [
            'registrasi' => $registrasi,
            'event' => $this->events->find($registrasi->event_id)?->judul ?? "Event #{$registrasi->event_id}",
            'tiket' => $this->tickets->summary($registrasi->ticket_type_id)?->nama ?? "Jenis tiket #{$registrasi->ticket_type_id}",
        ])->all();
    }
}