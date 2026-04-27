<?php
require_once __DIR__ . '/auth.php';
requireLogin();

define('CONTENT_FILE', __DIR__ . '/../data/content.json');

function getContent(): array {
    if (!file_exists(CONTENT_FILE)) return [];
    return json_decode(file_get_contents(CONTENT_FILE), true) ?? [];
}
function saveContent(array $data): void {
    file_put_contents(CONTENT_FILE, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$success = '';
$error   = '';
$ct = getContent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $section = $_POST['section'] ?? '';
    $allowed_sections = ['hero','about','services','gallery','contact'];
    if (!in_array($section, $allowed_sections, true)) {
        $error = 'قسم غير صحيح';
    } else {
        foreach ($_POST as $key => $val) {
            if ($key === 'csrf_token' || $key === 'section') continue;
            $ct[trim($key)] = trim($val);
        }
        saveContent($ct);
        $success = 'تم حفظ محتوى القسم بنجاح ✓';
    }
}

function cv(string $key): string {
    global $ct;
    return htmlspecialchars($ct[$key] ?? '');
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة المحتوى | لوحة التحكم</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <style>
    .content-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px; }
    .ctab {
      padding: 10px 20px;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 50px; color: var(--gray);
      font-family: inherit; font-size: 0.88rem; font-weight: 600;
      cursor: pointer; transition: all 0.3s;
    }
    .ctab.active, .ctab:hover {
      background: rgba(201,168,76,0.12);
      border-color: rgba(201,168,76,0.4);
      color: var(--gold);
    }
    .content-panel { display: none; }
    .content-panel.active { display: block; }
    .field-group {
      background: rgba(255,255,255,0.02);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: var(--radius-sm);
      padding: 20px; margin-bottom: 16px;
    }
    .field-group label {
      display: block; font-size: 0.8rem;
      color: var(--gold); font-weight: 700;
      margin-bottom: 8px; letter-spacing: 0.5px;
    }
    .field-group .form-input { width: 100%; }
    .field-group textarea.form-input { min-height: 90px; resize: vertical; }
    .fields-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media(max-width:600px){ .fields-grid { grid-template-columns: 1fr; } }
    .section-note {
      background: rgba(201,168,76,0.06);
      border: 1px solid rgba(201,168,76,0.2);
      border-radius: var(--radius-sm);
      padding: 12px 16px; margin-bottom: 20px;
      font-size: 0.82rem; color: var(--gray);
      display: flex; gap: 10px; align-items: flex-start;
    }
    .section-note i { color: var(--gold); margin-top: 2px; flex-shrink: 0; }
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
    <a href="/admin332233/content" class="nav-item active"><i class="fas fa-pen-to-square"></i> المحتوى</a>
    <a href="/admin332233/settings" class="nav-item"><i class="fas fa-cog"></i> الإعدادات</a>
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
    <div class="topbar-title">إدارة المحتوى</div>
    <div class="topbar-actions">
      <a href="/" target="_blank" class="admin-badge" style="text-decoration:none;">
        <i class="fas fa-eye"></i> معاينة الموقع
      </a>
    </div>
  </header>

  <div class="page-body">

    <?php if ($success): ?>
    <div class="alert success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Section Tabs -->
    <div class="content-tabs">
      <button class="ctab active" data-panel="hero"><i class="fas fa-home"></i> الرئيسية (Hero)</button>
      <button class="ctab" data-panel="about"><i class="fas fa-building"></i> من نحن</button>
      <button class="ctab" data-panel="services"><i class="fas fa-tools"></i> الخدمات</button>
      <button class="ctab" data-panel="gallery"><i class="fas fa-images"></i> المعرض</button>
      <button class="ctab" data-panel="contact"><i class="fas fa-phone"></i> التواصل</button>
    </div>

    <!-- ── Hero ── -->
    <div class="content-panel active" id="panel-hero">
      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="section" value="hero">
        <div class="section-note">
          <i class="fas fa-info-circle"></i>
          هذا القسم يتحكم في أول ما يراه الزائر عند فتح الموقع
        </div>
        <div class="field-group">
          <label>الشارة العلوية (Badge)</label>
          <input type="text" name="hero_badge" class="form-input" value="<?= cv('hero_badge') ?>">
        </div>
        <div class="fields-grid">
          <div class="field-group">
            <label>العنوان الرئيسي (السطر الأول - ذهبي)</label>
            <input type="text" name="hero_title_1" class="form-input" value="<?= cv('hero_title_1') ?>">
          </div>
          <div class="field-group">
            <label>العنوان الرئيسي (السطر الثاني)</label>
            <input type="text" name="hero_title_2" class="form-input" value="<?= cv('hero_title_2') ?>">
          </div>
        </div>
        <div class="field-group">
          <label>النص التوضيحي تحت العنوان</label>
          <textarea name="hero_subtitle" class="form-input"><?= cv('hero_subtitle') ?></textarea>
        </div>
        <button type="submit" class="btn-upload" style="width:auto;">
          <i class="fas fa-save"></i> حفظ قسم الرئيسية
        </button>
      </form>
    </div>

    <!-- ── About ── -->
    <div class="content-panel" id="panel-about">
      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="section" value="about">
        <div class="fields-grid">
          <div class="field-group">
            <label>العنوان الفرعي (Tag)</label>
            <input type="text" name="about_tag" class="form-input" value="<?= cv('about_tag') ?>">
          </div>
          <div class="field-group">
            <label>العنوان الرئيسي</label>
            <input type="text" name="about_title" class="form-input" value="<?= cv('about_title') ?>">
          </div>
        </div>
        <div class="field-group">
          <label>العنوان الذهبي</label>
          <input type="text" name="about_title_gold" class="form-input" value="<?= cv('about_title_gold') ?>">
        </div>
        <div class="field-group">
          <label>النص التعريفي عن الشركة</label>
          <textarea name="about_text" class="form-input" style="min-height:120px;"><?= cv('about_text') ?></textarea>
        </div>
        <div class="section-note">
          <i class="fas fa-star"></i>
          المميزات الأربع (تظهر مع أيقونة في قسم من نحن)
        </div>
        <div class="fields-grid">
          <div class="field-group">
            <label>ميزة 1 — العنوان</label>
            <input type="text" name="feature_1_title" class="form-input" value="<?= cv('feature_1_title') ?>">
          </div>
          <div class="field-group">
            <label>ميزة 1 — الوصف</label>
            <input type="text" name="feature_1_desc" class="form-input" value="<?= cv('feature_1_desc') ?>">
          </div>
          <div class="field-group">
            <label>ميزة 2 — العنوان</label>
            <input type="text" name="feature_2_title" class="form-input" value="<?= cv('feature_2_title') ?>">
          </div>
          <div class="field-group">
            <label>ميزة 2 — الوصف</label>
            <input type="text" name="feature_2_desc" class="form-input" value="<?= cv('feature_2_desc') ?>">
          </div>
          <div class="field-group">
            <label>ميزة 3 — العنوان</label>
            <input type="text" name="feature_3_title" class="form-input" value="<?= cv('feature_3_title') ?>">
          </div>
          <div class="field-group">
            <label>ميزة 3 — الوصف</label>
            <input type="text" name="feature_3_desc" class="form-input" value="<?= cv('feature_3_desc') ?>">
          </div>
          <div class="field-group">
            <label>ميزة 4 — العنوان</label>
            <input type="text" name="feature_4_title" class="form-input" value="<?= cv('feature_4_title') ?>">
          </div>
          <div class="field-group">
            <label>ميزة 4 — الوصف</label>
            <input type="text" name="feature_4_desc" class="form-input" value="<?= cv('feature_4_desc') ?>">
          </div>
        </div>
        <button type="submit" class="btn-upload" style="width:auto;">
          <i class="fas fa-save"></i> حفظ قسم من نحن
        </button>
      </form>
    </div>

    <!-- ── Services ── -->
    <div class="content-panel" id="panel-services">
      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="section" value="services">
        <?php for ($i = 1; $i <= 6; $i++): ?>
        <div class="section-note" style="margin-bottom:8px;">
          <i class="fas fa-briefcase"></i> الخدمة <?= $i ?>
        </div>
        <div class="fields-grid" style="margin-bottom:8px;">
          <div class="field-group">
            <label>العنوان</label>
            <input type="text" name="service_<?= $i ?>_title" class="form-input" value="<?= cv("service_{$i}_title") ?>">
          </div>
          <div class="field-group">
            <label>الوصف</label>
            <input type="text" name="service_<?= $i ?>_desc" class="form-input" value="<?= cv("service_{$i}_desc") ?>">
          </div>
        </div>
        <?php endfor; ?>
        <button type="submit" class="btn-upload" style="width:auto;">
          <i class="fas fa-save"></i> حفظ الخدمات
        </button>
      </form>
    </div>

    <!-- ── Gallery ── -->
    <div class="content-panel" id="panel-gallery">
      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="section" value="gallery">
        <div class="fields-grid">
          <div class="field-group">
            <label>عنوان قسم المعرض</label>
            <input type="text" name="gallery_title" class="form-input" value="<?= cv('gallery_title') ?>">
          </div>
          <div class="field-group">
            <label>العنوان الذهبي</label>
            <input type="text" name="gallery_title_gold" class="form-input" value="<?= cv('gallery_title_gold') ?>">
          </div>
        </div>
        <div class="field-group">
          <label>النص التوضيحي</label>
          <input type="text" name="gallery_desc" class="form-input" value="<?= cv('gallery_desc') ?>">
        </div>
        <button type="submit" class="btn-upload" style="width:auto;">
          <i class="fas fa-save"></i> حفظ قسم المعرض
        </button>
      </form>
    </div>

    <!-- ── Contact ── -->
    <div class="content-panel" id="panel-contact">
      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="section" value="contact">
        <div class="fields-grid">
          <div class="field-group">
            <label>عنوان قسم التواصل</label>
            <input type="text" name="contact_title" class="form-input" value="<?= cv('contact_title') ?>">
          </div>
          <div class="field-group">
            <label>العنوان الذهبي</label>
            <input type="text" name="contact_title_gold" class="form-input" value="<?= cv('contact_title_gold') ?>">
          </div>
        </div>
        <div class="field-group">
          <label>النص التوضيحي</label>
          <textarea name="contact_desc" class="form-input"><?= cv('contact_desc') ?></textarea>
        </div>
        <div class="field-group">
          <label>نص الفوتر (عن الشركة)</label>
          <textarea name="footer_about" class="form-input"><?= cv('footer_about') ?></textarea>
        </div>
        <button type="submit" class="btn-upload" style="width:auto;">
          <i class="fas fa-save"></i> حفظ قسم التواصل
        </button>
      </form>
    </div>

  </div>
</main>

<script>
  document.querySelector('.sidebar-toggle')?.addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
  });

  document.querySelectorAll('.ctab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.ctab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.content-panel').forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById('panel-' + tab.dataset.panel)?.classList.add('active');
    });
  });
</script>
</body>
</html>
