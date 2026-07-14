<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

require_login();
$pageTitle = 'Tanya Asisten AI';
$user = current_user();

$riwayat = db()->prepare('SELECT * FROM chat_history WHERE user_id = ? ORDER BY created_at ASC LIMIT 20');
$riwayat->execute([$user['id']]);
$riwayat = $riwayat->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<section class="section" style="padding-bottom:20px;">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Asisten AI</span>
      <h1>Tanya seputar kesehatan dasar</h1>
      <p>Tanyakan gejala umum, gizi, atau jadwal posyandu. Asisten ini memberi informasi edukatif — bukan diagnosis medis.</p>
    </div>
  </div>
</section>

<div class="wrap">
  <div class="chat-shell">
    <div class="chat-shell__head">
      <span class="dot"></span>
      <div>
        <strong>Asisten SehatDesa</strong>
        <div style="font-size:0.78rem; opacity:0.75;">Online · siap membantu</div>
      </div>
    </div>
    <div class="chat-shell__disclaimer">
      ⚠️ Informasi bersifat edukatif, bukan pengganti diagnosis dokter. Untuk kondisi darurat, segera hubungi Puskesmas atau layanan gawat darurat.
    </div>
    <div class="chat-log" id="chatLog">
      <?php if (!$riwayat): ?>
        <p class="chat-empty">Belum ada percakapan. Mulai dengan menuliskan pertanyaan kesehatan Anda di bawah ini.</p>
      <?php else: ?>
        <?php foreach ($riwayat as $r): ?>
          <div class="msg msg--user"><?= e($r['pesan']) ?></div>
          <div class="msg msg--ai"><span class="msg__label">Asisten SehatDesa</span><?= e($r['jawaban']) ?></div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <form class="chat-form" id="chatForm">
      <textarea id="chatInput" placeholder="Tulis pertanyaan kesehatan Anda..." required></textarea>
      <button type="submit" class="btn btn--primary">Kirim</button>
    </form>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
