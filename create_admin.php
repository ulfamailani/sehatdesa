<?php
/**
 * SKRIP SEKALI-JALAN — membuat akun admin pertama.
 * Jalankan sekali lewat browser atau CLI (php create_admin.php),
 * lalu SEGERA HAPUS file ini dari server dan ganti kata sandi default.
 */

require_once __DIR__ . '/includes/db.php';

$username = 'admin';
$password = 'GantiSekarang123!';
$email    = 'admin@sehatdesa.local';
$fullName = 'Administrator';

$stmt = db()->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);

if ($stmt->fetch()) {
    die("Akun admin dengan username '{$username}' sudah ada. Hapus file ini dari server.\n");
}

$hash = password_hash($password, PASSWORD_BCRYPT);
db()->prepare('INSERT INTO users (username, email, password_hash, full_name, role) VALUES (?,?,?,?,"admin")')
    ->execute([$username, $email, $hash, $fullName]);

echo "Akun admin berhasil dibuat.\n";
echo "Username : {$username}\n";
echo "Password : {$password}\n\n";
echo "PENTING: segera login dan ganti kata sandi ini, lalu HAPUS file create_admin.php dari server.\n";
