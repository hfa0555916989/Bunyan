<?php
require_once __DIR__ . '/auth.php';

$error = '';
$success = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if (login($u, $p)) {
        header('Location: /admin332233/dashboard');
        exit;
    }
    $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
}

// If logged in, go to dashboard
if (isLoggedIn()) {
    header('Location: /admin332233/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل الدخول | لوحة التحكم - بنيان رسلان</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --navy: #0b1629; --navy2: #162040; --gold: #c9a84c; --gold2: #d4af37;
      --white: #fff; --gray: #a0aec0; --radius: 16px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Tajawal', sans-serif;
      background: var(--navy);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      direction: rtl;
      background-image: radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.08) 0%, transparent 60%);
    }
    .login-card {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(201,168,76,0.2);
      border-radius: 24px;
      padding: 52px 44px;
      width: 420px; max-width: 95vw;
      backdrop-filter: blur(20px);
      box-shadow: 0 20px 80px rgba(0,0,0,0.4);
    }
    .login-header { text-align: center; margin-bottom: 40px; }
    .login-header img { height: 70px; margin-bottom: 16px; filter: drop-shadow(0 2px 8px rgba(201,168,76,0.3)); }
    .login-header .brand { font-size: 1.4rem; font-weight: 800; color: var(--gold); }
    .login-header .sub { font-size: 0.85rem; color: var(--gray); margin-top: 4px; }
    .divider { width: 40px; height: 2px; background: var(--gold); margin: 12px auto; border-radius: 2px; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 0.85rem; color: var(--gray); margin-bottom: 8px; font-weight: 600; }
    .input-wrap { position: relative; }
    .input-wrap i { position: absolute; top: 50%; right: 16px; transform: translateY(-50%); color: var(--gray); font-size: 1rem; }
    .input-wrap input {
      width: 100%; padding: 14px 44px 14px 16px;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 10px; color: var(--white);
      font-family: inherit; font-size: 0.95rem;
      transition: all 0.3s;
    }
    .input-wrap input:focus { outline: none; border-color: rgba(201,168,76,0.5); background: rgba(201,168,76,0.05); }
    .input-wrap input::placeholder { color: rgba(160,174,192,0.5); }

    .btn-login {
      width: 100%; padding: 15px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: var(--navy); font-size: 1rem; font-weight: 800;
      border: none; border-radius: 10px;
      cursor: pointer; font-family: inherit;
      transition: all 0.3s;
      margin-top: 8px;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(201,168,76,0.4); }

    .error-msg {
      background: rgba(252,129,74,0.12);
      border: 1px solid rgba(252,129,74,0.3);
      color: #fc8181; border-radius: 10px;
      padding: 12px 16px; font-size: 0.88rem;
      margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
    }
    .back-link {
      display: block; text-align: center; margin-top: 24px;
      color: var(--gray); font-size: 0.85rem; text-decoration: none;
      transition: color 0.3s;
    }
    .back-link:hover { color: var(--gold); }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-header">
      <img src="/assets/images/logo.png" alt="بنيان رسلان" onerror="this.style.display='none'">
      <div class="brand">بنيان رسلان</div>
      <div class="sub">لوحة التحكم الإدارية</div>
      <div class="divider"></div>
    </div>

    <?php if ($error): ?>
    <div class="error-msg"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="action" value="login">
      <div class="form-group">
        <label>اسم المستخدم</label>
        <div class="input-wrap">
          <i class="fas fa-user"></i>
          <input type="text" name="username" placeholder="اسم المستخدم" autocomplete="username" required>
        </div>
      </div>
      <div class="form-group">
        <label>كلمة المرور</label>
        <div class="input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
        </div>
      </div>
      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt"></i> دخول
      </button>
    </form>

    <a href="/" class="back-link"><i class="fas fa-arrow-right" style="margin-left:4px;"></i> العودة إلى الموقع</a>
  </div>
</body>
</html>
