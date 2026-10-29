<section>
    <div class="page-head">
        <div>
            <h1><?= e($title) ?></h1>
            <p class="note"><?= e(t('mhs.total', ['count' => count($rows)])) ?></p>
        </div>
        <a class="btn btn--primary" href="<?= e(url('mahasiswa-create')) ?>"><?= e(t('mhs.add')) ?></a>
    </div>

    <?php if ($rows === []): ?>
        <div class="card"><p class="empty"><?= e(t('mhs.empty')) ?></p></div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th><?= e(t('col.no')) ?></th>
                    <th><?= e(t('f.nim')) ?></th>
                    <th><?= e(t('f.nama')) ?></th>
                    <th><?= e(t('f.email')) ?></th>
                    <th><?= e(t('f.telepon')) ?></th>
                    <th><?= e(t('f.dob')) ?></th>
                    <th><?= e(t('f.gender')) ?></th>
                    <th><?= e(t('f.prodi')) ?></th>
                    <th><?= e(t('f.year')) ?></th>
                    <th><?= e(t('f.address')) ?></th>
                    <th><?= e(t('col.action')) ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($row['nim']) ?></td>
                        <td><?= e($row['nama']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['telepon']) ?></td>
                        <td><?= e(date('d-m-Y', strtotime($row['tanggal_lahir']))) ?></td>
                        <td><?= e(t('gender.' . $row['jenis_kelamin'])) ?></td>
                        <td><?= e(option('prodi')[$row['program_studi']] ?? $row['program_studi']) ?></td>
                        <td><?= e($row['angkatan']) ?></td>
                        <td class="cell-wrap"><?= e($row['alamat']) ?></td>
                        <td class="actions">
                            <a class="btn btn--small" href="<?= e(url('mahasiswa-edit', ['id' => $row['id']])) ?>"><?= e(t('btn.edit')) ?></a>
                            <form method="post" action="<?= e(url('mahasiswa-delete', ['id' => $row['id']])) ?>" class="inline"
                                  onsubmit="return confirm(<?= e(json_encode(t('mhs.confirm_delete', ['name' => $row['nama']]), JSON_UNESCAPED_UNICODE)) ?>)">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn--small btn--danger"><?= e(t('btn.delete')) ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
