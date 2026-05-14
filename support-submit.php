<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

require_once __DIR__ . '/admin/includes/db.php';

$name    = trim($_POST['name']    ?? '');
$phone   = trim($_POST['phone']   ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '')          $errors[] = 'Name is required.';
if ($phone === '')         $errors[] = 'Phone is required.';
if (strlen($phone) !== 10) $errors[] = 'Phone must be 10 digits.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO support_requests (name, phone, message) VALUES (?, ?, ?)'
    );
    $stmt->execute([$name, $phone, $message ?: null]);
    echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}