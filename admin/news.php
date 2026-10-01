<?php
$pageTitle = 'Manage News & Blog';
require_once 'includes/header.php';

$db = getDB();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Get image path first to delete file
        $stmt = $db->prepare("SELECT featured_image FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetchColumn();
        
        if ($img && file_exists(UPLOAD_DIR . $img)) {
            @unlink(UPLOAD_DIR . $img);
        }
        
        $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Post deleted successfully.";
    } catch (Exception $e) {
        $error = "Error deleting post.";
    }
}

// Get All Posts
$posts = $db->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Blog Posts (<?= count($posts) ?>)</h5>
    <a href="news-add.php" class="btn btn-gold btn-action">
        <i class="bi bi-plus-lg me-1"></i> Write New Post
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
                        <th style="width: 100px;">Image</th>
                        <th>Title</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $p): ?>
                        <tr>
                            <td>
                                <?php if ($p['featured_image']): ?>
                                    <img src="<?= UPLOAD_URL . $p['featured_image'] ?>" alt="" style="width:70px;height:45px;object-fit:cover;border-radius:3px;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:70px;height:45px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= e($p['title_en']) ?></div>
                                <div class="text-muted small"><?= e($p['title_km']) ?></div>
                            </td>
                            <td><i class="bi bi-eye me-1"></i> <?= $p['views'] ?></td>
                            <td>
                                <?php if ($p['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Published</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="small"><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="news-edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="news.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this post permanently?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No news posts found. Start sharing updates with your audience.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>