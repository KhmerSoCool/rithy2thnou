<?php
$pageTitle = 'View Inquiry Details';
require_once 'includes/header.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) redirect('orders.php');

// Mark as read immediately when viewing
try {
    $db->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?")->execute([$id]);
} catch (Exception $e) {}

$stmt = $db->prepare("SELECT * FROM contacts WHERE id = ?");
$stmt->execute([$id]);
$inquiry = $stmt->fetch();

if (!$inquiry) redirect('orders.php');
?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="orders.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Inquiries
    </a>
    <div>
        <a href="orders.php?delete=<?= $id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this inquiry?')">
            <i class="bi bi-trash me-1"></i> Delete
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Message Content</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1"><?= e($inquiry['subject'] ?: '(No Subject)') ?></h5>
                    <div class="text-muted small">Received on <?= date('F d, Y \a\t h:i A', strtotime($inquiry['created_at'])) ?></div>
                </div>
                
                <div class="p-4 bg-light rounded" style="white-space: pre-line; line-height: 1.8; color: var(--stone-dark);">
                    <?= e($inquiry['message']) ?>
                </div>
                
                <div class="mt-4 pt-4 border-top">
                    <a href="mailto:<?= e($inquiry['email']) ?>?subject=Re: <?= e($inquiry['subject']) ?>" class="btn btn-gold px-4">
                        <i class="bi bi-reply-fill me-2"></i> Reply via Email
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Sender Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-gold-light rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 50px; height: 50px; font-size: 1.2rem; background: var(--gold);">
                        <?= mb_substr(e($inquiry['name']), 0, 1) ?>
                    </div>
                    <div class="ms-3">
                        <div class="fw-bold"><?= e($inquiry['name']) ?></div>
                        <div class="text-muted small">Customer</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-1">Email Address</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope text-gold me-2"></i>
                        <a href="mailto:<?= e($inquiry['email']) ?>" class="text-decoration-none text-dark"><?= e($inquiry['email'] ?: 'N/A') ?></a>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-1">Phone Number</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-telephone text-gold me-2"></i>
                        <a href="tel:<?= e($inquiry['phone']) ?>" class="text-decoration-none text-dark"><?= e($inquiry['phone'] ?: 'N/A') ?></a>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i> This inquiry was sent via the website contact form.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Inquiry Status</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($inquiry['is_read']): ?>
                        <a href="orders.php?toggle_read=<?= $id ?>&status=0" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-envelope me-1"></i> Mark as New/Unread
                        </a>
                    <?php else: ?>
                        <button class="btn btn-success btn-sm" disabled>
                            <i class="bi bi-check-lg me-1"></i> Marked as Read
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 0.05em; }
</style>

<?php require_once 'includes/footer.php'; ?>