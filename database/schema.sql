-- ===================================================
-- AutoMarket - Used Car Marketplace Database Schema
-- ===================================================

CREATE DATABASE IF NOT EXISTS `used_cars_db`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `used_cars_db`;

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Cars Table
CREATE TABLE IF NOT EXISTS `cars` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `brand` VARCHAR(50) NOT NULL,
  `model` VARCHAR(100) NOT NULL,
  `year` INT NOT NULL,
  `mileage` INT NOT NULL DEFAULT 0,
  `price` DECIMAL(12,2) NOT NULL,
  `fuel_type` VARCHAR(30) NOT NULL DEFAULT 'Gasoline',
  `transmission` VARCHAR(30) NOT NULL DEFAULT 'Automatic',
  `condition_status` VARCHAR(30) NOT NULL DEFAULT 'Used',
  `description` TEXT,
  `location` VARCHAR(255) DEFAULT NULL,
  `location_lat` DECIMAL(10,7) DEFAULT NULL,
  `location_lng` DECIMAL(10,7) DEFAULT NULL,
  `whatsapp_phone` VARCHAR(20) DEFAULT NULL,
  `damage_data` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_brand` (`brand`),
  INDEX `idx_price` (`price`),
  INDEX `idx_year` (`year`)
) ENGINE=InnoDB;

-- Car Images Table
CREATE TABLE IF NOT EXISTS `car_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `car_id` INT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_primary` TINYINT(1) DEFAULT 0,
  FOREIGN KEY (`car_id`) REFERENCES `cars`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Favorites Table
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `car_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`car_id`) REFERENCES `cars`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_favorite` (`user_id`, `car_id`)
) ENGINE=InnoDB;

-- ===================================================
-- Seed Data: Sample User (password: password123)
-- ===================================================
INSERT INTO `users` (`name`, `email`, `password_hash`) VALUES
('Demo User', 'demo@automarket.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ===================================================
-- Seed Data: Sample Cars
-- ===================================================
INSERT INTO `cars` (`user_id`, `brand`, `model`, `year`, `mileage`, `price`, `fuel_type`, `transmission`, `condition_status`, `description`, `location`) VALUES
(1, 'Toyota', 'Camry', 2022, 15000, 28500.00, 'Gasoline', 'Automatic', 'Excellent', 'Well maintained Toyota Camry with low mileage. Single owner, full service history. Features include leather seats, sunroof, and advanced safety systems.', 'Dubai, UAE'),
(1, 'BMW', '3 Series', 2021, 25000, 42000.00, 'Gasoline', 'Automatic', 'Excellent', 'Stunning BMW 3 Series in Alpine White. Sport package included with M-Sport steering wheel, sport suspension, and 19-inch alloy wheels.', 'Abu Dhabi, UAE'),
(1, 'Mercedes-Benz', 'C-Class', 2023, 8000, 55000.00, 'Gasoline', 'Automatic', 'Like New', 'Nearly new Mercedes C-Class with AMG line package. Premium interior with ambient lighting, Burmester sound system, and panoramic roof.', 'Dubai, UAE'),
(1, 'Audi', 'A4', 2022, 18000, 38000.00, 'Gasoline', 'Automatic', 'Excellent', 'Audi A4 with Virtual Cockpit, MMI Navigation Plus, and premium Bang & Olufsen sound system. Finished in Mythos Black.', 'Sharjah, UAE'),
(1, 'Lexus', 'ES 350', 2021, 22000, 36000.00, 'Gasoline', 'Automatic', 'Excellent', 'Lexus ES 350 F-Sport with Mark Levinson audio, heads-up display, and adaptive variable suspension. Immaculate condition.', 'Dubai, UAE'),
(1, 'Honda', 'Accord', 2023, 5000, 32000.00, 'Gasoline', 'Automatic', 'Like New', 'Brand new condition Honda Accord Touring. Full LED headlights, wireless Apple CarPlay, and Honda Sensing suite.', 'Ajman, UAE'),
(1, 'Ford', 'Mustang', 2022, 12000, 48000.00, 'Gasoline', 'Automatic', 'Excellent', 'Ford Mustang GT Premium with 5.0L V8 engine. Custom exhaust, performance package, and Recaro sport seats.', 'Dubai, UAE'),
(1, 'Nissan', 'Patrol', 2023, 10000, 65000.00, 'Gasoline', 'Automatic', 'Like New', 'Nissan Patrol Platinum with full luxury package. 8-cylinder engine, Bose sound system, and intelligent rear view mirror.', 'Abu Dhabi, UAE');
