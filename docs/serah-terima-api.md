# Serah Terima API Management Library

Dokumen ini merangkum pengerjaan dokumentasi API mengikuti urutan lembar kerja **Serah Terima API**: menyiapkan dan memetakan, menyusun kontrak, membuat dokumentasi Postman, menguji silang, lalu memperbaiki dan merefleksikan.

## Ringkasan Hasil

| Item | Hasil |
| --- | --- |
| Spesifikasi OpenAPI | `docs/openapi.json` (OpenAPI 3.0.3) |
| Jumlah path | 15 |
| Jumlah operasi | 35 |
| Kontrak manusia | `docs/kontrak-api.md` |
| Koleksi Postman | `postman/collections/PerpusKu Management Library API` (35 request) |
| Environment Postman | `postman/environments/Local.environment.yaml` |
| Automated test | 8 test, 47 assertion, semua lulus |
| Validasi OpenAPI | Redocly valid, Postman spec lint tanpa issue |
| Validasi koleksi | Postman collection lint: 35 request, 0 error, 0 warning |
| Validasi environment | Postman environment lint: 0 error, 0 warning |

## 01 Siapkan dan Petakan

### 1.1 Menjalankan proyek

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Aplikasi berjalan di `http://localhost:8000`. API memakai prefix `/api/v1`, sehingga base URL API adalah `http://localhost:8000/api/v1`.

### 1.2 Memeriksa route

Route web diperiksa dengan `php artisan route:list`. Aplikasi asli adalah aplikasi Blade dengan resource web untuk buku, siswa, kelas, author, dan kategori, serta autentikasi Laravel Breeze. Aplikasi belum memiliki route API.

Route API kemudian ditambahkan di `routes/api.php`:

```php
Route::prefix('v1')->name('api.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware('auth.api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/dashboard', DashboardController::class);
        Route::apiResource('buku', BukuController::class);
        Route::apiResource('author', AuthorController::class);
        Route::apiResource('kategori', KategoriController::class);
        Route::apiResource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::apiResource('siswa', SiswaController::class);
    });
});
```

### 1.3 Memetakan data dan aturan validasi

| Resource | Model | Field utama | Relasi |
| --- | --- | --- | --- |
| Buku | `buku` | `judul`, `stock`, `author_id` | `author`, `kategoris` many-to-many |
| Author | `author` | `nama_author` | `buku` |
| Kategori | `kategori` | `nama_kategori` | `buku` many-to-many |
| Kelas | `kelas` | `nama_kelas`, `tingkat` | `siswa` |
| Siswa | `siswa` | `nama`, `nis`, `kelas_id` | `kelas` |
| Pengguna | `User` | `name`, `email`, `password`, `api_token` | - |

Validasi diambil langsung dari controller API, bukan dikarang. Contoh aturan buku: `judul` wajib string maksimal 255, `stock` wajib integer minimal 0, `author_id` wajib ada di tabel `authors`, dan `kategori_id` wajib array minimal satu item yang setiap ID-nya ada di tabel `kategoris`.

### 1.4 Temuan penting

- **Fitur peminjaman tidak ada di kode aplikasi.** Tidak ada tabel, model, route, controller, atau tampilan peminjaman. Karena itu peminjaman tidak dibuat sebagai endpoint palsu. Sebagai gantinya, endpoint inti yang didokumentasikan adalah register, login, detail siswa (anggota), daftar buku, dan dashboard.
- **Aplikasi belum punya API.** Diperlukan lapisan API baru agar website yang sebelumnya hanya Blade dapat diuji lewat Postman.
- **Autentikasi API belum ada.** Ditambahkan kolom `users.api_token`, middleware `auth.api`, dan endpoint register/login/logout.
- **Nama route bentrok.** Route API diberi prefix nama `api.` agar tidak menimpa nama route web.
- **Parameter `kelas` berubah menjadi `kela`.** Laravel men-singular-kan nama parameter; diperbaiki dengan `->parameters(['kelas' => 'kelas'])`.

## 02 Susun Kontrak API

Kontrak disusun dalam dua bentuk:

1. **Kontrak manusia:** [`docs/kontrak-api.md`](./kontrak-api.md) — aturan umum, tabel endpoint, validasi, contoh request/response, status code, dan catatan peminjaman.
2. **Kontrak mesin:** [`docs/openapi.json`](./openapi.json) — OpenAPI 3.0.3 yang bisa dipakai untuk generate koleksi, mock, dan validasi.

Isi spesifikasi OpenAPI:

- `info` berisi judul, versi, dan deskripsi aplikasi.
- `servers` memakai variabel `scheme`, `host`, dan `port` dengan default `http://localhost:8000/api/v1`.
- `tags` mengelompokkan Auth, Dashboard, Buku, Author, Kategori, Kelas, dan Siswa.
- `paths` memuat 15 path dan 35 operasi.
- `components.schemas` memuat schema resource, input, respons, pagination, dan error.
- `components.parameters` memuat parameter path, search, filter, dan pagination yang dipakai berulang.
- `components.responses` memuat `Unauthorized`, `NotFound`, `ValidationError`, `Conflict`, dan `TooManyRequests`.
- `components.examples` memuat contoh request dan response agar dokumentasi tidak kosong.
- `securitySchemes.bearerAuth` mendefinisikan autentikasi Bearer.
- Register dan login memakai `security: []` karena tidak memerlukan token.

Keputusan kontrak yang perlu diketahui penguji:

- `GET` detail mengembalikan `{ "data": ... }` tanpa `message`.
- `POST` dan `PUT`/`PATCH` mengembalikan `{ "message": ..., "data": ... }`.
- `PATCH` saat ini memakai validasi yang sama dengan `PUT`; semua field wajib dikirim.
- Delete author/kategori/kelas mengembalikan `409` jika masih punya relasi.
- `per_page` dibatasi 1 sampai 100.

## 03 Buat Dokumentasi Postman

Koleksi dibuat dari spesifikasi OpenAPI:

```bash
postman spec generate collection docs/openapi.json \
  -n "PerpusKu Management Library API" \
  --folder-strategy Tags \
  --force
```

Hasilnya berupa koleksi git-native v3 di `postman/collections/PerpusKu Management Library API`, dengan folder:

- **Auth** — register, login, profil, logout.
- **Dashboard** — ringkasan statistik.
- **Buku** — daftar, tambah, detail, update, patch, hapus.
- **Author** — daftar, tambah, detail, update, patch, hapus.
- **Kategori** — daftar, tambah, detail, update, patch, hapus.
- **Kelas** — daftar, tambah, detail, update, patch, hapus.
- **Siswa** — daftar, tambah, detail, update, patch, hapus.

Fitur koleksi:

- Variabel `baseUrl` dengan nilai `http://localhost:8000/api/v1`.
- Variabel `bearerToken` untuk menyimpan token.
- Auth Bearer di level koleksi.
- Request register dan login memakai `noauth`.
- Script `afterResponse` pada register/login menyimpan `data.token` ke variabel `bearerToken`.
- Setiap request memiliki contoh request dan response.
- Environment **Local** berisi `baseUrl` dan `bearerToken`.

Cara pakai:

1. Buka Postman dan pilih environment **Local**.
2. Jalankan **Auth > Login pengguna** (atau register).
3. Token tersimpan otomatis.
4. Jalankan request lain tanpa mengisi header Authorization manual.

Dokumentasi operasional koleksi ada di [`postman/README.md`](../postman/README.md).

## 04 Uji Silang

### 4.1 Uji otomatis yang sudah dijalankan

```bash
php artisan test tests/Feature/Api/LibraryApiTest.php
```

Hasil: **8 test lulus, 47 assertion**. Cakupan:

- Register dan menerima Bearer token.
- Login dan menerima Bearer token.
- Endpoint terproteksi menolak request tanpa token (`401`).
- Membuat dan mencari buku.
- Detail siswa beserta kelas.
- Validasi payload buku (`422`).
- Kelas yang masih punya siswa tidak bisa dihapus (`409`).
- Dashboard mengembalikan statistik.

Validasi dokumentasi:

```bash
npx --yes @redocly/cli lint docs/openapi.json
postman spec lint docs/openapi.json --issueType=syntax --no-report-events
postman collection lint "postman/collections/PerpusKu Management Library API"
postman environment lint postman/environments --fail-severity warning
```

Hasil: OpenAPI valid, spec lint tanpa issue, collection lint 35 request tanpa error/warning, environment lint tanpa issue.

### 4.2 Checklist uji silang dengan teman

Uji silang manual dengan teman belum dijalankan dalam sesi ini. Checklist berikut disiapkan agar penguji lain bisa menjalankannya:

- [ ] Import koleksi dan environment Local.
- [ ] Jalankan register dengan email baru; pastikan `201` dan token tersimpan.
- [ ] Jalankan login; pastikan `200` dan token berubah.
- [ ] Jalankan `GET /me`; pastikan data pengguna sama dengan yang login.
- [ ] Jalankan `GET /buku`; pastikan pagination dan filter bekerja.
- [ ] Tambah author, kategori, kelas, lalu siswa.
- [ ] Tambah buku dengan `author_id` dan `kategori_id` yang valid.
- [ ] Coba tambah buku dengan `stock: -1`; pastikan `422`.
- [ ] Coba hapus kelas yang masih punya siswa; pastikan `409`.
- [ ] Jalankan logout, lalu ulangi `GET /me` dengan token lama; pastikan `401`.
- [ ] Cocokkan response Postman dengan `docs/kontrak-api.md`.
- [ ] Catat perbedaan antara dokumentasi dan perilaku nyata.

## 05 Perbaiki dan Refleksi

### 5.1 Perbaikan yang dilakukan

| Temuan | Perbaikan |
| --- | --- |
| Aplikasi hanya punya route web | Menambahkan `routes/api.php` dengan prefix `/api/v1`. |
| Belum ada autentikasi API | Menambahkan `api_token`, middleware `auth.api`, dan endpoint register/login/logout. |
| Token disimpan mentah berisiko | Token disimpan sebagai hash SHA-256; token asli hanya dikembalikan sekali. |
| Route API menimpa nama route web | Semua route API memakai prefix nama `api.`. |
| Parameter `kelas` menjadi `kela` | Menambahkan `->parameters(['kelas' => 'kelas'])`. |
| Dashboard memakai fungsi SQL khusus MySQL | Query grafik dibuat DB-agnostic agar test SQLite stabil. |
| Author bisa terhapus meski masih punya buku | Delete author mengembalikan `409`. |
| Kategori/kelas bisa terhapus meski punya relasi | Delete mengembalikan `409`. |
| Contoh detail di spec memakai `message` | Schema detail dipisahkan dari schema create/update. |
| Contoh register memakai pesan login | Ditambahkan contoh respons register yang benar. |
| Login/register mewarisi Bearer auth | Request diberi `noauth`; contoh respons tidak lagi memuat header Authorization. |
| Token harus diisi manual di Postman | Script `afterResponse` menyimpan token otomatis. |

### 5.2 Refleksi

- Dokumentasi yang baik harus mengikuti kode yang ada, bukan daftar endpoint yang diinginkan. Karena peminjaman belum ada, peminjaman dicatat sebagai fitur yang belum tersedia.
- OpenAPI memaksa kontrak menjadi eksplisit: tipe data, field wajib, status code, dan bentuk error. Beberapa ketidakcocokan antara contoh dan controller baru terlihat setelah spec diuji.
- Memisahkan schema detail dan schema mutasi membuat kontrak lebih jujur terhadap respons nyata.
- Token otomatis di Postman mengurangi kesalahan manual saat pengujian.
- Automated test memberi bukti bahwa kontrak utama benar-benar berjalan, bukan hanya terdokumentasi.

### 5.3 Pekerjaan lanjutan yang disarankan

- Implementasikan fitur peminjaman dan pengembalian, lalu tambahkan endpoint serta schema-nya.
- Pertimbangkan `PATCH` sebagai partial update yang benar (field `sometimes`), atau ganti dokumentasi agar jelas bahwa `PATCH` saat ini setara `PUT`.
- Tambahkan masa berlaku token atau gunakan Laravel Sanctum untuk pengelolaan token yang lebih matang.
- Tambahkan rate limit untuk seluruh endpoint, bukan hanya register/login.
- Tambahkan test CRUD untuk author, kategori, dan siswa.
- Tambahkan validasi kontrak otomatis di CI (Redocly + Postman lint + test Laravel).
