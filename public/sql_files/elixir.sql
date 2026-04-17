-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2021 at 02:24 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19


-- --------------------------------------------------------

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `slug`, `desc`, `logo`, `banner`, `address`, `email`, `website`, `phone_no`, `latitude`, `longitude`, `order_min_amount`, `order_pre_time`, `auto_reject_time`, `commission_percent`, `commission_fixed_per_order`, `commission_monthly`, `dine_in`, `takeaway`, `delivery`, `status`, `add_category`, `setting`, `is_show_vendor_details`, `created_at`, `updated_at`, `show_slot`, `vendor_templete_id`, `auto_accept_order`) VALUES
(1, 'DeliveryZone', NULL, NULL, 'default/default_logo.png', 'default/default_image.png', 'Sheikh Zayed Road - Dubai - United Arab Emirates', NULL, NULL, NULL, '25.060924600000', '55.128979500000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 2, 1, 0, 0, NULL, '2021-09-30 05:22:52', 1, NULL, 0),
(2, 'Caremark', 'caremark', NULL, 'vendor/Q8jQWAuLpIfWBcPsS7amuEYdS564cCmXtHOtcAbI.png', 'vendor/0rQ3NiccEuGz48FIr9rh6mUUNKP8BPkzCtA0YXC5.jpg', 'Chandigarh, India', 'caremark@gmail.com', NULL, '9856589123', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 1, 1, 0, 0, '2021-09-30 05:23:51', '2021-10-08 10:18:24', 1, 1, 0),
(3, 'Wellwise', 'wellwise', NULL, 'vendor/EMo9oR2YCwvEWI7RjICc1rOuZUN1EnkTaP1iJM9b.jpg', 'vendor/oG44lnlAuSJkxK9cx1XVGgYQEdFCakl7dDPhTLmX.jpg', 'Chandigarh, India', 'wellwise@support.com', NULL, '9805621236', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 1, 1, 0, 0, '2021-09-30 05:25:24', '2021-10-08 10:18:07', 1, 1, 0),
(4, 'SpotRx', 'spotrx', NULL, 'vendor/4wOufRbq28B8f4KXnvuqqeGXia95LxFqdaF3ozT0.png', 'vendor/T458dWJVj9ERneEYA3B293hgLcS4W7wTZH9zltNK.jpg', 'Chandigarh, India', 'spotrx@support.com', NULL, '9805252630', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 1, 1, 0, 0, '2021-09-30 05:26:57', '2021-10-08 10:17:54', 1, 1, 0),
(5, 'Medlife', 'medlife', NULL, 'vendor/9drq5s1vMbOAXYYHSiPlLBeS6ALCBOV2PANbsBqf.jpg', 'vendor/ccvsDttUI8f8tvbAvRnHkru1RnSpUz31NfqxQU5N.jpg', 'Chandigarh, India', 'medlife@support.com', NULL, 'Medlife', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 1, 1, 0, 0, '2021-09-30 05:27:36', '2021-10-08 10:17:37', 1, 1, 0),
(6, 'HealthMart', 'healthmart', NULL, 'vendor/BH1Duj8zBohZBGmt1sxZKQ5L1MG6vqaHYpnkPkOO.png', 'vendor/6eoQ3Bj3wFYxoy4vTcFKOqiQujDv8NE8DzW3sMpI.jpg', 'Chandigarh, India', 'healthmart@support.com', NULL, '9857496321', '30.733314800000', '76.779417900000', '0.00', '0', NULL, 1, '0.00', '0.00', 0, 1, 1, 1, 1, 0, 0, '2021-09-30 05:28:35', '2021-10-08 10:17:25', 1, 1, 0);

--
-- Dumping data for table `addon_sets`
--
INSERT INTO `addon_sets` (`id`, `title`, `min_select`, `max_select`, `position`, `status`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, 1, 1, 1, NULL, NULL, NULL);

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `icon`, `slug`, `type_id`, `image`, `is_visible`, `status`, `position`, `is_core`, `can_add_products`, `parent_id`, `vendor_id`, `client_code`, `display_mode`, `warning_page_id`, `template_type_id`, `warning_page_design`, `created_at`, `updated_at`, `deleted_at`, `show_wishlist`) VALUES
(1, NULL, 'Root', 3, NULL, 0, 1, 1, 1, 0, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, NULL, 'Delivery', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:31', '2021-10-01 14:41:31', 1),
(3, NULL, 'Restaurant', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:28', '2021-10-01 14:41:28', 1),
(4, NULL, 'Supermarket', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:35', '2021-10-01 14:41:35', 1),
(5, NULL, 'Pharmacy', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:38', '2021-10-01 14:41:38', 1),
(6, NULL, 'Send something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-01 14:41:31', '2021-10-01 14:41:31', 1),
(7, NULL, 'Buy something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-01 14:41:31', '2021-10-01 14:41:31', 1),
(8, NULL, 'Vegetables', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-01 14:41:35', '2021-10-01 14:41:35', 1),
(9, NULL, 'Fruits', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-01 14:41:35', '2021-10-01 14:41:35', 1),
(10, NULL, 'Dairy and Eggs', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-01 14:41:35', '2021-10-01 14:41:35', 1),
(11, NULL, 'E-Commerce', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:40', '2021-10-01 14:41:40', 1),
(12, NULL, 'Cloth', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:43', '2021-10-01 14:41:43', 1),
(13, NULL, 'Dispatcher', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:46', '2021-10-01 14:41:46', 1),
(14, 'category/icon/hCLxaMYkLCK1OhEiexeMk6Go7mDbeApw70VvmAI0.svg', 'healthdevices', 1, 'category/image/fItgcnhPFdMNpvoSSRKeRtOzD8ER8va0QkrSSJLX.jpg', 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-30 05:40:46', '2021-10-07 12:51:05', NULL, 1),
(15, 'category/icon/asqEcTkFTcA8XwV4if0ZK7slLeY9QXjizvhxjUvf.svg', 'babycare', 1, 'category/image/Lf37xg86du2bb4d3tLssADY1mkeOY8I3zkqIhbBp.jpg', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-30 05:41:19', '2021-10-07 12:51:15', NULL, 1),
(16, 'category/icon/pJE0ctrdAuBnZSKtGFjWLYqSdSxIUZGEXfgg6bFr.svg', 'personalcare', 1, 'category/image/C7EAtBG6t7coDlsw2934zLYEM26HTKIAjq2WJQvn.jpg', 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-30 05:42:05', '2021-10-08 09:26:07', NULL, 1),
(17, 'category/icon/xoFc29gap9RqdkPQCEAfqWPhzldR2KOWMUxexhMM.svg', 'health&nutrition', 1, 'category/image/33P5YDpYZoWngPLQAHoDQYz2RszfDXUSCUc9Ahzt.jpg', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-30 05:42:42', '2021-10-07 12:56:02', NULL, 1),
(18, 'category/icon/BNHn0ujNBMR5vGCFuRNwqdU4J0XEloe8xeRdgyiP.svg', 'ayurveda', 1, 'category/image/0RoiOdoSuuChtXxfcdzeejM4X1rqJA9gTUvvFcPl.jpg', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-30 05:43:48', '2021-10-07 12:56:20', NULL, 1);

--
-- Dumping data for table `addon_options`
--
INSERT INTO `addon_options` (`id`, `title`, `addon_id`, `position`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, '100.00', NULL, NULL);


-- --------------------------------------------------------


--
-- Dumping data for table `addon_option_translations`
--

INSERT INTO `addon_option_translations` (`id`, `title`, `addon_opt_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, NULL, '2021-10-01 09:03:26');


-- --------------------------------------------------------


-- --------------------------------------------------------


--
-- Dumping data for table `addon_set_translations`
--
INSERT INTO `addon_set_translations` (`id`, `title`, `addon_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, NULL, NULL);



-- --------------------------------------------------------


--
-- Dumping data for table `brands`
--
INSERT INTO `brands` (`id`, `title`, `image`, `image_banner`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Roche', 'brand/LOFTlzDI8TgSIkIt5ypykgSXMQQdnMclQhi5QV3h.png', 'brand/da25WRqNFYjF7ztO95dqzrKWHSjwzGJ25VHJqj8a.png', 2, 1, NULL, '2021-10-08 11:08:42'),
(2, 'Pfizer', 'brand/SfcS9750yvhU1AmGRJhNMJBY1sZScff3uGOjm1fZ.jpg', 'brand/qrc9bmzZVmQDrOgegreY4sbDCmhtuHAm9eeURkEW.jpg', 1, 1, NULL, '2021-10-08 11:06:03'),
(3, 'Cipla', 'brand/tmIFl6LgTu4bJW1fq1jf722lN3Wv5nbk1E2ZIkrC.jpg', 'brand/oGhG8y8wI8n1bWwIb6MqITE5AzXPjCC5MVARvOIT.jpg', 3, 1, NULL, '2021-10-08 11:11:00'),
(4, 'AbbVie', 'brand/V6p7RBlT19pnUb34xLR6Qd4zEZloBluhNCK6CTnM.jpg', 'brand/wx0Ga2ZwkB04dH0kCFAEAirnCJTh8ET1ODxLX342.jpg', 4, 1, NULL, '2021-10-08 10:57:58');


-- --------------------------------------------------------
INSERT INTO `variants` (`id`, `title`, `type`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, 2, NULL, '2021-10-08 11:11:27'),
(2, 'Color', 2, 2, 2, NULL, '2021-10-08 11:11:30'),
(3, 'Phones', 1, 3, 2, NULL, '2021-10-08 11:11:33');
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

--
-- Dumping data for table `brand_categories`
--

INSERT INTO `brand_categories` (`id`, `brand_id`, `category_id`, `created_at`, `updated_at`) VALUES
(9, 4, 14, NULL, NULL),
(10, 2, 16, NULL, NULL),
(11, 1, 15, NULL, NULL),
(12, 3, 18, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `category_translations`
--
INSERT INTO `category_translations` (`id`, `name`, `trans-slug`, `meta_title`, `meta_description`, `meta_keywords`, `category_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'root', '', 'root', '', '', 1, 1, NULL, '2021-10-01 09:03:26'),
(2, 'Delivery', '', 'Delivery', NULL, NULL, 2, 1, NULL, '2021-10-01 09:03:26'),
(3, 'Restaurant', '', 'Restaurant', NULL, NULL, 3, 1, NULL, '2021-10-01 09:03:26'),
(4, 'Supermarket', '', 'Supermarket', NULL, NULL, 4, 1, NULL, '2021-10-01 09:03:26'),
(5, 'Pharmacy', '', 'Pharmacy', NULL, NULL, 5, 1, NULL, '2021-10-01 09:03:26'),
(6, 'Send something', '', 'Send something', '', '', 6, 1, NULL, '2021-10-01 09:03:26'),
(7, 'Buy something', '', 'Buy something', '', '', 7, 1, NULL, '2021-10-01 09:03:26'),
(8, 'Vegetables', '', 'Vegetables', '', '', 8, 1, NULL, '2021-10-01 09:03:26'),
(9, 'Fruits', '', 'Fruits', '', '', 9, 1, NULL, '2021-10-01 09:03:26'),
(10, 'Dairy and Eggs', '', 'Dairy and Eggs', '', '', 10, 1, NULL, '2021-10-01 09:03:26'),
(11, 'E-Commerce', '', 'E-Commerce', NULL, NULL, 11, 1, NULL, '2021-10-01 09:03:26'),
(12, 'Cloth', '', 'Cloth', NULL, NULL, 12, 1, NULL, '2021-10-01 09:03:26'),
(13, 'Dispatcher', '', 'Dispatcher', NULL, NULL, 13, 1, NULL, '2021-10-01 09:03:26'),
(14, 'Health Devices', NULL, 'Health Devices', NULL, NULL, 14, 1, '2021-09-30 05:40:46', '2021-10-01 09:03:26'),
(15, 'Baby Care', NULL, 'Baby Care', NULL, NULL, 15, 1, '2021-09-30 05:41:19', '2021-10-01 09:03:26'),
(16, 'Personal Care', NULL, 'Personal Care', NULL, NULL, 16, 1, '2021-09-30 05:42:05', '2021-10-01 09:03:26'),
(17, 'Health & Nutrition', NULL, 'Health & Nutrition', NULL, NULL, 17, 1, '2021-09-30 05:42:42', '2021-10-01 09:03:26'),
(18, 'Ayurveda', NULL, 'Ayurveda', NULL, NULL, 18, 1, '2021-09-30 05:43:48', '2021-10-01 09:03:26');

-- --------------------------------------------------------

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `title`, `url_slug`, `description`, `body_html`, `vendor_id`, `category_id`, `type_id`, `country_origin_id`, `is_new`, `is_featured`, `is_live`, `is_physical`, `weight`, `weight_unit`, `has_inventory`, `has_variant`, `sell_when_out_of_stock`, `requires_shipping`, `Requires_last_mile`, `averageRating`, `inquiry_only`, `publish_at`, `created_at`, `updated_at`, `brand_id`, `tax_category_id`, `deleted_at`, `pharmacy_check`, `tags`, `need_price_from_dispatcher`, `mode_of_service`) VALUES
(1, 'sku-id', '1', 'sku-id', NULL, NULL, 1, NULL, 1, NULL, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(2, 'Pulse Oximeters', 'Pulse Oximeters', 'Pulse Oximeters', NULL, '', 6, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:11:09', NULL, '2021-10-08 10:58:14', 4, NULL, NULL, 1, NULL, '0', NULL),
(3, 'Thermometers', 'Thermometers', 'Thermometers', NULL, '', 6, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:13:17', NULL, '2021-10-08 10:58:24', 4, NULL, NULL, 1, NULL, '0', NULL),
(4, 'Blood Pressure Monitors', 'Blood Pressure Monitors', 'Blood Pressure Monitors', NULL, '', 6, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:17:47', NULL, '2021-10-08 10:58:36', 4, NULL, NULL, 1, NULL, '0', NULL),
(5, 'Blood Glucose Monitors', 'Blood Glucose Monitors', 'Blood Glucose Monitors', NULL, '', 6, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:19:59', NULL, '2021-10-08 10:58:53', 4, NULL, NULL, 1, NULL, '0', NULL),
(6, 'Baby Body Lotion', 'Baby Body Lotion', 'Baby Body Lotion', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:27:26', NULL, '2021-10-08 11:00:29', 1, NULL, NULL, 1, NULL, '0', NULL),
(7, 'Baby Body Oil', 'Baby Body Oil', 'Baby Body Oil', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:30:39', NULL, '2021-10-08 11:00:39', 1, NULL, NULL, 1, NULL, '0', NULL),
(8, 'Moisturizer', 'Moisturizing', 'Moisturizer', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:32:41', NULL, '2021-10-08 11:00:49', 1, NULL, NULL, 1, NULL, '0', NULL),
(9, 'Baby Soap', 'Baby Soap', 'Baby Soap', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:36:30', NULL, '2021-10-08 11:01:12', 1, NULL, NULL, 1, NULL, '0', NULL),
(10, 'Sunscreens', 'Sunscreens', 'Sunscreens', NULL, '', 4, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:46:42', NULL, '2021-10-08 11:06:18', 2, NULL, NULL, 1, NULL, '0', NULL),
(11, 'Hair dyes', 'Hair dyes', 'Hair dyes', NULL, '', 4, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:47:39', NULL, '2021-10-08 11:06:28', 2, NULL, NULL, 1, NULL, '0', NULL),
(12, 'Skin cleansers', 'Skin cleansers', 'Skin cleansers', NULL, '', 4, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:48:26', NULL, '2021-10-08 11:06:39', 2, NULL, NULL, 1, NULL, '0', NULL),
(13, 'Deodorants', 'Deodorants', 'Deodorants', NULL, '', 4, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:49:32', NULL, '2021-10-08 11:07:03', 2, NULL, NULL, 1, NULL, '0', NULL),
(14, 'Coconut Water & Coconut Oil', 'Coconut Water & Coconut Oil', 'Coconut Water & Coconut Oil', NULL, '', 3, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:54:28', NULL, '2021-10-04 14:21:54', NULL, NULL, NULL, 1, NULL, '0', NULL),
(15, 'Cannabidiol', 'Cannabidiol', 'Cannabidiol', NULL, '', 3, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:55:20', NULL, '2021-10-04 14:21:59', NULL, NULL, NULL, 1, NULL, '0', NULL),
(16, 'Activated Charcoal', 'Activated Charcoal', 'Activated Charcoal', NULL, '', 3, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:56:48', NULL, '2021-10-04 14:22:06', NULL, NULL, NULL, 1, NULL, '0', NULL),
(17, 'Probiotics', 'Probiotics', 'Probiotics', NULL, '', 3, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 06:58:26', NULL, '2021-10-04 14:22:10', NULL, NULL, NULL, 1, NULL, '0', NULL),
(18, 'Ashwagandha', 'Ashwagandha', 'Ashwagandha', NULL, '', 2, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 07:04:04', NULL, '2021-10-08 11:03:11', 3, NULL, NULL, 1, NULL, '0', NULL),
(19, 'Aloe Vera', 'Shilajit', 'Aloe Vera', NULL, '', 2, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 07:07:01', NULL, '2021-10-08 11:03:26', 3, NULL, NULL, 1, NULL, '0', NULL),
(20, 'Giloy', 'Giloy', 'Giloy', NULL, '', 2, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 07:08:37', NULL, '2021-10-08 11:03:43', 3, NULL, NULL, 1, NULL, '0', NULL),
(21, 'Tulsi', 'Tulsi', 'Tulsi', NULL, '', 2, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 07:11:10', NULL, '2021-10-08 11:03:54', 3, NULL, NULL, 1, NULL, '0', NULL),
(22, 'JH1231633688756', 'Bound salads', 'JH1231633688756', NULL, '', 6, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, '2021-10-08 10:25:56', NULL, NULL, '2021-10-08 10:25:56', 0, NULL, NULL, NULL),
(23, 'JH1241633688752', 'Fruit salads', 'JH1241633688752', NULL, '', 6, 14, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, '2021-10-08 10:25:52', NULL, NULL, '2021-10-08 10:25:52', 0, NULL, NULL, NULL),
(24, 'NG125', 'Stethoscope', 'NG125', NULL, '', 6, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:33:31', NULL, '2021-10-08 10:59:08', 4, NULL, NULL, 1, NULL, '0', NULL),
(25, 'NG126', 'Blood Pressure Monitor', 'NG126', NULL, '', 6, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:35:53', NULL, '2021-10-08 11:00:05', 4, NULL, NULL, 1, NULL, '0', NULL),
(26, 'DF458', 'Baby Powder', 'DF458', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:38:13', NULL, '2021-10-08 11:01:29', 1, NULL, NULL, 1, NULL, '0', NULL),
(27, 'DF459', 'Baby Food', 'DF459', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:40:40', NULL, '2021-10-08 11:01:40', 1, NULL, NULL, 1, NULL, '0', NULL),
(28, 'ABC987', 'Lotion', 'ABC987', NULL, '', 4, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:43:43', NULL, '2021-10-08 11:07:21', 2, NULL, NULL, 1, NULL, '0', NULL),
(29, 'ABC988', 'Colognes', 'ABC988', NULL, '', 4, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:47:17', NULL, '2021-10-08 11:07:33', 2, NULL, NULL, 1, NULL, '0', NULL),
(30, 'DF147', 'Herbal Supplement', 'DF147', NULL, '', 3, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:52:05', NULL, '2021-10-08 10:52:05', NULL, NULL, NULL, 1, NULL, '0', NULL),
(31, 'DF148', 'Probiotic Coconut Yogurt', 'DF148', NULL, '', 3, 17, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:53:27', NULL, '2021-10-08 10:53:27', NULL, NULL, NULL, 1, NULL, '0', NULL),
(32, 'HR985', 'Rose water', 'HR985', NULL, '', 2, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 10:57:31', NULL, '2021-10-08 11:04:16', 3, NULL, NULL, 1, NULL, '0', NULL),
(33, 'HR986', 'Chyawanprash', 'HR986', NULL, '', 2, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 11:00:26', NULL, '2021-10-08 11:04:28', 3, NULL, NULL, 1, NULL, '0', NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, NULL),
(2, 14, NULL, NULL),
(2, 14, NULL, NULL),
(3, 14, NULL, NULL),
(2, 14, NULL, NULL),
(3, 14, NULL, NULL),
(4, 14, NULL, NULL),
(2, 14, NULL, NULL),
(3, 14, NULL, NULL),
(4, 14, NULL, NULL),
(5, 14, NULL, NULL),
(6, 15, NULL, NULL),
(6, 15, NULL, NULL),
(7, 15, NULL, NULL),
(6, 15, NULL, NULL),
(7, 15, NULL, NULL),
(8, 15, NULL, NULL),
(6, 15, NULL, NULL),
(7, 15, NULL, NULL),
(8, 15, NULL, NULL),
(9, 15, NULL, NULL),
(10, 16, NULL, NULL),
(10, 16, NULL, NULL),
(11, 16, NULL, NULL),
(10, 16, NULL, NULL),
(11, 16, NULL, NULL),
(12, 16, NULL, NULL),
(10, 16, NULL, NULL),
(11, 16, NULL, NULL),
(12, 16, NULL, NULL),
(13, 16, NULL, NULL),
(14, 17, NULL, NULL),
(14, 17, NULL, NULL),
(15, 17, NULL, NULL),
(14, 17, NULL, NULL),
(15, 17, NULL, NULL),
(16, 17, NULL, NULL),
(14, 17, NULL, NULL),
(15, 17, NULL, NULL),
(16, 17, NULL, NULL),
(17, 17, NULL, NULL),
(18, 18, NULL, NULL),
(18, 18, NULL, NULL),
(19, 18, NULL, NULL),
(18, 18, NULL, NULL),
(19, 18, NULL, NULL),
(20, 18, NULL, NULL),
(18, 18, NULL, NULL),
(19, 18, NULL, NULL),
(20, 18, NULL, NULL),
(21, 18, NULL, NULL),
(22, 14, NULL, NULL),
(22, 14, NULL, NULL),
(23, 14, NULL, NULL),
(24, 14, NULL, NULL),
(24, 14, NULL, NULL),
(25, 14, NULL, NULL),
(26, 15, NULL, NULL),
(26, 15, NULL, NULL),
(27, 15, NULL, NULL),
(28, 16, NULL, NULL),
(28, 16, NULL, NULL),
(29, 16, NULL, NULL),
(30, 17, NULL, NULL),
(30, 17, NULL, NULL),
(31, 17, NULL, NULL),
(32, 18, NULL, NULL),
(32, 18, NULL, NULL),
(33, 18, NULL, NULL);


-- --------------------------------------------------------

--
-- Dumping data for table `vendor_media`
--

INSERT INTO `vendor_media` (`id`, `media_type`, `vendor_id`, `path`, `created_at`, `updated_at`) VALUES
(3, 1, 6, 'prods/WlNvGG3Q9To4jvWjlcwQ6ZFZGgpiNEF8C0kMqRkX.jpg', '2021-09-30 06:10:49', '2021-09-30 06:10:49'),
(7, 1, 6, 'prods/Ag7wKqvbtOotXYl7Qlbsef4TbHdgwB5iGfPFaehb.jpg', '2021-09-30 06:19:42', '2021-09-30 06:19:42'),
(11, 1, 5, 'prods/tW50P4VenGmnszxivAecrKSDBTrErXuUdrWcIXUH.jpg', '2021-09-30 06:32:08', '2021-09-30 06:32:08'),
(33, 1, 2, 'prods/i2R3CiZjTxxueYNa0BDh5MtfUPVBFIHFuwbtyQQP.jpg', '2021-09-30 13:38:43', '2021-09-30 13:38:43'),
(36, 1, 2, 'prods/BzaqHDajwqYoEpa6kScJThYRfyFR5fgXwXJYRrgO.jpg', '2021-10-01 11:51:16', '2021-10-01 11:51:16'),
(37, 1, 2, 'prods/V5q8YVAZ6N4oLZb9bZuXv4JFq0rvqANBnw5B3UAv.jpg', '2021-10-01 11:51:56', '2021-10-01 11:51:56'),
(38, 1, 2, 'prods/sbtzWgYmJprbhwGFMNkKOMqVXjwebZndX7XVQO7J.jpg', '2021-10-01 11:52:33', '2021-10-01 11:52:33'),
(39, 1, 3, 'prods/zUlag8Qq9MeS3wuhPFvPp3Arqjd5vC62QKe10z12.jpg', '2021-10-01 11:53:57', '2021-10-01 11:53:57'),
(40, 1, 3, 'prods/a8TyfBP8difDGFudx9EXSj0bsjP8IwCaZ07DY7G2.jpg', '2021-10-01 11:55:02', '2021-10-01 11:55:02'),
(43, 1, 4, 'prods/8IrjgUZ2RsJU22YbSNYyBO6wqvtPTMRmqgQl4qC5.jpg', '2021-10-01 12:02:40', '2021-10-01 12:02:40'),
(46, 1, 4, 'prods/aTxXbRZ2XUyIkeD0lXRtdL00nwlm9buYZmOWVuqw.jpg', '2021-10-01 12:07:21', '2021-10-01 12:07:21'),
(47, 1, 5, 'prods/3WTK0opFcns7rsJwREqLFccpTNinV9m0pizeCOoU.jpg', '2021-10-01 12:09:51', '2021-10-01 12:09:51'),
(49, 1, 5, 'prods/9DrhlyL30uCBi2c1VRVpRLQnCHLEjqNGO8xBYj6Y.jpg', '2021-10-01 12:11:55', '2021-10-01 12:11:55'),
(50, 1, 5, 'prods/o7kIYuKlpG4jk6KKkhLgjN4WvKVIeXrgsuBCgBdy.jpg', '2021-10-01 12:14:04', '2021-10-01 12:14:04'),
(52, 1, 6, 'prods/mgwYV821HCY0rceWpIUGb7BrJkTrbAjH1NIEtG6I.jpg', '2021-10-01 12:22:12', '2021-10-01 12:22:12'),
(53, 1, 6, 'prods/SUVmLUpTiEIsdiUdGF8mDUuQsMkfUnpHvUNq6EDb.jpg', '2021-10-01 12:23:15', '2021-10-01 12:23:15'),
(54, 1, 6, 'prods/C9tG68mnWijKucChPaAbigPm2tBZNQxAqJhlFwyl.jpg', '2021-10-01 12:24:42', '2021-10-01 12:24:42'),
(55, 1, 6, 'prods/Xw7imaZ2pxF5dtmnVuxw6dImY37LecLRvfJndWm8.jpg', '2021-10-01 12:26:38', '2021-10-01 12:26:38'),
(56, 1, 5, 'prods/6aXUlJ26xuQ46W59sJE9WT2m3nzHrv9sejcZr96C.jpg', '2021-10-01 12:28:53', '2021-10-01 12:28:53'),
(57, 1, 5, 'prods/22kmImGB112ZccVpqCxGRztVFXl7d6BJHlSdOtL1.jpg', '2021-10-01 12:30:25', '2021-10-01 12:30:25'),
(59, 1, 3, 'prods/j7k8HqFfqUs7TQyC3lb6YVmK3gP9tOcVQgz85Awu.jpg', '2021-10-04 14:52:15', '2021-10-04 14:52:15'),
(60, 1, 3, 'prods/WXbWNwcLe3j0YMQiSfhV0RBxlUBZvcvAbDbGaXoS.jpg', '2021-10-04 14:55:16', '2021-10-04 14:55:16'),
(61, 1, 2, 'prods/rSK99AGTYhMPUT8PBovfElssQvMe0PRvLM7njIpK.jpg', '2021-10-04 15:00:02', '2021-10-04 15:00:02'),
(62, 1, 2, 'prods/um1gB1mUrzn5yxFbcMBUn7VOiTOECN1lyhNcMB73.jpg', '2021-10-04 15:00:33', '2021-10-04 15:00:33'),
(63, 1, 4, 'prods/LvXPHz5m9I2xXYvNsT8CoL9iDNFFqSuTNlcAPWoP.jpg', '2021-10-04 15:01:58', '2021-10-04 15:01:58'),
(64, 1, 4, 'prods/38I0S3OJiCfP3GMIIE6Jyu5j20jxrMk1k8YengJx.jpg', '2021-10-04 15:02:05', '2021-10-04 15:02:05'),
(65, 1, 6, 'prods/qeohGHwVavWLgzDdMuCHUMqha0BYPYJiiqPzuPbT.jpg', '2021-10-08 10:33:08', '2021-10-08 10:33:08'),
(66, 1, 6, 'prods/LZm90dBGVDUlurSoGNgyC2FbC1DafWKzqUhxnwit.jpg', '2021-10-08 10:35:48', '2021-10-08 10:35:48'),
(70, 1, 4, 'prods/eh7KG8KqJRuKJ6dODcMxMFgZscqi8HpaplSd52dB.jpg', '2021-10-08 10:46:50', '2021-10-08 10:46:50'),
(71, 1, 3, 'prods/Qxsaf6wA75op068hck2BA1rzYS3v5LmNVHzReOxl.jpg', '2021-10-08 10:51:04', '2021-10-08 10:51:04'),
(72, 1, 3, 'prods/XPmZk460vxipNtyXTgzbTQVIDnTDGvqrkAUPxgu7.jpg', '2021-10-08 10:55:14', '2021-10-08 10:55:14'),
(73, 1, 2, 'prods/dTptz8i2d3HdeQN0IOngzBNmfmUoOOsg3gf5ogYS.jpg', '2021-10-08 10:56:44', '2021-10-08 10:56:44'),
(75, 1, 2, 'prods/WG1qPq066XxSYDfRLMw4kEJvp2y1oDCKocSF2XEu.png', '2021-10-08 11:17:00', '2021-10-08 11:17:00'),
(76, 1, 5, 'prods/i8TnRzxmU3n8esLaaAjJcrPRXCOKHdy8hgBh7F0v.jpg', '2021-10-08 11:17:09', '2021-10-08 11:17:09'),
(78, 1, 5, 'prods/oUwMGNNs7O6S2biRG0frJuDsYiPLszWXRX4JkHT1.jpg', '2021-10-08 11:22:32', '2021-10-08 11:22:32'),
(79, 1, 5, 'prods/4CTEwxRfdIvWVZ2J6kDGOPJsNOAMUj6o1WMQ8OwF.jpg', '2021-10-08 11:22:38', '2021-10-08 11:22:38'),
(80, 1, 4, 'prods/PhK90iDxch6YYc3AqzecA7TpEzzQKC04cScm2Jlg.jpg', '2021-10-11 05:19:28', '2021-10-11 05:19:28'),
(81, 1, 4, 'prods/8X9SFBBEjZ0YQG64YmAiEeYxBA0apfWLGRAIrCcU.jpg', '2021-10-11 05:23:18', '2021-10-11 05:23:18'),
(83, 1, 4, 'prods/YeX7MpKD4rta10ajcvqU09NNJgIgLbvd7e26XIvg.jpg', '2021-10-11 05:24:42', '2021-10-11 05:24:42');

-- --------------------------------------------------------
--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `media_id`, `is_default`, `created_at`, `updated_at`) VALUES
(32, 19, 36, 1, NULL, NULL),
(33, 20, 37, 1, NULL, NULL),
(34, 21, 38, 1, NULL, NULL),
(35, 14, 39, 1, NULL, NULL),
(36, 15, 40, 1, NULL, NULL),
(39, 10, 43, 1, NULL, NULL),
(42, 13, 46, 1, NULL, NULL),
(43, 6, 47, 1, NULL, NULL),
(45, 8, 49, 1, NULL, NULL),
(47, 2, 52, 1, NULL, NULL),
(48, 3, 53, 1, NULL, NULL),
(49, 4, 54, 1, NULL, NULL),
(50, 5, 55, 1, NULL, NULL),
(51, 9, 56, 1, NULL, NULL),
(52, 7, 57, 1, NULL, NULL),
(54, 17, 59, 1, NULL, NULL),
(55, 16, 60, 1, NULL, NULL),
(56, 18, 62, 1, NULL, NULL),
(57, 12, 64, 1, NULL, NULL),
(58, 24, 65, 1, NULL, NULL),
(59, 25, 66, 1, NULL, NULL),
(63, 29, 70, 1, NULL, NULL),
(64, 30, 71, 1, NULL, NULL),
(65, 31, 72, 1, NULL, NULL),
(66, 32, 73, 1, NULL, NULL),
(68, 33, 75, 1, NULL, NULL),
(69, 26, 76, 1, NULL, NULL),
(71, 27, 79, 1, NULL, NULL),
(72, 28, 80, 1, NULL, NULL),
(74, 11, 83, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `product_translations`
--

INSERT INTO `product_translations` (`id`, `title`, `body_html`, `meta_title`, `meta_keyword`, `meta_description`, `product_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Xiaomi', NULL, 'Xiaomi', 'Xiaomi', NULL, 1, 1, NULL, '2021-10-01 09:03:26'),
(2, 'Pulse Oximeters', '<p>Pulse oximetry is a noninvasive method for monitoring a person&#39;s oxygen saturation. Peripheral oxygen saturation readings are typically within 2% accuracy of the more desirable reading of arterial oxygen saturation from arterial blood gas analysis.</p>', NULL, NULL, NULL, 2, 1, NULL, '2021-10-06 09:53:51'),
(3, 'Pulse Oximeters', '', '', '', '', 2, 1, NULL, '2021-10-01 09:03:26'),
(4, 'Thermometers', '<p>Thermometer is a device that measures temperature or a temperature gradient.&nbsp;</p>', NULL, NULL, NULL, 3, 1, NULL, '2021-10-06 09:54:26'),
(5, 'Pulse Oximeters', '', '', '', '', 2, 1, NULL, '2021-10-01 09:03:26'),
(6, 'Thermometers', '', '', '', '', 3, 1, NULL, '2021-10-01 09:03:26'),
(7, 'Blood Pressure Monitors', '<p>Blood pressure monitor is a device used to measure blood pressure, composed of an inflatable cuff to collapse and then release the artery under the cuff in a controlled manner, and a mercury or aneroid manometer to measure the pressure.</p>', NULL, NULL, NULL, 4, 1, NULL, '2021-10-06 09:56:33'),
(8, 'Pulse Oximeters', '', '', '', '', 2, 1, NULL, '2021-10-01 09:03:26'),
(9, 'Thermometers', '', '', '', '', 3, 1, NULL, '2021-10-01 09:03:26'),
(10, 'Blood Pressure Monitors', '', '', '', '', 4, 1, NULL, '2021-10-01 09:03:26'),
(11, 'Blood Glucose Monitors', '<p>Glucose meter is a medical device for determining the approximate concentration of glucose in the blood. It can also be a strip of glucose paper dipped into a substance and measured to the glucose chart.</p>', NULL, NULL, NULL, 5, 1, NULL, '2021-10-06 09:57:17'),
(12, 'Baby Body Lotion', '<p>Baby lotion is tailored to keep your little one&#39;s skin smooth, moisturized, and, more importantly, rash-free. It&nbsp;soothes&nbsp;your child&#39;s skin and helps in minimising skin irritations, diaper rashes, and dryness.&nbsp;</p>', NULL, NULL, NULL, 6, 1, NULL, '2021-10-06 09:58:59'),
(13, 'Baby Body Lotion', '', '', '', '', 6, 1, NULL, '2021-10-01 09:03:26'),
(14, 'Baby Body Oil', '<p>Body Oil is a&nbsp;caring oil designed to nourish and protect your baby&#39;s precious skin. Uniquely formulated using golden Swedish Oat Oil and Seed Oil Complex to help replenish and strengthen the skin barrier, leaving skin soft and soothed. Suitable for baby and mom.</p>', NULL, NULL, NULL, 7, 1, NULL, '2021-10-08 11:44:15'),
(15, 'Baby Body Lotion', '', '', '', '', 6, 1, NULL, '2021-10-01 09:03:26'),
(16, 'Baby Body Oil', '', '', '', '', 7, 1, NULL, '2021-10-01 09:03:26'),
(17, 'Moisturizer', '<p>Moisturizer, or emollient, is a cosmetic preparation used for protecting, moisturizing, and lubricating the skin.&nbsp;</p>', NULL, NULL, NULL, 8, 1, NULL, '2021-10-06 09:59:41'),
(18, 'Baby Body Lotion', '', '', '', '', 6, 1, NULL, '2021-10-01 09:03:26'),
(19, 'Baby Body Oil', '', '', '', '', 7, 1, NULL, '2021-10-01 09:03:26'),
(20, 'Moisturizing', '', '', '', '', 8, 1, NULL, '2021-10-01 09:03:26'),
(21, 'Baby Soap', '<p>Baby Soap Moisturize a baby&#39;s skin after washing&nbsp;them with plain water or a mild baby wash is an effective way of restoring moisture to the skin and trapping it there.</p>', NULL, NULL, NULL, 9, 1, NULL, '2021-10-06 10:01:07'),
(22, 'Sunscreens', '<p>Sunscreen, also known as sunblock or suntan lotion, is a photoprotective topical product for the skin that absorbs or reflects some of the sun&#39;s ultraviolet radiation and thus helps protect against sunburn and most importantly prevent skin cancer.</p>', NULL, NULL, NULL, 10, 1, NULL, '2021-10-06 10:01:54'),
(23, 'Sunscreens', '', '', '', '', 10, 1, NULL, '2021-10-01 09:03:26'),
(24, 'Hair dyes', '<p>Hair dye&nbsp;cosmetic products are used for colouring hair.&nbsp;</p>', NULL, NULL, NULL, 11, 1, NULL, '2021-10-06 10:03:24'),
(25, 'Sunscreens', '', '', '', '', 10, 1, NULL, '2021-10-01 09:03:26'),
(26, 'Hair dyes', '', '', '', '', 11, 1, NULL, '2021-10-01 09:03:26'),
(27, 'Skin cleansers', '<p>Skin cleanser is a skincare product&nbsp;used to remove make-up, dead skin cells, oil, dirt, and other types of pollutants from the skin.</p>', NULL, NULL, NULL, 12, 1, NULL, '2021-10-08 11:06:46'),
(28, 'Sunscreens', '', '', '', '', 10, 1, NULL, '2021-10-01 09:03:26'),
(29, 'Hair dyes', '', '', '', '', 11, 1, NULL, '2021-10-01 09:03:26'),
(30, 'Skin cleansers', '', '', '', '', 12, 1, NULL, '2021-10-01 09:03:26'),
(31, 'Deodorants', '<p>Deodorant is a substance applied to the body to prevent or mask body odor due to bacterial breakdown of perspiration in the armpits, groin, and feet, and in some cases vaginal secretions.</p>', NULL, NULL, NULL, 13, 1, NULL, '2021-10-06 10:04:52'),
(32, 'Coconut Water & Coconut Oil', '<p>Coconut Water &amp; Coconut Oil Helps Stop Heart Disease and High Blood Pressure.</p>', NULL, NULL, NULL, 14, 1, NULL, '2021-10-06 10:06:34'),
(33, 'Coconut Water & Coconut Oil', '', '', '', '', 14, 1, NULL, '2021-10-01 09:03:26'),
(34, 'Cannabidiol', '<p>Cannabidiol is a specific form of CBD is approved as a drug for seizure.</p>', NULL, NULL, NULL, 15, 1, NULL, '2021-10-06 10:08:13'),
(35, 'Coconut Water & Coconut Oil', '', '', '', '', 14, 1, NULL, '2021-10-01 09:03:26'),
(36, 'Cannabidiol', '', '', '', '', 15, 1, NULL, '2021-10-01 09:03:26'),
(37, 'Activated Charcoal', '<p>Activated charcoal is&nbsp;used in the emergency treatment of certain kinds of poisoning. It helps prevent the poison from being absorbed from the stomach into the body.&nbsp;</p>', NULL, NULL, NULL, 16, 1, NULL, '2021-10-06 10:09:15'),
(38, 'Coconut Water & Coconut Oil', '', '', '', '', 14, 1, NULL, '2021-10-01 09:03:26'),
(39, 'Cannabidiol', '', '', '', '', 15, 1, NULL, '2021-10-01 09:03:26'),
(40, 'Activated Charcoal', '', '', '', '', 16, 1, NULL, '2021-10-01 09:03:26'),
(41, 'Probiotics', '<p>Probiotics are live microorganisms promoted with claims that they provide health benefits when consumed, generally by improving or restoring the gut flora.&nbsp;</p>', NULL, NULL, NULL, 17, 1, NULL, '2021-10-06 10:10:02'),
(42, 'Ashwagandha', '<p>Ashwagandha is an evergreen shrub that grows in Asia and Africa. It is commonly used for stress.</p>', NULL, NULL, NULL, 18, 1, NULL, '2021-10-06 10:10:58'),
(43, 'Ashwagandha', '', '', '', '', 18, 1, NULL, '2021-10-01 09:03:26'),
(44, 'Aloe Vera', '<p>Aloe vera is gel from the leaves of aloe plants. People have used it for thousands of years for&nbsp;healing and softening the skin. Aloe has also long been a folk treatment for many maladies, including constipation and skin disorders.</p>', NULL, NULL, NULL, 19, 1, NULL, '2021-10-08 11:03:27'),
(45, 'Ashwagandha', '', '', '', '', 18, 1, NULL, '2021-10-01 09:03:26'),
(46, 'Shilajit', '', '', '', '', 19, 1, NULL, '2021-10-01 09:03:26'),
(47, 'Giloy', '<p>Giloy, also known as Amrita or Guduchi in Hindi, is&nbsp;an herb that helps improve digestion and boost immunity.&nbsp;</p>', NULL, NULL, NULL, 20, 1, NULL, '2021-10-06 10:12:45'),
(48, 'Ashwagandha', '', '', '', '', 18, 1, NULL, '2021-10-01 09:03:26'),
(49, 'Shilajit', '', '', '', '', 19, 1, NULL, '2021-10-01 09:03:26'),
(50, 'Giloy', '', '', '', '', 20, 1, NULL, '2021-10-01 09:03:26'),
(51, 'Tulsi', '<p>Tulsi leaves are used to treat skin problems like acne, blackheads and premature ageing. Tulsi is used&nbsp;to treat insect bites. Tulsi is also used to treat heart disease and fever.</p>', NULL, NULL, NULL, 21, 1, NULL, '2021-10-06 10:13:45'),
(52, 'Bound salads', '', '', '', '', 22, 1, NULL, NULL),
(53, 'Bound salads', '', '', '', '', 22, 1, NULL, NULL),
(54, 'Fruit salads', '', '', '', '', 23, 1, NULL, NULL),
(55, 'Stethoscope', '<p>Stethoscope&nbsp;is used to transmit low-volume sounds such as a heartbeat&nbsp;to the ear of the listener. A stethoscope may consist of two ear pieces connected by means of flexible tubing to a diaphragm that is placed against the skin of the patient.</p>', NULL, NULL, NULL, 24, 1, NULL, '2021-10-08 10:33:31'),
(56, 'Stethoscope', '', '', '', '', 24, 1, NULL, NULL),
(57, 'Blood Pressure Monitor', '<p>Blood Pressure&nbsp;is measured using a sphygmomanometer, or&nbsp;blood pressure&nbsp;monitor. It consists of an inflatable cuff that&#39;s wrapped around your arm, roughly level.</p>', NULL, NULL, NULL, 25, 1, NULL, '2021-10-08 10:45:57'),
(58, 'Baby Powder', '<p>Baby powder is an&nbsp;astringent powder used for preventing diaper rash and for cosmetic uses. It may be composed of talc&nbsp; or corn starch. Baby powder can also be used as a dry shampoo, cleaning agent&nbsp; and freshener.</p>', NULL, NULL, NULL, 26, 1, NULL, '2021-10-08 10:38:13'),
(59, 'Baby Powder', '', '', '', '', 26, 1, NULL, NULL),
(60, 'Baby Food', '<p>Baby food is&nbsp;any soft, easily consumed food other than breastmilk&nbsp;or infant formula that is made specifically for human babies between four and six months and two years old.</p>', NULL, NULL, NULL, 27, 1, NULL, '2021-10-08 10:40:40'),
(61, 'Lotion', '<p>Lotion moisturize newborn skin. Use sparingly on tiny newborns. Petroleum jelly Can be used to treat diaper rash. It provides baby&#39;s skin with a protective barrier against moist diapers.</p>', NULL, NULL, NULL, 28, 1, NULL, '2021-10-08 10:43:44'),
(62, 'Lotion', '', '', '', '', 28, 1, NULL, NULL),
(63, 'Colognes', '<p>&nbsp;</p>\r\n\r\n<p>Cologne was used&nbsp;to mask the smell of body odor, mud and feces. Cologne was especially useful for densely populated areas, such as large cities, where people had to live in close proximity constantly. Baths were rarely taken before the 1700s, and cologne helped postpone the need for a bath even longer.</p>', NULL, NULL, NULL, 29, 1, NULL, '2021-10-08 10:49:11'),
(64, 'Herbal Supplement', '<p>Herbal Supplement is a plant or plant part used for its scent, flavor, or therapeutic properties.</p>', NULL, NULL, NULL, 30, 1, NULL, '2021-10-08 11:04:38'),
(65, 'Herbal Supplement', '', '', '', '', 30, 1, NULL, NULL),
(66, 'Probiotic Coconut Yogurt', '<p>Probiotic Coconut Yogurt&nbsp; derives from coconut fruit, which is per se a functional food due to its reported benefits on health 10 like being a source of minerals,&nbsp;vitamins 11, dietary fiber 12&nbsp;and providing protection from certain viruses and bacteria 11.</p>', NULL, NULL, NULL, 31, 1, NULL, '2021-10-08 10:53:54'),
(67, 'Rose water', '<p>Rose water is&nbsp;created by distilling rose petals with steam. Rose water is fragrant, and it&#39;s sometimes used as a mild natural fragrance as an alternative to chemical-filled perfumes. Rose water has been used for thousands of years, including in the Middle Ages. It&#39;s thought to have originated in what is now Iran.</p>', NULL, NULL, NULL, 32, 1, NULL, '2021-10-08 10:57:31'),
(68, 'Rose water', '', '', '', '', 32, 1, NULL, NULL),
(69, 'Chyawanprash', '<p>Chyawanprash is an&nbsp;Ayurvedic health supplement&nbsp;which is made up of a super-concentrated blend of nutrient-rich herbs and minerals. It is meant to restore drained reserves of life force&nbsp; and to preserve strength, stamina, and vitality, while stalling the course of aging.</p>', NULL, NULL, NULL, 33, 1, NULL, '2021-10-08 11:00:26');

-- --------------------------------------------------------

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `sku`, `product_id`, `title`, `quantity`, `price`, `position`, `compare_at_price`, `barcode`, `cost_price`, `currency_id`, `tax_category_id`, `inventory_policy`, `fulfillment_service`, `inventory_management`, `created_at`, `updated_at`, `status`) VALUES
(1, 'sku-id', 1, NULL, 100, '500.00', 1, '500.00', '7543ebf012007e', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 'sku-id-1*5', 1, 'sku-id-Black-Black', 100, '500.00', 1, '500.00', '1500cdf2d597df', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, 'sku-id-1*6', 1, 'sku-id-Black-Grey', 100, '500.00', 1, '500.00', '2ea56327679387', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(4, 'sku-id-7*5', 1, 'sku-id-Medium-Black', 100, '500.00', 1, '500.00', '8f47f11a19433f', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(5, 'sku-id-7*6', 1, 'sku-id-Medium-Grey', 100, '500.00', 1, '500.00', '8f7318b112bbe9', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(6, 'Pulse Oximeters', 2, NULL, 200, '20.00', 1, '25.00', '58e12d7a1866c6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:09:55', '2021-09-30 06:13:26', 1),
(7, 'Thermometers', 3, NULL, 150, '22.00', 1, '25.00', '5e7a4aec328ead', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:09:55', '2021-09-30 06:13:34', 1),
(8, 'Blood Pressure Monitors', 4, NULL, 300, '25.00', 1, '30.00', '2eb3c4402c847c', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:09:55', '2021-09-30 06:17:55', 1),
(9, 'Blood Glucose Monitors', 5, NULL, 250, '30.00', 1, '35.00', '15c6610632c806', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:09:55', '2021-09-30 06:20:07', 1),
(10, 'Baby Body Lotion', 6, NULL, 200, '15.00', 1, '16.00', 'dc37ba3a137655', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:24:29', '2021-09-30 06:30:58', 1),
(11, 'Baby Body Oil', 7, NULL, 250, '12.00', 1, '15.00', 'e024d074c29449', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:24:29', '2021-09-30 06:30:48', 1),
(12, 'Moisturizing', 8, NULL, 220, '16.00', 1, '17.00', '03d9a000515ec2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:24:29', '2021-09-30 06:32:49', 1),
(13, 'Baby Soap', 9, NULL, 260, '20.00', 1, '22.00', '6c00d3153f0c0e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:24:29', '2021-09-30 06:36:51', 1),
(14, 'Sunscreens', 10, NULL, 240, '15.00', 1, '16.00', '1bbbd2418179c4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:43:34', '2021-09-30 06:46:50', 1),
(15, 'Hair dyes', 11, NULL, 200, '12.00', 1, '13.00', '4ef2cc12bc6588', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:43:34', '2021-09-30 06:47:47', 1),
(16, 'Skin cleansers', 12, NULL, 230, '15.00', 1, '17.00', '0af15fdecc8d1d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:43:34', '2021-09-30 06:48:36', 1),
(17, 'Deodorants', 13, NULL, 200, '10.00', 1, '14.00', '39f7e66be1c564', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:43:34', '2021-09-30 06:49:46', 1),
(18, 'Coconut Water & Coconut Oil', 14, NULL, 220, '12.00', 1, '13.00', '5918736da923f8', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:53:21', '2021-09-30 06:54:36', 1),
(19, 'Cannabidiol', 15, NULL, 230, '13.00', 1, '15.00', '62fd026f72a6b3', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:53:21', '2021-09-30 06:55:29', 1),
(20, 'Activated Charcoal', 16, NULL, 180, '16.00', 1, '18.00', '1de0cc36a422f4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:53:21', '2021-09-30 06:56:57', 1),
(21, 'Probiotics', 17, NULL, 180, '14.00', 1, '16.00', 'd1bf8971cd6870', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 06:53:21', '2021-09-30 06:58:36', 1),
(22, 'Ashwagandha', 18, NULL, 180, '13.00', 1, '15.00', '48e2a0ff18e4e9', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 07:02:26', '2021-09-30 07:04:24', 1),
(23, 'Shilajit', 19, NULL, 220, '13.00', 1, '16.00', '5b63b322468095', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 07:02:26', '2021-09-30 07:07:09', 1),
(24, 'Giloy', 20, NULL, 240, '18.00', 1, '20.00', '47c77a0e81fd3c', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 07:02:26', '2021-09-30 07:08:46', 1),
(25, 'Tulsi', 21, NULL, 260, '16.00', 1, '18.00', 'f59d3e497506b4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 07:02:26', '2021-09-30 07:11:18', 1),
(26, 'JH1231633688756a66c0f5', 22, NULL, 0, '0.00', 1, '0.00', '5578d83936ddb7', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:25:44', '2021-10-08 10:25:56', 1),
(27, 'JH12416336887529ccbff3', 23, NULL, 0, '0.00', 1, '0.00', '2e17631bef0dad', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:25:44', '2021-10-08 10:25:52', 1),
(28, 'NG125', 24, NULL, 0, '19.00', 1, '21.00', '2a8cec8ef29cc5', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:29:32', '2021-10-08 10:33:31', 1),
(29, 'NG126', 25, NULL, 1999, '15.00', 1, '18.00', '3024f73a24d578', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:29:32', '2021-10-08 10:35:53', 1),
(30, 'DF458', 26, NULL, 1890, '19.00', 1, '22.00', '9e09ac96c14290', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:33:32', '2021-10-08 10:38:13', 1),
(31, 'DF459', 27, NULL, 2880, '18.00', 1, '21.00', 'a55555084a1e57', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:33:32', '2021-10-08 10:40:40', 1),
(32, 'ABC987', 28, NULL, 1778, '18.00', 1, '23.00', 'bc55ca6fbeaab3', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:35:47', '2021-10-08 10:43:44', 1),
(33, 'ABC988', 29, NULL, 1899, '18.00', 1, '21.00', 'e5d58d036f481b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:35:47', '2021-10-08 10:47:17', 1),
(34, 'DF147', 30, NULL, 180, '10.00', 1, '12.00', '3123a5853fc998', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:37:56', '2021-10-08 10:52:16', 1),
(35, 'DF148', 31, NULL, 1899, '19.00', 1, '23.00', '4207d72c7e4714', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:37:56', '2021-10-08 10:53:54', 1),
(36, 'HR985', 32, NULL, 1890, '18.00', 1, '22.00', '58ad8b73d0ae90', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:42:23', '2021-10-08 10:57:31', 1),
(37, 'HR986', 33, NULL, 1896, '19.00', 1, '22.00', '9cbd08eae9173b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 10:42:23', '2021-10-08 11:00:26', 1);

-- --------------------------------------------------------

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
-- Dumping data for table `variant_option_translations`
--

INSERT INTO `variant_option_translations` (`id`, `title`, `variant_option_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small', 1, 1, NULL, '2021-10-01 09:03:26'),
(2, 'White', 2, 1, NULL, '2021-10-01 09:03:26'),
(3, 'Black', 3, 1, NULL, '2021-10-01 09:03:26'),
(4, 'Grey', 4, 1, NULL, '2021-10-01 09:03:26'),
(5, 'Medium', 5, 1, NULL, '2021-10-01 09:03:26'),
(6, 'Large', 6, 1, NULL, '2021-10-01 09:03:26'),
(7, 'IPhone', 7, 1, NULL, '2021-10-01 09:03:26'),
(8, 'Samsung', 8, 1, NULL, '2021-10-01 09:03:26'),
(9, 'Xiaomi', 9, 1, NULL, '2021-10-01 09:03:26');

-- --------------------------------------------------------


--
-- Dumping data for table `variant_translations`
--

INSERT INTO `variant_translations` (`id`, `title`, `variant_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, NULL, '2021-10-01 09:03:26'),
(2, 'Color', 2, 1, NULL, '2021-10-01 09:03:26'),
(3, 'Phones', 3, 1, NULL, '2021-10-01 09:03:26');



-- --------------------------------------------------------

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `vendor_id`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 14, 1, '2021-09-30 06:02:03', '2021-09-30 06:02:03'),
(2, 5, 15, 1, '2021-09-30 06:20:25', '2021-09-30 06:20:25'),
(3, 4, 16, 1, '2021-09-30 06:37:55', '2021-09-30 06:37:55'),
(4, 3, 17, 1, '2021-09-30 06:50:28', '2021-09-30 06:50:28'),
(5, 2, 18, 1, '2021-09-30 06:58:53', '2021-09-30 06:58:53'),
(6, 6, 15, 1, '2021-10-08 10:09:42', '2021-10-08 10:09:42'),
(7, 6, 16, 1, '2021-10-08 10:09:43', '2021-10-08 10:09:43'),
(8, 6, 17, 1, '2021-10-08 10:09:44', '2021-10-08 10:09:44'),
(9, 6, 18, 1, '2021-10-08 10:09:45', '2021-10-08 10:09:45'),
(10, 5, 14, 1, '2021-10-08 10:09:57', '2021-10-08 10:09:57'),
(11, 5, 16, 1, '2021-10-08 10:09:59', '2021-10-08 10:09:59'),
(12, 5, 17, 1, '2021-10-08 10:10:00', '2021-10-08 10:10:00'),
(13, 5, 18, 1, '2021-10-08 10:10:01', '2021-10-08 10:10:01'),
(14, 4, 14, 1, '2021-10-08 10:10:11', '2021-10-08 10:10:11'),
(15, 4, 15, 1, '2021-10-08 10:10:12', '2021-10-08 10:10:12'),
(16, 4, 18, 1, '2021-10-08 10:10:15', '2021-10-08 10:10:15'),
(17, 4, 17, 1, '2021-10-08 10:10:16', '2021-10-08 10:10:16'),
(18, 3, 14, 1, '2021-10-08 10:10:29', '2021-10-08 10:10:29'),
(19, 3, 15, 1, '2021-10-08 10:10:30', '2021-10-08 10:10:30'),
(20, 3, 16, 1, '2021-10-08 10:10:31', '2021-10-08 10:10:31'),
(21, 3, 18, 1, '2021-10-08 10:10:32', '2021-10-08 10:10:32'),
(22, 2, 14, 1, '2021-10-08 10:10:44', '2021-10-08 10:10:44'),
(23, 2, 15, 1, '2021-10-08 10:10:45', '2021-10-08 10:10:45'),
(24, 2, 16, 1, '2021-10-08 10:10:46', '2021-10-08 10:10:46'),
(25, 2, 17, 1, '2021-10-08 10:10:47', '2021-10-08 10:10:47');

-- --------------------------------------------------------



INSERT INTO `banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`, `image_mobile`) VALUES
(2, 'Personal Care', NULL, 'banner/xkKDss1CFsTeMztZGK1VpkTrwN1nsT1RfOBg6peR.jpg', 1, 2, 1, '2021-09-30 12:45:00', '2022-02-25 12:00:00', 16, NULL, 'category', NULL, '2021-10-11 10:25:11', 'banner/Xs2dkL4gjbRIEPUUylFG4oSg0uVmFKzuXYtuRYj9.png'),
(3, 'Babycare', NULL, 'banner/olrG03gWOpVbsRcnh5mtuD6U2MF9VxwDVAWqHGWb.jpg', 1, 3, 1, '2021-09-30 12:49:00', '2022-02-24 12:00:00', 15, NULL, 'category', NULL, '2021-10-11 10:25:01', 'banner/B7RkdOJ5rloiq1BdFG94dzcYE0pMStMgp2FuI1o1.png'),
(4, 'Banner 3', NULL, 'banner/xG7FYedBqi6raha6Fb0zMqyHSmHz9XuJwRR94ZWd.jpg', 1, 4, 1, '2021-10-08 11:10:00', '2025-10-30 12:00:00', 16, NULL, 'category', '2021-10-08 05:40:36', '2021-10-11 10:24:51', 'banner/x9HoHCUxGABYH2TouUmftxczV3BLVnsHRDgJWBMV.png');


INSERT INTO `mobile_banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`) VALUES
(2, 'Personal Care', NULL, 'banner/xkKDss1CFsTeMztZGK1VpkTrwN1nsT1RfOBg6peR.jpg', 1, 2, 1, '2021-09-30 12:45:00', '2022-02-25 12:00:00', 16, NULL, 'category', NULL, '2021-10-11 10:25:11'),
(3, 'Babycare', NULL, 'banner/olrG03gWOpVbsRcnh5mtuD6U2MF9VxwDVAWqHGWb.jpg', 1, 3, 1, '2021-09-30 12:49:00', '2022-02-24 12:00:00', 15, NULL, 'category', NULL, '2021-10-11 10:25:01'),
(4, 'Banner 3', NULL, 'banner/xG7FYedBqi6raha6Fb0zMqyHSmHz9XuJwRR94ZWd.jpg', 1, 4, 1, '2021-10-08 11:10:00', '2025-10-30 12:00:00', 16, NULL, 'category', '2021-10-08 05:40:36', '2021-10-11 10:24:51');


INSERT INTO `cab_booking_layouts` (`id`, `title`, `slug`, `order_by`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vendors', 'vendors', 1, 1, NULL, NULL),
(2, 'Featured Products', 'featured_products', 2, 1, NULL, NULL),
(3, 'New Products', 'new_products', 3, 1, NULL, NULL),
(4, 'On Sale', 'on_sale', 4, 1, NULL, NULL),
(5, 'Best Sellers', 'best_sellers', 5, 1, NULL, NULL),
(6, 'Brands', 'brands', 6, 1, NULL, NULL);



INSERT INTO `cab_booking_layout_transaltions` (`id`, `title`, `cab_booking_layout_id`, `language_id`, `created_at`, `updated_at`, `body_html`) VALUES
(1, NULL, 1, 1, '2021-10-05 07:41:40', '2021-10-05 07:41:40', NULL),
(2, NULL, 2, 1, '2021-10-05 07:41:40', '2021-10-05 07:41:40', NULL),
(3, NULL, 3, 1, '2021-10-05 07:41:40', '2021-10-05 07:41:40', NULL),
(4, NULL, 4, 1, '2021-10-05 07:41:40', '2021-10-05 07:41:40', NULL),
(5, NULL, 5, 1, '2021-10-05 07:41:40', '2021-10-05 07:41:40', NULL),
(6, NULL, 6, 1, '2021-10-05 07:41:40', '2021-10-05 07:41:40', NULL);