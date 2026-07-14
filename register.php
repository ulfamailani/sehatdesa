<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (current_user()) { header('Location: index.php'); exit; }

$pageTitle = 'Daftar Akun';
$errors = array();

$postUsername = isset($_POST['username']) ? $_POST['username'] : '';
$postEmail    = isset($_POST['email']) ? $_POST['email'] : '';
$postFullName = isset($_POST['full_name']) ? $_POST['full_name'] : '';
$postDesa     = isset($_POST['desa']) ? $_POST['desa'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postPassword = isset($_POST['password']) ? $_POST['password'] : '';
    $result = register_user($postUsername, $postEmail, $postPassword, $postFullName, $postDesa);
    $ok = $result[0];
    $message = $result[1];

    if ($ok) {
        flash('success', $message);
        header('Location: login.php');
        exit;
    }
    $errors[] = $message;
}

require __DIR__ . '/includes/header.php';
?>
<div class="wrap">
  <div class="form-shell">
    <h2>Buat akun warga</h2>
    <p>Daftar untuk mengakses asisten AI kesehatan dan menyimpan riwayat konsultasi Anda.</p>

    <?php foreach ($errors as $err): ?>
      <div class="alert alert--error"><?= e($err) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
      <div class="field">
        <label for="full_name">Nama lengkap</label>
        <input type="text" id="full_name" name="full_name" value="<?= e($postFullName) ?>" required>
      </div>
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= e($postUsername) ?>" required>
      </div>
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= e($postEmail) ?>" required>
      </div>
      <div class="field">
        <label for="desa">Desa / Dusun</label>
        <input type="text" id="desa" name="desa" value="<?= e($postDesa) ?>" placeholder="Contoh: Desa Makmur, Dusun II">
      </div>
      <div class="field">
        <label for="password">Kata sandi</label>
        <input type="password" id="password" name="password" required minlength="8">
        <p class="hint">Minimal 8 karakter.</p>
      </div>
      <button type="submit" class="btn btn--primary btn--block">Daftar</button>
    </form>

    <p class="form-foot">Sudah punya akun? <a href="login.php" style="color:var(--teal); font-weight:700;">Masuk di sini</a></p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
