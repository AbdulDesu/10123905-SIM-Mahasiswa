-- =====================================================================
--  SIM Data Mahasiswa  -  skrip database (MySQL / MariaDB)
--  Cara import:  mysql -u root -p < database/schema.sql
--  atau lewat phpMyAdmin: tab Import -> pilih file ini
-- =====================================================================

CREATE DATABASE IF NOT EXISTS sim_mahasiswa
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sim_mahasiswa;

-- ---------------------------------------------------------------------
--  Tabel pengguna (untuk login)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username      VARCHAR(50)  NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nama_lengkap  VARCHAR(100) NOT NULL,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username)
) ENGINE = InnoDB;

-- ---------------------------------------------------------------------
--  Tabel mahasiswa (data utama CRUD)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS mahasiswa (
    id            INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    nim           VARCHAR(12)       NOT NULL,
    nama          VARCHAR(100)      NOT NULL,
    email         VARCHAR(100)      NOT NULL,
    telepon       VARCHAR(15)       NOT NULL,
    tanggal_lahir DATE              NOT NULL,
    jenis_kelamin ENUM('L', 'P')    NOT NULL,
    program_studi VARCHAR(10)       NOT NULL,
    angkatan      SMALLINT UNSIGNED NOT NULL,
    alamat        VARCHAR(255)      NOT NULL,
    created_at    TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_mahasiswa_nim (nim)
) ENGINE = InnoDB;

-- ---------------------------------------------------------------------
--  Data awal
--  Akun login  : admin / admin123   (password disimpan sebagai hash bcrypt)
-- ---------------------------------------------------------------------
INSERT IGNORE INTO users (username, password_hash, nama_lengkap) VALUES
    ('admin', '$2y$12$XBhT.ub3z4QobQg5pWe1vurEsZaZMDf228lJzgBJzIRziOImtq3fO', 'Administrator');

INSERT IGNORE INTO mahasiswa
    (nim, nama, email, telepon, tanggal_lahir, jenis_kelamin, program_studi, angkatan, alamat) VALUES
    ('2023110001', 'Abdul Richard',  'abdul.richard@contoh.ac.id', '081234567890', '2004-03-12', 'L', 'TI',  2023, 'Jl. Dago No. 10, Bandung'),
    ('2023110002', 'Siti Nurhaliza', 'siti.nurhaliza@contoh.ac.id', '085712345678', '2004-07-25', 'P', 'SI',  2023, 'Jl. Cihampelas No. 21, Bandung'),
    ('2022110015', 'Budi Santoso',   'budi.santoso@contoh.ac.id',  '081398765432', '2003-11-02', 'L', 'MI',  2022, 'Jl. Setiabudi No. 5, Bandung');
