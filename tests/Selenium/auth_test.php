<?php

/*
|--------------------------------------------------------------------------
| SELENIUM — STATE TRANSITION (Autentikasi) — UI Test
|--------------------------------------------------------------------------
| Penanggung : Vincent Imanuel Putra (102062400026)
| Prasyarat  : php artisan migrate:fresh --seed  (akun admin@ironarchive.test / password)
|              php artisan serve  +  chromedriver --port=4444
| Skenario (1 transisi = 1 skenario):
|   T1. Guest akses halaman terproteksi -> diarahkan ke /login
|   T2. Login kredensial valid          -> masuk state authenticated (/home)
|   T3. Login kredensial salah          -> tetap guest + pesan error
*/

require __DIR__ . '/bootstrap.php';

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

$driver = makeDriver();

try {
    // Mulai dari kondisi bersih (state Tamu)
    $driver->manage()->deleteAllCookies();

    // ---- T1: Guest -> halaman terproteksi -> redirect /login ----
    echo "[Info] Memulai T1 (Guest akses /vehicles)...\n";
    $driver->get(appUrl('/vehicles'));
    $driver->wait(10)->until(WebDriverExpectedCondition::urlContains('/login'));
    check('T1 Guest akses /vehicles diarahkan ke /login', str_contains($driver->getCurrentURL(), '/login'));

    // ---- T2: Login valid -> authenticated (/home) ----
    echo "[Info] Memulai T2 (Login valid)...\n";
    $driver->get(appUrl('/login'));
    $driver->findElement(WebDriverBy::id('email'))->sendKeys('admin@ironarchive.test');
    $driver->findElement(WebDriverBy::id('password'))->sendKeys('password');
    $driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    $driver->wait(10)->until(WebDriverExpectedCondition::urlContains('/home'));
    check('T2 Login valid masuk ke /home', str_contains($driver->getCurrentURL(), '/home'));

    // Reset ke state Tamu (hapus cookie sesi) sebelum menguji login gagal
    $driver->manage()->deleteAllCookies();

    // ---- T3: Login salah -> tetap di /login + pesan error ----
    echo "[Info] Memulai T3 (Login invalid)...\n";
    $driver->get(appUrl('/login'));
    $driver->findElement(WebDriverBy::id('email'))->sendKeys('admin@ironarchive.test');
    $driver->findElement(WebDriverBy::id('password'))->sendKeys('password-salah');
    $driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    // Login gagal: Laravel mengembalikan ke halaman /login (field email masih ada)
    $driver->wait(10)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('email')));
    $url = $driver->getCurrentURL();
    check('T3 Login salah tetap di /login (bukan /home)', str_contains($url, '/login') && !str_contains($url, '/home'));

    // TODO (Vincent): tambah skenario logout & registrasi valid bila perlu. Sertakan screenshot tiap transisi.
} catch (\Throwable $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    try { $driver->takeScreenshot(__DIR__ . '/Error_Selenium.png'); echo "Screenshot kegagalan disimpan.\n"; } catch (\Throwable $x) {}
} finally {
    $driver->quit();
    summary();
}
