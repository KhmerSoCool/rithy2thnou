<?php
$pageTitle = 'Edit Post';
require_once 'includes/header.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) redirect('news.php');

$stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) redirect('news.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_en = trim($_POST['title_en'] ?? '');
    $title_km = trim($_POST['title_km'] ?? '');
    $excerpt_en = $_POST['excerpt_en'] ?? '';
    $excerpt_km = $_POST['excerpt_km'] ?? '';
    $content_en = $_POST['content_en'] ?? '';
    $content_km = $_POST['content_km'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $slug_str = slug($title_en);

    if (empty($title_en)) {
        $error = 'Post title (English) is required.';
    } else {
        // Handle Image Upload
        $featured_image = $p['featured_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload = uploadImage($_FILES['image'], 'news');
            if (isset($upload['success'])) {
                // Delete old image
                if ($featured_image && file_exists(UPLOAD_DIR . $featured_image)) {
                    @unlink(UPLOAD_DIR . $featured_image);
                }
                $featured_image = $upload['path'];
            } else {
                $error = $upload['error'];
            }
        }

        if (!$error) {
            try {
                $stmt = $db->prepare("
                    UPDATE posts SET 
                        title_en = :t_en, title_km = :t_km, slug = :slug, 
                        excerpt_en = :ex_en, excerpt_km = :ex_km, 
                        content_en = :con_en, content_km = :con_km,
                        featured_image = :img, is_active = :active,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id
                ");
                
                $stmt->execute([
                    't_en' => $title_en, 't_km' => $title_km, 'slug' => $slug_str,
                    'ex_en' => $excerpt_en, 'ex_km' => $excerpt_km, 
                    'con_en' => $content_en, 'con_km' => $content_km,
                    'img' => $featured_image, 'active' => $is_active, 'id' => $id
                ]);
                
                $success = "Post updated successfully!";
                // Refresh
                $stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
                $stmt->execute([$id]);
                $p = $stmt->fetch();
            } catch (Exception $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="mb-4">
    <a href="news.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Blog
    </a>
</div>

<form action="news-edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">English Content</h6>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= e($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= e($success) ?></div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Post Title (EN) <span class="text-danger">*</span></label>
                        <input type="text" name="title_en" class="form-control form-control-lg" required value="<?= e($p['title_en']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Excerpt / Short Summary (EN)</label>
                        <textarea name="excerpt_en" rows="3" class="form-control"><?= e($p['excerpt_en']) ?></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Main Content (EN)</label>
                        <textarea name="content_en" rows="12" class="form-control"><?= e($p['content_en']) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Khmer Content</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Post Title (KM)</label>
                        <input type="text" name="title_km" class="form-control form-control-lg" value="<?= e($p['title_km']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Excerpt / Short Summary (KM)</label>
                        <textarea name="excerpt_km" rows="3" class="form-control"><?= e($p['excerpt_km']) ?></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Main Content (KM)</label>
                        <textarea name="content_km" rows="12" class="form-control"><?= e($p['content_km']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Featured Image</h6>
                </div>
                <div class="card-body text-center">
                    <div id="imagePreview" class="mb-3 bg-light rounded d-flex align-items-center justify-content-center" style="height:200px;">
                        <?php if ($p['featured_image']): ?>
                            <img src="<?= UPLOAD_URL . $p['featured_image'] ?>" style="max-width:100%; max-height:100%; object-fit:contain;">
                        <?php else: ?>
                            <span class="text-muted small">Post Thumbnail</span>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                    <p class="text-muted small mt-2">Upload new image to replace current one.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Publishing Options</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= $p['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isActive">Publish Post (Publicly Visible)</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-save me-2"></i> Update Post
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `<img src="${event.target.result}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>