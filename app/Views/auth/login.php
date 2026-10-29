<section class="card card--narrow">
    <h1><?= e(t('auth.heading')) ?></h1>

    <?php if ($error !== null): ?>
        <div class="alert alert--error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('login')) ?>" novalidate>
        <?= csrf_field() ?>

        <div class="field">
            <label for="username"><?= e(t('auth.username')) ?></label>
            <input type="text" id="username" name="username" value="<?= e($username) ?>"
                   autocomplete="username" autofocus>
        </div>

        <div class="field">
            <label for="password"><?= e(t('auth.password')) ?></label>
            <input type="password" id="password" name="password" autocomplete="current-password">
        </div>

        <button type="submit" class="btn btn--primary btn--block"><?= e(t('auth.submit')) ?></button>
    </form>

    <p class="note"><?= e(t('auth.hint')) ?></p>
</section>
