<?php

namespace App\Modules\RegistrationModule\Http\Requests;

use App\Modules\TicketModule\Contracts\TicketInventoryContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PembelianTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_type_id' => ['required', 'integer'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_type_id.required' => 'Jenis tiket wajib dipilih.',
            'ticket_type_id.integer' => 'Jenis tiket tidak valid.',
            'jumlah.required' => 'Jumlah tiket wajib diisi.',
            'jumlah.integer' => 'Jumlah tiket harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah tiket minimal 1.',
        ];
    }

    /**
     * Validasi tambahan lewat Contract, tanpa menyentuh tabel milik TicketModule.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $tipe = app(TicketInventoryContract::class)->summary($this->integer('ticket_type_id'));

                if ($tipe === null) {
                    $validator->errors()->add('ticket_type_id', 'Jenis tiket tidak ditemukan.');

                    return;
                }

                if ($this->integer('jumlah') > $tipe->maksPerPembelian) {
                    $validator->errors()->add(
                        'jumlah',
                        "Maksimal {$tipe->maksPerPembelian} tiket per pembelian untuk jenis tiket ini.",
                    );
                }
            },
        ];
    }
}
