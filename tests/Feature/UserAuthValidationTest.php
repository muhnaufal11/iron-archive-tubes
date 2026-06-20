<?php

/*
|--------------------------------------------------------------------------
| BLACK BOX — EQUIVALENCE PARTITIONING + BOUNDARY VALUE ANALYSIS
|--------------------------------------------------------------------------
| Target     : UserController::store() & RegisterController (form personel & registrasi)
| Penanggung : MUH NAUFAL RABBANI MARUN (102062400038)
| Aturan     : name max:255, email email|max:255|unique, password min:8|confirmed,
|              role in:admin,user
|--------------------------------------------------------------------------
*/

use App\Models\User;

function userPayload(array $override = []): array
{
    return array_merge([
        'name' => 'Personel Baru',
        'email' => 'personel@test.com',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
        'role' => 'user',
    ], $override);
}

// ---------------- UserController::store() (admin-only) ----------------

test('EP valid: admin merekrut personel baru', function () {
    $this->actingAs(admin())
        ->post(route('users.store'), userPayload(['email' => 'valid@test.com']))
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', ['email' => 'valid@test.com']);
});

test('EP invalid: format email salah ditolak', function () {
    $this->actingAs(admin())
        ->post(route('users.store'), userPayload(['email' => 'bukan-email']))
        ->assertSessionHasErrors('email');
});

test('EP invalid: email duplikat ditolak (unique)', function () {
    User::factory()->create(['email' => 'dipakai@test.com']);

    $this->actingAs(admin())
        ->post(route('users.store'), userPayload(['email' => 'dipakai@test.com']))
        ->assertSessionHasErrors('email');
});

test('EP invalid: role di luar enum ditolak', function () {
    $this->actingAs(admin())
        ->post(route('users.store'), userPayload(['email' => 'role@test.com', 'role' => 'superadmin']))
        ->assertSessionHasErrors('role');
});

// BVA password: 7 karatker (batas bawah - 1) -> ditolak (min:8)
test('BVA: password 7 karakter ditolak', function () {
    $this->actingAs(admin())
        ->post(route('users.store'), userPayload([
            'email' => 'p7@test.com',
            'password' => 'rahasi7',
            'password_confirmation' => 'rahasi7',
        ]))
        ->assertSessionHasErrors('password');
});

// BVA password: 8 karakter (batas bawah) -> diterima
test('BVA: password 8 karakter diterima', function () {
    $this->actingAs(admin())
        ->post(route('users.store'), userPayload([
            'email' => 'p8@test.com',
            'password' => 'rahasia8',
            'password_confirmation' => 'rahasia8',
        ]))
        ->assertSessionDoesntHaveErrors('password');
});

test('EP invalid: konfirmasi password tidak cocok ditolak', function () {
    $this->actingAs(admin())
        ->post(route('users.store'), userPayload([
            'email' => 'conf@test.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'beda12345',
        ]))
        ->assertSessionHasErrors('password');
});

// ---------------- RegisterController (registrasi publik) ----------------

test('EP valid: registrasi publik berhasil & user tersimpan', function () {
    $this->post(route('register'), [
        'name' => 'Pendaftar',
        'email' => 'daftar@test.com',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
    ])->assertRedirect();

    $this->assertDatabaseHas('users', ['email' => 'daftar@test.com']);
});

test('BVA: registrasi dengan password 7 karakter ditolak', function () {
    $this->post(route('register'), [
        'name' => 'Pendaftar',
        'email' => 'daftar2@test.com',
        'password' => 'rahasi7',
        'password_confirmation' => 'rahasi7',
    ])->assertSessionHasErrors('password');
});
