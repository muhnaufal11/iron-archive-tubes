<?php

/*
|--------------------------------------------------------------------------
| BLACK BOX — STATE TRANSITION TESTING (Otorisasi Role)
|--------------------------------------------------------------------------
| Diagram state : Prajurit (user) <-> Komandan (admin)
|   - user  akses panel admin  -> 403 (AKSES DITOLAK)
|   - admin akses panel admin  -> 200 (boleh)
|   - admin ubah role user->admin -> personel naik pangkat (transisi state)
| Penanggung    : Jiyu Danjiki Ake Heriyanto (102062400152)
|--------------------------------------------------------------------------
*/

// TRANSISI/GUARD: user biasa ditolak masuk manajemen personel
test('user biasa ditolak mengakses manajemen personel (403)', function () {
    $this->actingAs(prajurit())
        ->get(route('users.index'))
        ->assertForbidden();
});

// admin boleh masuk manajemen personel
test('admin boleh mengakses manajemen personel (200)', function () {
    $this->actingAs(admin())
        ->get(route('users.index'))
        ->assertOk();
});

// user biasa ditolak mengakses manajemen kategori
test('user biasa ditolak mengakses manajemen kategori (403)', function () {
    $this->actingAs(prajurit())
        ->get(route('categories.index'))
        ->assertForbidden();
});

// admin boleh mengakses manajemen kategori
test('admin boleh mengakses manajemen kategori (200)', function () {
    $this->actingAs(admin())
        ->get(route('categories.index'))
        ->assertOk();
});

// TRANSISI: admin menaikkan pangkat user -> admin (role berubah)
test('transisi: admin menaikkan pangkat personel menjadi admin', function () {
    $admin = admin();
    $target = prajurit();

    expect($target->role)->toBe('user');

    $this->actingAs($admin)->post(route('users.update', $target), [
        '_method' => 'PUT',
        '_token' => 'dummy',
        'role' => 'admin',
    ])->assertRedirect();

    expect($target->fresh()->role)->toBe('admin');
});
