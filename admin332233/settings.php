<?php
require_once __DIR__ . '/auth.php';
requireLogin();

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    verifyCsrf();

    if ($_POST['action'] === 'change_password') {
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!password_verify($current, ADMIN_PASSWORD_HASH)) {
            $error = 'كلمة المرور الحالية غير صحيحة';
        } elseif (strlen($new) < 8) {
            $error = 'كلمة المرور الجديدة يجب أن تكون 8 أحرف على الأقل';
        } elseif ($new !== $confirm) {
            $error = 'كلمة المرور الجديدة وتأكيدها غير متطابقتين';
        } else {
            $newHash = password_hash($new, PASSWORD_BCRYPT);
            $configPath = __DIR__ . '/../data/config.php';
            $configContent = file_get_contents($configPath);
            $configContent = preg_replace(
                "/define\('ADMIN_PASSWORD_HASH',\s*'[^']+'\);/",
                "define('ADMIN_PASSWORD_HASH', '" . $newHash . "');",
                $configContent
            );
            file_put_contents($configPath, $configContent);
            $success = 'تم تغيير كلمة المرور بنجاح';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>الإعدادات | لوحة التحكم</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <img src="/assets/images/logo.png" alt="بنيان رسلان" onerror="this.style.display='none'">
    <div><div class="brand-name">بنيان رسلان</div><div class="brand-sub">لوحة التحكم</div></div>
  </div>
  <nav class="sidebar-nav">
    <a href="/admin332233/dashboard" class="nav-item"><i class="fas fa-chart-pie"></i> الرئيسية</a>
    <a href="/admin332233/dashboard#images" class="nav-item"><i class="fas fa-images"></i> الصور</a>
    <a href="/admin332233/dashboard#videos" class="nav-item"><i class="fas fa-film"></i> الفيديوهات</a>
    <a href="/admin332233/settings" class="nav-item active"><i class="fas fa-cog"></i> الإعدادات</a>
    <div class="nav-divider"></div>
    <a href="/" target="_blank" class="nav-item"><i class="fas fa-external-link-alt"></i> عرض الموقع</a>
    <a href="/admin332233/logout" class="nav-item danger"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
  </nav>
</aside>

<main class="main-content">
  <header class="topbar">
    <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
      <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title">الإعدادات</div>
    <div class="topbar-actions"><span class="admin-badge"><i class="fas fa-shield-alt"></i> مدير</span></div>
  </header>

  <div class="page-body">
    <div class="section-card" style="max-width:560px;">
      <div class="section-card-header">
        <h2><i class="fas fa-lock"></i> تغيير كلمة المرور</h2>
      </div>

      <?php if ($success): ?>
      <div class="alert success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
      <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" style="display:flex;flex-direction:column;gap:18px;">
        <input type="hidden" name="action" value="change_password">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
          <label>كلمة المرور الحالية</label>
          <input type="password" name="current_password" class="form-input" required placeholder="••••••••">
        </div>
        <div class="form-group">
          <label>كلمة المرور الجديدة</label>
          <input type="password" name="new_password" class="form-input" required placeholder="8 أحرف على الأقل">
        </div>
        <div class="form-group">
          <label>تأكيد كلمة المرور الجديدة</label>
          <input type="password" name="confirm_password" class="form-input" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn-upload" style="width:auto;align-self:flex-start;">
          <i class="fas fa-save"></i> حفظ التغييرات
        </button>
      </form>
    </div>
  </div>
</main>
<script>
  document.querySelector('.sidebar-toggle')?.addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
  });
</script>
</body>
</html>
