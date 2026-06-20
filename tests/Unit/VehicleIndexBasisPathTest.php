<?php

/*
|--------------------------------------------------------------------------
| WHITE BOX — BASIS PATH TESTING
|--------------------------------------------------------------------------
| Method      : VehicleController::index()  (app/Http/Controllers/VehicleController.php:19)
| Cyclomatic  : V(G) = 4 keputusan (search, nation, category, year) + 1 = 5
| Independent : 5 path -> 5 test() (tanpa filter, search, nation, category, year)
| Penanggung  : Nanda Pratama Sugiarto (102062400019)
|--------------------------------------------------------------------------
*/

use App\Models\Category;
use App\Models\Nation;
use App\Models\Vehicle;

beforeEach(function () {
    $this->user = prajurit();
});

// PATH 1: semua if = FALSE (tanpa filter) -> semua kendaraan tampil
test('path-1: index tanpa filter menampilkan semua kendaraan', function () {
    Vehicle::factory()->create(['name' => 'Tiger I']);
    Vehicle::factory()->create(['name' => 'Sherman']);

    $this->actingAs($this->user)
        ->get(route('vehicles.index'))
        ->assertOk()
        ->assertSee('Tiger I')
        ->assertSee('Sherman');
});

// PATH 2: if(filled('search')) = TRUE
test('path-2: filter search hanya menampilkan yang cocok', function () {
    Vehicle::factory()->create(['name' => 'Tiger I']);
    Vehicle::factory()->create(['name' => 'Sherman']);

    $this->actingAs($this->user)
        ->get(route('vehicles.index', ['search' => 'Tiger']))
        ->assertOk()
        ->assertSee('Tiger I')
        ->assertDontSee('Sherman');
});

// PATH 3: if(filled('nation')) = TRUE
test('path-3: filter negara hanya menampilkan kendaraan negara tsb', function () {
    $jerman = Nation::factory()->create();
    $usa = Nation::factory()->create();
    Vehicle::factory()->create(['name' => 'Tiger I', 'nation_id' => $jerman->id]);
    Vehicle::factory()->create(['name' => 'Sherman', 'nation_id' => $usa->id]);

    $this->actingAs($this->user)
        ->get(route('vehicles.index', ['nation' => $jerman->id]))
        ->assertOk()
        ->assertSee('Tiger I')
        ->assertDontSee('Sherman');
});

// PATH 4: if(filled('category')) = TRUE
test('path-4: filter kategori hanya menampilkan kategori tsb', function () {
    $heavy = Category::factory()->create();
    $medium = Category::factory()->create();
    Vehicle::factory()->create(['name' => 'Tiger I', 'category_id' => $heavy->id]);
    Vehicle::factory()->create(['name' => 'T-34', 'category_id' => $medium->id]);

    $this->actingAs($this->user)
        ->get(route('vehicles.index', ['category' => $heavy->id]))
        ->assertOk()
        ->assertSee('Tiger I')
        ->assertDontSee('T-34');
});

// PATH 5: if(filled('year')) = TRUE
test('path-5: filter tahun hanya menampilkan tahun tsb', function () {
    Vehicle::factory()->create(['name' => 'Tiger I', 'production_year' => 1942]);
    Vehicle::factory()->create(['name' => 'Maus', 'production_year' => 1944]);

    $this->actingAs($this->user)
        ->get(route('vehicles.index', ['year' => 1942]))
        ->assertOk()
        ->assertSee('Tiger I')
        ->assertDontSee('Maus');
});

// PATH KOMBINASI 1: search = TRUE, nation = TRUE
test('path-kombinasi-1: filter search dan negara secara bersamaan', function () {
    $jerman = Nation::factory()->create();
    $usa = Nation::factory()->create();
    
    // Kendaraan 1: cocok search dan negara
    Vehicle::factory()->create(['name' => 'Tiger I', 'nation_id' => $jerman->id]);
    // Kendaraan 2: cocok search tapi beda negara
    Vehicle::factory()->create(['name' => 'Tiger II', 'nation_id' => $usa->id]);
    // Kendaraan 3: beda search tapi cocok negara
    Vehicle::factory()->create(['name' => 'Panzer IV', 'nation_id' => $jerman->id]);

    $this->actingAs($this->user)
        ->get(route('vehicles.index', ['search' => 'Tiger', 'nation' => $jerman->id]))
        ->assertOk()
        ->assertSee('Tiger I')
        ->assertDontSee('Tiger II')
        ->assertDontSee('Panzer IV');
});

// PATH KOMBINASI 2: search = TRUE, nation = TRUE, category = TRUE, year = TRUE (semua filter)
test('path-kombinasi-2: semua filter (search, negara, kategori, tahun) aktif', function () {
    $jerman = Nation::factory()->create();
    $usa = Nation::factory()->create();
    $heavy = Category::factory()->create();
    $medium = Category::factory()->create();

    // Kendaraan cocok semua filter
    Vehicle::factory()->create([
        'name' => 'Tiger I',
        'nation_id' => $jerman->id,
        'category_id' => $heavy->id,
        'production_year' => 1942
    ]);

    // Kendaraan tidak cocok kategori dan negara
    Vehicle::factory()->create([
        'name' => 'Sherman',
        'nation_id' => $usa->id,
        'category_id' => $medium->id,
        'production_year' => 1942
    ]);

    $this->actingAs($this->user)
        ->get(route('vehicles.index', [
            'search' => 'Tiger',
            'nation' => $jerman->id,
            'category' => $heavy->id,
            'year' => 1942
        ]))
        ->assertOk()
        ->assertSee('Tiger I')
        ->assertDontSee('Sherman');
});

