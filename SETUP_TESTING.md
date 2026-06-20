# Setup Pengujian — Iron Archive (PEST + Selenium)

Panduan menjalankan lingkungan pengujian Tugas Besar Pengujian & Implementasi Sistem.

> **Cara baca:** tiap bagian diberi label **SIAPA** yang menjalankan.
> Singkatnya:
> - **Setup proyek (bikin file/konfigurasi)** → sudah selesai, **TIDAK perlu diulang siapa pun**.
> - **Instalasi lokal** → **SEMUA anggota**, 1× di laptop masing-masing.
> - **`npm build` + `migrate --seed` + `chromedriver`** → **Vincent** (dan siapa pun yang mau buka aplikasinya di browser).
> - **Menjalankan test** → **masing-masing anggota** untuk file bagiannya; **Ketua** cek keseluruhan.

---

## Bagian 1 — Setup Proyek (file & konfigurasi) — ✅ SUDAH SELESAI
**SIAPA: tidak ada (cukup 1× dan sudah ke-commit di repo).**

Pembuatan controller, factory, seeder, konfigurasi PEST, file test, dan Selenium sudah dikerjakan satu kali dan ada di repository. Anggota lain **cukup `git pull`**, tidak perlu membuat ulang.

```bash
git pull
```

---

## Bagian 2 — Instalasi Lokal (di laptop masing-masing) — 👥 SEMUA ANGGOTA (1× per orang)
**SIAPA: Nanda, Jiyu, Nadhif, Naufal, Vincent — semuanya, sekali setelah pull.**

Wajib agar bisa menjalankan PEST:

```bash
composer install                 # PEST & php-webdriver ikut terpasang di sini
copy .env.example .env           # git bash / Linux: cp .env.example .env
php artisan key:generate
```

> Test PEST memakai SQLite `:memory:` (lihat `phpunit.xml`), jadi **tidak butuh file database** dan tidak menyentuh data aslimu.

---

## Bagian 3 — Database & Build Aplikasi — 🧑‍💻 VINCENT (dan yang mau buka app di browser)
**SIAPA: Vincent (penanggung jawab Selenium), atau siapa pun yang ingin menjalankan aplikasi secara nyata.**

Hanya diperlukan untuk **menjalankan aplikasi / Selenium**, TIDAK diperlukan untuk PEST.

```bash
npm install && npm run build
php artisan migrate:fresh --seed
```

Seeder membuat akun default:

| Role  | Email                    | Password   |
|-------|--------------------------|------------|
| admin | `admin@ironarchive.test` | `password` |
| user  | `user@ironarchive.test`  | `password` |

Plus 4 negara, 4 kategori, dan 2 kendaraan contoh (Tiger I, T-34).

---

## Bagian 4 — Menjalankan Test PEST — 🎯 PER ANGGOTA
**SIAPA: masing-masing anggota menjalankan file bagiannya.**

| Anggota / NIM | Teknik | Perintah |
|---------------|--------|----------|
| **Nanda Pratama Sugiarto** (102062400019) — Ketua | White Box (Basis Path) Vehicle | `php artisan test tests/Unit/VehicleIndexBasisPathTest.php` |
| **Jiyu Danjiki Ake Heriyanto** (102062400152) | White Box User + State Transition Role | `php artisan test tests/Unit/UserManagementBasisPathTest.php tests/Feature/RoleAuthorizationTest.php` |
| **Nadhif Maulana Fayzalty** (102062400016) | Black Box EP/BVA Vehicle + Master Data | `php artisan test tests/Feature/VehicleValidationTest.php tests/Feature/MasterDataValidationTest.php` |
| **MUH NAUFAL RABBANI MARUN** (102062400038) | Black Box EP/BVA Auth + State Transition Auth | `php artisan test tests/Feature/UserAuthValidationTest.php tests/Feature/AuthStateTransitionTest.php` |
| **Ketua / siapa pun** | Cek SEMUA test sebelum dikumpulkan | `php artisan test` |

> Alternatif perintah: `./vendor/bin/pest <path>` (git bash) atau `vendor\bin\pest <path>` (cmd).
> Untuk laporan coverage: `php artisan test --coverage` (butuh Xdebug/PCOV).

### Pemetaan folder ke teknik
| Folder | Teknik manual (di laporan) | Tool |
|--------|----------------------------|------|
| `tests/Unit/`    | Basis Path Testing (flow graph, V(G), independent path) | PEST Unit Test |
| `tests/Feature/` | Equivalence Partitioning + BVA, State Transition | PEST Feature Test |

---

## Bagian 5 — Menjalankan Test Selenium (UI) — 🧑‍💻 VINCENT
**SIAPA: Vincent Imanuel Putra (102062400026).**

Butuh **3 proses** berjalan bersamaan (3 terminal). Lakukan Bagian 2 & 3 dulu.

```bash
# Terminal 1 — aplikasi (siapkan data dulu)
php artisan migrate:fresh --seed
php artisan serve                # http://127.0.0.1:8000

# Terminal 2 — driver browser (salah satu)
chromedriver --port=4444
#   atau Selenium Server: java -jar selenium-server.jar standalone

# Terminal 3 — jalankan skenario + ambil SCREENSHOT tiap transisi (untuk Bab 5)
php tests/Selenium/auth_test.php
php tests/Selenium/role_access_test.php
```

> Atur `APP_URL` / `SELENIUM_HOST` lewat environment bila port berbeda.

---

## Bagian 6 — Catatan Temuan (bahan Bab 6 — Hasil & Analisis) — 📋 SEMUA
Beberapa *gap* validasi yang sengaja dibuktikan lewat test (lihat komentar `GAP:`/`TODO:` di file test):
- `production_year` & `quantity` hanya `numeric` (tanpa `min`) → nilai **negatif/desimal lolos** validasi.
- `nation_id` & `category_id` tanpa rule `exists` → id tak valid baru gagal di level FK DB.
- `UserController::update` memakai heuristik `count($request->all()) == 3` untuk membedakan "ubah role saja" vs "edit profil" → rapuh.
- Form `users/create.blade.php` tidak punya input `role`, padahal `store` mewajibkannya.

Temuan ini bagus dijadikan analisis & rekomendasi perbaikan di laporan.
