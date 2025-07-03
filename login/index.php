<?php
session_start();

// Kalau sudah login, langsung redirect
if (isset($_SESSION['db_user'])) {
  header("Location: /final-project-sbd/index.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db_user = trim($_POST['db_user'] ?? '');
  $db_pass = $_POST['db_pass'] ?? '';

  // Coba koneksi ke database
  $test_conn = @new mysqli('localhost', $db_user, $db_pass, 'inventori_uas');

  if ($test_conn->connect_error) {
    $error = "❌ Login gagal: Username atau password salah.";
  } else {
    // Simpan user & password ke session
    $_SESSION['db_user'] = $db_user;
    $_SESSION['db_pass'] = $db_pass;
    $_SESSION['client_name'] = $db_user; // nama client dipakai untuk locked_by

    $test_conn->close(); // tutup koneksi test
    header("Location: /final-project-sbd/index.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Client</title>
  <style>
    body {
      font-family: sans-serif;
      max-width: 400px;
      margin: 50px auto;
    }
    input {
      width: 100%;
      padding: 6px;
      margin-top: 5px;
      margin-bottom: 15px;
    }
    .error {
      color: red;
      margin-top: -10px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <h2>Login MySQL User</h2>
  <form method="POST">
    <label>Username:</label>
    <input type="text" name="db_user" required>

    <label>Password:</label>
    <input type="password" name="db_pass" required>

    <?php if (!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <button type="submit">Login</button>
  </form>
</body>
</html>
