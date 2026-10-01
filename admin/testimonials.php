<?php
$pageTitle = 'Manage Testimonials';
require_once 'includes/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Testimonial deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting testimonial.";
    }
}

// Get All Testimonials
$testimonials = $db->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Client Reviews (<?= count($testimonials) ?>)</h5>
    <a href="testimonial-add.php" class="btn btn-gold btn-action">
        <i class="bi bi-plus-lg me-1"></i> Add Testimonial
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
                        <th>Client</th>
                        <th>Rating</th>
                        <th>Message Preview</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($testimonials)): ?>
                        <?php foreach ($testimonials as $t): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= e($t['client_name_en']) ?></div>
                                <div class="text-muted small"><?= e($t['company_en']) ?></div>
                                <div class="text-muted small mt-1" style="opacity: 0.7;"><?= e($t['client_name_km']) ?></div>
                            </td>
                            <td>
                                <div class="text-warning">
                                    <?= str_repeat('<i class="bi bi-star-fill"></i>', (int)$t['rating']) ?>
                                </div>
                            </td>
                            <td class="text-muted small">
                                <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    "<?= e($t['message_en']) ?>"
                                </div>
                            </td>
                            <td>
                                <?php if ($t['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Visible</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td class="small"><?= date('M d, Y', strtotime($t['created_at'])) ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="testimonial-edit.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="testimonials.php?delete=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this testimonial?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No testimonials found. Add some client reviews to show social proof.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>