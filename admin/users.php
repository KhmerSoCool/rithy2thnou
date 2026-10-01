<?php
$pageTitle = 'Manage Administrators';
require_once 'includes/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Prevent self-deletion
    if ($id === (int)$_SESSION['admin_id']) {
        $error = "You cannot delete your own account.";
    } else {
        try {
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Administrator deleted successfully.";
        } catch (Exception $e) {
            $error = "Error deleting user.";
        }
    }
}

// Get All Users
$users = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Administrators (<?= count($users) ?>)</h5>
    <a href="user-add.php" class="btn btn-gold btn-action">
        <i class="bi bi-person-plus me-1"></i> Add Administrator
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
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= e($user['full_name']) ?></div>
                                <?php if ((int)$user['id'] === (int)$_SESSION['admin_id']): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.65rem;">YOU</span>
                                <?php endif; ?>
                            </td>
                            <td><code class="text-dark"><?= e($user['username']) ?></code></td>
                            <td><?= e($user['email']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.7rem;"><?= e($user['role']) ?></span>
                            </td>
                            <td class="small"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="user-edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ((int)$user['id'] !== (int)$_SESSION['admin_id']): ?>
                                    <a href="users.php?delete=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to remove this administrator?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No administrators found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>