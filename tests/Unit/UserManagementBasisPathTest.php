<?php

/*
|--------------------------------------------------------------------------
| WHITE BOX — BASIS PATH TESTING
|--------------------------------------------------------------------------
| Method     : UserController::update()  (V(G) ~5)  & UserController::destroy() (V(G) ~3)
| Penanggung : Jiyu Danjiki Ake Heriyanto (102062400152)
|
| Independent path update():
|   A. bukan admin           -> abort 403 (checkAdmin)
|   B. ubah role saja        -> count(all)==3, validasi role, update role
|   C. edit profil, no pass  -> if(filled password)=FALSE
|   D. edit profil, with pass-> if(filled password)=TRUE (hash)
| Independent path destroy():
|   A. bukan admin           -> 403
|   B. hapus diri sendiri    -> ditolak
|   C. hapus user lain       -> terhapus
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Hash;

// ---------------- UserController::update() ----------------

// PATH B: cabang "ubah role saja" (request berisi _token, _method, role => count == 3)
test('update path-B: ubah role saja mengubah pangkat personel', function () {
    $admin = admin();
    $target = prajurit();

    $this->actingAs($admin)->post(route('users.update', $target), [
        '_method' => 'PUT',
        '_token' => 'dummy',
        'role' => 'admin',
    ])->assertRedirect();

    expect($target->fresh()->role)->toBe('admin');
});

// PATH C: edit profil lengkap TANPA password -> nama & email berubah, password tetap
test('update path-C: edit profil tanpa password tidak mengubah password', function () {
    $admin = admin();
    $target = prajurit(['name' => 'Lama', 'email' => 'lama@test.com']);
    $oldPass = $target->password;

    $this->actingAs($admin)->put(route('users.update', $target), [
        'name' => 'Baru',
        'email' => 'baru@test.com',
    ])->assertRedirect(route('users.index'));

    $target->refresh();
    expect($target->name)->toBe('Baru');
    expect($target->email)->toBe('baru@test.com');
    expect($target->password)->toBe($oldPass);
});

// PATH D: edit profil lengkap DENGAN password -> password di-hash ulang
test('update path-D: edit profil dengan password mengganti password', function () {
    $admin = admin();
    $target = prajurit();
    $oldPass = $target->password;

    $this->actingAs($admin)->put(route('users.update', $target), [
        'name' => 'Tetap',
        'email' => 'tetap@test.com',
        'password' => 'passwordbaru',
        'password_confirmation' => 'passwordbaru',
    ])->assertRedirect(route('users.index'));

    $target->refresh();
    expect($target->password)->not->toBe($oldPass);
    expect(Hash::check('passwordbaru', $target->password))->toBeTrue();
});

// PATH A: bukan admin -> 403
test('update path-A: user biasa ditolak (403)', function () {
    $biasa = prajurit();
    $target = prajurit();

    $this->actingAs($biasa)->put(route('users.update', $target), [
        'name' => 'X',
        'email' => 'x@test.com',
    ])->assertForbidden();
});

// ---------------- UserController::destroy() ----------------

// PATH C: admin hapus user lain -> terhapus
test('destroy path-C: admin hapus user lain berhasil', function () {
    $admin = admin();
    $target = prajurit();

    $this->actingAs($admin)->delete(route('users.destroy', $target))
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseMissing('users', ['id' => $target->id]);
});

// PATH B: admin hapus diri sendiri -> ditolak (data tetap ada)
test('destroy path-B: admin tidak bisa menghapus diri sendiri', function () {
    $admin = admin();

    $this->actingAs($admin)->delete(route('users.destroy', $admin))
        ->assertRedirect();

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
