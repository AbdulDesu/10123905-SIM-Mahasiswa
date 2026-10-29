<?php
declare(strict_types=1);

namespace App\Core;

use App\Repositories\UserRepository;

/** Otentikasi berbasis $_SESSION. */
final class Auth
{
    public static function attempt(UserRepository $users, string $username, string $password): bool
    {
        $user = $users->findByUsername($username);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true); // cegah session fixation
        $_SESSION['user'] = [
            'id'       => (int) $user['id'],
            'username' => $user['username'],
            'nama'     => $user['nama_lengkap'],
        ];
        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /** Dipanggil di awal setiap halaman terproteksi. */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            Flash::set('warning', t('auth.login_required'));
            redirect(url('login'));
        }
    }

    public static function logout(): void
    {
        unset($_SESSION['user'], $_SESSION['csrf_token']);
        session_regenerate_id(true); // bahasa pilihan tetap tersimpan
    }
}
