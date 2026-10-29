<?php
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Lang;

$flash = Flash::pull();
?>
<!DOCTYPE html>
<html lang="<?= e(Lang::current()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> - <?= e(t('app.name')) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="topbar__inner">
        <a class="brand" href="<?= e(url('mahasiswa')) ?>"><?= e(t('app.name')) ?></a>

        <nav class="topbar__nav" aria-label="<?= e(t('lang.label')) ?>">
            <span class="lang">
                <?php foreach (Lang::SUPPORTED as $code): ?>
                    <a href="<?= e(url('lang', ['set' => $code])) ?>"
                       class="<?= $code === Lang::current() ? 'is-active' : '' ?>"
                       hreflang="<?= e($code) ?>"><?= e(strtoupper($code)) ?></a>
                <?php endforeach; ?>
            </span>

            <?php if (Auth::check()): ?>
                <span class="user"><?= e(t('nav.hello', ['name' => Auth::user()['nama']])) ?></span>
                <form method="post" action="<?= e(url('logout')) ?>" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn--ghost"><?= e(t('nav.logout')) ?></button>
                </form>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="page">
    <?php if ($flash !== null): ?>
        <div class="alert alert--<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <?= $content ?>
</main>
</body>
</html>
