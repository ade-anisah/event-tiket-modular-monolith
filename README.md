# UTS Backend Development: Sistem Manajemen Event dan Tiket

Aplikasi web Modular Monolith berbasis Laravel 13 untuk UTS Backend Development (DT199),
D3 Teknik Informatika, Universitas AMIKOM Yogyakarta.

## Anggota

| Nama | NIM | Peran utama |
|---|---|---|
| Ade Anisah | 25.01.5251 | Kerangka proyek dan EventModule |
| Septiyana Hardiyanti | 25.01.5281 | TicketModule |
| Paquetha Erva Marchella Putri | 25.01.5290 | Autentikasi dan RegistrationModule |

## Teknologi

Laravel 13 (PHP 8.4 atau lebih), arsitektur Modular Monolith, SQLite, Laravel Sanctum, RBAC (admin dan peserta), Blade, PHPUnit, Laravel Pint.

## Modul

| Modul | Isi | Berkomunikasi lewat |
|---|---|---|
| EventModule | Event, kategori (Many-to-Many dengan pivot) | `EventLookupContract`, listener `TambahPesertaTerdaftar` |
| TicketModule | Jenis tiket, tiket berkode unik, kuota (One-to-Many) | `TicketInventoryContract` |
| RegistrationModule | Peserta, pendaftaran, alur pembelian (One-to-Many) | Domain Event `PendaftaranDikonfirmasi` |

Dokumen rancangan (bounded context, ERD, contract, event, seeder): folder `docs/perancangan`.

## Cara menjalankan (Windows PowerShell)

```
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File -Path database\database.sqlite -Force
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Akun contoh (data dummy, password semua: `password`)

| Email | Role |
|---|---|
| `admin@uts.test` | admin |
| `peserta1@uts.test` | peserta |
| `peserta2@uts.test` | peserta |
| `peserta3@uts.test` | peserta |

## Menjalankan tes

```
php artisan test
```

## Video demo

LINK_VIDEO_YOUTUBE_UNLISTED