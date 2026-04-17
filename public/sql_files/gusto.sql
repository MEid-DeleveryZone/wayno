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
(1, 'DeliveryZone', NULL, NULL, 'default/default_logo.png', 'default/default_image.png', 'Sheikh Zayed Road - Dubai - United Arab Emirates', NULL, NULL, NULL, '25.060924600000', '55.128979500000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 2, 1, 0, 0, NULL, '2021-09-30 10:13:17', 1, NULL, 0),
(2, 'Mini Mart', 'mini-mart', NULL, 'vendor/AaXdXudoRLJUzoWUbCh1xOerT3CmTPrCbgz2nBIE.jpg', 'vendor/NFEsYj6mGpCVcEctoCG3vHnpFg382s3ueODSBEVH.jpg', 'Chandigarh, India', 'minimart@support.com', NULL, '9859856363', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 1, 1, 1, 1, 1, 0, 0, '2021-09-30 10:12:17', '2021-10-08 10:32:47', 1, 1, 0),
(3, 'Wall Street', 'wall-street', NULL, 'vendor/Rjc22mFhVAsYf34VYcUR4htxHeWn7qRcCqE6fu2K.jpg', 'vendor/W7a4kYQAZcdcUsE6g9zMhWGA6XQaAQhj9Ugnbrdd.jpg', 'Chandigarh, India', 'wallstreet@support.com', NULL, '9857496201', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 1, 1, 1, 1, 1, 0, 0, '2021-09-30 10:13:13', '2021-10-08 10:33:45', 1, 1, 0),
(4, 'Fresh Harvest', 'fresh-harvest', NULL, 'vendor/BjM1bRwdAOwBfE1CC1icQi4y5CaVawxHkvJzL1Sx.png', 'vendor/GYOwjsGD30N3cuNT1PfKhNhnR9nKhdEf41HX9Xw7.jpg', 'Chandigarh, India', 'freshharvest@support.com', NULL, '9685234178', '30.538994400000', '75.955032900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 1, 1, 1, 1, 1, 0, 0, '2021-09-30 10:13:42', '2021-10-08 10:33:27', 1, 1, 0),
(5, 'Fresh Grocer', 'fresh-grocer', NULL, 'vendor/p29nxG7NNEWrhmXo6LiRncKZ0SywtnKDiGW2dPyE.jpg', 'vendor/aDkLjMFFXbNZICqPzy32yxsuYWFzHuKiwFBbwLBG.jpg', 'Chandigarh, India', 'freshgrocer@support.com', NULL, '9805203698', '30.538994400000', '75.955032900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 1, 1, 1, 1, 1, 0, 0, '2021-09-30 10:14:22', '2021-10-08 10:33:16', 1, 1, 0),
(6, 'Omega', 'omega', NULL, 'vendor/eDwvcqPjJvEg6tqTEBqVY3i66f0jZ9qFMCYZZIYa.jpg', 'vendor/hrIfJ3ImNhe7Dg4aQelCibiF5XqrzwG9fJMvspbv.jpg', 'Chandigarh, India', 'Omega Grocery', NULL, '8505201346', '30.733314800000', '76.779417900000', '0.00', '0', NULL, 1, '0.00', '0.00', 1, 1, 1, 1, 1, 0, 0, '2021-09-30 10:15:07', '2021-10-08 10:33:06', 1, 1, 0);

--
-- Dumping data for table `addon_sets`
--

INSERT INTO `addon_sets` (`id`, `title`, `min_select`, `max_select`, `position`, `status`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, 1, 1, 1, NULL, NULL, NULL),
(2, 'Brocoli', 1, 1, 1, 1, 1, 6, '2021-10-08 12:54:43', '2021-10-08 12:54:43'),
(3, 'Potato', 1, 1, 1, 1, 1, 6, '2021-10-08 12:55:33', '2021-10-08 12:55:33'),
(4, 'Carrot', 1, 1, 1, 1, 1, 6, '2021-10-08 12:56:18', '2021-10-08 12:56:18'),
(5, 'Raddish', 1, 1, 1, 1, 1, 6, '2021-10-08 12:57:19', '2021-10-08 12:57:19'),
(6, 'Apple', 1, 1, 1, 1, 1, 6, '2021-10-08 12:58:30', '2021-10-08 12:58:30'),
(7, 'Mango', 1, 1, 1, 1, 1, 6, '2021-10-08 12:59:11', '2021-10-08 12:59:11'),
(8, 'Banana', 1, 1, 1, 1, 1, 6, '2021-10-08 12:59:58', '2021-10-08 12:59:58'),
(9, 'Papaya', 1, 1, 1, 1, 1, 6, '2021-10-08 13:00:39', '2021-10-08 13:00:39'),
(10, 'Custard', 1, 1, 1, 1, 1, 5, '2021-10-08 13:02:33', '2021-10-08 13:02:33'),
(11, 'Cream', 1, 1, 1, 1, 1, 5, '2021-10-08 13:03:15', '2021-10-08 13:03:15'),
(12, 'Sugarcane juice', 1, 1, 1, 1, 1, 5, '2021-10-08 13:06:13', '2021-10-08 13:06:13'),
(13, 'Wine', 1, 1, 1, 1, 1, 5, '2021-10-08 13:08:03', '2021-10-08 13:08:03'),
(14, 'Pastries', 1, 1, 1, 1, 1, 5, '2021-10-08 13:09:06', '2021-10-08 13:09:06'),
(15, 'pies', 1, 1, 1, 1, 1, 5, '2021-10-08 13:09:40', '2021-10-08 13:09:40'),
(16, 'Cow Full Fat Milk', 1, 1, 1, 1, 1, 4, '2021-10-08 13:10:40', '2021-10-08 13:10:40'),
(17, 'Eggs', 1, 1, 1, 1, 1, 4, '2021-10-08 13:11:13', '2021-10-08 13:11:13'),
(18, 'Cheese', 1, 1, 1, 1, 1, 4, '2021-10-08 13:11:49', '2021-10-08 13:11:49'),
(19, 'Butter', 1, 1, 1, 1, 1, 4, '2021-10-08 13:12:23', '2021-10-08 13:12:23'),
(20, 'Yogurt', 1, 1, 1, 1, 1, 4, '2021-10-08 13:14:08', '2021-10-08 13:14:08'),
(21, 'Ice Cream', 1, 1, 1, 1, 1, 4, '2021-10-08 13:14:59', '2021-10-08 13:14:59'),
(22, 'Brown Bread', 0, 2, 1, 1, 1, 3, '2021-10-08 13:16:09', '2021-10-08 13:16:09'),
(23, 'Choco lava cake', 1, 1, 1, 1, 1, 3, '2021-10-08 13:16:37', '2021-10-08 13:16:37'),
(24, 'Blueberry Muffins', 1, 1, 1, 1, 1, 3, '2021-10-08 13:17:12', '2021-10-08 13:17:12'),
(25, 'Strawberry icecreams', 1, 1, 1, 1, 1, 3, '2021-10-08 13:17:44', '2021-10-08 13:17:44'),
(26, 'Bread & Buns', 1, 1, 1, 1, 1, 3, '2021-10-08 13:18:22', '2021-10-08 13:18:22'),
(27, 'Cake', 1, 1, 1, 1, 1, 3, '2021-10-08 13:19:38', '2021-10-08 13:19:38'),
(28, 'Coffee', 1, 1, 1, 1, 1, 2, '2021-10-08 13:22:17', '2021-10-08 13:22:17'),
(29, 'Health Drinks', 1, 1, 1, 1, 1, 2, '2021-10-08 13:22:56', '2021-10-08 13:22:56'),
(30, 'Cold Drinks', 1, 1, 1, 1, 1, 2, '2021-10-08 13:23:34', '2021-10-08 13:23:34'),
(31, 'Water Soda', 1, 1, 1, 1, 1, 2, '2021-10-08 13:24:14', '2021-10-08 13:24:14'),
(32, 'juice', 0, 2, 1, 1, 1, 2, '2021-10-08 13:24:49', '2021-10-08 13:24:49'),
(33, 'beer', 1, 1, 1, 1, 1, 2, '2021-10-08 13:25:22', '2021-10-08 13:25:22');



--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `icon`, `slug`, `type_id`, `image`, `is_visible`, `status`, `position`, `is_core`, `can_add_products`, `parent_id`, `vendor_id`, `client_code`, `display_mode`, `warning_page_id`, `template_type_id`, `warning_page_design`, `created_at`, `updated_at`, `deleted_at`, `show_wishlist`) VALUES
(1, NULL, 'Root', 3, NULL, 0, 1, 1, 1, 0, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, NULL, 'Delivery', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:40:41', '2021-10-01 14:40:41', 1),
(3, NULL, 'Restaurant', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:40:44', '2021-10-01 14:40:44', 1),
(4, NULL, 'Supermarket', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:40:47', '2021-10-01 14:40:47', 1),
(5, NULL, 'Pharmacy', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:40:53', '2021-10-01 14:40:53', 1),
(6, NULL, 'Send something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-01 14:40:41', '2021-10-01 14:40:41', 1),
(7, NULL, 'Buy something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-10-01 14:40:41', '2021-10-01 14:40:41', 1),
(8, 'category/icon/IBfpmY0WxtfyZt3aDa1oK1Ylbcl5WNjiJEJYsLSQ.svg', 'Vegetables& Fruits', 1, 'category/image/CYXcpdrgSKKbw8XMHPhQfUd3aLuXWQCekKqCRwm4.png', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 11:48:39', NULL, 1),
(9, 'category/icon/2vo8hi9LutW7v367hFhHidOZd9hcRyuRWRB0s4a2.svg', 'Fruits', 1, 'category/image/K2vLZvOuuYI5HSwSH43kBucl5xQZmyJho4r4BL2O.jpg', 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 12:48:57', NULL, 1),
(10, 'category/icon/eYR1U30UrQVFGZpewB5RpCSbz3cbXZx698FyV0r0.svg', 'Dairy and Eggs', 1, 'category/image/cTGFdgTVUa1Llbdwi9EO3vLRQ8jhFEpQsnsI6m4X.jpg', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-07 08:53:14', NULL, 1),
(11, NULL, 'E-Commerce', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:40:56', '2021-10-01 14:40:56', 1),
(12, NULL, 'Cloth', 1, NULL, 0, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:00', '2021-10-01 14:41:00', 1),
(13, NULL, 'Dispatcher', 6, NULL, 0, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-01 14:41:05', '2021-10-01 14:41:05', 1),
(14, 'category/icon/AhnoUOVKYf1NvjZ3lCvfiNylOwkU2Al5G0xtAIzI.svg', 'bakeryproducts', 1, 'category/image/W60KOGsUuhkkOKn0CJQzmdKHpSD85Yuf1buocCP4.jpg', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-30 10:00:46', '2021-10-07 08:53:19', NULL, 1),
(15, 'category/icon/l13a7knkwjhzcciMJOl2n5uw6xEVY9wkIKWzOdvb.svg', 'beverages', 1, 'category/image/v9Lgft35v6S9W78Sp6PqzStbFv3wQ2pqMkUADpM9.jpg', 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-30 10:10:54', '2021-10-07 08:53:24', NULL, 1);

--
-- Dumping data for table `addon_options`
--

INSERT INTO `addon_options` (`id`, `title`, `addon_id`, `position`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, '100.00', NULL, NULL),
(2, '1 Kg', 2, 1, '10.00', '2021-10-08 12:54:43', '2021-10-08 12:54:43'),
(3, '2Kg', 2, 2, '20.00', '2021-10-08 12:54:43', '2021-10-08 12:54:43'),
(4, '3Kg', 2, 3, '30.00', '2021-10-08 12:54:43', '2021-10-08 12:54:43'),
(5, '4Kg', 2, 4, '40.00', '2021-10-08 12:54:43', '2021-10-08 12:54:43'),
(6, '1 Kg', 3, 1, '10.00', '2021-10-08 12:55:33', '2021-10-08 12:55:33'),
(7, '2 Kg', 3, 2, '20.00', '2021-10-08 12:55:33', '2021-10-08 12:55:33'),
(8, '3 Kg', 3, 3, '30.00', '2021-10-08 12:55:33', '2021-10-08 12:55:33'),
(9, '4 Kg', 3, 4, '40.00', '2021-10-08 12:55:33', '2021-10-08 12:55:33'),
(10, '1 Kg', 4, 1, '10.00', '2021-10-08 12:56:18', '2021-10-08 12:56:18'),
(11, '2 Kg', 4, 2, '20.00', '2021-10-08 12:56:18', '2021-10-08 12:56:18'),
(12, '3 Kg', 4, 3, '30.00', '2021-10-08 12:56:18', '2021-10-08 12:56:18'),
(13, '4 Kg', 4, 4, '40.00', '2021-10-08 12:56:18', '2021-10-08 12:56:18'),
(14, '1 Kg', 5, 1, '10.00', '2021-10-08 12:57:19', '2021-10-08 12:57:19'),
(15, '2 Kg', 5, 2, '20.00', '2021-10-08 12:57:19', '2021-10-08 12:57:19'),
(16, '3 Kg', 5, 3, '30.00', '2021-10-08 12:57:19', '2021-10-08 12:57:19'),
(17, '4 Kg', 5, 4, '40.00', '2021-10-08 12:57:19', '2021-10-08 12:57:19'),
(18, '1 Kg', 6, 1, '10.00', '2021-10-08 12:58:30', '2021-10-08 12:58:30'),
(19, '2 Kg', 6, 2, '20.00', '2021-10-08 12:58:30', '2021-10-08 12:58:30'),
(20, '3 Kg', 6, 3, '30.00', '2021-10-08 12:58:30', '2021-10-08 12:58:30'),
(21, '4 Kg', 6, 4, '40.00', '2021-10-08 12:58:30', '2021-10-08 12:58:30'),
(22, '1 Kg', 7, 1, '10.00', '2021-10-08 12:59:11', '2021-10-08 12:59:11'),
(23, '2 Kg', 7, 2, '20.00', '2021-10-08 12:59:11', '2021-10-08 12:59:11'),
(24, '3 Kg', 7, 3, '30.00', '2021-10-08 12:59:11', '2021-10-08 12:59:11'),
(25, '4 Kg', 7, 4, '40.00', '2021-10-08 12:59:11', '2021-10-08 12:59:11'),
(26, '1 Kg', 8, 1, '10.00', '2021-10-08 12:59:58', '2021-10-08 12:59:58'),
(27, '2 Kg', 8, 2, '20.00', '2021-10-08 12:59:58', '2021-10-08 12:59:58'),
(28, '3 Kg', 8, 3, '30.00', '2021-10-08 12:59:58', '2021-10-08 12:59:58'),
(29, '4 Kg', 8, 4, '40.00', '2021-10-08 12:59:58', '2021-10-08 12:59:58'),
(30, '1 Kg', 9, 1, '10.00', '2021-10-08 13:00:39', '2021-10-08 13:00:39'),
(31, '2 Kg', 9, 2, '20.00', '2021-10-08 13:00:39', '2021-10-08 13:00:39'),
(32, '3 Kg', 9, 3, '30.00', '2021-10-08 13:00:39', '2021-10-08 13:00:39'),
(33, '4 Kg', 9, 4, '40.00', '2021-10-08 13:00:39', '2021-10-08 13:00:39'),
(34, '1', 10, 1, '10.00', '2021-10-08 13:02:33', '2021-10-08 13:33:24'),
(35, '2', 10, 2, '20.00', '2021-10-08 13:02:33', '2021-10-08 13:33:24'),
(36, '3', 10, 3, '30.00', '2021-10-08 13:02:33', '2021-10-08 13:33:24'),
(37, '4', 10, 4, '40.00', '2021-10-08 13:02:33', '2021-10-08 13:33:24'),
(38, '1 Kg', 11, 1, '10.00', '2021-10-08 13:03:15', '2021-10-08 13:03:15'),
(39, '2 Kg', 11, 2, '20.00', '2021-10-08 13:03:15', '2021-10-08 13:03:15'),
(40, '3 Kg', 11, 3, '30.00', '2021-10-08 13:03:15', '2021-10-08 13:03:15'),
(41, '4 Kg', 11, 4, '40.00', '2021-10-08 13:03:15', '2021-10-08 13:03:15'),
(42, '1 L', 12, 1, '10.00', '2021-10-08 13:06:13', '2021-10-08 13:06:13'),
(43, '2 L', 12, 2, '20.00', '2021-10-08 13:06:13', '2021-10-08 13:06:13'),
(44, '3 L', 12, 3, '30.00', '2021-10-08 13:06:13', '2021-10-08 13:06:13'),
(45, '4 L', 12, 4, '40.00', '2021-10-08 13:06:13', '2021-10-08 13:06:13'),
(46, 'Red Wine', 13, 1, '20.00', '2021-10-08 13:08:03', '2021-10-08 13:08:03'),
(47, 'Rose Wine', 13, 2, '25.00', '2021-10-08 13:08:03', '2021-10-08 13:08:03'),
(48, 'Sparkling Wine', 13, 3, '28.00', '2021-10-08 13:08:03', '2021-10-08 13:08:03'),
(49, 'Dessert wines', 13, 4, '30.00', '2021-10-08 13:08:03', '2021-10-08 13:08:03'),
(50, '1', 14, 1, '12.00', '2021-10-08 13:09:06', '2021-10-08 13:09:06'),
(51, '2', 14, 2, '24.00', '2021-10-08 13:09:06', '2021-10-08 13:09:06'),
(52, '3', 14, 3, '36.00', '2021-10-08 13:09:06', '2021-10-08 13:09:06'),
(53, '4', 14, 4, '40.00', '2021-10-08 13:09:06', '2021-10-08 13:09:06'),
(54, '1', 15, 1, '1.00', '2021-10-08 13:09:40', '2021-10-08 13:09:40'),
(55, '2', 15, 2, '2.00', '2021-10-08 13:09:40', '2021-10-08 13:09:40'),
(56, '3', 15, 3, '3.00', '2021-10-08 13:09:40', '2021-10-08 13:09:40'),
(57, '4', 15, 4, '4.00', '2021-10-08 13:09:40', '2021-10-08 13:09:40'),
(58, '1 L', 16, 1, '10.00', '2021-10-08 13:10:40', '2021-10-08 13:10:40'),
(59, '2 L', 16, 2, '20.00', '2021-10-08 13:10:40', '2021-10-08 13:10:40'),
(60, '3 L', 16, 3, '30.00', '2021-10-08 13:10:40', '2021-10-08 13:10:40'),
(61, '4 L', 16, 4, '40.00', '2021-10-08 13:10:40', '2021-10-08 13:10:40'),
(62, '1', 17, 1, '5.00', '2021-10-08 13:11:13', '2021-10-08 13:11:13'),
(63, '2', 17, 2, '10.00', '2021-10-08 13:11:13', '2021-10-08 13:11:13'),
(64, '3', 17, 3, '15.00', '2021-10-08 13:11:13', '2021-10-08 13:11:13'),
(65, '4', 17, 4, '20.00', '2021-10-08 13:11:13', '2021-10-08 13:11:13'),
(66, '1', 18, 1, '6.00', '2021-10-08 13:11:49', '2021-10-08 13:11:49'),
(67, '2', 18, 2, '12.00', '2021-10-08 13:11:50', '2021-10-08 13:11:50'),
(68, '3', 18, 3, '24.00', '2021-10-08 13:11:50', '2021-10-08 13:11:50'),
(69, '4', 18, 4, '30.00', '2021-10-08 13:11:50', '2021-10-08 13:11:50'),
(70, '1', 19, 1, '5.00', '2021-10-08 13:12:23', '2021-10-08 13:12:23'),
(71, '2', 19, 2, '10.00', '2021-10-08 13:12:23', '2021-10-08 13:12:23'),
(72, '3', 19, 3, '15.00', '2021-10-08 13:12:23', '2021-10-08 13:12:23'),
(73, '4', 19, 4, '20.00', '2021-10-08 13:12:23', '2021-10-08 13:12:23'),
(74, '1', 20, 1, '10.00', '2021-10-08 13:14:08', '2021-10-08 13:14:08'),
(75, '2', 20, 2, '20.00', '2021-10-08 13:14:08', '2021-10-08 13:14:08'),
(76, '3', 20, 3, '30.00', '2021-10-08 13:14:08', '2021-10-08 13:14:08'),
(77, '4', 20, 4, '40.00', '2021-10-08 13:14:08', '2021-10-08 13:14:08'),
(78, '1', 21, 1, '10.00', '2021-10-08 13:14:59', '2021-10-08 13:14:59'),
(79, '2', 21, 2, '20.00', '2021-10-08 13:14:59', '2021-10-08 13:14:59'),
(80, '3', 21, 3, '30.00', '2021-10-08 13:14:59', '2021-10-08 13:14:59'),
(81, '4', 21, 4, '40.00', '2021-10-08 13:14:59', '2021-10-08 13:14:59'),
(82, '1', 22, 1, '5.00', '2021-10-08 13:16:09', '2021-10-08 13:16:09'),
(83, '2', 22, 2, '10.00', '2021-10-08 13:16:09', '2021-10-08 13:16:09'),
(84, '3', 22, 3, '15.00', '2021-10-08 13:16:09', '2021-10-08 13:16:09'),
(85, '4', 22, 4, '20.00', '2021-10-08 13:16:09', '2021-10-08 13:16:09'),
(86, '1', 23, 1, '20.00', '2021-10-08 13:16:37', '2021-10-08 13:16:37'),
(87, '2', 23, 2, '40.00', '2021-10-08 13:16:37', '2021-10-08 13:16:37'),
(88, '3', 23, 3, '60.00', '2021-10-08 13:16:37', '2021-10-08 13:16:37'),
(89, '4', 23, 4, '80.00', '2021-10-08 13:16:37', '2021-10-08 13:16:37'),
(90, '1', 24, 1, '5.00', '2021-10-08 13:17:12', '2021-10-08 13:17:12'),
(91, '2', 24, 2, '10.00', '2021-10-08 13:17:12', '2021-10-08 13:17:12'),
(92, '3', 24, 3, '15.00', '2021-10-08 13:17:12', '2021-10-08 13:17:12'),
(93, '4', 24, 4, '20.00', '2021-10-08 13:17:12', '2021-10-08 13:17:12'),
(94, '1', 25, 1, '4.00', '2021-10-08 13:17:44', '2021-10-08 13:17:44'),
(95, '2', 25, 2, '8.00', '2021-10-08 13:17:44', '2021-10-08 13:17:44'),
(96, '3', 25, 3, '10.00', '2021-10-08 13:17:44', '2021-10-08 13:17:44'),
(97, '4', 25, 4, '12.00', '2021-10-08 13:17:44', '2021-10-08 13:17:44'),
(98, '1', 26, 1, '4.00', '2021-10-08 13:18:22', '2021-10-08 13:18:22'),
(99, '2', 26, 2, '8.00', '2021-10-08 13:18:22', '2021-10-08 13:18:22'),
(100, '3', 26, 3, '10.00', '2021-10-08 13:18:22', '2021-10-08 13:18:22'),
(101, '4', 26, 4, '12.00', '2021-10-08 13:18:22', '2021-10-08 13:18:22'),
(102, '1 Kg', 27, 1, '10.00', '2021-10-08 13:19:38', '2021-10-08 13:19:38'),
(103, '2 Kg', 27, 2, '20.00', '2021-10-08 13:19:38', '2021-10-08 13:19:38'),
(104, '3 Kg', 27, 3, '30.00', '2021-10-08 13:19:38', '2021-10-08 13:19:38'),
(105, '4 Kg', 27, 4, '40.00', '2021-10-08 13:19:38', '2021-10-08 13:19:38'),
(106, 'Macchiato', 28, 1, '5.00', '2021-10-08 13:22:17', '2021-10-08 13:22:17'),
(107, 'Ristretto', 28, 2, '10.00', '2021-10-08 13:22:17', '2021-10-08 13:22:17'),
(108, 'Cafe Latte', 28, 3, '15.00', '2021-10-08 13:22:17', '2021-10-08 13:22:17'),
(109, 'Piccolo Latte', 28, 4, '20.00', '2021-10-08 13:22:17', '2021-10-08 13:22:17'),
(110, '1', 29, 1, '8.00', '2021-10-08 13:22:56', '2021-10-08 13:22:56'),
(111, '2', 29, 2, '16.00', '2021-10-08 13:22:56', '2021-10-08 13:22:56'),
(112, '3', 29, 3, '20.00', '2021-10-08 13:22:56', '2021-10-08 13:22:56'),
(113, '4', 29, 4, '25.00', '2021-10-08 13:22:56', '2021-10-08 13:22:56'),
(114, '1', 30, 1, '10.00', '2021-10-08 13:23:34', '2021-10-08 13:23:34'),
(115, '2', 30, 2, '20.00', '2021-10-08 13:23:34', '2021-10-08 13:23:34'),
(116, '3', 30, 3, '30.00', '2021-10-08 13:23:34', '2021-10-08 13:23:34'),
(117, '4', 30, 4, '40.00', '2021-10-08 13:23:34', '2021-10-08 13:23:34'),
(118, '1', 31, 1, '10.00', '2021-10-08 13:24:14', '2021-10-08 13:24:14'),
(119, '2', 31, 2, '20.00', '2021-10-08 13:24:14', '2021-10-08 13:24:14'),
(120, '3', 31, 3, '30.00', '2021-10-08 13:24:14', '2021-10-08 13:24:14'),
(121, '4', 31, 4, '40.00', '2021-10-08 13:24:14', '2021-10-08 13:24:14'),
(122, '1', 32, 1, '8.00', '2021-10-08 13:24:49', '2021-10-08 13:24:49'),
(123, '2', 32, 2, '16.00', '2021-10-08 13:24:49', '2021-10-08 13:24:49'),
(124, '3', 32, 3, '20.00', '2021-10-08 13:24:49', '2021-10-08 13:24:49'),
(125, '4', 32, 4, '25.00', '2021-10-08 13:24:49', '2021-10-08 13:24:49'),
(126, '1', 33, 1, '12.00', '2021-10-08 13:25:22', '2021-10-08 13:25:22'),
(127, '2', 33, 2, '18.00', '2021-10-08 13:25:22', '2021-10-08 13:25:22'),
(128, '3', 33, 3, '25.00', '2021-10-08 13:25:22', '2021-10-08 13:25:22'),
(129, '4', 33, 4, '30.00', '2021-10-08 13:25:22', '2021-10-08 13:25:22');



-- --------------------------------------------------------


--
-- Dumping data for table `addon_option_translations`
--

INSERT INTO `addon_option_translations` (`id`, `title`, `addon_opt_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, NULL, '2021-09-30 12:04:20'),
(2, '1 Kg', 2, 1, NULL, NULL),
(3, '2Kg', 3, 1, NULL, NULL),
(4, '3Kg', 4, 1, NULL, NULL),
(5, '4Kg', 5, 1, NULL, NULL),
(6, '1 Kg', 6, 1, NULL, NULL),
(7, '2 Kg', 7, 1, NULL, NULL),
(8, '3 Kg', 8, 1, NULL, NULL),
(9, '4 Kg', 9, 1, NULL, NULL),
(10, '1 Kg', 10, 1, NULL, NULL),
(11, '2 Kg', 11, 1, NULL, NULL),
(12, '3 Kg', 12, 1, NULL, NULL),
(13, '4 Kg', 13, 1, NULL, NULL),
(14, '1 Kg', 14, 1, NULL, NULL),
(15, '2 Kg', 15, 1, NULL, NULL),
(16, '3 Kg', 16, 1, NULL, NULL),
(17, '4 Kg', 17, 1, NULL, NULL),
(18, '1 Kg', 18, 1, NULL, NULL),
(19, '2 Kg', 19, 1, NULL, NULL),
(20, '3 Kg', 20, 1, NULL, NULL),
(21, '4 Kg', 21, 1, NULL, NULL),
(22, '1 Kg', 22, 1, NULL, NULL),
(23, '2 Kg', 23, 1, NULL, NULL),
(24, '3 Kg', 24, 1, NULL, NULL),
(25, '4 Kg', 25, 1, NULL, NULL),
(26, '1 Kg', 26, 1, NULL, NULL),
(27, '2 Kg', 27, 1, NULL, NULL),
(28, '3 Kg', 28, 1, NULL, NULL),
(29, '4 Kg', 29, 1, NULL, NULL),
(30, '1 Kg', 30, 1, NULL, NULL),
(31, '2 Kg', 31, 1, NULL, NULL),
(32, '3 Kg', 32, 1, NULL, NULL),
(33, '4 Kg', 33, 1, NULL, NULL),
(34, '1', 34, 1, NULL, '2021-10-08 13:33:24'),
(35, '2', 35, 1, NULL, '2021-10-08 13:33:24'),
(36, '3', 36, 1, NULL, '2021-10-08 13:33:24'),
(37, '4', 37, 1, NULL, '2021-10-08 13:33:24'),
(38, '1 Kg', 38, 1, NULL, NULL),
(39, '2 Kg', 39, 1, NULL, NULL),
(40, '3 Kg', 40, 1, NULL, NULL),
(41, '4 Kg', 41, 1, NULL, NULL),
(42, '1 L', 42, 1, NULL, NULL),
(43, '2 L', 43, 1, NULL, NULL),
(44, '3 L', 44, 1, NULL, NULL),
(45, '4 L', 45, 1, NULL, NULL),
(46, 'Red Wine', 46, 1, NULL, NULL),
(47, 'Rose Wine', 47, 1, NULL, NULL),
(48, 'Sparkling Wine', 48, 1, NULL, NULL),
(49, 'Dessert wines', 49, 1, NULL, NULL),
(50, '1', 50, 1, NULL, NULL),
(51, '2', 51, 1, NULL, NULL),
(52, '3', 52, 1, NULL, NULL),
(53, '4', 53, 1, NULL, NULL),
(54, '1', 54, 1, NULL, NULL),
(55, '2', 55, 1, NULL, NULL),
(56, '3', 56, 1, NULL, NULL),
(57, '4', 57, 1, NULL, NULL),
(58, '1 L', 58, 1, NULL, NULL),
(59, '2 L', 59, 1, NULL, NULL),
(60, '3 L', 60, 1, NULL, NULL),
(61, '4 L', 61, 1, NULL, NULL),
(62, '1', 62, 1, NULL, NULL),
(63, '2', 63, 1, NULL, NULL),
(64, '3', 64, 1, NULL, NULL),
(65, '4', 65, 1, NULL, NULL),
(66, '1', 66, 1, NULL, NULL),
(67, '2', 67, 1, NULL, NULL),
(68, '3', 68, 1, NULL, NULL),
(69, '4', 69, 1, NULL, NULL),
(70, '1', 70, 1, NULL, NULL),
(71, '2', 71, 1, NULL, NULL),
(72, '3', 72, 1, NULL, NULL),
(73, '4', 73, 1, NULL, NULL),
(74, '1', 74, 1, NULL, NULL),
(75, '2', 75, 1, NULL, NULL),
(76, '3', 76, 1, NULL, NULL),
(77, '4', 77, 1, NULL, NULL),
(78, '1', 78, 1, NULL, NULL),
(79, '2', 79, 1, NULL, NULL),
(80, '3', 80, 1, NULL, NULL),
(81, '4', 81, 1, NULL, NULL),
(82, '1', 82, 1, NULL, NULL),
(83, '2', 83, 1, NULL, NULL),
(84, '3', 84, 1, NULL, NULL),
(85, '4', 85, 1, NULL, NULL),
(86, '1', 86, 1, NULL, NULL),
(87, '2', 87, 1, NULL, NULL),
(88, '3', 88, 1, NULL, NULL),
(89, '4', 89, 1, NULL, NULL),
(90, '1', 90, 1, NULL, NULL),
(91, '2', 91, 1, NULL, NULL),
(92, '3', 92, 1, NULL, NULL),
(93, '4', 93, 1, NULL, NULL),
(94, '1', 94, 1, NULL, NULL),
(95, '2', 95, 1, NULL, NULL),
(96, '3', 96, 1, NULL, NULL),
(97, '4', 97, 1, NULL, NULL),
(98, '1', 98, 1, NULL, NULL),
(99, '2', 99, 1, NULL, NULL),
(100, '3', 100, 1, NULL, NULL),
(101, '4', 101, 1, NULL, NULL),
(102, '1 Kg', 102, 1, NULL, NULL),
(103, '2 Kg', 103, 1, NULL, NULL),
(104, '3 Kg', 104, 1, NULL, NULL),
(105, '4 Kg', 105, 1, NULL, NULL),
(106, 'Macchiato', 106, 1, NULL, NULL),
(107, 'Ristretto', 107, 1, NULL, NULL),
(108, 'Cafe Latte', 108, 1, NULL, NULL),
(109, 'Piccolo Latte', 109, 1, NULL, NULL),
(110, '1', 110, 1, NULL, NULL),
(111, '2', 111, 1, NULL, NULL),
(112, '3', 112, 1, NULL, NULL),
(113, '4', 113, 1, NULL, NULL),
(114, '1', 114, 1, NULL, NULL),
(115, '2', 115, 1, NULL, NULL),
(116, '3', 116, 1, NULL, NULL),
(117, '4', 117, 1, NULL, NULL),
(118, '1', 118, 1, NULL, NULL),
(119, '2', 119, 1, NULL, NULL),
(120, '3', 120, 1, NULL, NULL),
(121, '4', 121, 1, NULL, NULL),
(122, '1', 122, 1, NULL, NULL),
(123, '2', 123, 1, NULL, NULL),
(124, '3', 124, 1, NULL, NULL),
(125, '4', 125, 1, NULL, NULL),
(126, '1', 126, 1, NULL, NULL),
(127, '2', 127, 1, NULL, NULL),
(128, '3', 128, 1, NULL, NULL),
(129, '4', 129, 1, NULL, NULL);

-- --------------------------------------------------------


-- --------------------------------------------------------


--
-- Dumping data for table `addon_set_translations`
--

INSERT INTO `addon_set_translations` (`id`, `title`, `addon_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, NULL, NULL),
(2, 'Brocoli', 2, 1, NULL, NULL),
(3, 'Potato', 3, 1, NULL, NULL),
(4, 'Carrot', 4, 1, NULL, NULL),
(5, 'Raddish', 5, 1, NULL, NULL),
(6, 'Apple', 6, 1, NULL, NULL),
(7, 'Mango', 7, 1, NULL, NULL),
(8, 'Banana', 8, 1, NULL, NULL),
(9, 'Papaya', 9, 1, NULL, NULL),
(10, 'Custard', 10, 1, NULL, NULL),
(11, 'Cream', 11, 1, NULL, NULL),
(12, 'Sugarcane juice', 12, 1, NULL, NULL),
(13, 'Wine', 13, 1, NULL, NULL),
(14, 'Pastries', 14, 1, NULL, NULL),
(15, 'pies', 15, 1, NULL, NULL),
(16, 'Cow Full Fat Milk', 16, 1, NULL, NULL),
(17, 'Eggs', 17, 1, NULL, NULL),
(18, 'Cheese', 18, 1, NULL, NULL),
(19, 'Butter', 19, 1, NULL, NULL),
(20, 'Yogurt', 20, 1, NULL, NULL),
(21, 'Ice Cream', 21, 1, NULL, NULL),
(22, 'Brown Bread', 22, 1, NULL, NULL),
(23, 'Choco lava cake', 23, 1, NULL, NULL),
(24, 'Blueberry Muffins', 24, 1, NULL, NULL),
(25, 'Strawberry icecreams', 25, 1, NULL, NULL),
(26, 'Bread & Buns', 26, 1, NULL, NULL),
(27, 'Cake', 27, 1, NULL, NULL),
(28, 'Coffee', 28, 1, NULL, NULL),
(29, 'Health Drinks', 29, 1, NULL, NULL),
(30, 'Cold Drinks', 30, 1, NULL, NULL),
(31, 'Water Soda', 31, 1, NULL, NULL),
(32, 'juice', 32, 1, NULL, NULL),
(33, 'beer', 33, 1, NULL, NULL);


-- --------------------------------------------------------


--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `title`, `image`, `image_banner`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Star Bazar', 'brand/66OaaAjzLjlx9YtLizZHdo3tMnTKs0uSBTW8fkpQ.jpg', 'brand/WnCZ307moDZSnkg7OULAg6Ja80b1kRGy4uI7LQEc.jpg', 2, 1, NULL, '2021-10-08 13:31:32'),
(2, 'Ebucks', 'brand/hcGF2TlOmpdeMO4cc3JBHy7o36lU3FeXiAu5PIoY.png', 'brand/aht5HKl4e6w7bp1CvjPJRIOO9UAblDyqBWbxHVyv.png', 4, 1, NULL, '2021-10-08 13:32:37'),
(3, 'DMart', 'brand/VRQ6GMfWndJMgCFnzgYembz0ncvCAY4fv6kuXgCY.png', 'brand/qUw0OXIjBZKcXEc9MbzjaHGylNX1JyhfNgj7MREm.png', 1, 1, NULL, '2021-10-08 13:30:24'),
(4, 'Wal-Mart', 'brand/YVoj6YtpsAHd5Im8Lo21blqCWT34qby6g3aPGVRf.png', 'brand/sZDK7ZcXokQ9A0TwMCbsnO7DvDpKTPh8M03oXntv.png', 3, 1, NULL, '2021-10-08 13:32:00');

-- --------------------------------------------------------

INSERT INTO `variants` (`id`, `title`, `type`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, 2, NULL, '2021-10-08 13:34:07'),
(2, 'Color', 2, 2, 2, NULL, '2021-10-08 13:34:09'),
(3, 'Phones', 1, 3, 2, NULL, '2021-10-08 13:34:12');
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
(16, 3, 8, NULL, NULL),
(17, 1, 14, NULL, NULL),
(18, 4, 10, NULL, NULL),
(19, 2, 15, NULL, NULL);



-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Dumping data for table `category_translations`
--

INSERT INTO `category_translations` (`id`, `name`, `trans-slug`, `meta_title`, `meta_description`, `meta_keywords`, `category_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'root', '', 'root', '', '', 1, 1, NULL, '2021-09-30 12:04:20'),
(2, 'Delivery', '', 'Delivery', NULL, NULL, 2, 1, NULL, '2021-09-30 12:04:20'),
(3, 'Restaurant', '', 'Restaurant', NULL, NULL, 3, 1, NULL, '2021-09-30 12:04:20'),
(4, 'Supermarket', '', 'Supermarket', NULL, NULL, 4, 1, NULL, '2021-09-30 12:04:20'),
(5, 'Pharmacy', '', 'Pharmacy', NULL, NULL, 5, 1, NULL, '2021-09-30 12:04:20'),
(6, 'Send something', '', 'Send something', '', '', 6, 1, NULL, '2021-09-30 12:04:20'),
(7, 'Buy something', '', 'Buy something', '', '', 7, 1, NULL, '2021-09-30 12:04:20'),
(8, 'Vegetables & Fruits', '', 'Vegetables & Fruits', NULL, NULL, 8, 1, NULL, '2021-10-08 11:48:39'),
(9, 'Fruits', '', 'Fruits', NULL, NULL, 9, 1, NULL, '2021-09-30 12:04:20'),
(10, 'Dairy and Eggs', '', 'Dairy and Eggs', NULL, NULL, 10, 1, NULL, '2021-09-30 12:04:20'),
(11, 'E-Commerce', '', 'E-Commerce', NULL, NULL, 11, 1, NULL, '2021-09-30 12:04:20'),
(12, 'Cloth', '', 'Cloth', NULL, NULL, 12, 1, NULL, '2021-09-30 12:04:20'),
(13, 'Dispatcher', '', 'Dispatcher', NULL, NULL, 13, 1, NULL, '2021-09-30 12:04:20'),
(14, 'Bakery Products', NULL, 'Bakery Products', NULL, NULL, 14, 1, '2021-09-30 10:00:46', '2021-09-30 12:04:20'),
(15, 'Beverages', NULL, 'Beverages', NULL, NULL, 15, 1, '2021-09-30 10:10:54', '2021-09-30 12:04:20');

-- --------------------------------------------------------

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `title`, `url_slug`, `description`, `body_html`, `vendor_id`, `category_id`, `type_id`, `country_origin_id`, `is_new`, `is_featured`, `is_live`, `is_physical`, `weight`, `weight_unit`, `has_inventory`, `has_variant`, `sell_when_out_of_stock`, `requires_shipping`, `Requires_last_mile`, `averageRating`, `inquiry_only`, `publish_at`, `created_at`, `updated_at`, `brand_id`, `tax_category_id`, `deleted_at`, `pharmacy_check`, `tags`, `need_price_from_dispatcher`, `mode_of_service`) VALUES
(1, 'sku-id', '1', 'sku-id', NULL, NULL, 1, NULL, 1, NULL, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(2, 'Veg458', 'Brocoli', 'Veg458', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:31:35', NULL, '2021-10-08 12:36:38', 3, NULL, NULL, 0, NULL, '0', NULL),
(3, 'Veg459', 'Potato', 'Veg459', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:32:37', NULL, '2021-10-08 12:37:06', 3, NULL, NULL, 0, NULL, '0', NULL),
(4, 'Veg460', 'Carrot', 'Veg460', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:33:25', NULL, '2021-10-08 12:37:24', 3, NULL, NULL, 0, NULL, '0', NULL),
(5, 'Veg461', 'Raddish', 'Veg461', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:34:13', NULL, '2021-10-08 12:38:36', 3, NULL, NULL, 0, NULL, '0', NULL),
(6, 'FR5591633695244', 'Apple', 'FR5591633695244', NULL, '', 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:37:26', NULL, '2021-10-08 12:14:04', NULL, NULL, '2021-10-08 12:14:04', 0, NULL, '0', NULL),
(7, 'FR5601633695247', 'Mango', 'FR5601633695247', NULL, '', 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:38:30', NULL, '2021-10-08 12:14:07', NULL, NULL, '2021-10-08 12:14:07', 0, NULL, '0', NULL),
(8, 'FR5611633695250', 'Banana', 'FR5611633695250', NULL, '', 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:39:14', NULL, '2021-10-08 12:14:10', NULL, NULL, '2021-10-08 12:14:10', 0, NULL, '0', NULL),
(9, 'FR5621633695253', 'Papaya', 'FR5621633695253', NULL, '', 5, 9, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:40:04', NULL, '2021-10-08 12:14:13', NULL, NULL, '2021-10-08 12:14:13', 0, NULL, '0', NULL),
(10, 'GS009', 'Cow Full Fat Milk', 'GS009', NULL, '', 4, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:47:20', NULL, '2021-10-08 12:39:53', 4, NULL, NULL, 0, NULL, '0', NULL),
(11, 'GS0101632998471', 'Brown Bread', 'GS0101632998471', NULL, '', 4, 10, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-09-30 10:41:11', NULL, NULL, '2021-09-30 10:41:11', 0, NULL, NULL, NULL),
(12, 'GS011', 'Eggs', 'GS011', NULL, '', 4, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:46:32', NULL, '2021-10-08 12:40:09', 4, NULL, NULL, 0, NULL, '0', NULL),
(13, 'GS012', 'Cheese', 'GS012', NULL, '', 4, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:44:19', NULL, '2021-10-08 12:40:30', 4, NULL, NULL, 0, NULL, '0', NULL),
(14, 'GS0131632998480', 'Choco lava cake', 'GS0131632998480', NULL, '', 4, 10, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-09-30 10:41:20', NULL, NULL, '2021-09-30 10:41:20', 0, NULL, NULL, NULL),
(15, 'GS0141632998497', 'Black Forest', 'GS0141632998497', NULL, '', 4, 10, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-09-30 10:41:37', NULL, NULL, '2021-09-30 10:41:37', 0, NULL, NULL, NULL),
(16, 'GS0151632998492', 'Blueberry Muffins', 'GS0151632998492', NULL, '', 4, 10, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-09-30 10:41:32', NULL, NULL, '2021-09-30 10:41:32', 0, NULL, NULL, NULL),
(17, 'GS0161632998486', 'Strawberry icecreams', 'GS0161632998486', NULL, '', 4, 10, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-09-30 10:41:26', NULL, NULL, '2021-09-30 10:41:26', 0, NULL, NULL, NULL),
(18, 'Butter', NULL, 'butter', NULL, NULL, 4, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:43:10', '2021-09-30 10:42:10', '2021-10-08 12:41:21', 4, NULL, NULL, 0, NULL, '0', NULL),
(19, 'GS010', 'Brown Bread', 'GS010', NULL, '', 3, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:50:31', NULL, '2021-10-08 12:42:30', 1, NULL, NULL, 0, NULL, '0', NULL),
(20, 'GS013', 'Choco lava cake', 'GS013', NULL, '', 3, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:51:27', NULL, '2021-10-08 12:43:37', 1, NULL, NULL, 0, NULL, '0', NULL),
(21, 'GS015', 'Blueberry Muffins', 'GS015', NULL, '', 3, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:52:26', NULL, '2021-10-08 12:44:02', 1, NULL, NULL, 0, NULL, '0', NULL),
(22, 'GS016', 'Strawberry icecreams', 'GS016', NULL, '', 3, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:53:19', NULL, '2021-10-08 12:44:19', 1, NULL, NULL, 0, NULL, '0', NULL),
(23, 'BEV665', 'Tea & Coffee', 'BEV665', NULL, '', 2, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:56:55', NULL, '2021-10-08 12:45:38', 2, NULL, NULL, 0, NULL, '0', NULL),
(24, 'BEV666', 'Health Drinks', 'BEV666', NULL, '', 2, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:58:00', NULL, '2021-10-08 12:45:56', 2, NULL, NULL, 0, NULL, '0', NULL),
(25, 'BEV667', 'Cold Drinks', 'BEV667', NULL, '', 2, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:58:47', NULL, '2021-10-08 12:46:18', 2, NULL, NULL, 0, NULL, '0', NULL),
(26, 'BEV668', 'Water Soda', 'BEV668', NULL, '', 2, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, '4.00', 0, '2021-09-30 10:59:40', NULL, '2021-10-08 12:46:33', 2, NULL, NULL, 0, NULL, '0', NULL),
(27, 'FR123', 'Apple', 'FR123', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 11:54:27', NULL, '2021-10-08 12:38:46', 3, NULL, NULL, 0, NULL, '0', NULL),
(28, 'FR124', 'Mango', 'FR124', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 11:55:04', NULL, '2021-10-08 12:39:02', 3, NULL, NULL, 0, NULL, '0', NULL),
(29, 'FR125', 'Banana', 'FR125', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 11:56:02', NULL, '2021-10-08 12:39:14', 3, NULL, NULL, 0, NULL, '0', NULL),
(30, 'FR126', 'Papaya', 'FR126', NULL, '', 6, 8, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 11:56:48', NULL, '2021-10-08 12:39:23', 3, NULL, NULL, 0, NULL, '0', NULL),
(31, 'HG123', 'Butter', 'HG123', NULL, '', 4, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:24:35', NULL, '2021-10-08 12:40:53', 4, NULL, NULL, 0, NULL, '0', NULL),
(32, 'HG124', 'Ice Cream', 'HG124', NULL, '', 4, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:26:08', NULL, '2021-10-08 12:41:05', 4, NULL, NULL, 0, NULL, '0', NULL),
(33, 'YT123', 'Bread & Buns', 'YT123', NULL, '', 3, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:31:43', NULL, '2021-10-08 12:44:41', 1, NULL, NULL, 0, NULL, '0', NULL),
(34, 'YT124', 'Cake', 'YT124', NULL, '', 3, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:35:08', NULL, '2021-10-08 12:45:05', 1, NULL, NULL, 0, NULL, '0', NULL),
(35, 'LO159', 'juice', 'LO159', NULL, '', 2, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:37:13', NULL, '2021-10-08 12:46:47', 2, NULL, NULL, 0, NULL, '0', NULL),
(36, 'LO160', 'beer', 'LO160', NULL, '', 2, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:39:55', NULL, '2021-10-08 12:47:16', 2, NULL, NULL, 0, NULL, '0', NULL),
(37, 'JO258', 'Custard', 'JO258', NULL, '', 5, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:47:36', NULL, '2021-10-08 13:04:21', 4, NULL, NULL, 0, NULL, '0', NULL),
(38, 'JO259', 'Cream', 'JO259', NULL, '', 5, 10, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:50:01', NULL, '2021-10-08 13:03:56', 4, NULL, NULL, 0, NULL, '0', NULL),
(39, 'JO260', 'Sugarcane juice', 'JO260', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:52:02', NULL, '2021-10-08 13:03:44', 2, NULL, NULL, 0, NULL, '0', NULL),
(40, 'JO261', 'Wine', 'JO261', NULL, '', 5, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:55:24', NULL, '2021-10-08 13:04:53', 2, NULL, NULL, 0, NULL, '0', NULL),
(41, 'JO262', 'pastries', 'JO262', NULL, '', 5, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 12:58:04', NULL, '2021-10-08 13:05:09', 1, NULL, NULL, 0, NULL, '0', NULL),
(42, 'JO263', 'pies', 'JO263', NULL, '', 5, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 1, 0, 0, 0, 1, NULL, 0, '2021-10-08 13:00:44', NULL, '2021-10-11 06:09:07', 1, NULL, NULL, 0, NULL, '0', NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `product_addons`
--

INSERT INTO `product_addons` (`product_id`, `addon_id`, `created_at`, `updated_at`) VALUES
(2, 2, NULL, NULL),
(3, 3, NULL, NULL),
(4, 4, NULL, NULL),
(5, 5, NULL, NULL),
(27, 6, NULL, NULL),
(28, 7, NULL, NULL),
(29, 8, NULL, NULL),
(30, 9, NULL, NULL),
(37, 10, NULL, NULL),
(38, 11, NULL, NULL),
(39, 12, NULL, NULL),
(40, 13, NULL, NULL),
(41, 14, NULL, NULL),
(42, 15, NULL, NULL),
(10, 16, NULL, NULL),
(12, 17, NULL, NULL),
(18, 19, NULL, NULL),
(31, 20, NULL, NULL),
(32, 21, NULL, NULL),
(19, 22, NULL, NULL),
(20, 23, NULL, NULL),
(21, 24, NULL, NULL),
(22, 25, NULL, NULL),
(33, 26, NULL, NULL),
(34, 27, NULL, NULL),
(23, 33, NULL, NULL),
(24, 29, NULL, NULL),
(25, 30, NULL, NULL),
(26, 31, NULL, NULL),
(35, 32, NULL, NULL),
(36, 33, NULL, NULL),
(13, 18, NULL, NULL);
-- --------------------------------------------------------

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, NULL),
(2, 8, NULL, NULL),
(2, 8, NULL, NULL),
(3, 8, NULL, NULL),
(2, 8, NULL, NULL),
(3, 8, NULL, NULL),
(4, 8, NULL, NULL),
(2, 8, NULL, NULL),
(3, 8, NULL, NULL),
(4, 8, NULL, NULL),
(5, 8, NULL, NULL),
(6, 9, NULL, NULL),
(6, 9, NULL, NULL),
(7, 9, NULL, NULL),
(6, 9, NULL, NULL),
(7, 9, NULL, NULL),
(8, 9, NULL, NULL),
(6, 9, NULL, NULL),
(7, 9, NULL, NULL),
(8, 9, NULL, NULL),
(9, 9, NULL, NULL),
(10, 10, NULL, NULL),
(10, 10, NULL, NULL),
(11, 10, NULL, NULL),
(10, 10, NULL, NULL),
(11, 10, NULL, NULL),
(12, 10, NULL, NULL),
(10, 10, NULL, NULL),
(11, 10, NULL, NULL),
(12, 10, NULL, NULL),
(13, 10, NULL, NULL),
(10, 10, NULL, NULL),
(11, 10, NULL, NULL),
(12, 10, NULL, NULL),
(13, 10, NULL, NULL),
(14, 10, NULL, NULL),
(10, 10, NULL, NULL),
(11, 10, NULL, NULL),
(12, 10, NULL, NULL),
(13, 10, NULL, NULL),
(14, 10, NULL, NULL),
(15, 10, NULL, NULL),
(10, 10, NULL, NULL),
(11, 10, NULL, NULL),
(12, 10, NULL, NULL),
(13, 10, NULL, NULL),
(14, 10, NULL, NULL),
(15, 10, NULL, NULL),
(16, 10, NULL, NULL),
(10, 10, NULL, NULL),
(11, 10, NULL, NULL),
(12, 10, NULL, NULL),
(13, 10, NULL, NULL),
(14, 10, NULL, NULL),
(15, 10, NULL, NULL),
(16, 10, NULL, NULL),
(17, 10, NULL, NULL),
(18, 10, '2021-09-30 10:42:10', '2021-09-30 10:42:10'),
(19, 14, NULL, NULL),
(19, 14, NULL, NULL),
(20, 14, NULL, NULL),
(19, 14, NULL, NULL),
(20, 14, NULL, NULL),
(21, 14, NULL, NULL),
(19, 14, NULL, NULL),
(20, 14, NULL, NULL),
(21, 14, NULL, NULL),
(22, 14, NULL, NULL),
(23, 15, NULL, NULL),
(23, 15, NULL, NULL),
(24, 15, NULL, NULL),
(23, 15, NULL, NULL),
(24, 15, NULL, NULL),
(25, 15, NULL, NULL),
(23, 15, NULL, NULL),
(24, 15, NULL, NULL),
(25, 15, NULL, NULL),
(26, 15, NULL, NULL),
(27, 8, NULL, NULL),
(27, 8, NULL, NULL),
(28, 8, NULL, NULL),
(27, 8, NULL, NULL),
(28, 8, NULL, NULL),
(29, 8, NULL, NULL),
(27, 8, NULL, NULL),
(28, 8, NULL, NULL),
(29, 8, NULL, NULL),
(30, 8, NULL, NULL),
(31, 10, NULL, NULL),
(31, 10, NULL, NULL),
(32, 10, NULL, NULL),
(33, 14, NULL, NULL),
(33, 14, NULL, NULL),
(34, 14, NULL, NULL),
(35, 15, NULL, NULL),
(35, 15, NULL, NULL),
(36, 15, NULL, NULL),
(37, 10, NULL, NULL),
(37, 10, NULL, NULL),
(38, 10, NULL, NULL),
(37, 10, NULL, NULL),
(38, 10, NULL, NULL),
(39, 15, NULL, NULL),
(37, 10, NULL, NULL),
(38, 10, NULL, NULL),
(39, 15, NULL, NULL),
(40, 15, NULL, NULL),
(37, 10, NULL, NULL),
(38, 10, NULL, NULL),
(39, 15, NULL, NULL),
(40, 15, NULL, NULL),
(41, 14, NULL, NULL),
(37, 10, NULL, NULL),
(38, 10, NULL, NULL),
(39, 15, NULL, NULL),
(40, 15, NULL, NULL),
(41, 14, NULL, NULL),
(42, 14, NULL, NULL);


-- --------------------------------------------------------

--
-- Dumping data for table `vendor_media`
--

INSERT INTO `vendor_media` (`id`, `media_type`, `vendor_id`, `path`, `created_at`, `updated_at`) VALUES
(5, 1, 5, 'prods/TwEV2yobYyf4sttwvLpaUdzJ4Unjbsb8hHL5S3Yj.webp', '2021-09-30 10:37:17', '2021-09-30 10:37:17'),
(6, 1, 5, 'prods/nQMIjEGcOfmzccODbklL0MF6mTyuf1KaqIfDicPZ.jpg', '2021-09-30 10:38:20', '2021-09-30 10:38:20'),
(7, 1, 5, 'prods/XEzld7HGyhn62qN08fA52HxGYVNnOHbZ8GGulN7w.jpg', '2021-09-30 10:39:04', '2021-09-30 10:39:04'),
(8, 1, 5, 'prods/29CMY7pryCrGgeI01jAoJTYvdo5N8JkhG6LZxc1R.jpg', '2021-09-30 10:39:52', '2021-09-30 10:39:52'),
(49, 1, 2, 'prods/T3tS8II5vPL1PC8eJlZb1IRX2VXN4eXb1hrutxKZ.jpg', '2021-10-11 04:47:46', '2021-10-11 04:47:46'),
(50, 1, 2, 'prods/0y5FEqq59NO0GApbOugltQtytQZHj8qgZaAi1WRi.jpg', '2021-10-11 04:47:59', '2021-10-11 04:47:59'),
(59, 1, 6, 'prods/NJNss9tE5Yghk9GA3sRvDMb9bh119qOr0VWVIJRw.jpg', '2021-10-11 05:36:17', '2021-10-11 05:36:17'),
(60, 1, 6, 'prods/ncs5tdexXWZjWhG4FKQ9HiJrNDFEkkKpaHvx2XVL.jpg', '2021-10-11 05:37:35', '2021-10-11 05:37:35'),
(61, 1, 6, 'prods/5PFloJ392ptgc7McWuPkoAVneMOqLzeilpJ0HTNa.jpg', '2021-10-11 05:39:52', '2021-10-11 05:39:52'),
(62, 1, 6, 'prods/JeEcttdQldj8WljYW6z6o2DXwuhvtrPVIneL6alg.jpg', '2021-10-11 05:41:30', '2021-10-11 05:41:30'),
(63, 1, 6, 'prods/tvVgbwe5XeRuM5z4chXzrLVo6jsz8ckLNZupEyVg.jpg', '2021-10-11 05:42:02', '2021-10-11 05:42:02'),
(64, 1, 6, 'prods/j9V6qQ4SPFdAMKfxMSfhVxtqscLjIxA6sZ7N4eNe.jpg', '2021-10-11 05:42:27', '2021-10-11 05:42:27'),
(65, 1, 6, 'prods/9tigQDWudmMJ4FqTZxJuOkG4t08agzvA1As0kBsU.jpg', '2021-10-11 05:43:58', '2021-10-11 05:43:58'),
(66, 1, 6, 'prods/E5ZDMeTHrLGJInDXps34h5QgIASdOePWB4Pek4dO.jpg', '2021-10-11 05:45:32', '2021-10-11 05:45:32'),
(67, 1, 6, 'prods/ARbtjG0ZKxFs7AzTJHE0ePa0bkDLQ5K0ZqJL6nCN.jpg', '2021-10-11 05:47:16', '2021-10-11 05:47:16'),
(68, 1, 6, 'prods/FSGcCO5FcltJuzUWXga4zGmc3qloMb9Vq6wsp6oF.jpg', '2021-10-11 05:48:54', '2021-10-11 05:48:54'),
(69, 1, 5, 'prods/DvwGIy0tSsHV7SCHTJCSOJsY5nB6vvLu16e4xXjK.jpg', '2021-10-11 05:56:39', '2021-10-11 05:56:39'),
(70, 1, 5, 'prods/G3sgCgAhINQggjzp4rwmJojcEWYRuuSIui5Pvdqz.jpg', '2021-10-11 05:58:08', '2021-10-11 05:58:08'),
(71, 1, 5, 'prods/7EghzEKUj6aHfwmVxyXXjVUbFjD09qnV4dXKSBWX.jpg', '2021-10-11 06:02:46', '2021-10-11 06:02:46'),
(72, 1, 5, 'prods/V6B81G385pGoHE7GYn5A8QYqVF9qrzvpZoEVclDb.jpg', '2021-10-11 06:05:43', '2021-10-11 06:05:43'),
(73, 1, 5, 'prods/CnBi4F9G9peposRbqwOGHLRRfhQL0MTDi5LY6bOJ.jpg', '2021-10-11 06:07:40', '2021-10-11 06:07:40'),
(74, 1, 5, 'prods/yGouNdk7wMRGJazp17vNNZMEMjEzdfvRrH9lH3oz.jpg', '2021-10-11 06:09:02', '2021-10-11 06:09:02'),
(75, 1, 4, 'prods/1rFzw78LRCr3Yvtpme4vQseB7OgZSFzdILJ19mhy.jpg', '2021-10-11 06:10:43', '2021-10-11 06:10:43'),
(76, 1, 4, 'prods/x2FG6aZ0c5us23T1BTQY5hKyXt7s64o4mWgzMPOB.jpg', '2021-10-11 06:11:57', '2021-10-11 06:11:57'),
(77, 1, 4, 'prods/I7pSd6SnPEKtfvVzLqzF6wchIKnd5oRYC6fpQgn5.jpg', '2021-10-11 06:13:09', '2021-10-11 06:13:09'),
(78, 1, 4, 'prods/5Rm3bL5khq0X2heNVAuW3q6SKIqOHROCUlb2o8Zz.jpg', '2021-10-11 06:14:55', '2021-10-11 06:14:55'),
(79, 1, 4, 'prods/BKZrUFvjPHD1cVI02NN5UX0GofAtavzJg9xSpHYw.jpg', '2021-10-11 06:18:18', '2021-10-11 06:18:18'),
(80, 1, 3, 'prods/GXyvmPOTQCm0cLHXi0zHBQ9rwldqtO3jU87klPHv.jpg', '2021-10-11 06:19:22', '2021-10-11 06:19:22'),
(81, 1, 3, 'prods/K3lxw7t6DYu0g3K308Ve6oaRKBSrFEIyJiOsip4u.jpg', '2021-10-11 06:20:49', '2021-10-11 06:20:49'),
(82, 1, 3, 'prods/DrvRmPwgcdJuhPHtu1FAItfLecPSAH9CC0zwQOtD.jpg', '2021-10-11 06:22:02', '2021-10-11 06:22:02'),
(83, 1, 3, 'prods/a8N5cKVXQ1Gj8Bm9OnTOZaEG60Ldz7Iq6KHESwZz.jpg', '2021-10-11 06:23:30', '2021-10-11 06:23:30'),
(84, 1, 3, 'prods/Jz0mjTyealh0KgeUGLFzgCnkFo6HYcwJcrY0WXYw.jpg', '2021-10-11 06:24:51', '2021-10-11 06:24:51'),
(85, 1, 3, 'prods/H8qXnxWQmW0h1qNEtfrhjcWv8HpKrjP8HygTTKkH.jpg', '2021-10-11 06:27:22', '2021-10-11 06:27:22'),
(86, 1, 2, 'prods/7rODIqd3G2EMhMGRUGCqWMyGaHOrW5wVkxVSKPyr.jpg', '2021-10-11 06:29:09', '2021-10-11 06:29:09'),
(87, 1, 2, 'prods/Q9wPcCuNFVFRS0UvtUZak7YF1CaY282kpINmYxi9.jpg', '2021-10-11 06:30:26', '2021-10-11 06:30:26'),
(88, 1, 2, 'prods/TD5cIpbBGPDx2FN2ijeG0g0vWY9uieKTUVjdGMQ4.jpg', '2021-10-11 06:31:47', '2021-10-11 06:31:47'),
(89, 1, 2, 'prods/EKM23dODzbF1tjthLrmxXmxjUsRj3LppRQ68OBUd.jpg', '2021-10-11 06:36:20', '2021-10-11 06:36:20'),
(90, 1, 2, 'prods/Sk5J0zxgCuW40IRgtKcvsERphLRx9tOTou4xzSZV.jpg', '2021-10-11 06:37:56', '2021-10-11 06:37:56'),
(91, 1, 2, 'prods/clnZ14BQoydNzxieEpx3K99TS661uHLnYehyzGdf.jpg', '2021-10-11 06:39:56', '2021-10-11 06:39:56'),
(92, 1, 4, 'prods/SJNuUvCPg4y59yD2rljTzlV2NxyS4Rj35Iyv7BCc.jpg', '2021-10-11 06:43:25', '2021-10-11 06:43:25');

-- --------------------------------------------------------
--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `media_id`, `is_default`, `created_at`, `updated_at`) VALUES
(5, 6, 5, 1, NULL, NULL),
(6, 7, 6, 1, NULL, NULL),
(7, 8, 7, 1, NULL, NULL),
(8, 9, 8, 1, NULL, NULL),
(57, 2, 59, 1, NULL, NULL),
(58, 3, 60, 1, NULL, NULL),
(59, 4, 63, 1, NULL, NULL),
(60, 5, 64, 1, NULL, NULL),
(61, 27, 65, 1, NULL, NULL),
(62, 28, 66, 1, NULL, NULL),
(63, 29, 67, 1, NULL, NULL),
(64, 30, 68, 1, NULL, NULL),
(65, 37, 69, 1, NULL, NULL),
(66, 38, 70, 1, NULL, NULL),
(67, 39, 71, 1, NULL, NULL),
(68, 40, 72, 1, NULL, NULL),
(69, 41, 73, 1, NULL, NULL),
(70, 42, 74, 1, NULL, NULL),
(71, 10, 75, 1, NULL, NULL),
(72, 12, 76, 1, NULL, NULL),
(73, 18, 77, 1, NULL, NULL),
(74, 31, 78, 1, NULL, NULL),
(75, 32, 79, 1, NULL, NULL),
(76, 19, 80, 1, NULL, NULL),
(77, 20, 81, 1, NULL, NULL),
(78, 21, 82, 1, NULL, NULL),
(79, 22, 83, 1, NULL, NULL),
(80, 33, 84, 1, NULL, NULL),
(81, 34, 85, 1, NULL, NULL),
(82, 23, 86, 1, NULL, NULL),
(83, 24, 87, 1, NULL, NULL),
(84, 25, 88, 1, NULL, NULL),
(85, 26, 89, 1, NULL, NULL),
(86, 35, 90, 1, NULL, NULL),
(87, 36, 91, 1, NULL, NULL),
(88, 13, 92, 1, NULL, NULL);


-- --------------------------------------------------------

--
-- Dumping data for table `product_translations`
--

INSERT INTO `product_translations` (`id`, `title`, `body_html`, `meta_title`, `meta_keyword`, `meta_description`, `product_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Xiaomi', NULL, 'Xiaomi', 'Xiaomi', NULL, 1, 1, NULL, '2021-09-30 12:04:20'),
(2, 'Brocoli', '<p>Broccoli is a fast-growing annual plant&nbsp;that grows 60&ndash;90 cm (24&ndash;35 inches) tall. Upright and branching with leathery leaves, broccoli bears dense green clusters of flower buds at the ends of the central axis and the branches.</p>', NULL, NULL, NULL, 2, 1, NULL, '2021-10-08 12:42:32'),
(3, 'Brocoli', '', '', '', '', 2, 1, NULL, '2021-09-30 12:04:20'),
(4, 'Potato', '<p>Potato is&nbsp;one of some 150 tuber-bearing species of the genus Solanum. The compound leaves are spirally arranged; each leaf is 20&ndash;30 cm (about 8&ndash;12 inches) long and consists of a terminal leaflet and two to four pairs of leaflets.</p>', NULL, NULL, NULL, 3, 1, NULL, '2021-10-11 05:38:01'),
(5, 'Brocoli', '', '', '', '', 2, 1, NULL, '2021-09-30 12:04:20'),
(6, 'Potato', '', '', '', '', 3, 1, NULL, '2021-09-30 12:04:20'),
(7, 'Carrot', '<p>Carrot, (Daucus carota), herbaceous, generally biennial plant of the Apiaceae family that produces an edible taproot.</p>', NULL, NULL, NULL, 4, 1, NULL, '2021-10-08 12:44:35'),
(8, 'Brocoli', '', '', '', '', 2, 1, NULL, '2021-09-30 12:04:20'),
(9, 'Potato', '', '', '', '', 3, 1, NULL, '2021-09-30 12:04:20'),
(10, 'Carrot', '', '', '', '', 4, 1, NULL, '2021-09-30 12:04:20'),
(11, 'Raddish', '<p>Radish&nbsp;annual or biennial plant in the mustard family&nbsp; grown for its large succulent taproot. ... Radish roots are low in calories and are usually eaten raw; the young leaves can be cooked like spinach.</p>', NULL, NULL, NULL, 5, 1, NULL, '2021-10-08 13:02:18'),
(12, 'Apple', NULL, NULL, NULL, NULL, 6, 1, NULL, '2021-09-30 12:04:20'),
(13, 'Apple', '', '', '', '', 6, 1, NULL, '2021-09-30 12:04:20'),
(14, 'Mango', NULL, NULL, NULL, NULL, 7, 1, NULL, '2021-09-30 12:04:20'),
(15, 'Apple', '', '', '', '', 6, 1, NULL, '2021-09-30 12:04:20'),
(16, 'Mango', '', '', '', '', 7, 1, NULL, '2021-09-30 12:04:20'),
(17, 'Banana', NULL, NULL, NULL, NULL, 8, 1, NULL, '2021-09-30 12:04:20'),
(18, 'Apple', '', '', '', '', 6, 1, NULL, '2021-09-30 12:04:20'),
(19, 'Mango', '', '', '', '', 7, 1, NULL, '2021-09-30 12:04:20'),
(20, 'Banana', '', '', '', '', 8, 1, NULL, '2021-09-30 12:04:20'),
(21, 'Papaya', NULL, NULL, NULL, NULL, 9, 1, NULL, '2021-09-30 12:04:20'),
(22, 'Cow Full Fat Milk', '<p>One full cup of whole milk has&nbsp;3.25 % of milk fat&nbsp;and is also good source of vitamin D. It has 149 calories and about 7.9 g fat and 4.6 g of saturated fat. Whole milk contains 7.7 g protein and 11.7 g of carbohydrates.</p>', NULL, NULL, NULL, 10, 1, NULL, '2021-10-08 13:21:39'),
(23, 'Cow Full Fat Milk', '', '', '', '', 10, 1, NULL, '2021-09-30 12:04:20'),
(24, 'Brown Bread', '', '', '', '', 11, 1, NULL, '2021-09-30 12:04:20'),
(25, 'Cow Full Fat Milk', '', '', '', '', 10, 1, NULL, '2021-09-30 12:04:20'),
(26, 'Brown Bread', '', '', '', '', 11, 1, NULL, '2021-09-30 12:04:20'),
(27, 'Eggs', '<p>Eggs have&nbsp;<strong>a </strong>hard shell of calcium carbonate enclosing a liquid white, a single yolk and an air cell.&nbsp;</p>', NULL, NULL, NULL, 12, 1, NULL, '2021-10-08 13:22:39'),
(28, 'Cow Full Fat Milk', '', '', '', '', 10, 1, NULL, '2021-09-30 12:04:20'),
(29, 'Brown Bread', '', '', '', '', 11, 1, NULL, '2021-09-30 12:04:20'),
(30, 'Eggs', '', '', '', '', 12, 1, NULL, '2021-09-30 12:04:20'),
(31, 'Cheese', '<p>Cheese is a dairy product,&nbsp;derived from milk&nbsp;and produced in wide ranges of flavors, textures and forms by coagulation of the milk protein casein. It comprises proteins and fat from milk, usually the milk of cows, buffalo, goats, or sheep.</p>', NULL, NULL, NULL, 13, 1, NULL, '2021-10-08 13:23:11'),
(32, 'Cow Full Fat Milk', '', '', '', '', 10, 1, NULL, '2021-09-30 12:04:20'),
(33, 'Brown Bread', '', '', '', '', 11, 1, NULL, '2021-09-30 12:04:20'),
(34, 'Eggs', '', '', '', '', 12, 1, NULL, '2021-09-30 12:04:20'),
(35, 'Cheese', '', '', '', '', 13, 1, NULL, '2021-09-30 12:04:20'),
(36, 'Choco lava cake', '', '', '', '', 14, 1, NULL, '2021-09-30 12:04:20'),
(37, 'Cow Full Fat Milk', '', '', '', '', 10, 1, NULL, '2021-09-30 12:04:20'),
(38, 'Brown Bread', '', '', '', '', 11, 1, NULL, '2021-09-30 12:04:20'),
(39, 'Eggs', '', '', '', '', 12, 1, NULL, '2021-09-30 12:04:20'),
(40, 'Cheese', '', '', '', '', 13, 1, NULL, '2021-09-30 12:04:20'),
(41, 'Choco lava cake', '', '', '', '', 14, 1, NULL, '2021-09-30 12:04:20'),
(42, 'Black Forest', '', '', '', '', 15, 1, NULL, '2021-09-30 12:04:20'),
(43, 'Cow Full Fat Milk', '', '', '', '', 10, 1, NULL, '2021-09-30 12:04:20'),
(44, 'Brown Bread', '', '', '', '', 11, 1, NULL, '2021-09-30 12:04:20'),
(45, 'Eggs', '', '', '', '', 12, 1, NULL, '2021-09-30 12:04:20'),
(46, 'Cheese', '', '', '', '', 13, 1, NULL, '2021-09-30 12:04:20'),
(47, 'Choco lava cake', '', '', '', '', 14, 1, NULL, '2021-09-30 12:04:20'),
(48, 'Black Forest', '', '', '', '', 15, 1, NULL, '2021-09-30 12:04:20'),
(49, 'Blueberry Muffins', '', '', '', '', 16, 1, NULL, '2021-09-30 12:04:20'),
(50, 'Cow Full Fat Milk', '', '', '', '', 10, 1, NULL, '2021-09-30 12:04:20'),
(51, 'Brown Bread', '', '', '', '', 11, 1, NULL, '2021-09-30 12:04:20'),
(52, 'Eggs', '', '', '', '', 12, 1, NULL, '2021-09-30 12:04:20'),
(53, 'Cheese', '', '', '', '', 13, 1, NULL, '2021-09-30 12:04:20'),
(54, 'Choco lava cake', '', '', '', '', 14, 1, NULL, '2021-09-30 12:04:20'),
(55, 'Black Forest', '', '', '', '', 15, 1, NULL, '2021-09-30 12:04:20'),
(56, 'Blueberry Muffins', '', '', '', '', 16, 1, NULL, '2021-09-30 12:04:20'),
(57, 'Strawberry icecreams', '', '', '', '', 17, 1, NULL, '2021-09-30 12:04:20'),
(58, 'Butter', '<p>Butter, a&nbsp;yellow-to-white solid emulsion of fat globules, water, and inorganic salts produced by churning the cream from cows&#39; milk.&nbsp;Butter is a high-energy food, containing approximately 715 calories per 100 grams. It has a high content of butterfat, or milk fat but is low in protein.</p>', NULL, NULL, NULL, 18, 1, NULL, '2021-10-08 12:41:07'),
(59, 'Brown Bread', '<p>Brown bread is&nbsp;bread made with significant amounts of whole grain flour, usually wheat, and sometimes dark-coloured ingredients such as molasses or coffee&nbsp;In some regions of the US, brown bread is called wheat bread to complement white bread.</p>', NULL, NULL, NULL, 19, 1, NULL, '2021-10-08 13:24:53'),
(60, 'Brown Bread', '', '', '', '', 19, 1, NULL, '2021-09-30 12:04:20'),
(61, 'Choco lava cake', '<p>Chocolate cake&nbsp;is a popular dessert that combines the elements of a&nbsp;chocolate cake&nbsp;and a&nbsp;&nbsp;Its name derives from the dessert&#39;s liquid chocolate center,&nbsp;and it is also known as&nbsp;chocolate&nbsp;chocolate lava cake, or simply&nbsp;lava cake</p>', NULL, NULL, NULL, 20, 1, NULL, '2021-10-08 13:26:41'),
(62, 'Brown Bread', '', '', '', '', 19, 1, NULL, '2021-09-30 12:04:20'),
(63, 'Choco lava cake', '', '', '', '', 20, 1, NULL, '2021-09-30 12:04:20'),
(64, 'Blueberry Muffins', '<p>Blueberry muffin syndrome&#39; is the descriptive term used&nbsp;when an infant is born with multiple blue/purple marks or nodules in the skin. These are due to the presence of clusters of blood-producing cells in the skin.</p>', NULL, NULL, NULL, 21, 1, NULL, '2021-10-08 13:28:21'),
(65, 'Brown Bread', '', '', '', '', 19, 1, NULL, '2021-09-30 12:04:20'),
(66, 'Choco lava cake', '', '', '', '', 20, 1, NULL, '2021-09-30 12:04:20'),
(67, 'Blueberry Muffins', '', '', '', '', 21, 1, NULL, '2021-09-30 12:04:20'),
(68, 'Strawberry icecreams', '<p>Strawberry ice cream is a&nbsp;flavor of ice cream made with strawberry or strawberry flavoring. It is made by blending in fresh strawberries or strawberry flavoring with the eggs, cream, vanilla and sugar used to make ice cream</p>', NULL, NULL, NULL, 22, 1, NULL, '2021-10-08 13:28:09'),
(69, 'Coffee', '<p>Coffee is&nbsp;darkly colored, bitter, slightly acidic and has a stimulating effect in humans, primarily due to its caffeine content.</p>', NULL, NULL, NULL, 23, 1, NULL, '2021-10-08 13:30:05'),
(70, 'Tea & Coffee', '', '', '', '', 23, 1, NULL, '2021-09-30 12:04:20'),
(71, 'Health Drinks', '<p>Health Drink&nbsp;that claims to be beneficial to health.&nbsp;The Tea Board&#39;s other initiative&nbsp;is to push consumption of teas by promoting the brew as a health drink.</p>', NULL, NULL, NULL, 24, 1, NULL, '2021-10-08 13:31:17'),
(72, 'Tea & Coffee', '', '', '', '', 23, 1, NULL, '2021-09-30 12:04:20'),
(73, 'Health Drinks', '', '', '', '', 24, 1, NULL, '2021-09-30 12:04:20'),
(74, 'Cold Drinks', '<p>Soft drink, any of a&nbsp;class of nonalcoholic beverages, usually but not necessarily carbonated, normally containing a natural or artificial sweetening agent, edible acids, natural or artificial flavours, and sometimes juice. Natural flavours are derived from fruits, nuts, berries, roots, herbs, and other plant sources.</p>', NULL, NULL, NULL, 25, 1, NULL, '2021-10-08 13:32:31'),
(75, 'Tea & Coffee', '', '', '', '', 23, 1, NULL, '2021-09-30 12:04:20'),
(76, 'Health Drinks', '', '', '', '', 24, 1, NULL, '2021-09-30 12:04:20'),
(77, 'Cold Drinks', '', '', '', '', 25, 1, NULL, '2021-09-30 12:04:20'),
(78, 'Water Soda', '<p>&nbsp;</p>\r\n\r\n<p>Water Soda is Carbonated water, sparkling water, bubbly water, and fizzy water are umbrella terms describing&nbsp;water that has been pressurized with carbon dioxide gas to produce effervescence,&nbsp;</p>', NULL, NULL, NULL, 26, 1, NULL, '2021-10-08 13:33:30'),
(79, 'Apple', '<p>Apple is a&nbsp;pome fruit, in which the ripened ovary and surrounding tissue both become fleshy and edible. When harvested, apples are usually roundish, 5&ndash;10 cm (2&ndash;4 inches) in diameter, and some shade of red, green, or yellow in colour; they vary in size, shape, and acidity depending on the variety.</p>', NULL, NULL, NULL, 27, 1, NULL, '2021-10-11 04:56:47'),
(80, 'Apple', '', '', '', '', 27, 1, NULL, NULL),
(81, 'Mango', '<p>Mango is a tropical usually large ovoid or oblong fruit with a firm yellowish-red skin, hard central stone, and juicy aromatic pulp&nbsp;also&nbsp; an evergreen tree of the cashew family that bears mangoes.</p>', NULL, NULL, NULL, 28, 1, NULL, '2021-10-11 05:45:45'),
(82, 'Apple', '', '', '', '', 27, 1, NULL, NULL),
(83, 'Mango', '', '', '', '', 28, 1, NULL, NULL),
(84, 'Banana', '<p>A banana is&nbsp;a curved, yellow fruit with a thick skin and soft sweet flesh. A banana is a tropical fruit that&#39;s quite popular all over the world.</p>', NULL, NULL, NULL, 29, 1, NULL, '2021-10-11 05:01:47'),
(85, 'Apple', '', '', '', '', 27, 1, NULL, NULL),
(86, 'Mango', '', '', '', '', 28, 1, NULL, NULL),
(87, 'Banana', '', '', '', '', 29, 1, NULL, NULL),
(88, 'Papaya', '<p>Papaya, also called papaw or pawpaw,&nbsp;succulent fruit of a large plant of the family Caricaceae. It is a popular breakfast fruit in many countries and is also used in salads, pies, sherbets, juices, and confections.</p>', NULL, NULL, NULL, 30, 1, NULL, '2021-10-11 05:48:59'),
(89, 'Yogurt', '<p>Butter a&nbsp;yellow-to-white solid emulsion of fat globules, water, and inorganic salts produced by churning the cream from cows&#39; milk. Butter is a high-energy food, containing approximately 715 calories per 100 grams.</p>', NULL, NULL, NULL, 31, 1, NULL, '2021-10-08 12:25:31'),
(90, 'Butter', '', '', '', '', 31, 1, NULL, NULL),
(91, 'Ice Cream', '<p>Ice Cream A rich, sweet, creamy frozen food made&nbsp;from variously flavored cream and milk products churned or stirred to a smooth consistency during the freezing process and often containing gelatin, eggs, fruits, nuts, etc</p>', NULL, NULL, NULL, 32, 1, NULL, '2021-10-08 12:26:08'),
(92, 'Bread & Buns', '<p>Bread &amp; Buns is Slightly moist, tender and Moist, tender and light Soft, springy texture, flaky crumb, with a medium crumb, with medium fine, tender and slightly moist fine grain.</p>', NULL, NULL, NULL, 33, 1, NULL, '2021-10-08 12:31:43'),
(93, 'Bread & Buns', '', '', '', '', 33, 1, NULL, NULL),
(94, 'Cake', '<p>Cake is&nbsp;a sweet food made by baking a mixture of flour, eggs, sugar, and fat in an oven. Cakes may be large and cut into slices or small and intended for one person only. Food that is formed into flat round shapes before it is cooked can be referred to as cakes.</p>', NULL, NULL, NULL, 34, 1, NULL, '2021-10-08 12:35:08'),
(95, 'juice', '<p>Juice is&nbsp;a drink made from the extraction or pressing of the natural liquid contained in fruit and vegetables. It can also refer to liquids that are flavored with concentrate or other biological food sources, such as meat or seafood, such as clam juice</p>', NULL, NULL, NULL, 35, 1, NULL, '2021-10-08 12:37:13'),
(96, 'juice', '', '', '', '', 35, 1, NULL, NULL),
(97, 'Beer', '<p>Beer is a carbonated, fermented alcoholic beverage&nbsp;that is usually made from malted cereal grain , is flavored with hops, and typically contains less than a 5% alcohol content compare ale, craft beer, lager entry 1 malt liquor sense 2.</p>', NULL, NULL, NULL, 36, 1, NULL, '2021-10-11 06:39:59'),
(98, 'Custard', '<p>Custard is&nbsp;a sweet, pudding-like dessert that&#39;s usually made with eggs. Baked custard is made with a combination of eggs, milk or cream, sugar, and sometimes flavoring, chocolate, or spices, that&#39;s cooked in small dishes sitting in a pan of water. The result is smooth, creamy, and rich.</p>', NULL, NULL, NULL, 37, 1, NULL, '2021-10-08 12:47:36'),
(99, 'Custard', '', '', '', '', 37, 1, NULL, NULL),
(100, 'Cream', '<p>Cream is&nbsp;a dairy product composed of the higher-fat layer skimmed from the top of milk before homogenization. In un-homogenized milk, the fat, which is less dense, eventually rises to the top.</p>', NULL, NULL, NULL, 38, 1, NULL, '2021-10-08 12:50:01'),
(101, 'Custard', '', '', '', '', 37, 1, NULL, NULL),
(102, 'Cream', '', '', '', '', 38, 1, NULL, NULL),
(103, 'Sugarcane juice', '<p>Sugarcane juice is the&nbsp;opaque and viscous liquid, brownish to deep-green in colour, obtained by pressing sugarcane stalks. Sugarcane juice is mainly processed into sugar, but part of the production goes to human consumption as fresh juice or alcohol .</p>', NULL, NULL, NULL, 39, 1, NULL, '2021-10-08 12:52:02'),
(104, 'Custard', '', '', '', '', 37, 1, NULL, NULL),
(105, 'Cream', '', '', '', '', 38, 1, NULL, NULL),
(106, 'Sugarcane juice', '', '', '', '', 39, 1, NULL, NULL),
(107, 'Wine', '<p>Wine is&nbsp;fermented grape juice. This is a secondary fermentation in which naturally occurring malic acid changes into lactic acid . Many wines described as buttery or creamy have gone through the process of Malo.</p>', NULL, NULL, NULL, 40, 1, NULL, '2021-10-08 12:55:41'),
(108, 'Custard', '', '', '', '', 37, 1, NULL, NULL),
(109, 'Cream', '', '', '', '', 38, 1, NULL, NULL),
(110, 'Sugarcane juice', '', '', '', '', 39, 1, NULL, NULL),
(111, 'Wine', '', '', '', '', 40, 1, NULL, NULL),
(112, 'Pastries', '<p>Pastries are&nbsp;shortcrust pastry, filo pastry, choux pastry, flaky pastry, rough puff pastry, suet crust pastry and puff pastry.</p>', NULL, NULL, NULL, 41, 1, NULL, '2021-10-08 13:08:34'),
(113, 'Custard', '', '', '', '', 37, 1, NULL, NULL),
(114, 'Cream', '', '', '', '', 38, 1, NULL, NULL),
(115, 'Sugarcane juice', '', '', '', '', 39, 1, NULL, NULL),
(116, 'Wine', '', '', '', '', 40, 1, NULL, NULL),
(117, 'pastries', '', '', '', '', 41, 1, NULL, NULL),
(118, 'pies', '<p>A pie is&nbsp;a baked dish which&nbsp;is usually made of a pastry dough casing that contains a filling of various sweet or savoury ingredients.&nbsp; Savoury pies may be filled with meat.</p>', NULL, NULL, NULL, 42, 1, NULL, '2021-10-08 13:00:45');

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
(6, 'Veg458', 2, NULL, 200, '5.00', 1, '6.00', '24028af82a4ad6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:30:27', '2021-09-30 10:31:43', 1),
(7, 'Veg459', 3, NULL, 240, '6.00', 1, '7.00', 'fc97d7534bf02c', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:30:27', '2021-09-30 10:32:45', 1),
(8, 'Veg460', 4, NULL, 150, '12.00', 1, '13.00', '7ddbbcce07d2ae', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:30:27', '2021-09-30 10:33:33', 1),
(9, 'Veg461', 5, NULL, 220, '14.00', 1, '16.00', '7103dda653f572', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:30:27', '2021-09-30 10:34:21', 1),
(10, 'FR55916336952449873dce', 6, NULL, 200, '10.00', 1, '12.00', 'cbbb559603979a', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:36:24', '2021-10-08 12:14:04', 1),
(11, 'FR56016336952471101f63', 7, NULL, 220, '10.00', 1, '12.00', '8e6e2e3022601c', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:36:24', '2021-10-08 12:14:07', 1),
(12, 'FR56116336952504162d9e', 8, NULL, 240, '13.00', 1, '14.00', '96922d95bc003b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:36:24', '2021-10-08 12:14:10', 1),
(13, 'FR562163369525356e2663', 9, NULL, 230, '11.00', 1, '12.00', 'd7369b5f4a87d0', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:36:24', '2021-10-08 12:14:13', 1),
(14, 'GS009', 10, NULL, 260, '11.00', 1, '13.00', '449c8c754db97d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:47:28', 1),
(15, 'GS01016329984710c5e0ba', 11, NULL, 0, '0.00', 1, '0.00', 'b0a4c3359f0780', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:41:11', 1),
(16, 'GS011', 12, NULL, 180, '6.00', 1, '7.00', 'cdfad77e6a10bd', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:46:42', 1),
(17, 'GS012', 13, NULL, 260, '13.00', 1, '14.00', 'ef3e5c08137970', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:44:27', 1),
(18, 'GS0131632998480a7ea6c1', 14, NULL, 0, '0.00', 1, '0.00', '7843344079bce0', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:41:20', 1),
(19, 'GS0141632998497b2696df', 15, NULL, 0, '0.00', 1, '0.00', '694f44124dc2ab', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:41:37', 1),
(20, 'GS0151632998492f62131c', 16, NULL, 0, '0.00', 1, '0.00', 'cbd8f9a31da339', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:41:32', 1),
(21, 'GS01616329984866e0ccbf', 17, NULL, 0, '0.00', 1, '0.00', '1d481f6adc1bf2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:41:01', '2021-09-30 10:41:26', 1),
(22, 'Butter', 18, NULL, 200, '10.00', 1, '12.00', '2c234788a85abb', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:42:10', '2021-09-30 10:43:18', 1),
(23, 'GS010', 19, NULL, 180, '10.00', 1, '12.00', '890546148dcacd', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:49:46', '2021-09-30 10:50:39', 1),
(24, 'GS013', 20, NULL, 240, '12.00', 1, '14.00', '63545f553b6d26', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:49:46', '2021-09-30 10:51:36', 1),
(25, 'GS015', 21, NULL, 250, '10.00', 1, '12.00', '5c1565819ccfc8', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:49:46', '2021-10-08 12:44:02', 1),
(26, 'GS016', 22, NULL, 230, '9.00', 1, '11.00', '0e9f236fbea56e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:49:46', '2021-09-30 10:53:30', 1),
(27, 'BEV665', 23, NULL, 240, '8.00', 1, '10.00', 'bcc0a594a92334', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:55:43', '2021-09-30 10:57:05', 1),
(28, 'BEV666', 24, NULL, 230, '11.00', 1, '12.00', '469f67cc3ea43e', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:55:43', '2021-09-30 10:58:08', 1),
(29, 'BEV667', 25, NULL, 260, '12.00', 1, '13.00', 'efb1226432ae48', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:55:43', '2021-09-30 10:59:02', 1),
(30, 'BEV668', 26, NULL, 270, '12.00', 1, '13.00', 'eb451a04e12685', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-30 10:55:43', '2021-09-30 11:00:15', 1),
(31, 'FR123', 27, NULL, 180, '10.00', 1, '12.00', '3d7644a0a166b2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 11:52:08', '2021-10-08 11:54:27', 1),
(32, 'FR124', 28, NULL, 250, '8.00', 1, '10.00', '7fd5f5e2fdcd73', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 11:52:08', '2021-10-08 11:55:04', 1),
(33, 'FR125', 29, NULL, 220, '7.00', 1, '9.00', '9b7bafb3b34975', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 11:52:08', '2021-10-08 11:56:02', 1),
(34, 'FR126', 30, NULL, 240, '12.00', 1, '14.00', '38e3c5b79068de', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 11:52:08', '2021-10-08 11:56:48', 1),
(35, 'HG123', 31, NULL, 1990, '17.00', 1, '20.00', '77e443a89b7f68', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:18:14', '2021-10-08 12:24:35', 1),
(36, 'HG124', 32, NULL, 1889, '12.00', 1, '15.00', 'f5aef29b442493', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:18:14', '2021-10-08 12:26:08', 1),
(37, 'YT123', 33, NULL, 1990, '18.00', 1, '21.00', 'eb82e7d163dc33', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:20:40', '2021-10-08 12:31:43', 1),
(38, 'YT124', 34, NULL, 1999, '19.00', 1, '21.00', '69f049fdb006d3', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:20:40', '2021-10-08 12:35:09', 1),
(39, 'LO159', 35, NULL, 1890, '15.00', 1, '19.00', 'd492f6b0c90e54', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:22:40', '2021-10-08 12:37:13', 1),
(40, 'LO160', 36, NULL, 1999, '19.00', 1, '21.00', 'c407dd7c46d4ab', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:22:40', '2021-10-08 12:39:56', 1),
(41, 'JO258', 37, NULL, 1990, '18.00', 1, '21.00', '33af4d34d78bee', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:34:13', '2021-10-08 12:47:36', 1),
(42, 'JO259', 38, NULL, 1900, '18.00', 1, '22.00', '0be6b995465890', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:34:13', '2021-10-08 12:50:01', 1),
(43, 'JO260', 39, NULL, 1900, '12.00', 1, '15.00', 'e56368094132d6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:34:13', '2021-10-08 12:52:02', 1),
(44, 'JO261', 40, NULL, 1990, '12.00', 1, '14.00', '864a9b6f9a935a', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:34:13', '2021-10-08 12:55:24', 1),
(45, 'JO262', 41, NULL, 2789, '19.00', 1, '20.00', '7e2e685a2d4556', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:34:13', '2021-10-08 12:58:05', 1),
(46, 'JO263', 42, NULL, 1890, '12.00', 1, '16.00', 'c5b0fb238305ac', NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-08 12:34:13', '2021-10-08 13:00:45', 1);

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
(1, 'Small', 1, 1, NULL, '2021-09-30 12:04:21'),
(2, 'White', 2, 1, NULL, '2021-09-30 12:04:21'),
(3, 'Black', 3, 1, NULL, '2021-09-30 12:04:21'),
(4, 'Grey', 4, 1, NULL, '2021-09-30 12:04:21'),
(5, 'Medium', 5, 1, NULL, '2021-09-30 12:04:21'),
(6, 'Large', 6, 1, NULL, '2021-09-30 12:04:21'),
(7, 'IPhone', 7, 1, NULL, '2021-09-30 12:04:21'),
(8, 'Samsung', 8, 1, NULL, '2021-09-30 12:04:21'),
(9, 'Xiaomi', 9, 1, NULL, '2021-09-30 12:04:21');

-- --------------------------------------------------------


--
-- Dumping data for table `variant_translations`
--
INSERT INTO `variant_translations` (`id`, `title`, `variant_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, NULL, '2021-09-30 12:04:20'),
(2, 'Color', 2, 1, NULL, '2021-09-30 12:04:20'),
(3, 'Phones', 3, 1, NULL, '2021-09-30 12:04:20');


-- --------------------------------------------------------

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `vendor_id`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 8, 1, '2021-09-30 10:30:20', '2021-09-30 10:30:20'),
(2, 5, 9, 1, '2021-09-30 10:36:17', '2021-09-30 10:36:17'),
(3, 4, 14, 1, '2021-09-30 10:40:36', '2021-10-08 10:32:17'),
(4, 4, 10, 1, '2021-09-30 10:40:38', '2021-09-30 10:40:38'),
(5, 3, 14, 1, '2021-09-30 10:47:45', '2021-09-30 10:47:45'),
(6, 2, 15, 1, '2021-09-30 10:55:36', '2021-10-08 12:22:32'),
(7, 6, 9, 1, '2021-10-08 10:31:47', '2021-10-08 10:31:47'),
(8, 6, 10, 1, '2021-10-08 10:31:49', '2021-10-08 10:31:49'),
(9, 6, 14, 1, '2021-10-08 10:31:50', '2021-10-08 10:31:50'),
(10, 6, 15, 1, '2021-10-08 10:31:50', '2021-10-08 10:31:50'),
(11, 5, 14, 1, '2021-10-08 10:32:01', '2021-10-08 10:32:01'),
(12, 5, 10, 1, '2021-10-08 10:32:02', '2021-10-08 10:32:02'),
(13, 5, 8, 1, '2021-10-08 10:32:03', '2021-10-08 10:32:03'),
(14, 4, 15, 1, '2021-10-08 10:32:18', '2021-10-08 10:32:18'),
(15, 4, 9, 1, '2021-10-08 10:32:22', '2021-10-08 10:32:22'),
(16, 3, 10, 1, '2021-10-08 10:32:32', '2021-10-08 10:32:32'),
(17, 3, 15, 1, '2021-10-08 10:32:34', '2021-10-08 10:32:34'),
(18, 3, 9, 1, '2021-10-08 10:32:35', '2021-10-08 10:32:35'),
(19, 3, 8, 1, '2021-10-08 10:32:36', '2021-10-08 10:32:36'),
(20, 2, 8, 1, '2021-10-08 10:32:48', '2021-10-08 10:32:48'),
(21, 2, 9, 1, '2021-10-08 10:32:49', '2021-10-08 10:32:49'),
(22, 2, 10, 1, '2021-10-08 10:32:50', '2021-10-08 10:32:50'),
(23, 2, 14, 1, '2021-10-08 10:32:54', '2021-10-08 10:32:54'),
(24, 4, 8, 1, '2021-10-08 12:17:59', '2021-10-08 12:17:59'),
(25, 5, 15, 1, '2021-10-08 12:31:07', '2021-10-08 12:31:07');


-- --------------------------------------------------------

INSERT INTO `banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`, `image_mobile`) VALUES
(1, 'Vegetables', NULL, 'banner/jNz3XAQzyOnDAO28EyQyIZgjAHo3Z6tbSXW4gCtM.png', 1, 1, 1, '2021-09-30 16:32:00', '2022-02-24 12:00:00', 8, NULL, 'category', NULL, '2021-10-11 10:13:22', 'banner/cUGwzVOqMLbh3w9dKguCaFHNl3iDECYvNtF3TnHb.png'),
(2, 'Beverages', NULL, 'banner/ANHtxqX9efpQyI569o3zfUv8wksov9b1uN8MfUiy.jpg', 1, 2, 1, '2021-09-30 16:38:00', '2021-12-30 12:00:00', 15, NULL, 'category', NULL, '2021-10-11 10:13:40', 'banner/mPpy09BVimNeg46TatzLQt3m0zDYgp3eT1eXzQyZ.png'),
(3, 'Fruits', NULL, 'banner/jFO3l6weFavP3irAM9jTu9k92mf0m8Wp9icfzxCY.jpg', 1, 3, 1, '2021-09-30 17:37:00', '2022-02-23 12:00:00', 9, NULL, 'category', NULL, '2021-10-11 10:14:50', 'banner/0F2XdQvXs0u81vN7KVi38AOgXyvi5Kwk81EhDWMq.png');


INSERT INTO `mobile_banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Vegetables', NULL, 'banner/jNz3XAQzyOnDAO28EyQyIZgjAHo3Z6tbSXW4gCtM.png', 1, 1, 1, '2021-09-30 16:32:00', '2022-02-24 12:00:00', 8, NULL, 'category', NULL, '2021-10-11 10:13:22'),
(2, 'Beverages', NULL, 'banner/ANHtxqX9efpQyI569o3zfUv8wksov9b1uN8MfUiy.jpg', 1, 2, 1, '2021-09-30 16:38:00', '2021-12-30 12:00:00', 15, NULL, 'category', NULL, '2021-10-11 10:13:40'),
(3, 'Fruits', NULL, 'banner/jFO3l6weFavP3irAM9jTu9k92mf0m8Wp9icfzxCY.jpg', 1, 3, 1, '2021-09-30 17:37:00', '2022-02-23 12:00:00', 9, NULL, 'category', NULL, '2021-10-11 10:14:50');


INSERT INTO `cab_booking_layouts` (`id`, `title`, `slug`, `order_by`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vendors', 'vendors', 1, 1, NULL, NULL),
(2, 'Featured Products', 'featured_products', 2, 1, NULL, NULL),
(3, 'New Products', 'new_products', 3, 1, NULL, NULL),
(4, 'On Sale', 'on_sale', 4, 1, NULL, NULL),
(5, 'Best Sellers', 'best_sellers', 5, 1, NULL, NULL),
(6, 'Brands', 'brands', 6, 1, NULL, NULL);

