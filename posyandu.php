<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Jadwal Posyandu';
$user = current_user();
$canManage = $user && in_array($user['role'], ['kader', 'admin'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canManage) {
    $stmt = db()->prepare('INSERT INTO posyandu_schedule (nama_posyandu, alamat, tanggal, jam, kegiatan, sasaran, created_by) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([
        $_POST['nama_posyandu'], $_POST['alamat'], $_POST['tanggal'], $_POST['jam'],
        $_POST['kegiatan'], $_POST['sasaran'], $user['id'],
    ]);
    flash('success', 'Jadwal posyandu berhasil ditambahkan.');
    header('Location: posyandu.php');
    exit;
}

$jadwal = db()->query('SELECT * FROM posyandu_schedule ORDER BY tanggal ASC')->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<section class="section">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Kegiatan rutin</span>
      <h1>Jadwal Posyandu</h1>
      <p>Pantau jadwal penimbangan balita, imunisasi, pemeriksaan ibu hamil, dan kegiatan lansia di desa Anda.</p>
    </div>

    <?php if ($canManage): ?>
      <div class="card card--pad-lg" style="margin-bottom:32px;">
        <h3>Tambah jadwal baru</h3>
        <form method="post">
          <div class="grid grid--2">
            <div class="field">
              <label for="nama_posyandu">Nama posyandu</label>
              <input type="text" id="nama_posyandu" name="nama_posyandu" required>
            </div>
            <div class="field">
              <label for="sasaran">Sasaran</label>
              <select id="sasaran" name="sasaran">
                <option value="balita">Balita</option>
                <option value="ibu_hamil">Ibu Hamil</option>
                <option value="lansia">Lansia</option>
                <option value="umum">Umum</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="alamat">Alamat / lokasi</label>
            <input type="text" id="alamat" name="alamat" required>
          </div>
          <div class="grid grid--2">
            <div class="field">
              <label for="tanggal">Tanggal</label>
              <input type="date" id="tanggal" name="tanggal" required>
            </div>
            <div class="field">
              <label for="jam">Jam</label>
              <input type="time" id="jam" name="jam" required>
            </div>
          </div>
          <div class="field">
            <label for="kegiatan">Kegiatan</label>
            <textarea id="kegiatan" name="kegiatan" required placeholder="Contoh: Penimbangan balita, imunisasi, pemberian vitamin A"></textarea>
          </div>
          <button type="submit" class="btn btn--primary">Simpan jadwal</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="card" style="overflow-x:auto;">
      <table>
        <thead>
          <tr><th>Posyandu</th><th>Alamat</th><th>Tanggal</th><th>Jam</th><th>Sasaran</th><th>Kegiatan</th></tr>
        </thead>
        <tbody>
          <?php foreach ($jadwal as $j): ?>
            <tr>
              <td><strong><?= e($j['nama_posyandu']) ?></strong></td>
              <td><?= e($j['alamat']) ?></td>
              <td><?= date('d M Y', strtotime($j['tanggal'])) ?></td>
              <td><?= date('H:i', strtotime($j['jam'])) ?></td>
              <td><span class="pill"><?= e(sasaran_label($j['sasaran'])) ?></span></td>
              <td><?= e($j['kegiatan']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$jadwal): ?>
            <tr><td colspan="6">Belum ada jadwal tersimpan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
