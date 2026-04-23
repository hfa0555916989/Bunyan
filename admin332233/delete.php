<?php
require_once __DIR__ . '/auth.php';
requireLogin();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $input['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF verification failed']);
    exit;
}

$id   = $input['id'] ?? '';
$type = $input['type'] ?? '';

if (!$id || !in_array($type, ['image', 'video'], true)) {
    echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
    exit;
}

$media = getMedia();

if ($type === 'image') {
    foreach ($media['images'] as $i => $img) {
        if ($img['id'] === $id) {
            $filePath = UPLOAD_DIR . 'images/' . $img['file'];
            if (file_exists($filePath)) unlink($filePath);
            array_splice($media['images'], $i, 1);
            saveMedia($media);
            echo json_encode(['success' => true, 'message' => 'تم حذف الصورة']);
            exit;
        }
    }
} else {
    foreach ($media['videos'] as $i => $vid) {
        if ($vid['id'] === $id) {
            $filePath = UPLOAD_DIR . 'videos/' . $vid['file'];
            if (file_exists($filePath)) unlink($filePath);
            if (!empty($vid['thumb'])) {
                $thumbPath = UPLOAD_DIR . 'images/' . $vid['thumb'];
                if (file_exists($thumbPath)) unlink($thumbPath);
            }
            array_splice($media['videos'], $i, 1);
            saveMedia($media);
            echo json_encode(['success' => true, 'message' => 'تم حذف الفيديو']);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'العنصر غير موجود']);
