<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_password']  ?? '';
    $new      = $_POST['new_password']       ?? '';
    $confirm  = $_POST['confirm_password']   ?? '';

    $stmt = $pdo->prepare('SELECT password FROM admin_users WHERE id = ?');
    $stmt->execute([$_SESSION['wavicle_admin_id']]);
    $hash = $stmt->fetchColumn();

    if (!password_verify($current, $hash)) {
        $errors[] = 'Current password is incorrect.';
    } elseif (strlen($new) < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    } elseif ($new !== $confirm) {
        $errors[] = 'New passwords do not match.';
    }

    if (!$errors) {
        $newHash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
        $pdo->prepare('UPDATE admin_users SET password = ? WHERE id = ?')
            ->execute([$newHash, $_SESSION['wavicle_admin_id']]);
        setFlash('success', 'Password changed successfully.');
        header('Location: change-password.php'); exit;
    }
}

$adminPageTitle  = 'Change Password';
$adminActivePage = 'password';
include __DIR__ . '/includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>Change Password</h1>
        <div class="wv-breadcrumb"><a href="index.php">Dashboard</a> / Change Password</div>
    </div>
</div>

<?php renderFlash(); ?>

<?php if ($errors): ?>
<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:6px;margin-bottom:20px;">
    <?php foreach ($errors as $err): ?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($err); ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div class="wv-card" style="max-width:500px;">
    <div class="wv-card__header"><span class="wv-card__title">Update Your Password</span></div>
    <div class="wv-card__body">
        <form method="POST">
            <div class="wv-form-group">
                <label class="wv-label">Current Password <span class="wv-required">*</span></label>
                <input type="password" name="current_password" class="wv-input" required />
            </div>
            <div class="wv-form-group">
                <label class="wv-label">New Password <span class="wv-required">*</span></label>
                <input type="password" name="new_password" class="wv-input" required minlength="8" />
                <small style="color:#6c757d; font-size:11px; margin-top:4px; display:block;">Minimum 8 characters.</small>
            </div>
            <div class="wv-form-group">
                <label class="wv-label">Confirm New Password <span class="wv-required">*</span></label>
                <input type="password" name="confirm_password" class="wv-input" required />
            </div>
            <button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-key"></i> Update Password</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
