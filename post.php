<?php
require_once 'includes/config.php';
$db = getDB();

$slug = $_GET['slug'] ?? '';
if (!$slug) { redirect('news.php'); }

$stmt = $db->prepare("SELECT * FROM posts WHERE slug = :slug AND is_active = 1 LIMIT 1");
$stmt->execute(['slug' => $slug]);
$post = $stmt->fetch();

if (!$post) { redirect('news.php'); }

// Update views
$db->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$post['id']]);

// Get recent posts
$recent = $db->prepare("SELECT * FROM posts WHERE id != ? AND is_active = 1 ORDER BY created_at DESC LIMIT 5");
$recent->execute([$post['id']]);
$recentPosts = $recent->fetchAll();

$pageTitle = getField($post, 'title');
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<div class="page-hero">
    <div class="container">
        <h1 class="page-hero-title"><?= e(getField($post, 'title')) ?></h1>
        <p class="page-hero-breadcrumb mt-2">
            <a href="index.php"><?= $lang['nav_home'] ?></a> / 
            <a href="news.php"><?= $lang['nav_news'] ?></a> / 
            <span class="text-white-50"><?= t('Article', 'អត្ថបទ') ?></span>
        </p>
    </div>
</div>

<section class="section-pad bg-white">
    <div class="container">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="news-article" data-aos="fade-up">
                    <?php if ($post['featured_image']): ?>
                        <div class="article-featured-img rounded overflow-hidden shadow-sm border mb-4">
                            <img src="<?= e(UPLOAD_URL . $post['featured_image']) ?>" alt="<?= e(getField($post, 'title')) ?>" class="w-100">
                        </div>
                    <?php endif; ?>

                    <div class="article-meta d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                        <div class="meta-item">
                            <i class="bi bi-calendar3 text-gold me-2"></i>
                            <span class="text-muted small fw-bold text-uppercase letter-spacing-1"><?= date('F d, Y', strtotime($post['created_at'])) ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-eye text-gold me-2"></i>
                            <span class="text-muted small fw-bold text-uppercase letter-spacing-1"><?= number_format($post['views']) ?> <?= t('Views', 'ការមើល') ?></span>
                        </div>
                    </div>

                    <h1 class="article-title fw-bold mb-4" style="font-family: var(--font-heading); font-size: 2.5rem; line-height: 1.2;">
                        <?= e(getField($post, 'title')) ?>
                    </h1>

                    <div class="article-content" style="line-height: 2; font-size: 1.1rem; color: var(--text-dark);">
                        <?= nl2br(e(getField($post, 'content'))) ?>
                    </div>

                    <!-- Share -->
                    <div class="article-share mt-5 pt-5 border-top d-flex align-items-center gap-3">
                        <span class="text-muted small fw-bold text-uppercase letter-spacing-1"><?= t('Share this story:', 'ចែករំលែកអត្ថបទនេះ:') ?></span>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" target="_blank" class="share-btn fb"><i class="bi bi-facebook"></i></a>
                            <a href="https://t.me/share/url?url=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>&text=<?= urlencode(getField($post, 'title')) ?>" target="_blank" class="share-btn tg"><i class="bi bi-telegram"></i></a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    <!-- Recent Posts -->
                    <?php if (!empty($recentPosts)): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-stone-dark text-white py-3">
                            <h6 class="mb-0 fw-bold text-uppercase letter-spacing-1"><?= t('Recent Stories', 'អត្ថបទថ្មីៗ') ?></h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentPosts as $rp): ?>
                                <a href="post.php?slug=<?= e($rp['slug']) ?>" class="list-group-item list-group-item-action border-0 p-3 border-bottom">
                                    <div class="d-flex gap-3 align-items-center">
                                        <?php if ($rp['featured_image']): ?>
                                            <img src="<?= e(UPLOAD_URL . $rp['featured_image']) ?>" style="width:60px; height:60px; object-fit:cover;" class="rounded border">
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold small line-clamp-2" style="line-height:1.3;"><?= e(getField($rp, 'title')) ?></div>
                                            <div class="text-gold xsmall mt-1 fw-bold"><?= date('M d, Y', strtotime($rp['created_at'])) ?></div>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Contact CTA -->
                    <div class="card border-gold border-2 bg-stone-pale shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 text-gold fs-1"><i class="bi bi-gem"></i></div>
                            <h5 class="fw-bold mb-3"><?= t('Quality You Can Trust', 'គុណភាពដែលអ្នកទុកចិត្ត') ?></h5>
                            <p class="small text-muted mb-4"><?= t('Contact us for a free consultation on your next granite project.', 'ទាក់ទងមកយើងសម្រាប់ការពន្យល់ និងការផ្តល់យោបល់ដោយឥតគិតថ្លៃ។') ?></p>
                            <a href="contact.php" class="btn btn-gold btn-sm w-100 py-2 fw-bold text-uppercase"><?= $lang['contact_us'] ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.article-title { color: var(--stone-dark); }
.letter-spacing-1 { letter-spacing: 0.05em; }
.xsmall { font-size: 0.7rem; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.share-btn { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; transition: var(--transition); }
.share-btn:hover { transform: translateY(-3px); color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
.share-btn.fb { background: #1877f2; }
.share-btn.tg { background: #0088cc; }
</style>

<?php require_once 'includes/footer.php'; ?>