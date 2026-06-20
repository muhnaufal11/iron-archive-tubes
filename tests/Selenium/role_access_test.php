<?php

/*
|--------------------------------------------------------------------------
| SELENIUM — STATE TRANSITION (Otorisasi Role) — UI Test
|--------------------------------------------------------------------------
| Penanggung : Vincent Imanuel Putra (102062400026)
| Skenario:
|   T4. Login sebagai user biasa -> akses /users -> tampil "AKSES DITOLAK" (403)
|   T5. Login sebagai admin      -> akses /users -> tampil "MANAJEMEN PERSONEL"
*/

require __DIR__ . '/bootstrap.php';

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

function loginAs(\Facebook\WebDriver\Remote\RemoteWebDriver $driver, string $email, string $password): void
{
    $driver->get(appUrl('/login'));
    $driver->findElement(WebDriverBy::id('email'))->clear()->sendKeys($email);
    $driver->findElement(WebDriverBy::id('password'))->clear()->sendKeys($password);
    $driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    $driver->wait(10)->until(WebDriverExpectedCondition::urlContains('/home'));
}

$driver = makeDriver();

try {
    // T4: user biasa -> /users -> 403
    loginAs($driver, 'user@ironarchive.test', 'password');
    $driver->get(appUrl('/users'));
    $body = $driver->findElement(WebDriverBy::tagName('body'))->getText();
    check('T4 User biasa ditolak di /users (AKSES DITOLAK)', stripos($body, 'DITOLAK') !== false || stripos($body, '403') !== false);

    // T5: admin -> /users -> boleh
    loginAs($driver, 'admin@ironarchive.test', 'password');
    $driver->get(appUrl('/users'));
    $body = $driver->findElement(WebDriverBy::tagName('body'))->getText();
    check('T5 Admin boleh akses manajemen personel', stripos($body, 'MANAJEMEN PERSONEL') !== false);
} finally {
    $driver->quit();
    summary();
}
