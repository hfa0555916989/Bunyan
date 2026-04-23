<?php
require_once __DIR__ . '/auth.php';
requireLogin();
verifyCsrf();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$type = $_POST['type'] ?? '';
$title = trim($_POST['title'] ?? '');

if (!in_array($type, ['image', 'video'], true)) {
    echo json_encode(['success' => false, 'message' => 'نوع غير صحيح']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $errCodes = [
        UPLOAD_ERR_INI_SIZE   => 'حجم الملف تجاوز الحد الأقصى',
        UPLOAD_ERR_FORM_SIZE  => 'حجم الملف تجاوز الحد المسموح',
        UPLOAD_ERR_PARTIAL    => 'تم رفع الملف جزئياً، حاول مجدداً',
        UPLOAD_ERR_NO_FILE    => 'لم يتم اختيار ملف',
        UPLOAD_ERR_NO_TMP_DIR => 'خطأ في الخادم (tmp)',
        UPLOAD_ERR_CANT_WRITE => 'تعذّر الحفظ على الخادم',
    ];
    $code = $_FILES['file']['error'] ?? 0;
    $msg  = $errCodes[$code] ?? 'خطأ في رفع الملف';
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$tmpFile  = $_FILES['file']['tmp_name'];
$origName = $_FILES['file']['name'];
$mimeType = mime_content_type($tmpFile);
$fileSize = $_FILES['file']['size'];

if ($type === 'image') {
    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES, true)) {
        echo json_encode(['success' => false, 'message' => 'نوع الملف غير مدعوم. يُسمح فقط بـ JPG, PNG, WebP']);
        exit;
    }
    if ($fileSize > MAX_IMAGE_SIZE) {
        echo json_encode(['success' => false, 'message' => 'حجم الصورة يتجاوز 10 MB']);
        exit;
    }
    $ext     = pathinfo($origName, PATHINFO_EXTENSION) ?: 'jpg';
    $newName = uniqid('img_', true) . '.' . strtolower($ext);
    $destDir = UPLOAD_DIR . 'images/';
    $dest    = $destDir . $newName;

    if (!is_dir($destDir)) mkdir($destDir, 0755, true);
    if (!move_uploaded_file($tmpFile, $dest)) {
        echo json_encode(['success' => false, 'message' => 'فشل في حفظ الصورة']);
        exit;
    }

    $media = getMedia();
    array_unshift($media['images'], [
        'id'    => uniqid(),
        'file'  => $newName,
        'title' => $title ?: pathinfo($origName, PATHINFO_FILENAME),
        'date'  => date('Y-m-d H:i'),
        'size'  => $fileSize,
    ]);
    saveMedia($media);

    echo json_encode([
        'success' => true,
        'message' => 'تم رفع الصورة بنجاح!',
        'file'    => $newName,
        'url'     => '/uploads/images/' . $newName,
    ]);

} else {
    if (!in_array($mimeType, ALLOWED_VIDEO_TYPES, true)) {
        echo json_encode(['success' => false, 'message' => 'نوع الملف غير مدعوم. يُسمح فقط بـ MP4, WebM']);
        exit;
    }
    if ($fileSize > MAX_VIDEO_SIZE) {
        echo json_encode(['success' => false, 'message' => 'حجم الفيديو يتجاوز 200 MB']);
        exit;
    }
    $ext     = pathinfo($origName, PATHINFO_EXTENSION) ?: 'mp4';
    $newName = uniqid('vid_', true) . '.' . strtolower($ext);
    $destDir = UPLOAD_DIR . 'videos/';
    $dest    = $destDir . $newName;

    if (!is_dir($destDir)) mkdir($destDir, 0755, true);
    if (!move_uploaded_file($tmpFile, $dest)) {
        echo json_encode(['success' => false, 'message' => 'فشل في حفظ الفيديو']);
        exit;
    }

    $media = getMedia();
    array_unshift($media['videos'], [
        'id'    => uniqid(),
        'file'  => $newName,
        'title' => $title ?: pathinfo($origName, PATHINFO_FILENAME),
        'date'  => date('Y-m-d H:i'),
        'size'  => $fileSize,
        'thumb' => '',
    ]);
    saveMedia($media);

    echo json_encode([
        'success' => true,
        'message' => 'تم رفع الفيديو بنجاح!',
        'file'    => $newName,
        'url'     => '/uploads/videos/' . $newName,
    ]);
}
