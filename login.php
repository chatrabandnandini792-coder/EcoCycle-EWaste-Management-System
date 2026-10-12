<?php
session_start();
if (!empty($_SESSION['ewaste_admin'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    // Demo credentials: admin / EcoCycle@123. Change these before any public deployment.
    if ($username === 'admin' && hash_equals('EcoCycle@123', $password)) {
        session_regenerate_id(true);
        $_SESSION['ewaste_admin'] = true;
        header('Location: dashboard.php'); exit;
    }
    $error = 'Incorrect username or password.';
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login | EcoCycle</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="admin-page"><div class="login-card"><a class="brand" href="../index.php"><span class="brand-mark">♻</span><span>Eco<span class="brand-accent">Cycle</span><small>ADMIN PORTAL</small></span></a><div class="section-kicker">SECURE ACCESS</div><h1>Welcome back.</h1><p>Sign in to manage e-waste pickup requests.</p><?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?><form method="post"><label>Username<input name="username" required autocomplete="username" placeholder="Admin username"></label><label>Password<input type="password" name="password" required autocomplete="current-password" placeholder="Password"></label><button class="btn btn-primary submit-btn" type="submit">Sign in <span>↗</span></button></form><div class="login-hint">Demo login: <b>admin</b> / <b>EcoCycle@123</b></div><a class="back-link" href="../index.php">← Back to website</a></div></body></html>