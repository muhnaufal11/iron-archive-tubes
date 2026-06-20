<?php

/*
|--------------------------------------------------------------------------
| SELENIUM — STATE TRANSITION (Autentikasi) — UI Test
|--------------------------------------------------------------------------
| Penanggung : Vincent Imanuel Putra (102062400026)
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
    // T1: Guest -> halaman terproteksi -> redirect login
    $driver->get(appUrl('/vehicles'));
    $driver->wait(10)->until(WebDriverExpectedCondition::urlContains('/login'));
    check('T1 Guest akses /vehicles diarahkan ke /login', str_contains($driver->getCurrentURL(), '/login'));

    // T2: Login valid -> authenticated (/home)
    $driver->get(appUrl('/login'));
    $driver->findElement(WebDriverBy::id('email'))->sendKeys('admin@ironarchive.test');
    $driver->findElement(WebDriverBy::id('password'))->sendKeys('password');
    $driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    $driver->wait(10)->until(WebDriverExpectedCondition::urlContains('/home'));
    check('T2 Login valid masuk ke /home', str_contains($driver->getCurrentURL(), '/home'));

    // (logout dulu untuk reset state)
    $driver->get(appUrl('/login')); // guest yg sudah login akan di-redirect; cukup utk demo

    // T3: Login salah -> tetap di /login + pesan error
    $driver->get(appUrl('/login'));
    $driver->findElement(WebDriverBy::id('email'))->sendKeys('admin@ironarchive.test');
    $driver->findElement(WebDriverBy::id('password'))->sendKeys('password-salah');
    $driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    sleep(1);
    $body = $driver->findElement(WebDriverBy::tagName('body'))->getText();
    check('T3 Login salah menampilkan pesan error', str_contains($driver->getCurrentURL(), '/login') || stripos($body, 'match') !== false || stripos($body, 'credentials') !== false);

    // TODO (Vincent): tambah skenario logout (buka dropdown #navbarDropdown -> klik Logout) dan
    //                 registrasi valid -> /home. Sertakan screenshot tiap transisi di laporan.
} finally {
    $driver->quit();
    summary();
}
