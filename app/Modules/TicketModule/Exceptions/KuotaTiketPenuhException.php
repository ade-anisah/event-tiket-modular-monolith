<?php

namespace App\Modules\TicketModule\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class KuotaTiketPenuhException extends RuntimeException
{
    public static function untuk(string $namaTiket, int $sisa, int $diminta): self
    {
        return new self("Kuota tiket \"{$namaTiket}\" tidak mencukupi. Sisa {$sisa}, diminta {$diminta}.");
    }

    /**
     * Tampilkan pesan di halaman sebelumnya (atau JSON bila request meminta JSON).
     */
    public function render(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $this->getMessage()], 409);
        }

        return back()->withInput()->with('error', $this->getMessage());
    }
}
