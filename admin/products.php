<?php
$pageTitle = 'Manage Products';
require_once 'includes/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Get image path first to delete file
        $stmt = $db->prepare("SELECT featured_image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetchColumn();
        
        if ($img && file_exists(UPLOAD_DIR . $img)) {
            @unlink(UPLOAD_DIR . $img);
        }
        
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Product deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting product.";
    }
}

// Get Products with Category Name
$products = $db->query("
    SELECT p.*, c.name_en as cat_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">All Products (<?= count($products) ?>)</h5>
    <a href="product-add.php" class="btn btn-gold btn-action">
        <i class="bi bi-plus-lg me-1"></i> Add New Product
    </a>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= e($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Origin</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php if ($p['featured_image']): ?>
                                    <img src="<?= UPLOAD_URL . $p['featured_image'] ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= e($p['name_en']) ?></div>
                                <div class="text-muted small"><?= e($p['name_km']) ?></div>
                            </td>
                            <td><?= e($p['cat_name'] ?: 'Uncategorized') ?></td>
                            <td>
                                <div class="fw-bold text-gold"><?= $p['price'] > 0 ? '$'.number_format($p['price'], 2) : 'Contact' ?></div>
                                <div class="text-muted small"><?= e($p['price_unit_en']) ?></div>
                            </td>
                            <td><?= e($p['origin_en']) ?></td>
                            <td>
                                <?php if ($p['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactive</span>
                                <?php endif; ?>
                                <?php if ($p['is_featured']): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-1">Featured</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="product-edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="products.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No products found. Add your first product to get started.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>