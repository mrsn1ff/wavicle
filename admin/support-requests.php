<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $rid    = (int)($_POST['request_id'] ?? 0);
    $status = $_POST['new_status'] ?? '';
    if ($rid > 0 && in_array($status, ['new', 'read', 'resolved'], true)) {
        $pdo->prepare('UPDATE support_requests SET status = ? WHERE id = ?')
            ->execute([$status, $rid]);
        setFlash('success', 'Status updated successfully.');
    }
    header('Location: support-requests.php');
    exit;
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $pdo->prepare('DELETE FROM support_requests WHERE id = ?')
        ->execute([(int)$_POST['delete_id']]);
    setFlash('success', 'Request deleted.');
    header('Location: support-requests.php');
    exit;
}

// Filter
$filterStatus = $_GET['status'] ?? 'all';
if (!in_array($filterStatus, ['all', 'new', 'read', 'resolved'], true)) $filterStatus = 'all';

if ($filterStatus === 'all') {
    $requests = $pdo->query('SELECT * FROM support_requests ORDER BY created_at DESC')->fetchAll();
} else {
    $stmt = $pdo->prepare('SELECT * FROM support_requests WHERE status = ? ORDER BY created_at DESC');
    $stmt->execute([$filterStatus]);
    $requests = $stmt->fetchAll();
}

// Counts
$counts   = $pdo->query('SELECT status, COUNT(*) as cnt FROM support_requests GROUP BY status')->fetchAll();
$countMap = ['all' => 0, 'new' => 0, 'read' => 0, 'resolved' => 0];
foreach ($counts as $c) {
    $countMap[$c['status']] = (int)$c['cnt'];
    $countMap['all'] += (int)$c['cnt'];
}

$adminPageTitle  = 'Support Requests';
$adminActivePage = 'support';
include __DIR__ . '/includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>Support Requests</h1>
        <div class="wv-breadcrumb"><a href="index.php">Dashboard</a> / Support Requests</div>
    </div>
    <?php if ($countMap['new'] > 0): ?>
        <div style="background:#fff3cd; color:#856404; padding:8px 18px; border-radius:8px;
                font-size:13px; font-weight:700; font-family:'Montserrat',sans-serif;
                border:1px solid #ffc107; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-bell"></i>
            <?php echo $countMap['new']; ?> New Request<?php echo $countMap['new'] > 1 ? 's' : ''; ?>
        </div>
    <?php endif; ?>
</div>

<?php renderFlash(); ?>

<!-- Filter Tabs -->
<div style="display:flex; gap:8px; margin-bottom:24px; flex-wrap:wrap;">
    <?php
    $tabs = [
        'all'      => ['label' => 'All',      'icon' => 'fa-list',          'color' => '#0e3c7d'],
        'new'      => ['label' => 'New',       'icon' => 'fa-circle-dot',    'color' => '#dc3545'],
        'read'     => ['label' => 'Read',      'icon' => 'fa-envelope-open', 'color' => '#0d6efd'],
        'resolved' => ['label' => 'Resolved',  'icon' => 'fa-circle-check',  'color' => '#198754'],
    ];
    foreach ($tabs as $key => $tab):
        $isActive = ($filterStatus === $key);
        $bg     = $isActive ? $tab['color'] : '#fff';
        $color  = $isActive ? '#fff' : '#6c757d';
        $border = $isActive ? $tab['color'] : '#dee2e6';
    ?>
        <a href="?status=<?php echo $key; ?>"
            style="display:inline-flex; align-items:center; gap:8px; padding:9px 18px;
              background:<?php echo $bg; ?>; color:<?php echo $color; ?>;
              border:1px solid <?php echo $border; ?>; border-radius:8px;
              font-family:'Montserrat',sans-serif; font-size:12px; font-weight:700;
              text-decoration:none; transition:all .2s;">
            <i class="fa <?php echo $tab['icon']; ?>"></i>
            <?php echo $tab['label']; ?>
            <span style="background:<?php echo $isActive ? 'rgba(255,255,255,.25)' : '#f0f0f0'; ?>;
                     color:<?php echo $isActive ? '#fff' : '#333'; ?>;
                     padding:1px 8px; border-radius:12px; font-size:11px;">
                <?php echo $countMap[$key]; ?>
            </span>
        </a>
    <?php endforeach; ?>
</div>

<!-- Table -->
<div class="wv-card">
    <div class="wv-card__header">
        <span class="wv-card__title"><?php echo ucfirst($filterStatus); ?> Requests (<?php echo count($requests); ?>)</span>
    </div>
    <div class="wv-card__body" style="padding:0;">
        <?php if ($requests): ?>
            <table class="wv-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $i => $req): ?>
                        <tr>
                            <td style="font-weight:600; color:#0e3c7d;"><?php echo $i + 1; ?></td>
                            <td style="font-weight:700; font-family:'Montserrat',sans-serif; font-size:13px;">
                                <?php echo e($req['name']); ?>
                            </td>
                            <td>
                                <a href="tel:<?php echo e(preg_replace('/\D/', '', $req['phone'])); ?>"
                                    style="color:#59b5e8; font-weight:600; text-decoration:none; font-size:13px;">
                                    <?php echo e($req['phone']); ?>
                                </a>
                            </td>
                            <td style="max-width:260px; font-size:13px; color:#555; line-height:1.5;">
                                <?php if (!empty($req['message'])): ?>
                                    <?php $msg = e($req['message']); ?>
                                    <?php echo strlen($req['message']) > 100 ? substr($msg, 0, 100) . '…' : $msg; ?>
                                    <?php if (strlen($req['message']) > 100): ?>
                                        <a href="#" onclick="document.getElementById('msg-<?php echo $req['id']; ?>').style.display='block';this.style.display='none';return false;"
                                            style="color:#59b5e8;font-size:11px;font-weight:700;display:block;margin-top:4px;">Read more</a>
                                        <div id="msg-<?php echo $req['id']; ?>" style="display:none;margin-top:6px;">
                                            <?php echo e($req['message']); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color:#adb5bd; font-style:italic; font-size:12px;">No message</span>
                                <?php endif; ?>
                            </td>
                            <td style="white-space:nowrap; font-size:12px; color:#6c757d;">
                                <?php echo date('d M Y', strtotime($req['created_at'])); ?><br />
                                <span style="font-size:11px;"><?php echo date('h:i A', strtotime($req['created_at'])); ?></span>
                            </td>
                            <td>
                                <?php
                                $sc = [
                                    'new'      => ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'New'],
                                    'read'     => ['bg' => '#cfe2ff', 'color' => '#084298', 'label' => 'Read'],
                                    'resolved' => ['bg' => '#d4edda', 'color' => '#155724', 'label' => 'Resolved'],
                                ][$req['status']] ?? ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'New'];
                                ?>
                                <span style="background:<?php echo $sc['bg']; ?>; color:<?php echo $sc['color']; ?>;
                                 padding:4px 12px; border-radius:20px; font-size:10px;
                                 font-weight:700; text-transform:uppercase; letter-spacing:.5px;">
                                    <?php echo $sc['label']; ?>
                                </span>
                            </td>
                            <td style="white-space:nowrap;">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="update_status" value="1" />
                                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>" />
                                    <select name="new_status" onchange="this.form.submit()"
                                        style="padding:5px 8px; border:1px solid #dee2e6; border-radius:6px;
                                       font-family:'Montserrat',sans-serif; font-size:11px;
                                       color:#0e3c7d; font-weight:600; cursor:pointer;
                                       background:#f8f9fa; margin-right:6px;">
                                        <option value="new" <?php echo $req['status'] === 'new'      ? 'selected' : ''; ?>>New</option>
                                        <option value="read" <?php echo $req['status'] === 'read'     ? 'selected' : ''; ?>>Read</option>
                                        <option value="resolved" <?php echo $req['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    </select>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $req['id']; ?>" />
                                    <button type="submit" class="wv-btn wv-btn-danger wv-btn-sm"
                                        data-confirm="Delete this request? This cannot be undone.">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="padding:60px; text-align:center; color:#6c757d;">
                <i class="fa fa-inbox" style="font-size:48px; color:#dee2e6; display:block; margin-bottom:16px;"></i>
                <div style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:15px; margin-bottom:6px;">
                    No requests yet
                </div>
                <div style="font-size:13px;">Support requests will appear here when customers submit the form.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>