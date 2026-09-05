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

CREATE TABLE IF NOT EXISTS `flight_api_settings` (
  `id` INT PRIMARY KEY DEFAULT 1,
  `environment` VARCHAR(20) DEFAULT 'live',
  `live_client_id` VARCHAR(100) DEFAULT 'APISKYPLANETN',
  `live_password` VARCHAR(255) DEFAULT 'SUB@908#54961',
  `live_merchant_id` VARCHAR(50) DEFAULT '200',
  `live_api_key` VARCHAR(255) DEFAULT 'kXAY9yHARK',
  `live_browser_key` VARCHAR(255) DEFAULT '069ab7973ac12116ccc1802546ad52bf',
  `live_agent_code` VARCHAR(50) DEFAULT ' ',
  `live_utils_url` VARCHAR(255) DEFAULT 'https://apiutilsagents.akbartravelsonline.com',
  `live_flight_url` VARCHAR(255) DEFAULT 'https://apiagents.akbartravelsonline.com',
  `sandbox_client_id` VARCHAR(100) DEFAULT 'bitest',
  `sandbox_password` VARCHAR(255) DEFAULT 'staging@1',
  `sandbox_merchant_id` VARCHAR(50) DEFAULT '300',
  `sandbox_api_key` VARCHAR(255) DEFAULT 'kXAY9yHARK',
  `sandbox_browser_key` VARCHAR(255) DEFAULT 'ef20-925c-4489-bfeb-236c8b406f7e',
  `sandbox_agent_code` VARCHAR(50) DEFAULT ' ',
  `sandbox_utils_url` VARCHAR(255) DEFAULT 'https://b2bapiutils.benzyinfotech.com',
  `sandbox_flight_url` VARCHAR(255) DEFAULT 'https://b2bapiflights.benzyinfotech.com',
  `channel_id` VARCHAR(100) DEFAULT 'b2bIndiaDeals',
  `is_enabled` TINYINT(1) DEFAULT 1,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `flight_api_settings` (`id`, `environment`, `live_client_id`, `live_password`, `live_merchant_id`, `live_api_key`, `live_browser_key`, `live_agent_code`, `live_utils_url`, `live_flight_url`, `sandbox_client_id`, `sandbox_password`, `sandbox_merchant_id`, `sandbox_api_key`, `sandbox_browser_key`, `sandbox_agent_code`, `sandbox_utils_url`, `sandbox_flight_url`, `channel_id`, `is_enabled`)
VALUES (1, 'live', 'APISKYPLANETN', 'SUB@908#54961', '200', 'kXAY9yHARK', '069ab7973ac12116ccc1802546ad52bf', ' ', 'https://apiutilsagents.akbartravelsonline.com', 'https://apiagents.akbartravelsonline.com', 'bitest', 'staging@1', '300', 'kXAY9yHARK', 'ef20-925c-4489-bfeb-236c8b406f7e', ' ', 'https://b2bapiutils.benzyinfotech.com', 'https://b2bapiflights.benzyinfotech.com', 'b2bIndiaDeals', 1);

CREATE TABLE IF NOT EXISTS `hotel_api_settings` (
  `id` INT PRIMARY KEY DEFAULT 1,
  `environment` VARCHAR(20) DEFAULT 'live',
  `live_client_id` VARCHAR(100) DEFAULT 'APISKYPLANETN',
  `live_password` VARCHAR(255) DEFAULT 'SUB@908#54961',
  `live_merchant_id` VARCHAR(50) DEFAULT '200',
  `live_api_key` VARCHAR(255) DEFAULT '069ab7973ac12116ccc1802546ad52bf',
  `live_browser_key` VARCHAR(255) DEFAULT '069ab7973ac12116ccc1802546ad52bf',
  `live_agent_code` VARCHAR(50) DEFAULT ' ',
  `live_utils_url` VARCHAR(255) DEFAULT 'https://apiutilsagents.akbartravelsonline.com',
  `live_hotel_url` VARCHAR(255) DEFAULT 'https://apiagents.akbartravelsonline.com',
  `sandbox_client_id` VARCHAR(100) DEFAULT 'bitest',
  `sandbox_password` VARCHAR(255) DEFAULT 'staging@1',
  `sandbox_merchant_id` VARCHAR(50) DEFAULT '300',
  `sandbox_api_key` VARCHAR(255) DEFAULT 'kXAY9yHARK',
  `sandbox_browser_key` VARCHAR(255) DEFAULT 'caecd3cd30225512c1811070dce615c1',
  `sandbox_agent_code` VARCHAR(50) DEFAULT ' ',
  `sandbox_utils_url` VARCHAR(255) DEFAULT 'https://b2bapiutils.benzyinfotech.com',
  `sandbox_hotel_url` VARCHAR(255) DEFAULT 'https://travelportalapi.benzyinfotech.com',
  `channel_id` VARCHAR(100) DEFAULT 'b2bIndiaDeals',
  `is_enabled` TINYINT(1) DEFAULT 1,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `hotel_api_settings` (`id`, `environment`, `live_client_id`, `live_password`, `live_merchant_id`, `live_api_key`, `live_browser_key`, `live_agent_code`, `live_utils_url`, `live_hotel_url`, `sandbox_client_id`, `sandbox_password`, `sandbox_merchant_id`, `sandbox_api_key`, `sandbox_browser_key`, `sandbox_agent_code`, `sandbox_utils_url`, `sandbox_hotel_url`, `channel_id`, `is_enabled`)
VALUES (1, 'live', 'APISKYPLANETN', 'SUB@908#54961', '200', '069ab7973ac12116ccc1802546ad52bf', '069ab7973ac12116ccc1802546ad52bf', ' ', 'https://apiutilsagents.akbartravelsonline.com', 'https://apiagents.akbartravelsonline.com', 'bitest', 'staging@1', '300', 'kXAY9yHARK', 'caecd3cd30225512c1811070dce615c1', ' ', 'https://b2bapiutils.benzyinfotech.com', 'https://travelportalapi.benzyinfotech.com', 'b2bIndiaDeals', 1);

CREATE TABLE IF NOT EXISTS `hotel_bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_reference` VARCHAR(100) NOT NULL,
  `supplier_reference` VARCHAR(100) DEFAULT NULL,
  `transaction_id` VARCHAR(100) DEFAULT NULL,
  `voucher_number` VARCHAR(100) DEFAULT NULL,
  `hotel_id` VARCHAR(100) NOT NULL,
  `hotel_name` VARCHAR(255) NOT NULL,
  `hotel_address` TEXT DEFAULT NULL,
  `hotel_image` TEXT DEFAULT NULL,
  `star_rating` INT DEFAULT 3,
  `room_type` VARCHAR(255) NOT NULL,
  `board_type` VARCHAR(255) DEFAULT NULL,
  `destination_city` VARCHAR(100) NOT NULL,
  `checkin_date` DATE NOT NULL,
  `checkout_date` DATE NOT NULL,
  `nights_count` INT DEFAULT 1,
  `rooms_count` INT DEFAULT 1,
  `adults_count` INT DEFAULT 2,
  `children_count` INT DEFAULT 0,
  `lead_guest_title` VARCHAR(10) DEFAULT 'Mr',
  `lead_guest_name` VARCHAR(150) NOT NULL,
  `lead_guest_email` VARCHAR(150) NOT NULL,
  `lead_guest_phone` VARCHAR(30) NOT NULL,
  `guest_details_json` LONGTEXT DEFAULT NULL,
  `special_requests` TEXT DEFAULT NULL,
  `total_amount` DECIMAL(10,2) DEFAULT '0.00',
  `tax_amount` DECIMAL(10,2) DEFAULT '0.00',
  `currency` VARCHAR(10) DEFAULT 'INR',
  `payment_status` VARCHAR(50) DEFAULT 'pending',
  `booking_status` VARCHAR(50) DEFAULT 'confirmed',
  `cancellation_policy` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_hotel_booking_ref` (`booking_reference`),
  INDEX `idx_hotel_status` (`booking_status`)
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


