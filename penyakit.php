<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Penyakit Umum';
$user = current_user();
$canManage = $user && in_array($user['role'], ['kader', 'admin'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canManage) {
    $stmt = db()->prepare('INSERT INTO penyakit_info (nama_penyakit, gejala, pencegahan, penanganan, tingkat_bahaya, created_by) VALUES (?,?,?,?,?,?)');
    $stmt->execute([
        $_POST['nama_penyakit'], $_POST['gejala'], $_POST['pencegahan'], $_POST['penanganan'],
        $_POST['tingkat_bahaya'], $user['id'],
    ]);
    flash('success', 'Informasi penyakit berhasil ditambahkan.');
    header('Location: penyakit.php');
    exit;
}

$penyakit = db()->query('SELECT * FROM penyakit_info ORDER BY FIELD(tingkat_bahaya,"waspada","sedang","ringan"), nama_penyakit ASC')->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<section class="section">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Kenali & cegah</span>
      <h1>Penyakit Umum di Lingkungan Masyarakat</h1>
      <p>Kenali gejala awal, langkah pencegahan, dan penanganan pertama. Untuk gejala berat atau tidak membaik, segera periksa ke fasilitas kesehatan.</p>
    </div>

    <?php if ($canManage): ?>
      <div class="card card--pad-lg" style="margin-bottom:32px;">
        <h3>Tambah informasi penyakit</h3>
        <form method="post">
          <div class="grid grid--2">
            <div class="field">
              <label for="nama_penyakit">Nama penyakit</label>
              <input type="text" id="nama_penyakit" name="nama_penyakit" required>
            </div>
            <div class="field">
              <label for="tingkat_bahaya">Tingkat kewaspadaan</label>
              <select id="tingkat_bahaya" name="tingkat_bahaya">
                <option value="ringan">Ringan</option>
                <option value="sedang">Sedang</option>
                <option value="waspada">Waspada</option>
              </select>
            </div>
          </div>
          <div class="field"><label for="gejala">Gejala</label><textarea id="gejala" name="gejala" required></textarea></div>
          <div class="field"><label for="pencegahan">Pencegahan</label><textarea id="pencegahan" name="pencegahan" required></textarea></div>
          <div class="field"><label for="penanganan">Penanganan awal</label><textarea id="penanganan" name="penanganan" required></textarea></div>
          <button type="submit" class="btn btn--primary">Simpan</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="grid grid--3">
      <?php foreach ($penyakit as $p): ?>
        <div class="card">
          <?= badge($p['tingkat_bahaya']) ?>
          <h3 style="margin-top:10px;"><?= e($p['nama_penyakit']) ?></h3>
          <p><strong>Gejala:</strong> <?= e($p['gejala']) ?></p>
          <p><strong>Pencegahan:</strong> <?= e($p['pencegahan']) ?></p>
          <p><strong>Penanganan:</strong> <?= e($p['penanganan']) ?></p>
        </div>
      <?php endforeach; ?>
      <?php if (!$penyakit): ?>
        <p>Belum ada data penyakit tersedia.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>

<?php
function badge($level)
{
    $labels = array('ringan' => 'Ringan', 'sedang' => 'Sedang', 'waspada' => 'Waspada');
    $label = isset($labels[$level]) ? $labels[$level] : $level;
    return '<span class="' . badge_class($level) . '">' . e($label) . '</span>';
}
