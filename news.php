<?php
require_once 'includes/config.php';
$db = getDB();

// Pagination
$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 9;

// Count total
$total = $db->query("SELECT COUNT(*) FROM posts WHERE is_active = 1")->fetchColumn();
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

// Get posts
$stmt = $db->prepare("SELECT * FROM posts WHERE is_active = 1 ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

$pageTitle = $lang['nav_news'] ?? 'News & Projects';
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<div class="page-hero">
    <div class="container">
        <h1 class="page-hero-title"><?= $lang['nav_news'] ?></h1>
        <p class="page-hero-breadcrumb mt-2">
            <a href="index.php"><?= $lang['nav_home'] ?></a> / <?= $lang['nav_news'] ?>
        </p>
    </div>
</div>

<section class="section-pad bg-white">
    <div class="container">
        <div class="row g-4">
            <?php if (empty($posts)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-newspaper fs-1 text-muted opacity-25"></i>
                    <p class="mt-3 text-muted"><?= t('No news posts found yet.', 'មិនមានព័ត៌មាននៅឡើយទេ') ?></p>
                    <a href="index.php" class="btn btn-gold mt-2"><?= $lang['nav_home'] ?></a>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $i => $p): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
                    <div class="news-card h-100 border rounded overflow-hidden shadow-hover transition">
                        <div class="news-card-img" style="height: 240px; overflow: hidden; background: var(--stone-pale);">
                            <?php if ($p['featured_image']): ?>
                                <img src="<?= e(UPLOAD_URL . $p['featured_image']) ?>" alt="<?= e(getField($p, 'title')) ?>" class="w-100 h-100 object-fit-cover transition">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center opacity-25"><i class="bi bi-newspaper fs-1"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="news-card-body p-4">
                            <div class="small text-gold fw-bold text-uppercase letter-spacing-1 mb-2"><?= date('M d, Y', strtotime($p['created_at'])) ?></div>
                            <h4 class="fw-bold mb-3 h5" style="font-family: var(--font-heading); line-height: 1.4;">
                                <a href="post.php?slug=<?= e($p['slug']) ?>" class="text-dark text-decoration-none"><?= e(getField($p, 'title')) ?></a>
                            </h4>
                            <p class="text-muted small mb-4"><?= e(mb_substr(getField($p, 'excerpt'), 0, 120)) ?>...</p>
                            <a href="post.php?slug=<?= e($p['slug']) ?>" class="fw-bold text-gold text-decoration-none small text-uppercase letter-spacing-1">
                                <?= $lang['read_more'] ?> <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav class="mt-5 pt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($pg = 1; $pg <= $totalPages; $pg++): ?>
                        <li class="page-item <?= $pg === $page ? 'active' : '' ?>">
                            <a class="page-link shadow-sm mx-1 border rounded" href="?p=<?= $pg ?>" style="<?= $pg === $page ? 'background:var(--gold); border-color:var(--gold); color:black;' : 'color:var(--stone-dark);' ?>"><?= $pg ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.shadow-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important; }
.news-card-img img { transition: transform 0.6s ease; }
.news-card:hover .news-card-img img { transform: scale(1.05); }
.letter-spacing-1 { letter-spacing: 0.05em; }
</style>

<?php require_once 'includes/footer.php'; ?>