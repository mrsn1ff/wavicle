<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Already logged in? redirect to dashboard
if (!empty($_SESSION['wavicle_admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['wavicle_admin_id']   = $user['id'];
            $_SESSION['wavicle_admin_name'] = $user['name'];
            $_SESSION['wavicle_admin_email']= $user['email'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login | Wavicle</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #0e3c7d;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, #0e3c7d 0%, #092d5e 40%, #051b35 100%);
            z-index: 0;
        }
        /* Decorative wave shape */
        body::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -80px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: rgba(89,181,232,.08);
            z-index: 0;
        }
        .login-wrap {
            position: relative; z-index: 1;
            display: flex; width: 100%; max-width: 900px;
            min-height: 520px; border-radius: 16px; overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,.4);
        }
        /* Left panel */
        .login-panel-left {
            flex: 1; background: rgba(255,255,255,.05);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.1);
            border-right: none;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 50px 40px; text-align: center;
        }
        .login-brand-name {
            font-size: 42px; font-weight: 800; color: #fff; letter-spacing: 2px;
        }
        .login-brand-sub {
            font-size: 11px; font-weight: 600; color: #59b5e8;
            letter-spacing: 4px; text-transform: uppercase; margin-top: 6px;
        }
        .login-panel-left p {
            color: rgba(255,255,255,.55); font-size: 13px; margin-top: 24px; line-height: 1.8;
        }
        .login-deco {
            margin-top: 40px; display: flex; gap: 16px;
        }
        .login-deco-dot {
            width: 10px; height: 10px; border-radius: 50%; background: #59b5e8; opacity: .4;
        }
        .login-deco-dot:nth-child(2) { opacity: .7; width: 14px; height: 14px; }
        .login-deco-dot:nth-child(3) { opacity: .4; }

        /* Right panel - form */
        .login-panel-right {
            flex: 1.1; background: #fff;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 50px 45px;
        }
        .login-form-title {
            font-size: 24px; font-weight: 800; color: #0e3c7d; margin-bottom: 6px;
        }
        .login-form-sub {
            font-size: 12px; color: #6c757d; margin-bottom: 32px;
        }
        .login-form { width: 100%; }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 11px; font-weight: 700; color: #0e3c7d;
            text-transform: uppercase; letter-spacing: .8px; margin-bottom: 8px;
        }
        .form-input-wrap { position: relative; }
        .form-input-wrap i {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #adb5bd; font-size: 14px;
        }
        .form-input {
            width: 100%; padding: 12px 14px 12px 40px;
            border: 1px solid #dee2e6; border-radius: 8px;
            font-family: 'Montserrat', sans-serif; font-size: 13px; color: #212529;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-input:focus {
            outline: none; border-color: #59b5e8; box-shadow: 0 0 0 3px rgba(89,181,232,.15);
        }
        .form-error {
            background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;
            padding: 12px 16px; border-radius: 7px; font-size: 13px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .login-btn {
            width: 100%; padding: 13px; background: #0e3c7d; color: #fff; border: none;
            border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px;
            font-weight: 700; cursor: pointer; transition: background .2s, transform .1s;
            letter-spacing: .5px; margin-top: 8px;
        }
        .login-btn:hover { background: #092d5e; }
        .login-btn:active { transform: scale(.98); }
        .login-hint {
            margin-top: 24px; font-size: 11px; color: #adb5bd; text-align: center; line-height: 1.8;
        }
        .login-hint strong { color: #0e3c7d; }

        @media (max-width: 640px) {
            .login-panel-left { display: none; }
            .login-panel-right { padding: 40px 28px; }
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <!-- Left Panel -->
    <div class="login-panel-left">
        <div class="login-brand-name">WAVICLE</div>
        <div class="login-brand-sub">Admin Portal</div>
        <p>Manage your Products, Services and Blog content from one place.</p>
        <div class="login-deco">
            <div class="login-deco-dot"></div>
            <div class="login-deco-dot"></div>
            <div class="login-deco-dot"></div>
        </div>
    </div>
    <!-- Right Panel -->
    <div class="login-panel-right">
        <div class="login-form-title">Welcome Back</div>
        <div class="login-form-sub">Sign in to access the admin panel</div>

        <?php if ($error): ?>
        <div class="form-error"><i class="fa fa-circle-exclamation"></i><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="login-form">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="form-input-wrap">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" class="form-input" placeholder="admin@wavicle.com"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required autofocus />
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="form-input-wrap">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required />
                </div>
            </div>
            <button type="submit" class="login-btn">Sign In &nbsp;<i class="fa fa-arrow-right"></i></button>
        </form>

        <div class="login-hint">
            Default credentials:<br />
            Email: <strong>admin@wavicle.com</strong> &nbsp;|&nbsp; Password: <strong>Admin@1234</strong><br />
            <small>Change your password after first login.</small>
        </div>
    </div>
</div>
</body>
</html>
