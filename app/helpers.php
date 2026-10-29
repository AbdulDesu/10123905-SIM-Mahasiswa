<?php
declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Lang;

/**
 * Nama: Abdul Richard
 * NIM: 10123905
 * Kelas: IF12-K - Teknik Informatika
 */

/** Escape output untuk mencegah XSS. Dipakai di SEMUA data yang dicetak ke HTML. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Terjemahan sesuai bahasa di session. Contoh: t('nav.hello', ['name' => 'Budi']) */
function t(string $key, array $params = []): string
{
    return Lang::get($key, $params);
}

/** Membuat URL internal. Contoh: url('mahasiswa-edit', ['id' => 3]) */
function url(string $page, array $query = []): string
{
    return 'index.php?' . http_build_query(array_merge(['page' => $page], $query));
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/** Ambil input POST bertipe string (array / nilai aneh diabaikan). */
function input(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

/** Panjang teks dalam karakter UTF-8, tanpa ekstensi mbstring. */
function textLength(string $value): int
{
    return (int) preg_match_all('/./su', $value);
}

/** Daftar opsi statis (mis. program studi) dari app/Config/options.php */
function option(string $name): array
{
    static $options = null;
    if ($options === null) {
        $options = require BASE_PATH . '/app/Config/options.php';
    }
    return $options[$name] ?? [];
}

function csrf_field(): string
{
    return Csrf::field();
}

function fieldError(array $errors, string $name): string
{
    return isset($errors[$name])
        ? '<p class="field-error">' . e($errors[$name]) . '</p>'
        : '';
}

/**
 * URL kembali (halaman sebelumnya) yang aman: hanya jika host sama,
 * sehingga tidak bisa dipakai untuk open-redirect.
 */
function safeBackUrl(string $fallback): string
{
    $parts = parse_url($_SERVER['HTTP_REFERER'] ?? '');
    $host  = explode(':', $_SERVER['HTTP_HOST'] ?? '')[0];
    $path  = is_array($parts) ? ($parts['path'] ?? '') : '';

    if (is_array($parts)
        && ($parts['host'] ?? '') === $host
        && $path !== ''
        && $path[0] === '/'
        && strpos($path, '//') !== 0
    ) {
        return $path . (isset($parts['query']) ? '?' . $parts['query'] : '');
    }
    return $fallback;
}
