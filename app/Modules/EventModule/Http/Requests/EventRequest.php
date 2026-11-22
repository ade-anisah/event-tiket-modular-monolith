<?php

namespace App\Modules\EventModule\Http\Requests;

use App\Modules\EventModule\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'lokasi' => ['required', 'string', 'max:150'],
            'mulai_at' => ['required', 'date'],
            'selesai_at' => ['required', 'date', 'after:mulai_at'],
            'status' => ['required', Rule::in(Event::STATUSES)],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul event wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'mulai_at.required' => 'Waktu mulai wajib diisi.',
            'mulai_at.date' => 'Format waktu mulai tidak valid.',
            'selesai_at.required' => 'Waktu selesai wajib diisi.',
            'selesai_at.after' => 'Waktu selesai harus setelah waktu mulai.',
            'status.in' => 'Status harus draft, published, atau closed.',
            'category_ids.required' => 'Pilih minimal satu kategori.',
            'category_ids.min' => 'Pilih minimal satu kategori.',
            'category_ids.*.exists' => 'Ada kategori yang dipilih tidak ditemukan.',
        ];
    }
}
