<?php
$pageTitle = 'Manage Homepage Sliders';
require_once 'includes/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Get image path first
        $stmt = $db->prepare("SELECT image FROM sliders WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetchColumn();
        
        if ($img && file_exists(UPLOAD_DIR . $img)) {
            @unlink(UPLOAD_DIR . $img);
        }
        
        $stmt = $db->prepare("DELETE FROM sliders WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Slider deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting slider.";
    }
}

// Get All Sliders
$sliders = $db->query("SELECT * FROM sliders ORDER BY sort_order ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Homepage Banners (<?= count($sliders) ?>)</h5>
    <a href="slider-add.php" class="btn btn-gold btn-action">
        <i class="bi bi-plus-lg me-1"></i> Add New Slider
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
                        <th style="width: 150px;">Banner</th>
                        <th>Text Overlay</th>
                        <th>Button Text</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sliders)): ?>
                        <?php foreach ($sliders as $s): ?>
                        <tr>
                            <td>
                                <?php if ($s['image']): ?>
                                    <img src="<?= UPLOAD_URL . $s['image'] ?>" alt="" style="width:120px;height:60px;object-fit:cover;border-radius:4px;border:1px solid rgba(0,0,0,0.05);">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:120px;height:60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= e($s['title_en']) ?></div>
                                <div class="text-muted small"><?= e($s['subtitle_en']) ?></div>
                                <div class="text-muted small mt-1" style="opacity: 0.7;"><?= e($s['title_km']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= e($s['btn_text_en']) ?></span>
                            </td>
                            <td><?= $s['sort_order'] ?></td>
                            <td>
                                <?php if ($s['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="slider-edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="sliders.php?delete=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this banner?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No homepage banners found. Add one to enhance your site's look.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>