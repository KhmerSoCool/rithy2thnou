<?php
// ================================================
// Database Configuration
// ================================================
define('DB_HOST', getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost'));
define('DB_NAME', getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'rithy_granite7979'));
define('DB_USER', getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'root'));
define('DB_PASS', getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? 'ServBay.dev'));
define('DB_CHARSET', getenv('DB_CHARSET') ?: ($_ENV['DB_CHARSET'] ?? 'utf8mb4'));

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
$protocol = $isHttps ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$defaultSiteUrl = (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') === false) ? ($protocol . $host) : 'http://localhost/rithy2thnou-granite';

define('SITE_URL', getenv('SITE_URL') ?: ($_ENV['SITE_URL'] ?? $defaultSiteUrl));
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');


// ================================================
// Database Connection (PDO)
// ================================================
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('<div style="padding:20px;background:#fee;border:1px solid #fcc;font-family:sans-serif;"><h3>Database Connection Error</h3><p>' . htmlspecialchars($e->getMessage()) . '</p><p>Please check your database configuration in <code>includes/config.php</code></p></div>');
        }
    }
    return $pdo;
}

// ================================================
// Helper Functions
// ================================================
function getSetting($key) {
    static $settings = null;
    if ($settings === null) {
        try {
            $db = getDB();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            return '';
        }
    }
    return $settings[$key] ?? '';
}

function getCurrentLang() {
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'km'])) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    if (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = getSetting('default_lang') ?: 'en';
    }
    return $_SESSION['lang'];
}

function t($en, $km) {
    return getCurrentLang() === 'km' ? $km : $en;
}

function getField($row, $field) {
    $lang = getCurrentLang();
    $key = $field . '_' . $lang;
    if (isset($row[$key]) && !empty($row[$key])) return $row[$key];
    // Fallback to English
    $enKey = $field . '_en';
    return $row[$enKey] ?? '';
}

function slug($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . $path);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(SITE_URL . '/admin/login.php');
    }
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return t('Just now', 'ទើបតែ');
    if ($diff < 3600) return floor($diff/60) . t(' min ago', ' នាទីមុន');
    if ($diff < 86400) return floor($diff/3600) . t(' hours ago', ' ម​ ​ ​ ​ ​ ​ ​ ​ ​');
    return date('M d, Y', $time);
}

function uploadImage($file, $folder = 'products') {
    $uploadDir = UPLOAD_DIR . $folder . '/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    
    $allowed = ['jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) return ['error' => 'Invalid file type'];
    if ($file['size'] > 5 * 1024 * 1024) return ['error' => 'File too large (max 5MB)'];
    
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $dest = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return ['success' => true, 'path' => $folder . '/' . $filename, 'url' => UPLOAD_URL . $folder . '/' . $filename];
    }
    return ['error' => 'Upload failed'];
}

session_start();

// Load Language
$currentLang = getCurrentLang();
$langFile = __DIR__ . '/../lang/' . $currentLang . '.php';
if (file_exists($langFile)) {
    require_once $langFile;
} else {
    // Fallback to English if file missing
    require_once __DIR__ . '/../lang/en.php';
}