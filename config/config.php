<?php
/**
 * Kredensial database & konstanta aplikasi.
 * JANGAN commit file ini ke Git — sudah dimasukkan ke .gitignore.
 * Sesuaikan nilai di bawah dengan akun MySQL/phpMyAdmin Anda.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'sehatdesa');
define('DB_USER', 'root');
define('DB_PASS', '');

// Ganti dengan string acak yang panjang (mis. hasil dari bin2hex(random_bytes(32)))
define('APP_SECRET', 'GANTI_DENGAN_STRING_ACAK_PANJANG');

define('APP_NAME', 'SehatDesa');

// Nama cookie sesi — bisa diubah agar tidak bentrok dengan aplikasi lain di hosting yang sama
define('SESSION_NAME', 'sehatdesa_session');
