<?php

namespace App\Modules\EventModule\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class EventTidakDibukaException extends RuntimeException
{
    public static function untuk(string $judul): self
    {
        return new self("Event \"{$judul}\" belum dibuka untuk penjualan tiket.");
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
