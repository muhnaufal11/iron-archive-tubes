<?php

/*
|--------------------------------------------------------------------------
| Bootstrap Selenium (php-webdriver)
|--------------------------------------------------------------------------
| Helper bersama untuk semua skenario UI Test (State Transition).
| Butuh paket: composer require php-webdriver/webdriver --dev
|
| Cara pakai (3 terminal):
|   1) php artisan migrate:fresh --seed     (siapkan akun admin/user + data)
|   2) php artisan serve                    (APP_URL=http://127.0.0.1:8000)
|   3) selenium-server / chromedriver       (default port 4444 utk Selenium Grid)
| Lalu: php tests/Selenium/auth_test.php
*/

require __DIR__ . '/../../vendor/autoload.php';

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

function makeDriver(): RemoteWebDriver
{
    $host = getenv('SELENIUM_HOST') ?: 'http://localhost:4444';
    return RemoteWebDriver::create($host, DesiredCapabilities::chrome());
}

function appUrl(string $path = ''): string
{
    $base = getenv('APP_URL') ?: 'http://127.0.0.1:8000';
    return rtrim($base, '/') . $path;
}

$GLOBALS['__pass'] = 0;
$GLOBALS['__fail'] = 0;

function check(string $label, bool $cond): void
{
    if ($cond) {
        $GLOBALS['__pass']++;
        echo "  [PASS] {$label}\n";
    } else {
        $GLOBALS['__fail']++;
        echo "  [FAIL] {$label}\n";
    }
}

function summary(): void
{
    echo "\n=== HASIL SELENIUM: {$GLOBALS['__pass']} PASS / {$GLOBALS['__fail']} FAIL ===\n";
    exit($GLOBALS['__fail'] > 0 ? 1 : 0);
}
