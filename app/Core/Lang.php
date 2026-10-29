<?php
declare(strict_types=1);

namespace App\Core;

/** Dual bahasa (id / en). Pilihan bahasa disimpan di $_SESSION['lang']. */
final class Lang
{
    public const SUPPORTED    = ['id', 'en'];
    public const DEFAULT_LANG = 'id';

    private static array $cache = [];

    public static function current(): string
    {
        $lang = $_SESSION['lang'] ?? self::DEFAULT_LANG;
        return in_array($lang, self::SUPPORTED, true) ? $lang : self::DEFAULT_LANG;
    }

    public static function set(string $lang): void
    {
        if (in_array($lang, self::SUPPORTED, true)) {
            $_SESSION['lang'] = $lang;
        }
    }

    /** Ambil teks. Placeholder :nama diganti dari $params. Jika key tidak ada, key dikembalikan. */
    public static function get(string $key, array $params = []): string
    {
        $lang = self::current();

        if (!isset(self::$cache[$lang])) {
            self::$cache[$lang] = require BASE_PATH . '/lang/' . $lang . '.php';
        }

        $text = self::$cache[$lang][$key] ?? $key;
        foreach ($params as $name => $value) {
            $text = str_replace(':' . $name, (string) $value, $text);
        }
        return $text;
    }
}
