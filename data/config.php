<?php
// Admin credentials - change password after first login
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', password_hash('Admin@332233', PASSWORD_BCRYPT));

// Site settings
define('SITE_NAME', 'بنيان رسلان العقارية');
define('SITE_NAME_EN', 'Bunyan Raslan Real Estate');
define('SITE_URL', 'https://bunyanraslan.com');
define('SITE_PHONE', '+966500000000');
define('SITE_WHATSAPP', '+966500000000');
define('SITE_TIKTOK', 'https://www.tiktok.com/@bunyanraslan');
define('SITE_EMAIL', 'info@bunyanraslan.com');

// Upload settings
define('MAX_IMAGE_SIZE', 10 * 1024 * 1024); // 10 MB
define('MAX_VIDEO_SIZE', 200 * 1024 * 1024); // 200 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime']);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('DATA_FILE', __DIR__ . '/media.json');

// Session timeout (seconds)
define('SESSION_TIMEOUT', 3600); // 1 hour
