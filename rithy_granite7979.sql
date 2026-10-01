-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2026 at 12:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rithy_granite7979`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name_en` varchar(200) NOT NULL,
  `name_km` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description_en` text DEFAULT NULL,
  `description_km` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name_en`, `name_km`, `slug`, `description_en`, `description_km`, `parent_id`, `sort_order`, `created_at`) VALUES
(1, 'Granite', 'ក្រានីត', 'granite', 'Natural granite stone for all applications', 'ថ្មក្រានីតធម្មជាតិសម្រាប់ការប្រើប្រាស់គ្រប់ប្រភេទ', NULL, 1, '2026-05-24 04:39:12'),
(2, 'Marble', 'ម៉ាប', 'marble', 'Elegant marble for luxury interiors', 'ថ្មម៉ាបសម្រាប់បន្ទប់贅沢', NULL, 2, '2026-05-24 04:39:12'),
(3, 'Tiles', 'ក្បឿង', 'tiles', 'Premium floor and wall tiles', 'ក្បឿងជាន់និងជញ្ជាំងគុណភាពខ្ពស់', NULL, 3, '2026-05-24 04:39:12'),
(4, 'Sandstone', 'ថ្មខ្សាច់', 'sandstone', 'Natural sandstone products', 'ផលិតផលថ្មខ្សាច់ធម្មជាតិ', NULL, 4, '2026-05-24 04:39:12'),
(5, 'Countertops', 'តុចម្អិន', 'countertops', 'Kitchen and bathroom countertops', 'តុចម្អិនបន្ទប់ម្ហូបនិងបន្ទប់ទឹក', NULL, 5, '2026-05-24 04:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(300) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title_en` varchar(300) NOT NULL,
  `title_km` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `content_en` longtext DEFAULT NULL,
  `content_km` longtext DEFAULT NULL,
  `meta_desc_en` text DEFAULT NULL,
  `meta_desc_km` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title_en` varchar(300) NOT NULL,
  `title_km` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `excerpt_en` text DEFAULT NULL,
  `excerpt_km` text DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `content_km` longtext DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title_en`, `title_km`, `slug`, `excerpt_en`, `excerpt_km`, `content_en`, `content_km`, `featured_image`, `author_id`, `is_active`, `views`, `created_at`, `updated_at`) VALUES
(1, 'ds f', 'ដសង', 'ds-f', 'agdf', 'ដសថង', 'dsg', 'ដសថហ', 'news/6a128accac496_1779600076.jpg', 1, 1, 1, '2026-05-24 05:21:16', '2026-05-24 05:21:34');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name_en` varchar(300) NOT NULL,
  `name_km` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `description_en` longtext DEFAULT NULL,
  `description_km` longtext DEFAULT NULL,
  `short_desc_en` text DEFAULT NULL,
  `short_desc_km` text DEFAULT NULL,
  `price` decimal(15,2) DEFAULT 0.00,
  `price_unit_en` varchar(50) DEFAULT 'per sqm',
  `price_unit_km` varchar(50) DEFAULT 'ក្នុងមួយម',
  `thickness` varchar(50) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `finish_type_en` varchar(100) DEFAULT NULL,
  `finish_type_km` varchar(100) DEFAULT NULL,
  `origin_en` varchar(100) DEFAULT NULL,
  `origin_km` varchar(100) DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name_en`, `name_km`, `slug`, `description_en`, `description_km`, `short_desc_en`, `short_desc_km`, `price`, `price_unit_en`, `price_unit_km`, `thickness`, `size`, `finish_type_en`, `finish_type_km`, `origin_en`, `origin_km`, `featured_image`, `gallery`, `is_featured`, `is_active`, `views`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Black Galaxy Granite', 'ក្រានីតទទួលហ្គាឡារ៉ាស', 'black-galaxy-granite', 'Premium black granite with golden speckles, perfect for floors and countertops.', '', 'Premium black granite with golden speckles.', '', 45.00, 'per sqm', 'ក្នុងមួយម', '18mm', '60x60cm', 'Polished', 'ខាត់ស្រលប់', 'India', 'ឥណ្ឌា', 'products/6a128b14416d8_1779600148.jpg', NULL, 1, 1, 2, 0, '2026-05-24 04:39:12', '2026-05-24 05:22:33'),
(2, 1, 'Kashmir White Granite', 'ក្រានីតស​ cache​ ហ្ស៍​ វ៉ាយ', 'kashmir-white-granite', 'Beautiful white granite with grey and burgundy crystals.', 'ក្រានីតស ស្ថ​អ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 'White granite with elegant crystal patterns.', 'ក្រានីតសសោភ័ណភ្ញាំ', 38.00, 'per sqm', 'ក្នុងមួយម', '18mm', '60x60cm', 'Polished', 'ខាត់ស្រលប់', 'India', 'ឥណ្ឌា', NULL, NULL, 1, 1, 0, 0, '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(3, 2, 'Calacatta Gold Marble', 'ថ្មម៉ាប ហ្គោល', 'calacatta-gold-marble', 'Luxurious Italian marble with gold veining, perfect for premium interiors.', 'ថ្មម៉ាបអ៊ីតាលីនាំចូលនូវវ៉ែនមាស ស​ ​ ​ ​ ​ ​', 'Luxurious Italian marble with gold veining.', 'ថ្មម៉ាបអ៊ីតាលីស្ថ​ ​ ​ ​', 85.00, 'per sqm', 'ក្នុងមួយម', '20mm', '60x120cm', 'Honed', 'ស​ ​ ​ ​', 'Italy', 'អ៊ីតាលី', NULL, NULL, 1, 1, 0, 0, '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(4, 3, 'Porcelain Floor Tile', 'ក្បឿងជាន់ Porcelain', 'porcelain-floor-tile', 'High-quality porcelain tiles for indoor and outdoor use.', 'ក្បឿងប​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 'High-quality porcelain tiles, slip-resistant.', 'ក្បឿង Porcelain', 22.00, 'per sqm', 'ក្នុងមួយម', '10mm', '60x60cm', 'Matte', 'ស​ ​', 'China', 'ចិន', NULL, NULL, 0, 1, 0, 0, '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(5, 5, 'Granite Kitchen Countertop', 'តុចម្អិន Granite', 'granite-kitchen-countertop', 'Custom granite countertops for kitchen and bathroom installations.', 'តុចម្អិន Granite ​ ​ ​ ​ ​', 'Custom granite countertops, heat and scratch resistant.', 'តុចម្អិន Granite ​ ​', 120.00, 'per sqm', 'ក្នុងមួយម', '30mm', 'Custom', 'Polished', 'ខាត់ស្រលប់', 'Brazil', 'ប្រេស៊ីល', NULL, NULL, 1, 1, 0, 0, '2026-05-24 04:39:12', '2026-05-24 04:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_name_en', 'Rithy 2 Thnou Granite', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(2, 'site_name_km', 'ឫទ្ធី ២ធ្នូ ក្រានីត', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(3, 'site_tagline_en', 'Premium Granite & Natural Stone Solutions', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(4, 'site_tagline_km', 'ដំណោះស្រាយថ្មក្រានីតនិងថ្មធម្មជាតិល្អបំផុត', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(5, 'site_email', 'rithy2thnou.info@gmail.com', '2026-05-24 04:39:12', '2026-05-24 05:06:41'),
(6, 'site_phone', '+855 88 690 7979', '2026-05-24 04:39:12', '2026-05-24 05:06:41'),
(7, 'site_phone2', '+855 23 456 789', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(8, 'site_address_en', 'Phnom Penh, Cambodia', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(9, 'site_address_km', 'ភ្នំពេញ, កម្ពុជា', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(10, 'site_facebook', 'https://facebook.com/rithy2thnou', '2026-05-24 04:39:12', '2026-05-24 05:06:41'),
(11, 'site_logo', 'site/6a128761d1166_1779599201.png', '2026-05-24 04:39:12', '2026-05-24 05:06:41'),
(12, 'site_favicon', '', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(13, 'about_short_en', 'Rithy 2 Thnou Granite has been supplying premium granite and natural stone products across Cambodia for over 20 years.', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(14, 'about_short_km', 'ឫទ្ធី ២ធ្នូ ក្រានីត បានផ្គត់ផ្គង់ផលិតផលថ្មក្រានីតនិងថ្មធម្មជាតិគុណភាពខ្ពស់នៅទូទាំងប្រទេសកម្ពុជារយៈពេលជាង ២០ ឆ្នាំ', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(15, 'currency', 'USD', '2026-05-24 04:39:12', '2026-05-24 04:39:12'),
(16, 'default_lang', 'en', '2026-05-24 04:39:12', '2026-05-24 04:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `title_en` varchar(300) DEFAULT NULL,
  `title_km` varchar(300) DEFAULT NULL,
  `subtitle_en` text DEFAULT NULL,
  `subtitle_km` text DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `btn_text_en` varchar(100) DEFAULT 'Learn More',
  `btn_text_km` varchar(100) DEFAULT 'ស្វែងយល់បន្ថែម',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title_en`, `title_km`, `subtitle_en`, `subtitle_km`, `image`, `link`, `btn_text_en`, `btn_text_km`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Premium Granite & Natural Stone', 'ក្រានីតនិងថ្មធម្មជាតិ', 'Supplying Cambodia with the finest granite and natural stone since 2004', 'ផ្គត់ផ្គង់ថ្មក្រានីតនិងថ្មធម្មជាតិល្អបំផុតដល់កម្ពុជាតាំងពីឆ្នាំ ២០០៤', 'sliders/6a12a172651bc_1779605874.jpg', '', 'View Products', 'មើលផលិតផល', 1, 1, '2026-05-24 04:39:12'),
(2, 'Transform Your Space', 'ផ្លាស់ប្ដូរទំហំរបស់អ្នក', 'From kitchens to grand lobbies — we have the stone for every vision', 'ពីបន្ទប់ម្ហូបដល់ Lobby ធំ — យើងមានថ្មសម្រាប់គ្រប់ការស្ទង់', 'sliders/6a12a1834b00f_1779605891.jpg', '', 'Shop Now', 'ទិញឥឡូវ', 2, 1, '2026-05-24 04:39:12'),
(3, 'Professional Installation', 'ការដំឡើងប្រកបដោយវិជ្ជាជីវៈ', 'Our expert team ensures perfect installation every time', 'ក្រុមអ្នកជំនាញរបស់យើងធានានូវការដំឡើងល្អឥតខ្ចោះ', 'sliders/6a12a1936a4c1_1779605907.jpg', '', 'Contact Us', 'ទាក់ទងយើង', 3, 1, '2026-05-24 04:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `client_name_en` varchar(200) DEFAULT NULL,
  `client_name_km` varchar(200) DEFAULT NULL,
  `company_en` varchar(200) DEFAULT NULL,
  `company_km` varchar(200) DEFAULT NULL,
  `message_en` text DEFAULT NULL,
  `message_km` text DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `client_name_en`, `client_name_km`, `company_en`, `company_km`, `message_en`, `message_km`, `rating`, `is_active`, `created_at`) VALUES
(1, 'Sopheak Chan', 'ចន សុភ័ក្ត', 'Chan Construction Co.', 'ក្រុមហ៊ុន ចន ก​ ​', 'Excellent quality granite at competitive prices. Fast delivery and professional installation team.', '​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 5, 1, '2026-05-24 04:39:12'),
(2, 'Dara Kosal', 'ដារ៉ា កូសាល', 'Luxury Homes KH', 'Luxury Homes KH', 'We used their marble for our luxury villa project. Simply stunning results.', '​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 5, 1, '2026-05-24 04:39:12'),
(3, 'Malis Pich', 'ពេជ្រ​ ​ ​ ​', 'Interior Design Studio', '​ ​ ​ ​', 'Best granite supplier in Phnom Penh. Always reliable and quality is top notch.', '​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​ ​', 5, 1, '2026-05-24 04:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(200) DEFAULT NULL,
  `role` enum('admin','editor','author') DEFAULT 'author',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$D//pcboW9w8KpE.LBfnVCOpgb/gwkVF7Bzeik65FWehKt4He7Iwq2', 'admin@rithygranite.com', 'Administrator', 'admin', '2026-05-24 04:39:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
