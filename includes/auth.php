<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(defined('SESSION_NAME') ? SESSION_NAME : 'sehatdesa_session');
    session_set_cookie_params(0, '/', null, false, true);
    session_start();
}

/**
 * Mengembalikan data pengguna yang sedang login, atau null jika belum login.
 */
function current_user()
{
    static $cached = false; // false = belum pernah dicek, null = sudah dicek & tidak login

    if ($cached !== false) {
        return $cached;
    }

    if (!isset($_SESSION['user_id'])) {
        $cached = null;
        return null;
    }

    $stmt = db()->prepare('SELECT id, username, email, full_name, role, desa FROM users WHERE id = ?');
    $stmt->execute(array($_SESSION['user_id']));
    $user = $stmt->fetch();

    if (!$user) {
        unset($_SESSION['user_id']);
        $cached = null;
        return null;
    }

    $cached = $user;
    return $user;
}

/** Wajibkan pengguna sudah login, redirect ke login.php jika belum. */
function require_login()
{
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

/** Wajibkan pengguna login DAN memiliki salah satu role yang diizinkan. */
function require_role($roles)
{
    $user = current_user();
    if (!$user) {
        header('Location: login.php');
        exit;
    }
    if (!in_array($user['role'], $roles, true)) {
        http_response_code(403);
        header('Location: index.php');
        exit;
    }
}

/**
 * Proses login. Mengembalikan array(bool sukses, string pesan).
 */
function login_user($username, $password)
{
    $username = trim($username);
    if ($username === '' || $password === '') {
        return array(false, 'Username/email dan kata sandi wajib diisi.');
    }

    $stmt = db()->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->execute(array($username, $username));
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return array(false, 'Username/email atau kata sandi salah.');
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];

    db()->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute(array($user['id']));

    return array(true, 'Berhasil masuk.');
}

/**
 * Proses registrasi akun warga baru. Mengembalikan array(bool sukses, string pesan).
 */
function register_user($username, $email, $password, $fullName, $desa)
{
    $username = trim($username);
    $email    = trim($email);
    $fullName = trim($fullName);
    $desa     = trim($desa);

    if ($username === '' || $email === '' || $password === '' || $fullName === '') {
        return array(false, 'Nama lengkap, username, email, dan kata sandi wajib diisi.');
    }
    if (!preg_match('/^[a-zA-Z0-9_.]{3,50}$/', $username)) {
        return array(false, 'Username hanya boleh huruf, angka, titik, dan garis bawah (3-50 karakter).');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array(false, 'Format email tidak valid.');
    }
    if (strlen($password) < 8) {
        return array(false, 'Kata sandi minimal 8 karakter.');
    }

    $stmt = db()->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->execute(array($username, $email));
    if ($stmt->fetch()) {
        return array(false, 'Username atau email sudah terdaftar.');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    db()->prepare('INSERT INTO users (username, email, password_hash, full_name, role, desa) VALUES (?,?,?,?,"warga",?)')
        ->execute(array($username, $email, $hash, $fullName, $desa !== '' ? $desa : null));

    return array(true, 'Pendaftaran berhasil. Silakan masuk dengan akun Anda.');
}

/** Hapus sesi & cookie login. */
function logout_user()
{
    $_SESSION = array();

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}
