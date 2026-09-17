-- Voyogo Database Schema

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'superadmin',
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
INSERT INTO `admin_users` (`username`, `password`, `email`, `role`)
SELECT 'admin', '$2y$10$cyNdwHuPyQQEtWob3FftDuNfhwqmMkmh5Li4i.bn2CfkgU0nLHEuO', 'admin@voyogo.com', 'superadmin'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `admin_users` WHERE `username` = 'admin');

-- Default Leads Manager User (Password: Leads@123*)
INSERT INTO `admin_users` (`username`, `password`, `email`, `role`)
SELECT 'leads', '$2y$10$w09dsm2Uj1yV3o7eZ.l1GuvlR1Qv79qj0U48uB1U66nE5t8l0309O', 'leads@voyogo.com', 'leads_manager'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `admin_users` WHERE `username` = 'leads');

-- Default Email Settings row
INSERT INTO `email_settings` (`id`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`, `smtp_crypto`, `from_email`, `from_name`)
SELECT 1, 'smtp.gmail.com', 587, '', '', 'tls', 'noreply@voyogo.com', 'Voyogo Travels'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `email_settings` WHERE `id` = 1);

-- Default Razorpay Settings row
INSERT INTO `razorpay_settings` (`id`, `razorpay_key_id`, `razorpay_key_secret`, `merchant_name`, `theme_color`, `currency`, `environment`, `is_enabled`)
SELECT 1, 'rzp_test_TTVGSNKy0V1o7B', 'na1MTEQwpH6CFfHOVghZn2GO', 'Voyogo Travels', '#0d3470', 'INR', 'test', 1
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `razorpay_settings` WHERE `id` = 1);

-- =====================================================
-- Franchise Module Schema (B2B Admin, Stores & Float)
-- =====================================================

CREATE TABLE IF NOT EXISTS `franchise_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `franchise_stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_code` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `store_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `gst_number` varchar(30) DEFAULT NULL,
  `wallet_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agent_code` (`agent_code`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `franchise_wallet_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `transaction_type` enum('credit','debit') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `previous_balance` decimal(12,2) NOT NULL,
  `new_balance` decimal(12,2) NOT NULL,
  `reference_type` varchar(50) NOT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT 'system',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `franchise_flight_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `booking_ref` varchar(50) NOT NULL,
  `pnr` varchar(50) DEFAULT NULL,
  `airline_name` varchar(100) DEFAULT NULL,
  `flight_number` varchar(50) DEFAULT NULL,
  `origin` varchar(10) NOT NULL,
  `destination` varchar(10) NOT NULL,
  `departure_datetime` datetime DEFAULT NULL,
  `passenger_details` longtext DEFAULT NULL,
  `base_fare` decimal(10,2) DEFAULT 0.00,
  `taxes` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `wallet_deducted` decimal(10,2) NOT NULL,
  `status` varchar(30) DEFAULT 'confirmed',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_ref` (`booking_ref`),
  KEY `idx_fb_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `franchise_hotel_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `booking_ref` varchar(50) NOT NULL,
  `hotel_id` varchar(50) DEFAULT NULL,
  `hotel_name` varchar(150) NOT NULL,
  `room_type` varchar(150) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `primary_guest_name` varchar(100) NOT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `guest_email` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `wallet_deducted` decimal(10,2) NOT NULL,
  `status` varchar(30) DEFAULT 'confirmed',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_ref` (`booking_ref`),
  KEY `idx_hb_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default Franchise Admin: franchiseadmin / Admin@123*
INSERT INTO `franchise_admins` (`id`, `username`, `password`, `name`, `email`, `phone`, `status`, `created_at`, `updated_at`)
SELECT 1, 'franchiseadmin', '$2y$10$oe/Ylru3/s9Hvb8qGXDHQe8dMHlDAGrpEOKgK01BT8OWFAfBRjWV2', 'Franchise Master Admin', 'franchise@voyogo.com', '9876543210', 'active', NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `franchise_admins` WHERE `username` = 'franchiseadmin');

-- =====================================================
-- Customer Users Schema (Firebase Phone OTP Authentication)
-- =====================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `phone` VARCHAR(20) NOT NULL UNIQUE,
  `first_name` VARCHAR(100) DEFAULT NULL,
  `last_name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `firebase_uid` VARCHAR(128) DEFAULT NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Dedicated Leads & Service Enquiry Tables
-- =====================================================

CREATE TABLE IF NOT EXISTS `visa_enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `destination_country` VARCHAR(150) NOT NULL,
  `purpose_of_travel` VARCHAR(100) DEFAULT 'Tourist',
  `travel_date` VARCHAR(50) DEFAULT NULL,
  `passengers` VARCHAR(50) DEFAULT '1 Traveler',
  `has_passport` VARCHAR(10) DEFAULT 'Yes',
  `passport_number` VARCHAR(50) DEFAULT NULL,
  `source_form` VARCHAR(100) DEFAULT 'Website Form',
  `status` ENUM('New', 'Contacted', 'Documents Received', 'Processed', 'Closed') DEFAULT 'New',
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_visa_phone` (`phone`),
  INDEX `idx_visa_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cab_enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `trip_type` VARCHAR(50) DEFAULT 'One Way',
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `pickup_location` VARCHAR(255) NOT NULL,
  `drop_location` VARCHAR(255) DEFAULT NULL,
  `travel_date` VARCHAR(50) DEFAULT NULL,
  `pickup_time` VARCHAR(50) DEFAULT NULL,
  `return_date` VARCHAR(50) DEFAULT NULL,
  `return_time` VARCHAR(50) DEFAULT NULL,
  `passengers` VARCHAR(50) DEFAULT '1',
  `vehicle_type` VARCHAR(100) DEFAULT 'Sedan',
  `special_requirements` TEXT DEFAULT NULL,
  `status` ENUM('New', 'Contacted', 'Assigned', 'Completed', 'Cancelled') DEFAULT 'New',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_cab_phone` (`phone`),
  INDEX `idx_cab_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `holiday_enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `destination` VARCHAR(150) NOT NULL,
  `travel_date` VARCHAR(50) DEFAULT NULL,
  `people_count` VARCHAR(50) DEFAULT '2 People (Couple)',
  `package_name` VARCHAR(255) DEFAULT NULL,
  `budget_range` VARCHAR(100) DEFAULT NULL,
  `special_requests` TEXT DEFAULT NULL,
  `status` ENUM('New', 'Quote Sent', 'Follow-up', 'Booked', 'Lost') DEFAULT 'New',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_holiday_phone` (`phone`),
  INDEX `idx_holiday_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `forex_enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_type` VARCHAR(50) DEFAULT 'Buy Forex',
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `location_city` VARCHAR(100) DEFAULT NULL,
  `purpose_of_visit` VARCHAR(100) DEFAULT 'Tourism / Holiday',
  `currency` VARCHAR(20) DEFAULT 'USD',
  `product` VARCHAR(100) DEFAULT 'Foreign Currency Notes',
  `amount_inr` DECIMAL(12,2) DEFAULT NULL,
  `status` ENUM('New', 'Rate Confirmed', 'Payment Pending', 'Delivered', 'Cancelled') DEFAULT 'New',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_forex_phone` (`phone`),
  INDEX `idx_forex_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cruise_enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `destination` VARCHAR(150) NOT NULL,
  `travel_date` VARCHAR(50) DEFAULT NULL,
  `travelers` VARCHAR(50) DEFAULT '2 Travelers',
  `budget_per_person` VARCHAR(100) DEFAULT NULL,
  `cabin_type` VARCHAR(100) DEFAULT 'Interior Cabin',
  `cruise_line` VARCHAR(150) DEFAULT NULL,
  `special_notes` TEXT DEFAULT NULL,
  `status` ENUM('New', 'Cabin Held', 'Booked', 'Closed') DEFAULT 'New',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_cruise_phone` (`phone`),
  INDEX `idx_cruise_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



