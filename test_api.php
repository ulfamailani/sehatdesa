<?php
/**
 * FILE DIAGNOSTIK SEMENTARA — untuk mencari tahu kenapa koneksi ke API
 * gagal. Taruh file ini di folder utama sehatdesa/, buka lewat browser:
 * http://localhost/sehatdesa/test_api.php
 * HAPUS file ini setelah masalah selesai (jangan biarkan di server produksi).
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== TES DIAGNOSTIK SEHATDESA ===\n\n";

echo "1. Cek ekstensi PHP:\n";
echo "   - curl aktif? " . (extension_loaded('curl') ? "YA" : "TIDAK — extension=curl belum aktif di php.ini") . "\n";
echo "   - openssl aktif? " . (extension_loaded('openssl') ? "YA" : "TIDAK — extension=openssl belum aktif di php.ini") . "\n\n";

echo "2. Cek pengaturan API key di database:\n";
try {
    $settings = get_api_settings();
    if (!$settings) {
        echo "   TIDAK DITEMUKAN baris di tabel api_settings.\n\n";
    } else {
        $key = $settings['api_key'];
        $masked = strlen($key) > 8 ? substr($key, 0, 6) . '...' . substr($key, -4) . ' (panjang: ' . strlen($key) . ' karakter)' : '(terlalu pendek / kosong)';
        echo "   API key tersimpan: " . $masked . "\n";
        echo "   Model: " . $settings['model'] . "\n\n";
    }
} catch (Exception $e) {
    echo "   ERROR mengambil dari database: " . $e->getMessage() . "\n\n";
}

echo "3. Tes koneksi cURL langsung ke api.anthropic.com:\n";
if (!$settings || $settings['api_key'] === 'GANTI_DENGAN_API_KEY_ANDA' || $settings['api_key'] === '') {
    echo "   Dilewati — API key belum diatur.\n\n";
} else {
    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . $settings['api_key'],
            'anthropic-version: 2023-06-01',
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'model' => $settings['model'] ?: 'claude-sonnet-5',
            'max_tokens' => 50,
            'messages' => [['role' => 'user', 'content' => 'halo']],
        ]),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_VERBOSE => false,
    ]);
    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrNo = curl_errno($ch);
    $curlErr   = curl_error($ch);
    curl_close($ch);

    echo "   HTTP Status Code : " . $httpCode . "\n";
    echo "   cURL error number: " . $curlErrNo . "\n";
    echo "   cURL error text  : " . ($curlErr ?: '(tidak ada)') . "\n";
    echo "   Respons mentah dari server:\n";
    echo "   " . substr($response ?: '(kosong / tidak ada respons)', 0, 1500) . "\n\n";
}

echo "=== SELESAI — screenshot atau copy semua tulisan di atas ===\n";