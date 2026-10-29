<?php
/** Form dipakai bersama untuk Create dan Update. */
$isEdit = $mode === 'edit';
$action = $isEdit ? url('mahasiswa-edit', ['id' => $id]) : url('mahasiswa-create');

$v   = static function (string $key) use ($values): string {
    return (string) ($values[$key] ?? '');
};
$cls = static function (string $key) use ($errors): string {
    return isset($errors[$key]) ? ' field--error' : '';
};
?>
<section class="card">
    <h1><?= e($title) ?></h1>

    <?php if ($errors !== []): ?>
        <div class="alert alert--error" role="alert"><?= e(t('mhs.fix_errors')) ?></div>
    <?php endif; ?>

    <!-- novalidate: validasi dilakukan di server (PHP) agar mudah didemonstrasikan -->
    <form method="post" action="<?= e($action) ?>" novalidate>
        <?= csrf_field() ?>

        <div class="grid">
            <div class="field<?= $cls('nim') ?>">
                <label for="nim"><?= e(t('f.nim')) ?></label>
                <input type="text" id="nim" name="nim" value="<?= e($v('nim')) ?>" inputmode="numeric" maxlength="12" placeholder="2023110001">
                <?= fieldError($errors, 'nim') ?>
            </div>

            <div class="field<?= $cls('nama') ?>">
                <label for="nama"><?= e(t('f.nama')) ?></label>
                <input type="text" id="nama" name="nama" value="<?= e($v('nama')) ?>" maxlength="100" autocomplete="name">
                <?= fieldError($errors, 'nama') ?>
            </div>

            <div class="field<?= $cls('email') ?>">
                <label for="email"><?= e(t('f.email')) ?></label>
                <input type="email" id="email" name="email" value="<?= e($v('email')) ?>" maxlength="100" autocomplete="email">
                <?= fieldError($errors, 'email') ?>
            </div>

            <div class="field<?= $cls('telepon') ?>">
                <label for="telepon"><?= e(t('f.telepon')) ?></label>
                <input type="tel" id="telepon" name="telepon" value="<?= e($v('telepon')) ?>" placeholder="081234567890">
                <?= fieldError($errors, 'telepon') ?>
            </div>

            <div class="field<?= $cls('tanggal_lahir') ?>">
                <label for="tanggal_lahir"><?= e(t('f.dob')) ?></label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?= e($v('tanggal_lahir')) ?>" max="<?= e(date('Y-m-d')) ?>">
                <?= fieldError($errors, 'tanggal_lahir') ?>
            </div>

            <div class="field<?= $cls('angkatan') ?>">
                <label for="angkatan"><?= e(t('f.year')) ?></label>
                <input type="number" id="angkatan" name="angkatan" value="<?= e($v('angkatan')) ?>" min="2000" max="<?= e(date('Y')) ?>" step="1">
                <?= fieldError($errors, 'angkatan') ?>
            </div>

            <div class="field<?= $cls('program_studi') ?>">
                <label for="program_studi"><?= e(t('f.prodi')) ?></label>
                <select id="program_studi" name="program_studi">
                    <option value=""><?= e(t('prodi.placeholder')) ?></option>
                    <?php foreach (option('prodi') as $key => $label): ?>
                        <option value="<?= e($key) ?>" <?= $v('program_studi') === (string) $key ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <?= fieldError($errors, 'program_studi') ?>
            </div>

            <div class="field<?= $cls('jenis_kelamin') ?>">
                <span class="label"><?= e(t('f.gender')) ?></span>
                <div class="choices">
                    <?php foreach (['L', 'P'] as $gender): ?>
                        <label class="choice">
                            <input type="radio" name="jenis_kelamin" value="<?= e($gender) ?>" <?= $v('jenis_kelamin') === $gender ? 'checked' : '' ?>>
                            <?= e(t('gender.' . $gender)) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?= fieldError($errors, 'jenis_kelamin') ?>
            </div>

            <div class="field field--wide<?= $cls('alamat') ?>">
                <label for="alamat"><?= e(t('f.address')) ?></label>
                <textarea id="alamat" name="alamat" rows="3" maxlength="255"><?= e($v('alamat')) ?></textarea>
                <?= fieldError($errors, 'alamat') ?>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn--primary"><?= e(t($isEdit ? 'btn.update' : 'btn.save')) ?></button>
            <a class="btn btn--ghost" href="<?= e(url('mahasiswa')) ?>"><?= e(t('btn.cancel')) ?></a>
        </div>
    </form>
</section>
