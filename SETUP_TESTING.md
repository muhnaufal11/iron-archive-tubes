# Setup Pengujian — Iron Archive (PEST + Selenium)

Panduan menjalankan lingkungan pengujian untuk Tugas Besar Pengujian & Implementasi Sistem.

## 1. Instalasi Awal (sekali saja)

```bash
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate

# Dependency pengujian (sudah ditambahkan ke composer.json --dev):
#   pestphp/pest, pestphp/pest-plugin-laravel, php-webdriver/webdriver
# Jika belum terpasang, jalankan:
composer require pestphp/pest pestphp/pest-plugin-laravel php-webdriver/webdriver --dev --with-all-dependencies
```

## 2. Database aplikasi (untuk dijalankan manual / Selenium)

```bash
php artisan migrate:fresh --seed
```

Seeder membuat akun default:

| Role  | Email                    | Password   |
|-------|--------------------------|------------|
| admin | `admin@ironarchive.test` | `password` |
| user  | `user@ironarchive.test`  | `password` |

Plus 4 negara, 4 kategori, dan 2 kendaraan contoh (Tiger I, T-34).

## 3. Menjalankan Test PEST (Unit + Feature)

Test memakai SQLite `:memory:` + `RefreshDatabase` (lihat `phpunit.xml` & `tests/Pest.php`), jadi **tidak** menyentuh database aslimu.

```bash
./vendor/bin/pest                 # semua test
./vendor/bin/pest tests/Unit      # WHITE BOX (Basis Path)
./vendor/bin/pest tests/Feature   # BLACK BOX (EP/BVA + State Transition)
./vendor/bin/pest --coverage      # butuh Xdebug/PCOV — untuk laporan coverage
```

### Pemetaan folder ke teknik
| Folder | Teknik manual | Tool |
|--------|---------------|------|
| `tests/Unit/`    | Basis Path Testing (flow graph, V(G), independent path) | PEST Unit Test |
| `tests/Feature/` | Equivalence Partitioning + BVA, State Transition | PEST Feature Test |

## 4. Menjalankan Test Selenium (UI / State Transition)

Selenium butuh **3 proses** berjalan bersamaan:

```bash
# Terminal 1 — aplikasi (siapkan data dulu)
php artisan migrate:fresh --seed
php artisan serve                 # http://127.0.0.1:8000

# Terminal 2 — driver browser (salah satu)
chromedriver --port=4444
#   atau Selenium Server:  java -jar selenium-server.jar standalone

# Terminal 3 — jalankan skenario
php tests/Selenium/auth_test.php
php tests/Selenium/role_access_test.php
```

> Atur `APP_URL` / `SELENIUM_HOST` lewat environment bila port berbeda.
> Ambil **screenshot tiap transisi** untuk dilampirkan di Bab 5 laporan.

## 5. Catatan Temuan (bahan Bab 6 — Hasil & Analisis)

Beberapa _gap_ validasi yang sengaja dibuktikan lewat test (lihat komentar `GAP:`/`TODO:` di file test):
- `production_year` & `quantity` hanya `numeric` (tanpa `min`) → nilai **negatif/desimal lolos** validasi.
- `nation_id` & `category_id` tanpa rule `exists` → id tak valid baru gagal di level FK DB.
- `UserController::update` memakai heuristik `count($request->all()) == 3` untuk membedakan "ubah role saja" vs "edit profil" → rapuh.
- Form `users/create.blade.php` tidak punya input `role`, padahal `store` mewajibkannya.

Temuan ini bagus dijadikan analisis & rekomendasi perbaikan di laporan.
