<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

/** Satu-satunya tempat yang berisi query SQL untuk tabel mahasiswa. */
final class MahasiswaRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** READ: semua data */
    public function all(): array
    {
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetchAll();
    }

    /** READ: satu data berdasarkan id */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /** Cek NIM sudah dipakai (abaikan id tertentu saat update). */
    public function nimExists(string $nim, ?int $ignoreId = null): bool
    {
        $sql    = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
        $params = ['nim' => $nim];

        if ($ignoreId !== null) {
            $sql .= ' AND id <> :id';
            $params['id'] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    /** CREATE */
    public function create(array $d): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, telepon, tanggal_lahir, jenis_kelamin, program_studi, angkatan, alamat)
             VALUES (:nim, :nama, :email, :telepon, :tanggal_lahir, :jenis_kelamin, :program_studi, :angkatan, :alamat)'
        );
        $stmt->execute($this->params($d));

        return (int) $this->db->lastInsertId();
    }

    /** UPDATE */
    public function update(int $id, array $d): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE mahasiswa SET nim = :nim, nama = :nama, email = :email, telepon = :telepon,
                    tanggal_lahir = :tanggal_lahir, jenis_kelamin = :jenis_kelamin,
                    program_studi = :program_studi, angkatan = :angkatan, alamat = :alamat
             WHERE id = :id'
        );

        return $stmt->execute($this->params($d) + ['id' => $id]);
    }

    /** DELETE */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    private function params(array $d): array
    {
        return [
            'nim'           => $d['nim'],
            'nama'          => $d['nama'],
            'email'         => $d['email'],
            'telepon'       => $d['telepon'],
            'tanggal_lahir' => $d['tanggal_lahir'],
            'jenis_kelamin' => $d['jenis_kelamin'],
            'program_studi' => $d['program_studi'],
            'angkatan'      => (int) $d['angkatan'],
            'alamat'        => $d['alamat'],
        ];
    }
}
