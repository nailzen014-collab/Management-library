# 📚 Management Library

Website **Management Library** untuk membantu proses pengelolaan perpustakaan secara digital, mulai dari data buku, anggota, hingga transaksi peminjaman dan pengembalian.

## ✨ Features

- 📖 Management Data Buku
- 👥 Management Data Siswa
- 🔄 Management Kelas
- ↩️ Management Kategori & author
- 📊 Dashboard & Statistik Perpustakaan
- 🔐 Authentication & Authorization
- 🔍 Pencarian Data


## 🛠️ Tech Stack

- **Laravel**
- **PHP**
- **MySQL**
- **Blade**
- **HTML**
- **CSS**
- **JavaScript**

## 📋 Requirements

Pastikan sudah menginstall:

- PHP >= 8.x
- Composer
- MySQL
- Node.js & NPM

## ⚙️ Installation

Clone repository:

```bash
git clone https://github.com/nailzen014-collab/Management-library.git
```

## API Documentation

- Kontrak API: [`docs/kontrak-api.md`](docs/kontrak-api.md)
- Spesifikasi OpenAPI 3.0.3: [`docs/openapi.json`](docs/openapi.json)
- Koleksi Postman: [`postman/README.md`](postman/README.md)
- Laporan serah terima: [`docs/serah-terima-api.md`](docs/serah-terima-api.md)

Base URL API lokal: `http://localhost:8000/api/v1`

Catatan: fitur peminjaman belum tersedia pada aplikasi saat ini, sehingga belum didokumentasikan sebagai endpoint.

Dokumentasi interaktif Swagger UI tersedia setelah login di `/api-docs`. Kontrak OpenAPI mentah dapat dibuka di `/openapi.json`.

### Akun demo lokal

Seeder lokal menyediakan akun untuk login web maupun mencoba endpoint API:

| Email | Password |
| --- | --- |
| `api.tester@example.com` | `password` |

Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Untuk mengulang seeder saja:

```bash
php artisan db:seed
```

Akun demo hanya dibuat pada environment `local` dan `testing`; jangan gunakan kredensial demo untuk production.