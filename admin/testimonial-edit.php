<?php
$pageTitle = 'Edit Testimonial';
require_once 'includes/header.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) redirect('testimonials.php');

$stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ?");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) redirect('testimonials.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_en = trim($_POST['client_name_en'] ?? '');
    $name_km = trim($_POST['client_name_km'] ?? '');
    $company_en = $_POST['company_en'] ?? '';
    $company_km = $_POST['company_km'] ?? '';
    $message_en = $_POST['message_en'] ?? '';
    $message_km = $_POST['message_km'] ?? '';
    $rating = (int)($_POST['rating'] ?? 5);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (empty($name_en) || empty($message_en)) {
        $error = 'Client name and message (English) are required.';
    } else {
        try {
            $stmt = $db->prepare("
                UPDATE testimonials SET 
                    client_name_en = :n_en, client_name_km = :n_km, 
                    company_en = :c_en, company_km = :c_km, 
                    message_en = :m_en, message_km = :m_km, 
                    rating = :rating, is_active = :active
                WHERE id = :id
            ");
            
            $stmt->execute([
                'n_en' => $name_en, 'n_km' => $name_km, 
                'c_en' => $company_en, 'c_km' => $company_km,
                'm_en' => $message_en, 'm_km' => $message_km,
                'rating' => $rating, 'active' => $is_active,
                'id' => $id
            ]);
            
            $success = "Testimonial updated successfully!";
            // Refresh
            $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ?");
            $stmt->execute([$id]);
            $t = $stmt->fetch();
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="mb-4">
    <a href="testimonials.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Testimonials
    </a>
</div>

<form action="testimonial-edit.php?id=<?= $id ?>" method="POST">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">English Content</h6>
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
                            <label class="form-label">Client Name (EN) <span class="text-danger">*</span></label>
                            <input type="text" name="client_name_en" class="form-control" required value="<?= e($t['client_name_en']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company/Title (EN)</label>
                            <input type="text" name="company_en" class="form-control" value="<?= e($t['company_en']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message (EN) <span class="text-danger">*</span></label>
                            <textarea name="message_en" rows="4" class="form-control" required><?= e($t['message_en']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Khmer Content</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Client Name (KM)</label>
                            <input type="text" name="client_name_km" class="form-control" value="<?= e($t['client_name_km']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company/Title (KM)</label>
                            <input type="text" name="company_km" class="form-control" value="<?= e($t['company_km']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message (KM)</label>
                            <textarea name="message_km" rows="4" class="form-control"><?= e($t['message_km']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Rating & Visibility</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Star Rating</label>
                        <select name="rating" class="form-select">
                            <option value="5" <?= $t['rating'] == 5 ? 'selected' : '' ?>>5 Stars</option>
                            <option value="4" <?= $t['rating'] == 4 ? 'selected' : '' ?>>4 Stars</option>
                            <option value="3" <?= $t['rating'] == 3 ? 'selected' : '' ?>>3 Stars</option>
                            <option value="2" <?= $t['rating'] == 2 ? 'selected' : '' ?>>2 Stars</option>
                            <option value="1" <?= $t['rating'] == 1 ? 'selected' : '' ?>>1 Star</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= $t['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isActive">Visible on site</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-save me-2"></i> Update Testimonial
                </button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>