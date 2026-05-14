<?php
// ─── Load .env file ───────────────────────────────────────────
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($val);
    }
}

// ─── DB Config from .env ──────────────────────────────────────
define('DB_HOST', $_ENV['DB_HOST'] ?? 'mariadb');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'wavicle_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'root');
define('DB_CHAR', $_ENV['DB_CHAR'] ?? 'utf8mb4');

// ─── PDO Connection ───────────────────────────────────────────
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHAR,
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('<div style="font-family:Montserrat,sans-serif;padding:40px;color:#721c24;
    background:#f8d7da;border:1px solid #f5c6cb;margin:20px;border-radius:6px;">
        <h3>Database Connection Failed</h3>
        <p>Check your <code>.env</code> file.</p>
        <p><small>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</small></p>
    </div>');
}
