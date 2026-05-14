<?php
// ─── Auth Guard ───────────────────────────────────────────────────────────────
// Include this at the top of every protected admin page.
// It starts the session and redirects to login if not authenticated.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['wavicle_admin_id']) && !empty($_SESSION['wavicle_admin_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . getAdminBase() . 'login.php');
        exit;
    }
}

function getAdminBase(): string {
    // Works whether admin files are accessed directly or from subdirs
    $depth = substr_count(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__), '/');
    return str_repeat('../', max(0, $depth - 1)) . 'admin/';
}

function adminLogout(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

function currentAdminName(): string {
    return $_SESSION['wavicle_admin_name'] ?? 'Admin';
}
