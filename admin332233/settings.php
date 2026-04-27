<?php
require_once __DIR__ . '/auth.php';
requireLogin();

define('SETTINGS_FILE', __DIR__ . '/../data/settings.json');

function getSettings(): array {
    $defaults = [
        'phone'          => '+966500000000',
        'whatsapp'       => '+966500000000',
        'tiktok'         => 'https://www.tiktok.com/@bunyanraslan',
        'email'          => 'info@bunyanraslan.com',
        'stats_projects' => '150',
        'stats_years'    => '12',
        'stats_clients'  => '5000',
        'stats_cities'   => '25',
    ];
    if (!file_exists(SETTINGS_FILE)) return $defaults;
    $data = json_decode(file_get_contents(SETTINGS_FILE), true);
    return array_merge($defaults, $data ?? []);
}

function saveSettings(array $data): void {
    file_put_contents(SETTINGS_FILE, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$success = '';
$error   = '';
$s = getSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'save_contact') {
        $phone    = trim($_POST['phone'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $tiktok   = trim($_POST['tiktok'] ?? '');
        $email    = trim($_POST['email'] ?? '');

        if (!$phone || !$whatsapp) {
            $error = 'رقم الجوال والواتساب مطلوبان';
        } else {
            $s['phone']    = $phone;
            $s['whatsapp'] = $whatsapp;
            $s['tiktok']   = $tiktok;
            $s['email']    = $email;
            saveSettings($s);
            $success = 'تم حفظ بيانات التواصل بنجاح';
        }
    }

    if ($action === 'save_stats') {
        $s['stats_projects'] = (int)($_POST['stats_projects'] ?? 150);
        $s['stats_years']    = (int)($_POST['stats_years']    ?? 12);
        $s['stats_clients']  = (int)($_POST['stats_clients']  ?? 5000);
        $s['stats_cities']   = (int)($_POST['stats_cities']   ?? 25);
        saveSettings($s);
        $success = 'تم حفظ الإحصائيات بنجاح';
    }

    if ($action === 'change_password') {
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
  <title>الإعدادات | لوحة التحكم - بنيان رسلان</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <style>
    .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    @media(max-width:768px){ .settings-grid { grid-template-columns: 1fr; } }
    .settings-card {
      background: var(--navy2);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: var(--radius);
      overflow: hidden;
    }
    .settings-card-header {
      padding: 18px 24px;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      display: flex; align-items: center; gap: 10px;
      font-size: 1rem; font-weight: 700;
    }
    .settings-card-header i { color: var(--gold); }
    .settings-card-body { padding: 24px; display: flex; flex-direction: column; gap: 16px; }
    .input-icon-wrap { position: relative; }
    .input-icon-wrap i {
      position: absolute; top: 50%; right: 14px;
      transform: translateY(-50%); color: var(--gray); font-size: 0.95rem;
      pointer-events: none;
    }
    .input-icon-wrap .form-input { padding-right: 40px; }
    .stat-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .stat-preview {
      text-align: center;
      padding: 12px;
      background: rgba(201,168,76,0.06);
      border: 1px solid rgba(201,168,76,0.15);
      border-radius: var(--radius-sm);
      margin-top: 4px;
    }
    .stat-preview .big { font-size: 1.8rem; font-weight: 900; color: var(--gold); }
    .stat-preview .lbl { font-size: 0.75rem; color: var(--gray); }
  </style>
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

    <?php if ($success): ?>
    <div class="alert success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="settings-grid">

      <!-- ─── بيانات التواصل ─── -->
      <div class="settings-card">
        <div class="settings-card-header">
          <i class="fas fa-address-book"></i> بيانات التواصل
        </div>
        <div class="settings-card-body">
          <form method="POST">
            <input type="hidden" name="action" value="save_contact">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
              <label>رقم الجوال</label>
              <div class="input-icon-wrap">
                <i class="fas fa-phone-alt"></i>
                <input type="text" name="phone" class="form-input"
                  value="<?= htmlspecialchars($s['phone']) ?>"
                  placeholder="+966500000000">
              </div>
            </div>

            <div class="form-group">
              <label>رقم الواتساب</label>
              <div class="input-icon-wrap">
                <i class="fab fa-whatsapp"></i>
                <input type="text" name="whatsapp" class="form-input"
                  value="<?= htmlspecialchars($s['whatsapp']) ?>"
                  placeholder="+966500000000">
              </div>
              <small style="color:var(--gray);font-size:0.75rem;margin-top:4px;display:block;">
                أدخل الرقم مع رمز الدولة بدون مسافات
              </small>
            </div>

            <div class="form-group">
              <label>رابط تيك توك</label>
              <div class="input-icon-wrap">
                <i class="fab fa-tiktok"></i>
                <input type="text" name="tiktok" class="form-input"
                  value="<?= htmlspecialchars($s['tiktok']) ?>"
                  placeholder="https://www.tiktok.com/@username">
              </div>
            </div>

            <div class="form-group">
              <label>البريد الإلكتروني</label>
              <div class="input-icon-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-input"
                  value="<?= htmlspecialchars($s['email']) ?>"
                  placeholder="info@bunyanraslan.com">
              </div>
            </div>

            <button type="submit" class="btn-upload">
              <i class="fas fa-save"></i> حفظ بيانات التواصل
            </button>
          </form>
        </div>
      </div>

      <!-- ─── الإحصائيات ─── -->
      <div class="settings-card">
        <div class="settings-card-header">
          <i class="fas fa-chart-bar"></i> إحصائيات الموقع
        </div>
        <div class="settings-card-body">
          <form method="POST">
            <input type="hidden" name="action" value="save_stats">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="stat-inputs">
              <div class="form-group">
                <label>المشاريع المنجزة</label>
                <input type="number" name="stats_projects" class="form-input"
                  value="<?= htmlspecialchars($s['stats_projects']) ?>"
                  min="0" max="9999" id="inp_projects">
                <div class="stat-preview">
                  <div class="big" id="prev_projects">+<?= htmlspecialchars($s['stats_projects']) ?></div>
                  <div class="lbl">مشروع منجز</div>
                </div>
              </div>

              <div class="form-group">
                <label>سنوات الخبرة</label>
                <input type="number" name="stats_years" class="form-input"
                  value="<?= htmlspecialchars($s['stats_years']) ?>"
                  min="0" max="99" id="inp_years">
                <div class="stat-preview">
                  <div class="big" id="prev_years">+<?= htmlspecialchars($s['stats_years']) ?></div>
                  <div class="lbl">سنة خبرة</div>
                </div>
              </div>

              <div class="form-group">
                <label>العملاء الراضون</label>
                <input type="number" name="stats_clients" class="form-input"
                  value="<?= htmlspecialchars($s['stats_clients']) ?>"
                  min="0" max="999999" id="inp_clients">
                <div class="stat-preview">
                  <div class="big" id="prev_clients">+<?= htmlspecialchars($s['stats_clients']) ?></div>
                  <div class="lbl">عميل راضٍ</div>
                </div>
              </div>

              <div class="form-group">
                <label>عدد المدن</label>
                <input type="number" name="stats_cities" class="form-input"
                  value="<?= htmlspecialchars($s['stats_cities']) ?>"
                  min="0" max="999" id="inp_cities">
                <div class="stat-preview">
                  <div class="big" id="prev_cities">+<?= htmlspecialchars($s['stats_cities']) ?></div>
                  <div class="lbl">مدينة</div>
                </div>
              </div>
            </div>

            <button type="submit" class="btn-upload" style="margin-top:20px;">
              <i class="fas fa-save"></i> حفظ الإحصائيات
            </button>
          </form>
        </div>
      </div>

      <!-- ─── تغيير كلمة المرور ─── -->
      <div class="settings-card">
        <div class="settings-card-header">
          <i class="fas fa-lock"></i> تغيير كلمة المرور
        </div>
        <div class="settings-card-body">
          <form method="POST">
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
            <button type="submit" class="btn-upload">
              <i class="fas fa-key"></i> تغيير كلمة المرور
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</main>

<script>
  document.querySelector('.sidebar-toggle')?.addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
  });

  // Live preview for stats
  [['inp_projects','prev_projects'],['inp_years','prev_years'],
   ['inp_clients','prev_clients'],['inp_cities','prev_cities']].forEach(([inpId, prevId]) => {
    const inp = document.getElementById(inpId);
    const prev = document.getElementById(prevId);
    if (!inp || !prev) return;
    inp.addEventListener('input', () => {
      prev.textContent = '+' + (inp.value || '0');
    });
  });
</script>
</body>
</html>
