<?php
$pageTitle = 'Add New Product';
require_once 'includes/header.php';

$db = getDB();
$categories = $db->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_en = trim($_POST['name_en'] ?? '');
    $name_km = trim($_POST['name_km'] ?? '');
    $category_id = $_POST['category_id'] ?: null;
    $price = $_POST['price'] ?: 0;
    $price_unit_en = $_POST['price_unit_en'] ?: 'per sqm';
    $price_unit_km = $_POST['price_unit_km'] ?: 'ក្នុងមួយម';
    $thickness = $_POST['thickness'] ?? '';
    $size = $_POST['size'] ?? '';
    $finish_en = $_POST['finish_en'] ?? '';
    $finish_km = $_POST['finish_km'] ?? '';
    $origin_en = $_POST['origin_en'] ?? '';
    $origin_km = $_POST['origin_km'] ?? '';
    $short_desc_en = $_POST['short_desc_en'] ?? '';
    $short_desc_km = $_POST['short_desc_km'] ?? '';
    $description_en = $_POST['description_en'] ?? '';
    $description_km = $_POST['description_km'] ?? '';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $slug_str = slug($name_en);

    if (empty($name_en)) {
        $error = 'Product name (English) is required.';
    } else {
        // Handle Image Upload
        $featured_image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload = uploadImage($_FILES['image'], 'products');
            if (isset($upload['success'])) {
                $featured_image = $upload['path'];
            } else {
                $error = $upload['error'];
            }
        }

        if (!$error) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO products (
                        category_id, name_en, name_km, slug, 
                        description_en, description_km, short_desc_en, short_desc_km,
                        price, price_unit_en, price_unit_km, thickness, size,
                        finish_type_en, finish_type_km, origin_en, origin_km,
                        featured_image, is_featured, is_active
                    ) VALUES (
                        :cat, :n_en, :n_km, :slug,
                        :desc_en, :desc_km, :s_desc_en, :s_desc_km,
                        :price, :p_unit_en, :p_unit_km, :thick, :size,
                        :f_en, :f_km, :o_en, :o_km,
                        :img, :feat, :active
                    )
                ");
                
                $stmt->execute([
                    'cat' => $category_id, 'n_en' => $name_en, 'n_km' => $name_km, 'slug' => $slug_str,
                    'desc_en' => $description_en, 'desc_km' => $description_km, 
                    's_desc_en' => $short_desc_en, 's_desc_km' => $short_desc_km,
                    'price' => $price, 'p_unit_en' => $price_unit_en, 'p_unit_km' => $price_unit_km,
                    'thick' => $thickness, 'size' => $size,
                    'f_en' => $finish_en, 'f_km' => $finish_km, 'o_en' => $origin_en, 'o_km' => $origin_km,
                    'img' => $featured_image, 'feat' => $is_featured, 'active' => $is_active
                ]);
                
                $success = "Product added successfully!";
                // Optional: Clear POST data or redirect
            } catch (Exception $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="mb-4">
    <a href="products.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Products
    </a>
</div>

<form action="product-add.php" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Basic Information</h6>
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
                            <label class="form-label">Product Name (EN) <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" class="form-control" required placeholder="e.g. Black Galaxy Granite">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product Name (KM)</label>
                            <input type="text" name="name_km" class="form-control" placeholder="ឈ្មោះភាសាខ្មែរ">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= e($cat['name_en']) ?> (<?= e($cat['name_km']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price Unit (EN)</label>
                            <input type="text" name="price_unit_en" class="form-control" value="per sqm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price Unit (KM)</label>
                            <input type="text" name="price_unit_km" class="form-control" value="ក្នុងមួយម">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Specifications & Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Thickness</label>
                            <input type="text" name="thickness" class="form-control" placeholder="e.g. 18mm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Size / Dimension</label>
                            <input type="text" name="size" class="form-control" placeholder="e.g. 60x60cm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Finish (EN)</label>
                            <input type="text" name="finish_en" class="form-control" placeholder="e.g. Polished">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Finish (KM)</label>
                            <input type="text" name="finish_km" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Origin (EN)</label>
                            <input type="text" name="origin_en" class="form-control" placeholder="e.g. India">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Origin (KM)</label>
                            <input type="text" name="origin_km" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short Description (EN)</label>
                            <textarea name="short_desc_en" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description (EN)</label>
                            <textarea name="description_en" rows="5" class="form-control"></textarea>
                        </div>
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
                        <span class="text-muted small">Preview Image</span>
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                    <p class="text-muted small mt-2">Recommended: 800x800px or 4:3 ratio. Max 5MB.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Visibility & Status</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Active / Visible on site</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured">
                        <label class="form-check-label" for="isFeatured">Featured Product</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-save me-2"></i> Save Product
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