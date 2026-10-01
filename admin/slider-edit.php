<?php
$pageTitle = 'Edit Slider';
require_once 'includes/header.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) redirect('sliders.php');

$stmt = $db->prepare("SELECT * FROM sliders WHERE id = ?");
$stmt->execute([$id]);
$s = $stmt->fetch();

if (!$s) redirect('sliders.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_en = trim($_POST['title_en'] ?? '');
    $title_km = trim($_POST['title_km'] ?? '');
    $subtitle_en = $_POST['subtitle_en'] ?? '';
    $subtitle_km = $_POST['subtitle_km'] ?? '';
    $btn_text_en = $_POST['btn_text_en'] ?: 'Learn More';
    $btn_text_km = $_POST['btn_text_km'] ?: 'ស្វែងយល់បន្ថែម';
    $link = $_POST['link'] ?? '';
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle Image Upload
    $image_path = $s['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload = uploadImage($_FILES['image'], 'sliders');
        if (isset($upload['success'])) {
            // Delete old image
            if ($image_path && file_exists(UPLOAD_DIR . $image_path)) {
                @unlink(UPLOAD_DIR . $image_path);
            }
            $image_path = $upload['path'];
        } else {
            $error = $upload['error'];
        }
    }

    if (!$error) {
        try {
            $stmt = $db->prepare("
                UPDATE sliders SET 
                    title_en = :t_en, title_km = :t_km, 
                    subtitle_en = :s_en, subtitle_km = :s_km, 
                    image = :img, link = :link, 
                    btn_text_en = :bt_en, btn_text_km = :bt_km, 
                    sort_order = :sort, is_active = :active
                WHERE id = :id
            ");
            
            $stmt->execute([
                't_en' => $title_en, 't_km' => $title_km, 
                's_en' => $subtitle_en, 's_km' => $subtitle_km,
                'img' => $image_path, 'link' => $link, 
                'bt_en' => $btn_text_en, 'bt_km' => $btn_text_km,
                'sort' => $sort_order, 'active' => $is_active,
                'id' => $id
            ]);
            
            $success = "Slider updated successfully!";
            // Refresh data
            $stmt = $db->prepare("SELECT * FROM sliders WHERE id = ?");
            $stmt->execute([$id]);
            $s = $stmt->fetch();
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="mb-4">
    <a href="sliders.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Sliders
    </a>
</div>

<form action="slider-edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Banner Text Overlays</h6>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= e($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= e($success) ?></div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title (EN)</label>
                            <input type="text" name="title_en" class="form-control" value="<?= e($s['title_en']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (KM)</label>
                            <input type="text" name="title_km" class="form-control" value="<?= e($s['title_km']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subtitle (EN)</label>
                            <textarea name="subtitle_en" rows="2" class="form-control"><?= e($s['subtitle_en']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subtitle (KM)</label>
                            <textarea name="subtitle_km" rows="2" class="form-control"><?= e($s['subtitle_km']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text (EN)</label>
                            <input type="text" name="btn_text_en" class="form-control" value="<?= e($s['btn_text_en']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text (KM)</label>
                            <input type="text" name="btn_text_km" class="form-control" value="<?= e($s['btn_text_km']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Target Link (URL)</label>
                            <input type="text" name="link" class="form-control" value="<?= e($s['link']) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Banner Image</h6>
                </div>
                <div class="card-body text-center">
                    <div id="imagePreview" class="mb-3 bg-light rounded d-flex align-items-center justify-content-center" style="height:180px;">
                        <?php if ($s['image']): ?>
                            <img src="<?= UPLOAD_URL . $s['image'] ?>" style="max-width:100%; max-height:100%; object-fit:contain;">
                        <?php else: ?>
                            <span class="text-muted small">No Image</span>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                    <p class="text-muted small mt-2">Upload new image to replace current one.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Display Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= e($s['sort_order']) ?>">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= $s['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isActive">Visible on homepage</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-save me-2"></i> Update Banner
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