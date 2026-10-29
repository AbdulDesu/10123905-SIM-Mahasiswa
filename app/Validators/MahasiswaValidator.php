<?php
declare(strict_types=1);

namespace App\Validators;

use App\Repositories\MahasiswaRepository;
use DateTimeImmutable;


/**
 * Nama: Abdul Richard
 * NIM: 10123905
 * Kelas: IF12-K - Teknik Informatika
 */

/** Sanitasi + validasi data form mahasiswa. Pesan error sudah diterjemahkan (dual bahasa). */
final class MahasiswaValidator
{
    private MahasiswaRepository $repo;

    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    /** SANITASI: bersihkan data mentah dari $_POST. */
    public function sanitize(array $input): array
    {
        return [
            'nim'           => self::clean($input['nim'] ?? ''),
            'nama'          => (string) preg_replace('/\s+/', ' ', self::clean($input['nama'] ?? '')),
            'email'         => (string) filter_var(self::clean($input['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'telepon'       => self::clean($input['telepon'] ?? ''),
            'tanggal_lahir' => self::clean($input['tanggal_lahir'] ?? ''),
            'jenis_kelamin' => self::clean($input['jenis_kelamin'] ?? ''),
            'program_studi' => self::clean($input['program_studi'] ?? ''),
            'angkatan'      => self::clean($input['angkatan'] ?? ''),
            'alamat'        => self::clean($input['alamat'] ?? ''),
        ];
    }

    /** VALIDASI: mengembalikan array [field => pesan]. Kosong = valid. */
    public function validate(array $d, ?int $ignoreId = null): array
    {
        $errors = [];

        // NIM
        if ($d['nim'] === '') {
            $errors['nim'] = $this->required('f.nim');
        } elseif (!preg_match('/^\d{8,12}$/', $d['nim'])) {
            $errors['nim'] = t('val.nim_format');
        } elseif ($this->repo->nimExists($d['nim'], $ignoreId)) {
            $errors['nim'] = t('val.nim_taken');
        }

        // Nama
        if ($d['nama'] === '') {
            $errors['nama'] = $this->required('f.nama');
        } elseif (
            textLength($d['nama']) < 3
            || textLength($d['nama']) > 100
            || !preg_match('/^[\p{L} .\'-]+$/u', $d['nama'])
        ) {
            $errors['nama'] = t('val.nama_format');
        }

        // Email
        if ($d['email'] === '') {
            $errors['email'] = $this->required('f.email');
        } elseif (strlen($d['email']) > 100 || !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = t('val.email_invalid');
        }

        // Telepon
        if ($d['telepon'] === '') {
            $errors['telepon'] = $this->required('f.telepon');
        } elseif (!preg_match('/^(\+62|62|0)8\d{8,11}$/', $d['telepon'])) {
            $errors['telepon'] = t('val.phone_format');
        }

        // Tanggal lahir
        if ($d['tanggal_lahir'] === '') {
            $errors['tanggal_lahir'] = $this->required('f.dob');
        } elseif (!$this->isValidDate($d['tanggal_lahir'])) {
            $errors['tanggal_lahir'] = t('val.dob_invalid');
        } elseif ($d['tanggal_lahir'] > date('Y-m-d')) {
            $errors['tanggal_lahir'] = t('val.dob_future');
        }

        // Jenis kelamin (switch)
        switch ($d['jenis_kelamin']) {
            case 'L':
            case 'P':
                break;
            default:
                $errors['jenis_kelamin'] = t('val.choose', ['field' => t('f.gender')]);
        }

        // Program studi
        if (!array_key_exists($d['program_studi'], option('prodi'))) {
            $errors['program_studi'] = t('val.choose', ['field' => t('f.prodi')]);
        }

        // Angkatan
        $minYear = 2000;
        $maxYear = (int) date('Y');
        if ($d['angkatan'] === '') {
            $errors['angkatan'] = $this->required('f.year');
        } elseif (
            !preg_match('/^\d{4}$/', $d['angkatan'])
            || (int) $d['angkatan'] < $minYear
            || (int) $d['angkatan'] > $maxYear
        ) {
            $errors['angkatan'] = t('val.year_range', ['min' => $minYear, 'max' => $maxYear]);
        }

        // Alamat
        $length = textLength($d['alamat']);
        if ($length === 0) {
            $errors['alamat'] = $this->required('f.address');
        } elseif ($length < 10 || $length > 255) {
            $errors['alamat'] = t('val.address_length');
        }

        return $errors;
    }

    /** Buang karakter kontrol (kecuali enter/tab) lalu trim. */
    private static function clean($value): string
    {
        $value = is_string($value) ? $value : '';
        $value = (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

        return trim($value);
    }

    private function required(string $fieldKey): string
    {
        return t('val.required', ['field' => t($fieldKey)]);
    }

    private function isValidDate(string $value): bool
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);

        return $date !== false && $date->format('Y-m-d') === $value;
    }
}
