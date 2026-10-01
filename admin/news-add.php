<?php
$pageTitle = 'Write New Post';
require_once 'includes/header.php';

$db = getDB();

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
        $featured_image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload = uploadImage($_FILES['image'], 'news');
            if (isset($upload['success'])) {
                $featured_image = $upload['path'];
            } else {
                $error = $upload['error'];
            }
        }

        if (!$error) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO posts (
                        title_en, title_km, slug, 
                        excerpt_en, excerpt_km, content_en, content_km,
                        featured_image, author_id, is_active
                    ) VALUES (
                        :t_en, :t_km, :slug,
                        :ex_en, :ex_km, :con_en, :con_km,
                        :img, :author, :active
                    )
                ");
                
                $stmt->execute([
                    't_en' => $title_en, 't_km' => $title_km, 'slug' => $slug_str,
                    'ex_en' => $excerpt_en, 'ex_km' => $excerpt_km, 
                    'con_en' => $content_en, 'con_km' => $content_km,
                    'img' => $featured_image, 'author' => $_SESSION['admin_id'], 'active' => $is_active
                ]);
                
                $success = "Post published successfully!";
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

<form action="news-add.php" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="title_en" class="form-control form-control-lg" required placeholder="Enter title here...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Excerpt / Short Summary (EN)</label>
                        <textarea name="excerpt_en" rows="3" class="form-control" placeholder="Brief summary for the listing page..."></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Main Content (EN)</label>
                        <textarea name="content_en" rows="12" class="form-control" placeholder="Write your post here..."></textarea>
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
                        <input type="text" name="title_km" class="form-control form-control-lg" placeholder="ចំណងជើងភាសាខ្មែរ...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Excerpt / Short Summary (KM)</label>
                        <textarea name="excerpt_km" rows="3" class="form-control" placeholder="សេចក្តីសង្ខេប..."></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Main Content (KM)</label>
                        <textarea name="content_km" rows="12" class="form-control" placeholder="ខ្លឹមសារអត្ថបទ..."></textarea>
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
                    <div id="imagePreview" class="mb-3 bg-light rounded d-flex align-items-center justify-content-center" style="height:200px; border:2px dashed #ddd;">
                        <span class="text-muted small">Post Thumbnail</span>
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                    <p class="text-muted small mt-2">Recommended: 1200x800px or 3:2 ratio.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Publishing Options</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Publish Post (Publicly Visible)</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-send me-2"></i> Publish Post
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
                preview.style.border = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>