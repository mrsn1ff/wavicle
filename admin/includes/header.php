<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$adminPageTitle  = $adminPageTitle  ?? 'Admin Panel';
$adminActivePage = $adminActivePage ?? '';

// Smart base URLs — work from any depth inside /admin/
$scriptPath = $_SERVER['SCRIPT_NAME'];
$adminBase  = preg_replace('#/admin/.*#', '/admin/', $scriptPath);
$siteBase   = preg_replace('#/admin/.*#', '/', $scriptPath);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8'); ?> | Wavicle Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.typekit.net/itc-avant-garde-gothic.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --navy: #0e3c7d;
            --navy-dark: #092d5e;
            --blue: #59b5e8;
            --blue-light: #e8f4fb;
            --white: #ffffff;
            --gray-bg: #f4f6fb;
            --gray-text: #6c757d;
            --border: #dee2e6;
            --sidebar-w: 260px;
            --topbar-h: 64px;
        }

        body,
        p,
        li,
        td,
        span,
        label,
        input,
        textarea,
        select,
        small,
        a {
            font-family: 'itc-avant-garde-gothic', 'Avant Garde', 'Montserrat', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .wv-topbar__title,
        .wv-sidebar__logo-text,
        .wv-card__title,
        .wv-page-header h1,
        .wv-stat__num,
        .wv-btn,
        .wv-label,
        .wv-nav-item,
        .wv-table th,
        .wv-sidebar__logo-sub,
        .wv-sidebar__section-label,
        .wv-sidebar__footer {
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background: var(--gray-bg);
            color: #212529;
            min-height: 100vh;
        }

        /* Topbar */
        .wv-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .wv-topbar__title {
            font-size: 17px;
            font-weight: 700;
            color: var(--navy);
        }

        .wv-topbar__right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .wv-topbar__user {
            font-size: 13px;
            color: var(--gray-text);
            font-weight: 500;
        }

        .wv-topbar__user span {
            color: var(--navy);
            font-weight: 700;
        }

        .wv-logout {
            background: var(--navy);
            color: var(--white);
            border: none;
            border-radius: 6px;
            padding: 7px 16px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .wv-logout:hover {
            background: var(--navy-dark);
            color: var(--white);
        }

        /* Sidebar */
        .wv-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy);
            overflow-y: auto;
            z-index: 200;
            display: flex;
            flex-direction: column;
        }

        .wv-sidebar__logo {
            padding: 22px 24px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .wv-sidebar__logo-text {
            font-size: 20px;
            font-weight: 800;
            color: var(--white);
            letter-spacing: .5px;
        }

        .wv-sidebar__logo-sub {
            font-size: 9px;
            font-weight: 500;
            color: var(--blue);
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-top: 3px;
        }

        .wv-sidebar__section-label {
            font-size: 9px;
            font-weight: 700;
            color: rgba(255, 255, 255, .4);
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 22px 24px 8px;
        }

        .wv-sidebar nav {
            flex: 1;
            padding-bottom: 20px;
        }

        .wv-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            color: rgba(255, 255, 255, .75);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background .15s, color .15s;
            border-left: 3px solid transparent;
        }

        .wv-nav-item i {
            width: 18px;
            text-align: center;
            font-size: 14px;
        }

        .wv-nav-item:hover,
        .wv-nav-item.active {
            background: rgba(89, 181, 232, .15);
            color: var(--white);
            border-left-color: var(--blue);
        }

        .wv-nav-item.active {
            font-weight: 700;
        }

        .wv-sidebar__footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255, 255, 255, .1);
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
            text-align: center;
        }

        /* Main */
        .wv-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 30px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* Cards */
        .wv-card {
            background: var(--white);
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .wv-card__header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .wv-card__title {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
        }

        .wv-card__body {
            padding: 24px;
        }

        /* Stats */
        .wv-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .wv-stat {
            background: var(--white);
            border-radius: 10px;
            padding: 24px 22px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .wv-stat__icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--blue-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--navy);
            flex-shrink: 0;
        }

        .wv-stat__num {
            font-size: 28px;
            font-weight: 800;
            color: var(--navy);
            line-height: 1;
        }

        .wv-stat__label {
            font-size: 12px;
            color: var(--gray-text);
            margin-top: 4px;
            font-weight: 500;
        }

        /* Buttons */
        .wv-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: opacity .2s, transform .1s;
        }

        .wv-btn:active {
            transform: scale(.97);
        }

        .wv-btn-primary {
            background: var(--navy);
            color: var(--white);
        }

        .wv-btn-primary:hover {
            background: var(--navy-dark);
            color: var(--white);
        }

        .wv-btn-success {
            background: #198754;
            color: var(--white);
        }

        .wv-btn-success:hover {
            background: #146c43;
            color: var(--white);
        }

        .wv-btn-danger {
            background: #dc3545;
            color: var(--white);
        }

        .wv-btn-danger:hover {
            background: #b02a37;
            color: var(--white);
        }

        .wv-btn-secondary {
            background: var(--gray-bg);
            color: var(--navy);
            border: 1px solid var(--border);
        }

        .wv-btn-secondary:hover {
            background: var(--border);
            color: var(--navy);
        }

        .wv-btn-sm {
            padding: 5px 12px;
            font-size: 11px;
        }

        /* Table */
        .wv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .wv-table th {
            background: var(--navy);
            color: var(--white);
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .wv-table th:first-child {
            border-radius: 6px 0 0 6px;
        }

        .wv-table th:last-child {
            border-radius: 0 6px 6px 0;
        }

        .wv-table td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .wv-table tr:last-child td {
            border-bottom: none;
        }

        .wv-table tr:hover td {
            background: var(--blue-light);
        }

        .wv-table img.thumb {
            width: 60px;
            height: 46px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Badge */
        .wv-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .wv-badge-active {
            background: #d4edda;
            color: #155724;
        }

        .wv-badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        /* Forms */
        .wv-form-group {
            margin-bottom: 20px;
        }

        .wv-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .5px;
            font-family: 'Montserrat', sans-serif;
        }

        .wv-input,
        .wv-select,
        .wv-textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 7px;
            font-family: 'itc-avant-garde-gothic', 'Montserrat', sans-serif;
            font-size: 13px;
            color: #212529;
            background: var(--white);
            transition: border-color .2s, box-shadow .2s;
        }

        .wv-input:focus,
        .wv-select:focus,
        .wv-textarea:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(89, 181, 232, .15);
        }

        .wv-textarea {
            resize: vertical;
            min-height: 110px;
        }

        .wv-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .wv-form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .wv-img-preview {
            margin-top: 10px;
        }

        .wv-img-preview img {
            max-width: 160px;
            max-height: 110px;
            border-radius: 6px;
            border: 1px solid var(--border);
            object-fit: cover;
        }

        .wv-required {
            color: #dc3545;
        }

        /* Page Header */
        .wv-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .wv-page-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--navy);
        }

        .wv-breadcrumb {
            font-size: 12px;
            color: var(--gray-text);
            margin-top: 3px;
        }

        .wv-breadcrumb a {
            color: var(--blue);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .wv-sidebar {
                transform: translateX(-100%);
            }

            .wv-topbar {
                left: 0;
            }

            .wv-main {
                margin-left: 0;
                padding: 16px;
            }

            .wv-form-row,
            .wv-form-row-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="file"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    var maxSize = 4 * 1024 * 1024; // 4MB
                    if (this.files[0] && this.files[0].size > maxSize) {
                        alert('Image size must be less than 4MB. Your file is ' + (this.files[0].size / 1024 / 1024).toFixed(2) + 'MB.');
                        this.value = '';
                        var previewId = this.getAttribute('data-preview');
                        if (previewId) {
                            var preview = document.getElementById(previewId);
                            if (preview) {
                                preview.src = '';
                                preview.style.display = 'none';
                            }
                        }
                    }
                });
            });
        });
    </script>
</head>

<body>

    <aside class="wv-sidebar">
        <div class="wv-sidebar__logo">
            <div class="wv-sidebar__logo-text">Wavicle</div>
            <span class="wv-sidebar__logo-sub">Admin Portal</span>
        </div>
        <nav>
            <div class="wv-sidebar__section-label">Main</div>
            <a href="<?php echo $adminBase; ?>index.php" class="wv-nav-item <?php echo $adminActivePage === 'dashboard' ? 'active' : ''; ?>">
                <i class="fa fa-gauge-high"></i> Dashboard
            </a>
            <div class="wv-sidebar__section-label">Catalog</div>
            <a href="<?php echo $adminBase; ?>catalog/categories/index.php?type=product" class="wv-nav-item <?php echo $adminActivePage === 'cat_mgr' ? 'active' : ''; ?>">
                <i class="fa fa-folder-open"></i> Categories
            </a>
            <a href="<?php echo $adminBase; ?>catalog/products/index.php" class="wv-nav-item <?php echo $adminActivePage === 'cat_products' ? 'active' : ''; ?>">
                <i class="fa fa-box-open"></i> Products
            </a>
            <a href="<?php echo $adminBase; ?>catalog/services/index.php" class="wv-nav-item <?php echo $adminActivePage === 'cat_services' ? 'active' : ''; ?>">
                <i class="fa fa-concierge-bell"></i> Services
            </a>
            <a href="<?php echo $adminBase; ?>catalog/blogs/index.php" class="wv-nav-item <?php echo $adminActivePage === 'cat_blogs' ? 'active' : ''; ?>">
                <i class="fa fa-newspaper"></i> News &amp; Articles
            </a>
            <a href="<?php echo $adminBase; ?>blogs/index.php" class="wv-nav-item <?php echo $adminActivePage === 'blogs' ? 'active' : ''; ?>">
                <i class="fa fa-newspaper"></i> Blogs
            </a>
            <a href="<?php echo $adminBase; ?>support-requests.php" class="wv-nav-item <?php echo $adminActivePage === 'support' ? 'active' : ''; ?>">
                <i class="fa fa-headset"></i> Support Requests
            </a>
            <div class="wv-sidebar__section-label">Settings</div>
            <a href="<?php echo $adminBase; ?>change-password.php" class="wv-nav-item <?php echo $adminActivePage === 'password' ? 'active' : ''; ?>">
                <i class="fa fa-key"></i> Change Password
            </a>
            <a href="<?php echo $siteBase; ?>index.php" class="wv-nav-item" target="_blank">
                <i class="fa fa-arrow-up-right-from-square"></i> View Website
            </a>
            <a href="<?php echo $adminBase; ?>logout.php" class="wv-nav-item">
                <i class="fa fa-right-from-bracket"></i> Logout
            </a>
        </nav>
        <div class="wv-sidebar__footer">&copy; <?php echo date('Y'); ?> Wavicle</div>
    </aside>

    <div class="wv-topbar">
        <div class="wv-topbar__title"><?php echo htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="wv-topbar__right">
            <div class="wv-topbar__user">Welcome, <span><?php echo htmlspecialchars(currentAdminName(), ENT_QUOTES, 'UTF-8'); ?></span></div>
            <a href="<?php echo $adminBase; ?>logout.php" class="wv-logout"><i class="fa fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>

    <main class="wv-main">