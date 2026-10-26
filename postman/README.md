# Dokumentasi Postman — PerpusKu Management Library API

Koleksi ini berisi 35 request untuk seluruh endpoint API `/api/v1` aplikasi Management Library.

| Item | Lokasi |
| --- | --- |
| Koleksi | `postman/collections/PerpusKu Management Library API` |
| Environment | `postman/environments/Local.environment.yaml` |
| Spesifikasi sumber | `docs/openapi.json` |
| Kontrak API | `docs/kontrak-api.md` |

## 1. Persiapan

Jalankan aplikasi Laravel:

```bash
php artisan migrate
php artisan serve
```

API berjalan di `http://localhost:8000/api/v1`.

## 2. Import ke Postman

1. Buka Postman.
2. Import folder `postman/collections/PerpusKu Management Library API`.
3. Import environment `postman/environments/Local.environment.yaml`.
4. Pilih environment **Local** di kanan atas.

Environment Local berisi:

| Variabel | Nilai | Keterangan |
| --- | --- | --- |
| `baseUrl` | `http://localhost:8000/api/v1` | Base URL API. |
| `bearerToken` | kosong | Diisi otomatis oleh script login/register. |

Koleksi juga memiliki variabel cadangan `scheme`, `host`, `port`, dan `bearerToken`. Nilai environment akan menimpa nilai koleksi.

## 3. Urutan Penggunaan

1. Jalankan **Auth > Registrasi pengguna baru** untuk membuat akun baru, atau **Auth > Login pengguna** jika akun sudah ada.
2. Script `afterResponse` pada kedua request tersebut menyimpan `data.token` ke variabel `bearerToken`.
3. Jalankan request lain. Auth Bearer di level koleksi otomatis memakai `{{bearerToken}}`.
4. Untuk mengakhiri sesi, jalankan **Auth > Logout dan cabut token**. Setelah logout, token lama tidak berlaku.

Contoh body login:

```json
{
  "email": "api.tester@example.com",
  "password": "password"
}
```

Akun ini dibuat oleh `php artisan migrate --seed` pada environment lokal.

Contoh body register:

```json
{
  "name": "API Tester",
  "email": "api.tester@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

## 4. Isi Folder

| Folder | Request |
| --- | --- |
| Auth | Registrasi, login, profil (`/me`), logout. |
| Dashboard | Ringkasan statistik perpustakaan. |
| Buku | Daftar, tambah, detail, update, patch, hapus. |
| Author | Daftar, tambah, detail, update, patch, hapus. |
| Kategori | Daftar, tambah, detail, update, patch, hapus. |
| Kelas | Daftar, tambah, detail, update, patch, hapus. |
| Siswa | Daftar, tambah, detail, update, patch, hapus. |

Setiap request memiliki contoh request dan contoh respons di folder `.resources`. Contoh ini bisa dilihat langsung dari panel contoh di Postman.

## 5. Catatan Penting

- **Peminjaman belum tersedia.** Aplikasi belum memiliki tabel, model, route, atau controller peminjaman. Karena itu tidak ada folder Peminjaman pada koleksi ini. Lihat `docs/kontrak-api.md` bagian Catatan Peminjaman.
- **Register dan login tidak memakai Bearer.** Kedua request memakai `noauth` agar tidak mengirim token kosong.
- **PATCH bukan partial update.** Controller memakai aturan validasi yang sama dengan PUT, jadi semua field wajib dikirim.
- **Delete dengan relasi menghasilkan 409.** Author/kategori yang masih punya buku dan kelas yang masih punya siswa tidak bisa dihapus.
- **`per_page` dibatasi 1 sampai 100.**
- **Rate limit register/login:** 10 percobaan per menit per IP. Jika terkena limit, tunggu satu menit atau gunakan akun lain.

## 6. Menjalankan dari CLI

Validasi koleksi dan environment:

```bash
postman collection lint "postman/collections/PerpusKu Management Library API"
postman environment lint postman/environments --fail-severity warning
```

Menjalankan seluruh koleksi (setelah server Laravel hidup dan environment dipilih):

```bash
postman collection run "postman/collections/PerpusKu Management Library API" \
  --environment postman/environments/Local.environment.yaml
```

Menjalankan request tertentu:

```bash
postman collection run "postman/collections/PerpusKu Management Library API" \
  --environment postman/environments/Local.environment.yaml \
  --folder Auth
```

Catatan: request yang membutuhkan ID resource (misalnya `GET /buku/{buku_id}`) memakai ID contoh `1`. Jalankan request tambah terlebih dahulu atau sesuaikan path variable dengan ID yang benar-benar ada.

## 7. Regenerasi Koleksi

Jika `docs/openapi.json` berubah, regenerasi koleksi:

```bash
postman spec generate collection docs/openapi.json \
  -n "PerpusKu Management Library API" \
  --folder-strategy Tags \
  --force
```

Setelah regenerasi, periksa kembali:

- `auth: noauth` pada register/login.
- Script `afterResponse` penyimpan token pada register/login.
- Contoh respons register yang memakai pesan `Registrasi berhasil.`.
- Environment Local masih menunjuk ke base URL yang benar.
