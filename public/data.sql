-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: royoorders-2-db-cluster.cvgfslznkneq.us-west-2.rds.amazonaws.com
-- Generation Time: Jul 19, 2021 at 12:41 PM
-- Server version: 8.0.23
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `royo_spidbi`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addon_options`
--

CREATE TABLE `addon_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addon_id` bigint UNSIGNED NOT NULL,
  `position` smallint NOT NULL DEFAULT '1',
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `addon_options`
--

INSERT INTO `addon_options` (`id`, `title`, `addon_id`, `position`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, '100.00', NULL, NULL),
(2, 'Spicy', 2, 1, '10.00', '2021-06-09 15:26:11', '2021-06-09 15:27:15'),
(3, 'less Spicy', 2, 2, '10.00', '2021-06-09 15:26:11', '2021-06-09 15:27:15'),
(4, 'More Spicy', 2, 3, '10.00', '2021-06-09 15:26:11', '2021-06-09 15:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `addon_option_translations`
--

CREATE TABLE `addon_option_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addon_opt_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `addon_option_translations`
--

INSERT INTO `addon_option_translations` (`id`, `title`, `addon_opt_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, NULL, NULL),
(2, 'Spicy', 2, 148, NULL, '2021-06-09 15:27:15'),
(3, 'less Spicy', 3, 148, NULL, '2021-06-09 15:27:15'),
(4, 'More Spicy', 4, 148, NULL, '2021-06-09 15:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `addon_sets`
--

CREATE TABLE `addon_sets` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_select` tinyint NOT NULL DEFAULT '1',
  `max_select` tinyint NOT NULL DEFAULT '1',
  `position` smallint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `is_core` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `addon_sets`
--

INSERT INTO `addon_sets` (`id`, `title`, `min_select`, `max_select`, `position`, `status`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, 1, 1, 1, NULL, NULL, NULL),
(2, 'Chicken Tikka Masala', 1, 1, 1, 1, 1, 3, '2021-06-09 15:26:11', '2021-06-09 15:26:11');

-- --------------------------------------------------------

--
-- Table structure for table `addon_set_translations`
--

CREATE TABLE `addon_set_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addon_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `addon_set_translations`
--

INSERT INTO `addon_set_translations` (`id`, `title`, `addon_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, NULL, NULL),
(2, 'Chicken Tikka Masala', 2, 148, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@cbl.com', NULL, '2021-06-28 14:08:12', '$2y$10$FRkzDunyWB4yVBFtPTw.k.RqZulDuoVKLnEWoq2A5LTPaflruwYKu', 'W5IyCBlGQZ', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `models` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_stylings`
--

CREATE TABLE `app_stylings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint DEFAULT NULL COMMENT '1-Text, 2-Option, 3-Option Images, 4-Color',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_stylings`
--

INSERT INTO `app_stylings` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Regular Font', 2, NULL, NULL),
(2, 'Medium Font', 2, NULL, NULL),
(3, 'Bold Font', 2, NULL, NULL),
(4, 'Primary Color', 4, NULL, NULL),
(5, 'Secondary Color', 4, NULL, NULL),
(6, 'Tertiary Color', 4, NULL, NULL),
(7, 'Tab Bar Style', 3, NULL, NULL),
(8, 'Home Page Style', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_styling_options`
--

CREATE TABLE `app_styling_options` (
  `id` bigint UNSIGNED NOT NULL,
  `app_styling_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_selected` tinyint NOT NULL DEFAULT '1' COMMENT '1-yes, 2-no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `template_id` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `app_styling_options`
--

INSERT INTO `app_styling_options` (`id`, `app_styling_id`, `name`, `image`, `is_selected`, `created_at`, `updated_at`, `template_id`) VALUES
(1, 1, 'CircularStd-Book', NULL, 1, NULL, NULL, NULL),
(2, 2, 'CircularStd-Medium', NULL, 1, NULL, NULL, NULL),
(3, 3, 'CircularStd-Bold', NULL, 1, NULL, NULL, NULL),
(4, 4, '#211A82', NULL, 1, NULL, '2021-07-19 10:08:12', NULL),
(5, 5, '#fff', NULL, 1, NULL, NULL, NULL),
(6, 6, '#fff', NULL, 1, NULL, NULL, NULL),
(7, 7, 'Tab 1', 'bar.png', 1, NULL, NULL, 1),
(8, 7, 'Tab 2', 'bar_two.png', 0, NULL, NULL, 2),
(9, 7, 'Tab 3', 'bar_three.png', 0, NULL, NULL, 3),
(10, 8, 'Home Page 1', 'home.png', 1, NULL, NULL, 1),
(11, 8, 'Home Page 2', 'home_two.png', 0, NULL, NULL, 2),
(12, 8, 'Home Page 3', 'home_three.png', 0, NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validity_on` tinyint NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
  `sorting` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - active, 0 - pending, 2 - blocked',
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `redirect_category_id` bigint UNSIGNED DEFAULT NULL,
  `redirect_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Food', NULL, 'banner/FHngSUM9VABhQAmmKewUMpkRDZ1hlujuGF8rXu0n.jpg', 1, 1, 1, '2021-07-16 18:31:00', '2021-08-31 12:00:00', NULL, NULL, NULL, NULL, '2021-07-16 13:02:13'),
(2, 'Food', NULL, 'banner/llsOfBXwFMMgkJ2iruH2SzjJvf8788zDs3JiPrhX.jpg', 1, 2, 1, '2021-07-16 18:32:00', '2021-09-29 12:00:00', NULL, NULL, NULL, NULL, '2021-07-16 13:02:37'),
(3, 'Food', NULL, 'banner/i9Gb1E8CVlTL1eMn8wL8Jwm1J6XuyhNNoidEOdM2.jpg', 1, 3, 1, '2021-07-16 18:32:00', '2021-11-30 12:00:00', NULL, NULL, NULL, NULL, '2021-07-16 13:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `blocked_tokens`
--

CREATE TABLE `blocked_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired` tinyint NOT NULL DEFAULT '0' COMMENT '1 yes, 0 no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` smallint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `title`, `image`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'J.Crew', 'default/default_image.png', 1, 2, NULL, '2021-07-16 12:59:39'),
(2, 'Allform', 'default/default_image.png', 2, 2, NULL, '2021-07-16 12:59:43'),
(3, 'EyeBuyDirect', 'default/default_image.png', 3, 2, NULL, '2021-07-16 12:59:47'),
(4, 'In Pictures', 'default/default_image.png', 4, 2, NULL, '2021-07-16 12:59:35'),
(5, 'Lavazza', 'brand/DyQVYP2Zbsmgek0P7pNEqL09OOJ63kIyEVS3BqIg.gif', 5, 1, '2021-07-16 12:59:20', '2021-07-16 12:59:20'),
(6, 'The Flying Squirrel', 'brand/46i8V0GdeFhtnq9kRwomac8UtdtDxl2otQxBfbIB.gif', 6, 1, '2021-07-16 13:00:15', '2021-07-16 13:00:15'),
(7, 'Schreiber', 'brand/CtGlMRH0GSsxop7JOUUNLKNYx19m6Tga4UyK73M0.jpg', 7, 1, '2021-07-16 13:00:33', '2021-07-16 13:00:33'),
(8, 'Green Mountain', 'brand/LVkP8VhpuwdzOFp5syTjo9SKK2AYityGRTHlUQJP.jpg', 8, 1, '2021-07-16 13:00:55', '2021-07-16 13:00:55'),
(9, 'Blue Tokai', 'brand/4faIztUTz7eypGepNeQEIFdACEss2lDNQYoToHET.png', 9, 1, '2021-07-16 13:01:12', '2021-07-16 13:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `brand_categories`
--

CREATE TABLE `brand_categories` (
  `brand_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `brand_categories`
--

INSERT INTO `brand_categories` (`brand_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, NULL),
(2, 11, NULL, NULL),
(3, 11, NULL, NULL),
(4, 11, NULL, NULL),
(5, 3, NULL, NULL),
(6, 3, NULL, NULL),
(7, 3, NULL, NULL),
(8, 3, NULL, NULL),
(9, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brand_translations`
--

CREATE TABLE `brand_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `brand_translations`
--

INSERT INTO `brand_translations` (`id`, `title`, `brand_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'J.Crew', 1, 1, NULL, NULL),
(2, 'Allform', 2, 1, NULL, NULL),
(3, 'EyeBuyDirect', 3, 1, NULL, NULL),
(4, 'In Pictures', 4, 1, NULL, NULL),
(5, 'Lavazza', 5, 148, NULL, NULL),
(6, 'Lavazza', 5, 2, NULL, NULL),
(7, 'Lavazza', 5, 4, NULL, NULL),
(8, 'Lavazza', 5, 59, NULL, NULL),
(9, 'The Flying Squirrel', 6, 148, NULL, NULL),
(10, 'The Flying Squirrel', 6, 2, NULL, NULL),
(11, 'The Flying Squirrel', 6, 4, NULL, NULL),
(12, 'The Flying Squirrel', 6, 59, NULL, NULL),
(13, 'Schreiber', 7, 148, NULL, NULL),
(14, 'Schreiber', 7, 2, NULL, NULL),
(15, 'Schreiber', 7, 4, NULL, NULL),
(16, 'Schreiber', 7, 59, NULL, NULL),
(17, 'Green Mountain', 8, 148, NULL, NULL),
(18, 'Green Mountain', 8, 2, NULL, NULL),
(19, 'Green Mountain', 8, 4, NULL, NULL),
(20, 'Green Mountain', 8, 59, NULL, NULL),
(21, 'Blue Tokai', 9, 148, NULL, NULL),
(22, 'Blue Tokai', 9, 2, NULL, NULL),
(23, 'Blue Tokai', 9, 4, NULL, NULL),
(24, 'Blue Tokai', 9, 59, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `unique_identifier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Active, 1-Blocked, 2-Deleted',
  `is_gift` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Yes, 1-No',
  `item_count` int DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `unique_identifier`, `user_id`, `created_by`, `status`, `is_gift`, `item_count`, `currency_id`, `created_at`, `updated_at`) VALUES
(1, '', 6, NULL, '1', '', 2, 98, '2021-07-19 12:02:05', '2021-07-19 12:03:07');

-- --------------------------------------------------------

--
-- Table structure for table `cart_addons`
--

CREATE TABLE `cart_addons` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `cart_product_id` bigint UNSIGNED NOT NULL,
  `addon_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `cart_coupons`
--

CREATE TABLE `cart_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `cart_products`
--

CREATE TABLE `cart_products` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL COMMENT '0-Active, 1-Blocked, 2-Deleted',
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `is_tax_applied` tinyint NOT NULL COMMENT '0-Yes, 1-No',
  `tax_rate_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `luxury_option_id` bigint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `cart_product_prescriptions`
--

CREATE TABLE `cart_product_prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `prescription` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_visible` tinyint DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `position` smallint NOT NULL DEFAULT '1' COMMENT 'for same position, display asc order',
  `is_core` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `can_add_products` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'only products name, product with description',
  `warning_page_id` bigint DEFAULT NULL,
  `template_type_id` bigint DEFAULT NULL,
  `warning_page_design` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `show_wishlist` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `icon`, `slug`, `type_id`, `image`, `is_visible`, `status`, `position`, `is_core`, `can_add_products`, `parent_id`, `vendor_id`, `client_code`, `display_mode`, `warning_page_id`, `template_type_id`, `warning_page_design`, `created_at`, `updated_at`, `deleted_at`, `show_wishlist`) VALUES
(1, NULL, 'root', 3, NULL, 0, 1, 1, 1, 0, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, NULL, 'delivery', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, 1, 1, NULL, NULL, '2021-07-19 11:30:52', NULL, 1),
(3, NULL, 'restaurant', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, 1, 1, NULL, NULL, '2021-07-19 12:01:25', NULL, 1),
(4, NULL, 'supermarket', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, 1, 1, NULL, NULL, '2021-07-19 11:32:00', NULL, 1),
(5, NULL, 'pharmacy', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, 1, 1, NULL, NULL, '2021-07-19 11:34:25', NULL, 1),
(6, NULL, 'send-something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-06-28 13:34:01', NULL, 1),
(7, NULL, 'buy-something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-06-28 13:34:01', NULL, 1),
(8, NULL, 'vegetables', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-06-28 13:34:01', NULL, 1),
(9, NULL, 'fruits', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-06-28 13:34:01', NULL, 1),
(10, NULL, 'dairy-and-eggs', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-06-28 13:34:01', NULL, 1),
(11, NULL, 'e-commerce', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, 1, 1, NULL, NULL, '2021-07-19 11:34:00', NULL, 1),
(12, NULL, 'cloth', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, 1, 1, NULL, NULL, '2021-07-19 11:33:28', NULL, 1),
(22, 'category/icon/FxpierBGDJeMq3tEX2jFbRj8tG8FLL9jWU1HAvhL.jpg', 'italian', 1, 'category/image/WggBiEpl4DPjgEQRs85tB1nvRszVlaUtgxzDOt3l.jpg', 1, 1, 1, 1, 1, 1, NULL, '4a7329', NULL, 1, 1, NULL, '2021-07-19 12:31:31', '2021-07-19 12:31:31', NULL, 1),
(23, 'category/icon/XtFRyKiKk7TzWIHtjANpw6mq9PXUGB9s1uN8KXOW.jpg', 'continental', 1, 'category/image/2yLxT1ck3wUi7DzAS45gkMjm9XqzpqiJ2pLagciK.jpg', 1, 1, 1, 1, 1, 1, NULL, '4a7329', NULL, 1, 1, NULL, '2021-07-19 12:36:12', '2021-07-19 12:36:12', NULL, 1),
(24, 'category/icon/0gveEWHJBjEuSa7O1dNZr1caRL4EPbmqVzIskhfL.jpg', 'chinese', 1, 'category/image/QgdmwpDu67WaPJrn19vUZBPOmxo8vwtsGJvuYpgO.jpg', 1, 1, 1, 1, 1, 1, NULL, '4a7329', NULL, 1, 1, NULL, '2021-07-19 12:40:06', '2021-07-19 12:40:06', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_histories`
--

CREATE TABLE `category_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `action` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'add' COMMENT 'add, update, delete, block, active',
  `updater_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SuperAdmin' COMMENT 'SuperAdmin, Admin, Seller',
  `update_id` bigint UNSIGNED DEFAULT NULL,
  `client_code` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `category_histories`
--

INSERT INTO `category_histories` (`id`, `category_id`, `action`, `updater_role`, `update_id`, `client_code`, `created_at`, `updated_at`) VALUES
(1, 5, 'Update', 'Admin', 82, 4, '2021-06-08 23:35:38', '2021-06-08 23:35:38'),
(2, 5, 'Update', 'Admin', 82, 4, '2021-06-08 23:35:47', '2021-06-08 23:35:47'),
(7, 2, 'Update', 'Admin', 1, 4, '2021-07-19 11:30:52', '2021-07-19 11:30:52'),
(8, 3, 'Update', 'Admin', 1, 4, '2021-07-19 11:31:22', '2021-07-19 11:31:22'),
(9, 4, 'Update', 'Admin', 1, 4, '2021-07-19 11:32:00', '2021-07-19 11:32:00'),
(11, 12, 'Update', 'Admin', 1, 4, '2021-07-19 11:32:46', '2021-07-19 11:32:46'),
(12, 12, 'Update', 'Admin', 1, 4, '2021-07-19 11:33:28', '2021-07-19 11:33:28'),
(13, 11, 'Update', 'Admin', 1, 4, '2021-07-19 11:34:01', '2021-07-19 11:34:01'),
(14, 5, 'Update', 'Admin', 1, 4, '2021-07-19 11:34:25', '2021-07-19 11:34:25'),
(17, 3, 'Update', 'Admin', 1, 4, '2021-07-19 12:01:25', '2021-07-19 12:01:25'),
(21, 22, 'Add', 'Admin', 1, 4, '2021-07-19 12:31:31', '2021-07-19 12:31:31'),
(22, 23, 'Add', 'Admin', 1, 4, '2021-07-19 12:36:12', '2021-07-19 12:36:12'),
(23, 24, 'Add', 'Admin', 1, 4, '2021-07-19 12:40:06', '2021-07-19 12:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `category_tags`
--

CREATE TABLE `category_tags` (
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `tag` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `category_translations`
--

CREATE TABLE `category_translations` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trans-slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `category_translations`
--

INSERT INTO `category_translations` (`id`, `name`, `trans-slug`, `meta_title`, `meta_description`, `meta_keywords`, `category_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'root', '', 'root', '', '', 1, 1, NULL, NULL),
(2, 'Delivery', '', 'Delivery', '', '', 2, 1, NULL, NULL),
(3, 'Restaurant', '', 'Restaurant', '', '', 3, 1, NULL, NULL),
(4, 'Supermarket', '', 'Supermarket', '', '', 4, 1, NULL, NULL),
(5, 'Pharmacy', '', 'Pharmacy', '', '', 5, 1, NULL, NULL),
(6, 'Send something', '', 'Send something', '', '', 6, 1, NULL, NULL),
(7, 'Buy something', '', 'Buy something', '', '', 7, 1, NULL, NULL),
(8, 'Vegetables', '', 'Vegetables', '', '', 8, 1, NULL, NULL),
(9, 'Fruits', '', 'Fruits', '', '', 9, 1, NULL, NULL),
(10, 'Dairy and Eggs', '', 'Dairy and Eggs', '', '', 10, 1, NULL, NULL),
(11, 'E-Commerce', '', 'E-Commerce', '', '', 11, 1, NULL, NULL),
(12, 'Cloth', '', 'Cloth', '', '', 12, 1, NULL, NULL),
(13, 'Farmacia', NULL, NULL, NULL, NULL, 5, 148, '2021-06-08 23:35:38', '2021-06-08 23:35:38'),
(27, 'Delivery', NULL, NULL, NULL, NULL, 2, 148, '2021-07-19 11:30:52', '2021-07-19 11:30:52'),
(28, 'Delivery', NULL, NULL, NULL, NULL, 2, 2, '2021-07-19 11:30:52', '2021-07-19 11:30:52'),
(29, 'Delivery', NULL, NULL, NULL, NULL, 2, 4, '2021-07-19 11:30:52', '2021-07-19 11:30:52'),
(30, 'Delivery', NULL, NULL, NULL, NULL, 2, 59, '2021-07-19 11:30:52', '2021-07-19 11:30:52'),
(31, 'restaurante', NULL, NULL, NULL, NULL, 3, 148, '2021-07-19 11:31:22', '2021-07-19 12:01:25'),
(32, 'Restaurant', NULL, NULL, NULL, NULL, 3, 2, '2021-07-19 11:31:22', '2021-07-19 11:31:22'),
(33, 'Restaurant', NULL, NULL, NULL, NULL, 3, 4, '2021-07-19 11:31:22', '2021-07-19 11:31:22'),
(34, 'Restaurant', NULL, NULL, NULL, NULL, 3, 59, '2021-07-19 11:31:22', '2021-07-19 11:31:22'),
(35, 'Supermarket', NULL, NULL, NULL, NULL, 4, 148, '2021-07-19 11:32:00', '2021-07-19 11:32:00'),
(36, 'Supermarket', NULL, NULL, NULL, NULL, 4, 2, '2021-07-19 11:32:00', '2021-07-19 11:32:00'),
(37, 'Supermarket', NULL, NULL, NULL, NULL, 4, 4, '2021-07-19 11:32:00', '2021-07-19 11:32:00'),
(38, 'Supermarket', NULL, NULL, NULL, NULL, 4, 59, '2021-07-19 11:32:00', '2021-07-19 11:32:00'),
(43, 'Cloth', NULL, NULL, NULL, NULL, 12, 148, '2021-07-19 11:32:46', '2021-07-19 11:33:28'),
(44, 'Cloth', NULL, NULL, NULL, NULL, 12, 2, '2021-07-19 11:32:46', '2021-07-19 11:33:28'),
(45, 'Cloth', NULL, NULL, NULL, NULL, 12, 4, '2021-07-19 11:32:46', '2021-07-19 11:33:28'),
(46, 'Cloth', NULL, NULL, NULL, NULL, 12, 59, '2021-07-19 11:32:46', '2021-07-19 11:33:28'),
(47, 'E-Commerce', NULL, NULL, NULL, NULL, 11, 148, '2021-07-19 11:34:00', '2021-07-19 11:34:00'),
(48, 'E-Commerce', NULL, NULL, NULL, NULL, 11, 2, '2021-07-19 11:34:01', '2021-07-19 11:34:01'),
(49, 'E-Commerce', NULL, NULL, NULL, NULL, 11, 4, '2021-07-19 11:34:01', '2021-07-19 11:34:01'),
(50, 'E-Commerce', NULL, NULL, NULL, NULL, 11, 59, '2021-07-19 11:34:01', '2021-07-19 11:34:01'),
(51, NULL, NULL, NULL, NULL, NULL, 5, 2, '2021-07-19 11:34:25', '2021-07-19 11:34:25'),
(52, NULL, NULL, NULL, NULL, NULL, 5, 4, '2021-07-19 11:34:25', '2021-07-19 11:34:25'),
(53, NULL, NULL, NULL, NULL, NULL, 5, 59, '2021-07-19 11:34:25', '2021-07-19 11:34:25'),
(71, 'Italiano', NULL, 'Italiano', NULL, NULL, 22, 148, '2021-07-19 12:31:31', '2021-07-19 12:31:31'),
(72, 'Italian', NULL, 'Italian', NULL, NULL, 22, 1, '2021-07-19 12:31:31', '2021-07-19 12:31:31'),
(73, NULL, NULL, NULL, NULL, NULL, 22, 2, '2021-07-19 12:31:31', '2021-07-19 12:31:31'),
(74, NULL, NULL, NULL, NULL, NULL, 22, 4, '2021-07-19 12:31:31', '2021-07-19 12:31:31'),
(75, NULL, NULL, NULL, NULL, NULL, 22, 59, '2021-07-19 12:31:31', '2021-07-19 12:31:31'),
(76, 'Continental-ab', NULL, 'continental-ab', NULL, NULL, 23, 148, '2021-07-19 12:36:12', '2021-07-19 12:36:12'),
(77, 'Continental', NULL, 'continental', NULL, NULL, 23, 1, '2021-07-19 12:36:12', '2021-07-19 12:36:12'),
(78, NULL, NULL, NULL, NULL, NULL, 23, 2, '2021-07-19 12:36:12', '2021-07-19 12:36:12'),
(79, NULL, NULL, NULL, NULL, NULL, 23, 4, '2021-07-19 12:36:12', '2021-07-19 12:36:12'),
(80, NULL, NULL, NULL, NULL, NULL, 23, 59, '2021-07-19 12:36:12', '2021-07-19 12:36:12'),
(81, 'Chino', NULL, 'chino', NULL, NULL, 24, 148, '2021-07-19 12:40:06', '2021-07-19 12:40:06'),
(82, 'Chinese', NULL, 'chinese', NULL, NULL, 24, 1, '2021-07-19 12:40:06', '2021-07-19 12:40:06'),
(83, NULL, NULL, NULL, NULL, NULL, 24, 2, '2021-07-19 12:40:06', '2021-07-19 12:40:06'),
(84, NULL, NULL, NULL, NULL, NULL, 24, 4, '2021-07-19 12:40:06', '2021-07-19 12:40:06'),
(85, NULL, NULL, NULL, NULL, NULL, 24, 59, '2021-07-19 12:40:06', '2021-07-19 12:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `celebrities`
--

CREATE TABLE `celebrities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - pending, 1 - active, 2 - inactive, 3 - deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `celebrity_brands`
--

CREATE TABLE `celebrity_brands` (
  `celebrity_id` bigint UNSIGNED DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encpass` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `timezone` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_domain` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_domain` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `is_blocked` tinyint NOT NULL DEFAULT '0',
  `database_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 for active, 0 for pending, 2 for blocked, 3 for inactive',
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `database_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_port` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_superadmin` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone_number`, `password`, `encpass`, `country_id`, `timezone`, `custom_domain`, `sub_domain`, `is_deleted`, `is_blocked`, `database_path`, `database_name`, `database_username`, `database_password`, `logo`, `company_name`, `company_address`, `language_id`, `status`, `code`, `created_at`, `updated_at`, `database_host`, `database_port`, `is_superadmin`) VALUES
(1, 'Ludovic', 'admin@spidbi.com', '6181381055', '$2y$10$UJLt17eYSWwJ0CmRyboLKOXfPev.jBaKndffwSVHF30xwfDqi7aQi', NULL, 1, 'America/Lima', 'spidbi', NULL, 0, 0, '', 'spidbi', 'cbladmin', 'aQ2hvKYLH4LKWmrA', 'Clientlogo/60c1a58ca1567.png', 'SPIDBI', 'Spidbi, Mx', NULL, 0, '4a7329', NULL, '2021-06-10 05:39:24', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `client_currencies`
--

CREATE TABLE `client_currencies` (
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `is_primary` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `doller_compare` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `client_currencies`
--

INSERT INTO `client_currencies` (`client_code`, `currency_id`, `is_primary`, `doller_compare`, `created_at`, `updated_at`) VALUES
('4a7329', 98, 1, '1.00', NULL, '2021-07-19 12:32:27'),
('4a7329', 147, 0, '20.00', NULL, '2021-07-19 12:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `client_languages`
--

CREATE TABLE `client_languages` (
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `is_primary` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `is_active` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `client_languages`
--

INSERT INTO `client_languages` (`client_code`, `language_id`, `is_primary`, `is_active`, `created_at`, `updated_at`) VALUES
('4a7329', 1, 0, 1, NULL, '2021-07-19 12:30:08'),
('4a7329', 148, 1, 1, NULL, '2021-07-19 12:32:27'),
('4a7329', 2, 0, 1, '2021-06-09 15:48:11', '2021-07-19 12:30:08'),
('4a7329', 4, 0, 1, '2021-06-09 15:48:11', '2021-07-19 12:30:08'),
('4a7329', 59, 0, 1, '2021-06-09 15:48:11', '2021-07-19 12:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `client_preferences`
--

CREATE TABLE `client_preferences` (
  `id` bigint UNSIGNED NOT NULL,
  `rating_check` tinyint DEFAULT '0',
  `enquire_mode` tinyint DEFAULT '0',
  `cart_enable` tinyint DEFAULT '0',
  `client_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_admin` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light' COMMENT 'Light, Dark',
  `distance_unit` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'metric' COMMENT 'metric, imperial',
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `date_format` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y-m-d',
  `time_format` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H:i',
  `fb_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `fb_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `twitter_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `google_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_login` tinyint NOT NULL DEFAULT '0' COMMENT '1 - enable, 0 - disable',
  `apple_client_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_client_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_client_url` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Default_location_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Default_latitude` decimal(15,12) NOT NULL DEFAULT '0.000000000000',
  `Default_longitude` decimal(16,12) NOT NULL DEFAULT '0.000000000000',
  `map_provider` bigint UNSIGNED DEFAULT NULL,
  `map_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_provider` bigint UNSIGNED DEFAULT NULL,
  `sms_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_email` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `verify_phone` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `web_template_id` bigint UNSIGNED DEFAULT NULL,
  `app_template_id` bigint UNSIGNED DEFAULT NULL,
  `personal_access_token_v1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_access_token_v2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'smtp',
  `mail_driver` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_host` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_port` smallint DEFAULT NULL,
  `mail_username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_encryption` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_hyperlocal` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `need_delivery_service` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `need_dispacher_ride` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `delivery_service_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispatcher_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `celebrity_check` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `delivery_service_key_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_service_key_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reffered_by_amount` decimal(8,2) DEFAULT NULL,
  `reffered_to_amount` decimal(8,2) DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pharmacy_check` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `dinein_check` tinyint DEFAULT '1' COMMENT '0-No, 1-Yes',
  `takeaway_check` tinyint DEFAULT '1' COMMENT '0-No, 1-Yes',
  `delivery_check` tinyint DEFAULT '1' COMMENT '0-No, 1-Yes',
  `pickup_delivery_service_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_delivery_service_key_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_delivery_service_key_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `client_preferences`
--

INSERT INTO `client_preferences` (`id`, `rating_check`, `enquire_mode`, `cart_enable`, `client_code`, `theme_admin`, `distance_unit`, `currency_id`, `language_id`, `date_format`, `time_format`, `fb_login`, `fb_client_id`, `fb_client_secret`, `fb_client_url`, `twitter_login`, `twitter_client_id`, `twitter_client_secret`, `twitter_client_url`, `google_login`, `google_client_id`, `google_client_secret`, `google_client_url`, `apple_login`, `apple_client_id`, `apple_client_secret`, `apple_client_url`, `Default_location_name`, `Default_latitude`, `Default_longitude`, `map_provider`, `map_key`, `map_secret`, `sms_provider`, `sms_key`, `sms_secret`, `sms_from`, `verify_email`, `verify_phone`, `web_template_id`, `app_template_id`, `personal_access_token_v1`, `personal_access_token_v2`, `mail_type`, `mail_driver`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from`, `is_hyperlocal`, `need_delivery_service`, `need_dispacher_ride`, `delivery_service_key`, `primary_color`, `secondary_color`, `dispatcher_key`, `created_at`, `updated_at`, `celebrity_check`, `delivery_service_key_url`, `delivery_service_key_code`, `reffered_by_amount`, `reffered_to_amount`, `favicon`, `web_color`, `pharmacy_check`, `dinein_check`, `takeaway_check`, `delivery_check`, `pickup_delivery_service_key`, `pickup_delivery_service_key_url`, `pickup_delivery_service_key_code`) VALUES
(1, 1, 0, 1, '4a7329', 'light', 'metric', NULL, NULL, 'YYYY-MM-DD', '24', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'Mexico', '23.634501000000', '-102.552784000000', 1, 'AIzaSyCujWIofFxUtLQMh71jw2xzDO0Wxi7QRhg', NULL, 1, NULL, NULL, NULL, 0, 0, 1, 2, NULL, NULL, 'smtp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, '#32B5FC', '#41A2E6', NULL, NULL, '2021-07-19 10:07:55', 0, NULL, NULL, '0.00', '0.00', NULL, '#392B7F', 0, 0, 0, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms`
--

CREATE TABLE `cms` (
  `id` bigint UNSIGNED NOT NULL,
  `sorting` smallint NOT NULL DEFAULT '1',
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html_content` longtext COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` longtext COLLATE utf8mb4_unicode_ci,
  `meta_keywords` longtext COLLATE utf8mb4_unicode_ci,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - active, 0 - pending, 2 - blocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(56) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nicename` varchar(56) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso3` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numcode` int DEFAULT NULL,
  `phonecode` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`, `created_at`, `updated_at`) VALUES
(1, 'US', 'United States', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'CA', 'Canada', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'AF', 'Afghanistan', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'AL', 'Albania', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'DZ', 'Algeria', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'DS', 'American Samoa', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'AD', 'Andorra', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'AO', 'Angola', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'AI', 'Anguilla', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'AQ', 'Antarctica', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'AG', 'Antigua and/or Barbuda', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'AR', 'Argentina', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'AM', 'Armenia', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'AW', 'Aruba', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'AU', 'Australia', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'AT', 'Austria', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'AZ', 'Azerbaijan', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'BS', 'Bahamas', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'BH', 'Bahrain', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'BD', 'Bangladesh', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'BB', 'Barbados', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'BY', 'Belarus', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'BE', 'Belgium', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'BZ', 'Belize', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'BJ', 'Benin', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'BM', 'Bermuda', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'BT', 'Bhutan', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'BO', 'Bolivia', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'BA', 'Bosnia and Herzegovina', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'BW', 'Botswana', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'BV', 'Bouvet Island', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'BR', 'Brazil', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'IO', 'British lndian Ocean Territory', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'BN', 'Brunei Darussalam', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'BG', 'Bulgaria', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'BF', 'Burkina Faso', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'BI', 'Burundi', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'KH', 'Cambodia', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'CM', 'Cameroon', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'CV', 'Cape Verde', NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'KY', 'Cayman Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'CF', 'Central African Republic', NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'TD', 'Chad', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'CL', 'Chile', NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'CN', 'China', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'CX', 'Christmas Island', NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'CC', 'Cocos (Keeling) Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'CO', 'Colombia', NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'KM', 'Comoros', NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'CG', 'Congo', NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'CK', 'Cook Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(52, 'CR', 'Costa Rica', NULL, NULL, NULL, NULL, NULL, NULL),
(53, 'HR', 'Croatia (Hrvatska)', NULL, NULL, NULL, NULL, NULL, NULL),
(54, 'CU', 'Cuba', NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'CY', 'Cyprus', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 'CZ', 'Czech Republic', NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'DK', 'Denmark', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 'DJ', 'Djibouti', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 'DM', 'Dominica', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'DO', 'Dominican Republic', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 'TP', 'East Timor', NULL, NULL, NULL, NULL, NULL, NULL),
(62, 'EC', 'Ecudaor', NULL, NULL, NULL, NULL, NULL, NULL),
(63, 'EG', 'Egypt', NULL, NULL, NULL, NULL, NULL, NULL),
(64, 'SV', 'El Salvador', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 'GQ', 'Equatorial Guinea', NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'ER', 'Eritrea', NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'EE', 'Estonia', NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'ET', 'Ethiopia', NULL, NULL, NULL, NULL, NULL, NULL),
(69, 'FK', 'Falkland Islands (Malvinas)', NULL, NULL, NULL, NULL, NULL, NULL),
(70, 'FO', 'Faroe Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'FJ', 'Fiji', NULL, NULL, NULL, NULL, NULL, NULL),
(72, 'FI', 'Finland', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 'FR', 'France', NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'FX', 'France, Metropolitan', NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'GF', 'French Guiana', NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'PF', 'French Polynesia', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 'TF', 'French Southern Territories', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 'GA', 'Gabon', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 'GM', 'Gambia', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 'GE', 'Georgia', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 'DE', 'Germany', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 'GH', 'Ghana', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 'GI', 'Gibraltar', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 'GR', 'Greece', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 'GL', 'Greenland', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 'GD', 'Grenada', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 'GP', 'Guadeloupe', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 'GU', 'Guam', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 'GT', 'Guatemala', NULL, NULL, NULL, NULL, NULL, NULL),
(90, 'GN', 'Guinea', NULL, NULL, NULL, NULL, NULL, NULL),
(91, 'GW', 'Guinea-Bissau', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 'GY', 'Guyana', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 'HT', 'Haiti', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 'HM', 'Heard and Mc Donald Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(95, 'HN', 'Honduras', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 'HK', 'Hong Kong', NULL, NULL, NULL, NULL, NULL, NULL),
(97, 'HU', 'Hungary', NULL, NULL, NULL, NULL, NULL, NULL),
(98, 'IS', 'Iceland', NULL, NULL, NULL, NULL, NULL, NULL),
(99, 'IN', 'India', NULL, NULL, NULL, NULL, NULL, NULL),
(100, 'ID', 'Indonesia', NULL, NULL, NULL, NULL, NULL, NULL),
(101, 'IR', 'Iran (Islamic Republic of)', NULL, NULL, NULL, NULL, NULL, NULL),
(102, 'IQ', 'Iraq', NULL, NULL, NULL, NULL, NULL, NULL),
(103, 'IE', 'Ireland', NULL, NULL, NULL, NULL, NULL, NULL),
(104, 'IL', 'Israel', NULL, NULL, NULL, NULL, NULL, NULL),
(105, 'IT', 'Italy', NULL, NULL, NULL, NULL, NULL, NULL),
(106, 'CI', 'Ivory Coast', NULL, NULL, NULL, NULL, NULL, NULL),
(107, 'JM', 'Jamaica', NULL, NULL, NULL, NULL, NULL, NULL),
(108, 'JP', 'Japan', NULL, NULL, NULL, NULL, NULL, NULL),
(109, 'JO', 'Jordan', NULL, NULL, NULL, NULL, NULL, NULL),
(110, 'KZ', 'Kazakhstan', NULL, NULL, NULL, NULL, NULL, NULL),
(111, 'KE', 'Kenya', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 'KI', 'Kiribati', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 'KP', 'Korea, Democratic People\'s Republic of', NULL, NULL, NULL, NULL, NULL, NULL),
(114, 'KR', 'Korea, Republic of', NULL, NULL, NULL, NULL, NULL, NULL),
(115, 'KW', 'Kuwait', NULL, NULL, NULL, NULL, NULL, NULL),
(116, 'KG', 'Kyrgyzstan', NULL, NULL, NULL, NULL, NULL, NULL),
(117, 'LA', 'Lao People\'s Democratic Republic', NULL, NULL, NULL, NULL, NULL, NULL),
(118, 'LV', 'Latvia', NULL, NULL, NULL, NULL, NULL, NULL),
(119, 'LB', 'Lebanon', NULL, NULL, NULL, NULL, NULL, NULL),
(120, 'LS', 'Lesotho', NULL, NULL, NULL, NULL, NULL, NULL),
(121, 'LR', 'Liberia', NULL, NULL, NULL, NULL, NULL, NULL),
(122, 'LY', 'Libyan Arab Jamahiriya', NULL, NULL, NULL, NULL, NULL, NULL),
(123, 'LI', 'Liechtenstein', NULL, NULL, NULL, NULL, NULL, NULL),
(124, 'LT', 'Lithuania', NULL, NULL, NULL, NULL, NULL, NULL),
(125, 'LU', 'Luxembourg', NULL, NULL, NULL, NULL, NULL, NULL),
(126, 'MO', 'Macau', NULL, NULL, NULL, NULL, NULL, NULL),
(127, 'MK', 'Macedonia', NULL, NULL, NULL, NULL, NULL, NULL),
(128, 'MG', 'Madagascar', NULL, NULL, NULL, NULL, NULL, NULL),
(129, 'MW', 'Malawi', NULL, NULL, NULL, NULL, NULL, NULL),
(130, 'MY', 'Malaysia', NULL, NULL, NULL, NULL, NULL, NULL),
(131, 'MV', 'Maldives', NULL, NULL, NULL, NULL, NULL, NULL),
(132, 'ML', 'Mali', NULL, NULL, NULL, NULL, NULL, NULL),
(133, 'MT', 'Malta', NULL, NULL, NULL, NULL, NULL, NULL),
(134, 'MH', 'Marshall Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(135, 'MQ', 'Martinique', NULL, NULL, NULL, NULL, NULL, NULL),
(136, 'MR', 'Mauritania', NULL, NULL, NULL, NULL, NULL, NULL),
(137, 'MU', 'Mauritius', NULL, NULL, NULL, NULL, NULL, NULL),
(138, 'TY', 'Mayotte', NULL, NULL, NULL, NULL, NULL, NULL),
(139, 'MX', 'Mexico', NULL, NULL, NULL, NULL, NULL, NULL),
(140, 'FM', 'Micronesia, Federated States of', NULL, NULL, NULL, NULL, NULL, NULL),
(141, 'MD', 'Moldova, Republic of', NULL, NULL, NULL, NULL, NULL, NULL),
(142, 'MC', 'Monaco', NULL, NULL, NULL, NULL, NULL, NULL),
(143, 'MN', 'Mongolia', NULL, NULL, NULL, NULL, NULL, NULL),
(144, 'MS', 'Montserrat', NULL, NULL, NULL, NULL, NULL, NULL),
(145, 'MA', 'Morocco', NULL, NULL, NULL, NULL, NULL, NULL),
(146, 'MZ', 'Mozambique', NULL, NULL, NULL, NULL, NULL, NULL),
(147, 'MM', 'Myanmar', NULL, NULL, NULL, NULL, NULL, NULL),
(148, 'NA', 'Namibia', NULL, NULL, NULL, NULL, NULL, NULL),
(149, 'NR', 'Nauru', NULL, NULL, NULL, NULL, NULL, NULL),
(150, 'NP', 'Nepal', NULL, NULL, NULL, NULL, NULL, NULL),
(151, 'NL', 'Netherlands', NULL, NULL, NULL, NULL, NULL, NULL),
(152, 'AN', 'Netherlands Antilles', NULL, NULL, NULL, NULL, NULL, NULL),
(153, 'NC', 'New Caledonia', NULL, NULL, NULL, NULL, NULL, NULL),
(154, 'NZ', 'New Zealand', NULL, NULL, NULL, NULL, NULL, NULL),
(155, 'NI', 'Nicaragua', NULL, NULL, NULL, NULL, NULL, NULL),
(156, 'NE', 'Niger', NULL, NULL, NULL, NULL, NULL, NULL),
(157, 'NG', 'Nigeria', NULL, NULL, NULL, NULL, NULL, NULL),
(158, 'NU', 'Niue', NULL, NULL, NULL, NULL, NULL, NULL),
(159, 'NF', 'Norfork Island', NULL, NULL, NULL, NULL, NULL, NULL),
(160, 'MP', 'Northern Mariana Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(161, 'NO', 'Norway', NULL, NULL, NULL, NULL, NULL, NULL),
(162, 'OM', 'Oman', NULL, NULL, NULL, NULL, NULL, NULL),
(163, 'PK', 'Pakistan', NULL, NULL, NULL, NULL, NULL, NULL),
(164, 'PW', 'Palau', NULL, NULL, NULL, NULL, NULL, NULL),
(165, 'PA', 'Panama', NULL, NULL, NULL, NULL, NULL, NULL),
(166, 'PG', 'Papua New Guinea', NULL, NULL, NULL, NULL, NULL, NULL),
(167, 'PY', 'Paraguay', NULL, NULL, NULL, NULL, NULL, NULL),
(168, 'PE', 'Peru', NULL, NULL, NULL, NULL, NULL, NULL),
(169, 'PH', 'Philippines', NULL, NULL, NULL, NULL, NULL, NULL),
(170, 'PN', 'Pitcairn', NULL, NULL, NULL, NULL, NULL, NULL),
(171, 'PL', 'Poland', NULL, NULL, NULL, NULL, NULL, NULL),
(172, 'PT', 'Portugal', NULL, NULL, NULL, NULL, NULL, NULL),
(173, 'PR', 'Puerto Rico', NULL, NULL, NULL, NULL, NULL, NULL),
(174, 'QA', 'Qatar', NULL, NULL, NULL, NULL, NULL, NULL),
(175, 'RE', 'Reunion', NULL, NULL, NULL, NULL, NULL, NULL),
(176, 'RO', 'Romania', NULL, NULL, NULL, NULL, NULL, NULL),
(177, 'RU', 'Russian Federation', NULL, NULL, NULL, NULL, NULL, NULL),
(178, 'RW', 'Rwanda', NULL, NULL, NULL, NULL, NULL, NULL),
(179, 'KN', 'Saint Kitts and Nevis', NULL, NULL, NULL, NULL, NULL, NULL),
(180, 'LC', 'Saint Lucia', NULL, NULL, NULL, NULL, NULL, NULL),
(181, 'VC', 'Saint Vincent and the Grenadines', NULL, NULL, NULL, NULL, NULL, NULL),
(182, 'WS', 'Samoa', NULL, NULL, NULL, NULL, NULL, NULL),
(183, 'SM', 'San Marino', NULL, NULL, NULL, NULL, NULL, NULL),
(184, 'ST', 'Sao Tome and Principe', NULL, NULL, NULL, NULL, NULL, NULL),
(185, 'SA', 'Saudi Arabia', NULL, NULL, NULL, NULL, NULL, NULL),
(186, 'SN', 'Senegal', NULL, NULL, NULL, NULL, NULL, NULL),
(187, 'SC', 'Seychelles', NULL, NULL, NULL, NULL, NULL, NULL),
(188, 'SL', 'Sierra Leone', NULL, NULL, NULL, NULL, NULL, NULL),
(189, 'SG', 'Singapore', NULL, NULL, NULL, NULL, NULL, NULL),
(190, 'SK', 'Slovakia', NULL, NULL, NULL, NULL, NULL, NULL),
(191, 'SI', 'Slovenia', NULL, NULL, NULL, NULL, NULL, NULL),
(192, 'SB', 'Solomon Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(193, 'SO', 'Somalia', NULL, NULL, NULL, NULL, NULL, NULL),
(194, 'ZA', 'South Africa', NULL, NULL, NULL, NULL, NULL, NULL),
(195, 'GS', 'South Georgia South Sandwich Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(196, 'ES', 'Spain', NULL, NULL, NULL, NULL, NULL, NULL),
(197, 'LK', 'Sri Lanka', NULL, NULL, NULL, NULL, NULL, NULL),
(198, 'SH', 'St. Helena', NULL, NULL, NULL, NULL, NULL, NULL),
(199, 'PM', 'St. Pierre and Miquelon', NULL, NULL, NULL, NULL, NULL, NULL),
(200, 'SD', 'Sudan', NULL, NULL, NULL, NULL, NULL, NULL),
(201, 'SR', 'Suriname', NULL, NULL, NULL, NULL, NULL, NULL),
(202, 'SJ', 'Svalbarn and Jan Mayen Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(203, 'SZ', 'Swaziland', NULL, NULL, NULL, NULL, NULL, NULL),
(204, 'SE', 'Sweden', NULL, NULL, NULL, NULL, NULL, NULL),
(205, 'CH', 'Switzerland', NULL, NULL, NULL, NULL, NULL, NULL),
(206, 'SY', 'Syrian Arab Republic', NULL, NULL, NULL, NULL, NULL, NULL),
(207, 'TW', 'Taiwan', NULL, NULL, NULL, NULL, NULL, NULL),
(208, 'TJ', 'Tajikistan', NULL, NULL, NULL, NULL, NULL, NULL),
(209, 'TZ', 'Tanzania, United Republic of', NULL, NULL, NULL, NULL, NULL, NULL),
(210, 'TH', 'Thailand', NULL, NULL, NULL, NULL, NULL, NULL),
(211, 'TG', 'Togo', NULL, NULL, NULL, NULL, NULL, NULL),
(212, 'TK', 'Tokelau', NULL, NULL, NULL, NULL, NULL, NULL),
(213, 'TO', 'Tonga', NULL, NULL, NULL, NULL, NULL, NULL),
(214, 'TT', 'Trinidad and Tobago', NULL, NULL, NULL, NULL, NULL, NULL),
(215, 'TN', 'Tunisia', NULL, NULL, NULL, NULL, NULL, NULL),
(216, 'TR', 'Turkey', NULL, NULL, NULL, NULL, NULL, NULL),
(217, 'TM', 'Turkmenistan', NULL, NULL, NULL, NULL, NULL, NULL),
(218, 'TC', 'Turks and Caicos Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(219, 'TV', 'Tuvalu', NULL, NULL, NULL, NULL, NULL, NULL),
(220, 'UG', 'Uganda', NULL, NULL, NULL, NULL, NULL, NULL),
(221, 'UA', 'Ukraine', NULL, NULL, NULL, NULL, NULL, NULL),
(222, 'AE', 'United Arab Emirates', NULL, NULL, NULL, NULL, NULL, NULL),
(223, 'GB', 'United Kingdom', NULL, NULL, NULL, NULL, NULL, NULL),
(224, 'UM', 'United States minor outlying islands', NULL, NULL, NULL, NULL, NULL, NULL),
(225, 'UY', 'Uruguay', NULL, NULL, NULL, NULL, NULL, NULL),
(226, 'UZ', 'Uzbekistan', NULL, NULL, NULL, NULL, NULL, NULL),
(227, 'VU', 'Vanuatu', NULL, NULL, NULL, NULL, NULL, NULL),
(228, 'VA', 'Vatican City State', NULL, NULL, NULL, NULL, NULL, NULL),
(229, 'VE', 'Venezuela', NULL, NULL, NULL, NULL, NULL, NULL),
(230, 'VN', 'Vietnam', NULL, NULL, NULL, NULL, NULL, NULL),
(231, 'VG', 'Virigan Islands (British)', NULL, NULL, NULL, NULL, NULL, NULL),
(232, 'VI', 'Virgin Islands (U.S.)', NULL, NULL, NULL, NULL, NULL, NULL),
(233, 'WF', 'Wallis and Futuna Islands', NULL, NULL, NULL, NULL, NULL, NULL),
(234, 'EH', 'Western Sahara', NULL, NULL, NULL, NULL, NULL, NULL),
(235, 'YE', 'Yemen', NULL, NULL, NULL, NULL, NULL, NULL),
(236, 'YU', 'Yugoslavia', NULL, NULL, NULL, NULL, NULL, NULL),
(237, 'ZR', 'Zaire', NULL, NULL, NULL, NULL, NULL, NULL),
(238, 'ZM', 'Zambia', NULL, NULL, NULL, NULL, NULL, NULL),
(239, 'ZW', 'Zimbabwe', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `csv_product_imports`
--

CREATE TABLE `csv_product_imports` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint DEFAULT NULL COMMENT '1-Pending, 2-Success, 3-Failed, 4-In-progress',
  `error` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `csv_product_imports`
--

INSERT INTO `csv_product_imports` (`id`, `vendor_id`, `name`, `path`, `uploaded_by`, `status`, `error`, `created_at`, `updated_at`) VALUES
(1, 9, '1626696680_salad.csv', '/storage/csv_products/1626696680_salad.csv', NULL, 2, NULL, '2021-07-19 12:11:20', '2021-07-19 12:11:20');

-- --------------------------------------------------------

--
-- Table structure for table `csv_vendor_imports`
--

CREATE TABLE `csv_vendor_imports` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint DEFAULT NULL COMMENT '1-Pending, 2-Success, 3-Failed, 4-In-progress',
  `error` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `csv_vendor_imports`
--

INSERT INTO `csv_vendor_imports` (`id`, `name`, `path`, `uploaded_by`, `status`, `error`, `created_at`, `updated_at`) VALUES
(1, '1626439701_Restaurant vendor csv.csv', '/storage/csv_vendors/1626439701_Restaurant vendor csv.csv', NULL, 2, NULL, '2021-07-16 12:48:21', '2021-07-16 12:48:24');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `iso_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subunit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subunit_to_unit` int NOT NULL,
  `symbol_first` tinyint NOT NULL,
  `html_entity` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_mark` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thousands_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_numeric` smallint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `priority`, `iso_code`, `symbol`, `subunit`, `subunit_to_unit`, `symbol_first`, `html_entity`, `decimal_mark`, `thousands_separator`, `iso_numeric`, `created_at`, `updated_at`) VALUES
(1, 'United Arab Emirates Dirham', 100, 'AED', 'د.إ', 'Fils', 100, 1, '', '.', ',', 784, NULL, NULL),
(2, 'Afghan Afghani', 100, 'AFN', '؋', 'Pul', 100, 0, '', '.', ',', 971, NULL, NULL),
(3, 'Albanian Lek', 100, 'ALL', 'L', 'Qintar', 100, 0, '', '.', ',', 8, NULL, NULL),
(4, 'Armenian Dram', 100, 'AMD', 'դր.', 'Luma', 100, 0, '', '.', ',', 51, NULL, NULL),
(5, 'Netherlands Antillean Gulden', 100, 'ANG', 'ƒ', 'Cent', 100, 1, '&#x0192;', ',', '.', 532, NULL, NULL),
(6, 'Angolan Kwanza', 100, 'AOA', 'Kz', 'Cêntimo', 100, 0, '', '.', ',', 973, NULL, NULL),
(7, 'Argentine Peso', 100, 'ARS', '$', 'Centavo', 100, 1, '&#x20B1;', ',', '.', 32, NULL, NULL),
(8, 'Australian Dollar', 4, 'AUD', '$', 'Cent', 100, 1, '$', '.', ',', 36, NULL, NULL),
(9, 'Aruban Florin', 100, 'AWG', 'ƒ', 'Cent', 100, 0, '&#x0192;', '.', ',', 533, NULL, NULL),
(10, 'Azerbaijani Manat', 100, 'AZN', 'null', 'Qəpik', 100, 1, '', '.', ',', 944, NULL, NULL),
(11, 'Bosnia and Herzegovina Convertible Mark', 100, 'BAM', 'КМ', 'Fening', 100, 1, '', '.', ',', 977, NULL, NULL),
(12, 'Barbadian Dollar', 100, 'BBD', '$', 'Cent', 100, 0, '$', '.', ',', 52, NULL, NULL),
(13, 'Bangladeshi Taka', 100, 'BDT', '৳', 'Paisa', 100, 1, '', '.', ',', 50, NULL, NULL),
(14, 'Bulgarian Lev', 100, 'BGN', 'лв', 'Stotinka', 100, 0, '', '.', ',', 975, NULL, NULL),
(15, 'Bahraini Dinar', 100, 'BHD', 'ب.د', 'Fils', 1000, 1, '', '.', ',', 48, NULL, NULL),
(16, 'Burundian Franc', 100, 'BIF', 'Fr', 'Centime', 100, 0, '', '.', ',', 108, NULL, NULL),
(17, 'Bermudian Dollar', 100, 'BMD', '$', 'Cent', 100, 1, '$', '.', ',', 60, NULL, NULL),
(18, 'Brunei Dollar', 100, 'BND', '$', 'Sen', 100, 1, '$', '.', ',', 96, NULL, NULL),
(19, 'Bolivian Boliviano', 100, 'BOB', 'Bs.', 'Centavo', 100, 1, '', '.', ',', 68, NULL, NULL),
(20, 'Brazilian Real', 100, 'BRL', 'R$', 'Centavo', 100, 1, 'R$', ',', '.', 986, NULL, NULL),
(21, 'Bahamian Dollar', 100, 'BSD', '$', 'Cent', 100, 1, '$', '.', ',', 44, NULL, NULL),
(22, 'Bhutanese Ngultrum', 100, 'BTN', 'Nu.', 'Chertrum', 100, 0, '', '.', ',', 64, NULL, NULL),
(23, 'Botswana Pula', 100, 'BWP', 'P', 'Thebe', 100, 1, '', '.', ',', 72, NULL, NULL),
(24, 'Belarusian Ruble', 100, 'BYR', 'Br', 'Kapyeyka', 100, 0, '', '.', ',', 974, NULL, NULL),
(25, 'Belize Dollar', 100, 'BZD', '$', 'Cent', 100, 1, '$', '.', ',', 84, NULL, NULL),
(26, 'Canadian Dollar', 5, 'CAD', '$', 'Cent', 100, 1, '$', '.', ',', 124, NULL, NULL),
(27, 'Congolese Franc', 100, 'CDF', 'Fr', 'Centime', 100, 0, '', '.', ',', 976, NULL, NULL),
(28, 'Swiss Franc', 100, 'CHF', 'Fr', 'Rappen', 100, 1, '', '.', ',', 756, NULL, NULL),
(29, 'Unidad de Fomento', 100, 'CLF', 'UF', 'Peso', 1, 1, '&#x20B1;', ',', '.', 990, NULL, NULL),
(30, 'Chilean Peso', 100, 'CLP', '$', 'Peso', 1, 1, '&#36;', ',', '.', 152, NULL, NULL),
(31, 'Chinese Renminbi Yuan', 100, 'CNY', '¥', 'Fen', 100, 1, '&#20803;', '.', ',', 156, NULL, NULL),
(32, 'Colombian Peso', 100, 'COP', '$', 'Centavo', 100, 1, '&#x20B1;', ',', '.', 170, NULL, NULL),
(33, 'Costa Rican Colón', 100, 'CRC', '₡', 'Céntimo', 100, 1, '&#x20A1;', ',', '.', 188, NULL, NULL),
(34, 'Cuban Convertible Peso', 100, 'CUC', '$', 'Centavo', 100, 0, '', '.', ',', 931, NULL, NULL),
(35, 'Cuban Peso', 100, 'CUP', '$', 'Centavo', 100, 1, '&#x20B1;', '.', ',', 192, NULL, NULL),
(36, 'Cape Verdean Escudo', 100, 'CVE', '$', 'Centavo', 100, 0, '', '.', ',', 132, NULL, NULL),
(37, 'Czech Koruna', 100, 'CZK', 'Kč', 'Haléř', 100, 1, '', ',', '.', 203, NULL, NULL),
(38, 'Djiboutian Franc', 100, 'DJF', 'Fdj', 'Centime', 100, 0, '', '.', ',', 262, NULL, NULL),
(39, 'Danish Krone', 100, 'DKK', 'kr', 'Øre', 100, 0, '', ',', '.', 208, NULL, NULL),
(40, 'Dominican Peso', 100, 'DOP', '$', 'Centavo', 100, 1, '&#x20B1;', '.', ',', 214, NULL, NULL),
(41, 'Algerian Dinar', 100, 'DZD', 'د.ج', 'Centime', 100, 0, '', '.', ',', 12, NULL, NULL),
(42, 'Egyptian Pound', 100, 'EGP', 'ج.م', 'Piastre', 100, 1, '&#x00A3;', '.', ',', 818, NULL, NULL),
(43, 'Eritrean Nakfa', 100, 'ERN', 'Nfk', 'Cent', 100, 0, '', '.', ',', 232, NULL, NULL),
(44, 'Ethiopian Birr', 100, 'ETB', 'Br', 'Santim', 100, 0, '', '.', ',', 230, NULL, NULL),
(45, 'Euro', 2, 'EUR', '€', 'Cent', 100, 1, '&#x20AC;', ',', '.', 978, NULL, NULL),
(46, 'Fijian Dollar', 100, 'FJD', '$', 'Cent', 100, 0, '$', '.', ',', 242, NULL, NULL),
(47, 'Falkland Pound', 100, 'FKP', '£', 'Penny', 100, 0, '&#x00A3;', '.', ',', 238, NULL, NULL),
(48, 'British Pound', 3, 'GBP', '£', 'Penny', 100, 1, '&#x00A3;', '.', ',', 826, NULL, NULL),
(49, 'Georgian Lari', 100, 'GEL', 'ლ', 'Tetri', 100, 0, '', '.', ',', 981, NULL, NULL),
(50, 'Ghanaian Cedi', 100, 'GHS', '₵', 'Pesewa', 100, 1, '&#x20B5;', '.', ',', 936, NULL, NULL),
(51, 'Gibraltar Pound', 100, 'GIP', '£', 'Penny', 100, 1, '&#x00A3;', '.', ',', 292, NULL, NULL),
(52, 'Gambian Dalasi', 100, 'GMD', 'D', 'Butut', 100, 0, '', '.', ',', 270, NULL, NULL),
(53, 'Guinean Franc', 100, 'GNF', 'Fr', 'Centime', 100, 0, '', '.', ',', 324, NULL, NULL),
(54, 'Guatemalan Quetzal', 100, 'GTQ', 'Q', 'Centavo', 100, 1, '', '.', ',', 320, NULL, NULL),
(55, 'Guyanese Dollar', 100, 'GYD', '$', 'Cent', 100, 0, '$', '.', ',', 328, NULL, NULL),
(56, 'Hong Kong Dollar', 100, 'HKD', '$', 'Cent', 100, 1, '$', '.', ',', 344, NULL, NULL),
(57, 'Honduran Lempira', 100, 'HNL', 'L', 'Centavo', 100, 1, '', '.', ',', 340, NULL, NULL),
(58, 'Croatian Kuna', 100, 'HRK', 'kn', 'Lipa', 100, 1, '', ',', '.', 191, NULL, NULL),
(59, 'Haitian Gourde', 100, 'HTG', 'G', 'Centime', 100, 0, '', '.', ',', 332, NULL, NULL),
(60, 'Hungarian Forint', 100, 'HUF', 'Ft', 'Fillér', 100, 0, '', ',', '.', 348, NULL, NULL),
(61, 'Indonesian Rupiah', 100, 'IDR', 'Rp', 'Sen', 100, 1, '', ',', '.', 360, NULL, NULL),
(62, 'Israeli New Sheqel', 100, 'ILS', '₪', 'Agora', 100, 1, '&#x20AA;', '.', ',', 376, NULL, NULL),
(63, 'Indian Rupee', 100, 'INR', '₹', 'Paisa', 100, 1, '&#x20b9;', '.', ',', 356, NULL, NULL),
(64, 'Iraqi Dinar', 100, 'IQD', 'ع.د', 'Fils', 1000, 0, '', '.', ',', 368, NULL, NULL),
(65, 'Iranian Rial', 100, 'IRR', '﷼', 'Dinar', 100, 1, '&#xFDFC;', '.', ',', 364, NULL, NULL),
(66, 'Icelandic Króna', 100, 'ISK', 'kr', 'Eyrir', 100, 1, '', ',', '.', 352, NULL, NULL),
(67, 'Jamaican Dollar', 100, 'JMD', '$', 'Cent', 100, 1, '$', '.', ',', 388, NULL, NULL),
(68, 'Jordanian Dinar', 100, 'JOD', 'د.ا', 'Piastre', 100, 1, '', '.', ',', 400, NULL, NULL),
(69, 'Japanese Yen', 6, 'JPY', '¥', 'null', 1, 1, '&#x00A5;', '.', ',', 392, NULL, NULL),
(70, 'Kenyan Shilling', 100, 'KES', 'KSh', 'Cent', 100, 1, '', '.', ',', 404, NULL, NULL),
(71, 'Kyrgyzstani Som', 100, 'KGS', 'som', 'Tyiyn', 100, 0, '', '.', ',', 417, NULL, NULL),
(72, 'Cambodian Riel', 100, 'KHR', '៛', 'Sen', 100, 0, '&#x17\\DB;', '.', ',', 116, NULL, NULL),
(73, 'Comorian Franc', 100, 'KMF', 'Fr', 'Centime', 100, 0, '', '.', ',', 174, NULL, NULL),
(74, 'North Korean Won', 100, 'KPW', '₩', 'Chŏn', 100, 0, '&#x20A9;', '.', ',', 408, NULL, NULL),
(75, 'South Korean Won', 100, 'KRW', '₩', 'null', 1, 1, '&#x20A9;', '.', ',', 410, NULL, NULL),
(76, 'Kuwaiti Dinar', 100, 'KWD', 'د.ك', 'Fils', 1000, 1, '', '.', ',', 414, NULL, NULL),
(77, 'Cayman Islands Dollar', 100, 'KYD', '$', 'Cent', 100, 1, '$', '.', ',', 136, NULL, NULL),
(78, 'Kazakhstani Tenge', 100, 'KZT', '〒', 'Tiyn', 100, 0, '', '.', ',', 398, NULL, NULL),
(79, 'Lao Kip', 100, 'LAK', '₭', 'Att', 100, 0, '&#x20AD;', '.', ',', 418, NULL, NULL),
(80, 'Lebanese Pound', 100, 'LBP', 'ل.ل', 'Piastre', 100, 1, '&#x00A3;', '.', ',', 422, NULL, NULL),
(81, 'Sri Lankan Rupee', 100, 'LKR', '₨', 'Cent', 100, 0, '&#x0BF9;', '.', ',', 144, NULL, NULL),
(82, 'Liberian Dollar', 100, 'LRD', '$', 'Cent', 100, 0, '$', '.', ',', 430, NULL, NULL),
(83, 'Lesotho Loti', 100, 'LSL', 'L', 'Sente', 100, 0, '', '.', ',', 426, NULL, NULL),
(84, 'Lithuanian Litas', 100, 'LTL', 'Lt', 'Centas', 100, 0, '', '.', ',', 440, NULL, NULL),
(85, 'Latvian Lats', 100, 'LVL', 'Ls', 'Santīms', 100, 1, '', '.', ',', 428, NULL, NULL),
(86, 'Libyan Dinar', 100, 'LYD', 'ل.د', 'Dirham', 1000, 0, '', '.', ',', 434, NULL, NULL),
(87, 'Moroccan Dirham', 100, 'MAD', 'د.م.', 'Centime', 100, 0, '', '.', ',', 504, NULL, NULL),
(88, 'Moldovan Leu', 100, 'MDL', 'L', 'Ban', 100, 0, '', '.', ',', 498, NULL, NULL),
(89, 'Malagasy Ariary', 100, 'MGA', 'Ar', 'Iraimbilanja', 5, 1, '', '.', ',', 969, NULL, NULL),
(90, 'Macedonian Denar', 100, 'MKD', 'ден', 'Deni', 100, 0, '', '.', ',', 807, NULL, NULL),
(91, 'Myanmar Kyat', 100, 'MMK', 'K', 'Pya', 100, 0, '', '.', ',', 104, NULL, NULL),
(92, 'Mongolian Tögrög', 100, 'MNT', '₮', 'Möngö', 100, 0, '&#x20AE;', '.', ',', 496, NULL, NULL),
(93, 'Macanese Pataca', 100, 'MOP', 'P', 'Avo', 100, 0, '', '.', ',', 446, NULL, NULL),
(94, 'Mauritanian Ouguiya', 100, 'MRO', 'UM', 'Khoums', 5, 0, '', '.', ',', 478, NULL, NULL),
(95, 'Mauritian Rupee', 100, 'MUR', '₨', 'Cent', 100, 1, '&#x20A8;', '.', ',', 480, NULL, NULL),
(96, 'Maldivian Rufiyaa', 100, 'MVR', 'MVR', 'Laari', 100, 0, '', '.', ',', 462, NULL, NULL),
(97, 'Malawian Kwacha', 100, 'MWK', 'MK', 'Tambala', 100, 0, '', '.', ',', 454, NULL, NULL),
(98, 'Mexican Peso', 100, 'MXN', '$', 'Centavo', 100, 1, '$', '.', ',', 484, NULL, NULL),
(99, 'Malaysian Ringgit', 100, 'MYR', 'RM', 'Sen', 100, 1, '', '.', ',', 458, NULL, NULL),
(100, 'Mozambican Metical', 100, 'MZN', 'MTn', 'Centavo', 100, 1, '', ',', '.', 943, NULL, NULL),
(101, 'Namibian Dollar', 100, 'NAD', '$', 'Cent', 100, 0, '$', '.', ',', 516, NULL, NULL),
(102, 'Nigerian Naira', 100, 'NGN', '₦', 'Kobo', 100, 0, '&#x20A6;', '.', ',', 566, NULL, NULL),
(103, 'Nicaraguan Córdoba', 100, 'NIO', 'C$', 'Centavo', 100, 0, '', '.', ',', 558, NULL, NULL),
(104, 'Norwegian Krone', 100, 'NOK', 'kr', 'Øre', 100, 1, 'kr', ',', '.', 578, NULL, NULL),
(105, 'Nepalese Rupee', 100, 'NPR', '₨', 'Paisa', 100, 1, '&#x20A8;', '.', ',', 524, NULL, NULL),
(106, 'New Zealand Dollar', 100, 'NZD', '$', 'Cent', 100, 1, '$', '.', ',', 554, NULL, NULL),
(107, 'Omani Rial', 100, 'OMR', 'ر.ع.', 'Baisa', 1000, 1, '&#xFDFC;', '.', ',', 512, NULL, NULL),
(108, 'Panamanian Balboa', 100, 'PAB', 'B/.', 'Centésimo', 100, 0, '', '.', ',', 590, NULL, NULL),
(109, 'Peruvian Nuevo Sol', 100, 'PEN', 'S/.', 'Céntimo', 100, 1, 'S/.', '.', ',', 604, NULL, NULL),
(110, 'Papua New Guinean Kina', 100, 'PGK', 'K', 'Toea', 100, 0, '', '.', ',', 598, NULL, NULL),
(111, 'Philippine Peso', 100, 'PHP', '₱', 'Centavo', 100, 1, '&#x20B1;', '.', ',', 608, NULL, NULL),
(112, 'Pakistani Rupee', 100, 'PKR', '₨', 'Paisa', 100, 1, '&#x20A8;', '.', ',', 586, NULL, NULL),
(113, 'Polish Złoty', 100, 'PLN', 'zł', 'Grosz', 100, 0, 'z&#322;', ',', '', 985, NULL, NULL),
(114, 'Paraguayan Guaraní', 100, 'PYG', '₲', 'Céntimo', 100, 1, '&#x20B2;', '.', ',', 600, NULL, NULL),
(115, 'Qatari Riyal', 100, 'QAR', 'ر.ق', 'Dirham', 100, 0, '&#xFDFC;', '.', ',', 634, NULL, NULL),
(116, 'Romanian Leu', 100, 'RON', 'Lei', 'Bani', 100, 1, '', ',', '.', 946, NULL, NULL),
(117, 'Serbian Dinar', 100, 'RSD', 'РСД', 'Para', 100, 1, '', '.', ',', 941, NULL, NULL),
(118, 'Russian Ruble', 100, 'RUB', 'р.', 'Kopek', 100, 0, '&#x0440;&#x0443;&#x0431;', ',', '.', 643, NULL, NULL),
(119, 'Rwandan Franc', 100, 'RWF', 'FRw', 'Centime', 100, 0, '', '.', ',', 646, NULL, NULL),
(120, 'Saudi Riyal', 100, 'SAR', 'ر.س', 'Hallallah', 100, 1, '&#xFDFC;', '.', ',', 682, NULL, NULL),
(121, 'Solomon Islands Dollar', 100, 'SBD', '$', 'Cent', 100, 0, '$', '.', ',', 90, NULL, NULL),
(122, 'Seychellois Rupee', 100, 'SCR', '₨', 'Cent', 100, 0, '&#x20A8;', '.', ',', 690, NULL, NULL),
(123, 'Sudanese Pound', 100, 'SDG', '£', 'Piastre', 100, 1, '', '.', ',', 938, NULL, NULL),
(124, 'Swedish Krona', 100, 'SEK', 'kr', 'Öre', 100, 0, '', ',', '', 752, NULL, NULL),
(125, 'Singapore Dollar', 100, 'SGD', '$', 'Cent', 100, 1, '$', '.', ',', 702, NULL, NULL),
(126, 'Saint Helenian Pound', 100, 'SHP', '£', 'Penny', 100, 0, '&#x00A3;', '.', ',', 654, NULL, NULL),
(127, 'Slovak Koruna', 100, 'SKK', 'Sk', 'Halier', 100, 1, '', '.', ',', 703, NULL, NULL),
(128, 'Sierra Leonean Leone', 100, 'SLL', 'Le', 'Cent', 100, 0, '', '.', ',', 694, NULL, NULL),
(129, 'Somali Shilling', 100, 'SOS', 'Sh', 'Cent', 100, 0, '', '.', ',', 706, NULL, NULL),
(130, 'Surinamese Dollar', 100, 'SRD', '$', 'Cent', 100, 0, '', '.', ',', 968, NULL, NULL),
(131, 'South Sudanese Pound', 100, 'SSP', '£', 'piaster', 100, 0, '&#x00A3;', '.', ',', 728, NULL, NULL),
(132, 'São Tomé and Príncipe Dobra', 100, 'STD', '\\DB', 'Cêntimo', 100, 0, '', '.', ',', 678, NULL, NULL),
(133, 'Salvadoran Colón', 100, 'SVC', '₡', 'Centavo', 100, 1, '&#x20A1;', '.', ',', 222, NULL, NULL),
(134, 'Syrian Pound', 100, 'SYP', '£S', 'Piastre', 100, 0, '&#x00A3;', '.', ',', 760, NULL, NULL),
(135, 'Swazi Lilangeni', 100, 'SZL', 'L', 'Cent', 100, 1, '', '.', ',', 748, NULL, NULL),
(136, 'Thai Baht', 100, 'THB', '฿', 'Satang', 100, 1, '&#x0E3F;', '.', ',', 764, NULL, NULL),
(137, 'Tajikistani Somoni', 100, 'TJS', 'ЅМ', 'Diram', 100, 0, '', '.', ',', 972, NULL, NULL),
(138, 'Turkmenistani Manat', 100, 'TMT', 'T', 'Tenge', 100, 0, '', '.', ',', 934, NULL, NULL),
(139, 'Tunisian Dinar', 100, 'TND', 'د.ت', 'Millime', 1000, 0, '', '.', ',', 788, NULL, NULL),
(140, 'Tongan Paʻanga', 100, 'TOP', 'T$', 'Seniti', 100, 1, '', '.', ',', 776, NULL, NULL),
(141, 'Turkish Lira', 100, 'TRY', 'TL', 'kuruş', 100, 0, '', ',', '.', 949, NULL, NULL),
(142, 'Trinidad and Tobago Dollar', 100, 'TTD', '$', 'Cent', 100, 0, '$', '.', ',', 780, NULL, NULL),
(143, 'New Taiwan Dollar', 100, 'TWD', '$', 'Cent', 100, 1, '$', '.', ',', 901, NULL, NULL),
(144, 'Tanzanian Shilling', 100, 'TZS', 'Sh', 'Cent', 100, 1, '', '.', ',', 834, NULL, NULL),
(145, 'Ukrainian Hryvnia', 100, 'UAH', '₴', 'Kopiyka', 100, 0, '&#x20B4;', '.', ',', 980, NULL, NULL),
(146, 'Ugandan Shilling', 100, 'UGX', 'USh', 'Cent', 100, 0, '', '.', ',', 800, NULL, NULL),
(147, 'United States Dollar', 1, 'USD', '$', 'Cent', 100, 1, '$', '.', ',', 840, NULL, NULL),
(148, 'Uruguayan Peso', 100, 'UYU', '$', 'Centésimo', 100, 1, '&#x20B1;', ',', '.', 858, NULL, NULL),
(149, 'Uzbekistani Som', 100, 'UZS', 'null', 'Tiyin', 100, 0, '', '.', ',', 860, NULL, NULL),
(150, 'Venezuelan Bolívar', 100, 'VEF', 'Bs F', 'Céntimo', 100, 1, '', ',', '.', 937, NULL, NULL),
(151, 'Vietnamese Đồng', 100, 'VND', '₫', 'Hào', 10, 1, '&#x20AB;', ',', '.', 704, NULL, NULL),
(152, 'Vanuatu Vatu', 100, 'VUV', 'Vt', 'null', 1, 1, '', '.', ',', 548, NULL, NULL),
(153, 'Samoan Tala', 100, 'WST', 'T', 'Sene', 100, 0, '', '.', ',', 882, NULL, NULL),
(154, 'Central African Cfa Franc', 100, 'XAF', 'Fr', 'Centime', 100, 0, '', '.', ',', 950, NULL, NULL),
(155, 'Silver (Troy Ounce)', 100, 'XAG', 'oz t', 'oz', 1, 0, '', '.', ',', 961, NULL, NULL),
(156, 'Gold (Troy Ounce)', 100, 'XAU', 'oz t', 'oz', 1, 0, '', '.', ',', 959, NULL, NULL),
(157, 'East Caribbean Dollar', 100, 'XCD', '$', 'Cent', 100, 1, '$', '.', ',', 951, NULL, NULL),
(158, 'Special Drawing Rights', 100, 'XDR', 'SDR', '', 1, 0, '$', '.', ',', 960, NULL, NULL),
(159, 'West African Cfa Franc', 100, 'XOF', 'Fr', 'Centime', 100, 0, '', '.', ',', 952, NULL, NULL),
(160, 'Cfp Franc', 100, 'XPF', 'Fr', 'Centime', 100, 0, '', '.', ',', 953, NULL, NULL),
(161, 'Yemeni Rial', 100, 'YER', '﷼', 'Fils', 100, 0, '&#xFDFC;', '.', ',', 886, NULL, NULL),
(162, 'South African Rand', 100, 'ZAR', 'R', 'Cent', 100, 1, '&#x0052;', '.', ',', 710, NULL, NULL),
(163, 'Zambian Kwacha', 100, 'ZMK', 'ZK', 'Ngwee', 100, 0, '', '.', ',', 894, NULL, NULL),
(164, 'Zambian Kwacha', 100, 'ZMW', 'ZK', 'Ngwee', 100, 0, '', '.', ',', 967, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dispatcher_status_options`
--

CREATE TABLE `dispatcher_status_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1 - for order, 2 - fordispatch',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 - active, 0 - inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispatcher_status_options`
--

INSERT INTO `dispatcher_status_options` (`id`, `title`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Created', 1, 1, '2021-07-09 10:52:10', '2021-07-09 10:52:10'),
(2, 'Assigned', 1, 1, '2021-07-09 10:52:10', '2021-07-09 10:52:10'),
(3, 'Started', 1, 1, '2021-07-09 10:52:10', '2021-07-09 10:52:10'),
(4, 'Arrived', 1, 1, '2021-07-09 10:52:10', '2021-07-09 10:52:10'),
(5, 'Completed', 1, 1, '2021-07-09 10:52:10', '2021-07-09 10:52:10');

-- --------------------------------------------------------

--
-- Table structure for table `dispatcher_template_type_options`
--

CREATE TABLE `dispatcher_template_type_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 for inactive and 1 for active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `dispatcher_template_type_options`
--

INSERT INTO `dispatcher_template_type_options` (`id`, `title`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pickup & Delivery', 'template1.png', 1, '2021-06-30 14:11:21', '2021-06-30 14:11:21'),
(2, 'Cab Booking', 'template2.png', 1, '2021-06-30 14:11:21', '2021-06-30 14:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `dispatcher_warning_pages`
--

CREATE TABLE `dispatcher_warning_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 for inactive and 1 for active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `dispatcher_warning_pages`
--

INSERT INTO `dispatcher_warning_pages` (`id`, `title`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Design 1', 'warning1.png', 1, '2021-06-30 14:10:35', '2021-06-30 14:10:35'),
(2, 'Design 2', 'warning2.png', 1, '2021-06-30 14:10:35', '2021-06-30 14:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `sort_code` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nativeName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `sort_code`, `name`, `nativeName`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', 'English', NULL, NULL),
(2, 'ab', 'Abkhaz', 'аҧсуа', NULL, NULL),
(3, 'aa', 'Afar', 'Afaraf', NULL, NULL),
(4, 'af', 'Afrikaans', 'Afrikaans', NULL, NULL),
(5, 'ak', 'Akan', 'Akan', NULL, NULL),
(6, 'sq', 'Albanian', 'Shqip', NULL, NULL),
(7, 'am', 'Amharic', 'አማርኛ', NULL, NULL),
(8, 'ar', 'Arabic', 'العربية', NULL, NULL),
(9, 'an', 'Aragonese', 'Aragonés', NULL, NULL),
(10, 'hy', 'Armenian', 'Հայերեն', NULL, NULL),
(11, 'as', 'Assamese', 'অসমীয়া', NULL, NULL),
(12, 'av', 'Avaric', 'авар мацӀ, магӀарул мацӀ', NULL, NULL),
(13, 'ae', 'Avestan', 'avesta', NULL, NULL),
(14, 'ay', 'Aymara', 'aymar aru', NULL, NULL),
(15, 'az', 'Azerbaijani', 'azərbaycan dili', NULL, NULL),
(16, 'bm', 'Bambara', 'bamanankan', NULL, NULL),
(17, 'ba', 'Bashkir', 'башҡорт теле', NULL, NULL),
(18, 'eu', 'Basque', 'euskara, euskera', NULL, NULL),
(19, 'be', 'Belarusian', 'Беларуская', NULL, NULL),
(20, 'bn', 'Bengali', 'বাংলা', NULL, NULL),
(21, 'bh', 'Bihari', 'भोजपुरी', NULL, NULL),
(22, 'bi', 'Bislama', 'Bislama', NULL, NULL),
(23, 'bs', 'Bosnian', 'bosanski jezik', NULL, NULL),
(24, 'br', 'Breton', 'brezhoneg', NULL, NULL),
(25, 'bg', 'Bulgarian', 'български език', NULL, NULL),
(26, 'my', 'Burmese', 'ဗမာစာ', NULL, NULL),
(27, 'ca', 'Catalan; Valencian', 'Català', NULL, NULL),
(28, 'ch', 'Chamorro', 'Chamoru', NULL, NULL),
(29, 'ce', 'Chechen', 'нохчийн мотт', NULL, NULL),
(30, 'ny', 'Chichewa; Chewa; Nyanja', 'chiCheŵa, chinyanja', NULL, NULL),
(31, 'zh', 'Chinese', '中文 (Zhōngwén), 汉语, 漢語', NULL, NULL),
(32, 'cv', 'Chuvash', 'чӑваш чӗлхи', NULL, NULL),
(33, 'kw', 'Cornish', 'Kernewek', NULL, NULL),
(34, 'co', 'Corsican', 'corsu, lingua corsa', NULL, NULL),
(35, 'cr', 'Cree', 'ᓀᐦᐃᔭᐍᐏᐣ', NULL, NULL),
(36, 'hr', 'Croatian', 'hrvatski', NULL, NULL),
(37, 'cs', 'Czech', 'česky, čeština', NULL, NULL),
(38, 'da', 'Danish', 'dansk', NULL, NULL),
(39, 'dv', 'Divehi; Dhivehi; Maldivian;', 'ދިވެހި', NULL, NULL),
(40, 'nl', 'Dutch', 'Nederlands, Vlaams', NULL, NULL),
(41, 'eo', 'Esperanto', 'Esperanto', NULL, NULL),
(42, 'et', 'Estonian', 'eesti, eesti keel', NULL, NULL),
(43, 'ee', 'Ewe', 'Eʋegbe', NULL, NULL),
(44, 'fo', 'Faroese', 'føroyskt', NULL, NULL),
(45, 'fj', 'Fijian', 'vosa Vakaviti', NULL, NULL),
(46, 'fi', 'Finnish', 'suomi, suomen kieli', NULL, NULL),
(47, 'fr', 'French', 'français, langue française', NULL, NULL),
(48, 'ff', 'Fula; Fulah; Pulaar; Pular', 'Fulfulde, Pulaar, Pular', NULL, NULL),
(49, 'gl', 'Galician', 'Galego', NULL, NULL),
(50, 'ka', 'Georgian', 'ქართული', NULL, NULL),
(51, 'de', 'German', 'Deutsch', NULL, NULL),
(52, 'el', 'Greek, Modern', 'Ελληνικά', NULL, NULL),
(53, 'gn', 'Guaraní', 'Avañeẽ', NULL, NULL),
(54, 'gu', 'Gujarati', 'ગુજરાતી', NULL, NULL),
(55, 'ht', 'Haitian; Haitian Creole', 'Kreyòl ayisyen', NULL, NULL),
(56, 'ha', 'Hausa', 'Hausa, هَوُسَ', NULL, NULL),
(57, 'he', 'Hebrew (modern)', 'עברית', NULL, NULL),
(58, 'hz', 'Herero', 'Otjiherero', NULL, NULL),
(59, 'hi', 'Hindi', 'हिन्दी, हिंदी', NULL, NULL),
(60, 'ho', 'Hiri Motu', 'Hiri Motu', NULL, NULL),
(61, 'hu', 'Hungarian', 'Magyar', NULL, NULL),
(62, 'ia', 'Interlingua', 'Interlingua', NULL, NULL),
(63, 'id', 'Indonesian', 'Bahasa Indonesia', NULL, NULL),
(64, 'ie', 'Interlingue', 'Originally called Occidental; then Interlingue after WWII', NULL, NULL),
(65, 'ga', 'Irish', 'Gaeilge', NULL, NULL),
(66, 'ig', 'Igbo', 'Asụsụ Igbo', NULL, NULL),
(67, 'ik', 'Inupiaq', 'Iñupiaq, Iñupiatun', NULL, NULL),
(68, 'io', 'Ido', 'Ido', NULL, NULL),
(69, 'is', 'Icelandic', 'Íslenska', NULL, NULL),
(70, 'it', 'Italian', 'Italiano', NULL, NULL),
(71, 'iu', 'Inuktitut', 'ᐃᓄᒃᑎᑐᑦ', NULL, NULL),
(72, 'ja', 'Japanese', '日本語 (にほんご／にっぽんご)', NULL, NULL),
(73, 'jv', 'Javanese', 'basa Jawa', NULL, NULL),
(74, 'kl', 'Kalaallisut, Greenlandic', 'kalaallisut, kalaallit oqaasii', NULL, NULL),
(75, 'kn', 'Kannada', 'ಕನ್ನಡ', NULL, NULL),
(76, 'kr', 'Kanuri', 'Kanuri', NULL, NULL),
(77, 'ks', 'Kashmiri', 'कश्मीरी, كشميري‎', NULL, NULL),
(78, 'kk', 'Kazakh', 'Қазақ тілі', NULL, NULL),
(79, 'km', 'Khmer', 'ភាសាខ្មែរ', NULL, NULL),
(80, 'ki', 'Kikuyu, Gikuyu', 'Gĩkũyũ', NULL, NULL),
(81, 'rw', 'Kinyarwanda', 'Ikinyarwanda', NULL, NULL),
(82, 'ky', 'Kirghiz, Kyrgyz', 'кыргыз тили', NULL, NULL),
(83, 'kv', 'Komi', 'коми кыв', NULL, NULL),
(84, 'kg', 'Kongo', 'KiKongo', NULL, NULL),
(85, 'ko', 'Korean', '한국어 (韓國語), 조선말 (朝鮮語)', NULL, NULL),
(86, 'ku', 'Kurdish', 'Kurdî, كوردی‎', NULL, NULL),
(87, 'kj', 'Kwanyama, Kuanyama', 'Kuanyama', NULL, NULL),
(88, 'la', 'Latin', 'latine, lingua latina', NULL, NULL),
(89, 'lb', 'Luxembourgish, Letzeburgesch', 'Lëtzebuergesch', NULL, NULL),
(90, 'lg', 'Luganda', 'Luganda', NULL, NULL),
(91, 'li', 'Limburgish, Limburgan, Limburger', 'Limburgs', NULL, NULL),
(92, 'ln', 'Lingala', 'Lingála', NULL, NULL),
(93, 'lo', 'Lao', 'ພາສາລາວ', NULL, NULL),
(94, 'lt', 'Lithuanian', 'lietuvių kalba', NULL, NULL),
(95, 'lu', 'Luba-Katanga', '', NULL, NULL),
(96, 'lv', 'Latvian', 'latviešu valoda', NULL, NULL),
(97, 'gv', 'Manx', 'Gaelg, Gailck', NULL, NULL),
(98, 'mk', 'Macedonian', 'македонски јазик', NULL, NULL),
(99, 'mg', 'Malagasy', 'Malagasy fiteny', NULL, NULL),
(100, 'ms', 'Malay', 'bahasa Melayu, بهاس ملايو‎', NULL, NULL),
(101, 'ml', 'Malayalam', 'മലയാളം', NULL, NULL),
(102, 'mt', 'Maltese', 'Malti', NULL, NULL),
(103, 'mi', 'Māori', 'te reo Māori', NULL, NULL),
(104, 'mr', 'Marathi (Marāṭhī)', 'मराठी', NULL, NULL),
(105, 'mh', 'Marshallese', 'Kajin M̧ajeļ', NULL, NULL),
(106, 'mn', 'Mongolian', 'монгол', NULL, NULL),
(107, 'na', 'Nauru', 'Ekakairũ Naoero', NULL, NULL),
(108, 'nv', 'Navajo, Navaho', 'Diné bizaad, Dinékʼehǰí', NULL, NULL),
(109, 'nb', 'Norwegian Bokmål', 'Norsk bokmål', NULL, NULL),
(110, 'nd', 'North Ndebele', 'isiNdebele', NULL, NULL),
(111, 'ne', 'Nepali', 'नेपाली', NULL, NULL),
(112, 'ng', 'Ndonga', 'Owambo', NULL, NULL),
(113, 'nn', 'Norwegian Nynorsk', 'Norsk nynorsk', NULL, NULL),
(114, 'no', 'Norwegian', 'Norsk', NULL, NULL),
(115, 'ii', 'Nuosu', 'ꆈꌠ꒿ Nuosuhxop', NULL, NULL),
(116, 'nr', 'South Ndebele', 'isiNdebele', NULL, NULL),
(117, 'oc', 'Occitan', 'Occitan', NULL, NULL),
(118, 'oj', 'Ojibwe, Ojibwa', 'ᐊᓂᔑᓈᐯᒧᐎᓐ', NULL, NULL),
(119, 'cu', 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic', 'ѩзыкъ словѣньскъ', NULL, NULL),
(120, 'om', 'Oromo', 'Afaan Oromoo', NULL, NULL),
(121, 'or', 'Oriya', 'ଓଡ଼ିଆ', NULL, NULL),
(122, 'os', 'Ossetian, Ossetic', 'ирон æвзаг', NULL, NULL),
(123, 'pa', 'Panjabi, Punjabi', 'ਪੰਜਾਬੀ, پنجابی‎', NULL, NULL),
(124, 'pi', 'Pāli', 'पाऴि', NULL, NULL),
(125, 'fa', 'Persian', 'فارسی', NULL, NULL),
(126, 'pl', 'Polish', 'polski', NULL, NULL),
(127, 'ps', 'Pashto, Pushto', 'پښتو', NULL, NULL),
(128, 'pt', 'Portuguese', 'Português', NULL, NULL),
(129, 'qu', 'Quechua', 'Runa Simi, Kichwa', NULL, NULL),
(130, 'rm', 'Romansh', 'rumantsch grischun', NULL, NULL),
(131, 'rn', 'Kirundi', 'kiRundi', NULL, NULL),
(132, 'ro', 'Romanian, Moldavian, Moldovan', 'română', NULL, NULL),
(133, 'ru', 'Russian', 'русский язык', NULL, NULL),
(134, 'sa', 'Sanskrit (Saṁskṛta)', 'संस्कृतम्', NULL, NULL),
(135, 'sc', 'Sardinian', 'sardu', NULL, NULL),
(136, 'sd', 'Sindhi', 'सिन्धी, سنڌي، سندھی‎', NULL, NULL),
(137, 'se', 'Northern Sami', 'Davvisámegiella', NULL, NULL),
(138, 'sm', 'Samoan', 'gagana faa Samoa', NULL, NULL),
(139, 'sg', 'Sango', 'yângâ tî sängö', NULL, NULL),
(140, 'sr', 'Serbian', 'српски језик', NULL, NULL),
(141, 'gd', 'Scottish Gaelic; Gaelic', 'Gàidhlig', NULL, NULL),
(142, 'sn', 'Shona', 'chiShona', NULL, NULL),
(143, 'si', 'Sinhala, Sinhalese', 'සිංහල', NULL, NULL),
(144, 'sk', 'Slovak', 'slovenčina', NULL, NULL),
(145, 'sl', 'Slovene', 'slovenščina', NULL, NULL),
(146, 'so', 'Somali', 'Soomaaliga, af Soomaali', NULL, NULL),
(147, 'st', 'Southern Sotho', 'Sesotho', NULL, NULL),
(148, 'es', 'Spanish; Castilian', 'español, castellano', NULL, NULL),
(149, 'su', 'Sundanese', 'Basa Sunda', NULL, NULL),
(150, 'sw', 'Swahili', 'Kiswahili', NULL, NULL),
(151, 'ss', 'Swati', 'SiSwati', NULL, NULL),
(152, 'sv', 'Swedish', 'svenska', NULL, NULL),
(153, 'ta', 'Tamil', 'தமிழ்', NULL, NULL),
(154, 'te', 'Telugu', 'తెలుగు', NULL, NULL),
(155, 'tg', 'Tajik', 'тоҷикӣ, toğikī, تاجیکی‎', NULL, NULL),
(156, 'th', 'Thai', 'ไทย', NULL, NULL),
(157, 'ti', 'Tigrinya', 'ትግርኛ', NULL, NULL),
(158, 'bo', 'Tibetan Standard, Tibetan, Central', 'བོད་ཡིག', NULL, NULL),
(159, 'tk', 'Turkmen', 'Türkmen, Түркмен', NULL, NULL),
(160, 'tl', 'Tagalog', 'Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔', NULL, NULL),
(161, 'tn', 'Tswana', 'Setswana', NULL, NULL),
(162, 'to', 'Tonga (Tonga Islands)', 'faka Tonga', NULL, NULL),
(163, 'tr', 'Turkish', 'Türkçe', NULL, NULL),
(164, 'ts', 'Tsonga', 'Xitsonga', NULL, NULL),
(165, 'tt', 'Tatar', 'татарча, tatarça, تاتارچا‎', NULL, NULL),
(166, 'tw', 'Twi', 'Twi', NULL, NULL),
(167, 'ty', 'Tahitian', 'Reo Tahiti', NULL, NULL),
(168, 'ug', 'Uighur, Uyghur', 'Uyƣurqə, ئۇيغۇرچە‎', NULL, NULL),
(169, 'uk', 'Ukrainian', 'українська', NULL, NULL),
(170, 'ur', 'Urdu', 'اردو', NULL, NULL),
(171, 'uz', 'Uzbek', 'zbek, Ўзбек, أۇزبېك‎', NULL, NULL),
(172, 've', 'Venda', 'Tshivenḓa', NULL, NULL),
(173, 'vi', 'Vietnamese', 'Tiếng Việt', NULL, NULL),
(174, 'vo', 'Volapük', 'Volapük', NULL, NULL),
(175, 'wa', 'Walloon', 'Walon', NULL, NULL),
(176, 'cy', 'Welsh', 'Cymraeg', NULL, NULL),
(177, 'wo', 'Wolof', 'Wollof', NULL, NULL),
(178, 'fy', 'Western Frisian', 'Frysk', NULL, NULL),
(179, 'xh', 'Xhosa', 'isiXhosa', NULL, NULL),
(180, 'yi', 'Yiddish', 'ייִדיש', NULL, NULL),
(181, 'yo', 'Yoruba', 'Yorùbá', NULL, NULL),
(182, 'za', 'Zhuang, Chuang', 'Saɯ cueŋƅ, Saw cuengh', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_cards`
--

CREATE TABLE `loyalty_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_points` int DEFAULT NULL,
  `per_order_minimum_amount` int DEFAULT NULL,
  `per_order_points` int DEFAULT NULL,
  `per_purchase_minimum_amount` int DEFAULT NULL,
  `amount_per_loyalty_point` int DEFAULT NULL,
  `redeem_points_per_primary_currency` int DEFAULT NULL,
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Active, 1-Deactive, 2-Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `loyalty_check` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '0-Active, 1-Deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `luxury_options`
--

CREATE TABLE `luxury_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `luxury_options`
--

INSERT INTO `luxury_options` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'delivery', NULL, NULL),
(2, 'dine_in', NULL, NULL),
(3, 'takeaway', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `map_providers`
--

CREATE TABLE `map_providers` (
  `id` bigint UNSIGNED NOT NULL,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT ' 0 for no, 1 for yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `map_providers`
--

INSERT INTO `map_providers` (`id`, `provider`, `keyword`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Google Map', 'google_map', 1, NULL, NULL),
(2, 'Map Box', 'map_box', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_admins_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_09_17_051112_create_api_logs_table', 1),
(5, '2019_10_13_000000_create_social_credentials_table', 1),
(6, '2020_12_07_100603_create_types_table', 1),
(7, '2020_12_07_103301_create_countries_table', 1),
(8, '2020_12_07_103302_create_currencies_table', 1),
(9, '2020_12_07_103380_create_languages_table', 1),
(10, '2020_12_07_103418_create_notification_types_table', 1),
(11, '2020_12_07_103419_create_blocked_tokens_table', 1),
(12, '2020_12_21_104934_create_clients_table', 1),
(13, '2020_12_21_120042_create_roles_table', 1),
(14, '2020_12_21_135144_create_users_table', 1),
(15, '2020_12_24_103343_create_map_providers_table', 1),
(16, '2020_12_24_104834_create_sms_providers_table', 1),
(17, '2020_12_25_114722_create_templates_table', 1),
(18, '2020_12_25_124722_create_client_preferences_table', 1),
(19, '2020_12_29_121021_create_client_languages_table', 1),
(20, '2020_12_30_053607_create_vendors_table', 1),
(21, '2020_12_30_060809_create_vendor_users_table', 1),
(22, '2020_12_30_061924_create_service_areas_table', 1),
(23, '2020_12_30_095943_create_cms_table', 1),
(24, '2020_12_31_142820_create_categories_table', 1),
(25, '2020_12_31_142836_create_category_translations_table', 1),
(26, '2020_12_31_165834_create_banners_table', 1),
(27, '2020_12_31_263025_create_vendor_slots_table', 1),
(28, '2020_12_31_323159_create_vendor_slot_dates_table', 1),
(29, '2020_12_31_325657_create_slot_days_table', 1),
(30, '2021_01_14_063531_create_client_currencies_table', 1),
(31, '2021_01_14_114953_create_variants_table', 1),
(32, '2021_01_14_115058_create_variant_categories_table', 1),
(33, '2021_01_14_115141_create_variant_translations_table', 1),
(34, '2021_01_14_115200_create_variant_options_table', 1),
(35, '2021_01_14_115217_create_variant_option_translations_table', 1),
(36, '2021_01_14_135222_create_brands_table', 1),
(37, '2021_01_18_141503_create_brand_categories_table', 1),
(38, '2021_01_18_141534_create_brand_translations_table', 1),
(39, '2021_01_19_103352_create_category_histories_table', 1),
(40, '2021_01_19_125204_create_tax_categories_table', 1),
(41, '2021_01_19_125318_create_tax_rates_table', 1),
(42, '2021_01_19_125451_create_tax_rate_categories_table', 1),
(43, '2021_01_20_114648_create_addon_sets_table', 1),
(44, '2021_01_20_114706_create_addon_set_translations_table', 1),
(45, '2021_01_20_114724_create_addon_options_table', 1),
(46, '2021_01_20_114734_create_addon_option_translations_table', 1),
(47, '2021_01_21_101637_create_vendor_media_table', 1),
(48, '2021_01_21_112832_create_products_table', 1),
(49, '2021_01_21_112848_create_product_translations_table', 1),
(50, '2021_01_22_101046_create_product_categories_table', 1),
(51, '2021_01_22_113717_create_product_addons_table', 1),
(52, '2021_01_22_113948_create_product_cross_sells_table', 1),
(53, '2021_01_22_114102_create_product_up_sells_table', 1),
(54, '2021_01_22_114129_create_product_related_table', 1),
(55, '2021_01_22_134800_create_product_variants_table', 1),
(56, '2021_01_22_141044_create_product_variant_sets_table', 1),
(57, '2021_02_01_101734_create_product_images_table', 1),
(58, '2021_02_03_052127_create_product_variant_images_table', 1),
(59, '2021_02_19_061327_create_user_devices_table', 1),
(60, '2021_02_20_100004_create_terminologies_table', 1),
(61, '2021_02_20_100026_create_accounts_table', 1),
(62, '2021_02_20_100053_create_reports_table', 1),
(63, '2021_02_26_094556_create_category_tags_table', 1),
(64, '2021_03_09_074202_add_port_to_clients_table', 1),
(65, '2021_03_25_064409_add_social_login_field', 1),
(66, '2021_03_25_072244_add_brands_field', 1),
(67, '2021_04_14_055025_create_user_wishlists_table', 1),
(68, '2021_04_15_125922_create_loyalty_cards_table', 1),
(69, '2021_04_16_074202_add_image_to_users_table', 1),
(70, '2021_04_20_054439_create_carts_table', 1),
(71, '2021_04_20_055234_create_promo_types_table', 1),
(72, '2021_04_20_055359_create_promocodes_table', 1),
(73, '2021_04_20_055509_create_promocode_restrictions_table', 1),
(74, '2021_04_20_055624_create_cart_coupons_table', 1),
(75, '2021_04_20_055625_create_cart_products_table', 1),
(76, '2021_04_20_092608_create_user_addresses_table', 1),
(77, '2021_04_28_041838_create_cart_addons_table', 1),
(78, '2021_05_03_092015_create_orders_table', 1),
(79, '2021_05_04_070811_create_order_vendors_table', 1),
(80, '2021_05_04_071200_create_order_products_table', 1),
(81, '2021_05_04_071929_create_order_product_addons_table', 1),
(82, '2021_05_05_080739_add_currency_field_in_cart_products', 1),
(83, '2021_05_07_145709_create_promo_usages_table', 1),
(84, '2021_05_10_034916_add_fields_to_promocodes_table', 1),
(85, '2021_05_10_052517_create_celebrities_table', 1),
(86, '2021_05_10_131738_create_promocode_details_table', 1),
(87, '2021_05_11_124314_add_fields_to_preferences_table', 1),
(88, '2021_05_12_050135_create_user_loyalty_points_table', 1),
(89, '2021_05_12_050252_create_user_loyalty_point_histories', 1),
(90, '2021_05_12_050529_alter_promocode_image_field_table', 1),
(91, '2021_05_12_100036_create_payments_table', 1),
(92, '2021_05_12_102501_alter_promocodes_short_desc_field_table', 1),
(93, '2021_05_12_115651_create_wallets_table', 1),
(94, '2021_05_12_123912_create_wallet_histories_table', 1),
(95, '2021_05_13_053118_create_refer_and_earns_table', 1),
(96, '2021_05_13_080525_create_user_refferals_table', 1),
(97, '2021_05_13_102448_alter_cart_coupons_for_vendor_ids_field_table', 1),
(98, '2021_05_13_153007_create_celebrity_brand_table', 1),
(99, '2021_05_14_083633_add_status_to_orders', 1),
(100, '2021_05_14_084221_add_order_id_to_payments', 1),
(101, '2021_05_14_141254_add_country_id_to_celebrities', 1),
(102, '2021_05_17_092828_create_product_celebrities_table', 1),
(103, '2021_05_17_141254_add_description_to_celebrities', 1),
(104, '2021_05_19_042503_create_payment_options_table', 1),
(105, '2021_05_19_042544_create_vendor_payment_options_table', 1),
(106, '2021_05_20_065410_alter_orders_table_order_no', 1),
(107, '2021_05_20_123811_create_jobs_table', 1),
(108, '2021_05_21_045823_add_phonecode_in_address_table', 1),
(109, '2021_05_21_045823_change_county_field_type_table', 1),
(110, '2021_05_21_082602_alter_order_products_table', 1),
(111, '2021_05_21_103918_alter_order_products_for_rename_table_table', 1),
(112, '2021_05_21_113803_create_vendor_categories_table', 1),
(113, '2021_05_25_100950_alter_order_vendors_table', 1),
(114, '2021_05_25_104437_alter_order_vendors_table_for_coupon_code', 1),
(115, '2021_05_27_091733_add_wishlist_field_in_categories_table', 1),
(116, '2021_05_27_112637_add_status_in_product_variants_table', 1),
(117, '2021_05_28_051503_add_timezone_in_users_table', 1),
(118, '2021_05_31_062720_add_category_switch_in_vendors_table', 1),
(119, '2021_05_31_090803_create_vendor_templetes_table', 1),
(120, '2021_05_31_091703_add_templete_id_in_vendors_table', 1),
(121, '2021_06_01_043327_create_timezones_table', 1),
(122, '2021_06_01_043453_create_order_status_table', 1),
(123, '2021_06_01_051302_add_timezone_field_users_table', 1),
(124, '2021_06_01_113407_alter_promo_codes_table', 1),
(125, '2021_06_03_061348_add_delete_at_products_table', 1),
(126, '2021_06_09_043732_add_loyalty_points_earned_orders_table', 2),
(127, '2021_06_09_074633_alter_cart_addons_table', 2),
(128, '2021_06_10_075653_add_credentials_to_payment_options', 3),
(129, '2021_06_10_094428_alter_categories_table_for_deleted_at', 3),
(130, '2021_06_08_094834_create_csv_product_imports_table', 4),
(131, '2021_06_11_083205_create_csv_vendor_imports_table', 4),
(132, '2021_05_21_063543_permission_table_for_acl', 5),
(133, '2021_05_21_074707_user_permissions_table_for_acl', 5),
(134, '2021_05_25_094250_user_vendors_table_for_acl', 5),
(135, '2021_05_28_052713_add_code_in_user', 5),
(136, '2021_05_28_052713_add_superadmin_in_client', 5),
(137, '2021_05_28_052713_add_superadmin_in_user', 5),
(138, '2021_06_10_074037_alter_preference_dispatch_new_keys', 5),
(139, '2021_06_14_050458_create_app_stylings_table', 5),
(140, '2021_06_14_050649_create_app_styling_options_table', 5),
(141, '2021_06_14_065037_alter_currency_id_cart_products_table', 5),
(142, '2021_06_14_105745_drop_vendor_payment_options_table', 6),
(143, '2021_06_15_102257_rename_order_status_table', 6),
(144, '2021_06_15_102631_create_dispatcher_status_options_table', 6),
(145, '2021_06_15_103435_create_order_statuses_table', 6),
(146, '2021_06_15_103450_create_dispatcher_statuses_table', 6),
(147, '2021_06_16_051848_rename_order_status2_table', 7),
(148, '2021_06_16_052121_add_vendor_id_to_order_status', 7),
(149, '2021_06_16_052903_rename_dispatcher_statuses_table', 7),
(150, '2021_06_16_052952_add_vendor_id_to_dispatcher_status', 7),
(151, '2021_06_16_064425_add_timezone_to_user_table', 8),
(152, '2021_06_16_110618_add_refer_and_earn_columns_to_client_preferences', 8),
(153, '2021_06_16_132604_drop_wallets_table', 8),
(154, '2018_11_06_222923_create_transactions_table', 9),
(155, '2018_11_07_192923_create_transfers_table', 9),
(156, '2018_11_07_202152_update_transfers_table', 9),
(157, '2018_11_15_124230_create_wallets_table', 9),
(158, '2018_11_19_164609_update_transactions_table', 9),
(159, '2018_11_20_133759_add_fee_transfers_table', 9),
(160, '2018_11_22_131953_add_status_transfers_table', 9),
(161, '2018_11_22_133438_drop_refund_transfers_table', 9),
(162, '2019_05_13_111553_update_status_transfers_table', 9),
(163, '2019_06_25_103755_add_exchange_status_transfers_table', 9),
(164, '2019_07_29_184926_decimal_places_wallets_table', 9),
(165, '2019_10_02_193759_add_discount_transfers_table', 9),
(166, '2020_10_30_193412_add_meta_wallets_table', 9),
(167, '2021_06_17_044423_alter_order_vendor_web_hook_code_table', 10),
(168, '2021_06_18_085437_drop_reffered_by_from_user_refferals_table', 11),
(169, '2021_06_18_112628_alter_some_table_for_ref', 12),
(170, '2021_06_21_064857_add_total_delivery_fee_to_orders', 13),
(171, '2021_06_21_094217_add_template_id_field_to_app_styling_options_table', 14),
(172, '2021_06_21_123621_alter_user_user_id_field_for_order_vendors_table', 15),
(173, '2021_06_21_123928_add_web_color_field_to_client_preferences_table', 16),
(174, '2021_06_18_072955_addtrackingurlinordervendor', 17),
(175, '2021_06_21_065449_orderproductratingstable', 17),
(176, '2021_06_22_062736_createreviewfilestable', 17),
(177, '2021_06_22_063355_alterorderproductratingsstatustable', 17),
(178, '2021_06_24_044626_add_description_to_users_table', 18),
(179, '2021_06_23_063853_add_pharmacy_check_field_to_client_preferences_table', 19),
(180, '2021_06_24_045847_add_pharmacy_check_field_to_products_table', 19),
(181, '2021_06_24_064903_create_cart_product_prescriptions_table', 19),
(182, '2021_06_24_122153_create_order_product_prescriptions_table', 19),
(183, '2021_06_25_045048_create_dispatcher_template_type_options_table', 20),
(184, '2021_06_25_052752_create_dispatcher_warning_pages_table', 20),
(185, '2021_06_25_083707_alter_category_table_for_dipatcher_field_in_tables', 20),
(186, '2021_06_25_053039_add_vendor_id_field_to_order_product_prescriptions_table', 21),
(187, '2021_06_25_054358_add_vendor_id_field_to_cart_product_prescriptions_table', 21),
(188, '2021_06_28_110323_add_category_id_field_to_order_products_table', 22),
(189, '2021_06_28_112229_alter_type_table_for_sequences_field_in_tables', 22),
(190, '2021_06_28_134738_alter_vendor_for_slug_tables', 23),
(191, '2021_06_29_045943_add_admin_commission_fields_to_order_vendors_table', 24),
(192, '2021_06_29_063720_add_actual_amount_fields_to_order_vendors_table', 24),
(193, '2021_06_29_084253_add_taxable_amount_fields_to_order_vendors_table', 25),
(194, '2021_06_29_094113_change_taxable_amount_fields_to_order_vendors_table', 26),
(195, '2021_06_29_102709_createtableorderreturnrequests', 27),
(196, '2021_06_29_121116_change_limit_fields_to_promocodes_table', 28),
(197, '2021_06_30_053518_createtablereturn_reasons', 29),
(198, '2021_06_30_095821_createtablereturnrequestfiles', 29),
(199, '2021_06_30_130644_alter_vendor_orders_add_payment_option_id_table', 30),
(200, '2021_06_30_123018_add_image_path_field_to_dispatcher_template_type_options_table', 31),
(201, '2021_06_30_123341_add_image_path_field_to_dispatcher_warning_pages_table', 31),
(202, '2021_07_01_072413_add_dine_in_fields_to_client_preferences_table', 32),
(203, '2021_07_01_071008_alter_orders_for_loyalty_membership_id_table', 33),
(204, '2021_07_01_124823_create_order_taxes_table', 34),
(205, '2021_07_02_123701_alter_order_vendor_products_for_order_vendor_id', 35),
(206, '2021_07_05_045644_add_slug_in_celebrities_table', 36),
(207, '2021_07_05_075226_alter_types_for_images_table', 37),
(208, '2021_07_05_104133_addreasonbyvendorinreturnrequests', 38),
(209, '2021_07_05_123711_add_loyalty_check_field_to_loyalty_cards_table', 39),
(210, '2021_07_06_045605_alter_vendors_table_forfew_fields', 40),
(211, '2021_07_06_084032_create_subscription_validities_table', 41),
(212, '2021_07_06_091605_create_social_media_table', 41),
(213, '2021_07_06_091729_create_subscription_features_list_table', 41),
(214, '2021_07_06_095637_create_user_subscriptions_table', 41),
(215, '2021_07_06_095655_create_user_subscription_features_table', 41),
(216, '2021_07_07_054636_create_luxury_options_table', 42),
(217, '2021_07_07_055509_add_luxury_option_id_to_cart_products_table', 42),
(218, '2021_07_07_092630_create_vendor_subscriptions_table', 43),
(219, '2021_07_07_092704_create_vendor_subscription_features_table', 43),
(220, '2021_07_07_104413_alter_client_preferences_table_for_cart_enable', 44),
(221, '2021_07_07_114341_alter_products_table_for_enquire_mod', 45),
(222, '2021_07_07_122135_add_rating_check_to_client_preferences_table', 46),
(223, '2021_07_06_054045_addpickupdeliverykeysinclientprefereance', 47),
(224, '2021_07_08_050906_addtagkeyinproducttable', 47),
(225, '2021_07_08_060154_create_product_inquiries_table', 48),
(226, '2021_07_08_064807_add_vendor_id_to_product_inquiries_table', 48),
(227, '2021_07_08_070945_create_pages_table', 48),
(228, '2021_07_08_105308_alter_pages_table', 49),
(229, '2021_07_08_132134_alter_wallets_table', 50),
(230, '2021_07_09_052529_alter_user_subscriptions_table', 51),
(231, '2021_07_09_063048_alter_vendor_subscriptions_table', 51),
(232, '2021_07_09_064149_create_page_translations_table', 51),
(233, '2021_07_09_091843_create_subscribed_status_options_table', 52),
(234, '2021_07_09_072813_create_subscribed_users_table', 53),
(235, '2020_01_01_000001_create_plans_table', 54),
(236, '2020_01_01_000002_create_plan_features_table', 54),
(237, '2020_01_01_000003_create_plan_subscriptions_table', 54),
(238, '2020_01_01_000004_create_plan_subscription_usage_table', 54),
(239, '2021_07_12_101144_add_phone_code_field_to_users_table', 54),
(240, '2021_07_12_105145_rename_phone_code_in_users_table', 54),
(241, '2021_07_12_130335_alter_payment_options_table', 55),
(242, '2021_07_15_141847_create_subscription_status_options_table', 56),
(243, '2021_07_16_042043_create_subscription_features_list_user_table', 56),
(244, '2021_07_16_042211_create_subscription_plans_user_table', 56),
(245, '2021_07_16_042346_create_subscription_plan_features_user_table', 56),
(246, '2021_07_16_042524_create_subscription_invoices_user_table', 56),
(247, '2021_07_16_042848_create_subscription_log_user_table', 56),
(248, '2021_07_16_045752_create_subscription_invoice_features_user_table', 56),
(249, '2021_07_16_061522_drop_subscriptions_table', 56),
(250, '2021_07_16_054443_create_vendor_registration_documents_table', 57),
(251, '2021_07_16_083525_alter_subscription_plans_user_table', 57),
(252, '2021_07_16_092718_create_subscription_features_list_vendor_table', 57),
(253, '2021_07_16_092754_create_subscription_plans_vendor_table', 57),
(254, '2021_07_16_092836_create_subscription_plan_features_vendor_table', 57),
(255, '2021_07_16_100042_create_vendor_docs_table', 57),
(256, '2021_07_16_120516_add_frequency_to_subscription_plans_user_table', 58),
(257, '2021_07_16_121019_add_frequency_to_subscription_plans_vendor_table', 58),
(258, '2021_07_16_121201_add_frequency_to_subscription_invoices_user_table', 58),
(259, '2021_07_16_105955_alter_user_table_for_title', 59),
(260, '2021_07_19_052218_alter_vendor_registration_document_translations_table', 59),
(261, '2021_07_19_081612_create_nomenclatures_table', 60),
(262, '2021_07_19_083627_alter_vendors_table_for_is_show_category_details', 60);

-- --------------------------------------------------------

--
-- Table structure for table `nomenclatures`
--

CREATE TABLE `nomenclatures` (
  `id` bigint UNSIGNED NOT NULL,
  `label` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_types`
--

CREATE TABLE `notification_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_option_id` tinyint NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `address_id` bigint UNSIGNED DEFAULT NULL,
  `is_deleted` tinyint NOT NULL COMMENT '0-No, 1-Yes',
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `loyalty_membership_id` int UNSIGNED DEFAULT NULL,
  `loyalty_points_used` decimal(10,2) DEFAULT NULL,
  `loyalty_amount_saved` decimal(10,2) DEFAULT NULL,
  `loyalty_points_earned` decimal(10,2) DEFAULT NULL,
  `paid_via_wallet` tinyint NOT NULL COMMENT '0-No, 1-Yes',
  `paid_via_loyalty` tinyint NOT NULL COMMENT '0-No, 1-Yes',
  `total_amount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `total_discount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `total_delivery_fee` decimal(8,2) DEFAULT NULL,
  `taxable_amount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `payable_amount` decimal(8,2) UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Credit Card, 2 - Cash On Delivery, 3 - Paypal, 4 - Wallet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `created_by`, `order_number`, `payment_option_id`, `user_id`, `address_id`, `is_deleted`, `currency_id`, `loyalty_membership_id`, `loyalty_points_used`, `loyalty_amount_saved`, `loyalty_points_earned`, `paid_via_wallet`, `paid_via_loyalty`, `total_amount`, `total_discount`, `total_delivery_fee`, `taxable_amount`, `payable_amount`, `tax_category_id`, `created_at`, `updated_at`, `payment_method`) VALUES
(1, NULL, '69718547', 1, 6, 1, 0, NULL, 0, '0.00', '0.00', '0.00', 0, 0, '15.00', '0.00', '0.00', '0.00', '30.00', NULL, '2021-07-19 12:03:40', '2021-07-19 12:03:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_product_addons`
--

CREATE TABLE `order_product_addons` (
  `id` bigint UNSIGNED NOT NULL,
  `order_product_id` bigint UNSIGNED NOT NULL,
  `addon_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_prescriptions`
--

CREATE TABLE `order_product_prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `prescription` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_ratings`
--

CREATE TABLE `order_product_ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `order_vendor_product_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `rating` decimal(4,2) DEFAULT NULL,
  `review` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_rating_files`
--

CREATE TABLE `order_product_rating_files` (
  `id` bigint UNSIGNED NOT NULL,
  `order_product_rating_id` bigint UNSIGNED NOT NULL,
  `file` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `order_return_requests`
--

CREATE TABLE `order_return_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `order_vendor_product_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `return_by` bigint UNSIGNED NOT NULL,
  `reason` varchar(220) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coments` text COLLATE utf8mb4_unicode_ci,
  `reason_by_vendor` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` enum('Pending','Accepted','Rejected','On-Hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `order_return_request_files`
--

CREATE TABLE `order_return_request_files` (
  `id` bigint UNSIGNED NOT NULL,
  `order_return_request_id` bigint UNSIGNED NOT NULL,
  `file` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_options`
--

CREATE TABLE `order_status_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1 - for order, 2 - fordispatch',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 - active, 0 - inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_options`
--

INSERT INTO `order_status_options` (`id`, `title`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, __('Placed'), 1, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55'),
(2, 'Accepted', 1, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55'),
(3, 'Rejected', 1, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55'),
(4, 'Processing', 1, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55'),
(5, 'Out For Delivery', 1, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55'),
(6, 'Delivered', 1, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55'),
(7, 'Accept', 2, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55'),
(8, 'Reject', 2, 1, '2021-07-09 10:51:55', '2021-07-09 10:51:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_taxes`
--

CREATE TABLE `order_taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `tax_category_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_vendors`
--

CREATE TABLE `order_vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `delivery_fee` decimal(8,2) UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL COMMENT '0-Created, 1-Confirmed, 2-Dispatched, 3-Delivered',
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxable_amount` decimal(10,2) DEFAULT NULL,
  `subtotal_amount` decimal(10,2) DEFAULT NULL,
  `payable_amount` decimal(8,2) DEFAULT NULL,
  `discount_amount` decimal(8,2) DEFAULT NULL,
  `web_hook_code` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_commission_percentage_amount` decimal(10,2) DEFAULT NULL,
  `admin_commission_fixed_amount` decimal(10,2) DEFAULT NULL,
  `coupon_paid_by` tinyint DEFAULT '1' COMMENT '0-Vendor, 1-Admin',
  `payment_option_id` tinyint DEFAULT NULL,
  `order_status_option_id` tinyint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dispatch_traking_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `order_vendors`
--

INSERT INTO `order_vendors` (`id`, `order_id`, `vendor_id`, `user_id`, `delivery_fee`, `status`, `coupon_id`, `coupon_code`, `taxable_amount`, `subtotal_amount`, `payable_amount`, `discount_amount`, `web_hook_code`, `admin_commission_percentage_amount`, `admin_commission_fixed_amount`, `coupon_paid_by`, `payment_option_id`, `order_status_option_id`, `created_at`, `updated_at`, `dispatch_traking_url`) VALUES
(1, 1, 7, 6, NULL, 0, NULL, NULL, '0.00', '30.00', '30.00', '0.00', NULL, '6.00', '10.00', 1, 1, 6, '2021-07-19 12:03:40', '2021-07-19 12:05:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_vendor_products`
--

CREATE TABLE `order_vendor_products` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `order_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `taxable_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `order_vendor_products`
--

INSERT INTO `order_vendor_products` (`id`, `order_id`, `product_id`, `order_vendor_id`, `quantity`, `product_name`, `image`, `price`, `taxable_amount`, `vendor_id`, `created_by`, `variant_id`, `tax_category_id`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 1, 3, 1, 2, 'cg301', 'prods/Sux19prmMH1dhQTLNtlHeC0Gv40ibyRHyfXCn5aU.jpg', '15.00', NULL, 7, NULL, 7, NULL, '2021-07-19 12:03:40', '2021-07-19 12:03:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'privacy-policy', '2021-07-14 13:27:43', '2021-07-14 13:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `page_translations`
--

CREATE TABLE `page_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `meta_title` mediumtext COLLATE utf8mb4_unicode_ci,
  `meta_keyword` mediumtext COLLATE utf8mb4_unicode_ci,
  `meta_description` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint NOT NULL DEFAULT '0' COMMENT '0 draft and 1 for published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `page_translations`
--

INSERT INTO `page_translations` (`id`, `title`, `description`, `page_id`, `language_id`, `meta_title`, `meta_keyword`, `meta_description`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'Privacy Policy', '<p>Test account&nbsp;</p>', 1, 148, 'Privacy Policy', 'Privacy Policy', 'Privacy Policy', 0, '2021-07-14 13:27:43', '2021-07-14 13:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `amount` int NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance_transaction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `cart_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `payment_options`
--

CREATE TABLE `payment_options` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` json DEFAULT NULL COMMENT 'credentials in json format',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 inactive, 1 active, 2 delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `off_site` tinyint UNSIGNED DEFAULT '0' COMMENT '0 = on-site, 1 = off-site'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `payment_options`
--

INSERT INTO `payment_options` (`id`, `code`, `path`, `title`, `credentials`, `status`, `created_at`, `updated_at`, `off_site`) VALUES
(1, 'cod', '', 'Cash On Delivery', NULL, 1, NULL, NULL, 0),
(3, 'paypal', 'omnipay/paypal', 'PayPal', NULL, 1, NULL, NULL, 1),
(4, 'stripe', 'omnipay/targetpay', 'Stripe', NULL, 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'DASHBOARD', 'dashboard', NULL, NULL),
(2, 'ORDERS', 'orders', NULL, NULL),
(3, 'VENDORS', 'vendors', NULL, NULL),
(4, 'CUSTOMERS', 'customers', NULL, NULL),
(5, 'Profile', 'profile', NULL, NULL),
(6, 'CUSTOMIZE', 'customize', NULL, NULL),
(7, 'CONFIGURATIONS', 'configurations', NULL, NULL),
(8, 'BANNER', 'banner', NULL, NULL),
(9, 'CATALOG', 'catalog', NULL, NULL),
(10, 'TAX', 'tax', NULL, NULL),
(11, 'PAYMENT', 'payment', NULL, NULL),
(12, 'PROMOCODE', 'promocode', NULL, NULL),
(13, 'LOYALTY CARDS', 'loyalty_cards', NULL, NULL),
(14, 'CELEBRITY', 'celebrity', NULL, NULL),
(15, 'WEB STYLING', 'web_styling', NULL, NULL),
(16, 'APP STYLING', 'app_styling', NULL, NULL),
(17, 'Accounting Orders', 'accounting_orders', NULL, NULL),
(18, 'Accounting Loyality', 'accounting_loyality', NULL, NULL),
(19, 'Accounting Promo Codes', 'accounting_promo_codes', NULL, NULL),
(20, 'Accounting Taxes', 'accounting_taxes', NULL, NULL),
(21, 'Accounting Vendors', 'accounting_vendors', NULL, NULL),
(22, 'Subscriptions Customers', 'subscriptions_customers', NULL, NULL),
(23, 'Subscriptions Vendors', 'subscriptions_vendors', NULL, NULL),
(24, 'CMS Pages', 'cms_pages', NULL, NULL),
(25, 'CMS Emails', 'cms_emails', NULL, NULL),
(26, 'Inquiries', 'inquiries', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `body_html` longtext COLLATE utf8mb4_unicode_ci,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `type_id` bigint UNSIGNED DEFAULT NULL,
  `country_origin_id` bigint UNSIGNED DEFAULT NULL,
  `is_new` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `is_featured` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `is_live` tinyint NOT NULL DEFAULT '0' COMMENT '0 - draft, 1 - published, 2 - blocked',
  `is_physical` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `weight` decimal(10,4) DEFAULT NULL,
  `weight_unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_inventory` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `has_variant` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `sell_when_out_of_stock` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `requires_shipping` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `Requires_last_mile` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `averageRating` decimal(4,2) DEFAULT NULL,
  `inquiry_only` tinyint DEFAULT '0',
  `publish_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `pharmacy_check` tinyint DEFAULT '0' COMMENT '0-No, 1-Yes',
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `title`, `url_slug`, `description`, `body_html`, `vendor_id`, `category_id`, `type_id`, `country_origin_id`, `is_new`, `is_featured`, `is_live`, `is_physical`, `weight`, `weight_unit`, `has_inventory`, `has_variant`, `sell_when_out_of_stock`, `requires_shipping`, `Requires_last_mile`, `averageRating`, `inquiry_only`, `publish_at`, `created_at`, `updated_at`, `brand_id`, `tax_category_id`, `deleted_at`, `pharmacy_check`, `tags`) VALUES
(1, 'sku-id', '1', 'sku-id', NULL, NULL, 1, NULL, 1, NULL, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(2, '234', NULL, '234', NULL, NULL, 3, NULL, 1, 1, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0, '2021-06-09 15:22:07', '2021-06-09 15:14:31', '2021-06-09 15:22:07', NULL, NULL, NULL, 0, NULL),
(3, 'cg301', NULL, 'cg301', NULL, NULL, 7, 3, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0, '2021-07-19 09:05:41', '2021-07-19 09:01:22', '2021-07-19 09:05:41', NULL, NULL, NULL, 0, NULL),
(4, 'cg302', NULL, 'cg302', NULL, NULL, 7, 3, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0, '2021-07-19 09:08:27', '2021-07-19 09:07:35', '2021-07-19 09:08:55', NULL, NULL, NULL, 0, NULL),
(5, 'cg303', NULL, 'cg303', NULL, NULL, 7, 3, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0, '2021-07-19 09:10:24', '2021-07-19 09:09:46', '2021-07-19 09:10:24', NULL, NULL, NULL, 0, NULL),
(6, 'cg304', NULL, 'cg304', NULL, NULL, 7, 3, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0, '2021-07-19 09:13:41', '2021-07-19 09:12:59', '2021-07-19 09:13:41', NULL, NULL, NULL, 0, NULL),
(7, 'SA123', 'Israeli salad', 'SA123', NULL, '', 9, NULL, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 0, NULL, 0, '2021-07-19 12:35:02', NULL, '2021-07-19 12:35:02', NULL, NULL, NULL, 0, NULL),
(8, 'SA124', 'Waldorf salad', 'SA124', NULL, '', 9, NULL, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(9, 'SA125', 'Gado-gado', 'SA125', NULL, '', 9, NULL, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(10, 'SA126', 'Nicoise salad', 'SA126', NULL, '', 9, NULL, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(11, 'SA127', 'Dressed herring salad', 'SA127', NULL, '', 9, NULL, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(12, 'SA128', 'Larb', 'SA128', NULL, '', 9, NULL, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_addons`
--

CREATE TABLE `product_addons` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `addon_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, NULL),
(3, 3, '2021-07-19 09:01:22', '2021-07-19 09:01:22'),
(4, 3, '2021-07-19 09:07:35', '2021-07-19 09:07:35'),
(5, 3, '2021-07-19 09:09:46', '2021-07-19 09:09:46'),
(6, 3, '2021-07-19 09:12:59', '2021-07-19 09:12:59'),
(7, NULL, '2021-07-19 12:35:02', '2021-07-19 12:35:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_celebrities`
--

CREATE TABLE `product_celebrities` (
  `celebrity_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `product_cross_sells`
--

CREATE TABLE `product_cross_sells` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `cross_product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `media_id` bigint UNSIGNED DEFAULT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0' COMMENT '0 - no, 1 - yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `media_id`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, NULL, NULL),
(2, 3, 2, 1, NULL, NULL),
(3, 4, 3, 1, NULL, NULL),
(4, 5, 4, 1, NULL, NULL),
(5, 6, 5, 1, NULL, NULL),
(6, 7, 6, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_inquiries`
--

CREATE TABLE `product_inquiries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `product_related`
--

CREATE TABLE `product_related` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `related_product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `product_translations`
--

CREATE TABLE `product_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_html` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `product_translations`
--

INSERT INTO `product_translations` (`id`, `title`, `body_html`, `meta_title`, `meta_keyword`, `meta_description`, `product_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Xiaomi', NULL, 'Xiaomi', 'Xiaomi', NULL, 1, 1, NULL, NULL),
(2, 'Mangoes pack for 12 mangoes', '<p><b>Fresh mangoes</b></p><p><b>Pack for 12 Mangoes</b></p>', 'Pack for 12 Mangoes', 'Pack for 12 Mangoes', 'Pack for 12 Mangoes', 2, 148, NULL, '2021-06-09 15:22:07'),
(3, 'Campfire Caprese Brie', NULL, NULL, NULL, NULL, 3, 148, NULL, '2021-07-19 09:03:16'),
(4, 'Grilled Zucchini', NULL, NULL, NULL, NULL, 4, 148, NULL, '2021-07-19 09:08:27'),
(5, 'Grilled Ahi Tuna', '<p><br></p>', NULL, NULL, NULL, 5, 148, NULL, '2021-07-19 09:10:24'),
(6, 'Grilled Cabbage Steaks', NULL, NULL, NULL, NULL, 6, 148, NULL, '2021-07-19 09:13:41'),
(7, 'Israeli salad', NULL, NULL, NULL, NULL, 7, 148, NULL, '2021-07-19 12:35:02'),
(8, 'Israeli salad', '', '', '', '', 7, 148, NULL, NULL),
(9, 'Waldorf salad', '', '', '', '', 8, 148, NULL, NULL),
(10, 'Israeli salad', '', '', '', '', 7, 148, NULL, NULL),
(11, 'Waldorf salad', '', '', '', '', 8, 148, NULL, NULL),
(12, 'Gado-gado', '', '', '', '', 9, 148, NULL, NULL),
(13, 'Israeli salad', '', '', '', '', 7, 148, NULL, NULL),
(14, 'Waldorf salad', '', '', '', '', 8, 148, NULL, NULL),
(15, 'Gado-gado', '', '', '', '', 9, 148, NULL, NULL),
(16, 'Nicoise salad', '', '', '', '', 10, 148, NULL, NULL),
(17, 'Israeli salad', '', '', '', '', 7, 148, NULL, NULL),
(18, 'Waldorf salad', '', '', '', '', 8, 148, NULL, NULL),
(19, 'Gado-gado', '', '', '', '', 9, 148, NULL, NULL),
(20, 'Nicoise salad', '', '', '', '', 10, 148, NULL, NULL),
(21, 'Dressed herring salad', '', '', '', '', 11, 148, NULL, NULL),
(22, 'Israeli salad', '', '', '', '', 7, 148, NULL, NULL),
(23, 'Waldorf salad', '', '', '', '', 8, 148, NULL, NULL),
(24, 'Gado-gado', '', '', '', '', 9, 148, NULL, NULL),
(25, 'Nicoise salad', '', '', '', '', 10, 148, NULL, NULL),
(26, 'Dressed herring salad', '', '', '', '', 11, 148, NULL, NULL),
(27, 'Larb', '', '', '', '', 12, 148, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_up_sells`
--

CREATE TABLE `product_up_sells` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `upsell_product_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT NULL,
  `position` tinyint NOT NULL DEFAULT '1',
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `barcode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `currency_id` bigint UNSIGNED DEFAULT NULL,
  `tax_category_id` bigint UNSIGNED DEFAULT NULL,
  `inventory_policy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fulfillment_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory_management` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 for avtive, 0 for inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `sku`, `product_id`, `title`, `quantity`, `price`, `position`, `compare_at_price`, `barcode`, `cost_price`, `currency_id`, `tax_category_id`, `inventory_policy`, `fulfillment_service`, `inventory_management`, `created_at`, `updated_at`, `status`) VALUES
(1, 'sku-id', 1, NULL, 100, '500.00', 1, '500.00', '7543ebf012007e', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 'sku-id-1*5', 1, 'sku-id-Black-Black', 100, '500.00', 1, '500.00', '1500cdf2d597df', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, 'sku-id-1*6', 1, 'sku-id-Black-Grey', 100, '500.00', 1, '500.00', '2ea56327679387', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(4, 'sku-id-7*5', 1, 'sku-id-Medium-Black', 100, '500.00', 1, '500.00', '8f47f11a19433f', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(5, 'sku-id-7*6', 1, 'sku-id-Medium-Grey', 100, '500.00', 1, '500.00', '8f7318b112bbe9', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(6, '234', 2, NULL, 1000, '12.00', 1, '30.00', '49c8972d650f0b', '10.00', NULL, NULL, NULL, NULL, NULL, '2021-06-09 15:14:31', '2021-06-09 15:22:07', 1),
(7, 'cg301', 3, NULL, 333, '15.00', 1, '17.00', 'ee9042f858e15d', '15.00', NULL, NULL, NULL, NULL, NULL, '2021-07-19 09:01:22', '2021-07-19 09:03:16', 1),
(8, 'cg302', 4, NULL, 222, '12.00', 1, '15.00', '6ab26108c4b55f', '12.00', NULL, NULL, NULL, NULL, NULL, '2021-07-19 09:07:35', '2021-07-19 09:08:27', 1),
(9, 'cg303', 5, NULL, 222, '10.00', 1, '15.00', '5102412eecc886', '10.00', NULL, NULL, NULL, NULL, NULL, '2021-07-19 09:09:46', '2021-07-19 09:10:24', 1),
(10, 'cg304', 6, NULL, 333, '16.00', 1, '20.00', '3cd158daf9bfd2', '16.00', NULL, NULL, NULL, NULL, NULL, '2021-07-19 09:12:59', '2021-07-19 09:13:41', 1),
(11, 'SA123', 7, NULL, 22, '12.00', 1, '13.00', 'afeae97e7dd481', '12.00', NULL, NULL, NULL, NULL, NULL, '2021-07-19 12:11:20', '2021-07-19 12:35:02', 1),
(12, 'SA124', 8, NULL, 0, NULL, 1, NULL, '1fdfa24f0ea59c', NULL, NULL, NULL, NULL, NULL, NULL, '2021-07-19 12:11:20', '2021-07-19 12:11:20', 1),
(13, 'SA125', 9, NULL, 0, NULL, 1, NULL, '3b4a8ea12310e8', NULL, NULL, NULL, NULL, NULL, NULL, '2021-07-19 12:11:20', '2021-07-19 12:11:20', 1),
(14, 'SA126', 10, NULL, 0, NULL, 1, NULL, '218e2d7510821d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-07-19 12:11:20', '2021-07-19 12:11:20', 1),
(15, 'SA127', 11, NULL, 0, NULL, 1, NULL, 'b4f1effe09a92e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-07-19 12:11:20', '2021-07-19 12:11:20', 1),
(16, 'SA128', 12, NULL, 0, NULL, 1, NULL, '7ae842cb4ca98e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-07-19 12:11:20', '2021-07-19 12:11:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_images`
--

CREATE TABLE `product_variant_images` (
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `product_image_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_sets`
--

CREATE TABLE `product_variant_sets` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `variant_type_id` bigint UNSIGNED DEFAULT NULL,
  `variant_option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `product_variant_sets`
--

INSERT INTO `product_variant_sets` (`id`, `product_id`, `product_variant_id`, `variant_type_id`, `variant_option_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 1, NULL, NULL),
(2, 1, 2, 2, 5, NULL, NULL),
(3, 1, 3, 1, 1, NULL, NULL),
(4, 1, 3, 2, 6, NULL, NULL),
(5, 1, 3, 1, 1, NULL, NULL),
(6, 1, 3, 2, 6, NULL, NULL),
(7, 1, 4, 1, 7, NULL, NULL),
(8, 1, 4, 2, 5, NULL, NULL),
(9, 1, 5, 1, 7, NULL, NULL),
(10, 1, 5, 2, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci,
  `short_desc` mediumtext COLLATE utf8mb4_unicode_ci,
  `amount` decimal(12,2) UNSIGNED DEFAULT NULL,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `promo_type_id` bigint UNSIGNED DEFAULT NULL,
  `allow_free_delivery` tinyint DEFAULT '0' COMMENT '0- No, 1- yes',
  `minimum_spend` int UNSIGNED DEFAULT NULL,
  `maximum_spend` int UNSIGNED DEFAULT NULL,
  `first_order_only` tinyint DEFAULT '0' COMMENT '0- No, 1- yes',
  `limit_per_user` int DEFAULT NULL,
  `limit_total` int DEFAULT NULL,
  `paid_by_vendor_admin` tinyint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT '0' COMMENT '0- No, 1- yes',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `restriction_on` tinyint DEFAULT '0' COMMENT '0- product, 1-vendor',
  `restriction_type` tinyint DEFAULT '0' COMMENT '0- Include, 1-Exclude'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `promocodes`
--

INSERT INTO `promocodes` (`id`, `name`, `title`, `short_desc`, `amount`, `expiry_date`, `promo_type_id`, `allow_free_delivery`, `minimum_spend`, `maximum_spend`, `first_order_only`, `limit_per_user`, `limit_total`, `paid_by_vendor_admin`, `is_deleted`, `created_by`, `image`, `created_at`, `updated_at`, `restriction_on`, `restriction_type`) VALUES
(2, 'Welcome20', 'Welcome50', 'This will allow the user to shop for first time and give them the discount for 50 USD', '50.00', '2021-06-30 12:00:00', 2, 0, 200, 500, 1, 1, 100, 1, 0, NULL, 'promocode/PGJNuOS78sgWMu8GMnSiDWVAdaz0UoaAz7kEDloB.jpg', '2021-06-09 15:55:42', '2021-06-09 15:55:42', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `promocode_details`
--

CREATE TABLE `promocode_details` (
  `id` bigint UNSIGNED NOT NULL,
  `promocode_id` bigint UNSIGNED DEFAULT NULL,
  `refrence_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promocode_details`
--

INSERT INTO `promocode_details` (`id`, `promocode_id`, `refrence_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '2021-06-09 15:55:42', '2021-06-09 15:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `promocode_restrictions`
--

CREATE TABLE `promocode_restrictions` (
  `promocode_id` bigint UNSIGNED DEFAULT NULL,
  `restriction_type` tinyint DEFAULT '0' COMMENT '0- product, 1-vendor, 2-category',
  `data_id` bigint UNSIGNED DEFAULT NULL,
  `is_included` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no',
  `is_excluded` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `promo_types`
--

CREATE TABLE `promo_types` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - Block, 3 - delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promo_types`
--

INSERT INTO `promo_types` (`id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Percentage Discount', 1, NULL, NULL),
(2, 'Fixed Amount', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promo_usages`
--

CREATE TABLE `promo_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `promocode_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `usage_count` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `refer_and_earns`
--

CREATE TABLE `refer_and_earns` (
  `id` bigint UNSIGNED NOT NULL,
  `reffered_by_amount` decimal(8,2) DEFAULT NULL,
  `reffered_to_amount` decimal(8,2) DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_reasons`
--

CREATE TABLE `return_reasons` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Block') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `order` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `return_reasons`
--

INSERT INTO `return_reasons` (`id`, `title`, `status`, `order`, `created_at`, `updated_at`) VALUES
(1, 'The merchant shipped the wrong item', 'Active', 1, NULL, NULL),
(2, 'Purchase arrived too late', 'Active', 2, NULL, NULL),
(3, 'Customer doesn\'t need it anymore', 'Active', 3, NULL, NULL),
(4, 'The product was damaged or defective', 'Active', 4, NULL, NULL),
(5, 'Other', 'Active', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL COMMENT '0 - pending, 1 - active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Buyer', 1, NULL, NULL),
(2, 'Seller', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_areas`
--

CREATE TABLE `service_areas` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `geo_array` text COLLATE utf8mb4_unicode_ci,
  `zoom_level` smallint NOT NULL DEFAULT '13',
  `polygon` geometry DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `service_areas`
--

INSERT INTO `service_areas` (`id`, `name`, `description`, `geo_array`, `zoom_level`, `polygon`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'Local Area', 'test', '(43.585024398349645, -71.5460141477427),(43.61784207276906, -71.4364941892466),(43.55990181656177, -71.3980420408091),(43.55442818105086, -71.51717503641457)', 12, 0x00000000010300000001000000050000000c235914e2ca454053ed52e5f1e251c06751f47215cf454053ed5285efdb51c0498ddbdcaac7454053ed528579d951c00fccac80f7c6454053ed526519e151c00c235914e2ca454053ed52e5f1e251c0, 4, '2021-06-09 15:46:31', '2021-06-09 15:46:31'),
(2, 'Mexico', 'Mexico', '(37.096931172679476, -125.08534431419803),(37.65567573200916, -76.65760993919803),(17.31669666855265, -69.18690681419803),(-0.0795017605180594, -74.63612556419803),(1.76592168502014, -121.48182868919803)', 4, 0x0000000001030000000100000006000000864f9c3d688c42405398ff4776455fc04ee1b02eedd342405398ff47162a53c0902f6a08135131405398ff47f64b51c0ec4466353a5ab4bf5398ff47b6a852c0e556c7183741fc3f5398ff47d65e5ec0864f9c3d688c42405398ff4776455fc0, 9, '2021-07-16 12:51:02', '2021-07-16 12:51:02'),
(3, 'Mexico', 'Mexico', '(36.4209605691494, -127.10682868919803),(39.67306250822727, -77.36073493919803),(15.74957758482876, -71.20839118919803),(3.820008966395011, -79.47010993919803),(9.405315413498302, -120.69081306419803)', 4, 0x00000000010300000001000000060000007cb33209e23542405398ff47d6c65fc0fb7f8ae926d643405398ff47165753c0f24e19a2c87f2f405398ff4756cd51c027bf68dc608f0e405398ff4716de53c071147b8085cf22405398ff47362c5ec07cb33209e23542405398ff47d6c65fc0, 8, '2021-07-16 12:52:23', '2021-07-16 12:52:23'),
(4, 'Mexico', 'Mexico', '(37.40475600072195, -128.513078689198),(42.130524651004876, -77.53651618919803),(18.77073633196711, -72.61464118919803),(3.732309164210555, -75.51503181419803),(4.433645196545228, -111.72596931419803),(18.35414587289951, -131.852922439198),(37.89187987504773, -128.688859939198)', 4, 0x0000000001030000000100000008000000f3fa6c0bcfb3424029ccff236b1060c0a2b12108b51045405398ff47566253c043a3ebf94ec532405398ff47562752c06fc336e8c4db0d405398ff47f6e052c0c0ea847c0dbc11405398ff4776ee5bc0e51dce4da95a324029ccff234b7b60c031a5a71e29f2424029ccff230b1660c0f3fa6c0bcfb3424029ccff236b1060c0, 7, '2021-07-16 12:54:08', '2021-07-16 12:54:08'),
(5, 'Mexico', 'Mexico', '(34.92164305795016, -123.85487556419803),(42.00002788704903, -74.46034431419803),(17.85290937049728, -69.89003181419803),(6.271220452520281, -75.86659431419803),(4.696480364436861, -112.51698493919803),(35.42454196356469, -124.20643806419803)', 4, 0x00000000010300000001000000070000009e3d5466f87541405398ff47b6f65ec056fbeee9000045405398ff47769d52c0debcbc4458da31405398ff47f67851c0df5276d0ba1519405398ff4776f752c0fb3f0e2632c912405398ff4716215cc01ea51c6457b641405398ff47360d5fc09e3d5466f87541405398ff47b6f65ec0, 6, '2021-07-16 12:55:24', '2021-07-16 12:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `slot_days`
--

CREATE TABLE `slot_days` (
  `id` bigint UNSIGNED NOT NULL,
  `slot_id` bigint UNSIGNED DEFAULT NULL,
  `day` tinyint NOT NULL DEFAULT '0' COMMENT '1 sunday, 2 monday, 3 tuesday, 4 wednesday, 5 thursday, 6 friday, 7 saturday',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `sms_providers`
--

CREATE TABLE `sms_providers` (
  `id` bigint UNSIGNED NOT NULL,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT ' 0 for no, 1 for yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_providers`
--

INSERT INTO `sms_providers` (`id`, `provider`, `keyword`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Twilio Service', 'twilio', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `social_credentials`
--

CREATE TABLE `social_credentials` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `access_token` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refresh_token` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_features_list_user`
--

CREATE TABLE `subscription_features_list_user` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_features_list_vendor`
--

CREATE TABLE `subscription_features_list_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_invoices_user`
--

CREATE TABLE `subscription_invoices_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `payment_option_id` tinyint UNSIGNED DEFAULT NULL,
  `status_id` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime DEFAULT NULL,
  `next_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `subscription_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(12,2) DEFAULT NULL,
  `paid_amount` decimal(12,2) DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_invoice_features_user`
--

CREATE TABLE `subscription_invoice_features_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_invoice_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `feature_id` bigint UNSIGNED DEFAULT NULL,
  `feature_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_log_user`
--

CREATE TABLE `subscription_log_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_invoice_id` bigint UNSIGNED DEFAULT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `payment_option_id` tinyint UNSIGNED DEFAULT NULL,
  `status_id` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_reference` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime DEFAULT NULL,
  `next_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `subscription_amount` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(12,2) DEFAULT NULL,
  `paid_amount` decimal(12,2) DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans_user`
--

CREATE TABLE `subscription_plans_user` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `period` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'plan validity in days',
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint NOT NULL DEFAULT '1' COMMENT 'for same position, display asc order',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans_vendor`
--

CREATE TABLE `subscription_plans_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `period` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'plan validity in days',
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint NOT NULL DEFAULT '1' COMMENT 'for same position, display asc order',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `on_request` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_features_user`
--

CREATE TABLE `subscription_plan_features_user` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_plan_id` bigint UNSIGNED NOT NULL,
  `feature_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan_features_vendor`
--

CREATE TABLE `subscription_plan_features_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_plan_id` bigint UNSIGNED NOT NULL,
  `feature_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_status_options`
--

CREATE TABLE `subscription_status_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0-Inactive, 1-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_categories`
--

CREATE TABLE `tax_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_core` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `tax_categories`
--

INSERT INTO `tax_categories` (`id`, `title`, `code`, `description`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'VAT', 'vat', NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` bigint UNSIGNED NOT NULL,
  `identifier` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_zip` tinyint NOT NULL DEFAULT '1' COMMENT '0 - no, 1 - yes',
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_from` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_to` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `identifier`, `is_zip`, `zip_code`, `zip_from`, `zip_to`, `state`, `country`, `tax_rate`, `tax_amount`, `created_at`, `updated_at`) VALUES
(1, 'VAT', 0, '', '', '', 'Dubai', 'United Arab Emirates', '5.00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tax_rate_categories`
--

CREATE TABLE `tax_rate_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `tax_cate_id` bigint UNSIGNED NOT NULL,
  `tax_rate_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `tax_rate_categories`
--

INSERT INTO `tax_rate_categories` (`id`, `tax_cate_id`, `tax_rate_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `for` tinyint NOT NULL DEFAULT '0' COMMENT '1 for web, 2 for app',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `name`, `image`, `for`, `created_at`, `updated_at`) VALUES
(1, 'Default', 'default/templete.jpg', 1, NULL, NULL),
(2, 'Default', 'default/templete.jpg', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `terminologies`
--

CREATE TABLE `terminologies` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint UNSIGNED NOT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `offset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diff_from_gtm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `timezone`, `offset`, `diff_from_gtm`, `created_at`, `updated_at`) VALUES
(1, 'Africa/Abidjan', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(2, 'Africa/Accra', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(3, 'Africa/Addis_Ababa', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:00', '2021-06-07 07:54:00'),
(4, 'Africa/Algiers', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(5, 'Africa/Asmara', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:00', '2021-06-07 07:54:00'),
(6, 'Africa/Bamako', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(7, 'Africa/Bangui', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(8, 'Africa/Banjul', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(9, 'Africa/Bissau', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(10, 'Africa/Blantyre', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(11, 'Africa/Brazzaville', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(12, 'Africa/Bujumbura', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(13, 'Africa/Cairo', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(14, 'Africa/Casablanca', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(15, 'Africa/Ceuta', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(16, 'Africa/Conakry', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(17, 'Africa/Dakar', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(18, 'Africa/Dar_es_Salaam', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:00', '2021-06-07 07:54:00'),
(19, 'Africa/Djibouti', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:00', '2021-06-07 07:54:00'),
(20, 'Africa/Douala', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(21, 'Africa/El_Aaiun', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(22, 'Africa/Freetown', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(23, 'Africa/Gaborone', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(24, 'Africa/Harare', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(25, 'Africa/Johannesburg', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(26, 'Africa/Juba', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(27, 'Africa/Kampala', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:00', '2021-06-07 07:54:00'),
(28, 'Africa/Khartoum', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(29, 'Africa/Kigali', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(30, 'Africa/Kinshasa', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(31, 'Africa/Lagos', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(32, 'Africa/Libreville', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(33, 'Africa/Lome', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(34, 'Africa/Luanda', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(35, 'Africa/Lubumbashi', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(36, 'Africa/Lusaka', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(37, 'Africa/Malabo', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(38, 'Africa/Maputo', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(39, 'Africa/Maseru', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(40, 'Africa/Mbabane', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(41, 'Africa/Mogadishu', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:00', '2021-06-07 07:54:00'),
(42, 'Africa/Monrovia', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(43, 'Africa/Nairobi', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:00', '2021-06-07 07:54:00'),
(44, 'Africa/Ndjamena', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(45, 'Africa/Niamey', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(46, 'Africa/Nouakchott', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(47, 'Africa/Ouagadougou', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(48, 'Africa/Porto-Novo', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(49, 'Africa/Sao_Tome', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(50, 'Africa/Tripoli', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(51, 'Africa/Tunis', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:00', '2021-06-07 05:54:00'),
(52, 'Africa/Windhoek', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:00', '2021-06-07 06:54:00'),
(53, 'America/Adak', '-09:00', 'UTC/GMT -09:00', '2021-06-06 19:54:00', '2021-06-06 19:54:00'),
(54, 'America/Anchorage', '-08:00', 'UTC/GMT -08:00', '2021-06-06 20:54:00', '2021-06-06 20:54:00'),
(55, 'America/Anguilla', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(56, 'America/Antigua', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(57, 'America/Araguaina', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(58, 'America/Argentina/Buenos_Aires', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(59, 'America/Argentina/Catamarca', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(60, 'America/Argentina/Cordoba', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(61, 'America/Argentina/Jujuy', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(62, 'America/Argentina/La_Rioja', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(63, 'America/Argentina/Mendoza', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(64, 'America/Argentina/Rio_Gallegos', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(65, 'America/Argentina/Salta', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(66, 'America/Argentina/San_Juan', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(67, 'America/Argentina/San_Luis', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(68, 'America/Argentina/Tucuman', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(69, 'America/Argentina/Ushuaia', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(70, 'America/Aruba', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(71, 'America/Asuncion', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(72, 'America/Atikokan', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:00', '2021-06-06 23:54:00'),
(73, 'America/Bahia', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(74, 'America/Bahia_Banderas', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:00', '2021-06-06 23:54:00'),
(75, 'America/Barbados', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(76, 'America/Belem', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(77, 'America/Belize', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(78, 'America/Blanc-Sablon', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(79, 'America/Boa_Vista', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(80, 'America/Bogota', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:00', '2021-06-06 23:54:00'),
(81, 'America/Boise', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(82, 'America/Cambridge_Bay', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(83, 'America/Campo_Grande', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(84, 'America/Cancun', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:00', '2021-06-06 23:54:00'),
(85, 'America/Caracas', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(86, 'America/Cayenne', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:00', '2021-06-07 01:54:00'),
(87, 'America/Cayman', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:00', '2021-06-06 23:54:00'),
(88, 'America/Chicago', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:00', '2021-06-06 23:54:00'),
(89, 'America/Chihuahua', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(90, 'America/Costa_Rica', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(91, 'America/Creston', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:00', '2021-06-06 21:54:00'),
(92, 'America/Cuiaba', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(93, 'America/Curacao', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(94, 'America/Danmarkshavn', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:00', '2021-06-07 04:54:00'),
(95, 'America/Dawson', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:00', '2021-06-06 21:54:00'),
(96, 'America/Dawson_Creek', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:00', '2021-06-06 21:54:00'),
(97, 'America/Denver', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(98, 'America/Detroit', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(99, 'America/Dominica', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:00', '2021-06-07 00:54:00'),
(100, 'America/Edmonton', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(101, 'America/Eirunepe', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:00', '2021-06-06 23:54:00'),
(102, 'America/El_Salvador', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:00', '2021-06-06 22:54:00'),
(103, 'America/Fort_Nelson', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:01', '2021-06-06 21:54:01'),
(104, 'America/Fortaleza', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(105, 'America/Glace_Bay', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(106, 'America/Goose_Bay', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(107, 'America/Grand_Turk', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(108, 'America/Grenada', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(109, 'America/Guadeloupe', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(110, 'America/Guatemala', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(111, 'America/Guayaquil', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(112, 'America/Guyana', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(113, 'America/Halifax', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(114, 'America/Havana', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(115, 'America/Hermosillo', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:01', '2021-06-06 21:54:01'),
(116, 'America/Indiana/Indianapolis', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(117, 'America/Indiana/Knox', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(118, 'America/Indiana/Marengo', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(119, 'America/Indiana/Petersburg', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(120, 'America/Indiana/Tell_City', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(121, 'America/Indiana/Vevay', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(122, 'America/Indiana/Vincennes', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(123, 'America/Indiana/Winamac', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(124, 'America/Inuvik', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(125, 'America/Iqaluit', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(126, 'America/Jamaica', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(127, 'America/Juneau', '-08:00', 'UTC/GMT -08:00', '2021-06-06 20:54:01', '2021-06-06 20:54:01'),
(128, 'America/Kentucky/Louisville', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(129, 'America/Kentucky/Monticello', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(130, 'America/Kralendijk', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(131, 'America/La_Paz', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(132, 'America/Lima', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(133, 'America/Los_Angeles', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:01', '2021-06-06 21:54:01'),
(134, 'America/Lower_Princes', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(135, 'America/Maceio', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(136, 'America/Managua', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(137, 'America/Manaus', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(138, 'America/Marigot', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(139, 'America/Martinique', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(140, 'America/Matamoros', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(141, 'America/Mazatlan', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(142, 'America/Menominee', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(143, 'America/Merida', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(144, 'America/Metlakatla', '-08:00', 'UTC/GMT -08:00', '2021-06-06 20:54:01', '2021-06-06 20:54:01'),
(145, 'America/Mexico_City', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(146, 'America/Miquelon', '-02:00', 'UTC/GMT -02:00', '2021-06-07 02:54:01', '2021-06-07 02:54:01'),
(147, 'America/Moncton', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(148, 'America/Monterrey', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(149, 'America/Montevideo', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(150, 'America/Montserrat', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(151, 'America/Nassau', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(152, 'America/New_York', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(153, 'America/Nipigon', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(154, 'America/Nome', '-08:00', 'UTC/GMT -08:00', '2021-06-06 20:54:01', '2021-06-06 20:54:01'),
(155, 'America/Noronha', '-02:00', 'UTC/GMT -02:00', '2021-06-07 02:54:01', '2021-06-07 02:54:01'),
(156, 'America/North_Dakota/Beulah', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(157, 'America/North_Dakota/Center', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(158, 'America/North_Dakota/New_Salem', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(159, 'America/Nuuk', '-02:00', 'UTC/GMT -02:00', '2021-06-07 02:54:01', '2021-06-07 02:54:01'),
(160, 'America/Ojinaga', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(161, 'America/Panama', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(162, 'America/Pangnirtung', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(163, 'America/Paramaribo', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(164, 'America/Phoenix', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:01', '2021-06-06 21:54:01'),
(165, 'America/Port-au-Prince', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(166, 'America/Port_of_Spain', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(167, 'America/Porto_Velho', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(168, 'America/Puerto_Rico', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(169, 'America/Punta_Arenas', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(170, 'America/Rainy_River', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(171, 'America/Rankin_Inlet', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(172, 'America/Recife', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(173, 'America/Regina', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(174, 'America/Resolute', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(175, 'America/Rio_Branco', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(176, 'America/Santarem', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(177, 'America/Santiago', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(178, 'America/Santo_Domingo', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(179, 'America/Sao_Paulo', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(180, 'America/Scoresbysund', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:01', '2021-06-07 04:54:01'),
(181, 'America/Sitka', '-08:00', 'UTC/GMT -08:00', '2021-06-06 20:54:01', '2021-06-06 20:54:01'),
(182, 'America/St_Barthelemy', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(183, 'America/St_Johns', '-02:30', 'UTC/GMT -02:30', '2021-06-07 02:24:01', '2021-06-07 02:24:01'),
(184, 'America/St_Kitts', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(185, 'America/St_Lucia', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(186, 'America/St_Thomas', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(187, 'America/St_Vincent', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(188, 'America/Swift_Current', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(189, 'America/Tegucigalpa', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(190, 'America/Thule', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(191, 'America/Thunder_Bay', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(192, 'America/Tijuana', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:01', '2021-06-06 21:54:01'),
(193, 'America/Toronto', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(194, 'America/Tortola', '-04:00', 'UTC/GMT -04:00', '2021-06-07 00:54:01', '2021-06-07 00:54:01'),
(195, 'America/Vancouver', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:01', '2021-06-06 21:54:01'),
(196, 'America/Whitehorse', '-07:00', 'UTC/GMT -07:00', '2021-06-06 21:54:01', '2021-06-06 21:54:01'),
(197, 'America/Winnipeg', '-05:00', 'UTC/GMT -05:00', '2021-06-06 23:54:01', '2021-06-06 23:54:01'),
(198, 'America/Yakutat', '-08:00', 'UTC/GMT -08:00', '2021-06-06 20:54:01', '2021-06-06 20:54:01'),
(199, 'America/Yellowknife', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:01', '2021-06-06 22:54:01'),
(200, 'Antarctica/Casey', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:01', '2021-06-07 15:54:01'),
(201, 'Antarctica/Davis', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:01', '2021-06-07 11:54:01'),
(202, 'Antarctica/DumontDUrville', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:01', '2021-06-07 14:54:01'),
(203, 'Antarctica/Macquarie', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:01', '2021-06-07 14:54:01'),
(204, 'Antarctica/Mawson', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:01', '2021-06-07 09:54:01'),
(205, 'Antarctica/McMurdo', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:01', '2021-06-07 16:54:01'),
(206, 'Antarctica/Palmer', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(207, 'Antarctica/Rothera', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:01', '2021-06-07 01:54:01'),
(208, 'Antarctica/Syowa', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(209, 'Antarctica/Troll', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:01', '2021-06-07 06:54:01'),
(210, 'Antarctica/Vostok', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:01', '2021-06-07 10:54:01'),
(211, 'Arctic/Longyearbyen', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:01', '2021-06-07 06:54:01'),
(212, 'Asia/Aden', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(213, 'Asia/Almaty', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:01', '2021-06-07 10:54:01'),
(214, 'Asia/Amman', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(215, 'Asia/Anadyr', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:01', '2021-06-07 16:54:01'),
(216, 'Asia/Aqtau', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:01', '2021-06-07 09:54:01'),
(217, 'Asia/Aqtobe', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:01', '2021-06-07 09:54:01'),
(218, 'Asia/Ashgabat', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:01', '2021-06-07 09:54:01'),
(219, 'Asia/Atyrau', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:01', '2021-06-07 09:54:01'),
(220, 'Asia/Baghdad', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(221, 'Asia/Bahrain', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(222, 'Asia/Baku', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:01', '2021-06-07 08:54:01'),
(223, 'Asia/Bangkok', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:01', '2021-06-07 11:54:01'),
(224, 'Asia/Barnaul', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:01', '2021-06-07 11:54:01'),
(225, 'Asia/Beirut', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(226, 'Asia/Bishkek', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:01', '2021-06-07 10:54:01'),
(227, 'Asia/Brunei', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(228, 'Asia/Chita', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:01', '2021-06-07 13:54:01'),
(229, 'Asia/Choibalsan', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(230, 'Asia/Colombo', '+05:30', 'UTC/GMT +05:30', '2021-06-07 10:24:01', '2021-06-07 10:24:01'),
(231, 'Asia/Damascus', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(232, 'Asia/Dhaka', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:01', '2021-06-07 10:54:01'),
(233, 'Asia/Dili', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:01', '2021-06-07 13:54:01'),
(234, 'Asia/Dubai', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:01', '2021-06-07 08:54:01'),
(235, 'Asia/Dushanbe', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:01', '2021-06-07 09:54:01'),
(236, 'Asia/Famagusta', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(237, 'Asia/Gaza', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(238, 'Asia/Hebron', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(239, 'Asia/Ho_Chi_Minh', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:01', '2021-06-07 11:54:01'),
(240, 'Asia/Hong_Kong', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(241, 'Asia/Hovd', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:01', '2021-06-07 11:54:01'),
(242, 'Asia/Irkutsk', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(243, 'Asia/Jakarta', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:01', '2021-06-07 11:54:01'),
(244, 'Asia/Jayapura', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:01', '2021-06-07 13:54:01'),
(245, 'Asia/Jerusalem', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(246, 'Asia/Kabul', '+04:30', 'UTC/GMT +04:30', '2021-06-07 09:24:01', '2021-06-07 09:24:01'),
(247, 'Asia/Kamchatka', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:01', '2021-06-07 16:54:01'),
(248, 'Asia/Karachi', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:01', '2021-06-07 09:54:01'),
(249, 'Asia/Kathmandu', '+05:45', 'UTC/GMT +05:45', '2021-06-07 10:39:01', '2021-06-07 10:39:01'),
(250, 'Asia/Khandyga', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:01', '2021-06-07 13:54:01'),
(251, 'Asia/Kolkata', '+05:30', 'UTC/GMT +05:30', '2021-06-07 10:24:01', '2021-06-07 10:24:01'),
(252, 'Asia/Krasnoyarsk', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:01', '2021-06-07 11:54:01'),
(253, 'Asia/Kuala_Lumpur', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(254, 'Asia/Kuching', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(255, 'Asia/Kuwait', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:01', '2021-06-07 07:54:01'),
(256, 'Asia/Macau', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(257, 'Asia/Magadan', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:01', '2021-06-07 15:54:01'),
(258, 'Asia/Makassar', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(259, 'Asia/Manila', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:01', '2021-06-07 12:54:01'),
(260, 'Asia/Muscat', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:01', '2021-06-07 08:54:01'),
(261, 'Asia/Nicosia', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(262, 'Asia/Novokuznetsk', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:02', '2021-06-07 11:54:02'),
(263, 'Asia/Novosibirsk', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:02', '2021-06-07 11:54:02'),
(264, 'Asia/Omsk', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:02', '2021-06-07 10:54:02'),
(265, 'Asia/Oral', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:02', '2021-06-07 09:54:02'),
(266, 'Asia/Phnom_Penh', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:02', '2021-06-07 11:54:02'),
(267, 'Asia/Pontianak', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:02', '2021-06-07 11:54:02'),
(268, 'Asia/Pyongyang', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:02', '2021-06-07 13:54:02'),
(269, 'Asia/Qatar', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(270, 'Asia/Qostanay', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:02', '2021-06-07 10:54:02'),
(271, 'Asia/Qyzylorda', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:02', '2021-06-07 09:54:02'),
(272, 'Asia/Riyadh', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(273, 'Asia/Sakhalin', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(274, 'Asia/Samarkand', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:02', '2021-06-07 09:54:02'),
(275, 'Asia/Seoul', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:02', '2021-06-07 13:54:02'),
(276, 'Asia/Shanghai', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:02', '2021-06-07 12:54:02'),
(277, 'Asia/Singapore', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:02', '2021-06-07 12:54:02'),
(278, 'Asia/Srednekolymsk', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(279, 'Asia/Taipei', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:02', '2021-06-07 12:54:02'),
(280, 'Asia/Tashkent', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:02', '2021-06-07 09:54:02'),
(281, 'Asia/Tbilisi', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(282, 'Asia/Tehran', '+04:30', 'UTC/GMT +04:30', '2021-06-07 09:24:02', '2021-06-07 09:24:02'),
(283, 'Asia/Thimphu', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:02', '2021-06-07 10:54:02'),
(284, 'Asia/Tokyo', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:02', '2021-06-07 13:54:02'),
(285, 'Asia/Tomsk', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:02', '2021-06-07 11:54:02'),
(286, 'Asia/Ulaanbaatar', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:02', '2021-06-07 12:54:02'),
(287, 'Asia/Urumqi', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:02', '2021-06-07 10:54:02'),
(288, 'Asia/Ust-Nera', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(289, 'Asia/Vientiane', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:02', '2021-06-07 11:54:02'),
(290, 'Asia/Vladivostok', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(291, 'Asia/Yakutsk', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:02', '2021-06-07 13:54:02'),
(292, 'Asia/Yangon', '+06:30', 'UTC/GMT +06:30', '2021-06-07 11:24:02', '2021-06-07 11:24:02'),
(293, 'Asia/Yekaterinburg', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:02', '2021-06-07 09:54:02'),
(294, 'Asia/Yerevan', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(295, 'Atlantic/Azores', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:02', '2021-06-07 04:54:02'),
(296, 'Atlantic/Bermuda', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:02', '2021-06-07 01:54:02'),
(297, 'Atlantic/Canary', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(298, 'Atlantic/Cape_Verde', '-01:00', 'UTC/GMT -01:00', '2021-06-07 03:54:02', '2021-06-07 03:54:02'),
(299, 'Atlantic/Faroe', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(300, 'Atlantic/Madeira', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(301, 'Atlantic/Reykjavik', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:02', '2021-06-07 04:54:02'),
(302, 'Atlantic/South_Georgia', '-02:00', 'UTC/GMT -02:00', '2021-06-07 02:54:02', '2021-06-07 02:54:02'),
(303, 'Atlantic/St_Helena', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:02', '2021-06-07 04:54:02'),
(304, 'Atlantic/Stanley', '-03:00', 'UTC/GMT -03:00', '2021-06-07 01:54:02', '2021-06-07 01:54:02'),
(305, 'Australia/Adelaide', '+09:30', 'UTC/GMT +09:30', '2021-06-07 14:24:02', '2021-06-07 14:24:02'),
(306, 'Australia/Brisbane', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(307, 'Australia/Broken_Hill', '+09:30', 'UTC/GMT +09:30', '2021-06-07 14:24:02', '2021-06-07 14:24:02'),
(308, 'Australia/Darwin', '+09:30', 'UTC/GMT +09:30', '2021-06-07 14:24:02', '2021-06-07 14:24:02'),
(309, 'Australia/Eucla', '+08:45', 'UTC/GMT +08:45', '2021-06-07 13:39:02', '2021-06-07 13:39:02'),
(310, 'Australia/Hobart', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(311, 'Australia/Lindeman', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(312, 'Australia/Lord_Howe', '+10:30', 'UTC/GMT +10:30', '2021-06-07 15:24:02', '2021-06-07 15:24:02'),
(313, 'Australia/Melbourne', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(314, 'Australia/Perth', '+08:00', 'UTC/GMT +08:00', '2021-06-07 12:54:02', '2021-06-07 12:54:02'),
(315, 'Australia/Sydney', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(316, 'Europe/Amsterdam', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(317, 'Europe/Andorra', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(318, 'Europe/Astrakhan', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(319, 'Europe/Athens', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(320, 'Europe/Belgrade', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(321, 'Europe/Berlin', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(322, 'Europe/Bratislava', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(323, 'Europe/Brussels', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(324, 'Europe/Bucharest', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(325, 'Europe/Budapest', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(326, 'Europe/Busingen', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(327, 'Europe/Chisinau', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(328, 'Europe/Copenhagen', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(329, 'Europe/Dublin', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(330, 'Europe/Gibraltar', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(331, 'Europe/Guernsey', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(332, 'Europe/Helsinki', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(333, 'Europe/Isle_of_Man', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(334, 'Europe/Istanbul', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(335, 'Europe/Jersey', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(336, 'Europe/Kaliningrad', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(337, 'Europe/Kiev', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(338, 'Europe/Kirov', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(339, 'Europe/Lisbon', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(340, 'Europe/Ljubljana', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(341, 'Europe/London', '+01:00', 'UTC/GMT +01:00', '2021-06-07 05:54:02', '2021-06-07 05:54:02'),
(342, 'Europe/Luxembourg', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(343, 'Europe/Madrid', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(344, 'Europe/Malta', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(345, 'Europe/Mariehamn', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(346, 'Europe/Minsk', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(347, 'Europe/Monaco', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(348, 'Europe/Moscow', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(349, 'Europe/Oslo', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(350, 'Europe/Paris', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(351, 'Europe/Podgorica', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(352, 'Europe/Prague', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(353, 'Europe/Riga', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(354, 'Europe/Rome', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(355, 'Europe/Samara', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(356, 'Europe/San_Marino', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(357, 'Europe/Sarajevo', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(358, 'Europe/Saratov', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(359, 'Europe/Simferopol', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(360, 'Europe/Skopje', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(361, 'Europe/Sofia', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(362, 'Europe/Stockholm', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(363, 'Europe/Tallinn', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(364, 'Europe/Tirane', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(365, 'Europe/Ulyanovsk', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(366, 'Europe/Uzhgorod', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(367, 'Europe/Vaduz', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(368, 'Europe/Vatican', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(369, 'Europe/Vienna', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(370, 'Europe/Vilnius', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(371, 'Europe/Volgograd', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(372, 'Europe/Warsaw', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(373, 'Europe/Zagreb', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(374, 'Europe/Zaporozhye', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(375, 'Europe/Zurich', '+02:00', 'UTC/GMT +02:00', '2021-06-07 06:54:02', '2021-06-07 06:54:02'),
(376, 'Indian/Antananarivo', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(377, 'Indian/Chagos', '+06:00', 'UTC/GMT +06:00', '2021-06-07 10:54:02', '2021-06-07 10:54:02'),
(378, 'Indian/Christmas', '+07:00', 'UTC/GMT +07:00', '2021-06-07 11:54:02', '2021-06-07 11:54:02'),
(379, 'Indian/Cocos', '+06:30', 'UTC/GMT +06:30', '2021-06-07 11:24:02', '2021-06-07 11:24:02'),
(380, 'Indian/Comoro', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(381, 'Indian/Kerguelen', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:02', '2021-06-07 09:54:02'),
(382, 'Indian/Mahe', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(383, 'Indian/Maldives', '+05:00', 'UTC/GMT +05:00', '2021-06-07 09:54:02', '2021-06-07 09:54:02'),
(384, 'Indian/Mauritius', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(385, 'Indian/Mayotte', '+03:00', 'UTC/GMT +03:00', '2021-06-07 07:54:02', '2021-06-07 07:54:02'),
(386, 'Indian/Reunion', '+04:00', 'UTC/GMT +04:00', '2021-06-07 08:54:02', '2021-06-07 08:54:02'),
(387, 'Pacific/Apia', '+13:00', 'UTC/GMT +13:00', '2021-06-07 17:54:02', '2021-06-07 17:54:02'),
(388, 'Pacific/Auckland', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(389, 'Pacific/Bougainville', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(390, 'Pacific/Chatham', '+12:45', 'UTC/GMT +12:45', '2021-06-07 17:39:02', '2021-06-07 17:39:02'),
(391, 'Pacific/Chuuk', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(392, 'Pacific/Easter', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:02', '2021-06-06 22:54:02'),
(393, 'Pacific/Efate', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(394, 'Pacific/Enderbury', '+13:00', 'UTC/GMT +13:00', '2021-06-07 17:54:02', '2021-06-07 17:54:02'),
(395, 'Pacific/Fakaofo', '+13:00', 'UTC/GMT +13:00', '2021-06-07 17:54:02', '2021-06-07 17:54:02'),
(396, 'Pacific/Fiji', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(397, 'Pacific/Funafuti', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(398, 'Pacific/Galapagos', '-06:00', 'UTC/GMT -06:00', '2021-06-06 22:54:02', '2021-06-06 22:54:02'),
(399, 'Pacific/Gambier', '-09:00', 'UTC/GMT -09:00', '2021-06-06 19:54:02', '2021-06-06 19:54:02'),
(400, 'Pacific/Guadalcanal', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(401, 'Pacific/Guam', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(402, 'Pacific/Honolulu', '-10:00', 'UTC/GMT -10:00', '2021-06-06 18:54:02', '2021-06-06 18:54:02'),
(403, 'Pacific/Kiritimati', '+14:00', 'UTC/GMT +14:00', '2021-06-07 18:54:02', '2021-06-07 18:54:02'),
(404, 'Pacific/Kosrae', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(405, 'Pacific/Kwajalein', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(406, 'Pacific/Majuro', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(407, 'Pacific/Marquesas', '-09:30', 'UTC/GMT -09:30', '2021-06-06 19:24:02', '2021-06-06 19:24:02'),
(408, 'Pacific/Midway', '-11:00', 'UTC/GMT -11:00', '2021-06-06 17:54:02', '2021-06-06 17:54:02'),
(409, 'Pacific/Nauru', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(410, 'Pacific/Niue', '-11:00', 'UTC/GMT -11:00', '2021-06-06 17:54:02', '2021-06-06 17:54:02'),
(411, 'Pacific/Norfolk', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(412, 'Pacific/Noumea', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(413, 'Pacific/Pago_Pago', '-11:00', 'UTC/GMT -11:00', '2021-06-06 17:54:02', '2021-06-06 17:54:02'),
(414, 'Pacific/Palau', '+09:00', 'UTC/GMT +09:00', '2021-06-07 13:54:02', '2021-06-07 13:54:02'),
(415, 'Pacific/Pitcairn', '-08:00', 'UTC/GMT -08:00', '2021-06-06 20:54:02', '2021-06-06 20:54:02'),
(416, 'Pacific/Pohnpei', '+11:00', 'UTC/GMT +11:00', '2021-06-07 15:54:02', '2021-06-07 15:54:02'),
(417, 'Pacific/Port_Moresby', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(418, 'Pacific/Rarotonga', '-10:00', 'UTC/GMT -10:00', '2021-06-06 18:54:02', '2021-06-06 18:54:02'),
(419, 'Pacific/Saipan', '+10:00', 'UTC/GMT +10:00', '2021-06-07 14:54:02', '2021-06-07 14:54:02'),
(420, 'Pacific/Tahiti', '-10:00', 'UTC/GMT -10:00', '2021-06-06 18:54:02', '2021-06-06 18:54:02'),
(421, 'Pacific/Tarawa', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(422, 'Pacific/Tongatapu', '+13:00', 'UTC/GMT +13:00', '2021-06-07 17:54:02', '2021-06-07 17:54:02'),
(423, 'Pacific/Wake', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(424, 'Pacific/Wallis', '+12:00', 'UTC/GMT +12:00', '2021-06-07 16:54:02', '2021-06-07 16:54:02'),
(425, 'UTC', '+00:00', 'UTC/GMT +00:00', '2021-06-07 04:54:02', '2021-06-07 04:54:02');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `payable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_id` bigint UNSIGNED NOT NULL,
  `wallet_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('deposit','withdraw') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(64,0) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `meta` json DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `from_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint UNSIGNED NOT NULL,
  `to_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_id` bigint UNSIGNED NOT NULL,
  `status` enum('exchange','transfer','paid','refund','gift') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `status_last` enum('exchange','transfer','paid','refund','gift') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_id` bigint UNSIGNED NOT NULL,
  `withdraw_id` bigint UNSIGNED NOT NULL,
  `discount` decimal(64,0) NOT NULL DEFAULT '0',
  `fee` decimal(64,0) NOT NULL DEFAULT '0',
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` mediumtext COLLATE utf8mb4_unicode_ci,
  `sequence` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `title`, `description`, `image`, `sequence`, `created_at`, `updated_at`) VALUES
(1, 'Product', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'product.png', 2, '2021-07-05 12:23:15', '2021-07-05 12:23:15'),
(2, 'Pickup/Parent', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'dispatcher.png', 7, '2021-07-05 12:23:15', '2021-07-05 12:23:15'),
(3, 'Vendor', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'vendor.png', 3, '2021-07-05 12:23:15', '2021-07-05 12:23:15'),
(4, 'Brand', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'brand.png', 4, '2021-07-05 12:23:15', '2021-07-05 12:23:15'),
(5, 'Celebrity', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'celebrity.png', 6, '2021-07-05 12:23:15', '2021-07-05 12:23:15'),
(6, 'Subcategory', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'subcategory.png', 1, '2021-07-05 12:23:15', '2021-07-05 12:23:15'),
(7, 'Pickup/Delivery', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'dispatcher.png', 6, '2021-07-05 12:23:15', '2021-07-05 12:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `phone_number` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dial_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL DEFAULT '0' COMMENT '1 for buyer, 2 for seller',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - pending, 1 - active, 2 - blocked, 3 - inactive',
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `facebook_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_auth_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_token` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_token_valid_till` timestamp NULL DEFAULT NULL,
  `phone_token` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_token_valid_till` timestamp NULL DEFAULT NULL,
  `is_email_verified` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `is_phone_verified` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `timezone_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_superadmin` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `is_admin` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `description`, `phone_number`, `dial_code`, `email_verified_at`, `password`, `type`, `status`, `country_id`, `role_id`, `auth_token`, `system_id`, `remember_token`, `created_at`, `updated_at`, `facebook_auth_id`, `twitter_auth_id`, `google_auth_id`, `apple_auth_id`, `image`, `email_token`, `email_token_valid_till`, `phone_token`, `phone_token_valid_till`, `is_email_verified`, `is_phone_verified`, `timezone_id`, `code`, `is_superadmin`, `is_admin`, `title`, `timezone`) VALUES
(1, 'Hilda Zoe Zamudio', 'admin@spidbi.com', NULL, '+10776741153', NULL, NULL, '$2y$10$AN/JvQkG07HSIMJlTDCOVO3ggGntuaR.BYRU1NBQgBMlEEfh7DK0m', 0, 1, NULL, NULL, NULL, NULL, NULL, '2021-06-07 17:41:12', '2021-06-07 17:41:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 1, 0, NULL, NULL),
(2, 'Silver man', 'silverman@silverman.com', NULL, '+1987654321', NULL, NULL, '$2y$10$zHq6884tMsoMsC4oZcwFzun0v2R1vAq0KeCtipvxVGh.4aGcXwdca', 0, 1, NULL, NULL, NULL, NULL, NULL, '2021-06-09 15:30:46', '2021-07-14 13:25:45', NULL, NULL, NULL, NULL, 'profile/pUnY9KF4xykmrbZpXocKeZfgz4Z09GX98CDnHQxQ.jpg', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, 1, NULL, NULL),
(3, 'Gulshan Gupta', 'gulshanguptaprince@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$kH2Bb/o5uZtBG11e4R66sOWzxAsyFiMzi6hYQ3/v7Xg6kRvEPaqzC', 1, 1, NULL, 1, 'eyJ0eXAiOiJqd3QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjY0MzkwMjksImV4cCI6MTYyOTExNzQyOSwiaXNzIjoicm95b29yZGVycy5jb20ifQ.FqS9YeshhSY4kQma2kid6QSznpAgJjIiRY4KNi8uNc0', NULL, NULL, '2021-07-16 12:36:51', '2021-07-16 12:37:09', NULL, NULL, '112548462778238872040', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, 0, 0, NULL, NULL),
(4, 'Sukhdeep Kaur', 'sukhdeep@code-brew.com', NULL, NULL, NULL, NULL, '$2y$10$ibZ7VBH4V3kCI20zcK7oXuP62/VaWBRarDpY2SWpvndwM/Pmz1fQS', 1, 1, NULL, 1, 'eyJ0eXAiOiJqd3QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjY2Njg5NzgsImV4cCI6MTYyOTM0NzM3OCwiaXNzIjoicm95b29yZGVycy5jb20ifQ.HZYc3VnYIvn9PPkBnymzj5POdtWTQLG7BiHzHg2rNE0', NULL, NULL, '2021-07-19 04:29:38', '2021-07-19 04:29:38', NULL, NULL, '111894060638837017709', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, 0, 0, NULL, NULL),
(5, 'Rahul', 'rahul11@gmail.com', NULL, '4785858585', '91', NULL, '$2y$10$.vdmJq5HnqW4wqPCDA0yEuXJx24B9ZpquJAbeSGs9mc/pO0meiKue', 1, 1, 99, 1, NULL, NULL, NULL, '2021-07-19 10:16:19', '2021-07-19 10:16:19', NULL, NULL, NULL, NULL, NULL, '612594', '2021-07-19 10:26:18', '995670', '2021-07-19 10:26:18', 0, 0, NULL, NULL, 0, 0, NULL, NULL),
(6, 'Rupali', 'Rupali123@gmail.com', NULL, '3636363637', NULL, NULL, '$2y$10$9.t18w0nsnvZJPVDaOpPo.IuKUOd.eZwUfg8cnNpfeukhrKqIpMt.', 1, 1, 99, 1, 'eyJ0eXAiOiJqd3QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjY2OTY0NDAsImV4cCI6MTYyOTM3NDg0MCwiaXNzIjoicm95b29yZGVycy5jb20ifQ.23g2KLi2frEjLR2gyk1dQt4o1a9LR6_6i82ryJpXsq0', NULL, NULL, '2021-07-19 12:02:51', '2021-07-19 12:07:20', NULL, NULL, NULL, NULL, 'profile/68e06d5ce5614307.jpg', '124244', '2021-07-19 12:12:51', '877811', '2021-07-19 12:16:09', 0, 0, NULL, NULL, 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(15,12) DEFAULT NULL,
  `longitude` decimal(16,12) DEFAULT NULL,
  `pincode` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `phonecode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1 - home',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `address`, `street`, `city`, `state`, `latitude`, `longitude`, `pincode`, `is_primary`, `phonecode`, `country_code`, `country`, `type`, `created_at`, `updated_at`) VALUES
(1, 6, '271, Sector 21A, Block A, Sector 21, Chandigarh, 160022, India', 'Chandigarh', 'Chandigarh', 'CH', '30.729412600000', '76.779348400000', '160022', 0, '+91', 'IN', 'India', 1, '2021-07-19 12:03:26', '2021-07-19 12:03:26');

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `device_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `user_devices`
--

INSERT INTO `user_devices` (`id`, `user_id`, `device_type`, `device_token`, `access_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'web', 'web', '', NULL, NULL),
(2, 2, 'web', 'web', '', NULL, NULL),
(3, 3, 'android', 'sadassa', '', '2021-07-16 12:36:51', '2021-07-16 12:37:09'),
(4, 4, 'android', '899635845dcd7827', '', '2021-07-19 04:29:38', '2021-07-19 04:29:38'),
(5, 6, 'android', '9e00ed499f39351a', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_loyalty_points`
--

CREATE TABLE `user_loyalty_points` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `points` int DEFAULT '0',
  `loyalty_card_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `user_loyalty_points`
--

INSERT INTO `user_loyalty_points` (`id`, `user_id`, `points`, `loyalty_card_id`, `assigned_by`, `created_at`, `updated_at`) VALUES
(1, 1, 0, NULL, NULL, NULL, NULL),
(2, 2, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_loyalty_point_histories`
--

CREATE TABLE `user_loyalty_point_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `points` int DEFAULT NULL,
  `earn_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'on_purchase, get_as_gift, add_wallet_money',
  `earn_type_id` bigint UNSIGNED DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `permission_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 2, 3, NULL, NULL),
(4, 2, 4, NULL, NULL),
(5, 2, 5, NULL, NULL),
(6, 2, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_refferals`
--

CREATE TABLE `user_refferals` (
  `id` bigint UNSIGNED NOT NULL,
  `refferal_code` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reffered_by` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `user_refferals`
--

INSERT INTO `user_refferals` (`id`, `refferal_code`, `reffered_by`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'c5dadb24', NULL, 1, '2021-07-12 09:18:32', '2021-07-12 09:18:32'),
(2, '53225311', NULL, 2, '2021-07-12 09:18:32', '2021-07-12 09:18:32'),
(3, 'c91388e8', NULL, 5, '2021-07-19 10:16:19', '2021-07-19 10:16:19'),
(4, 'd8fa15', NULL, 6, '2021-07-19 12:02:51', '2021-07-19 12:02:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_vendors`
--

CREATE TABLE `user_vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `user_vendors`
--

INSERT INTO `user_vendors` (`id`, `user_id`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, NULL, NULL),
(2, 2, 3, NULL, NULL),
(3, 6, 5, NULL, NULL),
(4, 6, 6, NULL, NULL),
(5, 6, 7, NULL, NULL),
(6, 6, 8, NULL, NULL),
(7, 6, 9, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_wishlists`
--

CREATE TABLE `user_wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `added_on` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1 for dropdown, 2 for color',
  `position` smallint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - pending, 1 - active, 2 - blocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `title`, `type`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, 1, NULL, NULL),
(2, 'Color', 2, 2, 1, NULL, NULL),
(3, 'Phones', 1, 3, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variant_categories`
--

CREATE TABLE `variant_categories` (
  `variant_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `variant_categories`
--

INSERT INTO `variant_categories` (`variant_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, NULL),
(2, 11, NULL, NULL),
(3, 12, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variant_options`
--

CREATE TABLE `variant_options` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `hexacode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` smallint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `variant_options`
--

INSERT INTO `variant_options` (`id`, `title`, `variant_id`, `hexacode`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Small', 1, '', 1, NULL, NULL),
(2, 'White', 2, '#ffffff', 1, NULL, NULL),
(3, 'Black', 2, '#000000', 1, NULL, NULL),
(4, 'Grey', 2, '#808080', 1, NULL, NULL),
(5, 'Medium', 1, '', 1, NULL, NULL),
(6, 'Large', 1, '', 1, NULL, NULL),
(7, 'IPhone', 3, '', 1, NULL, NULL),
(8, 'Samsung', 3, '', 1, NULL, NULL),
(9, 'Xiaomi', 3, '', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variant_option_translations`
--

CREATE TABLE `variant_option_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_option_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `variant_option_translations`
--

INSERT INTO `variant_option_translations` (`id`, `title`, `variant_option_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small', 1, 1, NULL, NULL),
(2, 'White', 2, 1, NULL, NULL),
(3, 'Black', 3, 1, NULL, NULL),
(4, 'Grey', 4, 1, NULL, NULL),
(5, 'Medium', 5, 1, NULL, NULL),
(6, 'Large', 6, 1, NULL, NULL),
(7, 'IPhone', 7, 1, NULL, NULL),
(8, 'Samsung', 8, 1, NULL, NULL),
(9, 'Xiaomi', 9, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variant_translations`
--

CREATE TABLE `variant_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `variant_translations`
--

INSERT INTO `variant_translations` (`id`, `title`, `variant_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, NULL, NULL),
(2, 'Color', 2, 1, NULL, NULL),
(3, 'Phones', 3, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(15,12) DEFAULT NULL,
  `longitude` decimal(16,12) DEFAULT NULL,
  `order_min_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_pre_time` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_reject_time` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_percent` smallint DEFAULT '1',
  `commission_fixed_per_order` decimal(10,2) DEFAULT '0.00',
  `commission_monthly` decimal(10,2) DEFAULT '0.00',
  `dine_in` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `takeaway` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `delivery` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1-active, 0-pending, 2-blocked',
  `add_category` tinyint NOT NULL DEFAULT '1' COMMENT '0 for no, 1 for yes',
  `setting` tinyint NOT NULL DEFAULT '0' COMMENT '0 for no, 1 for yes',
  `is_show_vendor_details` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `show_slot` tinyint NOT NULL DEFAULT '1' COMMENT '1 for yes, 0 for no',
  `vendor_templete_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `slug`, `desc`, `logo`, `banner`, `address`, `email`, `website`, `phone_no`, `latitude`, `longitude`, `order_min_amount`, `order_pre_time`, `auto_reject_time`, `commission_percent`, `commission_fixed_per_order`, `commission_monthly`, `dine_in`, `takeaway`, `delivery`, `status`, `add_category`, `setting`, `is_show_vendor_details`, `created_at`, `updated_at`, `show_slot`, `vendor_templete_id`) VALUES
(1, 'DeliveryZone', 'deliveryzone', NULL, 'default/default_logo.png', 'default/default_image.png', 'Sheikh Zayed Road - Dubai - United Arab Emirates', NULL, NULL, NULL, '25.060924600000', '55.128979500000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 2, 1, 0, 0, NULL, '2021-06-28 13:58:51', 1, NULL),
(2, 'Gritty Dirt Mx Inc', 'gritty-dirt-mx-inc', 'Test', 'vendor/dpKx5tDEynpyxBq7HblatYEptJC9IiwrnGg3wnbk.png', 'vendor/ixTZ3JhmstaLgOz9jqN60zI56YBdeWdjmWzboIFw.png', 'Avenida Allende Oriente 110, Centro, Tepic, Nayarit, Mexico', NULL, NULL, NULL, '21.507790200000', '-104.891038700000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 2, 1, 0, 0, '2021-06-09 14:55:15', '2021-07-16 12:48:39', 1, NULL),
(3, 'Maxico Business Associates', 'maxico-business-associates', 'About the business', 'vendor/jlvG6PK7bexAOWtzHlkNsI4W3G1YwONR3V1rLmYb.png', 'vendor/VzIWKWeG7fRSVepzneGMtVGVicNlesUdragSPmV7.png', 'Guadalajara, Jalisco, Mexico', NULL, NULL, NULL, '20.659698800000', '-103.349609200000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 2, 1, 0, 0, '2021-06-09 15:10:35', '2021-07-16 12:48:43', 1, NULL),
(4, 'Hmoob Restaurant', 'hmoob-restaurant', 'This is the testing restaurant', 'vendor/XBnCSxKvjIUE3UKnZulXlmNOPgmpQoZLsFUP3f69.jpg', 'vendor/hpREO37wnt9rcpFOYYrKzCZbIIH4NsauLwMAg4Is.jpg', 'Mexico City, CDMX, Mexico', NULL, NULL, NULL, '19.432607700000', '-99.133208000000', '0.00', NULL, NULL, 1, '0.00', '0.00', 1, 1, 1, 2, 1, 0, 0, '2021-06-09 15:43:45', '2021-07-16 12:48:49', 1, NULL),
(5, 'The Capital Grille', 'the-capital-grille', NULL, 'vendor/VXrG9syBVs1dOMivcHe8gfezz38vQlOfBtYTAqCS.jpg', 'vendor/kEeaIYDfujflGEXsKKe73rhVG4GGw8WnFXHAb3cJ.jpg', 'AV. DE LA REPUBLICA 27 S/N, BADILLO, 911106,VERACRUZ', 'capitalgrill@support.com', NULL, '5454545454', '19.538008800000', '-96.896961500000', '0.00', '25', 'NULL', 10, '10.00', '0.00', 0, 0, 1, 1, 1, 0, 0, NULL, '2021-07-16 12:56:09', 1, NULL),
(6, 'Daniel', 'daniel', NULL, 'vendor/ipm6jLgtCCJx3Anx4pvqd6sI1Pe9xPrPTTybQgSR.png', 'vendor/0s8xvS88JH3WB1jVlshHcutyh7C16t0ZIz3PkbSS.jpg', 'ACAMAPICHTLI 118, EJIDAL OCOLUSEN, 582105,	MICHOACAN', 'daniel@support.com', NULL, '4343434343', '19.684928900000', '-101.156560700000', '500.00', '30', '5', 15, '15.00', '0.00', 0, 0, 1, 1, 1, 0, 0, NULL, '2021-07-16 12:54:52', 1, NULL),
(7, 'Chill & Grill', 'chill-grill', NULL, 'vendor/XT4xdxZLBV1XV9Zeyamg2ZULkI4JcBEGKw3suEgf.png', 'vendor/byvHSfaLReUl6A04cgLhdVrXLES5MkXvy7XwI0zf.jpg', 'MONTECRISTO 27, SAN ROMAN, 28787,Campeche', 'chill&grill@support.com', NULL, '5656565656', '19.833892400000', '-90.549373600000', '0.00', '20', 'NULL', 20, '10.00', '0.00', 0, 0, 1, 1, 1, 0, 0, NULL, '2021-07-16 12:54:16', 1, NULL),
(8, 'Carmine\'s Italian', 'carmines-italian', 'NULL', 'vendor/uYBxEzeL7iRykCQDq2rf1FuIqWddAdfyOavOy5ai.jpg', 'vendor/qsNQoCx2F6UPWy1JwLB0hzxutFz5M0ksaxlTyQo5.jpg', 'VENEZUELA 663, MODERNA, 441104,JALISCO', 'carmines@support.com', NULL, '3434343434', '20.666537200000', '-103.363336700000', '0.00', '15', '2', 15, '12.00', '0.00', 0, 0, 1, 1, 1, 0, 0, NULL, '2021-07-16 12:51:52', 1, NULL),
(9, 'Seasons 52', 'seasons-52', NULL, 'vendor/C8uFCRkbpSczjr1EUhCZhJnScxIh28rmcGQg96dM.png', 'vendor/PGVzJdPJXCHThnUwSm7D1nETQefFbztjfihdjkDr.jpg', 'J. Ortiz D. Dominguez S2Ur 242,Toluca', 'season@support.com', NULL, '7458965877', '34.060077900000', '-118.258978800000', '0.00', '20', 'NULL', 10, '13.00', '0.00', 0, 0, 1, 1, 1, 0, 0, NULL, '2021-07-16 12:50:30', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_categories`
--

CREATE TABLE `vendor_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `vendor_id`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(2, 3, 3, 0, '2021-06-09 15:28:21', '2021-06-09 15:28:22'),
(3, 4, 3, 1, '2021-06-09 15:43:57', '2021-06-09 15:43:57'),
(4, 7, 3, 1, '2021-07-19 09:00:13', '2021-07-19 09:00:13'),
(6, 9, 22, 1, '2021-07-19 12:34:17', '2021-07-19 12:34:17');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_docs`
--

CREATE TABLE `vendor_docs` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `file_name` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_media`
--

CREATE TABLE `vendor_media` (
  `id` bigint UNSIGNED NOT NULL,
  `media_type` tinyint NOT NULL DEFAULT '1' COMMENT '1 - image, 2 - video, 3 - file',
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `vendor_media`
--

INSERT INTO `vendor_media` (`id`, `media_type`, `vendor_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'prods/aOUeJm7ri5gbj9eBVcwAnwf5fCqRReBFRDRCAxDn.jpg', '2021-06-09 15:17:42', '2021-06-09 15:17:42'),
(2, 1, 7, 'prods/Sux19prmMH1dhQTLNtlHeC0Gv40ibyRHyfXCn5aU.jpg', '2021-07-19 09:02:01', '2021-07-19 09:02:01'),
(3, 1, 7, 'prods/X62To7h7ICZN7fiPMXUJ6urQcNi9WSJlJ5kDDF5f.jpg', '2021-07-19 09:08:08', '2021-07-19 09:08:08'),
(4, 1, 7, 'prods/WIhmCQQ9egUrgjhNxzVIDyTk76tjRXvBeRnYpjC3.jpg', '2021-07-19 09:09:56', '2021-07-19 09:09:56'),
(5, 1, 7, 'prods/K8G8J8eYvWYYag1zMjG3tqMiJSetXU58ArfLqQsc.jpg', '2021-07-19 09:13:09', '2021-07-19 09:13:09'),
(6, 1, 9, 'prods/5cOgwKGHDfFfpbooZLTnJGB7AIBrfsS5Y3eCOvzF.jpg', '2021-07-19 12:34:52', '2021-07-19 12:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_order_dispatcher_statuses`
--

CREATE TABLE `vendor_order_dispatcher_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `dispatcher_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `dispatcher_status_option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_order_statuses`
--

CREATE TABLE `vendor_order_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `order_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `order_status_option_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Dumping data for table `vendor_order_statuses`
--

INSERT INTO `vendor_order_statuses` (`id`, `order_id`, `order_vendor_id`, `order_status_option_id`, `created_at`, `updated_at`, `vendor_id`) VALUES
(1, 1, 1, 1, '2021-07-19 12:03:40', '2021-07-19 12:03:40', 7),
(2, 1, NULL, 2, '2021-07-19 12:04:58', '2021-07-19 12:04:58', 7),
(3, 1, NULL, 4, '2021-07-19 12:05:00', '2021-07-19 12:05:00', 7),
(4, 1, NULL, 5, '2021-07-19 12:05:02', '2021-07-19 12:05:02', 7),
(5, 1, NULL, 6, '2021-07-19 12:05:04', '2021-07-19 12:05:04', 7);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_registration_documents`
--

CREATE TABLE `vendor_registration_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_registration_document_translations`
--

CREATE TABLE `vendor_registration_document_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `vendor_registration_document_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_slots`
--

CREATE TABLE `vendor_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `geo_id` bigint UNSIGNED DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `dine_in` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `takeaway` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `delivery` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_slot_dates`
--

CREATE TABLE `vendor_slot_dates` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `specific_date` date NOT NULL,
  `working_today` tinyint NOT NULL DEFAULT '1' COMMENT '1 - yes, 0 - no',
  `dine_in` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `takeaway` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `delivery` tinyint NOT NULL DEFAULT '0' COMMENT '1 for yes, 0 for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_templetes`
--

CREATE TABLE `vendor_templetes` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '1 - active, 0 - inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_templetes`
--

INSERT INTO `vendor_templetes` (`id`, `title`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Product', 'Grid', 1, NULL, NULL),
(2, 'Category', 'Grid', 1, NULL, NULL),
(3, 'Product', 'List', 0, NULL, NULL),
(4, 'Category', 'List', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_users`
--

CREATE TABLE `vendor_users` (
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `holder_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holder_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `balance` decimal(64,0) NOT NULL DEFAULT '0',
  `decimal_places` smallint NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci TABLESPACE `innodb_system`;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addon_options`
--
ALTER TABLE `addon_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_options_addon_id_foreign` (`addon_id`);

--
-- Indexes for table `addon_option_translations`
--
ALTER TABLE `addon_option_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_option_translations_addon_opt_id_foreign` (`addon_opt_id`),
  ADD KEY `addon_option_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `addon_sets`
--
ALTER TABLE `addon_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_sets_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `addon_set_translations`
--
ALTER TABLE `addon_set_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_set_translations_addon_id_foreign` (`addon_id`),
  ADD KEY `addon_set_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_stylings`
--
ALTER TABLE `app_stylings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_styling_options`
--
ALTER TABLE `app_styling_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_styling_options_app_styling_id_foreign` (`app_styling_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_redirect_category_id_foreign` (`redirect_category_id`),
  ADD KEY `banners_redirect_vendor_id_foreign` (`redirect_vendor_id`),
  ADD KEY `banners_name_index` (`name`),
  ADD KEY `banners_status_index` (`status`),
  ADD KEY `banners_start_date_time_index` (`start_date_time`),
  ADD KEY `banners_end_date_time_index` (`end_date_time`);

--
-- Indexes for table `blocked_tokens`
--
ALTER TABLE `blocked_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_position_index` (`position`),
  ADD KEY `brands_status_index` (`status`);

--
-- Indexes for table `brand_categories`
--
ALTER TABLE `brand_categories`
  ADD KEY `brand_categories_brand_id_foreign` (`brand_id`),
  ADD KEY `brand_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `brand_translations`
--
ALTER TABLE `brand_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_translations_brand_id_foreign` (`brand_id`),
  ADD KEY `brand_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_created_by_foreign` (`created_by`),
  ADD KEY `carts_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `cart_addons`
--
ALTER TABLE `cart_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_addons_cart_product_id_foreign` (`cart_product_id`),
  ADD KEY `cart_addons_addon_id_foreign` (`addon_id`),
  ADD KEY `cart_addons_option_id_foreign` (`option_id`);

--
-- Indexes for table `cart_coupons`
--
ALTER TABLE `cart_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_coupons_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_coupons_coupon_id_foreign` (`coupon_id`);

--
-- Indexes for table `cart_products`
--
ALTER TABLE `cart_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_products_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_products_product_id_foreign` (`product_id`),
  ADD KEY `cart_products_vendor_id_foreign` (`vendor_id`),
  ADD KEY `cart_products_created_by_foreign` (`created_by`),
  ADD KEY `cart_products_variant_id_foreign` (`variant_id`),
  ADD KEY `cart_products_tax_rate_id_foreign` (`tax_rate_id`),
  ADD KEY `cart_products_status_index` (`status`),
  ADD KEY `cart_products_is_tax_applied_index` (`is_tax_applied`),
  ADD KEY `cart_products_tax_category_id_foreign` (`tax_category_id`);

--
-- Indexes for table `cart_product_prescriptions`
--
ALTER TABLE `cart_product_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_product_prescriptions_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_product_prescriptions_product_id_foreign` (`product_id`),
  ADD KEY `cart_product_prescriptions_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_client_code_foreign` (`client_code`),
  ADD KEY `categories_vendor_id_foreign` (`vendor_id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`),
  ADD KEY `categories_type_id_foreign` (`type_id`),
  ADD KEY `categories_status_index` (`status`),
  ADD KEY `categories_is_core_index` (`is_core`),
  ADD KEY `categories_position_index` (`position`),
  ADD KEY `categories_can_add_products_index` (`can_add_products`),
  ADD KEY `categories_display_mode_index` (`display_mode`);

--
-- Indexes for table `category_histories`
--
ALTER TABLE `category_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_histories_category_id_foreign` (`category_id`);

--
-- Indexes for table `category_tags`
--
ALTER TABLE `category_tags`
  ADD KEY `category_tags_category_id_foreign` (`category_id`);

--
-- Indexes for table `category_translations`
--
ALTER TABLE `category_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_translations_name_index` (`name`),
  ADD KEY `category_translations_category_id_foreign` (`category_id`),
  ADD KEY `category_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `celebrities`
--
ALTER TABLE `celebrities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `celebrities_email_unique` (`email`),
  ADD KEY `celebrities_country_id_foreign` (`country_id`);

--
-- Indexes for table `celebrity_brands`
--
ALTER TABLE `celebrity_brands`
  ADD KEY `celebrity_brands_celebrity_id_foreign` (`celebrity_id`),
  ADD KEY `celebrity_brands_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD UNIQUE KEY `clients_code_unique` (`code`),
  ADD KEY `clients_country_id_foreign` (`country_id`),
  ADD KEY `clients_language_id_foreign` (`language_id`),
  ADD KEY `clients_phone_number_index` (`phone_number`),
  ADD KEY `clients_custom_domain_index` (`custom_domain`),
  ADD KEY `clients_is_deleted_index` (`is_deleted`),
  ADD KEY `clients_is_blocked_index` (`is_blocked`),
  ADD KEY `clients_database_name_index` (`database_name`),
  ADD KEY `clients_company_name_index` (`company_name`),
  ADD KEY `clients_status_index` (`status`);

--
-- Indexes for table `client_currencies`
--
ALTER TABLE `client_currencies`
  ADD KEY `client_currencies_client_code_foreign` (`client_code`),
  ADD KEY `client_currencies_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `client_languages`
--
ALTER TABLE `client_languages`
  ADD KEY `client_languages_client_code_foreign` (`client_code`),
  ADD KEY `client_languages_language_id_foreign` (`language_id`);

--
-- Indexes for table `client_preferences`
--
ALTER TABLE `client_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_preferences_client_code_unique` (`client_code`),
  ADD KEY `client_preferences_fb_login_index` (`fb_login`),
  ADD KEY `client_preferences_twitter_login_index` (`twitter_login`),
  ADD KEY `client_preferences_google_login_index` (`google_login`),
  ADD KEY `client_preferences_apple_login_index` (`apple_login`),
  ADD KEY `client_preferences_verify_email_index` (`verify_email`),
  ADD KEY `client_preferences_verify_phone_index` (`verify_phone`),
  ADD KEY `client_preferences_currency_id_foreign` (`currency_id`),
  ADD KEY `client_preferences_language_id_foreign` (`language_id`),
  ADD KEY `client_preferences_map_provider_foreign` (`map_provider`),
  ADD KEY `client_preferences_sms_provider_foreign` (`sms_provider`),
  ADD KEY `client_preferences_web_template_id_foreign` (`web_template_id`),
  ADD KEY `client_preferences_app_template_id_foreign` (`app_template_id`),
  ADD KEY `client_preferences_is_hyperlocal_index` (`is_hyperlocal`),
  ADD KEY `client_preferences_need_delivery_service_index` (`need_delivery_service`),
  ADD KEY `client_preferences_mail_type_index` (`mail_type`),
  ADD KEY `client_preferences_celebrity_check_index` (`celebrity_check`);

--
-- Indexes for table `cms`
--
ALTER TABLE `cms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_title_unique` (`title`),
  ADD KEY `cms_language_id_foreign` (`language_id`),
  ADD KEY `cms_title_index` (`title`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countries_code_index` (`code`),
  ADD KEY `countries_name_index` (`name`);

--
-- Indexes for table `csv_product_imports`
--
ALTER TABLE `csv_product_imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `csv_product_imports_vendor_id_foreign` (`vendor_id`),
  ADD KEY `csv_product_imports_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `csv_vendor_imports`
--
ALTER TABLE `csv_vendor_imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `csv_vendor_imports_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currencies_name_index` (`name`),
  ADD KEY `currencies_priority_index` (`priority`),
  ADD KEY `currencies_iso_code_index` (`iso_code`),
  ADD KEY `currencies_iso_numeric_index` (`iso_numeric`);

--
-- Indexes for table `dispatcher_status_options`
--
ALTER TABLE `dispatcher_status_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatcher_template_type_options`
--
ALTER TABLE `dispatcher_template_type_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatcher_warning_pages`
--
ALTER TABLE `dispatcher_warning_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_sort_code_unique` (`sort_code`),
  ADD UNIQUE KEY `languages_name_unique` (`name`);

--
-- Indexes for table `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `luxury_options`
--
ALTER TABLE `luxury_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `map_providers`
--
ALTER TABLE `map_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nomenclatures`
--
ALTER TABLE `nomenclatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_types`
--
ALTER TABLE `notification_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_types_name_index` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_address_id_foreign` (`address_id`),
  ADD KEY `orders_tax_category_id_foreign` (`tax_category_id`),
  ADD KEY `orders_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `order_product_addons`
--
ALTER TABLE `order_product_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_addons_order_product_id_foreign` (`order_product_id`),
  ADD KEY `order_product_addons_addon_id_foreign` (`addon_id`),
  ADD KEY `order_product_addons_option_id_foreign` (`option_id`);

--
-- Indexes for table `order_product_prescriptions`
--
ALTER TABLE `order_product_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_prescriptions_order_id_foreign` (`order_id`),
  ADD KEY `order_product_prescriptions_product_id_foreign` (`product_id`),
  ADD KEY `order_product_prescriptions_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `order_product_ratings`
--
ALTER TABLE `order_product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_ratings_order_id_foreign` (`order_id`),
  ADD KEY `order_product_ratings_order_vendor_product_id_foreign` (`order_vendor_product_id`);

--
-- Indexes for table `order_product_rating_files`
--
ALTER TABLE `order_product_rating_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_rating_files_order_product_rating_id_foreign` (`order_product_rating_id`);

--
-- Indexes for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_return_requests_order_vendor_product_id_foreign` (`order_vendor_product_id`),
  ADD KEY `order_return_requests_order_id_foreign` (`order_id`),
  ADD KEY `order_return_requests_return_by_foreign` (`return_by`);

--
-- Indexes for table `order_return_request_files`
--
ALTER TABLE `order_return_request_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_return_request_files_order_return_request_id_foreign` (`order_return_request_id`);

--
-- Indexes for table `order_status_options`
--
ALTER TABLE `order_status_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_taxes`
--
ALTER TABLE `order_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_vendors_order_id_foreign` (`order_id`),
  ADD KEY `order_vendors_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `order_vendor_products`
--
ALTER TABLE `order_vendor_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_products_order_id_foreign` (`order_id`),
  ADD KEY `order_products_product_id_foreign` (`product_id`),
  ADD KEY `order_products_tax_category_id_foreign` (`tax_category_id`),
  ADD KEY `order_products_vendor_id_foreign` (`vendor_id`),
  ADD KEY `order_products_variant_id_foreign` (`variant_id`),
  ADD KEY `order_vendor_products_category_id_foreign` (`category_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_translations`
--
ALTER TABLE `page_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_translations_page_id_foreign` (`page_id`),
  ADD KEY `page_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_cart_id_foreign` (`cart_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `payment_options`
--
ALTER TABLE `payment_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_vendor_id_foreign` (`vendor_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_type_id_foreign` (`type_id`),
  ADD KEY `products_country_origin_id_foreign` (`country_origin_id`),
  ADD KEY `products_is_new_index` (`is_new`),
  ADD KEY `products_is_featured_index` (`is_featured`),
  ADD KEY `products_is_live_index` (`is_live`),
  ADD KEY `products_is_physical_index` (`is_physical`),
  ADD KEY `products_has_inventory_index` (`has_inventory`),
  ADD KEY `products_sell_when_out_of_stock_index` (`sell_when_out_of_stock`),
  ADD KEY `products_requires_shipping_index` (`requires_shipping`),
  ADD KEY `products_requires_last_mile_index` (`Requires_last_mile`),
  ADD KEY `products_averagerating_index` (`averageRating`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_tax_category_id_foreign` (`tax_category_id`);

--
-- Indexes for table `product_addons`
--
ALTER TABLE `product_addons`
  ADD KEY `product_addons_product_id_foreign` (`product_id`),
  ADD KEY `product_addons_addon_id_foreign` (`addon_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD KEY `product_categories_product_id_foreign` (`product_id`),
  ADD KEY `product_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_celebrities`
--
ALTER TABLE `product_celebrities`
  ADD KEY `product_celebrities_celebrity_id_foreign` (`celebrity_id`),
  ADD KEY `product_celebrities_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_cross_sells`
--
ALTER TABLE `product_cross_sells`
  ADD KEY `product_cross_sells_product_id_foreign` (`product_id`),
  ADD KEY `product_cross_sells_cross_product_id_foreign` (`cross_product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`),
  ADD KEY `product_images_media_id_foreign` (`media_id`);

--
-- Indexes for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_inquiries_product_id_foreign` (`product_id`),
  ADD KEY `product_inquiries_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `product_related`
--
ALTER TABLE `product_related`
  ADD KEY `product_related_product_id_foreign` (`product_id`),
  ADD KEY `product_related_related_product_id_foreign` (`related_product_id`);

--
-- Indexes for table `product_translations`
--
ALTER TABLE `product_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_translations_product_id_foreign` (`product_id`),
  ADD KEY `product_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `product_up_sells`
--
ALTER TABLE `product_up_sells`
  ADD KEY `product_up_sells_product_id_foreign` (`product_id`),
  ADD KEY `product_up_sells_upsell_product_id_foreign` (`upsell_product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD UNIQUE KEY `product_variants_barcode_unique` (`barcode`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_variants_tax_category_id_foreign` (`tax_category_id`),
  ADD KEY `product_variants_sku_index` (`sku`),
  ADD KEY `product_variants_quantity_index` (`quantity`),
  ADD KEY `product_variants_price_index` (`price`),
  ADD KEY `product_variants_compare_at_price_index` (`compare_at_price`),
  ADD KEY `product_variants_cost_price_index` (`cost_price`);

--
-- Indexes for table `product_variant_images`
--
ALTER TABLE `product_variant_images`
  ADD KEY `product_variant_images_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `product_variant_images_product_image_id_foreign` (`product_image_id`);

--
-- Indexes for table `product_variant_sets`
--
ALTER TABLE `product_variant_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_sets_product_id_foreign` (`product_id`),
  ADD KEY `product_variant_sets_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `product_variant_sets_variant_type_id_foreign` (`variant_type_id`),
  ADD KEY `product_variant_sets_variant_option_id_foreign` (`variant_option_id`);

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promocodes_promo_type_id_foreign` (`promo_type_id`),
  ADD KEY `promocodes_created_by_foreign` (`created_by`),
  ADD KEY `promocodes_allow_free_delivery_index` (`allow_free_delivery`),
  ADD KEY `promocodes_minimum_spend_index` (`minimum_spend`),
  ADD KEY `promocodes_maximum_spend_index` (`maximum_spend`),
  ADD KEY `promocodes_first_order_only_index` (`first_order_only`),
  ADD KEY `promocodes_limit_per_user_index` (`limit_per_user`),
  ADD KEY `promocodes_limit_total_index` (`limit_total`),
  ADD KEY `promocodes_paid_by_vendor_admin_index` (`paid_by_vendor_admin`),
  ADD KEY `promocodes_is_deleted_index` (`is_deleted`);

--
-- Indexes for table `promocode_details`
--
ALTER TABLE `promocode_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promocode_restrictions`
--
ALTER TABLE `promocode_restrictions`
  ADD KEY `promocode_restrictions_is_included_index` (`is_included`),
  ADD KEY `promocode_restrictions_is_excluded_index` (`is_excluded`),
  ADD KEY `promocode_restrictions_promocode_id_foreign` (`promocode_id`);

--
-- Indexes for table `promo_types`
--
ALTER TABLE `promo_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_usages`
--
ALTER TABLE `promo_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo_usages_promocode_id_foreign` (`promocode_id`),
  ADD KEY `promo_usages_user_id_foreign` (`user_id`);

--
-- Indexes for table `refer_and_earns`
--
ALTER TABLE `refer_and_earns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refer_and_earns_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_reasons`
--
ALTER TABLE `return_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_areas_vendor_id_foreign` (`vendor_id`),
  ADD KEY `service_areas_name_index` (`name`);

--
-- Indexes for table `slot_days`
--
ALTER TABLE `slot_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slot_days_slot_id_foreign` (`slot_id`),
  ADD KEY `slot_days_day_index` (`day`);

--
-- Indexes for table `sms_providers`
--
ALTER TABLE `sms_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_credentials`
--
ALTER TABLE `social_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_features_list_user`
--
ALTER TABLE `subscription_features_list_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_features_list_vendor`
--
ALTER TABLE `subscription_features_list_vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_invoices_user`
--
ALTER TABLE `subscription_invoices_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_invoices_user_user_id_index` (`user_id`),
  ADD KEY `subscription_invoices_user_status_id_index` (`status_id`),
  ADD KEY `subscription_invoices_user_subscription_id_index` (`subscription_id`),
  ADD KEY `subscription_invoices_user_payment_option_id_index` (`payment_option_id`);

--
-- Indexes for table `subscription_invoice_features_user`
--
ALTER TABLE `subscription_invoice_features_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_invoice_features_user_subscription_invoice_id_index` (`subscription_invoice_id`),
  ADD KEY `subscription_invoice_features_user_feature_id_index` (`feature_id`);

--
-- Indexes for table `subscription_log_user`
--
ALTER TABLE `subscription_log_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans_user`
--
ALTER TABLE `subscription_plans_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans_vendor`
--
ALTER TABLE `subscription_plans_vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plan_features_user`
--
ALTER TABLE `subscription_plan_features_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_plan_features_user_subscription_plan_id_foreign` (`subscription_plan_id`),
  ADD KEY `subscription_plan_features_user_feature_id_foreign` (`feature_id`);

--
-- Indexes for table `subscription_plan_features_vendor`
--
ALTER TABLE `subscription_plan_features_vendor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_plan_features_vendor_subscription_plan_id_foreign` (`subscription_plan_id`),
  ADD KEY `subscription_plan_features_vendor_feature_id_foreign` (`feature_id`);

--
-- Indexes for table `subscription_status_options`
--
ALTER TABLE `subscription_status_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_categories`
--
ALTER TABLE `tax_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_categories_vendor_id_foreign` (`vendor_id`),
  ADD KEY `tax_categories_code_index` (`code`),
  ADD KEY `tax_categories_is_core_index` (`is_core`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_rates_is_zip_index` (`is_zip`);

--
-- Indexes for table `tax_rate_categories`
--
ALTER TABLE `tax_rate_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_rate_categories_tax_cate_id_foreign` (`tax_cate_id`),
  ADD KEY `tax_rate_categories_tax_rate_id_foreign` (`tax_rate_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminologies`
--
ALTER TABLE `terminologies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_uuid_unique` (`uuid`),
  ADD KEY `transactions_payable_type_payable_id_index` (`payable_type`,`payable_id`),
  ADD KEY `payable_type_ind` (`payable_type`,`payable_id`,`type`),
  ADD KEY `payable_confirmed_ind` (`payable_type`,`payable_id`,`confirmed`),
  ADD KEY `payable_type_confirmed_ind` (`payable_type`,`payable_id`,`type`,`confirmed`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_wallet_id_foreign` (`wallet_id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfers_uuid_unique` (`uuid`),
  ADD KEY `transfers_from_type_from_id_index` (`from_type`,`from_id`),
  ADD KEY `transfers_to_type_to_id_index` (`to_type`,`to_id`),
  ADD KEY `transfers_deposit_id_foreign` (`deposit_id`),
  ADD KEY `transfers_withdraw_id_foreign` (`withdraw_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_country_id_foreign` (`country_id`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_phone_number_index` (`phone_number`),
  ADD KEY `users_type_index` (`type`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_facebook_auth_id_index` (`facebook_auth_id`),
  ADD KEY `users_twitter_auth_id_index` (`twitter_auth_id`),
  ADD KEY `users_google_auth_id_index` (`google_auth_id`),
  ADD KEY `users_apple_auth_id_index` (`apple_auth_id`),
  ADD KEY `users_timezone_id_foreign` (`timezone_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_devices_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_loyalty_points`
--
ALTER TABLE `user_loyalty_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_loyalty_points_points_index` (`points`),
  ADD KEY `user_loyalty_points_user_id_foreign` (`user_id`),
  ADD KEY `user_loyalty_points_loyalty_card_id_foreign` (`loyalty_card_id`);

--
-- Indexes for table `user_loyalty_point_histories`
--
ALTER TABLE `user_loyalty_point_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_refferals`
--
ALTER TABLE `user_refferals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_refferals_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_vendors`
--
ALTER TABLE `user_vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_vendors_vendor_id_foreign` (`vendor_id`),
  ADD KEY `user_vendors_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_wishlists_user_id_foreign` (`user_id`),
  ADD KEY `user_wishlists_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variants_type_index` (`type`),
  ADD KEY `variants_position_index` (`position`),
  ADD KEY `variants_status_index` (`status`);

--
-- Indexes for table `variant_categories`
--
ALTER TABLE `variant_categories`
  ADD KEY `variant_categories_variant_id_foreign` (`variant_id`),
  ADD KEY `variant_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_options_position_index` (`position`),
  ADD KEY `variant_options_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `variant_option_translations`
--
ALTER TABLE `variant_option_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_option_translations_variant_option_id_foreign` (`variant_option_id`),
  ADD KEY `variant_option_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `variant_translations`
--
ALTER TABLE `variant_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_translations_variant_id_foreign` (`variant_id`),
  ADD KEY `variant_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendors_name_index` (`name`),
  ADD KEY `vendors_order_min_amount_index` (`order_min_amount`),
  ADD KEY `vendors_order_pre_time_index` (`order_pre_time`),
  ADD KEY `vendors_auto_reject_time_index` (`auto_reject_time`),
  ADD KEY `vendors_commission_percent_index` (`commission_percent`),
  ADD KEY `vendors_commission_fixed_per_order_index` (`commission_fixed_per_order`),
  ADD KEY `vendors_commission_monthly_index` (`commission_monthly`),
  ADD KEY `vendors_dine_in_index` (`dine_in`),
  ADD KEY `vendors_takeaway_index` (`takeaway`),
  ADD KEY `vendors_delivery_index` (`delivery`),
  ADD KEY `vendors_add_category_index` (`add_category`),
  ADD KEY `vendors_vendor_templete_id_foreign` (`vendor_templete_id`);

--
-- Indexes for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_categories_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `vendor_docs`
--
ALTER TABLE `vendor_docs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_media`
--
ALTER TABLE `vendor_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_media_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_media_media_type_index` (`media_type`);

--
-- Indexes for table `vendor_order_dispatcher_statuses`
--
ALTER TABLE `vendor_order_dispatcher_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispatcher_statuses_order_id_foreign` (`order_id`),
  ADD KEY `dispatcher_statuses_dispatcher_status_option_id_foreign` (`dispatcher_status_option_id`),
  ADD KEY `vendor_order_dispatcher_statuses_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_order_statuses`
--
ALTER TABLE `vendor_order_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_statuses_order_id_foreign` (`order_id`),
  ADD KEY `order_statuses_order_status_option_id_foreign` (`order_status_option_id`),
  ADD KEY `vendor_order_statuses_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_registration_documents`
--
ALTER TABLE `vendor_registration_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_registration_document_translations`
--
ALTER TABLE `vendor_registration_document_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_slots`
--
ALTER TABLE `vendor_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_slots_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_slots_category_id_foreign` (`category_id`),
  ADD KEY `vendor_slots_start_time_index` (`start_time`),
  ADD KEY `vendor_slots_end_time_index` (`end_time`),
  ADD KEY `vendor_slots_dine_in_index` (`dine_in`),
  ADD KEY `vendor_slots_takeaway_index` (`takeaway`),
  ADD KEY `vendor_slots_delivery_index` (`delivery`);

--
-- Indexes for table `vendor_slot_dates`
--
ALTER TABLE `vendor_slot_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_slot_dates_specific_date_index` (`specific_date`),
  ADD KEY `vendor_slot_dates_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_slot_dates_category_id_foreign` (`category_id`),
  ADD KEY `vendor_slot_dates_dine_in_index` (`dine_in`),
  ADD KEY `vendor_slot_dates_takeaway_index` (`takeaway`),
  ADD KEY `vendor_slot_dates_delivery_index` (`delivery`);

--
-- Indexes for table `vendor_templetes`
--
ALTER TABLE `vendor_templetes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_users`
--
ALTER TABLE `vendor_users`
  ADD KEY `vendor_users_user_id_foreign` (`user_id`),
  ADD KEY `vendor_users_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallets_holder_type_holder_id_slug_unique` (`holder_type`,`holder_id`,`slug`),
  ADD KEY `wallets_holder_type_holder_id_index` (`holder_type`,`holder_id`),
  ADD KEY `wallets_slug_index` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addon_options`
--
ALTER TABLE `addon_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `addon_option_translations`
--
ALTER TABLE `addon_option_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `addon_sets`
--
ALTER TABLE `addon_sets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `addon_set_translations`
--
ALTER TABLE `addon_set_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_stylings`
--
ALTER TABLE `app_stylings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `app_styling_options`
--
ALTER TABLE `app_styling_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blocked_tokens`
--
ALTER TABLE `blocked_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `brand_translations`
--
ALTER TABLE `brand_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_addons`
--
ALTER TABLE `cart_addons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_coupons`
--
ALTER TABLE `cart_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_products`
--
ALTER TABLE `cart_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_product_prescriptions`
--
ALTER TABLE `cart_product_prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `category_histories`
--
ALTER TABLE `category_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `category_translations`
--
ALTER TABLE `category_translations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `celebrities`
--
ALTER TABLE `celebrities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_preferences`
--
ALTER TABLE `client_preferences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms`
--
ALTER TABLE `cms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `csv_product_imports`
--
ALTER TABLE `csv_product_imports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `csv_vendor_imports`
--
ALTER TABLE `csv_vendor_imports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `dispatcher_status_options`
--
ALTER TABLE `dispatcher_status_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dispatcher_template_type_options`
--
ALTER TABLE `dispatcher_template_type_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dispatcher_warning_pages`
--
ALTER TABLE `dispatcher_warning_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `luxury_options`
--
ALTER TABLE `luxury_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `map_providers`
--
ALTER TABLE `map_providers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `nomenclatures`
--
ALTER TABLE `nomenclatures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_product_addons`
--
ALTER TABLE `order_product_addons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_prescriptions`
--
ALTER TABLE `order_product_prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_ratings`
--
ALTER TABLE `order_product_ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_rating_files`
--
ALTER TABLE `order_product_rating_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return_request_files`
--
ALTER TABLE `order_return_request_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_options`
--
ALTER TABLE `order_status_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_taxes`
--
ALTER TABLE `order_taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_vendors`
--
ALTER TABLE `order_vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_vendor_products`
--
ALTER TABLE `order_vendor_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `page_translations`
--
ALTER TABLE `page_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_options`
--
ALTER TABLE `payment_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_translations`
--
ALTER TABLE `product_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_variant_sets`
--
ALTER TABLE `product_variant_sets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promocode_details`
--
ALTER TABLE `promocode_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promo_types`
--
ALTER TABLE `promo_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promo_usages`
--
ALTER TABLE `promo_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refer_and_earns`
--
ALTER TABLE `refer_and_earns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_reasons`
--
ALTER TABLE `return_reasons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_areas`
--
ALTER TABLE `service_areas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `slot_days`
--
ALTER TABLE `slot_days`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_providers`
--
ALTER TABLE `sms_providers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `social_credentials`
--
ALTER TABLE `social_credentials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_features_list_user`
--
ALTER TABLE `subscription_features_list_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_features_list_vendor`
--
ALTER TABLE `subscription_features_list_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_invoices_user`
--
ALTER TABLE `subscription_invoices_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_invoice_features_user`
--
ALTER TABLE `subscription_invoice_features_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_log_user`
--
ALTER TABLE `subscription_log_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans_user`
--
ALTER TABLE `subscription_plans_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans_vendor`
--
ALTER TABLE `subscription_plans_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plan_features_user`
--
ALTER TABLE `subscription_plan_features_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plan_features_vendor`
--
ALTER TABLE `subscription_plan_features_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_status_options`
--
ALTER TABLE `subscription_status_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_categories`
--
ALTER TABLE `tax_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_rate_categories`
--
ALTER TABLE `tax_rate_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `terminologies`
--
ALTER TABLE `terminologies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_loyalty_points`
--
ALTER TABLE `user_loyalty_points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_loyalty_point_histories`
--
ALTER TABLE `user_loyalty_point_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_refferals`
--
ALTER TABLE `user_refferals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_vendors`
--
ALTER TABLE `user_vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `variant_options`
--
ALTER TABLE `variant_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `variant_option_translations`
--
ALTER TABLE `variant_option_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `variant_translations`
--
ALTER TABLE `variant_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor_docs`
--
ALTER TABLE `vendor_docs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_media`
--
ALTER TABLE `vendor_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor_order_dispatcher_statuses`
--
ALTER TABLE `vendor_order_dispatcher_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_order_statuses`
--
ALTER TABLE `vendor_order_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vendor_registration_documents`
--
ALTER TABLE `vendor_registration_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_registration_document_translations`
--
ALTER TABLE `vendor_registration_document_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_slots`
--
ALTER TABLE `vendor_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_slot_dates`
--
ALTER TABLE `vendor_slot_dates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_templetes`
--
ALTER TABLE `vendor_templetes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addon_options`
--
ALTER TABLE `addon_options`
  ADD CONSTRAINT `addon_options_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `addon_option_translations`
--
ALTER TABLE `addon_option_translations`
  ADD CONSTRAINT `addon_option_translations_addon_opt_id_foreign` FOREIGN KEY (`addon_opt_id`) REFERENCES `addon_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `addon_option_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `addon_sets`
--
ALTER TABLE `addon_sets`
  ADD CONSTRAINT `addon_sets_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `addon_set_translations`
--
ALTER TABLE `addon_set_translations`
  ADD CONSTRAINT `addon_set_translations_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `addon_set_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `app_styling_options`
--
ALTER TABLE `app_styling_options`
  ADD CONSTRAINT `app_styling_options_app_styling_id_foreign` FOREIGN KEY (`app_styling_id`) REFERENCES `app_stylings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_redirect_category_id_foreign` FOREIGN KEY (`redirect_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `banners_redirect_vendor_id_foreign` FOREIGN KEY (`redirect_vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `brand_categories`
--
ALTER TABLE `brand_categories`
  ADD CONSTRAINT `brand_categories_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `brand_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `brand_translations`
--
ALTER TABLE `brand_translations`
  ADD CONSTRAINT `brand_translations_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `brand_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_addons`
--
ALTER TABLE `cart_addons`
  ADD CONSTRAINT `cart_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_addons_cart_product_id_foreign` FOREIGN KEY (`cart_product_id`) REFERENCES `cart_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_addons_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `addon_options` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_coupons`
--
ALTER TABLE `cart_coupons`
  ADD CONSTRAINT `cart_coupons_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_coupons_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `promocodes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_products`
--
ALTER TABLE `cart_products`
  ADD CONSTRAINT `cart_products_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_products_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_tax_rate_id_foreign` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_product_prescriptions`
--
ALTER TABLE `cart_product_prescriptions`
  ADD CONSTRAINT `cart_product_prescriptions_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_product_prescriptions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_product_prescriptions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `category_histories`
--
ALTER TABLE `category_histories`
  ADD CONSTRAINT `category_histories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_tags`
--
ALTER TABLE `category_tags`
  ADD CONSTRAINT `category_tags_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_translations`
--
ALTER TABLE `category_translations`
  ADD CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `celebrities`
--
ALTER TABLE `celebrities`
  ADD CONSTRAINT `celebrities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `celebrity_brands`
--
ALTER TABLE `celebrity_brands`
  ADD CONSTRAINT `celebrity_brands_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `celebrity_brands_celebrity_id_foreign` FOREIGN KEY (`celebrity_id`) REFERENCES `celebrities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `client_currencies`
--
ALTER TABLE `client_currencies`
  ADD CONSTRAINT `client_currencies_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_currencies_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `client_languages`
--
ALTER TABLE `client_languages`
  ADD CONSTRAINT `client_languages_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_languages_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `client_preferences`
--
ALTER TABLE `client_preferences`
  ADD CONSTRAINT `client_preferences_app_template_id_foreign` FOREIGN KEY (`app_template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_client_code_foreign` FOREIGN KEY (`client_code`) REFERENCES `clients` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_map_provider_foreign` FOREIGN KEY (`map_provider`) REFERENCES `map_providers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_sms_provider_foreign` FOREIGN KEY (`sms_provider`) REFERENCES `sms_providers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `client_preferences_web_template_id_foreign` FOREIGN KEY (`web_template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cms`
--
ALTER TABLE `cms`
  ADD CONSTRAINT `cms_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE;

--
-- Constraints for table `csv_product_imports`
--
ALTER TABLE `csv_product_imports`
  ADD CONSTRAINT `csv_product_imports_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `csv_product_imports_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `csv_vendor_imports`
--
ALTER TABLE `csv_vendor_imports`
  ADD CONSTRAINT `csv_vendor_imports_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_addons`
--
ALTER TABLE `order_product_addons`
  ADD CONSTRAINT `order_product_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_addons_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `addon_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_addons_order_product_id_foreign` FOREIGN KEY (`order_product_id`) REFERENCES `order_vendor_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_prescriptions`
--
ALTER TABLE `order_product_prescriptions`
  ADD CONSTRAINT `order_product_prescriptions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_prescriptions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_prescriptions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_ratings`
--
ALTER TABLE `order_product_ratings`
  ADD CONSTRAINT `order_product_ratings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_ratings_order_vendor_product_id_foreign` FOREIGN KEY (`order_vendor_product_id`) REFERENCES `order_vendor_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product_rating_files`
--
ALTER TABLE `order_product_rating_files`
  ADD CONSTRAINT `order_product_rating_files_order_product_rating_id_foreign` FOREIGN KEY (`order_product_rating_id`) REFERENCES `order_product_ratings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  ADD CONSTRAINT `order_return_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_return_requests_order_vendor_product_id_foreign` FOREIGN KEY (`order_vendor_product_id`) REFERENCES `order_vendor_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_return_requests_return_by_foreign` FOREIGN KEY (`return_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_return_request_files`
--
ALTER TABLE `order_return_request_files`
  ADD CONSTRAINT `order_return_request_files_order_return_request_id_foreign` FOREIGN KEY (`order_return_request_id`) REFERENCES `order_return_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD CONSTRAINT `order_vendors_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_vendors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_vendor_products`
--
ALTER TABLE `order_vendor_products`
  ADD CONSTRAINT `order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_products_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_vendor_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `page_translations`
--
ALTER TABLE `page_translations`
  ADD CONSTRAINT `page_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  ADD CONSTRAINT `page_translations_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_country_origin_id_foreign` FOREIGN KEY (`country_origin_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_addons`
--
ALTER TABLE `product_addons`
  ADD CONSTRAINT `product_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addon_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_addons_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_celebrities`
--
ALTER TABLE `product_celebrities`
  ADD CONSTRAINT `product_celebrities_celebrity_id_foreign` FOREIGN KEY (`celebrity_id`) REFERENCES `celebrities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_celebrities_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_cross_sells`
--
ALTER TABLE `product_cross_sells`
  ADD CONSTRAINT `product_cross_sells_cross_product_id_foreign` FOREIGN KEY (`cross_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_cross_sells_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `vendor_media` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_inquiries`
--
ALTER TABLE `product_inquiries`
  ADD CONSTRAINT `product_inquiries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_inquiries_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_related`
--
ALTER TABLE `product_related`
  ADD CONSTRAINT `product_related_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_related_related_product_id_foreign` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_translations`
--
ALTER TABLE `product_translations`
  ADD CONSTRAINT `product_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_up_sells`
--
ALTER TABLE `product_up_sells`
  ADD CONSTRAINT `product_up_sells_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_up_sells_upsell_product_id_foreign` FOREIGN KEY (`upsell_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_variant_images`
--
ALTER TABLE `product_variant_images`
  ADD CONSTRAINT `product_variant_images_product_image_id_foreign` FOREIGN KEY (`product_image_id`) REFERENCES `product_images` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_images_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_sets`
--
ALTER TABLE `product_variant_sets`
  ADD CONSTRAINT `product_variant_sets_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_sets_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_sets_variant_option_id_foreign` FOREIGN KEY (`variant_option_id`) REFERENCES `variant_options` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_variant_sets_variant_type_id_foreign` FOREIGN KEY (`variant_type_id`) REFERENCES `variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD CONSTRAINT `promocodes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `promocodes_promo_type_id_foreign` FOREIGN KEY (`promo_type_id`) REFERENCES `promo_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `promocode_restrictions`
--
ALTER TABLE `promocode_restrictions`
  ADD CONSTRAINT `promocode_restrictions_promocode_id_foreign` FOREIGN KEY (`promocode_id`) REFERENCES `promocodes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promo_usages`
--
ALTER TABLE `promo_usages`
  ADD CONSTRAINT `promo_usages_promocode_id_foreign` FOREIGN KEY (`promocode_id`) REFERENCES `promocodes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promo_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `refer_and_earns`
--
ALTER TABLE `refer_and_earns`
  ADD CONSTRAINT `refer_and_earns_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD CONSTRAINT `service_areas_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `slot_days`
--
ALTER TABLE `slot_days`
  ADD CONSTRAINT `slot_days_slot_id_foreign` FOREIGN KEY (`slot_id`) REFERENCES `vendor_slots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_plan_features_user`
--
ALTER TABLE `subscription_plan_features_user`
  ADD CONSTRAINT `subscription_plan_features_user_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `subscription_features_list_user` (`id`),
  ADD CONSTRAINT `subscription_plan_features_user_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans_user` (`id`);

--
-- Constraints for table `subscription_plan_features_vendor`
--
ALTER TABLE `subscription_plan_features_vendor`
  ADD CONSTRAINT `subscription_plan_features_vendor_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `subscription_features_list_vendor` (`id`),
  ADD CONSTRAINT `subscription_plan_features_vendor_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans_vendor` (`id`);

--
-- Constraints for table `tax_categories`
--
ALTER TABLE `tax_categories`
  ADD CONSTRAINT `tax_categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tax_rate_categories`
--
ALTER TABLE `tax_rate_categories`
  ADD CONSTRAINT `tax_rate_categories_tax_cate_id_foreign` FOREIGN KEY (`tax_cate_id`) REFERENCES `tax_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tax_rate_categories_tax_rate_id_foreign` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_deposit_id_foreign` FOREIGN KEY (`deposit_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfers_withdraw_id_foreign` FOREIGN KEY (`withdraw_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_timezone_id_foreign` FOREIGN KEY (`timezone_id`) REFERENCES `timezones` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_loyalty_points`
--
ALTER TABLE `user_loyalty_points`
  ADD CONSTRAINT `user_loyalty_points_loyalty_card_id_foreign` FOREIGN KEY (`loyalty_card_id`) REFERENCES `loyalty_cards` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_loyalty_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_refferals`
--
ALTER TABLE `user_refferals`
  ADD CONSTRAINT `user_refferals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_vendors`
--
ALTER TABLE `user_vendors`
  ADD CONSTRAINT `user_vendors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vendors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_wishlists`
--
ALTER TABLE `user_wishlists`
  ADD CONSTRAINT `user_wishlists_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_categories`
--
ALTER TABLE `variant_categories`
  ADD CONSTRAINT `variant_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_categories_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD CONSTRAINT `variant_options_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_option_translations`
--
ALTER TABLE `variant_option_translations`
  ADD CONSTRAINT `variant_option_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_option_translations_variant_option_id_foreign` FOREIGN KEY (`variant_option_id`) REFERENCES `variant_options` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_translations`
--
ALTER TABLE `variant_translations`
  ADD CONSTRAINT `variant_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `client_languages` (`language_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_translations_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_vendor_templete_id_foreign` FOREIGN KEY (`vendor_templete_id`) REFERENCES `vendor_templetes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD CONSTRAINT `vendor_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_media`
--
ALTER TABLE `vendor_media`
  ADD CONSTRAINT `vendor_media_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_order_dispatcher_statuses`
--
ALTER TABLE `vendor_order_dispatcher_statuses`
  ADD CONSTRAINT `dispatcher_statuses_dispatcher_status_option_id_foreign` FOREIGN KEY (`dispatcher_status_option_id`) REFERENCES `dispatcher_status_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispatcher_statuses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_order_dispatcher_statuses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_order_statuses`
--
ALTER TABLE `vendor_order_statuses`
  ADD CONSTRAINT `order_statuses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_statuses_order_status_option_id_foreign` FOREIGN KEY (`order_status_option_id`) REFERENCES `order_status_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_order_statuses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_slots`
--
ALTER TABLE `vendor_slots`
  ADD CONSTRAINT `vendor_slots_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_slots_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendor_slot_dates`
--
ALTER TABLE `vendor_slot_dates`
  ADD CONSTRAINT `vendor_slot_dates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_slot_dates_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_users`
--
ALTER TABLE `vendor_users`
  ADD CONSTRAINT `vendor_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_users_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
