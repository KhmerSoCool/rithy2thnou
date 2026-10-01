<?php
require_once '../includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(SITE_URL . '/admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_user'] = $user['username'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['admin_role'] = $user['role'];
                
                redirect(SITE_URL . '/admin/index.php');
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            $error = 'Database error. Please try again later.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}

$siteName = t(getSetting('site_name_en'), getSetting('site_name_km'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= e($siteName) ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --gold: #C9A84C;
            --gold-dark: #A07830;
            --stone-dark: #1A1714;
            --stone-mid: #2D2520;
            --white: #FFFFFF;
        }

        body {
            background-color: #0f0e0d;
            background-image: radial-gradient(circle at 50% 50%, #1a1714 0%, #0f0e0d 100%);
            font-family: 'Barlow', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: var(--white);
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: var(--stone-dark);
            border: 1px solid rgba(201, 168, 76, 0.2);
            border-radius: 4px;
            padding: 40px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 0.05em;
            margin-bottom: 5px;
            display: block;
        }

        .login-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: rgba(255, 255, 255, 0.5);
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            color: var(--white);
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--gold);
            box-shadow: none;
            color: var(--white);
        }

        .btn-gold {
            background: var(--gold);
            border: none;
            color: var(--stone-dark);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 14px;
            border-radius: 2px;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-gold:hover {
            background: var(--gold-light, #E8C96C);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(201, 168, 76, 0.3);
        }

        .alert-custom {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff8e98;
            font-size: 0.85rem;
            border-radius: 2px;
            padding: 12px;
            margin-bottom: 25px;
        }

        .back-to-site {
            display: block;
            text-align: center;
            margin-top: 25px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-to-site:hover {
            color: var(--gold);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <span class="brand-name">Rithy 2 Thnou</span>
        <div class="login-title">Administrative Portal</div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-custom">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-4">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" required autofocus>
        </div>
        
        <div class="mb-4">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-gold">Sign In</button>
    </form>

    <a href="../index.php" class="back-to-site">
        <i class="bi bi-arrow-left me-1"></i> Back to Website
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>