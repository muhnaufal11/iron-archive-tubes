<?php

/*
|--------------------------------------------------------------------------
| BLACK BOX — EQUIVALENCE PARTITIONING + BOUNDARY VALUE ANALYSIS
|--------------------------------------------------------------------------
| Target     : VehicleController::store() (validasi form kendaraan)
| Penanggung : Nadhif Maulana Fayzalty (102062400016)
| Aturan     : name/battles/description required, nation_id/category_id required,
|              production_year & quantity required|numeric, image image|file|max:5000
|--------------------------------------------------------------------------
*/

use App\Models\Category;
use App\Models\Nation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function vehiclePayload(array $override = []): array
{
    return array_merge([
        'name' => 'Panzer IV',
        'nation_id' => Nation::factory()->create()->id,
        'category_id' => Category::factory()->create()->id,
        'production_year' => 1943,
        'quantity' => 100,
        'battles' => 'Normandy',
        'description' => 'Tank medium Jerman.',
    ], $override);
}

beforeEach(function () {
    $this->admin = admin();
});

// EP VALID: semua field benar -> tersimpan
test('EP valid: data kendaraan lengkap tersimpan', function () {
    $this->actingAs($this->admin)
        ->post(route('vehicles.store'), vehiclePayload(['name' => 'Panzer IV']))
        ->assertRedirect(route('vehicles.index'));

    $this->assertDatabaseHas('vehicles', ['name' => 'Panzer IV']);
});

// EP INVALID: name kosong -> error required
test('EP invalid: name kosong ditolak', function () {
    $this->actingAs($this->admin)
        ->post(route('vehicles.store'), vehiclePayload(['name' => '']))
        ->assertSessionHasErrors('name');
});

// EP INVALID: production_year bukan angka -> error numeric
test('EP invalid: production_year berisi huruf ditolak', function () {
    $this->actingAs($this->admin)
        ->post(route('vehicles.store'), vehiclePayload(['production_year' => 'abcd']))
        ->assertSessionHasErrors('production_year');
});

// EP INVALID: quantity bukan angka -> error numeric
test('EP invalid: quantity berisi huruf ditolak', function () {
    $this->actingAs($this->admin)
        ->post(route('vehicles.store'), vehiclePayload(['quantity' => 'banyak']))
        ->assertSessionHasErrors('quantity');
});

// BVA: image tepat 5000 KB (batas atas) -> diterima
// Catatan: pakai create() bukan image() agar tidak butuh ekstensi GD.
test('BVA: image 5000 KB (batas atas) diterima', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('foto.jpg', 5000, 'image/jpeg');

    $this->actingAs($this->admin)
        ->post(route('vehicles.store'), vehiclePayload(['name' => 'WithImg', 'image' => $file]))
        ->assertSessionDoesntHaveErrors('image');
});

// BVA: image 5001 KB (batas atas + 1) -> ditolak
test('BVA: image 5001 KB (melebihi batas) ditolak', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('foto.jpg', 5001, 'image/jpeg');

    $this->actingAs($this->admin)
        ->post(route('vehicles.store'), vehiclePayload(['name' => 'TooBig', 'image' => $file]))
        ->assertSessionHasErrors('image');
});

// TEMUAN DEFECT (untuk Bab Hasil & Analisis):
// rule 'numeric' tanpa 'min' membuat quantity negatif LOLOS validasi.
test('GAP: quantity negatif lolos validasi (bukti defect)', function () {
    $this->actingAs($this->admin)
        ->post(route('vehicles.store'), vehiclePayload(['name' => 'NegQty', 'quantity' => -50]))
        ->assertSessionDoesntHaveErrors('quantity');

    $this->assertDatabaseHas('vehicles', ['name' => 'NegQty', 'quantity' => -50]);
});

// TODO (Nadhif): tambahkan partisi name max:255 (255 valid / 256 invalid) bila rule max ditambahkan,
// dan partisi image non-image (mis. .pdf) -> error rule image.
