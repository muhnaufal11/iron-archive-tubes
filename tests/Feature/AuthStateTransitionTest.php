<?php

/*
|--------------------------------------------------------------------------
| BLACK BOX — STATE TRANSITION TESTING (Autentikasi)
|--------------------------------------------------------------------------
| Diagram state : Guest -> (login valid) -> Authenticated -> (logout) -> Guest
|                 Guest -> (login invalid) -> Guest (+error)
|                 Guest -> (register valid) -> Authenticated
| Penanggung    : MUH NAUFAL RABBANI MARUN (102062400038)
| Catatan       : versi PEST Feature. Versi UI (Selenium) ada di tests/Selenium.
|--------------------------------------------------------------------------
*/

use App\Models\User;

// TRANSISI: Guest mengakses halaman terproteksi -> diarahkan ke login
test('transisi: guest akses halaman terproteksi diarahkan ke login', function () {
    $this->get(route('vehicles.index'))
        ->assertRedirect(route('login'));
});

// TRANSISI: Guest -> login kredensial benar -> Authenticated (redirect /home)
test('transisi: login valid memindahkan ke state authenticated', function () {
    $user = User::factory()->create([
        'email' => 'login@test.com',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('login'), [
        'email' => 'login@test.com',
        'password' => 'password',
    ])->assertRedirect('/home');

    $this->assertAuthenticatedAs($user);
});

// TRANSISI: Guest -> login kredensial salah -> tetap Guest (+error)
test('transisi: login invalid tetap di state guest', function () {
    User::factory()->create([
        'email' => 'login@test.com',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('login'), [
        'email' => 'login@test.com',
        'password' => 'salah-password',
    ])->assertSessionHasErrors();

    $this->assertGuest();
});

// TRANSISI: Authenticated -> logout -> Guest
test('transisi: logout mengembalikan ke state guest', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect();

    $this->assertGuest();
});

// TRANSISI: Guest -> register valid -> Authenticated
test('transisi: registrasi valid langsung authenticated', function () {
    $this->post(route('register'), [
        'name' => 'User Baru',
        'email' => 'baru@test.com',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
    ])->assertRedirect('/home');

    $this->assertAuthenticated();
});
