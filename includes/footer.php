<?php // Footer template ?>
<!-- Footer -->
<footer class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="row g-5">
                <!-- Brand Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <a href="<?= url('index.php') ?>" class="text-decoration-none d-flex align-items-center gap-3 mb-3">
                            <?php if(getSetting('site_logo')): ?>
                                <img src="<?= e(UPLOAD_URL . getSetting('site_logo')) ?>" alt="<?= e($siteName) ?>" height="45" class="brand-logo">
                            <?php endif; ?>
                            <div class="footer-logo-text">
                                <span class="footer-brand-name"><?= $currentLang === 'km' ? 'ឫទ្ធី ២ធ្នូ' : 'Rithy 2 Thnou' ?></span>
                                <span class="footer-brand-sub"><?= $currentLang === 'km' ? 'ក្រានីត' : 'GRANITE' ?></span>
                            </div>
                        </a>
                        
                        <p class="footer-desc mt-3">
                            <?= e(t(getSetting('about_short_en'), getSetting('about_short_km'))) ?>
                        </p>
                        <div class="footer-social mt-4">
                            <?php if(getSetting('site_facebook')): ?>
                            <a href="<?= e(getSetting('site_facebook')) ?>" target="_blank" class="social-icon"><i class="bi bi-facebook"></i></a>
                            <?php endif; ?>
                            <a href="tel:<?= e(getSetting('site_phone')) ?>" class="social-icon"><i class="bi bi-telephone-fill"></i></a>
                            <a href="mailto:<?= e(getSetting('site_email')) ?>" class="social-icon"><i class="bi bi-envelope-fill"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading"><?= $lang['footer_quick_links'] ?></h5>
                    <ul class="footer-links">
                        <li><a href="<?= url('index.php') ?>"><i class="bi bi-chevron-right"></i> <?= $lang['nav_home'] ?></a></li>
                        <li><a href="<?= url('products.php') ?>"><i class="bi bi-chevron-right"></i> <?= $lang['nav_products'] ?></a></li>
                        <li><a href="<?= url('about.php') ?>"><i class="bi bi-chevron-right"></i> <?= $lang['nav_about'] ?></a></li>
                        <li><a href="<?= url('news.php') ?>"><i class="bi bi-chevron-right"></i> <?= $lang['nav_news'] ?></a></li>
                        <li><a href="<?= url('contact.php') ?>"><i class="bi bi-chevron-right"></i> <?= $lang['nav_contact'] ?></a></li>
                    </ul>
                </div>

                <!-- Products -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading"><?= $lang['footer_products'] ?></h5>
                    <ul class="footer-links">
                        <?php
                        try {
                            $db = getDB();
                            $cats = $db->query("SELECT * FROM categories ORDER BY sort_order LIMIT 6")->fetchAll();
                            foreach ($cats as $cat) {
                                echo '<li><a href="' . url('products.php?cat=' . e($cat['slug'])) . '"><i class="bi bi-chevron-right"></i> ' . e(getField($cat, 'name')) . '</a></li>';
                            }
                        } catch(Exception $e) {}
                        ?>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading"><?= $lang['footer_contact'] ?></h5>
                    <ul class="footer-contact-list">
                        <li>
                            <i class="bi bi-geo-alt-fill text-gold"></i>
                            <span><?= e(t(getSetting('site_address_en'), getSetting('site_address_km'))) ?></span>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill text-gold"></i>
                            <span>
                                <a href="tel:<?= e(getSetting('site_phone')) ?>"><?= e(getSetting('site_phone')) ?></a><br>
                                <?php if(getSetting('site_phone2')): ?>
                                <a href="tel:<?= e(getSetting('site_phone2')) ?>"><?= e(getSetting('site_phone2')) ?></a>
                                <?php endif; ?>
                            </span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill text-gold"></i>
                            <span><a href="mailto:<?= e(getSetting('site_email')) ?>"><?= e(getSetting('site_email')) ?></a></span>
                        </li>
                    </ul>
                    <!-- Language Switcher -->
                    <div class="footer-lang mt-4">
                        <span class="text-muted small text-uppercase fw-bold letter-spacing-1 d-block mb-2"><?= t('Language', 'ភាសា') ?></span>
                        <a href="<?= langUrl('en') ?>" class="footer-lang-btn <?= $currentLang === 'en' ? 'active' : '' ?>">English</a>
                        <span class="text-white-50 mx-2">|</span>
                        <a href="<?= langUrl('km') ?>" class="footer-lang-btn <?= $currentLang === 'km' ? 'active' : '' ?>">ភាសាខ្មែរ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">&copy; <?= date('Y') ?> <?= e($siteName) ?>. <?= $lang['footer_rights'] ?>.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 footer-tagline"><?= e(t(getSetting('site_tagline_en'), getSetting('site_tagline_km'))) ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll To Top -->
<button class="scroll-top-btn" id="scrollTopBtn" title="<?= t('Back to top', 'ត្រឡប់ទៅលើ') ?>">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Custom JS -->
<script src="<?= url('assets/js/main.js') ?>"></script>

<?= isset($extraScripts) ? $extraScripts : '' ?>
</body>
</html>