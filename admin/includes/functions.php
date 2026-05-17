<?php
function makeSlug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function handleImageUpload(string $fieldName, string $subFolder, string $oldImage = ''): string
{
    error_log('ROOT: ' . realpath(__DIR__ . '/../../'));
    error_log('FILES: ' . print_r($_FILES, true));
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) return '';
    $file     = $_FILES[$fieldName];
    $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp', 'image/tiff', 'image/svg+xml', 'image/x-icon'];
    $mimeType = mime_content_type($file['tmp_name']);
    if (!in_array($mimeType, $allowed, true)) return '';
    $extMap   = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif', 'image/bmp' => 'bmp', 'image/tiff' => 'tiff', 'image/svg+xml' => 'svg', 'image/x-icon' => 'ico'];
    $ext      = $extMap[$mimeType] ?? 'jpg';
    $filename = uniqid('img_', true) . '.' . $ext;

    // Find project root - using this file's location
    $root = realpath(__DIR__ . '/../../');
    $absDir = $root . '/admin/uploads/' . trim($subFolder, '/');
    if (!is_dir($absDir)) mkdir($absDir, 0755, true);
    $dest = $absDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) return '';

    if ($oldImage && strpos($oldImage, 'admin/uploads/') !== false) {
        $absOld = $root . '/' . ltrim($oldImage, '/');
        if (file_exists($absOld)) @unlink($absOld);
    }
    return 'admin/uploads/' . trim($subFolder, '/') . '/' . $filename;
}

function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function setFlash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function renderFlash(): void
{
    $flash = getFlash();
    if (!$flash) return;
    $colors = [
        'success' => ['bg' => '#d4edda', 'border' => '#c3e6cb', 'text' => '#155724'],
        'error'   => ['bg' => '#f8d7da', 'border' => '#f5c6cb', 'text' => '#721c24'],
        'info'    => ['bg' => '#d1ecf1', 'border' => '#bee5eb', 'text' => '#0c5460'],
    ];
    $c = $colors[$flash['type']] ?? $colors['info'];
    echo '<div data-flash style="background:' . $c['bg'] . ';border:1px solid ' . $c['border'] . ';color:' . $c['text'] . ';padding:14px 20px;border-radius:6px;margin-bottom:20px;font-size:14px;">'
        . e($flash['message']) . '</div>';
}
