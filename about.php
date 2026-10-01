<?php
require_once 'includes/config.php';
$db = getDB();

$pageTitle = $lang['nav_about'] ?? 'About Us';
require_once 'includes/header.php';
?>

<!-- Page Hero -->
<div class="page-hero">
    <div class="container">
        <h1 class="page-hero-title"><?= $lang['about_title'] ?></h1>
        <p class="page-hero-breadcrumb mt-2">
            <a href="index.php"><?= $lang['nav_home'] ?></a> / <?= $lang['nav_about'] ?>
        </p>
    </div>
</div>

<!-- Intro Section -->
<section class="section-pad">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="section-tag"><?= t('Our Story', 'ប្រវត្តិរបស់យើង') ?></div>
                <h2 class="section-title"><?= t('Decades of Excellence in Natural Stone', 'បទពិសោធន៍រាប់ទសវត្សរ៍ក្នុងវិស័យថ្មធម្មជាតិ') ?></h2>
                <div class="divider-gold"></div>
                <p class="lead mb-4" style="color:var(--gold-dark);font-weight:500;">
                    <?= e(getSetting('about_short_' . getCurrentLang())) ?>
                </p>
                <p class="mb-4" style="line-height:1.8;color:var(--text-muted);">
                    <?= t(
                        'Founded in 2004, Rithy 2 Thnou Granite has grown from a local supplier to one of Cambodia\'s leading natural stone providers. We specialize in sourcing, processing, and installing premium granite, marble, and other natural stones for residential and commercial projects nationwide.',
                        'បង្កើតឡើងក្នុងឆ្នាំ ២០០៤ ឫទ្ធី ២ធ្នូ ក្រានីត បានរីកចម្រើនពីអ្នកផ្គត់ផ្គង់ក្នុងស្រុកទៅជាអ្នកផ្តល់សេវាថ្មធម្មជាតិឈានមុខគេមួយនៅក្នុងប្រទេសកម្ពុជា។ យើងមានឯកទេសក្នុងការស្វែងរក ផលិត និងដំឡើងថ្មក្រានីត ម៉ាប និងថ្មធម្មជាតិគុណភាពខ្ពស់ផ្សេងទៀតសម្រាប់គម្រោងលំនៅដ្ឋាន និងពាណិជ្ជកម្មទូទាំងប្រទេស។'
                    ) ?>
                </p>
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div style="display:flex;gap:15px;">
                            <div style="color:var(--gold);font-size:1.5rem;"><i class="bi bi-shield-check"></i></div>
                            <div>
                                <h6 style="margin-bottom:5px;font-weight:700;"><?= t('Quality Assurance', 'ធានាគុណភាព') ?></h6>
                                <p style="font-size:0.85rem;color:var(--text-muted);margin:0;"><?= t('We hand-pick every slab to ensure the highest standards.', 'យើងជ្រើសរើសថ្មគ្រប់សន្លឹកដោយផ្ទាល់ដៃដើម្បីធានាគុណភាព។') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="display:flex;gap:15px;">
                            <div style="color:var(--gold);font-size:1.5rem;"><i class="bi bi-award"></i></div>
                            <div>
                                <h6 style="margin-bottom:5px;font-weight:700;"><?= t('Expert Craftsmanship', 'ជំនាញវិជ្ជាជីវៈ') ?></h6>
                                <p style="font-size:0.85rem;color:var(--text-muted);margin:0;"><?= t('Our artisans have decades of experience in stone cutting.', 'សិប្បកររបស់យើងមានបទពិសោធន៍រាប់ទសវត្សរ៍ក្នុងការកាត់ថ្ម។') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div style="position:relative;">
                    <img src="assets/images/about-1.jpg" alt="About Rithy Granite" style="width:100%;border-radius:3px;box-shadow:var(--shadow-lg);">
                    <div class="d-none d-lg-block" style="position:absolute;bottom:-30px;right:-30px;background:var(--stone-dark);padding:30px;border-radius:3px;color:var(--white);">
                        <div style="font-family:var(--font-heading);font-size:2.5rem;font-weight:700;color:var(--gold);line-height:1;">20+</div>
                        <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;opacity:0.7;"><?= t('Years Experience', 'ឆ្នាំបទពិសោធន៍') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter (Dark) -->
<section class="cta-section section-pad" style="background:var(--stone-dark);">
    <div class="container">
        <div class="row g-4 text-center">
            <?php
            $stats = [
                ['bi-calendar-check', '20+', $lang['stats_years']],
                ['bi-building-check', '500+', $lang['stats_projects']],
                ['bi-people', '1000+', $lang['stats_clients']],
                ['bi-gem', '200+', $lang['stats_products']],
            ];
            foreach ($stats as $s):
            ?>
            <div class="col-6 col-lg-3" data-aos="zoom-in">
                <div style="padding:20px;">
                    <i class="bi <?= $s[0] ?>" style="font-size:2rem;color:var(--gold);margin-bottom:15px;display:block;"></i>
                    <div style="font-family:var(--font-heading);font-size:2.8rem;font-weight:700;color:var(--white);line-height:1;margin-bottom:8px;"><?= $s[1] ?></div>
                    <div style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.5);"><?= e($s[2]) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="section-pad" style="background:var(--stone-cream);">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-up">
                <div style="background:var(--white);padding:40px;border-radius:3px;height:100%;border:1px solid var(--border);box-shadow:var(--shadow);">
                    <div style="width:50px;height:50px;background:var(--stone-pale);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:20px;color:var(--gold);">
                        <i class="bi bi-bullseye" style="font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-family:var(--font-heading);margin-bottom:20px;"><?= $lang['our_mission'] ?></h3>
                    <p style="color:var(--text-muted);line-height:1.8;margin:0;">
                        <?= t(
                            'Our mission is to provide the highest quality natural stone products that enhance the beauty and value of our clients\' spaces, while maintaining the highest standards of integrity, craftsmanship, and customer service.',
                            'បេសកកម្មរបស់យើងគឺផ្តល់នូវផលិតផលថ្មធម្មជាតិគុណភាពខ្ពស់បំផុតដែលបង្កើនសោភ័ណភាព និងតម្លៃនៃកន្លែងរស់នៅរបស់អតិថិជនយើង ស្របពេលរក្សានូវស្តង់ដារខ្ពស់បំផុតនៃភាពស្មោះត្រង់ ជំនាញ និងសេវាកម្មអតិថិជន។'
                        ) ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="150">
                <div style="background:var(--white);padding:40px;border-radius:3px;height:100%;border:1px solid var(--border);box-shadow:var(--shadow);">
                    <div style="width:50px;height:50px;background:var(--stone-pale);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:20px;color:var(--gold);">
                        <i class="bi bi-eye" style="font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-family:var(--font-heading);margin-bottom:20px;"><?= $lang['our_vision'] ?></h3>
                    <p style="color:var(--text-muted);line-height:1.8;margin:0;">
                        <?= t(
                            'To be Cambodia\'s most trusted name in natural stone, recognized for our commitment to quality, innovation in stone processing, and dedication to creating lasting value for every project we touch.',
                            'ក្លាយជាឈ្មោះដែលគួរឱ្យទុកចិត្តបំផុតរបស់កម្ពុជាក្នុងវិស័យថ្មធម្មជាតិ ដែលត្រូវបានទទួលស្គាល់ចំពោះការប្តេជ្ញាចិត្តលើគុណភាព ការច្នៃប្រឌិតក្នុងការកែច្នៃថ្ម និងការយកចិត្តទុកដាក់ក្នុងការបង្កើតតម្លៃយូរអង្វែងសម្រាប់គ្រប់គម្រោងដែលយើងប៉ះពាល់។'
                        ) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-pad">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-tag justify-content-center"><?= t('Excellence Guaranteed', 'ធានានូវឧត្តមភាព') ?></div>
            <h2 class="section-title"><?= $lang['why_choose'] ?></h2>
            <div class="divider-gold mx-auto"></div>
        </div>
        <div class="row g-4">
            <?php
            $features = [
                ['bi-stars', $lang['feature1_title'], $lang['feature1_text']],
                ['bi-tools', $lang['feature2_title'], $lang['feature2_text']],
                ['bi-tags', $lang['feature3_title'], $lang['feature3_text']],
                ['bi-truck', $lang['feature4_title'], $lang['feature4_text']],
                ['bi-scissors', $lang['feature5_title'], $lang['feature5_text']],
                ['bi-heart', $lang['feature6_title'], $lang['feature6_text']],
            ];
            foreach ($features as $f):
            ?>
            <div class="col-md-4" data-aos="fade-up">
                <div class="text-center p-4">
                    <i class="bi <?= $f[0] ?>" style="font-size:2.5rem;color:var(--gold);margin-bottom:20px;display:block;"></i>
                    <h5 style="font-weight:700;margin-bottom:12px;"><?= e($f[1]) ?></h5>
                    <p style="color:var(--text-muted);font-size:0.9rem;line-height:1.7;"><?= e($f[2]) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>