<?php
declare(strict_types=1);

namespace App\Core;

/** Merender file view ke dalam layout. */
final class View
{
    public static function render(string $viewFile, array $viewData = []): void
    {
        extract($viewData, EXTR_SKIP);

        ob_start();
        require BASE_PATH . '/app/Views/' . $viewFile . '.php';
        $content = ob_get_clean();

        require BASE_PATH . '/app/Views/layout.php';
    }
}
