<?php
require_once __DIR__ . '/data/config.php';

// Load media data
$media = json_decode(file_get_contents(DATA_FILE), true) ?? ['images' => [], 'videos' => []];
$images = $media['images'] ?? [];
$videos = $media['videos'] ?? [];

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
    "logo": "<?= SITE_URL ?>/assets/images/logo.png",
    "description": "<?= $page_desc ?>",
    "telephone": "<?= SITE_PHONE ?>",
    "email": "<?= SITE_EMAIL ?>",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "SA"
    },
    "sameAs": [
      "<?= SITE_TIKTOK ?>"
    ],
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "<?= SITE_PHONE ?>",
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
  <img src="/assets/images/logo.png" alt="<?= SITE_NAME ?>" class="loader-logo" onerror="this.style.display='none'">
  <div style="font-size:1.5rem;font-weight:900;color:#c9a84c;font-family:Tajawal,sans-serif;">بنيان رسلان</div>
  <div class="loader-bar"></div>
</div>

<!-- Particles -->
<canvas id="particles-canvas"></canvas>

<!-- ═══ NAVIGATION ═══ -->
<nav id="navbar">
  <div class="nav-inner">
    <a href="/" class="nav-logo">
      <img src="/assets/images/logo.png" alt="<?= SITE_NAME ?>" onerror="this.style.display='none'">
    </a>

    <ul class="nav-links">
      <li><a href="#hero">الرئيسية</a></li>
      <li><a href="#about">من نحن</a></li>
      <li><a href="#services">خدماتنا</a></li>
      <li><a href="#gallery">معرضنا</a></li>
      <li><a href="#contact">تواصل معنا</a></li>
    </ul>

    <div class="nav-actions">
      <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', SITE_WHATSAPP) ?>" target="_blank" class="btn-whatsapp-nav">
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
      شركة رائدة في التطوير العقاري
    </div>

    <h1 class="hero-title">
      <span class="gold">بنيان رسلان</span><br>
      <span>العقارية</span>
    </h1>

    <p class="hero-subtitle">
      نبني أحلامكم بأعلى معايير الجودة والتميز، ونوفر لكم أفضل المشاريع السكنية والتجارية في المملكة العربية السعودية
    </p>

    <div class="hero-cta">
      <a href="#gallery" class="btn-primary">
        <i class="fas fa-images"></i>
        مشاريعنا
      </a>
      <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', SITE_WHATSAPP) ?>" target="_blank" class="btn-secondary">
        <i class="fab fa-whatsapp"></i>
        تواصل معنا
      </a>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num" data-target="150" data-suffix="+">0+</div>
        <div class="stat-label">مشروع منجز</div>
      </div>
      <div class="stat-item">
        <div class="stat-num" data-target="12" data-suffix="+">0+</div>
        <div class="stat-label">سنة خبرة</div>
      </div>
      <div class="stat-item">
        <div class="stat-num" data-target="5000" data-suffix="+">0+</div>
        <div class="stat-label">عميل راضٍ</div>
      </div>
      <div class="stat-item">
        <div class="stat-num" data-target="25" data-suffix="+">0+</div>
        <div class="stat-label">مدينة</div>
      </div>
    </div>
  </div>

  <div class="hero-scroll">
    <span>تصفح للأسفل</span>
    <div class="arrow"></div>
  </div>
</section>

<!-- ═══ ABOUT ═══ -->
<section id="about">
  <div class="container">
    <div class="about-grid">
      <div class="about-image reveal">
        <img src="/assets/images/about.jpg" alt="بنيان رسلان العقارية" onerror="this.src='https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80'">
        <div class="overlay-badge">
          <div class="num">+12</div>
          <div class="lbl">سنة من التميز</div>
        </div>
      </div>

      <div class="about-content">
        <div class="section-header" style="text-align:right;margin-bottom:32px;">
          <div class="section-tag">من نحن</div>
          <h2 class="section-title">نبني المستقبل <span class="gold">بثقة واحترافية</span></h2>
          <div class="gold-line" style="margin:16px 0 0;"></div>
        </div>

        <p class="reveal">
          بنيان رسلان العقارية شركة رائدة في مجال التطوير العقاري، تأسست على مبادئ الجودة والأمانة والابتكار. نسعى دائماً لتقديم أفضل الحلول العقارية التي تلبي تطلعات عملائنا وتتجاوز توقعاتهم.
        </p>

        <div class="about-features">
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-medal"></i></div>
            <div class="feature-text">
              <h4>جودة لا تُنافس</h4>
              <p>نستخدم أفضل المواد وأحدث تقنيات البناء</p>
            </div>
          </div>
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-handshake"></i></div>
            <div class="feature-text">
              <h4>ثقة واحترافية</h4>
              <p>علاقات طويلة الأمد مبنية على الشفافية والأمانة</p>
            </div>
          </div>
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
            <div class="feature-text">
              <h4>مواقع استراتيجية</h4>
              <p>مشاريع في أفضل المواقع بكبرى المدن السعودية</p>
            </div>
          </div>
          <div class="feature-item reveal">
            <div class="feature-icon"><i class="fas fa-headset"></i></div>
            <div class="feature-text">
              <h4>دعم متواصل</h4>
              <p>فريق متخصص لخدمتك على مدار الساعة</p>
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
      <p class="section-desc">نوفر طيفاً واسعاً من الخدمات العقارية المتكاملة لتلبية جميع احتياجاتك</p>
      <div class="gold-line"></div>
    </div>

    <div class="services-grid">
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-building"></i></div>
        <h3>المشاريع السكنية</h3>
        <p>شقق وفلل ومجمعات سكنية متكاملة بتصاميم عصرية وأسعار تنافسية</p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-store"></i></div>
        <h3>المشاريع التجارية</h3>
        <p>مراكز تجارية ومكاتب ومحلات في أفضل المواقع الاستراتيجية</p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-hard-hat"></i></div>
        <h3>إدارة المشاريع</h3>
        <p>إشراف متكامل على جميع مراحل التطوير من التصميم حتى التسليم</p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-chart-line"></i></div>
        <h3>الاستشارات العقارية</h3>
        <p>خدمات استشارية متخصصة لمساعدتك في اتخاذ أفضل القرارات الاستثمارية</p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-key"></i></div>
        <h3>بيع وتأجير العقارات</h3>
        <p>محفظة متنوعة من العقارات للبيع والإيجار تناسب جميع الميزانيات</p>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><i class="fas fa-drafting-compass"></i></div>
        <h3>التصميم المعماري</h3>
        <p>فريق من المهندسين والمصممين لتحويل أفكارك إلى واقع مبهر</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ GALLERY ═══ -->
<section id="gallery">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-tag">معرضنا</div>
      <h2 class="section-title">مشاريعنا <span class="gold">بالصور والفيديو</span></h2>
      <p class="section-desc">استعرض أحدث مشاريعنا وإنجازاتنا</p>
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
      <h2 class="section-title">نحن هنا <span class="gold">لخدمتك</span></h2>
      <p class="section-desc">تواصل معنا الآن واحصل على استشارة مجانية من خبرائنا</p>
      <div class="gold-line"></div>
    </div>

    <div class="contact-grid">
      <div class="contact-info reveal">
        <p>فريقنا المتخصص جاهز للإجابة على جميع استفساراتك وتقديم أفضل الحلول العقارية التي تناسب احتياجاتك وميزانيتك.</p>

        <div class="contact-links">
          <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', SITE_WHATSAPP) ?>" target="_blank" class="contact-link whatsapp">
            <div class="icon"><i class="fab fa-whatsapp"></i></div>
            <div class="info">
              <div class="label">واتساب</div>
              <div class="value"><?= SITE_WHATSAPP ?></div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>

          <a href="tel:<?= SITE_PHONE ?>" class="contact-link phone">
            <div class="icon"><i class="fas fa-phone-alt"></i></div>
            <div class="info">
              <div class="label">الجوال</div>
              <div class="value"><?= SITE_PHONE ?></div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>

          <a href="<?= SITE_TIKTOK ?>" target="_blank" class="contact-link tiktok">
            <div class="icon"><i class="fab fa-tiktok"></i></div>
            <div class="info">
              <div class="label">تيك توك</div>
              <div class="value">@bunyanraslan</div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>

          <a href="mailto:<?= SITE_EMAIL ?>" class="contact-link email">
            <div class="icon"><i class="fas fa-envelope"></i></div>
            <div class="info">
              <div class="label">البريد الإلكتروني</div>
              <div class="value"><?= SITE_EMAIL ?></div>
            </div>
            <i class="fas fa-arrow-left" style="color:var(--gray);"></i>
          </a>
        </div>
      </div>

      <div class="contact-form reveal">
        <h3><i class="fas fa-paper-plane" style="margin-left:8px;"></i> أرسل رسالتك</h3>
        <form id="contact-form" novalidate>
          <div class="form-group">
            <label>الاسم الكريم</label>
            <input type="text" placeholder="أدخل اسمك" required>
          </div>
          <div class="form-group">
            <label>رقم الجوال</label>
            <input type="tel" placeholder="05xxxxxxxx" required>
          </div>
          <div class="form-group">
            <label>نوع الخدمة</label>
            <select>
              <option value="">اختر الخدمة</option>
              <option>شراء عقار</option>
              <option>بيع عقار</option>
              <option>تأجير</option>
              <option>استشارة عقارية</option>
              <option>أخرى</option>
            </select>
          </div>
          <div class="form-group">
            <label>رسالتك</label>
            <textarea placeholder="اكتب رسالتك هنا..." required></textarea>
          </div>
          <button type="submit" class="btn-submit">
            <i class="fas fa-paper-plane" style="margin-left:8px;"></i> إرسال الرسالة
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
          <img src="/assets/images/logo.png" alt="<?= SITE_NAME ?>" onerror="this.style.display='none'">
          <div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--gold);">بنيان رسلان</div>
            <div style="font-size:0.75rem;color:var(--gray);">العقارية</div>
          </div>
        </div>
        <p>شركة رائدة في مجال التطوير العقاري، نبني أحلامكم بأعلى معايير الجودة والتميز في المملكة العربية السعودية.</p>
        <div class="footer-social">
          <a href="<?= SITE_TIKTOK ?>" target="_blank" class="social-btn" title="تيك توك"><i class="fab fa-tiktok"></i></a>
          <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', SITE_WHATSAPP) ?>" target="_blank" class="social-btn" title="واتساب"><i class="fab fa-whatsapp"></i></a>
          <a href="tel:<?= SITE_PHONE ?>" class="social-btn" title="اتصل بنا"><i class="fas fa-phone-alt"></i></a>
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
  <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', SITE_WHATSAPP) ?>" target="_blank" class="float-btn whatsapp" title="واتساب">
    <i class="fab fa-whatsapp"></i>
  </a>
  <a href="tel:<?= SITE_PHONE ?>" class="float-btn phone" title="اتصل بنا">
    <i class="fas fa-phone-alt"></i>
  </a>
</div>

<!-- Scripts -->
<script src="/assets/js/main.js" defer></script>
</body>
</html>
