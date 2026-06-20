<?php

/*
|--------------------------------------------------------------------------
| BLACK BOX — EQUIVALENCE PARTITIONING + BOUNDARY VALUE ANALYSIS
|--------------------------------------------------------------------------
| Target     : CategoryController::store() & NationController::store()
| Penanggung : Nadhif Maulana Fayzalty (102062400016)
| Aturan     : name required|string|max:255|unique ; slug/flag nullable
|--------------------------------------------------------------------------
*/

use App\Models\Category;
use App\Models\Nation;

beforeEach(function () {
    $this->admin = admin();
});

// ---------------- Category ----------------

test('EP valid: kategori baru tersimpan', function () {
    $this->actingAs($this->admin)
        ->post(route('categories.store'), ['name' => 'Tank Destroyer'])
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', ['name' => 'Tank Destroyer']);
});

test('EP invalid: nama kategori kosong ditolak', function () {
    $this->actingAs($this->admin)
        ->post(route('categories.store'), ['name' => ''])
        ->assertSessionHasErrors('name');
});

test('EP invalid: nama kategori duplikat ditolak (unique)', function () {
    Category::factory()->create(['name' => 'Heavy Tank']);

    $this->actingAs($this->admin)
        ->post(route('categories.store'), ['name' => 'Heavy Tank'])
        ->assertSessionHasErrors('name');
});

// BVA: nama tepat 255 karakter (batas atas) -> diterima
test('BVA: nama kategori 255 karakter diterima', function () {
    $this->actingAs($this->admin)
        ->post(route('categories.store'), ['name' => str_repeat('A', 255)])
        ->assertSessionDoesntHaveErrors('name');
});

// BVA: nama 256 karakter (batas atas + 1) -> ditolak
test('BVA: nama kategori 256 karakter ditolak', function () {
    $this->actingAs($this->admin)
        ->post(route('categories.store'), ['name' => str_repeat('A', 256)])
        ->assertSessionHasErrors('name');
});

// ---------------- Nation ----------------

test('EP valid: negara baru tersimpan', function () {
    $this->actingAs($this->admin)
        ->post(route('nations.store'), ['name' => 'United Kingdom'])
        ->assertRedirect(route('nations.index'));

    $this->assertDatabaseHas('nations', ['name' => 'United Kingdom']);
});

test('EP invalid: nama negara duplikat ditolak (unique)', function () {
    Nation::factory()->create(['name' => 'France']);

    $this->actingAs($this->admin)
        ->post(route('nations.store'), ['name' => 'France'])
        ->assertSessionHasErrors('name');
});
