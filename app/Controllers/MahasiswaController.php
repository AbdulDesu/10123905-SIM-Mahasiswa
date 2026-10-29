<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Flash;
use App\Core\View;
use App\Repositories\MahasiswaRepository;
use App\Validators\MahasiswaValidator;

final class MahasiswaController
{
    private MahasiswaRepository $repo;
    private MahasiswaValidator $validator;

    public function __construct(MahasiswaRepository $repo, MahasiswaValidator $validator)
    {
        $this->repo      = $repo;
        $this->validator = $validator;
    }

    /** READ: tabel semua data */
    public function index(): void
    {
        View::render('mahasiswa/index', [
            'title' => t('mhs.list_title'),
            'rows'  => $this->repo->all(),
        ]);
    }

    /** CREATE: tampilkan form kosong */
    public function create(): void
    {
        $this->renderForm('create', [], []);
    }

    /** CREATE: proses form */
    public function store(): void
    {
        $data   = $this->validator->sanitize($_POST);
        $errors = $this->validator->validate($data);

        if ($errors !== []) {
            $this->renderForm('create', $data, $errors);
            return;
        }

        $this->repo->create($data);
        Flash::set('success', t('mhs.created', ['name' => $data['nama']]));
        redirect(url('mahasiswa'));
    }

    /** UPDATE: tampilkan form terisi data lama */
    public function edit(int $id): void
    {
        $row = $this->findOrRedirect($id);
        $this->renderForm('edit', $row, [], $id);
    }

    /** UPDATE: proses form */
    public function update(int $id): void
    {
        $this->findOrRedirect($id);

        $data   = $this->validator->sanitize($_POST);
        $errors = $this->validator->validate($data, $id);

        if ($errors !== []) {
            $this->renderForm('edit', $data, $errors, $id);
            return;
        }

        $this->repo->update($id, $data);
        Flash::set('success', t('mhs.updated', ['name' => $data['nama']]));
        redirect(url('mahasiswa'));
    }

    /** DELETE */
    public function delete(int $id): void
    {
        $row = $this->findOrRedirect($id);

        $this->repo->delete($id);
        Flash::set('success', t('mhs.deleted', ['name' => $row['nama']]));
        redirect(url('mahasiswa'));
    }

    private function findOrRedirect(int $id): array
    {
        $row = $id > 0 ? $this->repo->find($id) : null;

        if ($row === null) {
            Flash::set('warning', t('mhs.not_found'));
            redirect(url('mahasiswa'));
        }
        return $row;
    }

    private function renderForm(string $mode, array $values, array $errors, ?int $id = null): void
    {
        View::render('mahasiswa/form', [
            'title'  => t($mode === 'edit' ? 'mhs.edit_title' : 'mhs.create_title'),
            'mode'   => $mode,
            'values' => $values,
            'errors' => $errors,
            'id'     => $id,
        ]);
    }
}
