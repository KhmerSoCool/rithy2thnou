<?php
$pageTitle = 'Site Settings';
require_once 'includes/header.php';

$db = getDB();

$error = '';
$success = '';

// Fetch all current settings into an associative array
$stmt = $db->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // List of text settings to update
    $text_keys = [
        'site_name_en', 'site_name_km', 'site_tagline_en', 'site_tagline_km',
        'site_email', 'site_phone', 'site_phone2', 'site_address_en', 'site_address_km',
        'site_facebook', 'about_short_en', 'about_short_km', 'default_lang'
    ];

    try {
        $db->beginTransaction();

        $upsert_stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :val) ON DUPLICATE KEY UPDATE setting_value = :val2");

        foreach ($text_keys as $key) {
            $val = $_POST[$key] ?? '';
            $upsert_stmt->execute(['key' => $key, 'val' => $val, 'val2' => $val]);
            $settings[$key] = $val; // Update local array for display
        }

        // Handle Logo Upload
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === 0) {
            $upload = uploadImage($_FILES['site_logo'], 'site');
            if (isset($upload['success'])) {
                // Delete old logo if exists
                if (!empty($settings['site_logo']) && file_exists(UPLOAD_DIR . $settings['site_logo'])) {
                    @unlink(UPLOAD_DIR . $settings['site_logo']);
                }
                $new_logo = $upload['path'];
                $upsert_stmt->execute(['key' => 'site_logo', 'val' => $new_logo, 'val2' => $new_logo]);
                $settings['site_logo'] = $new_logo;
            } else {
                $error = "Logo upload error: " . $upload['error'];
            }
        }

        // Handle Favicon Upload
        if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === 0) {
            $upload = uploadImage($_FILES['site_favicon'], 'site');
            if (isset($upload['success'])) {
                // Delete old favicon if exists
                if (!empty($settings['site_favicon']) && file_exists(UPLOAD_DIR . $settings['site_favicon'])) {
                    @unlink(UPLOAD_DIR . $settings['site_favicon']);
                }
                $new_favicon = $upload['path'];
                $upsert_stmt->execute(['key' => 'site_favicon', 'val' => $new_favicon, 'val2' => $new_favicon]);
                $settings['site_favicon'] = $new_favicon;
            } else {
                $error = "Favicon upload error: " . $upload['error'];
            }
        }

        if (!$error) {
            $db->commit();
            $success = "Settings updated successfully!";
        } else {
            $db->rollBack();
        }
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<form action="settings.php" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- General Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-gold"></i> General Information</h6>
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
                            <label class="form-label">Site Name (EN)</label>
                            <input type="text" name="site_name_en" class="form-control" value="<?= e($settings['site_name_en'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site Name (KM)</label>
                            <input type="text" name="site_name_km" class="form-control" value="<?= e($settings['site_name_km'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site Tagline (EN)</label>
                            <input type="text" name="site_tagline_en" class="form-control" value="<?= e($settings['site_tagline_en'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site Tagline (KM)</label>
                            <input type="text" name="site_tagline_km" class="form-control" value="<?= e($settings['site_tagline_km'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Default Language</label>
                            <select name="default_lang" class="form-select">
                                <option value="en" <?= ($settings['default_lang'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                                <option value="km" <?= ($settings['default_lang'] ?? 'en') === 'km' ? 'selected' : '' ?>>Khmer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">About Summary (EN)</label>
                            <textarea name="about_short_en" rows="3" class="form-control"><?= e($settings['about_short_en'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">About Summary (KM)</label>
                            <textarea name="about_short_km" rows="3" class="form-control"><?= e($settings['about_short_km'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Social -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-telephone me-2 text-gold"></i> Contact & Social Media</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Site Email</label>
                            <input type="email" name="site_email" class="form-control" value="<?= e($settings['site_email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Facebook Page URL</label>
                            <input type="text" name="site_facebook" class="form-control" value="<?= e($settings['site_facebook'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone 1</label>
                            <input type="text" name="site_phone" class="form-control" value="<?= e($settings['site_phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone 2 (Optional)</label>
                            <input type="text" name="site_phone2" class="form-control" value="<?= e($settings['site_phone2'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address (EN)</label>
                            <textarea name="site_address_en" rows="2" class="form-control"><?= e($settings['site_address_en'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address (KM)</label>
                            <textarea name="site_address_km" rows="2" class="form-control"><?= e($settings['site_address_km'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Logo Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-image me-2 text-gold"></i> Site Assets</h6>
                </div>
                <div class="card-body">
                    <!-- Logo -->
                    <div class="mb-4 text-center">
                        <label class="form-label d-block text-start">Site Logo</label>
                        <div id="logoPreview" class="mb-3 bg-light rounded d-flex align-items-center justify-content-center" style="height:120px; border:1px solid #eee;">
                            <?php if (!empty($settings['site_logo'])): ?>
                                <img src="<?= UPLOAD_URL . $settings['site_logo'] ?>" style="max-width:100%; max-height:100%; object-fit:contain;">
                            <?php else: ?>
                                <span class="text-muted small">No Logo</span>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="site_logo" id="logoInput" class="form-control" accept="image/*">
                        <small class="text-muted">Max 5MB. PNG/WebP recommended.</small>
                    </div>

                    <hr>

                    <!-- Favicon -->
                    <div class="mb-0 text-center">
                        <label class="form-label d-block text-start">Favicon</label>
                        <div id="faviconPreview" class="mb-3 bg-light rounded d-flex align-items-center justify-content-center mx-auto" style="width:64px; height:64px; border:1px solid #eee;">
                            <?php if (!empty($settings['site_favicon'])): ?>
                                <img src="<?= UPLOAD_URL . $settings['site_favicon'] ?>" style="width:32px; height:32px; object-fit:contain;">
                            <?php else: ?>
                                <i class="bi bi-star text-muted"></i>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="site_favicon" id="faviconInput" class="form-control" accept="image/*">
                        <small class="text-muted">Recommended: 32x32px .ico or .png</small>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gold btn-lg py-3 fw-bold shadow-sm">
                    <i class="bi bi-check2-circle me-2"></i> Save All Settings
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    // Preview for Logo
    document.getElementById('logoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('logoPreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `<img src="${event.target.result}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview for Favicon
    document.getElementById('faviconInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('faviconPreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `<img src="${event.target.result}" style="width:32px; height:32px; object-fit:contain;">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>