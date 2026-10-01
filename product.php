<?php
require_once 'includes/config.php';
$db = getDB();

$slug = $_GET['slug'] ?? '';
if (!$slug) { redirect(url('products.php')); }

$stmt = $db->prepare("SELECT p.*, c.name_en cat_en, c.name_km cat_km, c.slug cat_slug FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.slug=:slug AND p.is_active=1");
$stmt->execute(['slug' => $slug]);
$product = $stmt->fetch();
if (!$product) { redirect(url('products.php')); }

// Update views
$db->prepare("UPDATE products SET views=views+1 WHERE id=:id")->execute(['id' => $product['id']]);

// Related products
$related = $db->prepare("SELECT p.*, c.name_en cat_en, c.name_km cat_km FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.category_id=:cat AND p.id!=:id AND p.is_active=1 LIMIT 4");
$related->execute(['cat' => $product['category_id'], 'id' => $product['id']]);
$related = $related->fetchAll();

// Gallery images
$gallery = $product['gallery'] ? json_decode($product['gallery'], true) : [];

$pageTitle = getField($product, 'name');
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<div class="page-hero">
    <div class="container">
        <h1 class="page-hero-title"><?= e(getField($product, 'name')) ?></h1>
        <p class="page-hero-breadcrumb mt-2">
            <a href="index.php"><?= $lang['nav_home'] ?></a> /
            <a href="products.php"><?= $lang['nav_products'] ?></a> /
            <?php if ($product['cat_slug']): ?>
            <a href="products.php?cat=<?= e($product['cat_slug']) ?>"><?= e(t($product['cat_en'] ?? '', $product['cat_km'] ?? '')) ?></a> /
            <?php endif; ?>
            <span class="text-white-50"><?= e(getField($product, 'name')) ?></span>
        </p>
    </div>
</div>

<section class="section-pad">
    <div class="container">
        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="product-main-img-wrap rounded overflow-hidden shadow-sm border mb-3">
                    <?php if ($product['featured_image']): ?>
                    <img id="productMainImg" src="<?= e(UPLOAD_URL . $product['featured_image']) ?>" alt="<?= e(getField($product, 'name')) ?>" class="product-detail-img w-100">
                    <?php else: ?>
                    <div class="product-detail-img placeholder-img" style="height:450px;"><i class="bi bi-gem fs-1"></i></div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($gallery)): ?>
                <div class="d-flex gap-2 flex-wrap mt-3">
                    <?php if ($product['featured_image']): ?>
                    <img src="<?= e(UPLOAD_URL . $product['featured_image']) ?>" class="gallery-thumb active rounded" alt="" onclick="changeMainImg(this.src, this)">
                    <?php endif; ?>
                    <?php foreach ($gallery as $img): ?>
                    <img src="<?= e(UPLOAD_URL . $img) ?>" class="gallery-thumb rounded" alt="" onclick="changeMainImg(this.src, this)">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="product-cat-label mb-2"><?= e(t($product['cat_en'] ?? '', $product['cat_km'] ?? '')) ?></div>
                <h1 class="section-title mb-3" style="font-size: 2.5rem;"><?= e(getField($product, 'name')) ?></h1>
                <div class="divider-gold"></div>

                <div class="product-price-large my-4 py-2 border-bottom border-top d-flex align-items-baseline gap-2">
                    <?php if ($product['price'] > 0): ?>
                        <span class="text-gold h2 fw-bold mb-0" style="font-family:var(--font-heading);"><?= formatPrice($product['price']) ?></span>
                        <span class="text-muted small">/ <?= e(t($product['price_unit_en'], $product['price_unit_km'])) ?></span>
                    <?php else: ?>
                        <span class="text-gold h3 fw-bold mb-0" style="font-family:var(--font-heading);"><?= t('Price on Inquiry', 'ទំនាក់ទំនងសាកសួរតម្លៃ') ?></span>
                    <?php endif; ?>
                </div>

                <div class="product-short-info mb-4">
                    <p class="lead" style="font-size: 1.05rem; color:var(--text-muted); line-height: 1.8;">
                        <?= e(getField($product, 'short_desc')) ?>
                    </p>
                </div>

                <!-- Specs Grid -->
                <div class="product-specs-grid my-4">
                    <h5 class="fw-bold mb-3" style="font-family:var(--font-heading);"><?= $lang['specifications'] ?></h5>
                    <div class="row g-2">
                        <?php if ($product['thickness']): ?>
                        <div class="col-6 col-sm-4">
                            <div class="spec-item p-3 rounded bg-light border text-center">
                                <div class="text-muted xsmall text-uppercase mb-1 fw-bold letter-spacing-1"><?= $lang['thickness'] ?></div>
                                <div class="fw-bold"><?= e($product['thickness']) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($product['size']): ?>
                        <div class="col-6 col-sm-4">
                            <div class="spec-item p-3 rounded bg-light border text-center">
                                <div class="text-muted xsmall text-uppercase mb-1 fw-bold letter-spacing-1"><?= t('Dimensions', 'ទំហំ') ?></div>
                                <div class="fw-bold"><?= e($product['size']) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($product['origin_en']): ?>
                        <div class="col-6 col-sm-4">
                            <div class="spec-item p-3 rounded bg-light border text-center">
                                <div class="text-muted xsmall text-uppercase mb-1 fw-bold letter-spacing-1"><?= $lang['origin'] ?></div>
                                <div class="fw-bold"><?= e(t($product['origin_en'], $product['origin_km'])) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-3 flex-wrap mt-5">
                    <a href="contact.php?product=<?= e(urlencode(getField($product, 'name'))) ?>" class="btn btn-gold btn-lg px-5 py-3 fw-bold flex-grow-1 flex-md-grow-0 shadow-sm">
                        <i class="bi bi-chat-left-text-fill me-2"></i><?= $lang['get_quote'] ?>
                    </a>
                    <a href="tel:<?= e(getSetting('site_phone')) ?>" class="btn btn-outline-gold btn-lg px-4 py-3 fw-bold flex-grow-1 flex-md-grow-0">
                        <i class="bi bi-telephone-fill me-2"></i><?= e(getSetting('site_phone')) ?>
                    </a>
                </div>

                <!-- Share -->
                <div class="mt-5 pt-4 d-flex align-items-center gap-3 border-top">
                    <span class="text-muted small fw-bold text-uppercase letter-spacing-1"><?= t('Share on:', 'ចែករំលែកតាម:') ?></span>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" target="_blank" class="share-btn fb" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://wa.me/?text=<?= urlencode(getField($product, 'name') . ' - ' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" target="_blank" class="share-btn wa" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://t.me/share/url?url=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>&text=<?= urlencode(getField($product, 'name')) ?>" target="_blank" class="share-btn tg" title="Telegram"><i class="bi bi-telegram"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Tabs / Content -->
        <div class="row mt-5 pt-4">
            <div class="col-12">
                <ul class="nav nav-tabs custom-tabs mb-4" id="productTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold text-uppercase px-4 py-3" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button"><?= $lang['product_detail'] ?></button>
                    </li>
                    <?php if($product['finish_type_en']): ?>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-uppercase px-4 py-3" id="finish-tab" data-bs-toggle="tab" data-bs-target="#finish" type="button"><?= t('Finishing', 'ប្រភេទសម្រេច') ?></button>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content p-4 border rounded bg-white" id="productTabsContent">
                    <div class="tab-pane fade show active" id="details">
                        <?php $desc = getField($product, 'description'); ?>
                        <?php if($desc): ?>
                            <div class="rich-text-content" style="line-height: 2; color:var(--text-muted);">
                                <?= nl2br(e($desc)) ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted italic"><?= t('No detailed description available for this product.', 'មិនមានការពិពណ៌នាលម្អិតសម្រាប់ផលិតផលនេះឡើយ។') ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if($product['finish_type_en']): ?>
                    <div class="tab-pane fade" id="finish">
                        <div class="d-flex align-items-center gap-3">
                            <div class="finish-icon bg-stone-pale p-3 rounded-circle" style="width: 60px; height: 60px; display:flex; align-items:center; justify-content:center; color:var(--gold);">
                                <i class="bi bi-palette-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1"><?= t('Surface Treatment', 'ការកែច្នៃផ្ទៃ') ?></h6>
                                <p class="mb-0 text-muted"><?= e(t($product['finish_type_en'], $product['finish_type_km'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related)): ?>
        <div class="row mt-5 pt-5">
            <div class="col-12 mb-4">
                <h3 class="section-title" style="font-size: 1.8rem;"><?= $lang['related_products'] ?></h3>
                <div class="divider-gold"></div>
            </div>
            <div class="row g-4">
                <?php foreach ($related as $rp): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card">
                        <div class="product-card-img" style="height:200px;">
                            <?php if ($rp['featured_image']): ?>
                            <img src="<?= e(UPLOAD_URL . $rp['featured_image']) ?>" alt="<?= e(getField($rp, 'name')) ?>">
                            <?php else: ?>
                            <div class="placeholder-img w-100 h-100"><i class="bi bi-gem"></i></div>
                            <?php endif; ?>
                            <a href="product.php?slug=<?= e($rp['slug']) ?>" class="product-overlay">
                                <span class="btn btn-light btn-sm fw-bold"><?= $lang['learn_more'] ?></span>
                            </a>
                        </div>
                        <div class="product-card-body p-3">
                            <h6 class="product-title mb-2" style="font-size:1.05rem;"><a href="product.php?slug=<?= e($rp['slug']) ?>" class="text-dark"><?= e(getField($rp, 'name')) ?></a></h6>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="text-gold fw-bold small"><?= $rp['price'] > 0 ? formatPrice($rp['price']) : t('Enquire', 'សាកសួរ') ?></span>
                                <a href="product.php?slug=<?= e($rp['slug']) ?>" class="btn-view-product" style="width:30px; height:30px; font-size: 0.8rem;"><i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.product-detail-img {
    height: auto;
    max-height: 550px;
    object-fit: contain;
    background: #fff;
}
.gallery-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: var(--transition);
    opacity: 0.6;
}
.gallery-thumb:hover, .gallery-thumb.active {
    opacity: 1;
    border-color: var(--gold);
    box-shadow: 0 0 0 2px rgba(201,168,76,0.2);
}
.letter-spacing-1 { letter-spacing: 0.05em; }
.xsmall { font-size: 0.7rem; }
.share-btn {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem;
    transition: var(--transition);
}
.share-btn:hover { transform: translateY(-3px); color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
.share-btn.fb { background: #1877f2; }
.share-btn.wa { background: #25D366; }
.share-btn.tg { background: #0088cc; }

.custom-tabs .nav-link {
    border: none;
    color: var(--text-muted);
    border-bottom: 2px solid transparent;
    transition: var(--transition);
}
.custom-tabs .nav-link.active {
    color: var(--gold);
    border-bottom: 2px solid var(--gold);
    background: transparent;
}
.custom-tabs .nav-link:hover {
    color: var(--gold);
    border-color: rgba(201,168,76,0.3);
}
</style>

<script>
function changeMainImg(src, el) {
    document.getElementById('productMainImg').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}
</script>

<?php require_once 'includes/footer.php'; ?>