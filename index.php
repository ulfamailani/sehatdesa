<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Beranda';

$jadwalTerdekat = db()->query('SELECT * FROM posyandu_schedule WHERE tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 3')->fetchAll();
$artikelTerbaru = db()->query('SELECT * FROM gizi_articles ORDER BY created_at DESC LIMIT 3')->fetchAll();
$totalWarga = db()->query('SELECT COUNT(*) c FROM users')->fetch()['c'];

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="wrap hero__inner">
    <div>
      <span class="hero__eyebrow">Layanan kesehatan desa berbasis AI</span>
      <h1>Kesehatan keluarga desa, dipantau dan dipahami bersama.</h1>
      <p class="lede">SehatDesa membantu warga, kader, dan Puskesmas mengakses informasi kesehatan dasar, jadwal posyandu, gizi keluarga, serta gejala penyakit umum — dilengkapi asisten AI yang siap menjawab 24 jam.</p>
      <div class="hero__actions">
        <?php if (!current_user()): ?>
          <a href="register.php" class="btn btn--primary">Daftar sebagai Warga</a>
          <a href="chat.php" class="btn btn--outline">Coba Tanya AI</a>
        <?php else: ?>
          <a href="chat.php" class="btn btn--primary">Buka Asisten AI</a>
          <a href="posyandu.php" class="btn btn--outline">Lihat Jadwal Posyandu</a>
        <?php endif; ?>
      </div>
      <div class="hero__stats">
        <div class="hero__stat"><strong><?= (int)$totalWarga ?></strong><span>Warga terdaftar</span></div>
        <div class="hero__stat"><strong>3</strong><span>Pilar informasi kesehatan</span></div>
        <div class="hero__stat"><strong>24/7</strong><span>Asisten AI siaga</span></div>
      </div>
    </div>
    <div>
      <!-- Motif grafik pertumbuhan, terinspirasi Kartu Menuju Sehat (KMS) -->
      <svg class="growth-chart" viewBox="0 0 420 320" xmlns="http://www.w3.org/2000/svg">
        <rect x="0" y="0" width="420" height="320" rx="20" fill="#ffffff" stroke="#DCD1B8"/>
        <g stroke="#EFE7D4" stroke-width="1">
          <line x1="40" y1="30" x2="40" y2="280"/>
          <line x1="40" y1="280" x2="390" y2="280"/>
          <line x1="40" y1="220" x2="390" y2="220"/>
          <line x1="40" y1="160" x2="390" y2="160"/>
          <line x1="40" y1="100" x2="390" y2="100"/>
        </g>
        <text x="10" y="284">0</text><text x="10" y="164">12</text><text x="6" y="104">24</text>
        <text x="36" y="300">Lahir</text><text x="180" y="300">6 bln</text><text x="350" y="300">2 th</text>
        <path class="line-p97" d="M40 260 C 120 190, 220 120, 390 60" fill="none" stroke-width="2.4" stroke-linecap="round"/>
        <path class="line-p50" d="M40 268 C 130 220, 230 170, 390 110" fill="none" stroke-width="2.8" stroke-linecap="round"/>
        <path class="line-p3" d="M40 276 C 140 250, 240 220, 390 170" fill="none" stroke-width="2.4" stroke-linecap="round"/>
        <circle cx="230" cy="172" r="5" fill="#E0603F"/>
        <text x="242" y="176" fill="#E0603F" font-weight="700">Anak Anda</text>
      </svg>
    </div>
  </div>
</section>

<svg class="section-divider" viewBox="0 0 1180 46" preserveAspectRatio="none"><path d="M0 30 C 200 5, 400 45, 590 25 S 980 5, 1180 30" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>

<section class="section">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Tiga pilar</span>
      <h2>Semua yang dibutuhkan posyandu dalam satu tempat</h2>
    </div>
    <div class="grid grid--3">
      <div class="card card--feature">
        <span class="card__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path d="M5 20c0-4 3-6 7-6s7 2 7 6"/></svg></span>
        <h3>Jadwal Posyandu</h3>
        <p>Informasi jadwal penimbangan balita, imunisasi, dan pemeriksaan lansia di dusun Anda, selalu terbaru.</p>
        <a href="posyandu.php" class="btn btn--ghost btn--sm">Lihat jadwal →</a>
      </div>
      <div class="card card--feature">
        <span class="card__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21c-4-3-8-6-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5-4 8-8 11z"/></svg></span>
        <h3>Gizi Keluarga</h3>
        <p>Artikel edukasi seputar pencegahan stunting, menu seimbang, dan gizi ibu hamil yang mudah dipahami.</p>
        <a href="gizi.php" class="btn btn--ghost btn--sm">Baca artikel →</a>
      </div>
      <div class="card card--feature">
        <span class="card__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-4z"/></svg></span>
        <h3>Penyakit Umum</h3>
        <p>Kenali gejala, pencegahan, dan penanganan awal penyakit yang sering muncul di lingkungan masyarakat.</p>
        <a href="penyakit.php" class="btn btn--ghost btn--sm">Lihat daftar →</a>
      </div>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Terjadwal</span>
      <h2>Kegiatan posyandu terdekat</h2>
    </div>
    <?php if ($jadwalTerdekat): ?>
      <div class="grid grid--3">
        <?php foreach ($jadwalTerdekat as $j): ?>
          <div class="card">
            <span class="pill"><?= e(sasaran_label($j['sasaran'])) ?></span>
            <h3><?= e($j['nama_posyandu']) ?></h3>
            <p><?= e($j['alamat']) ?></p>
            <p><strong><?= date('d M Y', strtotime($j['tanggal'])) ?></strong> · pukul <?= date('H:i', strtotime($j['jam'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Belum ada jadwal mendatang. Kader dapat menambahkan jadwal baru di halaman Posyandu.</p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section__head">
      <span class="section__eyebrow">Edukasi</span>
      <h2>Artikel gizi terbaru</h2>
    </div>
    <div class="grid grid--3">
      <?php foreach ($artikelTerbaru as $a): ?>
        <div class="card">
          <span class="pill"><?= e(sasaran_label($a['kategori'])) ?></span>
          <h3><?= e($a['judul']) ?></h3>
          <p><?= e($a['ringkasan']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
