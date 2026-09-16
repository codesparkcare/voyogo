-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: 127.0.0.1    Database: voyogo
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `franchise_admins`
--

DROP TABLE IF EXISTS `franchise_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `franchise_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `franchise_stores`
--

DROP TABLE IF EXISTS `franchise_stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `franchise_stores` (
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
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agent_code` (`agent_code`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `franchise_wallet_transactions`
--

DROP TABLE IF EXISTS `franchise_wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `franchise_wallet_transactions` (
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
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `franchise_flight_bookings`
--

DROP TABLE IF EXISTS `franchise_flight_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `franchise_flight_bookings` (
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
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_ref` (`booking_ref`),
  KEY `idx_fb_store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `franchise_hotel_bookings`
--

DROP TABLE IF EXISTS `franchise_hotel_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `franchise_hotel_bookings` (
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
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_ref` (`booking_ref`),
  KEY `idx_hb_store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

--
-- Seed Default Franchise Admin: franchiseadmin / Admin@123*
--
INSERT INTO `franchise_admins` (`id`, `username`, `password`, `name`, `email`, `phone`, `status`, `created_at`, `updated_at`)
VALUES (1, 'franchiseadmin', '$2y$10$oe/Ylru3/s9Hvb8qGXDHQe8dMHlDAGrpEOKgK01BT8OWFAfBRjWV2', 'Franchise Master Admin', 'franchise@voyogo.com', '9876543210', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-16

