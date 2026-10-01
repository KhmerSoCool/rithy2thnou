<?php
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

$db = getDB();

// Get Stats
try {
    $productCount = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $categoryCount = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $unreadContacts = $db->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();
    $postCount = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    
    // Get Recent Contacts
    $recentContacts = $db->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
    
    // Get Recent Products
    $recentProducts = $db->query("SELECT p.*, c.name_en as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
} catch (Exception $e) {
    $error = "Error loading dashboard data.";
}
?>

<div class="row g-4 mb-5">
    <!-- Stat Card: Products -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(201,168,76,0.1);color:var(--gold);">
                <i class="bi bi-gem"></i>
            </div>
            <div class="stat-value"><?= number_format($productCount ?? 0) ?></div>
            <div class="stat-label">Total Products</div>
        </div>
    </div>
    
    <!-- Stat Card: Categories -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(13,110,253,0.1);color:#0d6efd;">
                <i class="bi bi-grid-3x3-gap"></i>
            </div>
            <div class="stat-value"><?= number_format($categoryCount ?? 0) ?></div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
    
    <!-- Stat Card: Unread Messages -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(25,135,84,0.1);color:#198754;">
                <i class="bi bi-envelope-exclamation"></i>
            </div>
            <div class="stat-value"><?= number_format($unreadContacts ?? 0) ?></div>
            <div class="stat-label">Unread Messages</div>
        </div>
    </div>
    
    <!-- Stat Card: Posts -->
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(108,117,125,0.1);color:#6c757d;">
                <i class="bi bi-newspaper"></i>
            </div>
            <div class="stat-value"><?= number_format($postCount ?? 0) ?></div>
            <div class="stat-label">News Posts</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Inquiries -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold" style="font-size:1rem;">Recent Inquiries</h5>
                <a href="orders.php" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentContacts)): ?>
                                <?php foreach ($recentContacts as $contact): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= e($contact['name']) ?></div>
                                        <div class="text-muted small"><?= e($contact['phone']) ?></div>
                                    </td>
                                    <td><?= e($contact['subject'] ?: 'No Subject') ?></td>
                                    <td><?= timeAgo($contact['created_at']) ?></td>
                                    <td>
                                        <?php if ($contact['is_read']): ?>
                                            <span class="badge bg-light text-dark border">Read</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">New</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No recent inquiries found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Links / Recent Products -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="font-size:1rem;">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="product-add.php" class="btn btn-outline-stone text-start py-3 px-4 border">
                        <i class="bi bi-plus-circle-fill text-gold me-2"></i> Add New Product
                    </a>
                    <a href="news-add.php" class="btn btn-outline-stone text-start py-3 px-4 border">
                        <i class="bi bi-pencil-square text-primary me-2"></i> Write News Post
                    </a>
                    <a href="settings.php" class="btn btn-outline-stone text-start py-3 px-4 border">
                        <i class="bi bi-gear-fill text-secondary me-2"></i> Site Configuration
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="font-size:1rem;">Latest Products</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (!empty($recentProducts)): ?>
                        <?php foreach ($recentProducts as $p): ?>
                        <li class="list-group-item px-4 py-3 d-flex align-items-center justify-content-between border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-gem text-gold"></i>
                                </div>
                                <div>
                                    <div class="fw-bold small"><?= e($p['name_en']) ?></div>
                                    <div class="text-muted" style="font-size:0.75rem;"><?= e($p['cat_name']) ?></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold small text-gold"><?= $p['price'] > 0 ? '$'.number_format($p['price'], 2) : 'Contact' ?></div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center py-4 text-muted">No products found.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-outline-stone {
        color: var(--stone-dark);
        border-color: rgba(0,0,0,0.08);
        background: #fff;
    }
    .btn-outline-stone:hover {
        background: #f8f9fa;
        border-color: var(--gold);
        color: var(--stone-dark);
    }
    .text-gold {
        color: var(--gold);
    }
</style>

<?php require_once 'includes/footer.php'; ?>