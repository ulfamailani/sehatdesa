<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Gizi Keluarga';
$user = current_user();
$canManage = $user && in_array($user['role'], ['kader', 'admin'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canManage) {
    $stmt = db()->prepare('INSERT INTO gizi_articles (judul, kategori, ringkasan, konten, created_by) VALUES (?,?,?,?,?)');
    $stmt->execute([$_POST['judul'], $_POST['kategori'], $_POST['ringkasan'], $_POST['konten'], $user['id']]);
    flash('success', 'Artikel gizi berhasil ditambahkan.');
    header('Location: gizi.php');
    exit;
}

$artikel = db()->query('SELECT * FROM gizi_articles ORDER BY created_at DESC')->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<section class="section">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Edukasi gizi</span>
      <h1>Gizi Keluarga</h1>
      <p>Kumpulan artikel edukatif seputar pemenuhan gizi balita, ibu hamil, lansia, dan keluarga secara umum.</p>
    </div>

    <?php if ($canManage): ?>
      <div class="card card--pad-lg" style="margin-bottom:32px;">
        <h3>Tulis artikel baru</h3>
        <form method="post">
          <div class="grid grid--2">
            <div class="field">
              <label for="judul">Judul</label>
              <input type="text" id="judul" name="judul" required>
            </div>
            <div class="field">
              <label for="kategori">Kategori</label>
              <select id="kategori" name="kategori">
                <option value="balita">Balita</option>
                <option value="ibu_hamil">Ibu Hamil</option>
                <option value="lansia">Lansia</option>
                <option value="umum">Umum</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="ringkasan">Ringkasan singkat</label>
            <input type="text" id="ringkasan" name="ringkasan" maxlength="300" required>
          </div>
          <div class="field">
            <label for="konten">Isi artikel</label>
            <textarea id="konten" name="konten" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn btn--primary">Terbitkan artikel</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="grid grid--3">
      <?php foreach ($artikel as $a): ?>
        <div class="card">
          <span class="pill"><?= e(sasaran_label($a['kategori'])) ?></span>
          <h3><?= e($a['judul']) ?></h3>
          <p><?= e($a['ringkasan']) ?></p>
          <details>
            <summary style="cursor:pointer; font-weight:700; color:var(--teal); font-size:0.9rem;">Baca selengkapnya</summary>
            <p style="margin-top:10px;"><?= nl2br(e($a['konten'])) ?></p>
          </details>
        </div>
      <?php endforeach; ?>
      <?php if (!$artikel): ?>
        <p>Belum ada artikel tersedia.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
