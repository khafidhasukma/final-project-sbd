<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_SESSION['db_user'])) {
  header("Location: /final-project-sbd/index.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db_user = trim($_POST['db_user'] ?? '');
  $db_pass = $_POST['db_pass'] ?? '';

  try {
    $test_conn = new mysqli('localhost', $db_user, $db_pass, 'inventori_uas');
    
    if ($test_conn->connect_errno === 0) {
      $_SESSION['db_user'] = $db_user;
      $_SESSION['db_pass'] = $db_pass;
      $_SESSION['client_name'] = $db_user;
      header("Location: /final-project-sbd/index.php");
      exit;
    }
  } catch (mysqli_sql_exception $e) {
    $error = "❌ Akses ditolak. User <b>$db_user</b> tidak memiliki izin ke database.";
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
