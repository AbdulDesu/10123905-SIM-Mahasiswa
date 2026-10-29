<?php
declare(strict_types=1);

/**
 * Konfigurasi aplikasi. Urutan prioritas (yang terakhir menang):
 *   1. Nilai bawaan di bawah (cocok untuk XAMPP/Laragon: root tanpa password)
 *   2. app/Config/config.local.php  (opsional, TIDAK ikut di-commit ke GitHub)
 *   3. Environment variable DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS  (dipakai Docker)
 */
$config = [
    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'sim_mahasiswa',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
];

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $config = array_replace_recursive($config, require $local);
}

$env = [
    'host' => 'DB_HOST',
    'port' => 'DB_PORT',
    'name' => 'DB_NAME',
    'user' => 'DB_USER',
    'pass' => 'DB_PASS',
];
foreach ($env as $key => $variable) {
    $value = getenv($variable);
    if ($value !== false && $value !== '') {
        $config['db'][$key] = $key === 'port' ? (int) $value : $value;
    }
}

return $config;
