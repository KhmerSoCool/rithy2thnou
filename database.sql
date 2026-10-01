-- ================================================
-- Rithy 2 Thnou Granite - Database Setup
-- ================================================

CREATE DATABASE IF NOT EXISTS rithy_granite7979 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rithy_granite7979;

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    full_name VARCHAR(200),
    role ENUM('admin','editor','author') DEFAULT 'author',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(200) NOT NULL,
    name_km VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description_en TEXT,
    description_km TEXT,
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name_en VARCHAR(300) NOT NULL,
    name_km VARCHAR(300) NOT NULL,
    slug VARCHAR(300) UNIQUE NOT NULL,
    description_en LONGTEXT,
    description_km LONGTEXT,
    short_desc_en TEXT,
    short_desc_km TEXT,
    price DECIMAL(15,2) DEFAULT 0,
    price_unit_en VARCHAR(50) DEFAULT 'per sqm',
    price_unit_km VARCHAR(50) DEFAULT 'ក្នុងមួយម',
    thickness VARCHAR(50),
    size VARCHAR(100),
    finish_type_en VARCHAR(100),
    finish_type_km VARCHAR(100),
    origin_en VARCHAR(100),
    origin_km VARCHAR(100),
    featured_image VARCHAR(500),
    gallery TEXT,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    views INT DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Pages table (CMS pages like About, Contact)
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(300) NOT NULL,
    title_km VARCHAR(300) NOT NULL,
    slug VARCHAR(300) UNIQUE NOT NULL,
    content_en LONGTEXT,
    content_km LONGTEXT,
    meta_desc_en TEXT,
    meta_desc_km TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blog/News posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(300) NOT NULL,
    title_km VARCHAR(300) NOT NULL,
    slug VARCHAR(300) UNIQUE NOT NULL,
    excerpt_en TEXT,
    excerpt_km TEXT,
    content_en LONGTEXT,
    content_km LONGTEXT,
    featured_image VARCHAR(500),
    author_id INT,
    is_active TINYINT(1) DEFAULT 1,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Contact messages
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    subject VARCHAR(300),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name_en VARCHAR(200),
    client_name_km VARCHAR(200),
    company_en VARCHAR(200),
    company_km VARCHAR(200),
    message_en TEXT,
    message_km TEXT,
    rating INT DEFAULT 5,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sliders/Banners
CREATE TABLE IF NOT EXISTS sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(300),
    title_km VARCHAR(300),
    subtitle_en TEXT,
    subtitle_km TEXT,
    image VARCHAR(500),
    link VARCHAR(500),
    btn_text_en VARCHAR(100) DEFAULT 'Learn More',
    btn_text_km VARCHAR(100) DEFAULT 'ស្វែងយល់បន្ថែម',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- Default Data
-- ================================================

-- Admin user (password: admin123)
INSERT INTO users (username, password, email, full_name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@rithygranite.com', 'Administrator', 'admin');

-- Site settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name_en', 'Rithy 2 Thnou Granite'),
('site_name_km', 'ឫទ្ធី ២ធ្នូ ក្រានីត'),
('site_tagline_en', 'Premium Granite & Natural Stone Solutions'),
('site_tagline_km', 'ដំណោះស្រាយថ្មក្រានីតនិងថ្មធម្មជាតិល្អបំផុត'),
('site_email', 'info@rithygranite.com'),
('site_phone', '+855 12 345 678'),
('site_phone2', '+855 23 456 789'),
('site_address_en', 'Phnom Penh, Cambodia'),
('site_address_km', 'ភ្នំពេញ, កម្ពុជា'),
('site_facebook', 'https://facebook.com/rithygranite'),
('site_logo', ''),
('site_favicon', ''),
('about_short_en', 'Rithy 2 Thnou Granite has been supplying premium granite and natural stone products across Cambodia for over 20 years.'),
('about_short_km', 'ឫទ្ធី ២ធ្នូ ក្រានីត បានផ្គត់ផ្គង់ផលិតផលថ្មក្រានីតនិងថ្មធម្មជាតិគុណភាពខ្ពស់នៅទូទាំងប្រទេសកម្ពុជារយៈពេលជាង ២០ ឆ្នាំ'),
('currency', 'USD'),
('default_lang', 'en');

-- Categories
INSERT INTO categories (name_en, name_km, slug, description_en, description_km, sort_order) VALUES
('Granite', 'ក្រានីត', 'granite', 'Natural granite stone for all applications', 'ថ្មក្រានីតធម្មជាតិសម្រាប់ការប្រើប្រាស់គ្រប់ប្រភេទ', 1),
('Marble', 'ម៉ាប', 'marble', 'Elegant marble for luxury interiors', 'ថ្មម៉ាបសម្រាប់បន្ទប់贅沢', 2),
('Tiles', 'ក្បឿង', 'tiles', 'Premium floor and wall tiles', 'ក្បឿងជាន់និងជញ្ជាំងគុណភាពខ្ពស់', 3),
('Sandstone', 'ថ្មខ្សាច់', 'sandstone', 'Natural sandstone products', 'ផលិតផលថ្មខ្សាច់ធម្មជាតិ', 4),
('Countertops', 'តុចម្អិន', 'countertops', 'Kitchen and bathroom countertops', 'តុចម្អិនបន្ទប់ម្ហូបនិងបន្ទប់ទឹក', 5);

-- Sample products
INSERT INTO products (category_id, name_en, name_km, slug, description_en, description_km, short_desc_en, short_desc_km, price, thickness, size, finish_type_en, finish_type_km, origin_en, origin_km, is_featured, is_active) VALUES
(1, 'Black Galaxy Granite', 'ក្រានីតទទួលហ្គាឡារ៉ាស', 'black-galaxy-granite', 'Premium black granite with golden speckles, perfect for floors and countertops.', 'ក្រានីតខ្មៅបន្ថែមទំហំមាស សមរម្យសម្រាប់ជាន់និងតុចម្អិន។', 'Premium black granite with golden speckles.', 'ក្រានីតខ្មៅគ្រាប់មាស', 45.00, '18mm', '60x60cm', 'Polished', 'ខាត់ស្រលប់', 'India', 'ឥណ្ឌា', 1, 1),
(1, 'Kashmir White Granite', 'ក្រានីតស​ cache​ ហ្ស៍​ វ៉ាយ', 'kashmir-white-granite', 'Beautiful white granite with grey and burgundy crystals.', 'ក្រានីតស ស្ថ​អ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 'White granite with elegant crystal patterns.', 'ក្រានីតសសោភ័ណភ្ញាំ', 38.00, '18mm', '60x60cm', 'Polished', 'ខាត់ស្រលប់', 'India', 'ឥណ្ឌា', 1, 1),
(2, 'Calacatta Gold Marble', 'ថ្មម៉ាប ហ្គោល', 'calacatta-gold-marble', 'Luxurious Italian marble with gold veining, perfect for premium interiors.', 'ថ្មម៉ាបអ៊ីតាលីនាំចូលនូវវ៉ែនមាស ស​ ​ ​ ​ ​ ​', 'Luxurious Italian marble with gold veining.', 'ថ្មម៉ាបអ៊ីតាលីស្ថ​ ​ ​ ​', 85.00, '20mm', '60x120cm', 'Honed', 'ស​ ​ ​ ​', 'Italy', 'អ៊ីតាលី', 1, 1),
(3, 'Porcelain Floor Tile', 'ក្បឿងជាន់ Porcelain', 'porcelain-floor-tile', 'High-quality porcelain tiles for indoor and outdoor use.', 'ក្បឿងប​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 'High-quality porcelain tiles, slip-resistant.', 'ក្បឿង Porcelain', 22.00, '10mm', '60x60cm', 'Matte', 'ស​ ​', 'China', 'ចិន', 0, 1),
(5, 'Granite Kitchen Countertop', 'តុចម្អិន Granite', 'granite-kitchen-countertop', 'Custom granite countertops for kitchen and bathroom installations.', 'តុចម្អិន Granite ​ ​ ​ ​ ​', 'Custom granite countertops, heat and scratch resistant.', 'តុចម្អិន Granite ​ ​', 120.00, '30mm', 'Custom', 'Polished', 'ខាត់ស្រលប់', 'Brazil', 'ប្រេស៊ីល', 1, 1);

-- Testimonials
INSERT INTO testimonials (client_name_en, client_name_km, company_en, company_km, message_en, message_km, rating) VALUES
('Sopheak Chan', 'ចន សុភ័ក្ត', 'Chan Construction Co.', 'ក្រុមហ៊ុន ចន ก​ ​', 'Excellent quality granite at competitive prices. Fast delivery and professional installation team.', '​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 5),
('Dara Kosal', 'ដារ៉ា កូសាល', 'Luxury Homes KH', 'Luxury Homes KH', 'We used their marble for our luxury villa project. Simply stunning results.', '​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 5),
('Malis Pich', 'ពេជ្រ​ ​ ​ ​', 'Interior Design Studio', '​ ​ ​ ​', 'Best granite supplier in Phnom Penh. Always reliable and quality is top notch.', '​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 5);

-- Sliders
INSERT INTO sliders (title_en, title_km, subtitle_en, subtitle_km, btn_text_en, btn_text_km, sort_order) VALUES
('Premium Granite & Natural Stone', 'ក្រានីតនិងថ្មធម្មជាតិ', 'Supplying Cambodia with the finest granite and natural stone since 2004', 'ផ្គត់ផ្គង់ថ្មក្រានីតនិងថ្មធម្មជាតិល្អបំផុតដល់កម្ពុជាតាំងពីឆ្នាំ ២០០៤', 'View Products', 'មើលផលិតផល', 1),
('Transform Your Space', 'ផ្លាស់ប្ដូរទំហំរបស់អ្នក', 'From kitchens to grand lobbies — we have the stone for every vision', 'ពីបន្ទប់ម្ហូបដល់ Lobby ធំ — យើងមានថ្មសម្រាប់គ្រប់ការស្ទង់', 'Shop Now', 'ទិញឥឡូវ', 2),
('Professional Installation', 'ការដំឡើងប្រកបដោយវិជ្ជាជីវៈ', 'Our expert team ensures perfect installation every time', 'ក្រុមអ្នកជំនាញរបស់យើងធានានូវការដំឡើងល្អឥតខ្ចោះ', 'Contact Us', 'ទាក់ទងយើង', 3);