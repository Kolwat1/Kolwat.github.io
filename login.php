<?php
session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $message = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } else {
        if (isset($_SESSION['users'])) {
            $found = false;
            foreach ($_SESSION['users'] as $user) {
                if ($user['username'] === $username && password_verify($password, $user['password'])) {
                    $found = true;
                    // เก็บสถานะล็อกอิน
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    header('Location: dashboard.php'); // หน้าเว็บหลังล็อกอิน
                    exit;
                }
            }
            if (!$found) {
                $message = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            }
        } else {
            $message = 'ไม่มีผู้ใช้ในระบบ กรุณาสมัครสมาชิกก่อน';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>เข้าสู่ระบบ</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-box {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 350px;
    }
    h2 {
      margin-top: 0;
      color: #0078d7;
      text-align: center;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
    }
    input[type=text],
    input[type=password] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      background: #0078d7;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background: #005ea2;
    }
    .message {
      margin-top: 15px;
      color: red;
      text-align: center;
      font-weight: 600;
    }
    .register-link {
      text-align: center;
      margin-top: 10px;
    }
    .register-link a {
      color: #0078d7;
      text-decoration: none;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>เข้าสู่ระบบ</h2>
    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="">
      <label for="username">ชื่อผู้ใช้</label>
      <input type="text" id="username" name="username" required />

      <label for="password">รหัสผ่าน</label>
      <input type="password" id="password" name="password" required />

      <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <div class="register-link">
      ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
    </div>
  </div>
</body>
</html>
