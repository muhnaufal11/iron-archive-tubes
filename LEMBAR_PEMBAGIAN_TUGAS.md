# Lembar Pembagian Tugas — Tugas Besar Pengujian & Implementasi Sistem (BBK2MAB2)

**Nama Sistem:** Iron Archive — Database Kendaraan Perang Dunia II (Laravel 12)
**Repository:** _(isi link GitHub)_  **Kelas:** _(isi)_

> Pemetaan teknik (sesuai brief): **White Box → Basis Path → PEST Unit Test** | **Black Box → EP+BVA → PEST Feature Test** | **Black Box → State Transition → PEST Feature + Selenium**.
> Teknik manual (flow graph, V(G), tabel EP/BVA, diagram state) **wajib dikerjakan di laporan lebih dulu** sebelum diimplementasikan ke tools.

---

## Ringkasan Pembagian

| No | Nama / NIM | Fokus Teknik | File Test (tools) | Bab Laporan |
|----|------------|--------------|-------------------|-------------|
| 1 | **Nanda Pratama Sugiarto** (102062400019) — *Ketua* | White Box (Basis Path) Vehicle | `tests/Unit/VehicleIndexBasisPathTest.php` | Bab 1 Latar Belakang + Bab 7 Referensi |
| 2 | **Jiyu Danjiki Ake Heriyanto** (102062400152) | White Box (Basis Path) User + State Transition Role | `tests/Unit/UserManagementBasisPathTest.php`, `tests/Feature/RoleAuthorizationTest.php` | Bab 3 White Box Manual |
| 3 | **Nadhif Maulana Fayzalty** (102062400016) | Black Box EP + BVA | `tests/Feature/VehicleValidationTest.php`, `tests/Feature/MasterDataValidationTest.php` | Bab 2 (bagian EP + BVA) |
| 4 | **MUH NAUFAL RABBANI MARUN** (102062400038) | Black Box EP/BVA Auth + State Transition Auth | `tests/Feature/UserAuthValidationTest.php`, `tests/Feature/AuthStateTransitionTest.php` | Bab 4 Implementasi PEST |
| 5 | **Vincent Imanuel Putra** (102062400026) | UI Test (Selenium) State Transition | `tests/Selenium/auth_test.php`, `tests/Selenium/role_access_test.php` | Bab 5 Implementasi Selenium + Bab 6 Hasil & Analisis |

---

## Rincian Tugas per Anggota

### 1. Nanda Pratama Sugiarto (102062400019) — Ketua Kelompok
**Teknik manual (laporan):**
- Menggambar **flow graph** dan menghitung **V(G) = 4 + 1 = 5** untuk `VehicleController::index()` (cabang: search, nation, category, year).
- Menentukan **5 independent path** dan menyusun tabel test case basis path.

**Implementasi tools:**
- Menulis **PEST Unit Test** `tests/Unit/VehicleIndexBasisPathTest.php` (5 `test()` = 1 path per test).

**Laporan & koordinasi:**
- Menyusun **Bab 1 Latar Belakang** (deskripsi sistem, alasan perlu diuji, fitur utama target pengujian).
- Mengumpulkan **Bab 7 Referensi** (maks 5 jurnal/buku) + finalisasi & penggabungan laporan.

---

### 2. Jiyu Danjiki Ake Heriyanto (102062400152)
**Teknik manual (laporan):**
- Flow graph + **V(G)** untuk `UserController::update()` (≈5, kondisi role-only vs profil + password) dan `UserController::destroy()` (≈3, guard hapus diri sendiri).
- **Diagram State Transition** untuk Otorisasi Role: state *Prajurit (user)* ↔ *Komandan (admin)*.

**Implementasi tools:**
- **PEST Unit Test** `tests/Unit/UserManagementBasisPathTest.php` (update path A–D, destroy path A–C).
- **PEST Feature Test** `tests/Feature/RoleAuthorizationTest.php` (transisi akses 403/200 + naik pangkat).

**Laporan:**
- Menyusun **Bab 3 White Box Manual** (penjelasan Basis Path Testing, rumus V(G), independent path, hasil unit test).

---

### 3. Nadhif Maulana Fayzalty (102062400016)
**Teknik manual (laporan):**
- Membuat **tabel Equivalence Partitioning + Boundary Value Analysis** untuk form Kendaraan: `production_year`/`quantity` (numeric), `image` (max 5000 KB → boundary 5000/5001), field `required`.
- Tabel EP/BVA untuk **Kategori & Negara**: `name` (required, unique, max:255 → boundary 255/256).

**Implementasi tools:**
- **PEST Feature Test** `tests/Feature/VehicleValidationTest.php` dan `tests/Feature/MasterDataValidationTest.php` (1 partisi/boundary = 1 `test()`).

**Laporan:**
- Menyusun **Bab 2 (bagian Black Box Manual — EP + BVA)**: tabel partisi & nilai batas beserta hasil.

---

### 4. MUH NAUFAL RABBANI MARUN (102062400038)
**Teknik manual (laporan):**
- Tabel **EP + BVA** untuk form Personel & Registrasi: `password` (min:8 → boundary **7/8/9**), `email` (format/unique/max:255), `role` (enum `admin,user`), `name` (max:255).
- **Diagram State Transition** untuk Autentikasi: *Guest → Login → Authenticated → Logout*, plus transisi login gagal & registrasi.

**Implementasi tools:**
- **PEST Feature Test** `tests/Feature/UserAuthValidationTest.php` (EP/BVA) dan `tests/Feature/AuthStateTransitionTest.php` (state transition).

**Laporan:**
- Menyusun **Bab 4 Implementasi PEST**: cara setup Pest, struktur folder `tests/Unit` & `tests/Feature`, perintah menjalankan, rekap & screenshot hasil `pest`.

---

### 5. Vincent Imanuel Putra (102062400026)
**Setup & teknik:**
- Menyiapkan **environment Selenium** (ChromeDriver/Selenium Server) + menjalankan aplikasi (`php artisan serve`).
- Bersama Naufal & Jiyu memfinalkan **diagram State Transition** menjadi skenario UI.

**Implementasi tools:**
- **Selenium UI Test** `tests/Selenium/auth_test.php` (transisi login valid/invalid, akses terproteksi) dan `tests/Selenium/role_access_test.php` (akses admin vs user). Sertakan **screenshot tiap transisi**.

**Laporan:**
- Menyusun **Bab 5 Implementasi Selenium** (cara setup, skenario, screenshot).
- Menyusun **Bab 6 Hasil dan Analisis** (rekap seluruh test PEST + Selenium, coverage fitur utama, analisis temuan defect, kesimpulan).

---

## Lembar Tanda Tangan (lampirkan di halaman terakhir laporan)

| No | Nama Lengkap | NIM | Bagian yang Dikerjakan (spesifik) | Tanda Tangan |
|----|--------------|-----|-----------------------------------|--------------|
| 1 | Nanda Pratama Sugiarto | 102062400019 | V(G) & flow graph `VehicleController::index`, 5 PEST Unit Test basis path, Bab 1 & Bab 7, ketua | |
| 2 | Jiyu Danjiki Ake Heriyanto | 102062400152 | V(G) `UserController::update`/`destroy`, diagram state role, PEST Unit + Feature role, Bab 3 | |
| 3 | Nadhif Maulana Fayzalty | 102062400016 | Tabel EP/BVA Vehicle & Master Data, PEST Feature validasi, Bab 2 (EP+BVA) | |
| 4 | MUH NAUFAL RABBANI MARUN | 102062400038 | Tabel EP/BVA Auth + diagram state auth, PEST Feature auth, Bab 4 | |
| 5 | Vincent Imanuel Putra | 102062400026 | Setup & skenario Selenium, 2 file UI test + screenshot, Bab 5 & Bab 6 | |

Surabaya, ____________________

Ketua Kelompok,                          Diketahui Dosen Pengampu,


(Nanda Pratama Sugiarto)                 (____________________________)
        102062400019                          NIP.
