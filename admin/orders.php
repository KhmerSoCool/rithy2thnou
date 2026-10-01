<?php
$pageTitle = 'Orders & Inquiries';
require_once 'includes/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Inquiry deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting inquiry.";
    }
}

// Handle Mark as Read/Unread
if (isset($_GET['toggle_read'])) {
    $id = (int)$_GET['toggle_read'];
    $status = (int)$_GET['status'];
    try {
        $stmt = $db->prepare("UPDATE contacts SET is_read = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    } catch (Exception $e) {}
}

// Get All Inquiries
$inquiries = $db->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Customer Inquiries (<?= count($inquiries) ?>)</h5>
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
                        <th style="width: 50px;">Status</th>
                        <th>Client Info</th>
                        <th>Subject</th>
                        <th>Message Preview</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inquiries)): ?>
                        <?php foreach ($inquiries as $i): ?>
                        <tr class="<?= !$i['is_read'] ? 'table-light fw-bold' : '' ?>">
                            <td>
                                <?php if (!$i['is_read']): ?>
                                    <span class="badge bg-warning text-dark">New</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">Read</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?= e($i['name']) ?></div>
                                <div class="text-muted small fw-normal"><?= e($i['phone']) ?></div>
                                <div class="text-muted small fw-normal"><?= e($i['email']) ?></div>
                            </td>
                            <td><?= e($i['subject'] ?: '(No Subject)') ?></td>
                            <td class="text-muted small fw-normal">
                                <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?= e($i['message']) ?>
                                </div>
                            </td>
                            <td class="small fw-normal"><?= timeAgo($i['created_at']) ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="order-view.php?id=<?= $i['id'] ?>" class="btn btn-sm btn-outline-secondary" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($i['is_read']): ?>
                                        <a href="orders.php?toggle_read=<?= $i['id'] ?>&status=0" class="btn btn-sm btn-outline-secondary" title="Mark as New">
                                            <i class="bi bi-envelope"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="orders.php?toggle_read=<?= $i['id'] ?>&status=1" class="btn btn-sm btn-outline-secondary" title="Mark as Read">
                                            <i class="bi bi-envelope-open"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="orders.php?delete=<?= $i['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this inquiry?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No customer inquiries found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>