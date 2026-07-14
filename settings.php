<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

require_role(['admin']);
$pageTitle = 'Pengaturan';
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey  = trim(isset($_POST['api_key']) ? $_POST['api_key'] : '');
    $model   = trim(isset($_POST['model']) ? $_POST['model'] : 'claude-sonnet-5');
    $existing = get_api_settings();

    if ($apiKey === '') {
        flash('error', 'API key tidak boleh kosong.');
    } else {
        if ($existing) {
            db()->prepare('UPDATE api_settings SET api_key = ?, model = ?, updated_by = ? WHERE id = ?')
                ->execute([$apiKey, $model, $user['id'], $existing['id']]);
        } else {
            db()->prepare('INSERT INTO api_settings (provider, api_key, model, updated_by) VALUES ("anthropic", ?, ?, ?)')
                ->execute([$apiKey, $model, $user['id']]);
        }
        flash('success', 'Pengaturan API key berhasil disimpan.');
    }
    header('Location: settings.php');
    exit;
}

$settings = get_api_settings();

require __DIR__ . '/includes/header.php';
?>
<section class="section">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Khusus admin</span>
      <h1>Pengaturan Asisten AI</h1>
      <p>Atur API key layanan AI yang digunakan oleh fitur "Tanya AI". Key disimpan di tabel <code>api_settings</code> pada database Anda.</p>
    </div>

    <div class="card card--pad-lg form-shell form-shell--wide" style="margin:0;">
      <form method="post">
        <div class="field">
          <label for="api_key">API Key</label>
          <input type="password" id="api_key" name="api_key" placeholder="sk-ant-..." value="<?= $settings ? e($settings['api_key']) : '' ?>" required autocomplete="off">
          <p class="hint">Diperoleh dari dashboard penyedia layanan AI Anda (mis. Anthropic Console). Rahasiakan key ini.</p>
        </div>
        <div class="field">
          <label for="model">Model</label>
          <input type="text" id="model" name="model" value="<?= $settings ? e($settings['model']) : 'claude-sonnet-5' ?>">
          <p class="hint">Nama model yang dipanggil pada setiap permintaan chat.</p>
        </div>
        <button type="submit" class="btn btn--primary">Simpan pengaturan</button>
      </form>
    </div>

    <?php if ($settings): ?>
      <p style="margin-top:18px; font-size:0.85rem; color:var(--ink-soft);">
        Terakhir diperbarui: <?= date('d M Y H:i', strtotime($settings['updated_at'])) ?>
      </p>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
