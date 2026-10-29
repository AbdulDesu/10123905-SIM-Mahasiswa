<?php
declare(strict_types=1);

namespace App\Core;

/** Pesan satu kali tampil (mis. "Data berhasil disimpan") yang bertahan satu redirect. */
final class Flash
{
    public static function set(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function pull(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
