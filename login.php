<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (current_user()) { header('Location: index.php'); exit; }

$pageTitle = 'Masuk';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = isset($_POST['username']) ? $_POST['username'] : '';
    $passwordInput = isset($_POST['password']) ? $_POST['password'] : '';
    $result = login_user($usernameInput, $passwordInput);
    $ok = $result[0];
    $message = $result[1];
    if ($ok) {
        header('Location: index.php');
        exit;
    }
    $errors[] = $message;
}

require __DIR__ . '/includes/header.php';
?>
<div class="wrap">
  <div class="form-shell">
    <h2>Masuk ke akun Anda</h2>
    <p>Akses jadwal posyandu, artikel gizi, dan asisten AI kesehatan komunitas.</p>

    <?php foreach ($errors as $err): ?>
      <div class="alert alert--error"><?= e($err) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
      <div class="field">
        <label for="username">Username atau email</label>
        <input type="text" id="username" name="username" required autofocus>
      </div>
      <div class="field">
        <label for="password">Kata sandi</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn--primary btn--block">Masuk</button>
    </form>

    <p class="form-foot">Belum punya akun? <a href="register.php" style="color:var(--teal); font-weight:700;">Daftar gratis</a></p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
