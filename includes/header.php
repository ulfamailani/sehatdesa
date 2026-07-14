<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(isset($pageTitle) ? $pageTitle : 'SehatDesa') ?> · SehatDesa</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="wrap site-header__inner" id="siteHeaderInner">
    <a href="index.php" class="brand">
      <span class="brand__mark">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-4z"/></svg>
      </span>
      <span class="brand__text">
        <span class="brand__name">Sehat<em>Desa</em></span>
        <span class="brand__tag">Kesehatan Komunitas</span>
      </span>
    </a>

    <nav class="main-nav">
      <a href="index.php" class="<?= current_page('index.php') ?>">Beranda</a>
      <a href="posyandu.php" class="<?= current_page('posyandu.php') ?>">Posyandu</a>
      <a href="gizi.php" class="<?= current_page('gizi.php') ?>">Gizi</a>
      <a href="penyakit.php" class="<?= current_page('penyakit.php') ?>">Penyakit</a>
      <?php if (current_user()): ?>
        <a href="chat.php" class="<?= current_page('chat.php') ?>">Tanya AI</a>
      <?php endif; ?>
    </nav>

    <div class="header-actions">
      <?php if ($u = current_user()): ?>
        <?php if ($u['role'] === 'admin'): ?>
          <a href="settings.php" class="btn btn--ghost btn--sm">Pengaturan</a>
        <?php endif; ?>
        <div class="user-chip">
          <span class="user-chip__avatar"><?= e(mb_strtoupper(mb_substr($u['full_name'], 0, 1))) ?></span>
          <span class="user-chip__name"><?= e($u['full_name']) ?></span>
        </div>
        <a href="logout.php" class="btn btn--outline btn--sm">Keluar</a>
      <?php else: ?>
        <a href="login.php" class="btn btn--outline btn--sm">Masuk</a>
        <a href="register.php" class="btn btn--primary btn--sm">Daftar</a>
      <?php endif; ?>
      <button class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<main>
<?php foreach (get_flashes() as $f): ?>
  <div class="wrap">
    <div class="alert alert--<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
  </div>
<?php endforeach; ?>
