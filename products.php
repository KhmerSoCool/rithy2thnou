<?php
require_once 'includes/config.php';
$db = getDB();

// Filters
$catSlug = $_GET['cat'] ?? '';
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 12;

// Build query
$where = ['p.is_active = 1'];
$params = [];

if ($catSlug) {
    $where[] = 'c.slug = :cat';
    $params['cat'] = $catSlug;
}
if ($search) {
    $where[] = '(p.name_en LIKE :search OR p.name_km LIKE :search OR p.description_en LIKE :search OR p.description_km LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

$whereStr = 'WHERE ' . implode(' AND ', $where);
$countSql = "SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id=c.id $whereStr";
$stmt = $db->prepare($countSql);
$stmt->execute($params);
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $perPage);

$offset = ($page - 1) * $perPage;
$sql = "SELECT p.*, c.name_en cat_en, c.name_km cat_km, c.slug cat_slug
        FROM products p LEFT JOIN categories c ON p.category_id=c.id
        $whereStr ORDER BY p.is_featured DESC, p.sort_order ASC, p.created_at DESC
        LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params as $k => $v) $stmt->bindValue(':' . $k, $v);
$stmt->execute();
$products = $stmt->fetchAll();

$categories = $db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY sort_order")->fetchAll();
$currentCat = null;
if ($catSlug) {
    foreach ($categories as $c) { if ($c['slug'] === $catSlug) { $currentCat = $c; break; } }
}

$pageTitle = $lang['nav_products'];
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<div class="page-hero">
    <div class="container">
        <h1 class="page-hero-title"><?= $lang['products_title'] ?></h1>
        <p class="page-hero-breadcrumb mt-2">
            <a href="index.php"><?= $lang['nav_home'] ?></a> / <?= $lang['nav_products'] ?>
            <?php if ($currentCat): ?> / <?= e(getField($currentCat, 'name')) ?><?php endif; ?>
        </p>
    </div>
</div>

<section class="section-pad">
    <div class="container">
        <div class="row g-5">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar sticky-top" style="top: 100px;">
                    <h5 class="filter-title"><?= $lang['filter'] ?></h5>

                    <!-- Search -->
                    <div class="filter-group mb-4">
                        <label class="filter-label" for="prodSearch"><?= $lang['search'] ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="prodSearch" placeholder="<?= e($lang['search_placeholder']) ?>" value="<?= e($search) ?>">
                            <button class="btn btn-gold" type="button" onclick="doSearch()"><i class="bi bi-search"></i></button>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="filter-group">
                        <label class="filter-label"><?= $lang['category'] ?></label>
                        <div class="d-flex flex-column gap-1">
                            <a href="products.php" class="filter-check text-decoration-none <?= !$catSlug ? 'active fw-bold text-gold' : 'text-muted' ?>">
                                <i class="bi <?= !$catSlug ? 'bi-record-circle-fill' : 'bi-circle' ?>"></i>
                                <?= $lang['all_categories'] ?>
                            </a>
                            <?php foreach ($categories as $cat): ?>
                            <a href="?cat=<?= e($cat['slug']) ?>" class="filter-check text-decoration-none <?= $catSlug === $cat['slug'] ? 'active fw-bold text-gold' : 'text-muted' ?>">
                                <i class="bi <?= $catSlug === $cat['slug'] ? 'bi-record-circle-fill' : 'bi-circle' ?>"></i>
                                <?= e(getField($cat, 'name')) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="mt-5 p-4 rounded bg-stone text-center shadow-sm">
                        <div class="mb-3">
                            <i class="bi bi-chat-dots-fill text-gold fs-2"></i>
                        </div>
                        <h6 class="text-white mb-2"><?= t('Need Expert Advice?', 'ត្រូវការជំនួយពីអ្នកជំនាញ?') ?></h6>
                        <p class="text-white-50 small mb-4"><?= t('Let us help you find the perfect stone for your project.', 'ឱ្យយើងជួយអ្នកស្វែងរកថ្មដ៏ល្អបំផុតសម្រាប់គម្រោងរបស់អ្នក។') ?></p>
                        <a href="contact.php" class="btn btn-gold btn-sm w-100 py-2 fw-bold text-uppercase"><?= $lang['contact_us'] ?></a>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Results info -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                    <div>
                        <h4 class="mb-1 fw-bold" style="font-family:var(--font-heading);">
                            <?php if ($catSlug && $currentCat): ?>
                                <?= e(getField($currentCat, 'name')) ?>
                            <?php elseif ($search): ?>
                                <?= t('Search Results for', 'លទ្ធផលស្វែងរកសម្រាប់') ?>: "<?= e($search) ?>"
                            <?php else: ?>
                                <?= t('All Products', 'ផលិតផលទាំងអស់') ?>
                            <?php endif; ?>
                        </h4>
                        <p class="text-muted mb-0 small">
                            <?= t("Showing $total premium products", "បង្ហាញ $total ផលិតផលគុណភាពខ្ពស់") ?>
                        </p>
                    </div>
                    <?php if ($search || $catSlug): ?>
                    <div>
                        <a href="products.php" class="btn btn-sm btn-outline-secondary px-3">
                            <i class="bi bi-x-circle me-1"></i> <?= t('Clear All Filters', 'លុបតម្រងទាំងអស់') ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($products)): ?>
                <div class="text-center py-5 bg-stone-pale rounded border-dashed border-2">
                    <i class="bi bi-search fs-1 text-muted opacity-25"></i>
                    <h5 class="mt-4 text-muted"><?= t('No products matching your criteria.', 'មិនមានផលិតផលដែលត្រូវនឹងការស្វែងរករបស់អ្នកឡើយ។') ?></h5>
                    <p class="text-muted small"><?= t('Try adjusting your search or category filters.', 'សូមព្យាយាមកែតម្រូវការស្វែងរក ឬតម្រងប្រភេទរបស់អ្នក។') ?></p>
                    <a href="products.php" class="btn btn-gold mt-3 px-4"><?= $lang['view_all'] ?></a>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($products as $i => $p): ?>
                    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 50 ?>">
                        <div class="product-card">
                            <div class="product-card-img">
                                <?php if ($p['featured_image']): ?>
                                    <img src="<?= e(UPLOAD_URL . $p['featured_image']) ?>" alt="<?= e(getField($p, 'name')) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="placeholder-img w-100 h-100">
                                        <i class="bi bi-gem"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if ($p['is_featured']): ?>
                                    <div class="product-card-badge"><?= t('Premium', 'ពិសេស') ?></div>
                                <?php endif; ?>
                                <a href="product.php?slug=<?= e($p['slug']) ?>" class="product-overlay">
                                    <span class="btn btn-light btn-sm fw-bold text-uppercase"><?= $lang['learn_more'] ?></span>
                                </a>
                            </div>
                            <div class="product-card-body">
                                <div class="product-cat-label"><?= e(t($p['cat_en'] ?? '', $p['cat_km'] ?? '')) ?></div>
                                <h3 class="product-title">
                                    <a href="product.php?slug=<?= e($p['slug']) ?>" class="text-dark"><?= e(getField($p, 'name')) ?></a>
                                </h3>
                                <p class="product-desc mb-3">
                                    <?= e(mb_substr(getField($p, 'short_desc'), 0, 85)) ?><?= mb_strlen(getField($p, 'short_desc')) > 85 ? '...' : '' ?>
                                </p>
                                
                                <?php if ($p['thickness'] || $p['origin_en']): ?>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <?php if ($p['thickness']): ?>
                                        <span class="badge" style="background:var(--stone-pale); color:var(--stone-light); font-weight:500;">
                                            <i class="bi bi-layers-half me-1"></i><?= e($p['thickness']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($p['origin_en']): ?>
                                        <span class="badge" style="background:var(--stone-pale); color:var(--stone-light); font-weight:500;">
                                            <i class="bi bi-geo-alt me-1"></i><?= e(t($p['origin_en'], $p['origin_km'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <div class="product-footer">
                                    <div class="product-price">
                                        <?php if ($p['price'] > 0): ?>
                                            <?= formatPrice($p['price']) ?>
                                            <span class="price-unit">/ <?= e(t($p['price_unit_en'], $p['price_unit_km'])) ?></span>
                                        <?php else: ?>
                                            <span class="text-gold"><?= t('Inquiry Only', 'ទំនាក់ទំនងសុំតម្លៃ') ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="contact.php?product=<?= urlencode(getField($p, 'name')) ?>" class="btn-view-product" title="<?= $lang['get_quote'] ?>">
                                        <i class="bi bi-chat-quote"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav class="mt-5 pt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?p=<?= $page - 1 ?><?= $catSlug ? '&cat='.$catSlug : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($pg = 1; $pg <= $totalPages; $pg++): ?>
                            <?php if ($pg == 1 || $pg == $totalPages || ($pg >= $page - 1 && $pg <= $page + 1)): ?>
                                <li class="page-item <?= $pg === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?p=<?= $pg ?><?= $catSlug ? '&cat='.$catSlug : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>"><?= $pg ?></a>
                                </li>
                            <?php elseif ($pg == 2 || $pg == $totalPages - 1): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?p=<?= $page + 1 ?><?= $catSlug ? '&cat='.$catSlug : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.filter-check {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: var(--transition);
}
.filter-check:hover {
    background: rgba(201,168,76,0.05);
    color: var(--gold);
}
.filter-check.active {
    background: rgba(201,168,76,0.1);
}
.filter-check i {
    font-size: 0.8rem;
}
.product-overlay {
    position: absolute; inset: 0;
    background: rgba(26,23,20,0.4);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: var(--transition);
    z-index: 1;
}
.product-card-img:hover .product-overlay { opacity: 1; }
.pagination .page-link {
    border: 1px solid var(--border);
    color: var(--stone-dark);
    font-weight: 600;
    min-width: 40px;
    text-align: center;
    margin: 0 3px;
    border-radius: 3px !important;
}
.pagination .page-item.active .page-link {
    background-color: var(--gold);
    border-color: var(--gold);
    color: var(--stone-dark);
}
.pagination .page-link:hover {
    background-color: var(--stone-pale);
    border-color: var(--gold);
}
.border-dashed { border-style: dashed !important; }
</style>

<script>
function doSearch() {
    const val = document.getElementById('prodSearch').value;
    const urlParams = new URLSearchParams(window.location.search);
    if (val) {
        urlParams.set('search', val);
    } else {
        urlParams.delete('search');
    }
    urlParams.delete('p'); // Reset to page 1 on search
    window.location.search = urlParams.toString();
}

// Add Enter key listener
document.getElementById('prodSearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') doSearch();
});
</script>

<?php require_once 'includes/footer.php'; ?>