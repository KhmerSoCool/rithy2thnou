<?php
require_once 'includes/config.php';
$db = getDB();

// Get sliders
$sliders = $db->query("SELECT * FROM sliders WHERE is_active=1 ORDER BY sort_order")->fetchAll();

// Get featured products
$featured = $db->query("SELECT p.*, c.name_en cat_en, c.name_km cat_km FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_featured=1 AND p.is_active=1 ORDER BY p.sort_order LIMIT 6")->fetchAll();

// Get testimonials
$testimonials = $db->query("SELECT * FROM testimonials WHERE is_active=1 ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Get latest posts
$posts = $db->query("SELECT * FROM posts WHERE is_active=1 ORDER BY created_at DESC LIMIT 3")->fetchAll();

require_once 'includes/header.php';
?>

<!-- ======================== HERO SECTION / SLIDER ======================== -->
<?php if (!empty($sliders)): ?>
<div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="6000">
    <div class="carousel-indicators">
        <?php foreach ($sliders as $i => $s): ?>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($sliders as $i => $s): ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
            <div class="hero-carousel-bg" style="background-image: linear-gradient(rgba(26,23,20,0.65), rgba(26,23,20,0.65)), url('<?= e(UPLOAD_URL . $s['image']) ?>');"></div>
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-8" data-aos="fade-up" data-aos-duration="1200">
                        <div class="hero-content text-white">
                            <span class="hero-tag-premium mb-3"><?= e(t($s['subtitle_en'], $s['subtitle_km'])) ?></span>
                            <h1 class="hero-title-premium mb-4"><?= e(t($s['title_en'], $s['title_km'])) ?></h1>
                            <div class="hero-btns mt-5">
                                <a href="<?= e($s['link'] ?: 'products.php') ?>" class="btn btn-gold btn-lg px-5 py-3 fw-bold shadow-lg">
                                    <?= e(t($s['btn_text_en'], $s['btn_text_km'])) ?> <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($sliders) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
    <?php endif; ?>
</div>
<?php else: ?>
<!-- Fallback Static Hero if no sliders -->
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-content container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="hero-tag-premium"><?= t('Premium Natural Stone', 'ថ្មធម្មជាតិពិសេស') ?></div>
                <h1 class="hero-title-premium mt-3">
                    <?= t('Cambodia\'s Finest <span class="gold-text">Granite</span><br>& Stone Supplier', 'អ្នកផ្គត់ផ្គង់ <span class="gold-text">ក្រានីត</span><br>ល្អបំផុតនៅកម្ពុជា') ?>
                </h1>
                <p class="hero-desc mt-4 lead text-white-50">
                    <?= e(t(getSetting('site_tagline_en'), getSetting('site_tagline_km'))) ?>
                </p>
                <div class="hero-btns mt-5">
                    <a href="products.php" class="btn btn-gold btn-lg px-5 py-3 fw-bold"><?= $lang['hero_btn_products'] ?></a>
                    <a href="contact.php" class="btn btn-outline-white btn-lg px-4 py-3 ms-md-3 fw-bold"><?= $lang['hero_btn_contact'] ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ======================== FEATURES ======================== -->
<section class="features-section section-pad-sm">
    <div class="container">
        <div class="row g-3">
            <?php
            $features = [
                ['bi-gem', 'feature1_title', 'feature1_text'],
                ['bi-tools', 'feature2_title', 'feature2_text'],
                ['bi-tag-fill', 'feature3_title', 'feature3_text'],
                ['bi-truck', 'feature4_title', 'feature4_text'],
                ['bi-scissors', 'feature5_title', 'feature5_text'],
                ['bi-shield-check', 'feature6_title', 'feature6_text'],
            ];
            foreach ($features as $i => $feat):
            ?>
            <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="<?= $i * 50 ?>">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi <?= $feat[0] ?>"></i></div>
                    <h6 class="feature-title fw-bold"><?= $lang[$feat[1]] ?></h6>
                    <p class="feature-text small opacity-75"><?= $lang[$feat[2]] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================== FEATURED PRODUCTS ======================== -->
<section class="section-pad bg-white">
    <div class="container">
        <div class="row mb-5 align-items-end">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="section-tag"><?= t('Our Collection', 'បណ្តុំរបស់យើង') ?></div>
                <h2 class="section-title"><?= $lang['featured_products'] ?></h2>
                <div class="divider-gold"></div>
                <p class="section-desc"><?= $lang['featured_products_sub'] ?></p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <a href="products.php" class="btn btn-outline-gold px-4 fw-bold shadow-sm"><?= $lang['view_all'] ?> <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($featured)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-gem fs-1 opacity-25 mb-3 d-block"></i>
                    <?= t('No featured products yet.', 'មិនទាន់មានផលិតផលពិសេសនៅឡើយទេ') ?>
                </div>
            <?php else: ?>
            <?php foreach ($featured as $i => $p): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
                <div class="product-card">
                    <div class="product-card-img">
                        <?php if ($p['featured_image']): ?>
                            <img src="<?= e(UPLOAD_URL . $p['featured_image']) ?>" alt="<?= e(getField($p, 'name')) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="placeholder-img w-100 h-100"><i class="bi bi-gem"></i></div>
                        <?php endif; ?>
                        <div class="product-card-badge"><?= t('Premium', 'ផលិតផលសម្រាំង') ?></div>
                        <a href="product.php?slug=<?= e($p['slug']) ?>" class="product-overlay">
                            <span class="btn btn-light btn-sm fw-bold text-uppercase"><?= $lang['learn_more'] ?></span>
                        </a>
                    </div>
                    <div class="product-card-body">
                        <div class="product-cat-label"><?= e(t($p['cat_en'] ?? '', $p['cat_km'] ?? '')) ?></div>
                        <h3 class="product-title"><?= e(getField($p, 'name')) ?></h3>
                        <p class="product-desc small text-muted"><?= e(mb_substr(getField($p, 'short_desc'), 0, 100)) ?>...</p>
                        <div class="product-footer mt-auto">
                            <div class="product-price">
                                <?php if ($p['price'] > 0): ?>
                                    <?= formatPrice($p['price']) ?> <span class="price-unit small opacity-75">/ <?= e(t($p['price_unit_en'], $p['price_unit_km'])) ?></span>
                                <?php else: ?>
                                    <span class="text-gold fw-bold"><?= t('Ask for Quote', 'សាកសួរតម្លៃ') ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="product.php?slug=<?= e($p['slug']) ?>" class="btn-view-product">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ======================== WHY CHOOSE US ======================== -->
<section class="cta-section section-pad overflow-hidden">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="section-tag"><?= t('Excellence in Stone', 'ឧត្តមភាពក្នុងវិស័យថ្ម') ?></div>
                <h2 class="section-title section-title-light"><?= $lang['why_choose'] ?></h2>
                <div class="divider-gold"></div>
                <p class="text-white-50 mb-5 lead" style="line-height:1.9;">
                    <?= t(
                        'With over 20 years in Cambodia\'s natural stone industry, Rithy 2 Thnou Granite has built a reputation on quality, precision, and unparalleled craftsmanship. We source the finest materials globally to bring luxury and durability to your doorstep.',
                        'ជាមួយបទពិសោធន៍ជាង ២០ ឆ្នាំក្នុងឧស្សកម្មថ្មធម្មជាតិនៅកម្ពុជា ឫទ្ធី ២ធ្នូ ក្រានីត បានកសាងកេរ្តិ៍ឈ្មោះលើគុណភាព ភាពច្បាស់លាស់ និងជំនាញសិប្បកម្មដែលគ្មានគូប្រៀប។ យើងស្វែងរកសម្ភារៈល្អបំផុតជាសកលដើម្បីនាំមកនូវភាពប្រណិត និងភាពធន់ជូនដល់អ្នក។'
                    ) ?>
                </p>
                <div class="row g-4 mb-5">
                    <?php
                    $whys = [
                        ['bi-patch-check-fill', t('High Quality Materials', 'សម្ភារៈគុណភាពខ្ពស់')],
                        ['bi-globe-americas', t('Global Sourcing', 'នាំចូលពីជុំវិញពិភពលោក')],
                        ['bi-shield-fill-check', t('Expert Installation', 'ការដំឡើងដោយអ្នកជំនាញ')],
                        ['bi-headset', t('Full After-Sales Support', 'សេវាកម្មគាំទ្រពេញលេញ')],
                    ];
                    foreach ($whys as $w):
                    ?>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-gold d-flex align-items-center justify-content-center" style="width:32px; height:32px; flex-shrink:0;">
                                <i class="bi <?= $w[0] ?>" style="color: var(--stone-dark); font-size:0.9rem;"></i>
                            </div>
                            <span class="text-white opacity-90 fw-bold small text-uppercase letter-spacing-1"><?= e($w[1]) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="about.php" class="btn btn-gold btn-lg px-5 py-3 fw-bold shadow-lg"><?= $lang['learn_more'] ?> <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
            <div class="col-lg-5" data-aos="zoom-in" data-aos-delay="200">
                <div class="stats-grid">
                    <?php
                    $stats2 = [
                        ['20', t('Years', 'ឆ្នាំ'), '+'],
                        ['500', t('Projects', 'គម្រោង'), '+'],
                        ['200', t('Products', 'ផលិតផល'), '+'],
                        ['1000', t('Clients', 'អតិថិជន'), '+'],
                    ];
                    foreach ($stats2 as $s):
                    ?>
                    <div class="stat-card-mini">
                        <div class="stat-val-mini" data-count="<?= $s[0] ?>" data-suffix="<?= $s[2] ?>">0</div>
                        <div class="stat-label-mini"><?= e($s[1]) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================== TESTIMONIALS ======================== -->
<?php if (!empty($testimonials)): ?>
<section class="testimonials-section section-pad bg-stone-pale">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-tag justify-content-center"><?= t('Client Reviews', 'មតិអតិថិជន') ?></div>
            <h2 class="section-title-text"><?= $lang['testimonials'] ?></h2>
            <div class="divider-gold mx-auto"></div>
        </div>
        <div class="row g-4">
            <?php foreach ($testimonials as $i => $t_item): ?>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                <div class="testi-card-premium shadow-sm h-100 bg-white p-5 rounded position-relative">
                    <div class="quote-icon position-absolute top-0 end-0 p-4 opacity-10"><i class="bi bi-quote fs-1"></i></div>
                    <div class="testi-stars mb-4 text-warning">
                        <?= str_repeat('<i class="bi bi-star-fill"></i>', (int)$t_item['rating']) ?>
                    </div>
                    <p class="testi-text-main mb-4" style="line-height: 1.8; font-style: italic; color:var(--text-dark);">
                        "<?= e(getField($t_item, 'message')) ?>"
                    </p>
                    <div class="testi-author-info d-flex align-items-center gap-3 mt-auto">
                        <div class="testi-avatar-main bg-gold text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-size: 1.2rem;">
                            <?= mb_substr(getField($t_item, 'client_name') ?: 'A', 0, 1) ?>
                        </div>
                        <div>
                            <div class="fw-bold"><?= e(getField($t_item, 'client_name')) ?></div>
                            <div class="text-gold small fw-bold text-uppercase letter-spacing-1" style="font-size: 0.7rem;"><?= e(getField($t_item, 'company')) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ======================== LATEST NEWS ======================== -->
<?php if (!empty($posts)): ?>
<section class="section-pad bg-white">
    <div class="container">
        <div class="row mb-5 align-items-end">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="section-tag"><?= t('Updates & News', 'ព័ត៌មានថ្មីៗ') ?></div>
                <h2 class="section-title"><?= $lang['latest_news'] ?></h2>
                <div class="divider-gold"></div>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <a href="news.php" class="btn btn-outline-gold px-4 fw-bold shadow-sm"><?= $lang['view_all'] ?></a>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($posts as $i => $p): ?>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
                <div class="news-card-premium border rounded overflow-hidden h-100 d-flex flex-column transition shadow-hover">
                    <div class="news-img-wrap overflow-hidden" style="height:240px;">
                        <?php if ($p['featured_image']): ?>
                            <img src="<?= e(UPLOAD_URL . $p['featured_image']) ?>" alt="<?= e(getField($p, 'title')) ?>" class="w-100 h-100 object-fit-cover transition">
                        <?php else: ?>
                            <div class="bg-stone-pale w-100 h-100 d-flex align-items-center justify-content-center"><i class="bi bi-newspaper fs-1 opacity-25"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="news-body p-4 flex-grow-1 d-flex flex-column">
                        <div class="news-date-tag small text-gold fw-bold text-uppercase letter-spacing-1 mb-2"><?= date('M d, Y', strtotime($p['created_at'])) ?></div>
                        <h4 class="news-title-link mb-3"><a href="post.php?slug=<?= e($p['slug']) ?>" class="text-dark text-decoration-none fw-bold"><?= e(getField($p, 'title')) ?></a></h4>
                        <p class="text-muted small mb-4"><?= e(mb_substr(getField($p, 'excerpt'), 0, 100)) ?>...</p>
                        <a href="post.php?slug=<?= e($p['slug']) ?>" class="mt-auto fw-bold text-gold text-decoration-none small text-uppercase letter-spacing-1">
                            <?= $lang['read_more'] ?> <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ======================== CONTACT CTA ======================== -->
<section class="section-pad bg-stone-cream text-dark text-center position-relative overflow-hidden">
    <div class="position-absolute top-50 start-50 translate-middle opacity-05" style="font-size: 20rem; color: var(--gold); z-index: 0;"><i class="bi bi-gem"></i></div>
    <div class="container position-relative" style="z-index: 1;" data-aos="zoom-in">
        <div class="section-tag justify-content-center mx-auto mb-4"><?= t('Partner with Experts', 'សហការជាមួយអ្នកជំនាញ') ?></div>
        <h2 class="section-title mb-3" style="font-size: 3rem; color: var(--stone-dark);"><?= t('Get a Free Quote Today', 'ស្នើសុំសម្រង់តម្លៃដោយឥតគិតថ្លៃ') ?></h2>
        <div class="divider-gold mx-auto mb-4" style="width:100px;"></div>
        <p class="text-muted mb-5 mx-auto lead" style="max-width:600px; font-weight: 500;">
            <?= t('Looking for a custom quote or need to discuss your project requirements? Our stone experts are ready to assist you.', 'តើអ្នកកំពុងស្វែងរកការសាកសួរតម្លៃ ឬចង់ពិភាក្សាអំពីតម្រូវការគម្រោងរបស់អ្នកមែនទេ? អ្នកជំនាញរបស់យើងត្រៀមខ្លួនជាស្រេចដើម្បីជួយអ្នក។') ?>
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="contact.php" class="btn btn-gold btn-lg px-5 py-3 fw-bold shadow-lg"><i class="bi bi-envelope-fill me-2"></i><?= $lang['contact_us'] ?></a>
            <a href="tel:<?= e(getSetting('site_phone')) ?>" class="btn btn-outline-dark btn-lg px-4 py-3 fw-bold border-2"><i class="bi bi-telephone-fill me-2"></i><?= e(getSetting('site_phone')) ?></a>
        </div>
    </div>
</section>

<style>
.hero-carousel { height: 90vh; min-height: 600px; }
.hero-carousel .carousel-item { height: 90vh; min-height: 600px; }
.hero-carousel-bg { position: absolute; inset: 0; background-size: cover; background-position: center; z-index: -1; }
.hero-title-premium { font-size: clamp(2.5rem, 5vw, 4.5rem); font-weight: 700; line-height: 1.1; font-family: var(--font-heading); }
.hero-tag-premium { display: inline-block; padding: 5px 15px; border-left: 3px solid var(--gold); background: rgba(201,168,76,0.1); font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; font-size: 0.85rem; color: var(--gold); }
.stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.stat-card-mini { background: rgba(255,255,255,0.05); border: 1px solid rgba(201,168,76,0.2); padding: 30px 20px; text-align: center; border-radius: 4px; transition: var(--transition); }
.stat-card-mini:hover { background: rgba(201,168,76,0.1); border-color: var(--gold); transform: translateY(-5px); }
.stat-val-mini { font-family: var(--font-heading); font-size: 2.5rem; font-weight: 700; color: var(--gold); line-height: 1; }
.stat-label-mini { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.5); margin-top: 5px; }
.transition { transition: var(--transition); }
.shadow-hover:hover { shadow: var(--shadow-lg); transform: translateY(-5px); }
.news-img-wrap img:hover { transform: scale(1.05); }
.btn-outline-white { color: #fff; border: 1px solid rgba(255,255,255,0.3); transition: var(--transition); }
.btn-outline-white:hover { background: #fff; color: var(--stone-dark); }
.letter-spacing-1 { letter-spacing: 0.05em; }
</style>

<?php require_once 'includes/footer.php'; ?>