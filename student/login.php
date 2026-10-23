<?php
require 'crud_bootstrap.php';

// ถ้า login อยู่แล้ว ไม่ต้องเห็นหน้านี้
if (isset($_SESSION['user_id'])) {
    header('Location: skills.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql  = 'SELECT id, password_hash FROM users WHERE email = ? LIMIT 1';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userId, $hash);

    if (mysqli_stmt_fetch($stmt) && password_verify($password, $hash)) {
        mysqli_stmt_close($stmt);
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        header('Location: skills.php');
        exit;
    }

    mysqli_stmt_close($stmt);
    $error = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
}

page_start('เข้าสู่ระบบ');
?>


<?php if ($error): ?>
  <p class="err"><?= h($error) ?></p>
<?php endif; ?>

<form method="post">
  <?php csrf_field(); ?>
  <p>
    <label>อีเมล<br>
      <input type="email" name="email" required>
    </label>
  </p>
  <p>
    <label>รหัสผ่าน<br>
      <input type="password" name="password" required>
    </label>
  </p>
  <button type="submit">เข้าสู่ระบบ</button>
</form>
<?php page_end(); ?>