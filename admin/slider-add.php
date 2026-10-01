<?php
$pageTitle = 'Add New Slider';
require_once 'includes/header.php';

$db = getDB();

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
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload = uploadImage($_FILES['image'], 'sliders');
        if (isset($upload['success'])) {
            $image_path = $upload['path'];
        } else {
            $error = $upload['error'];
        }
    } else {
        $error = 'Please select a banner image.';
    }

    if (!$error) {
        try {
            $stmt = $db->prepare("
                INSERT INTO sliders (
                    title_en, title_km, subtitle_en, subtitle_km, 
                    image, link, btn_text_en, btn_text_km, 
                    sort_order, is_active
                ) VALUES (
                    :t_en, :t_km, :s_en, :s_km, 
                    :img, :link, :bt_en, :bt_km, 
                    :sort, :active
                )
            ");
            
            $stmt->execute([
                't_en' => $title_en, 't_km' => $title_km, 
                's_en' => $subtitle_en, 's_km' => $subtitle_km,
                'img' => $image_path, 'link' => $link, 
                'bt_en' => $btn_text_en, 'bt_km' => $btn_text_km,
                'sort' => $sort_order, 'active' => $is_active
            ]);
            
            $success = "Slider added successfully!";
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

<form action="slider-add.php" method="POST" enctype="multipart/form-data">
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
                            <input type="text" name="title_en" class="form-control" placeholder="Main headline">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (KM)</label>
                            <input type="text" name="title_km" class="form-control" placeholder="ចំណងជើងធំ">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subtitle (EN)</label>
                            <textarea name="subtitle_en" rows="2" class="form-control" placeholder="Smaller descriptive text"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subtitle (KM)</label>
                            <textarea name="subtitle_km" rows="2" class="form-control" placeholder="អត្ថបទបន្ថែម"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text (EN)</label>
                            <input type="text" name="btn_text_en" class="form-control" value="Learn More">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text (KM)</label>
                            <input type="text" name="btn_text_km" class="form-control" value="ស្វែងយល់បន្ថែម">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Target Link (URL)</label>
                            <input type="text" name="link" class="form-control" placeholder="e.g. products.php?cat=granite">
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
                    <div id="imagePreview" class="mb-3 bg-light rounded d-flex align-items-center justify-content-center" style="height:180px; border:2px dashed #ddd;">
                        <span class="text-muted small">Select wide banner image</span>
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" required>
                    <p class="text-muted small mt-2">Recommended: 1920x800px or similar wide ratio. High quality but optimized.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Display Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Visible on homepage</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-save me-2"></i> Save Banner
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