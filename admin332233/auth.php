<?php
require_once __DIR__ . '/../data/config.php';

session_start();

function isLoggedIn(): bool {
    if (!isset($_SESSION['admin_logged_in'], $_SESSION['admin_time'])) return false;
    if (time() - $_SESSION['admin_time'] > SESSION_TIMEOUT) {
        session_destroy();
        return false;
    }
    $_SESSION['admin_time'] = time();
    return $_SESSION['admin_logged_in'] === true;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: /admin332233');
        exit;
    }
}

function login(string $username, string $password): bool {
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_time'] = time();
        return true;
    }
    return false;
}

function logout(): void {
    session_destroy();
    header('Location: /admin332233');
    exit;
}

function getMedia(): array {
    $data = json_decode(file_get_contents(DATA_FILE), true);
    return $data ?? ['images' => [], 'videos' => []];
}

function saveMedia(array $data): void {
    file_put_contents(DATA_FILE, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF verification failed');
    }
}
