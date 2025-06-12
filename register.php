<?php
session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password || !$confirm_password) {
        $message = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'อีเมลไม่ถูกต้อง';
    } elseif ($password !== $confirm_password) {
        $message = 'รหัสผ่านไม่ตรงกัน';
    } else {
        // เก็บข้อมูลสมาชิกใน Session (จำลอง)
        if (!isset($_SESSION['users'])) {
            $_SESSION['users'] = [];
        }

        // ตรวจสอบ username หรือ email ซ้ำใน session
        foreach ($_SESSION['users'] as $user) {
            if ($user['username'] === $username) {
                $message = 'ชื่อผู้ใช้นี้ถูกใช้แล้ว';
                break;
            }
            if ($user['email'] === $email) {
                $message = 'อีเมลนี้ถูกใช้แล้ว';
                break;
            }
        }

        if (!$message) {
            // เก็บข้อมูล
            $_SESSION['users'][] = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $message = 'สมัครสมาชิกสำเร็จ (ข้อมูลเก็บใน session เท่านั้น)';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>สมัครสมาชิก (No DB)</title>
  <style>
    /* ใช้ style เดียวกับตัวอย่างก่อนหน้า */
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
    .register-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
    h2 { margin-top: 0; color: #0078d7; text-align: center; }
    label { display: block; margin-top: 15px; font-weight: 600; }
    input[type=text], input[type=email], input[type=password] { width: 100%; padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box; }
    button { margin-top: 20px; width: 100%; padding: 10px; background: #0078d7; border: none; color: white; font-size: 16px; border-radius: 4px; cursor: pointer; }
    button:hover { background: #005ea2; }
    .message { margin-top: 15px; color: <?= $message === 'สมัครสมาชิกสำเร็จ (ข้อมูลเก็บใน session เท่านั้น)' ? 'green' : 'red' ?>; text-align: center; font-weight: 600; }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>สมัครสมาชิก (No DB)</h2>
    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="">
      <label for="username">ชื่อผู้ใช้</label>
      <input type="text" id="username" name="username" required />

      <label for="email">อีเมล</label>
      <input type="email" id="email" name="email" required />

      <label for="password">รหัสผ่าน</label>
      <input type="password" id="password" name="password" required />

      <label for="confirm_password">ยืนยันรหัสผ่าน</label>
      <input type="password" id="confirm_password" name="confirm_password" required />

      <button type="submit">สมัครสมาชิก</button>
    </form>
  </div>
</body>
</html>
