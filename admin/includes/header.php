<?php
require_once __DIR__ . '/../../includes/config.php';
requireLogin();

$adminName = $_SESSION['admin_name'] ?? 'Admin';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$siteName = t(getSetting('site_name_en'), getSetting('site_name_km'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Dashboard') ?> - Admin Portal</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
            --sidebar-width: 260px;
            --bg-body: #f8f9fa;
        }

        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--stone-dark);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
            color: #fff;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            text-align: center;
        }

        .sidebar-brand {
            color: var(--gold);
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-decoration: none;
        }

        .sidebar-menu {
            padding: 20px 0;
            list-style: none;
            margin: 0;
        }

        .menu-item {
            padding: 2px 15px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .menu-link:hover, .menu-link.active {
            color: #fff;
            background: rgba(201,168,76,0.15);
        }

        .menu-link.active {
            color: var(--gold);
            background: rgba(201,168,76,0.1);
        }

        .menu-link i {
            font-size: 1.1rem;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.05);
            margin: 15px 20px;
        }

        .menu-title {
            padding: 0 30px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: rgba(255,255,255,0.3);
            margin-bottom: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 0;
            transition: all 0.3s;
        }

        /* Topbar */
        .admin-topbar {
            height: 70px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--stone-dark);
            margin: 0;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-name {
            font-weight: 600;
            color: var(--stone-dark);
            font-size: 0.9rem;
        }

        .logout-btn {
            color: #dc3545;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        /* Cards & Components */
        .content-body {
            padding: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--stone-dark);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 5px;
            font-weight: 500;
        }

        .btn-action {
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 4px;
        }

        .table-custom th {
            background: #f8f9fa;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6c757d;
            border-top: none;
            padding: 12px 15px;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 15px;
            font-size: 0.9rem;
        }

        .badge-stone {
            background: var(--stone-dark);
            color: #fff;
            font-weight: 500;
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <a href="index.php" class="sidebar-brand">Rithy Granite</a>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item">
            <a href="index.php" class="menu-link <?= $currentPage === 'index' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        
        <div class="menu-divider"></div>
        <div class="menu-title">Content Management</div>
        
        <li class="menu-item">
            <a href="products.php" class="menu-link <?= $currentPage === 'products' ? 'active' : '' ?>">
                <i class="bi bi-gem"></i> Products
            </a>
        </li>
        <li class="menu-item">
            <a href="categories.php" class="menu-link <?= $currentPage === 'categories' ? 'active' : '' ?>">
                <i class="bi bi-grid-3x3-gap"></i> Categories
            </a>
        </li>
        <li class="menu-item">
            <a href="orders.php" class="menu-link <?= $currentPage === 'orders' ? 'active' : '' ?>">
                <i class="bi bi-cart-check"></i> Orders/Inquiries
            </a>
        </li>
        
        <div class="menu-divider"></div>
        <div class="menu-title">Pages & Marketing</div>
        
        <li class="menu-item">
            <a href="news.php" class="menu-link <?= $currentPage === 'news' ? 'active' : '' ?>">
                <i class="bi bi-newspaper"></i> News/Blog
            </a>
        </li>
        <li class="menu-item">
            <a href="sliders.php" class="menu-link <?= $currentPage === 'sliders' ? 'active' : '' ?>">
                <i class="bi bi-images"></i> Homepage Sliders
            </a>
        </li>
        <li class="menu-item">
            <a href="testimonials.php" class="menu-link <?= $currentPage === 'testimonials' ? 'active' : '' ?>">
                <i class="bi bi-chat-quote"></i> Testimonials
            </a>
        </li>
        
        <div class="menu-divider"></div>
        <div class="menu-title">System</div>
        
        <li class="menu-item">
            <a href="settings.php" class="menu-link <?= $currentPage === 'settings' ? 'active' : '' ?>">
                <i class="bi bi-gear"></i> Site Settings
            </a>
        </li>
        <li class="menu-item">
            <a href="users.php" class="menu-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Administrators
            </a>
        </li>
        
        <li class="menu-item mt-4">
            <a href="../index.php" class="menu-link" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i> View Website
            </a>
        </li>
    </ul>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <!-- Topbar -->
    <div class="admin-topbar">
        <h4 class="page-title"><?= e($pageTitle ?? 'Dashboard') ?></h4>
        
        <div class="user-nav">
            <span class="admin-name d-none d-md-inline">Welcome, <?= e($adminName) ?></span>
            <div class="vr mx-2 d-none d-md-block"></div>
            <a href="logout.php" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="content-body">