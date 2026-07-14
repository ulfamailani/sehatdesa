<?php

/** Escape output untuk mencegah XSS. */
function e($value)
{
    if ($value === null) {
        $value = '';
    }
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Simpan pesan flash (sukses/error) untuk ditampilkan sekali di request berikutnya. */
function flash($type, $message)
{
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = array();
    }
    $_SESSION['flash'][] = array('type' => $type, 'message' => $message);
}

/** Ambil & kosongkan semua pesan flash yang tersimpan. */
function get_flashes()
{
    $flashes = isset($_SESSION['flash']) ? $_SESSION['flash'] : array();
    unset($_SESSION['flash']);
    return $flashes;
}

/** Label tampilan untuk kategori/sasaran. */
function sasaran_label($key)
{
    $labels = array(
        'balita'    => 'Balita',
        'ibu_hamil' => 'Ibu Hamil',
        'lansia'    => 'Lansia',
        'umum'      => 'Umum',
    );
    return isset($labels[$key]) ? $labels[$key] : ucfirst(str_replace('_', ' ', $key));
}

/** Kelas CSS badge sesuai tingkat bahaya penyakit. */
function badge_class($level)
{
    $map = array(
        'ringan'  => 'badge badge--ok',
        'sedang'  => 'badge badge--warn',
        'waspada' => 'badge badge--danger',
    );
    return isset($map[$level]) ? $map[$level] : 'badge';
}

/** Ambil baris pengaturan API AI terbaru (atau null jika belum diatur). */
function get_api_settings()
{
    $row = db()->query('SELECT * FROM api_settings ORDER BY id DESC LIMIT 1')->fetch();
    return $row ? $row : null;
}

/** Kelas 'is-active' untuk item menu navigasi yang sedang aktif. */
function current_page($page)
{
    $current = isset($_SERVER['PHP_SELF']) ? basename($_SERVER['PHP_SELF']) : '';
    return $current === $page ? 'is-active' : '';
}
