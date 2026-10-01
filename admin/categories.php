<?php
$pageTitle = 'Manage Categories';
require_once 'includes/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Check if category has products
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        $hasProducts = $stmt->fetchColumn();

        if ($hasProducts) {
            $error = "Cannot delete category: It contains " . $hasProducts . " products. Please move or delete them first.";
        } else {
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Category deleted successfully.";
        }
    } catch (Exception $e) {
        $error = "Error deleting category.";
    }
}

// Get All Categories with parent info
$categories = $db->query("
    SELECT c1.*, c2.name_en as parent_name 
    FROM categories c1 
    LEFT JOIN categories c2 ON c1.parent_id = c2.id 
    ORDER BY c1.sort_order ASC
")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Categories (<?= count($categories) ?>)</h5>
    <a href="category-add.php" class="btn btn-gold btn-action">
        <i class="bi bi-plus-lg me-1"></i> Add New Category
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
                        <th style="width: 50px;">#</th>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Order</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td>
                                <div class="fw-bold"><?= e($c['name_en']) ?></div>
                                <div class="text-muted small"><?= e($c['name_km']) ?></div>
                            </td>
                            <td><code class="small"><?= e($c['slug']) ?></code></td>
                            <td><?= $c['parent_name'] ? e($c['parent_name']) : '<span class="text-muted small">None</span>' ?></td>
                            <td><?= $c['sort_order'] ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="category-edit.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="categories.php?delete=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No categories found. Add your first category to get started.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>