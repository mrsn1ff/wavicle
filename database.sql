-- ============================================================
-- Wavicle - Complete MySQL Database
-- FRESH IMPORT: PhpMyAdmin > wavicle_db > Import > Go
-- Purana data automatically replace ho jayega
-- ============================================================

CREATE DATABASE IF NOT EXISTS `wavicle_db`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `wavicle_db`;

SET FOREIGN_KEY_CHECKS = 0;

-- ─── Admin Users ─────────────────────────────────────────────
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Login: admin@wavicle.com / Admin@1234
INSERT INTO `admin_users` (`name`,`email`,`password`) VALUES
('Wavicle Admin','admin@wavicle.com','$2y$10$i/MoY1i49Q.ERuIpLVrH0.5EvNDjxP4Qdn3cCVVjhRD32b.xyvNEi');

-- ─── Products ────────────────────────────────────────────────
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(200) NOT NULL,
  `slug`        VARCHAR(200) NOT NULL DEFAULT '',
  `category`    VARCHAR(100) NOT NULL DEFAULT '',
  `description` TEXT         NOT NULL,
  `image`       VARCHAR(300) NOT NULL DEFAULT '',
  `link_url`    VARCHAR(300) NOT NULL DEFAULT 'item-details.php',
  `sort_order`  INT          NOT NULL DEFAULT 0,
  `status`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_products_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`title`,`slug`,`category`,`description`,`image`,`sort_order`) VALUES
('Scuba Diving',   'scuba-diving',   'Advanced',     'Professional scuba instruction for those ready to explore deeper waters and expand their horizons beneath the surface.',   'assets/images/courses/course-1-1.jpg', 1),
('Extended Range', 'extended-range', 'Beginner',     'A welcoming introduction to extended-range diving for those taking their very first steps into the ocean.',               'assets/images/courses/course-1-2.jpg', 2),
('Free Diving',    'free-diving',    'Professional', 'Master the art of breath-hold diving with guided sessions from our world-class freediving coaches.',                     'assets/images/courses/course-1-3.jpg', 3),
('Rebreather',     'rebreather',     'Advanced',     'Elevate your dive experience with our advanced rebreather course designed for serious technical divers.',                 'assets/images/courses/course-1-4.jpg', 4),
('Swimming',       'swimming',       'All Levels',   'Build confidence and strength in the water with structured swimming sessions for all ages and abilities.',                'assets/images/courses/course-1-5.jpg', 5),
('Snorkeling',     'snorkeling',     'Beginner',     'Discover the beauty just beneath the surface with our relaxed and fun snorkeling introduction courses.',                 'assets/images/courses/course-1-6.jpg', 6);

-- ─── Services ────────────────────────────────────────────────
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(200) NOT NULL,
  `slug`        VARCHAR(200) NOT NULL DEFAULT '',
  `icon_class`  VARCHAR(100) NOT NULL DEFAULT 'scubo-icon-scuba-diving',
  `description` TEXT         NOT NULL,
  `link_url`    VARCHAR(300) NOT NULL DEFAULT 'item-details.php',
  `sort_order`  INT          NOT NULL DEFAULT 0,
  `status`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_services_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `services` (`title`,`slug`,`icon_class`,`description`,`sort_order`) VALUES
('Scuba Diving',    'scuba-diving-svc', 'scubo-icon-scuba-diving', 'Experience the thrill of scuba diving with professional guidance and top-quality equipment from our expert team.',         1),
('Snorkeling Dive', 'snorkeling-dive',  'scubo-icon-aqualung',     'Explore the ocean surface with our guided snorkeling sessions, perfect for beginners and families.',                       2),
('Learn Swimming',  'learn-swimming',   'scubo-icon-swimming',     'Our certified swimming instructors teach all ages, from beginners to advanced swimmers, in a safe environment.',            3),
('Free Diving',     'free-diving-svc',  'scubo-icon-snorkel',      'Push your limits with freediving training under expert supervision and in a supportive group setting.',                    4);

-- ─── Blogs ───────────────────────────────────────────────────
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(300) NOT NULL,
  `slug`        VARCHAR(300) NOT NULL,
  `excerpt`     TEXT         NOT NULL,
  `content`     LONGTEXT     NOT NULL,
  `image`       VARCHAR(300) NOT NULL DEFAULT '',
  `author`      VARCHAR(100) NOT NULL DEFAULT 'Admin',
  `status`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_blogs_slug` (`slug`),
  KEY `idx_status_created` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `blogs` (`title`,`slug`,`excerpt`,`content`,`image`,`author`) VALUES
('Learn How to Do Scuba Diving on the Island',
 'learn-scuba-diving-island',
 'Discover the best island diving spots and how to prepare for your first open-water island dive experience.',
 '<p>Island diving is one of the most rewarding experiences an ocean enthusiast can have. The combination of crystal-clear water, colourful marine life and the feeling of weightlessness beneath the surface creates a memory that lasts a lifetime.</p><p style="margin-top:15px;">For those new to scuba diving, preparation is everything. Before you even step foot on an island, you should complete a recognised open-water certification course. At Wavicle, our beginner programmes are designed to give you the confidence and skills you need before your first ocean dive.</p>',
 'assets/images/blog/blog-1-1.jpg','Admin'),
('Top Freediving Destinations Around the World',
 'top-freediving-destinations',
 'From the crystal waters of the Philippines to the Mediterranean, explore where the world''s best freedivers train.',
 '<p>Freediving is a sport that takes you to some of the most breathtaking locations on earth. From the warm, clear waters of the Philippines to the dramatic underwater caves of Mexico, the world is full of incredible freediving destinations.</p><p style="margin-top:15px;">At Wavicle, we help you prepare for these adventures with world-class training and expert guidance.</p>',
 'assets/images/blog/blog-1-2.jpg','Admin'),
('Essential Equipment for Every New Diver',
 'essential-equipment-new-diver',
 'A practical guide to the gear you need before your first dive — what to buy, what to rent and what to skip.',
 '<p>Choosing the right equipment is one of the most important decisions you will make as a new diver. The right gear can make the difference between a comfortable, enjoyable dive and a frustrating experience.</p><p style="margin-top:15px;">In this guide, we break down everything you need to know about essential dive equipment — from masks and fins to regulators and BCDs.</p>',
 'assets/images/blog/blog-1-3.jpg','Admin');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- BLOGS SYSTEM v2 — Run in PhpMyAdmin → wavicle_db → SQL
-- ============================================================

-- Drop old blogs table and recreate with sections support
ALTER TABLE `blogs` 
  ADD COLUMN IF NOT EXISTS `main_image` VARCHAR(300) NOT NULL DEFAULT '' AFTER `slug`,
  ADD COLUMN IF NOT EXISTS `description` LONGTEXT NOT NULL DEFAULT '' AFTER `main_image`,
  ADD COLUMN IF NOT EXISTS `author` VARCHAR(100) NOT NULL DEFAULT 'Admin' AFTER `description`;

-- Blog sections table
CREATE TABLE IF NOT EXISTS `blog_sections` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `blog_id`     INT UNSIGNED NOT NULL,
  `heading`     VARCHAR(300) NOT NULL DEFAULT '',
  `content`     LONGTEXT     NOT NULL,
  `sort_order`  INT          NOT NULL DEFAULT 0,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_blog_sec` (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
