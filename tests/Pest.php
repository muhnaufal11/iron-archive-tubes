<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi PEST — Tugas Besar Pengujian & Implementasi Sistem
|--------------------------------------------------------------------------
| Semua test (Feature & Unit) di-bootstrap memakai Tests\TestCase agar
| aplikasi Laravel ikut dijalankan, dan RefreshDatabase agar migrasi
| dijalankan otomatis di SQLite :memory: (lihat phpunit.xml).
|
| Pemetaan ke teknik pengujian:
|  - tests/Unit    -> WHITE BOX  (Basis Path Testing, 1 independent path = 1 test)
|  - tests/Feature -> BLACK BOX  (EP/BVA & State Transition, 1 partisi/transisi = 1 test)
*/

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Helper Global
|--------------------------------------------------------------------------
*/

/** Buat user dengan role admin (Komandan). */
function admin(array $attributes = []): \App\Models\User
{
    return \App\Models\User::factory()->create(array_merge(['role' => 'admin'], $attributes));
}

/** Buat user dengan role user biasa (Prajurit). */
function prajurit(array $attributes = []): \App\Models\User
{
    return \App\Models\User::factory()->create(array_merge(['role' => 'user'], $attributes));
}
