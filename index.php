<?php
require_once __DIR__ . '/data/config.php';

// Load media data
$media = json_decode(file_get_contents(DATA_FILE), true) ?? ['images' => [], 'videos' => []];
$images = $media['images'] ?? [];
$videos = $media['videos'] ?? [];

// Load dynamic settings
$_settingsFile = __DIR__ . '/data/settings.json';
$_settingsDefaults = [
    'phone'          => SITE_PHONE,
    'whatsapp'       => SITE_WHATSAPP,
    'tiktok'         => SITE_TIKTOK,
    'email'          => SITE_EMAIL,
    'stats_projects' => '150',
    'stats_years'    => '12',
    'stats_clients'  => '5000',
    'stats_cities'   => '25',
];
$cfg = array_merge($_settingsDefaults,
    file_exists($_settingsFile) ? (json_decode(file_get_contents($_settingsFile), true) ?? []) : []
);
$phone    = $cfg['phone'];
$whatsapp = preg_replace('/[^0-9]/', '', $cfg['whatsapp']);
$logoFile = $cfg['logo_file'] ?? 'logo.png';
$logoSrc  = '/assets/images/' . htmlspecialchars($logoFile);
$logoCacheBust = file_exists(__DIR__ . '/assets/images/' . $logoFile)
    ? '?v=' . filemtime(__DIR__ . '/assets/images/' . $logoFile)
    : '';

// Load dynamic content
$_contentFile = __DIR__ . '/data/content.json';
$_contentDefaults = [
    'hero_badge'        => 'شركة رائدة في مجال المقاولات',
    'hero_title_1'      => 'بنيان رسلان',
    'hero_title_2'      => 'للمقاولات',
    'hero_subtitle'     => 'ننفذ مشاريع البناء والمقاولات بأعلى معايير الجودة، مع فريق متكامل من المهندسين المعتمدين والمشرفين المتخصصين في كل مشروع',
    'about_tag'         => 'من نحن',
    'about_title'       => 'نبني بإتقان',
    'about_title_gold'  => 'بمهندسين ومشرفين محترفين',
    'about_text'        => 'بنيان رسلان للمقاولات شركة متخصصة في تنفيذ مشاريع البناء والتشييد.',
    'feature_1_title'   => 'مهندسون معتمدون',
    'feature_1_desc'    => 'لكل مشروع فريق هندسي متخصص يشرف على كل مرحلة',
    'feature_2_title'   => 'مشرفون ميدانيون',
    'feature_2_desc'    => 'إشراف يومي على الموقع لضمان الجودة والسلامة',
    'feature_3_title'   => 'جودة مواد البناء',
    'feature_3_desc'    => 'نستخدم أفضل مواد البناء المعتمدة والموثوقة',
    'feature_4_title'   => 'التزام بالمواعيد',
    'feature_4_desc'    => 'ننجز مشاريعنا في الوقت المحدد دون تأخير',
    'service_1_title'   => 'المقاولات العامة',
    'service_1_desc'    => 'تنفيذ مشاريع البناء الكاملة من الأساسات حتى التسليم',
    'service_2_title'   => 'البناء والتشييد',
    'service_2_desc'    => 'إنشاء المباني السكنية والتجارية بأعلى معايير الجودة',
    'service_3_title'   => 'الخرسانة والهيكل الإنشائي',
    'service_3_desc'    => 'أعمال الخرسانة المسلحة بإشراف مهندسين إنشائيين معتمدين',
    'service_4_title'   => 'التشطيب والديكور',
    'service_4_desc'    => 'أعمال التشطيبات الداخلية والخارجية بأرقى المواصفات',
    'service_5_title'   => 'البنية التحتية',
    'service_5_desc'    => 'تنفيذ أعمال الطرق والشبكات والبنية التحتية',
    'service_6_title'   => 'الصيانة والإصلاح',
    'service_6_desc'    => 'خدمات صيانة شاملة للمباني والمنشآت',
    'gallery_title'     => 'مشاريعنا',
    'gallery_title_gold'=> 'بالصور والفيديو',
    'gallery_desc'      => 'استعرض أحدث مشاريع المقاولات وإنجازاتنا الميدانية',
    'contact_title'     => 'نحن هنا',
    'contact_title_gold'=> 'لخدمتك',
    'contact_desc'      => 'تواصل معنا الآن للحصول على عرض سعر مجاني وفريق من المهندسين جاهز لخدمتك',
    'footer_about'      => 'شركة متخصصة في مجال المقاولات والبناء والتشييد بأعلى معايير الجودة.',
];
$c = array_merge($_contentDefaults,
    file_exists($_contentFile) ? (json_decode(file_get_contents($_contentFile), true) ?? []) : []
);
function ct(string $key): string {
    global $c;
    return htmlspecialchars($c[$key] ?? '');
}
$tiktok   = $cfg['tiktok'];
$email    = $cfg['email'];

// SEO
$page_title = SITE_NAME . ' | ' . SITE_NAME_EN;
$page_desc = 'بنيان رسلان العقارية - شركة رائدة في مجال التطوير العقاري، نقدم أفضل المشاريع السكنية والتجارية بأعلى معايير الجودة والتميز. تواصل معنا اليوم.';
$page_keywords = 'بنيان رسلان, عقارات, تطوير عقاري, مشاريع سكنية, مشاريع تجارية, عقار, شقق, فلل, مجمعات سكنية, استثمار عقاري';
$og_image = SITE_URL . '/assets/images/og-image.jpg';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Primary SEO -->
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
  <meta name="author" content="بنيان رسلان العقارية">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <meta name="language" content="ar">
  <link rel="canonical" href="<?= SITE_URL ?>/">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= SITE_URL ?>/">
  <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta property="og:image" content="<?= $og_image ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:site_name" content="<?= SITE_NAME ?>">
  <meta property="og:locale" content="ar_SA">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
  <meta name="twitter:image" content="<?= $og_image ?>">

  <!-- Geo SEO -->
  <meta name="geo.region" content="SA">
  <meta name="geo.placename" content="المملكة العقربية السعودية">

  <!-- Structured Data - Organization -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "RealEstateAgent",
    "name": "<?= SITE_NAME ?>",
    "alternateName": "<?= SITE_NAME_EN ?>",
    "url": "<?= SITE_URL ?>",
    "logo": "<?= SITE_URL ?>/assets/images/<?= htmlspecialchars($logoFile) ?>",
    "description": "<?= $page_desc ?>",
    "telephone": "<?= htmlspecialchars($phone) ?>",
    "email": "<?= htmlspecialchars($email) ?>",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "SA"
    },
    "sameAs": [
      "<?= htmlspecialchars($tiktok) ?>"
    ],
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "<?= htmlspecialchars($phone) ?>",
      "contactType": "customer service",
      "areaServed": "SA",
      "availableLanguage": "Arabic"
    }
  }
  </script>

  <!-- Website Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "<?= SITE_NAME ?>",
    "url": "<?= SITE_URL ?>",
    "inLanguage": "ar",
    "potentialAction": {
      "@type": "SearchAction",
      "target": {
        "@type": "EntryPoint",
        "urlTemplate": "<?= SITE_URL ?>/?s={search_term_string}"
      },
      "query-input": "required name=search_term_string"
    }
  }
  </script>

  <!-- Breadcrumb Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
      "@type": "ListItem",
      "position": 1,
      "name": "الرئيسية",
      "item": "<?= SITE_URL ?>/"
    }]
  }
  </script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/style.css">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/images/favicon.png">
  <link rel="apple-touch-icon" href="/assets/images/favicon.png">

  <!-- Theme Color -->
  <meta name="theme-color" content="#0b1629">
</head>
<body>

<!-- Loader -->
<div id="loader">
  <img src="<?= $logoSrc . $logoCacheBust ?>" alt="<?= SITE_NAME ?>" class="loader-logo" onerror="this.style.display='none'">
  <div style="font-size:1.5rem;font-weight:900;color:#c9a84c;font-family:Tajawal,sans-serif;">بنيان رسلان</div>
  <div class="loader-bar"></div>
</div>

<!-- Particles -->
<canvas id="particles-canvas"></canvas>

<!-- ═══ NAVIGATION ═══ -->
<nav id="navbar">
  <div class="nav-inner">
    <a href="/" class="nav-logo">
      <img src="<?= $logoSrc . $logoCacheBust ?>" alt="<?= SITE_NAME ?>" onerror="this.style.display='none'">
    </a>

    <ul class="nav-links">
      <li><a href="#hero">الرئيسية</a></li>
      <li><a href="#about">من نحن</a></li>
      <li><a href="#services">خدماتنا</a></li>
      <li><a href="#gallery">معرضنا</a></li>
      <li><a href="#contact">تواصل معنا</a></li>
    </ul>

    <div class="nav-actions">
      <a href="https://wa.me/<?= $whatsapp ?>" target="_blank" class="btn-whatsapp-nav">
        <i class="fab fa-whatsapp"></i>
        <span>واتساب</span>
      </a>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
    </div>
  </div>
</nav>

<!-- ═══ HERO ═══ -->
<section id="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>

  <div class="hero-content">
    <div class="hero-badge">
      <span class="dot"></span>
      <?= ct('hero_badge') ?>
    </div>

    <h1 class="hero-title">
      <span class="gold"><?= ct('hero_title_1') ?></span><br>
      <span><?= ct('hero_title_2') ?></span>
    </h1>

    <p class="hero-subtitle">
      <?= ct('hero_subtitle') ?>
    </p>

    <div class="hero-cta">
      <a href="#gallery" class="btn-primary">
        <i class="fas fa-images"></i>
        مشاريعنا
      </a>
      <a href="https://wa.me/<?= $whatsapp ?>" target="_blank" class="btn-secondary">
        <i class="fab fa-whatsapp"></i>
        تواصل معنا
      </a>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num" data-target="<?= (int)$cfg['stats_projects'] ?>" data-suffix="+">0+</div>
        <div class="stat-label">مشروع منجز</div>
      </div>
      <div class="stat-item">
        <div class="stat-num" data-target="<?= (int)$cfg['stats_years'] ?>" data-suffix="+">0+</div>
        <div class="stat-label">سنة خبرة</div>
      </div>
      <div class="stat-item">
        <div class="stat-num" data-target="<?= (int)$cfg['stats_clients'] ?>" data-suffix="+">0+</div>
        <div class="stat-label">عميل راضٍ</div>
      </div>
      <div class="stat-item">
        <div class="stat-num" data-target="<?= (int)$cfg['stats_cities'] ?>" data-suffix="+">0+</div>
        <div class="stat-label">مدينة</div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ ABOUT ═══ -->
<section id="about">
  <div class="container">
    <div class="about-grid">
      <div class="about-image reveal">
        <img src="/assets/images/about.jpg" alt="بنيان رسلان للمقاولات" onerror="this.src='https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80'">
        <div class="overlay-badge">
          <div class="num">+<?= (int)$cfg['stats_years'] ?></div>
          <div class="lbl">سنة من الخبرة</div>
        </div>
      </div>

      <div class="about-content">
        <div class="section-header" style="text-align:right;margin-bottom:32px;">
          <div class="section-tag"><?= ct('about_tag') ?></div>
          <h2 class="section-title"><?= ct('about_title') ?> <span class="gold"><?= ct('about_title_gold') ?></span></h2>
          <div class="gold-line" style="margin:16px 0 0;"></div>
        </div>

        <p class="reveal"><?= ct('about_text') ?></p>

        <div class="about-features">
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-hard-hat"></i></div>
            <div class="feature-text">
              <h4><?= ct('feature_1_title') ?></h4>
              <p><?= ct('feature_1_desc') ?></p>
            </div>
          </div>
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-user-tie"></i></div>
            <div class="feature-text">
              <h4><?= ct('feature_2_title') ?></h4>
              <p><?= ct('feature_2_desc') ?></p>
            </div>
          </div>
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-medal"></i></div>
            <div class="feature-text">
              <h4><?= ct('feature_3_title') ?></h4>
              <p><?= ct('feature_3_desc') ?></p>
            </div>
          </div>
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-clock"></i></div>
            <div class="feature-text">
              <h4><?= ct('feature_4_title') ?></h4>
              <p><?= ct('feature_4_desc') ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ SERVICES ═══ -->
<section id="services">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag">خدماتنا</div>
      <h2 class="section-title">ماذا <span class="gold">نقدم لك؟</span></h2>
      <p class="section-desc">نوفر خدمات مقاولات متكاملة مع فريق من المهندسين والمشرفين لكل مشروع</p>
      <div class="gold-line"></div>
    </div>

    <div class="services-grid">
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-building"></i></div>
        <h3><?= ct('service_1_title') ?></h3>
        <p><?= ct('service_1_desc') ?></p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-hard-hat"></i></div>
        <h3><?= ct('service_2_title') ?></h3>
        <p><?= ct('service_2_desc') ?></p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-drafting-compass"></i></div>
        <h3><?= ct('service_3_title') ?></h3>
        <p><?= ct('service_3_desc') ?></p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-paint-roller"></i></div>
        <h3><?= ct('service_4_title') ?></h3>
        <p><?= ct('service_4_desc') ?></p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-road"></i></div>
        <h3><?= ct('service_5_title') ?></h3>
        <p><?= ct('service_5_desc') ?></p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-tools"></i></div>
        <h3><?= ct('service_6_title') ?></h3>
        <p><?= ct('service_6_desc') ?></p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ GALLERY ═══ -->
<section id="gallery">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag">معرضنا</div>
      <h2 class="section-title"><?= ct('gallery_title') ?> <span class="gold"><?= ct('gallery_title_gold') ?></span></h2>
      <p class="section-desc"><?= ct('gallery_desc') ?></p>
      <div class="gold-line"></div>
    </div>

    <div class="gallery-tabs reveal">
      <button class="tab-btn active" data-filter="all">الكل</button>
      <button class="tab-btn" data-filter="image">الصور</button>
      <button class="tab-btn" data-filter="video">الفيديوهات</button>
    </div>

    <div class="gallery-grid" id="gallery-grid">
      <?php if (empty($images) && empty($videos)): ?>
      <div class="gallery-empty">
        <div class="icon"><i class="fas fa-photo-film"></i></div>
        <p>سيتم إضافة الصور والفيديوهات قريباً</p>
      </div>
      <?php else: ?>

        <?php foreach ($images as $img): ?>
        <div class="gallery-item reveal" data-type="image" data-src="/uploads/images/<?= htmlspecialchars($img['file']) ?>">
          <img src="/uploads/images/<?= htmlspecialchars($img['file']) ?>" alt="<?= htmlspecialchars($img['title'] ?? 'مشروع') ?>" loading="lazy">
          <div class="gallery-overlay">
            <div class="gallery-overlay-content">
              <h4><?= htmlspecialchars($img['title'] ?? 'مشروع') ?></h4>
              <span><i class="fas fa-search-plus"></i> عرض</span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

        <?php foreach ($videos as $vid): ?>
        <div class="gallery-item reveal" data-type="video" data-src="/uploads/videos/<?= htmlspecialchars($vid['file']) ?>">
          <?php if (!empty($vid['thumb'])): ?>
          <img src="/uploads/images/<?= htmlspecialchars($vid['thumb']) ?>" alt="<?= htmlspecialchars($vid['title'] ?? 'فيديو') ?>" loading="lazy">
          <?php else: ?>
          <video src="/uploads/videos/<?= htmlspecialchars($vid['file']) ?>" preload="metadata" muted></video>
          <?php endif; ?>
          <div class="play-btn"><i class="fas fa-play"></i></div>
          <div class="gallery-overlay">
            <div class="gallery-overlay-content">
              <h4><?= htmlspecialchars($vid['title'] ?? 'فيديو') ?></h4>
              <span><i class="fas fa-play-circle"></i> تشغيل</span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Lightbox -->
<div id="lightbox">
  <div class="lightbox-inner">
    <button class="lightbox-close" id="lb-close"><i class="fas fa-times"></i></button>
    <img id="lb-img" src="" alt="عرض" style="display:none;">
    <video id="lb-video" src="" controls style="display:none;max-width:90vw;max-height:85vh;border-radius:12px;"></video>
  </div>
</div>

<!-- ═══ CONTACT ═══ -->
<section id="contact">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag">تواصل معنا</div>
      <h2 class="section-title"><?= ct('contact_title') ?> <span class="gold"><?= ct('contact_title_gold') ?></span></h2>
      <p class="section-desc"><?= ct('contact_desc') ?></p>
      <div class="gold-line"></div>
    </div>

    <div class="contact-grid">
      <div class="contact-info reveal">
        <p>فريقنا المتخصص جاهز للإجابة على جميع استفساراتك وتقديم أفضل الحلول العقارية التي تناسب احتياجاتك وميزانيتك.</p>

        <div class="contact-links">
          <a href="https://wa.me/<?= $whatsapp ?>" target="_blank" class="contact-link whatsapp">
            <div class="icon"><i class="fab fa-whatsapp"></i></div>
            <div class="info">
              <div class="label">واتساب</div>
              <div class="value"><?= htmlspecialchars($cfg['whatsapp']) ?></div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>

          <a href="tel:<?= htmlspecialchars($phone) ?>" class="contact-link phone">
            <div class="icon"><i class="fas fa-phone-alt"></i></div>
            <div class="info">
              <div class="label">الجوال</div>
              <div class="value"><?= htmlspecialchars($phone) ?></div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>

          <a href="<?= htmlspecialchars($tiktok) ?>" target="_blank" class="contact-link tiktok">
            <div class="icon"><i class="fab fa-tiktok"></i></div>
            <div class="info">
              <div class="label">تيك توك</div>
              <div class="value"><?= htmlspecialchars(ltrim(parse_url($tiktok, PHP_URL_PATH), '/')) ?></div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>

          <a href="mailto:<?= htmlspecialchars($email) ?>" class="contact-link email">
            <div class="icon"><i class="fas fa-envelope"></i></div>
            <div class="info">
              <div class="label">البريد الإلكتروني</div>
              <div class="value"><?= htmlspecialchars($email) ?></div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>
        </div>
      </div>

      <div class="contact-form reveal">
        <h3><i class="fab fa-whatsapp" style="margin-left:8px;color:#25d366;"></i> أرسل رسالتك عبر واتساب</h3>
        <form id="contact-form" novalidate>
          <div class="form-group">
            <label>الاسم الكريم</label>
            <input type="text" name="contact_name" placeholder="أدخل اسمك" required>
          </div>
          <div class="form-group">
            <label>رقم الجوال</label>
            <input type="tel" name="contact_phone" placeholder="05xxxxxxxx" required>
          </div>
          <div class="form-group">
            <label>نوع الخدمة</label>
            <select name="contact_service">
              <option value="">اختر الخدمة</option>
              <?php
                $serviceOptions = array_filter(
                  array_map('trim', explode("\n", $cfg['service_options'] ?? ''))
                );
                foreach ($serviceOptions as $opt):
              ?>
              <option><?= htmlspecialchars($opt) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>رسالتك <span style="color:var(--gray);font-size:0.78rem;">(اختياري)</span></label>
            <textarea name="contact_message" placeholder="اكتب رسالتك هنا..."></textarea>
          </div>
          <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#25d366,#128c7e);">
            <i class="fab fa-whatsapp" style="margin-left:8px;"></i> إرسال عبر واتساب
          </button>
          <div class="form-msg" id="form-msg"></div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo-wrap">
          <img src="<?= $logoSrc . $logoCacheBust ?>" alt="<?= SITE_NAME ?>" onerror="this.style.display='none'">
          <div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--gold);">بنيان رسلان</div>
            <div style="font-size:0.75rem;color:var(--gray);">العقارية</div>
          </div>
        </div>
        <p><?= ct('footer_about') ?></p>
        <div class="footer-social">
          <a href="<?= htmlspecialchars($tiktok) ?>" target="_blank" class="social-btn" title="تيك توك"><i class="fab fa-tiktok"></i></a>
          <a href="https://wa.me/<?= $whatsapp ?>" target="_blank" class="social-btn" title="واتساب"><i class="fab fa-whatsapp"></i></a>
          <a href="tel:<?= htmlspecialchars($phone) ?>" class="social-btn" title="اتصل بنا"><i class="fas fa-phone-alt"></i></a>
        </div>
      </div>

      <div class="footer-col">
        <h4>روابط سريعة</h4>
        <ul>
          <li><a href="#hero">الرئيسية</a></li>
          <li><a href="#about">من نحن</a></li>
          <li><a href="#services">خدماتنا</a></li>
          <li><a href="#gallery">معرض المشاريع</a></li>
          <li><a href="#contact">تواصل معنا</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>خدماتنا</h4>
        <ul>
          <li><a href="#services">المشاريع السكنية</a></li>
          <li><a href="#services">المشاريع التجارية</a></li>
          <li><a href="#services">إدارة المشاريع</a></li>
          <li><a href="#services">الاستشارات العقارية</a></li>
          <li><a href="#services">التصميم المعماري</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> <?= SITE_NAME ?>. جميع الحقوق محفوظة.</p>
      <p>تصميم وتطوير بكل <span style="color:var(--gold);">♥</span></p>
    </div>
  </div>
</footer>

<!-- Floating Buttons -->
<div class="float-buttons">
  <a href="https://wa.me/<?= $whatsapp ?>" target="_blank" class="float-btn whatsapp" title="واتساب">
    <i class="fab fa-whatsapp"></i>
  </a>
  <a href="tel:<?= htmlspecialchars($phone) ?>" class="float-btn phone" title="اتصل بنا">
    <i class="fas fa-phone-alt"></i>
  </a>
</div>

<!-- Scripts -->
<script>window.SITE_WA = '<?= $whatsapp ?>';</script>
<script src="/assets/js/main.js" defer></script>
</body>
</html>
