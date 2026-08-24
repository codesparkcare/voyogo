-- Voyogo Database Schema

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `flight_bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_ref` VARCHAR(50) NOT NULL UNIQUE,
  `pnr` VARCHAR(20) DEFAULT NULL,
  `airline_name` VARCHAR(100) DEFAULT NULL,
  `airline_code` VARCHAR(10) DEFAULT NULL,
  `flight_number` VARCHAR(20) DEFAULT NULL,
  `origin` VARCHAR(100) DEFAULT NULL,
  `destination` VARCHAR(100) DEFAULT NULL,
  `departure_datetime` DATETIME DEFAULT NULL,
  `arrival_datetime` DATETIME DEFAULT NULL,
  `cabin_class` VARCHAR(50) DEFAULT 'Economy',
  `passenger_details` TEXT DEFAULT NULL,
  `contact_name` VARCHAR(100) DEFAULT NULL,
  `contact_email` VARCHAR(100) DEFAULT NULL,
  `contact_phone` VARCHAR(20) DEFAULT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `payment_id` VARCHAR(100) DEFAULT NULL,
  `payment_status` VARCHAR(20) DEFAULT 'Pending',
  `booking_status` VARCHAR(20) DEFAULT 'Confirmed',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hotel_bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_ref` VARCHAR(50) NOT NULL UNIQUE,
  `hotel_id` VARCHAR(50) DEFAULT NULL,
  `hotel_name` VARCHAR(255) DEFAULT NULL,
  `hotel_address` TEXT DEFAULT NULL,
  `hotel_image` TEXT DEFAULT NULL,
  `room_type` VARCHAR(100) DEFAULT NULL,
  `checkin_date` DATE DEFAULT NULL,
  `checkout_date` DATE DEFAULT NULL,
  `guests_count` INT DEFAULT 1,
  `rooms_count` INT DEFAULT 1,
  `primary_guest_name` VARCHAR(100) DEFAULT NULL,
  `guest_email` VARCHAR(100) DEFAULT NULL,
  `guest_phone` VARCHAR(20) DEFAULT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `payment_id` VARCHAR(100) DEFAULT NULL,
  `payment_status` VARCHAR(20) DEFAULT 'Pending',
  `booking_status` VARCHAR(20) DEFAULT 'Confirmed',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_settings` (
  `id` INT PRIMARY KEY DEFAULT 1,
  `smtp_host` VARCHAR(100) DEFAULT 'smtp.gmail.com',
  `smtp_port` INT DEFAULT 587,
  `smtp_user` VARCHAR(100) DEFAULT '',
  `smtp_pass` VARCHAR(255) DEFAULT '',
  `smtp_crypto` VARCHAR(10) DEFAULT 'tls',
  `from_email` VARCHAR(100) DEFAULT 'noreply@voyogo.com',
  `from_name` VARCHAR(100) DEFAULT 'Voyogo Travels',
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `razorpay_settings` (
  `id` INT PRIMARY KEY DEFAULT 1,
  `razorpay_key_id` VARCHAR(255) DEFAULT 'rzp_test_TTVGSNKy0V1o7B',
  `razorpay_key_secret` VARCHAR(255) DEFAULT 'na1MTEQwpH6CFfHOVghZn2GO',
  `merchant_name` VARCHAR(100) DEFAULT 'Voyogo Travels',
  `theme_color` VARCHAR(20) DEFAULT '#0d3470',
  `currency` VARCHAR(10) DEFAULT 'INR',
  `environment` VARCHAR(20) DEFAULT 'test',
  `is_enabled` TINYINT(1) DEFAULT 1,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `api_logs` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `service_type` VARCHAR(50) NOT NULL,
  `action_name` VARCHAR(100) NOT NULL,
  `endpoint_url` TEXT NOT NULL,
  `request_method` VARCHAR(10) NOT NULL DEFAULT 'POST',
  `request_payload` LONGTEXT DEFAULT NULL,
  `response_payload` LONGTEXT DEFAULT NULL,
  `http_code` INT DEFAULT 200,
  `execution_time_ms` INT DEFAULT 0,
  `error_message` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_service_type` (`service_type`),
  INDEX `idx_action_name` (`action_name`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Admin User (Password: admin123)
INSERT INTO `admin_users` (`username`, `password`, `email`)
SELECT 'admin', '$2y$10$cyNdwHuPyQQEtWob3FftDuNfhwqmMkmh5Li4i.bn2CfkgU0nLHEuO', 'admin@voyogo.com'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `admin_users` WHERE `username` = 'admin');

-- Default Email Settings row
INSERT INTO `email_settings` (`id`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`, `smtp_crypto`, `from_email`, `from_name`)
SELECT 1, 'smtp.gmail.com', 587, '', '', 'tls', 'noreply@voyogo.com', 'Voyogo Travels'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `email_settings` WHERE `id` = 1);

-- Default Razorpay Settings row
INSERT INTO `razorpay_settings` (`id`, `razorpay_key_id`, `razorpay_key_secret`, `merchant_name`, `theme_color`, `currency`, `environment`, `is_enabled`)
SELECT 1, 'rzp_test_TTVGSNKy0V1o7B', 'na1MTEQwpH6CFfHOVghZn2GO', 'Voyogo Travels', '#0d3470', 'INR', 'test', 1
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `razorpay_settings` WHERE `id` = 1);


