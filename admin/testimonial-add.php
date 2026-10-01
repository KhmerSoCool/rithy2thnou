<?php
$pageTitle = 'Add Testimonial';
require_once 'includes/header.php';

$db = getDB();

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
                INSERT INTO testimonials (
                    client_name_en, client_name_km, company_en, company_km, 
                    message_en, message_km, rating, is_active
                ) VALUES (
                    :n_en, :n_km, :c_en, :c_km, 
                    :m_en, :m_km, :rating, :active
                )
            ");
            
            $stmt->execute([
                'n_en' => $name_en, 'n_km' => $name_km, 
                'c_en' => $company_en, 'c_km' => $company_km,
                'm_en' => $message_en, 'm_km' => $message_km,
                'rating' => $rating, 'active' => $is_active
            ]);
            
            $success = "Testimonial added successfully!";
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

<form action="testimonial-add.php" method="POST">
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
                            <input type="text" name="client_name_en" class="form-control" required placeholder="e.g. Sopheak Chan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company/Title (EN)</label>
                            <input type="text" name="company_en" class="form-control" placeholder="e.g. CEO of Chan Construction">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message (EN) <span class="text-danger">*</span></label>
                            <textarea name="message_en" rows="4" class="form-control" required placeholder="What did the client say?"></textarea>
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
                            <input type="text" name="client_name_km" class="form-control" placeholder="ឈ្មោះអតិថិជន">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company/Title (KM)</label>
                            <input type="text" name="company_km" class="form-control" placeholder="ក្រុមហ៊ុន/តួនាទី">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message (KM)</label>
                            <textarea name="message_km" rows="4" class="form-control" placeholder="មតិយោបល់អតិថិជន"></textarea>
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
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Visible on site</label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold">
                    <i class="bi bi-save me-2"></i> Save Testimonial
                </button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>