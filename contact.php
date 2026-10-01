<?php
require_once 'includes/config.php';
$db = getDB();

// Handle AJAX form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$message) {
        echo json_encode(['success' => false, 'message' => t('Name and message are required.', 'សូមបញ្ចូលឈ្មោះ និងសាររបស់អ្នក។')]);
        exit;
    }

    try {
        $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (:name,:email,:phone,:subject,:message)");
        $ok = $stmt->execute([
            'name' => $name, 'email' => $email, 'phone' => $phone,
            'subject' => $subject, 'message' => $message
        ]);
        
        if ($ok) {
            echo json_encode(['success' => true, 'message' => t('Your message has been sent! We will contact you soon.', 'សាររបស់អ្នកត្រូវបានផ្ញើ! យើងនឹងទាក់ទងអ្នកក្នុងពេលឆាប់ៗនេះ។')]);
        } else {
            echo json_encode(['success' => false, 'message' => t('Failed to send. Please try again.', 'ការផ្ញើបានបរាជ័យ។ សូមព្យាយាមម្តងទៀត។')]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

$productName = $_GET['product'] ?? '';
$pageTitle = $lang['contact_title'] ?? 'Contact Us';
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<div class="page-hero">
    <div class="container">
        <h1 class="page-hero-title"><?= $lang['contact_title'] ?></h1>
        <p class="page-hero-breadcrumb mt-2">
            <a href="index.php"><?= $lang['nav_home'] ?></a> / <?= $lang['nav_contact'] ?>
        </p>
    </div>
</div>

<section class="section-pad">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-4" data-aos="fade-right">
                <div class="contact-info-card">
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;margin-bottom:8px;font-weight:700;"><?= t('Get In Touch', 'ទាក់ទងមកយើង') ?></h3>
                    <div class="divider-gold mb-4"></div>
                    <p style="color:rgba(255,255,255,0.6);font-size:0.95rem;line-height:1.8;margin-bottom:32px;">
                        <?= t('We\'re here to help with all your granite and natural stone needs. Contact us today for a free consultation.', 'យើងនៅទីនេះដើម្បីជួយរាល់តម្រូវការថ្មក្រានីត និងថ្មធម្មជាតិរបស់អ្នក។ ទាក់ទងមកយើងថ្ងៃនេះ ដើម្បីទទួលបានការពិគ្រោះយោបល់ដោយឥតគិតថ្លៃ។') ?>
                    </p>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="contact-info-text">
                            <h6><?= $lang['address'] ?></h6>
                            <p><?= e(t(getSetting('site_address_en'), getSetting('site_address_km'))) ?></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div class="contact-info-text">
                            <h6><?= $lang['phone'] ?></h6>
                            <p>
                                <a href="tel:<?= e(getSetting('site_phone')) ?>"><?= e(getSetting('site_phone')) ?></a><br>
                                <?php if(getSetting('site_phone2')): ?>
                                <a href="tel:<?= e(getSetting('site_phone2')) ?>"><?= e(getSetting('site_phone2')) ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div class="contact-info-text">
                            <h6><?= $lang['email'] ?></h6>
                            <p><a href="mailto:<?= e(getSetting('site_email')) ?>"><?= e(getSetting('site_email')) ?></a></p>
                        </div>
                    </div>

                    <?php if(getSetting('site_facebook')): ?>
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-facebook"></i></div>
                        <div class="contact-info-text">
                            <h6>Facebook</h6>
                            <p><a href="<?= e(getSetting('site_facebook')) ?>" target="_blank"><?= t('Visit our page', 'ចូលទៅកាន់ទំព័ររបស់យើង') ?></a></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Business Hours -->
                    <div class="mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.08);">
                        <h6 style="color:var(--gold);font-size:0.85rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:15px;font-weight:700;"><?= t('Business Hours', 'ម៉ោងធ្វើការ') ?></h6>
                        <div style="font-size:0.9rem;color:rgba(255,255,255,0.5);line-height:2.2;">
                            <div class="d-flex justify-content-between">
                                <span><?= t('Mon – Sat:', 'ចន្ទ - សៅរ៍:') ?></span>
                                <span style="color:rgba(255,255,255,0.85);">7:00 AM – 6:00 PM</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><?= t('Sunday:', 'អាទិត្យ:') ?></span>
                                <span style="color:rgba(255,255,255,0.85);">8:00 AM – 12:00 PM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8" data-aos="fade-left">
                <div class="contact-form-card">
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;margin-bottom:8px;font-weight:700;"><?= t('Send Us a Message', 'ផ្ញើសារមកយើង') ?></h3>
                    <div class="divider-gold mb-4"></div>

                    <form id="contactForm" action="contact.php" method="POST">
                        <input type="hidden" name="ajax" value="1">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label"><?= $lang['your_name'] ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="<?= e($lang['your_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= $lang['your_phone'] ?></label>
                                <input type="tel" name="phone" class="form-control" placeholder="+855 XX XXX XXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= $lang['your_email'] ?></label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= $lang['subject'] ?></label>
                                <input type="text" name="subject" class="form-control" value="<?= e($productName ? t('Inquiry: ', 'ការសាកសួរ: ') . $productName : '') ?>" placeholder="<?= t('Product Inquiry', 'ការសាកសួរពីផលិតផល') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label"><?= $lang['message'] ?> <span class="text-danger">*</span></label>
                                <textarea name="message" rows="6" class="form-control" required placeholder="<?= t('Tell us about your project...', 'ប្រាប់យើងពីគម្រោងរបស់អ្នក...') ?>"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold btn-lg px-5 py-3 fw-bold">
                                    <i class="bi bi-send-fill me-2"></i><?= $lang['send_message'] ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row mt-5 pt-4">
            <div class="col-12" data-aos="fade-up">
                <div class="map-container" style="background:var(--stone-cream); border:1px solid var(--border); border-radius:3px; height:450px; position:relative; overflow:hidden;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d581.0584886504317!2d104.91874003310255!3d11.560459300325428!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310951dab99795f1%3A0xc56beb73ae239a0f!2sRithy%20Granite%20(Cambodia)!5e0!3m2!1sen!2skh!4v1779672634994!5m2!1sen!2skh" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>