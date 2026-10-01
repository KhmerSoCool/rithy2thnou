<?php
require_once __DIR__ . '/config.php';
$currentLang = getCurrentLang();

$siteName = t(getSetting('site_name_en'), getSetting('site_name_km'));
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Helper to preserve query params during lang switch
function langUrl($lang) {
    $params = $_GET;
    $params['lang'] = $lang;
    return '?' . http_build_query($params);
}

function navLink($href, $key, $currentPage) {
    global $lang;
    $active = '';
    $basePage = basename($href, '.php');
    if ($currentPage === $basePage) $active = ' active';
    echo '<a class="nav-link' . $active . '" href="' . url($href) . '">' . e($lang[$key]) . '</a>';
}
?>
<!DOCTYPE html>
<html lang="<?= $currentLang === 'km' ? 'km' : 'en' ?>" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($siteName) ?><?= isset($pageTitle) ? ' - ' . e($pageTitle) : '' ?></title>
    <meta name="description" content="<?= e(isset($metaDesc) ? $metaDesc : t(getSetting('site_tagline_en'), getSetting('site_tagline_km'))) ?>">
    
    <?php if(getSetting('site_favicon')): ?>
    <link rel="icon" type="image/png" href="<?= e(UPLOAD_URL . getSetting('site_favicon')) ?>">
    <?php endif; ?>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;1,300&family=Battambang:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    
    <?= isset($extraHead) ? $extraHead : '' ?>
</head>
<body class="lang-<?= $currentLang ?>" data-lang="<?= $currentLang ?>">

<!-- Top Bar -->
<div class="topbar d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="topbar-info">
                    <a href="tel:<?= e(getSetting('site_phone')) ?>">
                        <i class="bi bi-telephone-fill"></i> <?= e(getSetting('site_phone')) ?>
                    </a>
                    <a href="mailto:<?= e(getSetting('site_email')) ?>">
                        <i class="bi bi-envelope-fill"></i> <?= e(getSetting('site_email')) ?>
                    </a>
                    <span>
                        <i class="bi bi-geo-alt-fill"></i> <?= e(t(getSetting('site_address_en'), getSetting('site_address_km'))) ?>
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="topbar-right">
                    <!-- Language Switcher -->
                    <div class="lang-switcher">
                        <a href="<?= langUrl('en') ?>" class="lang-btn <?= $currentLang === 'en' ? 'active' : '' ?>">
                            <img src="<?= url('assets/images/flag-en.svg') ?>" alt="EN" onerror="this.style.display='none'"> EN
                        </a>
                        <span class="lang-divider">|</span>
                        <a href="<?= langUrl('km') ?>" class="lang-btn <?= $currentLang === 'km' ? 'active' : '' ?>">
                            <img src="<?= url('assets/images/flag-km.svg') ?>" alt="KM" onerror="this.style.display='none'"> ខ្មែរ
                        </a>
                    </div>
                    <?php if(getSetting('site_facebook')): ?>
                    <a href="<?= e(getSetting('site_facebook')) ?>" target="_blank" class="topbar-social"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark main-nav sticky-top" id="mainNav">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?= url('index.php') ?>">
            <?php if(getSetting('site_logo')): ?>
                <img src="<?= e(UPLOAD_URL . getSetting('site_logo')) ?>" alt="<?= e($siteName) ?>" height="55" class="brand-logo">
            <?php endif; ?>
            <div class="brand-text">
                <span class="brand-name"><?= $currentLang === 'km' ? 'ឫទ្ធី ២ធ្នូ' : 'Rithy 2 Thnou' ?></span>
                <span class="brand-sub"><?= $currentLang === 'km' ? 'ក្រានីត' : 'GRANITE' ?></span>
            </div>
        </a>

        <!-- Mobile Language Switcher -->
        <div class="d-flex d-lg-none align-items-center me-2">
            <a href="<?= langUrl('en') ?>" class="lang-btn-mob <?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
            <span class="text-white opacity-50 mx-1">|</span>
            <a href="<?= langUrl('km') ?>" class="lang-btn-mob <?= $currentLang === 'km' ? 'active' : '' ?>">ខ្មែរ</a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link <?= $currentPage==='index'?'active':'' ?>" href="<?= url('index.php') ?>"><?= $lang['nav_home'] ?></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $currentPage==='products'||$currentPage==='product'?'active':'' ?>" href="<?= url('products.php') ?>" role="button" data-bs-toggle="dropdown"><?= $lang['nav_products'] ?></a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="<?= url('products.php') ?>"><?= $lang['all_categories'] ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php
                        try {
                            $db = getDB();
                            $cats = $db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY sort_order")->fetchAll();
                            foreach ($cats as $cat) {
                                echo '<li><a class="dropdown-item" href="' . url('products.php?cat=' . e($cat['slug'])) . '">' . e(getField($cat, 'name')) . '</a></li>';
                            }
                        } catch(Exception $e) {}
                        ?>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link <?= $currentPage==='about'?'active':'' ?>" href="<?= url('about.php') ?>"><?= $lang['nav_about'] ?></a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage==='news'?'active':'' ?>" href="<?= url('news.php') ?>"><?= $lang['nav_news'] ?></a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage==='contact'?'active':'' ?>" href="<?= url('contact.php') ?>"><?= $lang['nav_contact'] ?></a></li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-gold" href="<?= url('contact.php') ?>"><?= $lang['get_quote'] ?></a>
                </li>
            </ul>
        </div>
    </div>
</nav>