<?php
declare(strict_types=1);


/**
 * Nama: Abdul Richard
 * NIM: 10123905
 * Kelas: IF12-K - Teknik Informatika
 */

/**
 * Bootstrap: dijalankan paling awal oleh public/index.php
 * 1. Menentukan BASE_PATH
 * 2. Mendaftarkan autoloader (App\Core\Auth -> app/Core/Auth.php)
 * 3. Memuat fungsi helper
 * 4. Memulai session (dipakai untuk login, bahasa, CSRF, flash message)
 */

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $file = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require BASE_PATH . '/app/helpers.php';

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();
