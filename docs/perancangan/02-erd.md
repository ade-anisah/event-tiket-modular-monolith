# Entity Relationship Diagram

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email
        string password
        string role "admin atau peserta"
    }
    CATEGORIES {
        bigint id PK
        string nama
        string slug UK
    }
    EVENTS {
        bigint id PK
        string judul
        string slug UK
        text deskripsi
        string lokasi
        datetime mulai_at
        datetime selesai_at
        string status "draft, published, closed"
        int peserta_terdaftar
        bigint created_by "logis ke users"
    }
    CATEGORY_EVENT {
        bigint id PK
        bigint category_id FK
        bigint event_id FK
    }
    TICKET_TYPES {
        bigint id PK
        bigint event_id "logis ke events"
        string nama
        int harga
        int kuota
        int terjual
        int maks_per_pembelian
    }
    TICKETS {
        bigint id PK
        bigint ticket_type_id FK
        bigint registration_id "logis ke registrations"
        bigint user_id "logis ke users"
        string kode UK
        string status "aktif, dipakai, batal"
        int harga_snapshot
        datetime used_at
    }
    PARTICIPANTS {
        bigint id PK
        bigint user_id UK "logis ke users"
        string nama_lengkap
        string no_hp
    }
    REGISTRATIONS {
        bigint id PK
        bigint participant_id FK
        bigint event_id "logis ke events"
        bigint ticket_type_id "logis ke ticket_types"
        string kode_pendaftaran UK
        int jumlah
        int harga_satuan_snapshot
        int total
        string status "pending, confirmed, failed"
    }

    CATEGORIES ||--o{ CATEGORY_EVENT : "punya"
    EVENTS ||--o{ CATEGORY_EVENT : "punya"
    TICKET_TYPES ||--o{ TICKETS : "menerbitkan"
    PARTICIPANTS ||--o{ REGISTRATIONS : "melakukan"
    EVENTS ||..o{ TICKET_TYPES : "logis lintas modul"
    EVENTS ||..o{ REGISTRATIONS : "logis lintas modul"
    TICKET_TYPES ||..o{ REGISTRATIONS : "logis lintas modul"
    REGISTRATIONS ||..o{ TICKETS : "logis lintas modul"
    USERS ||..o| PARTICIPANTS : "logis shared kernel"
```

Keterangan:
- Garis solid: relasi nyata di dalam satu modul (foreign key dan relasi Eloquent).
  - Many-to-Many (EventModule): `categories` dan `events` lewat pivot `category_event`.
  - One-to-Many (TicketModule): `ticket_types` ke `tickets`.
  - One-to-Many (RegistrationModule): `participants` ke `registrations`.
- Garis putus-putus: relasi logis lintas modul. Hanya berupa ID biasa tanpa foreign key dan tanpa relasi Eloquent. Data diambil lewat Contract.
- Kolom snapshot (`harga_snapshot`, `harga_satuan_snapshot`) menyimpan harga saat transaksi agar perubahan harga kemudian tidak mengubah riwayat.