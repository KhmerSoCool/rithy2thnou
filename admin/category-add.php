<?php
$pageTitle = 'Add New Category';
require_once 'includes/header.php';

$db = getDB();
$all_categories = $db->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_en = trim($_POST['name_en'] ?? '');
    $name_km = trim($_POST['name_km'] ?? '');
    $parent_id = $_POST['parent_id'] ?: null;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $description_en = $_POST['description_en'] ?? '';
    $description_km = $_POST['description_km'] ?? '';
    
    $slug_str = slug($name_en);

    if (empty($name_en)) {
        $error = 'Category name (English) is required.';
    } else {
        // Check if slug exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
        $stmt->execute([$slug_str]);
        if ($stmt->fetchColumn() > 0) {
            $slug_str .= '-' . time();
        }

        try {
            $stmt = $db->prepare("
                INSERT INTO categories (
                    name_en, name_km, slug, description_en, description_km, parent_id, sort_order
                ) VALUES (
                    :n_en, :n_km, :slug, :desc_en, :desc_km, :parent, :sort
                )
            ");
            
            $stmt->execute([
                'n_en' => $name_en, 'n_km' => $name_km, 'slug' => $slug_str,
                'desc_en' => $description_en, 'desc_km' => $description_km,
                'parent' => $parent_id, 'sort' => $sort_order
            ]);
            
            $success = "Category added successfully!";
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="mb-4">
    <a href="categories.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Categories
    </a>
</div>

<form action="category-add.php" method="POST">
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
                            <label class="form-label">Category Name (EN) <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" class="form-control" required placeholder="e.g. Granite">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category Name (KM)</label>
                            <input type="text" name="name_km" class="form-control" placeholder="ឈ្មោះភាសាខ្មែរ">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description (EN)</label>
                            <textarea name="description_en" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description (KM)</label>
                            <textarea name="description_km" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Hierarchy & Sorting</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="parent_id" class="form-select">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= e($cat['name_en']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                        <small class="text-muted">Lower numbers appear first.</small>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-save me-2"></i> Save Category
                </button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>