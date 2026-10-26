# Kontrak API Management Library (PerpusKu)

Dokumen ini adalah kontrak API untuk aplikasi **Management Library (PerpusKu)**. Kontrak disusun dari route, aturan validasi, dan respons yang benar-benar dipakai oleh aplikasi Laravel, lalu dituangkan ke spesifikasi OpenAPI 3.0.3.

| Item | Nilai |
| --- | --- |
| Versi API | `v1` |
| Base URL lokal | `http://localhost:8000/api/v1` |
| Format data | JSON (`application/json`) |
| Spesifikasi mesin | [`docs/openapi.json`](./openapi.json) |
| Koleksi Postman | `postman/collections/PerpusKu Management Library API` |
| Environment Postman | `postman/environments/Local.environment.yaml` |

## 1. Aturan Umum

### 1.1 Header

| Header | Wajib | Keterangan |
| --- | --- | --- |
| `Accept: application/json` | Ya | Semua endpoint mengembalikan JSON. |
| `Content-Type: application/json` | Untuk request berbody | Dipakai pada `POST`, `PUT`, dan `PATCH`. |
| `Authorization: Bearer <token>` | Ya, kecuali register/login | Token didapat dari respons register atau login. |

### 1.2 Autentikasi

- `POST /register` dan `POST /login` tidak memerlukan token.
- Endpoint lain memerlukan Bearer token.
- Token berupa string acak 64 karakter. Di database, token disimpan dalam bentuk hash SHA-256 pada kolom `users.api_token`.
- Satu pengguna memiliki satu token aktif. Login atau register baru akan mengganti token sebelumnya.
- `POST /logout` menghapus token aktif sehingga token tersebut tidak dapat dipakai lagi.
- Token tidak valid atau tidak dikirim menghasilkan `401` dengan body:

```json
{
  "message": "Unauthenticated."
}
```

### 1.3 Bentuk Respons

Respons sukses yang mengembalikan satu data:

```json
{
  "message": "Buku berhasil ditambahkan.",
  "data": {}
}
```

Respons detail terkadang hanya berisi `data` tanpa `message`, misalnya `GET /buku/{id}` dan `GET /siswa/{id}`:

```json
{
  "data": {}
}
```

Respons daftar memakai paginator Laravel dengan field di level atas:

```json
{
  "current_page": 1,
  "data": [],
  "first_page_url": "http://localhost:8000/api/v1/buku?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost:8000/api/v1/buku?page=1",
  "links": [],
  "next_page_url": null,
  "path": "http://localhost:8000/api/v1/buku",
  "per_page": 10,
  "prev_page_url": null,
  "to": 1,
  "total": 1
}
```

Respons error memakai bentuk berikut:

```json
{
  "message": "Pesan error."
}
```

Khusus error validasi (`422`):

```json
{
  "message": "The judul field is required. (and 3 more errors)",
  "errors": {
    "judul": ["The judul field is required."],
    "stock": ["The stock field must be at least 0."]
  }
}
```

### 1.4 Pagination

Semua endpoint daftar mendukung query `page` dan `per_page`.

| Parameter | Tipe | Default | Batas | Keterangan |
| --- | --- | --- | --- | --- |
| `page` | integer | `1` | >= 1 | Halaman yang diminta. |
| `per_page` | integer | `10` | 1 sampai 100 | Jumlah data per halaman. Nilai di luar batas akan dipotong. |

## 2. Ringkasan Endpoint

### 2.1 Autentikasi

| Method | Path | Auth | Keterangan |
| --- | --- | --- | --- |
| `POST` | `/register` | Tidak | Membuat akun dan menerbitkan token. |
| `POST` | `/login` | Tidak | Validasi kredensial dan menerbitkan token. |
| `GET` | `/me` | Bearer | Profil pengguna yang sedang login. |
| `POST` | `/logout` | Bearer | Menghapus token aktif. |

`POST /register` dan `POST /login` dibatasi `10` percobaan per menit per IP. Jika terlampaui, API mengembalikan `429`.

### 2.2 Dashboard

| Method | Path | Auth | Keterangan |
| --- | --- | --- | --- |
| `GET` | `/dashboard` | Bearer | Statistik, grafik 6 bulan, dan 5 buku terbaru. |

### 2.3 Buku

| Method | Path | Auth | Keterangan |
| --- | --- | --- | --- |
| `GET` | `/buku` | Bearer | Daftar buku dengan pencarian dan filter. |
| `POST` | `/buku` | Bearer | Tambah buku. |
| `GET` | `/buku/{buku_id}` | Bearer | Detail buku beserta author dan kategori. |
| `PUT` | `/buku/{buku_id}` | Bearer | Perbarui seluruh data buku. |
| `PATCH` | `/buku/{buku_id}` | Bearer | Perbarui data buku; field tetap wajib lengkap. |
| `DELETE` | `/buku/{buku_id}` | Bearer | Hapus buku dan lepaskan relasi kategori. |

Query `GET /buku`:

| Parameter | Tipe | Keterangan |
| --- | --- | --- |
| `search` | string | Cari pada `judul`. |
| `author_id` | integer | Filter berdasarkan author. |
| `kategori_id` | integer | Filter buku yang memiliki kategori tertentu. |
| `page` | integer | Halaman. |
| `per_page` | integer | Jumlah per halaman (1-100). |

### 2.4 Author

| Method | Path | Auth | Keterangan |
| --- | --- | --- | --- |
| `GET` | `/author` | Bearer | Daftar author beserta jumlah buku. |
| `POST` | `/author` | Bearer | Tambah author. |
| `GET` | `/author/{author_id}` | Bearer | Detail author beserta daftar buku. |
| `PUT` | `/author/{author_id}` | Bearer | Perbarui author. |
| `PATCH` | `/author/{author_id}` | Bearer | Perbarui author; field tetap wajib. |
| `DELETE` | `/author/{author_id}` | Bearer | Hapus author jika belum punya buku. |

Query `GET /author`: `search`, `page`, `per_page`.

### 2.5 Kategori

| Method | Path | Auth | Keterangan |
| --- | --- | --- | --- |
| `GET` | `/kategori` | Bearer | Daftar kategori beserta jumlah buku. |
| `POST` | `/kategori` | Bearer | Tambah kategori. |
| `GET` | `/kategori/{kategori_id}` | Bearer | Detail kategori beserta daftar buku. |
| `PUT` | `/kategori/{kategori_id}` | Bearer | Perbarui kategori. |
| `PATCH` | `/kategori/{kategori_id}` | Bearer | Perbarui kategori; field tetap wajib. |
| `DELETE` | `/kategori/{kategori_id}` | Bearer | Hapus kategori jika belum punya buku. |

Query `GET /kategori`: `search`, `page`, `per_page`.

### 2.6 Kelas

| Method | Path | Auth | Keterangan |
| --- | --- | --- | --- |
| `GET` | `/kelas` | Bearer | Daftar kelas beserta jumlah siswa. |
| `POST` | `/kelas` | Bearer | Tambah kelas. |
| `GET` | `/kelas/{kelas_id}` | Bearer | Detail kelas beserta daftar siswa. |
| `PUT` | `/kelas/{kelas_id}` | Bearer | Perbarui kelas. |
| `PATCH` | `/kelas/{kelas_id}` | Bearer | Perbarui kelas; field tetap wajib. |
| `DELETE` | `/kelas/{kelas_id}` | Bearer | Hapus kelas jika belum punya siswa. |

Query `GET /kelas`: `search`, `page`, `per_page`.

### 2.7 Siswa (Anggota)

| Method | Path | Auth | Keterangan |
| --- | --- | --- | --- |
| `GET` | `/siswa` | Bearer | Daftar siswa beserta kelas. |
| `POST` | `/siswa` | Bearer | Tambah siswa. |
| `GET` | `/siswa/{siswa_id}` | Bearer | Detail siswa beserta kelas. |
| `PUT` | `/siswa/{siswa_id}` | Bearer | Perbarui siswa. |
| `PATCH` | `/siswa/{siswa_id}` | Bearer | Perbarui siswa; field tetap wajib. |
| `DELETE` | `/siswa/{siswa_id}` | Bearer | Hapus siswa. |

Query `GET /siswa`:

| Parameter | Tipe | Keterangan |
| --- | --- | --- |
| `search` | string | Cari pada `nama` atau `nis`. |
| `kelas_id` | integer | Filter berdasarkan kelas. |
| `page` | integer | Halaman. |
| `per_page` | integer | Jumlah per halaman (1-100). |

## 3. Kontrak Data dan Validasi

### 3.1 Buku

| Field | Tipe | Wajib | Aturan |
| --- | --- | --- | --- |
| `judul` | string | Ya | Maksimal 255 karakter. |
| `stock` | integer | Ya | Minimal 0. |
| `author_id` | integer | Ya | Harus ada di tabel `authors`. |
| `kategori_id` | array of integer | Ya | Minimal 1 item; setiap ID harus ada di tabel `kategoris`. |

### 3.2 Author

| Field | Tipe | Wajib | Aturan |
| --- | --- | --- | --- |
| `nama_author` | string | Ya | Maksimal 255 karakter. |

### 3.3 Kategori

| Field | Tipe | Wajib | Aturan |
| --- | --- | --- | --- |
| `nama_kategori` | string | Ya | Maksimal 255 karakter. |

### 3.4 Kelas

| Field | Tipe | Wajib | Aturan |
| --- | --- | --- | --- |
| `nama_kelas` | string | Ya | Maksimal 255 karakter. |
| `tingkat` | string | Ya | Maksimal 255 karakter. |

### 3.5 Siswa

| Field | Tipe | Wajib | Aturan |
| --- | --- | --- | --- |
| `nama` | string | Ya | Maksimal 255 karakter. |
| `nis` | integer | Ya | Unik di tabel `siswas`. Saat update, ID siswa sendiri diabaikan. |
| `kelas_id` | integer | Ya | Harus ada di tabel `kelas`. |

### 3.6 Register dan Login

| Endpoint | Field | Tipe | Wajib | Aturan |
| --- | --- | --- | --- | --- |
| `POST /register` | `name` | string | Ya | Maksimal 255 karakter. |
| `POST /register` | `email` | string | Ya | Format email, huruf kecil, maksimal 255, unik. |
| `POST /register` | `password` | string | Ya | Harus dikonfirmasi lewat `password_confirmation`; mengikuti aturan `Password::defaults()`. |
| `POST /login` | `email` | string | Ya | Format email. |
| `POST /login` | `password` | string | Ya | String. |

Catatan: `PUT` dan `PATCH` pada resource memakai aturan validasi yang sama. Artinya `PATCH` saat ini **bukan** partial update; semua field wajib dikirim.

## 4. Contoh Kontrak Endpoint Inti

### 4.1 Register

`POST /register`

```json
{
  "name": "API Tester",
  "email": "api.tester@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

Respons `201`:

```json
{
  "message": "Registrasi berhasil.",
  "data": {
    "user": {
      "id": 1,
      "name": "API Tester",
      "email": "api.tester@example.com",
      "email_verified_at": null,
      "created_at": "2026-10-26T08:00:00.000000Z",
      "updated_at": "2026-10-26T08:00:00.000000Z"
    },
    "token": "5f8d0d55b54764421b7156c3d5a7c3f9b5e4c1a2d3f6e7a8b9c0d1e2f3a4b5c6",
    "token_type": "Bearer"
  }
}
```

### 4.2 Login

`POST /login`

```json
{
  "email": "api.tester@example.com",
  "password": "password"
}
```

Respons `200`:

```json
{
  "message": "Login berhasil.",
  "data": {
    "user": {
      "id": 1,
      "name": "API Tester",
      "email": "api.tester@example.com",
      "email_verified_at": null,
      "created_at": "2026-10-26T08:00:00.000000Z",
      "updated_at": "2026-10-26T08:00:00.000000Z"
    },
    "token": "5f8d0d55b54764421b7156c3d5a7c3f9b5e4c1a2d3f6e7a8b9c0d1e2f3a4b5c6",
    "token_type": "Bearer"
  }
}
```

### 4.3 Detail Siswa (Anggota)

`GET /siswa/1`

Header:

```http
Authorization: Bearer <token>
Accept: application/json
```

Respons `200`:

```json
{
  "data": {
    "id": 1,
    "nama": "Budi Santoso",
    "nis": 12345,
    "kelas_id": 1,
    "kelas": {
      "id": 1,
      "nama_kelas": "XI RPL 1",
      "tingkat": "XI",
      "created_at": "2026-10-26T08:00:00.000000Z",
      "updated_at": "2026-10-26T08:00:00.000000Z"
    },
    "created_at": "2026-10-26T08:00:00.000000Z",
    "updated_at": "2026-10-26T08:00:00.000000Z"
  }
}
```

### 4.4 Daftar Buku

`GET /buku?search=OpenAPI&author_id=1&kategori_id=1&page=1&per_page=10`

Respons `200` (dipotong):

```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "judul": "Belajar OpenAPI",
      "stock": 5,
      "author_id": 1,
      "author": {
        "id": 1,
        "nama_author": "Eko Kurniawan",
        "created_at": "2026-10-26T08:00:00.000000Z",
        "updated_at": "2026-10-26T08:00:00.000000Z"
      },
      "kategoris": [
        {
          "id": 1,
          "nama_kategori": "Programming",
          "created_at": "2026-10-26T08:00:00.000000Z",
          "updated_at": "2026-10-26T08:00:00.000000Z"
        }
      ],
      "created_at": "2026-10-26T08:00:00.000000Z",
      "updated_at": "2026-10-26T08:00:00.000000Z"
    }
  ],
  "first_page_url": "http://localhost:8000/api/v1/buku?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost:8000/api/v1/buku?page=1",
  "links": [],
  "next_page_url": null,
  "path": "http://localhost:8000/api/v1/buku",
  "per_page": 10,
  "prev_page_url": null,
  "to": 1,
  "total": 1
}
```

### 4.5 Dashboard

`GET /dashboard`

Respons `200` (dipotong pada `recent_buku`):

```json
{
  "data": {
    "stats": {
      "total_buku": 120,
      "total_author": 18,
      "total_kategori": 9,
      "total_siswa": 240,
      "total_kelas": 12
    },
    "chart": {
      "labels": ["Mei", "Jun", "Jul", "Agu", "Sep", "Okt"],
      "data": [3, 5, 2, 8, 6, 4]
    },
    "recent_buku": []
  }
}
```

## 5. Status Code

| Status | Kapan Muncul |
| --- | --- |
| `200` | Permintaan berhasil (daftar, detail, update, delete, login, logout). |
| `201` | Resource berhasil dibuat (register, tambah buku/author/kategori/kelas/siswa). |
| `401` | Bearer token tidak ada atau tidak valid. |
| `404` | ID resource tidak ditemukan. |
| `409` | Resource tidak bisa dihapus karena masih punya relasi. |
| `422` | Payload atau parameter gagal validasi. |
| `429` | Terlalu banyak percobaan register/login (rate limit). |

Aturan `409`:

| Resource | Kondisi |
| --- | --- |
| Author | Masih memiliki buku. |
| Kategori | Masih memiliki buku. |
| Kelas | Masih memiliki siswa. |

## 6. Catatan Peminjaman

Fitur **peminjaman dan pengembalian belum tersedia** pada kode aplikasi saat ini. Tidak ada tabel, model, route, controller, maupun tampilan peminjaman. Karena itu:

- Tidak ada endpoint `/peminjaman` pada kontrak versi ini.
- Endpoint inti yang didokumentasikan adalah register, login, detail siswa (anggota), daftar buku, dan dashboard.
- Jika fitur peminjaman ditambahkan nanti, buat resource baru (misalnya `peminjaman` dan `pengembalian`), lalu tambahkan path, schema, dan contohnya ke `docs/openapi.json` sebelum memperbarui koleksi Postman.

## 7. Cara Memakai Kontrak

1. Jalankan aplikasi: `php artisan serve`.
2. Jalankan migrasi: `php artisan migrate`.
3. Buka Postman, pilih environment **Local**.
4. Jalankan request **Login pengguna** atau **Registrasi pengguna baru**. Script `afterResponse` akan menyimpan token ke variabel `bearerToken`.
5. Jalankan request lain; header Bearer terisi otomatis dari variabel tersebut.

Validasi kontrak:

```bash
npx --yes @redocly/cli lint docs/openapi.json
postman spec lint docs/openapi.json --issueType=syntax --no-report-events
postman collection lint "postman/collections/PerpusKu Management Library API"
```
