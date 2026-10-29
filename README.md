# Author:
Nama: Abdul Richard <br>
NIM: 10123905 <br>
Kelas: IF12-K


# SIM Data Mahasiswa (PHP murni + MySQL/MariaDB)

Aplikasi manajemen data mahasiswa sederhana: login berbasis `$_SESSION`, CRUD, validasi & sanitasi form, dan dua bahasa (Indonesia / English).

## Fitur
- **Form & validasi**: metode POST, validasi `if-else` / `switch`, sanitasi (`trim`, `filter_var`, `htmlspecialchars` saat output).
- **CRUD** dengan PDO + prepared statement (aman dari SQL injection).
- **Session**: login/logout, halaman CRUD terproteksi, pilihan bahasa tersimpan di `$_SESSION['lang']`.
- Tambahan keamanan: token CSRF di semua POST, `password_hash`/`password_verify`, `session_regenerate_id` saat login.

## Struktur proyek (layered architecture sederhana)
```
public/                 <- satu-satunya folder yang diakses browser
  index.php             <- front controller / router
  assets/style.css
app/
  bootstrap.php         <- autoload, helper, session
  helpers.php           <- e(), t(), url(), redirect(), ...
  Config/               <- konfigurasi DB & opsi form
  Core/                 <- Database, Auth, Lang, Csrf, Flash, View
  Repositories/         <- SEMUA query SQL (MahasiswaRepository, UserRepository)
  Validators/           <- sanitasi + validasi form
  Controllers/          <- alur request -> respons
  Views/                <- HTML (layout, login, tabel, form)
lang/id.php, en.php     <- kamus terjemahan
database/schema.sql     <- skrip database + data awal
```
Alur: `Request -> index.php (router) -> Controller -> Validator / Repository -> View`.

## Kebutuhan
- PHP 7.4+ (diuji target PHP 8.x) dengan ekstensi **pdo_mysql**
- MySQL 5.7+ / MariaDB 10.3+

Cek ekstensi: `php -m | grep -i pdo_mysql`. Jika belum ada (Ubuntu/WSL): `sudo apt install php-mysql mariadb-server`.

## Cara tercepat: Docker (tanpa instal PHP / MySQL)
Cukup butuh Docker + Docker Compose.
```bash
docker compose up -d --build
```
| Layanan | Alamat | Keterangan |
|---|---|---|
| Aplikasi | http://localhost:8080 | login `admin` / `admin123` |
| phpMyAdmin | http://localhost:8081 | user `root`, password `root_pass_123` (untuk lihat data / ekspor .sql) |

- Database dibuat dan diisi otomatis dari `database/schema.sql` saat pertama kali jalan.
- Kode di `app/`, `public/`, `lang/` di-mount, jadi hasil edit langsung terlihat tanpa build ulang.
- Lihat log: `docker compose logs -f web` | Berhenti: `docker compose down`
- Reset database ke kondisi awal: `docker compose down -v && docker compose up -d`
- Port bentrok? Ubah lewat `WEB_PORT=9000 PMA_PORT=9001 docker compose up -d`.
- Ekspor database untuk tugas: `docker compose exec db mariadb-dump -uroot -proot_pass_123 sim_mahasiswa > database/sim_mahasiswa_export.sql`

## Menjalankan tanpa Docker
1. **Import database**
   ```bash
   sudo mysql < database/schema.sql      # atau: mysql -u root -p < database/schema.sql
   ```
   (XAMPP/Laragon: import `database/schema.sql` lewat phpMyAdmin.)
2. **Atur koneksi** (lewati bila memakai XAMPP dengan user `root` tanpa password)
   ```bash
   sudo mysql < database/create_user.example.sql
   cp app/Config/config.local.example.php app/Config/config.local.php
   ```
3. **Jalankan server**
   ```bash
   php -S localhost:8000 -t public
   ```
   Buka http://localhost:8000
4. **Login**: `admin` / `admin123`

Pastikan repositori berstatus **Public**. Ekspor database terbaru (opsional) dengan
`mysqldump -u root -p sim_mahasiswa > database/sim_mahasiswa_export.sql`.
