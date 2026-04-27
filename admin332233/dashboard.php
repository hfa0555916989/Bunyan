<?php
require_once __DIR__ . '/auth.php';
requireLogin();

$media = getMedia();
$images = $media['images'] ?? [];
$videos = $media['videos'] ?? [];

$totalImages = count($images);
$totalVideos = count($videos);

// Calculate upload folder size
function folderSize(string $dir): int {
    $size = 0;
    if (!is_dir($dir)) return 0;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)) as $file) {
        $size += $file->getSize();
    }
    return $size;
}
$uploadSize = folderSize(UPLOAD_DIR);
$uploadSizeMB = round($uploadSize / 1024 / 1024, 1);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>لوحة التحكم | بنيان رسلان العقارية</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <img src="/assets/images/logo.png" alt="بنيان رسلان" onerror="this.style.display='none'">
    <div>
      <div class="brand-name">بنيان رسلان</div>
      <div class="brand-sub">لوحة التحكم</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a href="/admin332233/dashboard" class="nav-item active">
      <i class="fas fa-chart-pie"></i> الرئيسية
    </a>
    <a href="/admin332233/dashboard#images" class="nav-item">
      <i class="fas fa-images"></i> الصور
      <?php if ($totalImages > 0): ?><span class="badge"><?= $totalImages ?></span><?php endif; ?>
    </a>
    <a href="/admin332233/dashboard#videos" class="nav-item">
      <i class="fas fa-film"></i> الفيديوهات
      <?php if ($totalVideos > 0): ?><span class="badge"><?= $totalVideos ?></span><?php endif; ?>
    </a>
    <a href="/admin332233/content" class="nav-item">
      <i class="fas fa-pen-to-square"></i> المحتوى
    </a>
    <a href="/admin332233/settings" class="nav-item">
      <i class="fas fa-cog"></i> الإعدادات
    </a>
    <div class="nav-divider"></div>
    <a href="/" target="_blank" class="nav-item">
      <i class="fas fa-external-link-alt"></i> عرض الموقع
    </a>
    <a href="/admin332233/logout" class="nav-item danger">
      <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
    </a>
  </nav>
</aside>

<!-- Main Content -->
<main class="main-content">

  <!-- Topbar -->
  <header class="topbar">
    <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
      <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title">لوحة التحكم</div>
    <div class="topbar-actions">
      <span class="admin-badge"><i class="fas fa-shield-alt"></i> مدير</span>
    </div>
  </header>

  <div class="page-body">

    <!-- Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon images"><i class="fas fa-images"></i></div>
        <div class="stat-info">
          <div class="stat-num"><?= $totalImages ?></div>
          <div class="stat-label">إجمالي الصور</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon videos"><i class="fas fa-film"></i></div>
        <div class="stat-info">
          <div class="stat-num"><?= $totalVideos ?></div>
          <div class="stat-label">إجمالي الفيديوهات</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon storage"><i class="fas fa-database"></i></div>
        <div class="stat-info">
          <div class="stat-num"><?= $uploadSizeMB ?> MB</div>
          <div class="stat-label">حجم المحتوى</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon total"><i class="fas fa-photo-film"></i></div>
        <div class="stat-info">
          <div class="stat-num"><?= $totalImages + $totalVideos ?></div>
          <div class="stat-label">إجمالي الوسائط</div>
        </div>
      </div>
    </div>

    <!-- Upload Section -->
    <div class="section-card" id="upload">
      <div class="section-card-header">
        <h2><i class="fas fa-cloud-upload-alt"></i> رفع وسائط جديدة</h2>
      </div>
      <div class="upload-tabs">
        <button class="upload-tab active" data-tab="images"><i class="fas fa-image"></i> رفع صور</button>
        <button class="upload-tab" data-tab="videos"><i class="fas fa-video"></i> رفع فيديو</button>
      </div>

      <!-- Image Upload -->
      <div class="upload-panel active" id="tab-images">
        <form id="imageUploadForm" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
          <input type="hidden" name="type" value="image">
          <div class="upload-drop-zone" id="imageDrop">
            <div class="drop-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="drop-text">اسحب الصور هنا أو <span class="drop-link">اضغط للاختيار</span></div>
            <div class="drop-hint">JPG, PNG, WebP — حد أقصى 10 MB</div>
            <input type="file" name="file" id="imageFile" accept="image/jpeg,image/png,image/webp,image/gif" hidden>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>عنوان الصورة</label>
              <input type="text" name="title" placeholder="أدخل عنوان الصورة" class="form-input">
            </div>
          </div>
          <div class="upload-preview" id="imagePreview" style="display:none;">
            <img id="previewImg" src="" alt="معاينة">
            <button type="button" class="clear-preview" onclick="clearPreview('image')"><i class="fas fa-times"></i></button>
          </div>
          <div class="progress-bar-wrap" id="imageProgress" style="display:none;">
            <div class="progress-bar"><div class="progress-fill" id="imageFill"></div></div>
            <span class="progress-pct" id="imagePct">0%</span>
          </div>
          <button type="submit" class="btn-upload" id="imageBtn">
            <i class="fas fa-upload"></i> رفع الصورة
          </button>
          <div class="upload-msg" id="imageMsg"></div>
        </form>
      </div>

      <!-- Video Upload -->
      <div class="upload-panel" id="tab-videos">
        <form id="videoUploadForm" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
          <input type="hidden" name="type" value="video">
          <div class="upload-drop-zone" id="videoDrop">
            <div class="drop-icon"><i class="fas fa-video"></i></div>
            <div class="drop-text">اسحب الفيديو هنا أو <span class="drop-link">اضغط للاختيار</span></div>
            <div class="drop-hint">MP4, WebM — حد أقصى 200 MB</div>
            <input type="file" name="file" id="videoFile" accept="video/mp4,video/webm,video/ogg" hidden>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>عنوان الفيديو</label>
              <input type="text" name="title" placeholder="أدخل عنوان الفيديو" class="form-input">
            </div>
          </div>
          <div class="upload-preview video-preview-wrap" id="videoPreview" style="display:none;">
            <video id="previewVideo" controls></video>
            <button type="button" class="clear-preview" onclick="clearPreview('video')"><i class="fas fa-times"></i></button>
          </div>
          <div class="progress-bar-wrap" id="videoProgress" style="display:none;">
            <div class="progress-bar"><div class="progress-fill" id="videoFill"></div></div>
            <span class="progress-pct" id="videoPct">0%</span>
          </div>
          <button type="submit" class="btn-upload" id="videoBtn">
            <i class="fas fa-upload"></i> رفع الفيديو
          </button>
          <div class="upload-msg" id="videoMsg"></div>
        </form>
      </div>
    </div>

    <!-- Images Gallery -->
    <div class="section-card" id="images">
      <div class="section-card-header">
        <h2><i class="fas fa-images"></i> الصور (<?= $totalImages ?>)</h2>
      </div>
      <?php if (empty($images)): ?>
      <div class="empty-state">
        <i class="fas fa-images"></i>
        <p>لا توجد صور مرفوعة بعد</p>
      </div>
      <?php else: ?>
      <div class="media-grid" id="imagesGrid">
        <?php foreach (array_reverse($images) as $img): ?>
        <div class="media-card" id="img-<?= htmlspecialchars($img['id']) ?>">
          <div class="media-thumb">
            <img src="/uploads/images/<?= htmlspecialchars($img['file']) ?>" alt="<?= htmlspecialchars($img['title'] ?? '') ?>" loading="lazy">
            <div class="media-overlay">
              <a href="/uploads/images/<?= htmlspecialchars($img['file']) ?>" target="_blank" class="media-action view" title="عرض">
                <i class="fas fa-eye"></i>
              </a>
              <button class="media-action delete" title="حذف" onclick="deleteMedia('<?= htmlspecialchars($img['id']) ?>', 'image')">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
          <div class="media-info">
            <div class="media-title"><?= htmlspecialchars($img['title'] ?? 'بدون عنوان') ?></div>
            <div class="media-meta"><?= htmlspecialchars($img['date'] ?? '') ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Videos Gallery -->
    <div class="section-card" id="videos">
      <div class="section-card-header">
        <h2><i class="fas fa-film"></i> الفيديوهات (<?= $totalVideos ?>)</h2>
      </div>
      <?php if (empty($videos)): ?>
      <div class="empty-state">
        <i class="fas fa-film"></i>
        <p>لا توجد فيديوهات مرفوعة بعد</p>
      </div>
      <?php else: ?>
      <div class="media-grid" id="videosGrid">
        <?php foreach (array_reverse($videos) as $vid): ?>
        <div class="media-card" id="vid-<?= htmlspecialchars($vid['id']) ?>">
          <div class="media-thumb video-thumb">
            <?php if (!empty($vid['thumb'])): ?>
            <img src="/uploads/images/<?= htmlspecialchars($vid['thumb']) ?>" alt="<?= htmlspecialchars($vid['title'] ?? '') ?>" loading="lazy">
            <?php else: ?>
            <video src="/uploads/videos/<?= htmlspecialchars($vid['file']) ?>" preload="metadata" muted></video>
            <?php endif; ?>
            <div class="play-badge"><i class="fas fa-play"></i></div>
            <div class="media-overlay">
              <a href="/uploads/videos/<?= htmlspecialchars($vid['file']) ?>" target="_blank" class="media-action view" title="عرض">
                <i class="fas fa-eye"></i>
              </a>
              <button class="media-action delete" title="حذف" onclick="deleteMedia('<?= htmlspecialchars($vid['id']) ?>', 'video')">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
          <div class="media-info">
            <div class="media-title"><?= htmlspecialchars($vid['title'] ?? 'بدون عنوان') ?></div>
            <div class="media-meta"><?= htmlspecialchars($vid['date'] ?? '') ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

  </div><!-- /page-body -->
</main>

<!-- Delete Confirm Modal -->
<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div class="modal-icon danger"><i class="fas fa-trash-alt"></i></div>
    <h3>تأكيد الحذف</h3>
    <p>هل أنت متأكد من حذف هذا العنصر؟ لا يمكن التراجع عن هذا الإجراء.</p>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal()">إلغاء</button>
      <button class="btn-confirm-delete" id="confirmDeleteBtn">حذف</button>
    </div>
  </div>
</div>

<script src="/assets/js/admin.js"></script>
</body>
</html>
