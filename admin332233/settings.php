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

    if ($action === 'upload_logo') {
        if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            $error = 'الرجاء اختيار ملف صورة';
        } else {
            $mime = mime_content_type($_FILES['logo']['tmp_name']);
            if (!in_array($mime, ['image/jpeg','image/png','image/webp','image/svg+xml'], true)) {
                $error = 'يُسمح فقط بـ PNG, JPG, WebP, SVG';
            } elseif ($_FILES['logo']['size'] > 5 * 1024 * 1024) {
                $error = 'حجم الشعار يتجاوز 5 MB';
            } else {
                $ext     = in_array($mime, ['image/svg+xml']) ? 'svg' : pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $destDir = __DIR__ . '/../assets/images/';
                if (!is_dir($destDir)) mkdir($destDir, 0755, true);
                // Keep backup of old logo
                $oldLogo = $destDir . 'logo.png';
                if (file_exists($oldLogo)) copy($oldLogo, $destDir . 'logo_backup.png');
                $newFile = $destDir . 'logo.' . strtolower($ext ?: 'png');
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $newFile)) {
                    // If new ext differs from png, update pointer
                    $s['logo_file'] = 'logo.' . strtolower($ext ?: 'png');
                    saveSettings($s);
                    $success = 'تم رفع الشعار بنجاح! سيظهر على الموقع فوراً';
                } else {
                    $error = 'فشل في حفظ الشعار، تحقق من صلاحيات المجلد';
                }
            }
        }
    }

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

    if ($action === 'save_services') {
        $raw = $_POST['service_options'] ?? '';
        // Clean lines
        $lines = array_filter(array_map('trim', explode("\n", str_replace("\r", '', $raw))));
        $s['service_options'] = implode("\n", array_values($lines));
        saveSettings($s);
        $success = 'تم حفظ خيارات الخدمة بنجاح';
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
    .logo-upload-wrap {
      display: flex; gap: 24px; align-items: center; flex-wrap: wrap;
    }
    .logo-preview-box {
      width: 140px; height: 100px; flex-shrink: 0;
      background: rgba(255,255,255,0.04);
      border: 2px dashed rgba(201,168,76,0.3);
      border-radius: var(--radius-sm);
      display: flex; align-items: center; justify-content: center;
      overflow: hidden;
    }
    .logo-preview-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .logo-preview-box .no-logo { color: var(--gray); font-size: 0.78rem; text-align: center; padding: 8px; }
    .logo-drop {
      flex: 1;
      border: 2px dashed rgba(255,255,255,0.12);
      border-radius: var(--radius-sm);
      padding: 28px 20px;
      text-align: center; cursor: pointer;
      transition: all 0.3s;
    }
    .logo-drop:hover { border-color: var(--gold); background: rgba(201,168,76,0.04); }
    .logo-drop .drop-icon { font-size: 1.8rem; color: var(--gold); opacity: 0.7; margin-bottom: 8px; }
    .logo-drop .drop-text { font-size: 0.9rem; color: var(--white); }
    .logo-drop .drop-hint { font-size: 0.75rem; color: var(--gray); margin-top: 4px; }
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

    <!-- ─── رفع الشعار (full width) ─── -->
    <div class="settings-card" style="margin-bottom:24px;">
      <div class="settings-card-header">
        <i class="fas fa-image"></i> شعار الموقع
      </div>
      <div class="settings-card-body">
        <form method="POST" enctype="multipart/form-data" id="logoForm">
          <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
          <input type="hidden" name="action" value="upload_logo">
          <div class="logo-upload-wrap">
            <div class="logo-preview-box" id="logoPreviewBox">
              <?php
                $logoFile = $s['logo_file'] ?? 'logo.png';
                $logoPath = __DIR__ . '/../assets/images/' . $logoFile;
              ?>
              <?php if (file_exists($logoPath)): ?>
                <img src="/assets/images/<?= htmlspecialchars($logoFile) ?>?v=<?= filemtime($logoPath) ?>" id="logoPreviewImg" alt="الشعار الحالي">
              <?php else: ?>
                <div class="no-logo" id="logoPreviewImg"><i class="fas fa-image" style="font-size:2rem;display:block;margin-bottom:6px;"></i>لا يوجد شعار</div>
              <?php endif; ?>
            </div>
            <div class="logo-drop" id="logoDrop" onclick="document.getElementById('logoFile').click()">
              <div class="drop-icon"><i class="fas fa-cloud-upload-alt"></i></div>
              <div class="drop-text">اضغط لاختيار الشعار أو اسحبه هنا</div>
              <div class="drop-hint">PNG, JPG, WebP, SVG — حد أقصى 5 MB</div>
              <input type="file" name="logo" id="logoFile" accept="image/png,image/jpeg,image/webp,image/svg+xml" hidden>
            </div>
          </div>
          <div style="margin-top:16px;display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <button type="submit" class="btn-upload" style="width:auto;" id="logoBtn">
              <i class="fas fa-upload"></i> رفع الشعار
            </button>
            <span style="font-size:0.8rem;color:var(--gray);">
              <i class="fas fa-info-circle" style="color:var(--gold);"></i>
              سيتم تحديث الشعار في كل صفحات الموقع فوراً بعد الرفع
            </span>
          </div>
        </form>
      </div>
    </div>

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

      <!-- ─── خيارات الخدمة ─── -->
      <div class="settings-card">
        <div class="settings-card-header">
          <i class="fas fa-list-check"></i> خيارات نوع الخدمة في فورم التواصل
        </div>
        <div class="settings-card-body">
          <form method="POST">
            <input type="hidden" name="action" value="save_services">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group">
              <label>الخيارات (كل خيار في سطر منفصل)</label>
              <textarea
                name="service_options"
                class="form-input"
                style="min-height:180px;line-height:2;"
                placeholder="مقاولات عامة&#10;بناء وتشييد&#10;تشطيب وديكور&#10;أخرى"
              ><?= htmlspecialchars($s['service_options'] ?? '') ?></textarea>
              <small style="color:var(--gray);font-size:0.75rem;margin-top:6px;display:block;">
                <i class="fas fa-info-circle" style="color:var(--gold);"></i>
                اكتب كل خيار في سطر جديد — سيظهر مباشرة في قائمة الخدمة بالموقع
              </small>
            </div>
            <button type="submit" class="btn-upload">
              <i class="fas fa-save"></i> حفظ الخيارات
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

  // Logo preview before upload
  const logoFile = document.getElementById('logoFile');
  const logoDrop = document.getElementById('logoDrop');
  const previewBox = document.getElementById('logoPreviewBox');

  logoFile?.addEventListener('change', () => {
    const file = logoFile.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      previewBox.innerHTML = `<img src="${e.target.result}" style="max-width:100%;max-height:100%;object-fit:contain;" alt="معاينة">`;
    };
    reader.readAsDataURL(file);
    logoDrop.querySelector('.drop-text').textContent = file.name;
  });

  // Drag & Drop for logo
  logoDrop?.addEventListener('dragover', e => { e.preventDefault(); logoDrop.style.borderColor = 'var(--gold)'; });
  logoDrop?.addEventListener('dragleave', () => { logoDrop.style.borderColor = ''; });
  logoDrop?.addEventListener('drop', e => {
    e.preventDefault(); logoDrop.style.borderColor = '';
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer(); dt.items.add(file); logoFile.files = dt.files;
    logoFile.dispatchEvent(new Event('change'));
  });
</script>
</body>
</html>
