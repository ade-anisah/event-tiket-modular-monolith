# Contract, Domain Event, Seeder, dan Rencana Pengujian

## Contract (interface) antar modul

| Contract | Dimiliki modul | Dipakai oleh | Method | Keterangan |
|---|---|---|---|---|
| `EventLookupContract` | EventModule | TicketModule, RegistrationModule | `find(int $eventId): ?EventSummary`, `findPublished(int $eventId): ?EventSummary`, dan `ensureOpen(int $eventId): void` | `find` untuk semua status, `findPublished` hanya event published, `ensureOpen` melempar `EventTidakDibukaException` bila event tidak ada atau belum published |
| `TicketInventoryContract` | TicketModule | RegistrationModule | `summary(int $ticketTypeId): TicketTypeSummary` dan `reserveAndIssue(int $ticketTypeId, int $jumlah, int $registrationId, int $userId): array` | Mengurangi kuota dan menerbitkan tiket berkode secara atomik. Melempar `KuotaTiketPenuhException` bila sisa kurang |

`EventSummary` dan `TicketTypeSummary` adalah objek data sederhana milik modul penyedia. Modul pemakai hanya menerima objek itu, bukan model Eloquent.

## Domain Event

| Event | Dikirim oleh | Didengar oleh | Isi (payload) | Dampak |
|---|---|---|---|---|
| `PendaftaranDikonfirmasi` | RegistrationModule (setelah transaksi berhasil) | EventModule (`TambahPesertaTerdaftar`) | `registrationId`, `eventId`, `jumlah` | Menambah `events.peserta_terdaftar` |

## Alur pembelian tiket (RegistrationService::purchase)

1. Peserta mengirim form (jenis tiket, jumlah). `PembelianTiketRequest` memvalidasi input.
2. `RegistrationService` memulai `DB::transaction`.
3. Cek event lewat `EventLookupContract::ensureOpen`.
4. Buat `registrations` berstatus `pending`.
5. Panggil `TicketInventoryContract::reserveAndIssue`. Bila kuota kurang, `KuotaTiketPenuhException` dilempar dan SELURUH transaksi dibatalkan (rollback).
6. Ubah `registrations.status` menjadi `confirmed`.
7. Setelah transaksi commit, kirim `PendaftaranDikonfirmasi`.
8. Pengecualian ditangani dan ditampilkan sebagai pesan informatif di view.

## Seeder deterministik (data contoh)

Akun (password semua `password`, hanya data dummy):

| Email | Role |
|---|---|
| `admin@uts.test` | admin |
| `peserta1@uts.test` | peserta |
| `peserta2@uts.test` | peserta |
| `peserta3@uts.test` | peserta |

Kategori: 1 Musik, 2 Seminar, 3 Olahraga.

| Event | Judul (contoh) | Status | Kategori | `category_event` |
|---|---|---|---|---|
| E1 | Festival Musik Jogja | published | Musik, Seminar | 2 baris |
| E2 | Seminar Teknologi | published | Seminar | 1 baris |
| E3 | Fun Run Kampus | draft | Olahraga | 1 baris |

Total baris `category_event` = 2 + 1 + 1 = 4.

| Jenis tiket | Event | Nama | Harga | Kuota | Terjual awal | Maks per pembelian |
|---|---|---|---|---|---|---|
| TT1 | E1 | Regular | 150000 | 5 | 0 | 4 |
| TT2 | E1 | VIP | 300000 | 2 | 0 | 4 |
| TT3 | E2 | Umum | 50000 | 10 | 0 | 4 |
| TT4 | E3 | Early Bird | 75000 | 3 | 0 | 4 |

## Rencana pengujian dengan angka (kumulatif, urut dari data seeder awal)

| No | Skenario | Input | Hasil yang diharapkan | Cara hitung |
|---|---|---|---|---|
| T1 | Beli berhasil | peserta1, TT1, jumlah 2 | Sukses. TT1 terjual 2, sisa 3. 1 pendaftaran `confirmed`, 2 tiket berkode unik. Total 300000. `peserta_terdaftar` E1 = 2 | 2 x 150000 = 300000. Sisa = 5 - 2 = 3 |
| T2 | Kuota kurang, rollback | peserta2, TT1, jumlah 4 | `KuotaTiketPenuhException`. TT1 terjual tetap 2. Jumlah pendaftaran tetap 1. Jumlah tiket tetap 2. `peserta_terdaftar` E1 tetap 2 | Sisa 3 < diminta 4, transaksi dibatalkan |
| T3 | Habiskan kuota | peserta2, TT1, jumlah 3 | Sukses. TT1 terjual 5, sisa 0. Total 450000. `peserta_terdaftar` E1 = 5 | 3 x 150000 = 450000. 2 + 3 = 5 |
| T4 | Kuota habis | peserta3, TT1, jumlah 1 | `KuotaTiketPenuhException` (sisa 0). Data tidak berubah | Sisa 0 < 1 |
| T5 | Beli VIP | peserta3, TT2, jumlah 2 | Sukses. TT2 terjual 2, sisa 0. Total 600000. `peserta_terdaftar` E1 = 7 | 2 x 300000 = 600000. 5 + 2 = 7 |
| T6 | Validasi: jumlah lebih dari batas | peserta1, TT3, jumlah 5 | Gagal di Form Request: maksimal 4 per pembelian | 5 > 4 |
| T7 | Validasi: jumlah nol | peserta1, TT3, jumlah 0 | Gagal di Form Request: minimal 1 | 0 < 1 |
| T8 | Event draft | peserta1, TT4, jumlah 1 | `EventTidakDibukaException`. Data tidak berubah | E3 berstatus draft |
| T9 | RBAC | peserta1 membuka halaman admin | Ditolak (403 atau pesan akses ditolak) | role `peserta` bukan `admin` |
| T10 | Kode tiket unik | semua tiket dari T1, T3, T5 | 7 kode berbeda | 2 + 3 + 2 = 7 tiket |
