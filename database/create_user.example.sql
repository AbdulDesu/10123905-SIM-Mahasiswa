-- OPSIONAL: untuk MariaDB/MySQL di Linux/WSL yang user root-nya memakai auth_socket.
-- Jalankan:  sudo mysql < database/create_user.example.sql
-- Lalu salin app/Config/config.local.example.php -> config.local.php (isinya sudah cocok).

CREATE USER IF NOT EXISTS 'sim_user'@'localhost' IDENTIFIED BY 'sim_pass_123';
GRANT ALL PRIVILEGES ON sim_mahasiswa.* TO 'sim_user'@'localhost';
FLUSH PRIVILEGES;
