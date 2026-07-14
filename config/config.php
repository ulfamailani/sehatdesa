<?php
/**
 * Kredensial database & konstanta aplikasi.
 * JANGAN commit file ini ke Git — sudah dimasukkan ke .gitignore.
 * Sesuaikan nilai di bawah dengan akun MySQL/phpMyAdmin Anda.
 */

// Kalau ada environment variable dari Railway (MYSQLHOST dkk), pakai itu.
// Kalau tidak ada (mis. lagi dev lokal pakai XAMPP/Laragon), fallback ke localhost.
define('DB_HOST', getenv('MYSQLHOST') ?: 'localhost');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'sehatdesa');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('DB_PORT', getenv('MYSQLPORT') ?: '3306');

// Ganti dengan string acak yang panjang (mis. hasil dari bin2hex(random_bytes(32)))
define('APP_SECRET', getenv('APP_SECRET') ?: 'GANTI_DENGAN_STRING_ACAK_PANJANG');

define('APP_NAME', 'SehatDesa');

// Nama cookie sesi — bisa diubah agar tidak bentrok dengan aplikasi lain di hosting yang sama
define('SESSION_NAME', 'sehatdesa_session');
