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
(1, 'Homeric', 'homeric', NULL, 'vendor/JcyIWwmMOoe4cJYERZdSPiQGgXlPA37EUgnxwLP7.jpg', 'vendor/RWL4Tw3GR6Lxx71dpatzUIbb3HwIABAsjfRIVXdj.png', 'Chandigarh, India', 'homeric@support.com', NULL, '8525459636', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 1, 1, 0, 0, NULL, '2021-09-29 18:31:49', 1, NULL, 0);


--
-- Dumping data for table `addon_sets`
--
INSERT INTO `addon_sets` (`id`, `title`, `min_select`, `max_select`, `position`, `status`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, 1, 1, 1, NULL, NULL, NULL),
(2, 'Number of Bedrooms ?', 0, 4, 1, 1, 1, 1, '2021-09-29 12:56:19', '2021-10-11 10:02:04'),
(3, 'What kind of services you require ?', 1, 1, 1, 1, 1, 1, '2021-09-29 12:57:13', '2021-09-29 12:57:13'),
(4, 'Number of Bathrooms ?', 1, 1, 1, 1, 1, 1, '2021-09-29 12:57:59', '2021-09-29 12:57:59'),
(5, 'What kind of services you require ?', 1, 1, 1, 1, 1, 1, '2021-09-29 12:59:11', '2021-09-29 12:59:11');


--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `icon`, `slug`, `type_id`, `image`, `is_visible`, `status`, `position`, `is_core`, `can_add_products`, `parent_id`, `vendor_id`, `client_code`, `display_mode`, `warning_page_id`, `template_type_id`, `warning_page_design`, `created_at`, `updated_at`, `deleted_at`, `show_wishlist`) VALUES
(1, NULL, 'Root', 3, NULL, 0, 1, 1, 1, 0, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 'category/icon/MWlOeFC9hCqLsDubFulYR8YprnVau9b9cvZpUYFy.svg', 'disinfection', 8, 'category/image/MwXJJE7DkihnUGGjfkPlCBEcyVX5j2pJ0FktNijG.jpg', 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 09:56:12', NULL, 1),
(3, 'category/icon/M2Z53bUDuzilqExSOw32e5ak4jmK2gVtrrXacL98.svg', 'Home Cleaning', 8, 'category/image/jgC2LvGBz4XHRpVBQo5ksA4JgikSvmkbwIxauG2W.jpg', 1, 1, 2, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 10:01:49', NULL, 1),
(4, 'category/icon/1LdpIWD3adsy7ZDMNALw5Iek8rAiDql8UJtQX15D.svg', 'AC Cleaning', 8, 'category/image/D0nMpTRINelmC9S0oogrOfgdDyvAxJMziydLWoOC.png', 1, 1, 3, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 10:01:49', NULL, 1),
(5, 'category/icon/ahWz5gB6IVyWr7qEv7l8Xt30zFW6v5K9nJx9DubZ.svg', 'Carpet Cleaning', 8, 'category/image/orTaPzYgUJHNpWlKb9qQoxI502yUYslJZiPXkWlJ.jpg', 1, 1, 4, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 10:01:49', NULL, 1),
(6, NULL, 'Send something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 07:45:38', '2021-09-29 07:45:38', 1),
(7, NULL, 'Buy something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 07:45:43', '2021-09-29 07:45:43', 1),
(8, NULL, 'Vegetables', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 07:46:45', '2021-09-29 07:46:45', 1),
(9, NULL, 'Fruits', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 07:46:50', '2021-09-29 07:46:50', 1),
(10, NULL, 'Dairy and Eggs', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 07:46:55', '2021-09-29 07:46:55', 1),
(11, 'category/icon/d1E10Vwrd8WmNuQX1VIOZxLfKJGiIpE3LzKkr0Nm.svg', 'Mattress Cleaning', 8, 'category/image/Qqj5OgKDb4gFyazkWshbo8pXbmKjsM6SJ5M0p7Ux.jpg', 1, 1, 5, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 10:01:49', NULL, 1),
(12, 'category/icon/wKeciGYWJvFMc7SqoPmtV30AqxKsYUXOKWQUuiF8.svg', 'Sofa Cleaning', 8, 'category/image/5ScuLF8nSYQ0cJ6l52T80ub8lrGvSXek0ajcUCYm.jpg', 1, 1, 6, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 10:01:49', NULL, 1),
(13, 'category/icon/Jr9ONGwbUxCNClRpTyQpiWcmop5KBok9cV8tLC83.svg', 'Handyman & Maintenance', 8, 'category/image/FhncvpTe0WIgX4J3rQLTghL0nDyxwlpS5Gcxd0nB.jpg', 1, 1, 7, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-10-08 10:01:49', NULL, 1),
(14, 'category/icon/xn3VqBnf80RveecqZdHgY2qTDFNCH1yf4rgrKpKG.svg', 'Laundry & Dry Cleaning', 8, 'category/image/xe5I3DaWzOuxhQHpztNw7yqgAakloJjAVaa23REf.jpg', 1, 1, 9, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 07:49:54', '2021-10-08 10:01:49', NULL, 1),
(15, NULL, 'Deep Cleaning', 8, NULL, 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 07:50:19', '2021-09-29 08:14:07', '2021-09-29 08:14:07', 1),
(16, NULL, 'Car Wash', 8, NULL, 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 07:50:45', '2021-09-29 08:14:14', '2021-09-29 08:14:14', 1),
(17, NULL, 'Packers & Movers', 8, NULL, 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 07:51:14', '2021-09-29 07:52:32', '2021-09-29 07:52:32', 1),
(18, 'category/icon/FM5QIkqzLGVLygm0Asm2ZswWMuX3OEbUcHelf1KY.svg', 'Men\'s Salon', 8, 'category/image/oNSEboeu0WuFxvIOm2jgZZlYemmQsnTFDrYCnno7.png', 1, 1, 10, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 07:51:38', '2021-10-08 10:01:49', NULL, 1),
(19, 'category/icon/i4ljiv2n2jjgvgXPKvJLw8orDwfW6hI9kj875JJ5.svg', 'Pest Control', 8, 'category/image/66GEfTBQaXg0Y5ddF9vJ7DYK3TWx3n2BcLF7yWmR.jpg', 1, 1, 8, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 07:52:18', '2021-10-11 09:52:07', NULL, 1);

--
-- Dumping data for table `addon_options`
--

INSERT INTO `addon_options` (`id`, `title`, `addon_id`, `position`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, '100.00', NULL, NULL),
(2, '1 Bedroom', 2, 1, '10.00', '2021-09-29 12:56:19', '2021-10-11 10:00:17'),
(3, '2 Bedrooms', 2, 2, '20.00', '2021-09-29 12:56:19', '2021-10-11 10:00:17'),
(4, 'Interior Clean Up', 3, 1, '15.00', '2021-09-29 12:57:13', '2021-09-29 12:57:13'),
(5, 'Exterior Clean Up', 3, 2, '25.00', '2021-09-29 12:57:13', '2021-09-29 12:57:13'),
(6, '1 Bathroom', 4, 1, '15.00', '2021-09-29 12:57:59', '2021-09-29 12:57:59'),
(7, '2 Bathroom', 4, 2, '20.00', '2021-09-29 12:57:59', '2021-09-29 12:57:59'),
(8, '3 Sofa Sets', 5, 1, '11.00', '2021-09-29 12:59:11', '2021-09-29 12:59:11'),
(9, 'Bathroom Rugs', 5, 2, '10.00', '2021-09-29 12:59:11', '2021-09-29 12:59:11'),
(10, 'Dining Table Carpet', 5, 3, '15.00', '2021-09-29 12:59:11', '2021-09-29 12:59:11'),
(11, '3 Bedrooms', 2, 1, '23.00', '2021-10-11 09:59:33', '2021-10-11 10:00:17'),
(12, '4 Bedrooms', 2, 1, '25.00', '2021-10-11 10:00:17', '2021-10-11 10:00:17');


-- --------------------------------------------------------


--
-- Dumping data for table `addon_option_translations`
--

INSERT INTO `addon_option_translations` (`id`, `title`, `addon_opt_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small parcel', 1, 1, NULL, '2021-09-29 12:38:45'),
(2, '1 Bedroom', 2, 1, NULL, '2021-10-11 10:00:17'),
(3, '2 Bedrooms', 3, 1, NULL, '2021-10-11 10:00:17'),
(4, 'Interior Clean Up', 4, 1, NULL, NULL),
(5, 'Exterior Clean Up', 5, 1, NULL, NULL),
(6, '1 Bathroom', 6, 1, NULL, NULL),
(7, '2 Bathroom', 7, 1, NULL, NULL),
(8, '3 Sofa Sets', 8, 1, NULL, NULL),
(9, 'Bathroom Rugs', 9, 1, NULL, NULL),
(10, 'Dining Table Carpet', 10, 1, NULL, NULL),
(11, '3 Bedrooms', 11, 1, '2021-10-11 09:59:33', '2021-10-11 10:00:17'),
(12, '4 Bedrooms', 12, 1, '2021-10-11 10:00:17', '2021-10-11 10:00:17');

-- --------------------------------------------------------


-- --------------------------------------------------------


--
-- Dumping data for table `addon_set_translations`
--

INSERT INTO `addon_set_translations` (`id`, `title`, `addon_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Small Parcels', 1, 1, NULL, NULL),
(2, 'Number of Bedrooms ?', 2, 1, NULL, '2021-10-11 10:00:17'),
(3, 'What kind of services you require ?', 3, 1, NULL, NULL),
(4, 'Number of Bathrooms ?', 4, 1, NULL, NULL),
(5, 'What kind of services you require ?', 5, 1, NULL, NULL);


-- --------------------------------------------------------


--
-- Dumping data for table `brands`
--
INSERT INTO `brands` (`id`, `title`, `image`, `image_banner`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hubois', 'brand/Hw9N0YoGDODv3OAUZABxi8x85fDTaCPtRto3XgWa.jpg', NULL, 1, 1, NULL, '2021-10-01 07:11:39'),
(2, 'ECO', 'brand/GY1zlOdiNWVn7mggLygX7hcgiAsNXhkOuV47NuF2.jpg', NULL, 2, 1, NULL, '2021-10-01 07:11:39'),
(3, 'DAI\'s', 'brand/mpDEkv8Z8zmk19z3fQUhDc4KUhP3BBF9MsgQoVTZ.jpg', NULL, 3, 1, NULL, '2021-10-01 07:11:06'),
(4, 'Lahela-Jane', 'brand/1hQMT5upNkAYCXgKNqZ93mrnVXvo1cdkuJS8PLix.png', NULL, 4, 1, NULL, '2021-10-01 07:10:07'),
(5, 'House Proud', 'brand/sU9S8RMMrBKB48z1HPKhFIoKMZWAyr2wyDoAVp45.png', NULL, 5, 2, '2021-09-29 13:37:25', '2021-10-01 06:56:48');

-- --------------------------------------------------------
INSERT INTO `variants` (`id`, `title`, `type`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, 1, NULL, NULL),
(2, 'Color', 2, 2, 1, NULL, NULL),
(3, 'Phones', 1, 3, 2, NULL, '2021-09-29 12:42:03');
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
(1, 1, 11, NULL, NULL),
(2, 2, 11, NULL, NULL),
(3, 3, 11, NULL, NULL),
(4, 4, 11, NULL, NULL);



-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Dumping data for table `category_translations`
--

INSERT INTO `category_translations` (`id`, `name`, `trans-slug`, `meta_title`, `meta_description`, `meta_keywords`, `category_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'root', '', 'root', '', '', 1, 1, NULL, '2021-09-29 12:38:45'),
(2, 'Disinfection Service', '', 'Disinfection Service', NULL, NULL, 2, 1, NULL, '2021-09-29 12:38:45'),
(3, 'Home Cleaning', '', 'Home Cleaning', NULL, NULL, 3, 1, NULL, '2021-09-29 12:38:45'),
(4, 'AC Cleaning', '', 'AC Cleaning', NULL, NULL, 4, 1, NULL, '2021-09-29 12:38:45'),
(5, 'Carpet Cleaning', '', 'Carpet Cleaning', NULL, NULL, 5, 1, NULL, '2021-09-29 12:38:45'),
(6, 'Send something', '', 'Send something', '', '', 6, 1, NULL, '2021-09-29 12:38:45'),
(7, 'Buy something', '', 'Buy something', '', '', 7, 1, NULL, '2021-09-29 12:38:45'),
(8, 'Vegetables', '', 'Vegetables', '', '', 8, 1, NULL, '2021-09-29 12:38:45'),
(9, 'Fruits', '', 'Fruits', '', '', 9, 1, NULL, '2021-09-29 12:38:45'),
(10, 'Dairy and Eggs', '', 'Dairy and Eggs', '', '', 10, 1, NULL, '2021-09-29 12:38:45'),
(11, 'Mattress Cleaning', '', 'Mattress Cleaning', NULL, NULL, 11, 1, NULL, '2021-09-29 12:38:45'),
(12, 'Sofa Cleaning', '', 'Sofa Cleaning', NULL, NULL, 12, 1, NULL, '2021-09-29 12:38:45'),
(13, 'Handyman & Maintenance', '', 'Handyman & Maintenance', NULL, NULL, 13, 1, NULL, '2021-09-29 12:38:45'),
(14, 'Laundry & Dry Cleaning', NULL, 'Laundry & Dry Cleaning', NULL, NULL, 14, 1, '2021-09-29 07:49:54', '2021-09-29 12:38:45'),
(15, 'Deep Cleaning', NULL, 'Deep Cleaning', NULL, NULL, 15, 1, '2021-09-29 07:50:19', '2021-09-29 12:38:45'),
(16, 'Car Wash', NULL, 'Car Wash', NULL, NULL, 16, 1, '2021-09-29 07:50:45', '2021-09-29 12:38:45'),
(17, 'Packers & Movers', NULL, 'Packers & Movers', NULL, NULL, 17, 1, '2021-09-29 07:51:14', '2021-09-29 12:38:45'),
(18, 'Men\'s Salon', NULL, 'Men\'s Salon', NULL, NULL, 18, 1, '2021-09-29 07:51:38', '2021-09-29 12:38:45'),
(19, 'Pest Control', NULL, 'Pest Control', NULL, NULL, 19, 1, '2021-09-29 07:52:18', '2021-09-29 12:38:45');


-- --------------------------------------------------------

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `title`, `url_slug`, `description`, `body_html`, `vendor_id`, `category_id`, `type_id`, `country_origin_id`, `is_new`, `is_featured`, `is_live`, `is_physical`, `weight`, `weight_unit`, `has_inventory`, `has_variant`, `sell_when_out_of_stock`, `requires_shipping`, `Requires_last_mile`, `averageRating`, `inquiry_only`, `publish_at`, `created_at`, `updated_at`, `brand_id`, `tax_category_id`, `deleted_at`, `pharmacy_check`, `tags`, `need_price_from_dispatcher`, `mode_of_service`) VALUES
(1, 'sku-id1632902543', '1', 'sku-id1632902543', NULL, NULL, 1, NULL, 1, NULL, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-09-29 08:02:23', NULL, NULL, '2021-09-29 08:02:23', 0, NULL, NULL, NULL),
(2, 'DS001', 'Studio Apartments', 'DS001', NULL, '', 1, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-09-29 11:50:14', NULL, '2021-10-11 10:00:39', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(3, 'DS002', 'Villa', 'DS002', NULL, '', 1, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-09-29 11:49:58', NULL, '2021-10-05 05:21:09', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(4, 'DS003', 'Office', 'DS003', NULL, '', 1, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:49:43', NULL, '2021-10-05 05:21:56', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(5, 'MS011', 'Haircut', 'MS011', NULL, '', 1, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-09-29 11:49:20', NULL, '2021-10-05 05:22:33', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(6, 'MS012', 'Beard Trim', 'MS012', NULL, '', 1, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-09-29 11:49:03', NULL, '2021-10-05 05:23:33', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(7, 'MS013', 'Paraffin Treatment', 'MS013', NULL, '', 1, 18, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 1, '4.00', 0, '2021-09-29 11:48:47', NULL, '2021-10-05 05:24:25', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(8, 'HM023', 'Electrician Call out', 'HM023', NULL, '', 1, 13, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:52:43', NULL, '2021-10-01 07:41:53', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(9, 'HM024', 'Handyman Call out', 'HM024', NULL, '', 1, 13, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:54:05', NULL, '2021-10-01 07:40:47', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(10, 'HM025', 'Plumber Call out', 'HM025', NULL, '', 1, 13, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:52:59', NULL, '2021-10-01 07:43:16', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(11, 'HM0261632916228', 'AC Technician Call out', 'HM0261632916228', NULL, '', 1, 13, 1, NULL, 1, 0, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, NULL, NULL, '2021-09-29 11:50:28', NULL, NULL, '2021-09-29 11:50:28', 0, NULL, NULL, NULL),
(12, 'SC027', 'Dining Chair', 'SC027', NULL, '', 1, 12, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:48:19', NULL, '2021-10-05 05:29:05', NULL, NULL, NULL, 0, NULL, '0', NULL),
(13, 'SC028', 'Sofa Seat & Cushion', 'SC028', NULL, '', 1, 12, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:48:02', NULL, '2021-10-05 05:29:44', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(14, 'MC029', 'Single Bed', 'MC029', NULL, '', 1, 11, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:47:46', NULL, '2021-10-05 05:30:30', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(15, 'MC030', 'King/Queen Mattress', 'MC030', NULL, '', 1, 11, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:47:28', NULL, '2021-10-05 05:31:25', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(16, 'MC031', 'Baby Mattress', 'MC031', NULL, '', 1, 11, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:47:13', NULL, '2021-10-05 05:30:12', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(17, 'CC032', 'Bathroom Rugs', 'CC032', NULL, '', 1, 5, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:46:55', NULL, '2021-10-05 05:33:54', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(18, 'CC033', 'Dining Table Carpets', 'CC033', NULL, '', 1, 5, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:46:39', NULL, '2021-10-05 05:34:44', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(19, 'CC034', 'Large Area Carpets', 'CC034', NULL, '', 1, 5, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:46:22', NULL, '2021-10-05 05:36:04', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(20, 'AC035', 'AC Regular Cleaning', 'AC035', NULL, '', 1, 4, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:46:05', NULL, '2021-10-05 07:57:20', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(21, 'AC036', 'AC Deep Cleaning(Duct)', 'AC036', NULL, '', 1, 4, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:45:40', NULL, '2021-10-05 07:57:56', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(22, 'AC037', 'AC Deep Cleaning(Coil)', 'AC037', NULL, '', 1, 4, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:17:42', NULL, '2021-10-05 08:00:34', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(23, 'HC038', 'One Time Clean', 'HC038', NULL, '', 1, 3, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:16:37', NULL, '2021-10-05 08:01:24', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(24, 'HC039', 'Bi-Weekly Clean', 'HC039', NULL, '', 1, 3, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:15:12', NULL, '2021-10-05 08:02:34', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(25, 'HC040', 'Weekly Clean', 'HC040', NULL, '', 1, 3, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 11:14:36', NULL, '2021-10-05 05:29:46', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(26, 'Bedbugscontrol', NULL, 'bedbugscontrol', NULL, NULL, 1, 19, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 08:09:22', '2021-09-29 08:08:58', '2021-10-05 08:04:53', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(27, 'Cockroachespestcontrol', NULL, 'cockroachespestcontrol', NULL, NULL, 1, 19, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 08:10:05', '2021-09-29 08:09:45', '2021-10-05 08:05:56', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(28, 'Blankets', NULL, 'blankets', NULL, NULL, 1, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 08:11:34', '2021-09-29 08:11:17', '2021-10-05 08:07:17', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule'),
(29, 'Bedsheets', NULL, 'bedsheets', NULL, NULL, 1, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, '4.00', 0, '2021-09-29 08:12:15', '2021-09-29 08:11:55', '2021-10-05 05:30:27', NULL, NULL, NULL, 0, 'Cab Service', '0', 'schedule');

-- --------------------------------------------------------

--
-- Dumping data for table `product_addons`
--

INSERT INTO `product_addons` (`product_id`, `addon_id`, `created_at`, `updated_at`) VALUES
(4, 3, NULL, NULL),
(12, 5, NULL, NULL),
(13, 5, NULL, NULL),
(23, 2, NULL, NULL),
(23, 4, NULL, NULL),
(24, 2, NULL, NULL),
(25, 2, NULL, NULL),
(25, 4, NULL, NULL),
(2, 2, NULL, NULL);
-- --------------------------------------------------------

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 11, NULL, NULL),
(2, 2, NULL, NULL),
(2, 2, NULL, NULL),
(3, 2, NULL, NULL),
(2, 2, NULL, NULL),
(3, 2, NULL, NULL),
(4, 2, NULL, NULL),
(5, 18, NULL, NULL),
(5, 18, NULL, NULL),
(6, 18, NULL, NULL),
(5, 18, NULL, NULL),
(6, 18, NULL, NULL),
(7, 18, NULL, NULL),
(8, 13, NULL, NULL),
(8, 13, NULL, NULL),
(9, 13, NULL, NULL),
(8, 13, NULL, NULL),
(9, 13, NULL, NULL),
(10, 13, NULL, NULL),
(8, 13, NULL, NULL),
(9, 13, NULL, NULL),
(10, 13, NULL, NULL),
(11, 13, NULL, NULL),
(12, 12, NULL, NULL),
(12, 12, NULL, NULL),
(13, 12, NULL, NULL),
(14, 11, NULL, NULL),
(14, 11, NULL, NULL),
(15, 11, NULL, NULL),
(14, 11, NULL, NULL),
(15, 11, NULL, NULL),
(16, 11, NULL, NULL),
(17, 5, NULL, NULL),
(17, 5, NULL, NULL),
(18, 5, NULL, NULL),
(17, 5, NULL, NULL),
(18, 5, NULL, NULL),
(19, 5, NULL, NULL),
(20, 4, NULL, NULL),
(20, 4, NULL, NULL),
(21, 4, NULL, NULL),
(20, 4, NULL, NULL),
(21, 4, NULL, NULL),
(22, 4, NULL, NULL),
(23, 3, NULL, NULL),
(23, 3, NULL, NULL),
(24, 3, NULL, NULL),
(23, 3, NULL, NULL),
(24, 3, NULL, NULL),
(25, 3, NULL, NULL),
(26, 19, '2021-09-29 08:08:58', '2021-09-29 08:08:58'),
(27, 19, '2021-09-29 08:09:45', '2021-09-29 08:09:45'),
(28, 14, '2021-09-29 08:11:17', '2021-09-29 08:11:17'),
(29, 14, '2021-09-29 08:11:56', '2021-09-29 08:11:56');

-- --------------------------------------------------------

--
-- Dumping data for table `vendor_media`
--

INSERT INTO `vendor_media` (`id`, `media_type`, `vendor_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'prods/UrJ2RrMAKWHZp5IMB1qXLfwpOPOyD0TkoNUDsPFY.jpg', '2021-09-29 11:13:02', '2021-09-29 11:13:02'),
(2, 1, 1, 'prods/QQPKRdGAecr9Z9go2K4A5iRut2JC9OdlWzfBNI6y.jpg', '2021-09-29 11:13:17', '2021-09-29 11:13:17'),
(3, 1, 1, 'prods/Sfal1dzzQWtZJamwW7hqmsgNLRR3ZXWTeCLWBAQ0.jpg', '2021-09-29 11:13:30', '2021-09-29 11:13:30'),
(4, 1, 1, 'prods/tHAguCx2x8R2rVqRkDIlyvbPFVKDKQ3EV19Xt3hN.jpg', '2021-09-29 11:13:46', '2021-09-29 11:13:46'),
(5, 1, 1, 'prods/NhQyEZWzH5i4ie1uRYOJ85IngHXPP1KlpYy4LtLl.jpg', '2021-09-29 11:14:21', '2021-09-29 11:14:21'),
(6, 1, 1, 'prods/z9USxyiSfPhnPr2cRm0GZRDqemiw9KbMBeHMOGJk.jpg', '2021-09-29 11:14:58', '2021-09-29 11:14:58'),
(7, 1, 1, 'prods/UWDZwNmgXiOje2xnIwIYc1WQr4hlEG5kJtLRysG1.jpg', '2021-09-29 11:16:24', '2021-09-29 11:16:24'),
(8, 1, 1, 'prods/tOqj1yqnM4H9tZ6OETJjuFpxR8UCfT4FdUezl2Xv.jpg', '2021-09-29 11:17:06', '2021-09-29 11:17:06'),
(9, 1, 1, 'prods/aEDoes3hKYenWHY1pXwDhGrpwDJjo2dYo8gjjctd.jpg', '2021-09-29 11:18:03', '2021-09-29 11:18:03'),
(10, 1, 1, 'prods/SRcvd6r8S4ux3UEMcFF8F6HC9eEcDQSmjPqyMmDH.jpg', '2021-09-29 11:45:37', '2021-09-29 11:45:37'),
(11, 1, 1, 'prods/BrHcFCbR1WXncWQOccb1t9y7Ny3z4DOXyaaiy0MP.jpg', '2021-09-29 11:46:03', '2021-09-29 11:46:03'),
(12, 1, 1, 'prods/MUuIWpcY0jcwrEUcRhyUGnw2PtKPkDdqDJ8DULXZ.jpg', '2021-09-29 11:46:20', '2021-09-29 11:46:20'),
(13, 1, 1, 'prods/ek8NUqTWe4LBnh3GjRfAIk60AexzHSSp5iO72nQB.jpg', '2021-09-29 11:46:37', '2021-09-29 11:46:37'),
(14, 1, 1, 'prods/oTPPAhAIu0S25AKLRT43nVNtMX4kAxyhUmoe4Tth.jpg', '2021-09-29 11:46:53', '2021-09-29 11:46:53'),
(15, 1, 1, 'prods/zwn8O5tvAFnSOgMQwykIiMUsCeMLUNcAaLWpktEp.jpg', '2021-09-29 11:47:10', '2021-09-29 11:47:10'),
(16, 1, 1, 'prods/fwkqyVS9f2q0l4TcGsLHej2jiWHJJ9dtzzsBZgBt.jpg', '2021-09-29 11:47:26', '2021-09-29 11:47:26'),
(17, 1, 1, 'prods/8qcgBkiugi2Eepo5mnMvH20b4FETZZyC2TG34ol1.jpg', '2021-09-29 11:47:44', '2021-09-29 11:47:44'),
(18, 1, 1, 'prods/YZRNiFjm7IuN9eYoX6Hm3UeROknz2H9znXgKKJtw.jpg', '2021-09-29 11:48:01', '2021-09-29 11:48:01'),
(20, 1, 1, 'prods/9ReEWyXrmsoQDzMyRCmtRGyZNooeaheD0nrfEBOt.jpg', '2021-09-29 11:48:43', '2021-09-29 11:48:43'),
(21, 1, 1, 'prods/aDv3tEelRZx5ru2l2dVLfiH00VDQPwqaAA8ypPv1.jpg', '2021-09-29 11:49:01', '2021-09-29 11:49:01'),
(22, 1, 1, 'prods/ylLaN05IteWovp2QeMQeZyMVwAuXfc2oAF2167kQ.jpg', '2021-09-29 11:49:18', '2021-09-29 11:49:18'),
(23, 1, 1, 'prods/lcIVBNBveCpvPnlCEJCHFyt5pwNJ5OyWbVed087B.jpg', '2021-09-29 11:49:39', '2021-09-29 11:49:39'),
(24, 1, 1, 'prods/dGZ2symsWKsfCbcE9FO34r5XHZXcr9kDTi8QWleO.jpg', '2021-09-29 11:49:55', '2021-09-29 11:49:55'),
(25, 1, 1, 'prods/f6gDqg1hN2eb7IFw0NCnk0P4d0niE7Y8pFpsdYqS.jpg', '2021-09-29 11:50:12', '2021-09-29 11:50:12'),
(36, 1, 1, 'prods/KEArBXaBBK3jY8wVCQShKAVAEQjcgWrIYt458qYW.jpg', '2021-10-04 12:51:08', '2021-10-04 12:51:08'),
(40, 1, 1, 'prods/Z65wOjqbUk1LZ1d0sgDQBpTvtTupIRPOX2LY98pg.jpg', '2021-10-04 13:06:19', '2021-10-04 13:06:19'),
(41, 1, 1, 'prods/qFLbmUZiD4LTDPtaADYUnhjmw0xddUofTQgE1yea.jpg', '2021-10-04 13:07:09', '2021-10-04 13:07:09'),
(44, 1, 1, 'prods/S9BzIVFubYQVJu3hjhRr7YtQnREQnHMd2KPpA68j.jpg', '2021-10-04 13:15:58', '2021-10-04 13:15:58'),
(45, 1, 1, 'prods/sLqEm1iZ0x7p88PkYwctxQoBMm7rbNM5CSMKf9WZ.jpg', '2021-10-04 13:16:41', '2021-10-04 13:16:41'),
(46, 1, 1, 'prods/edSDeqb7iOS99OkV93feXkJSWdIRadt62QG2HdDi.png', '2021-10-05 05:16:29', '2021-10-05 05:16:29');

-- --------------------------------------------------------
--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `media_id`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 29, 1, 1, NULL, NULL),
(2, 28, 2, 1, NULL, NULL),
(3, 27, 3, 1, NULL, NULL),
(4, 26, 4, 1, NULL, NULL),
(5, 25, 5, 1, NULL, NULL),
(6, 24, 6, 1, NULL, NULL),
(7, 23, 7, 1, NULL, NULL),
(8, 22, 8, 1, NULL, NULL),
(9, 21, 10, 1, NULL, NULL),
(10, 20, 11, 1, NULL, NULL),
(11, 19, 12, 1, NULL, NULL),
(12, 18, 13, 1, NULL, NULL),
(13, 17, 14, 1, NULL, NULL),
(14, 16, 15, 1, NULL, NULL),
(15, 15, 16, 1, NULL, NULL),
(16, 14, 17, 1, NULL, NULL),
(17, 13, 18, 1, NULL, NULL),
(19, 7, 20, 1, NULL, NULL),
(20, 6, 21, 1, NULL, NULL),
(21, 5, 22, 1, NULL, NULL),
(22, 4, 23, 1, NULL, NULL),
(23, 3, 24, 1, NULL, NULL),
(24, 2, 25, 1, NULL, NULL),
(35, 8, 36, 1, NULL, NULL),
(41, 9, 44, 1, NULL, NULL),
(42, 10, 45, 1, NULL, NULL),
(43, 12, 46, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `product_translations`
--

INSERT INTO `product_translations` (`id`, `title`, `body_html`, `meta_title`, `meta_keyword`, `meta_description`, `product_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Xiaomi', NULL, 'Xiaomi', 'Xiaomi', NULL, 1, 1, NULL, '2021-09-29 12:38:45'),
(2, 'Studio Apartments', '<p>A&nbsp;studio<strong>&nbsp;</strong>apartment is basically a single large room and the one who occupies the place, will have to allocate space for everything within this large room. Although&nbsp;studio apartments<strong>&nbsp;</strong>are small, it offers several benefits for tenants and owners.</p>', NULL, NULL, NULL, 2, 1, NULL, '2021-10-05 05:20:10'),
(3, 'Studio Apartments', '', '', '', '', 2, 1, NULL, '2021-09-29 12:38:45'),
(4, 'Villa', '<p>Home Inspection&nbsp;Services. Villa Home Inspections provides various<strong>&nbsp;</strong>home inspection&nbsp;packages to choose from.</p>', NULL, NULL, NULL, 3, 1, NULL, '2021-10-05 05:21:09'),
(5, 'Studio Apartments', '', '', '', '', 2, 1, NULL, '2021-09-29 12:38:45'),
(6, 'Villa', '', '', '', '', 3, 1, NULL, '2021-09-29 12:38:45'),
(7, 'Office', '<p>Many businesses can benefit from having a<strong>&nbsp;</strong>cleaning<strong>&nbsp;</strong>service as it will allow the employees to focus more on their work and less on unrelated&nbsp;cleaning<strong>&nbsp;</strong>duties.</p>', NULL, NULL, NULL, 4, 1, NULL, '2021-10-05 05:21:56'),
(8, 'Haircut', '<p>The master barber assesses the structure and movement of the hair, before suggesting a cut that will complement one&rsquo;s distinct sense of style.</p>', NULL, NULL, NULL, 5, 1, NULL, '2021-10-05 05:22:33'),
(9, 'Haircut', '', '', '', '', 5, 1, NULL, '2021-09-29 12:38:45'),
(10, 'Beard Trim', '<p>Full service beard trim, shape and style&nbsp; ideal if you&rsquo;ve recently grown a beard or have a longer one to keep smart. Includes styling with beard products, line up and trim. 15 minutes with a barber to understand your needs and face shape, with recommendations for styling and advice on how to maintain style at home.</p>', NULL, NULL, NULL, 6, 1, NULL, '2021-10-05 05:23:33'),
(11, 'Haircut', '', '', '', '', 5, 1, NULL, '2021-09-29 12:38:45'),
(12, 'Beard Trim', '', '', '', '', 6, 1, NULL, '2021-09-29 12:38:45'),
(13, 'Paraffin Treatment', '<p>Paraffin dips lend themselves to other add-on services, such as<strong>&nbsp;</strong>aromatherapy and reflexology. While a paraffin dip is soothing and relaxing on its own, think how much more you can enhance the service with a delicious scent and a deep massage.</p>', NULL, NULL, NULL, 7, 1, NULL, '2021-10-05 05:24:25'),
(14, 'Electrician Call out', '<p>We&rsquo;re an established company specializing in supply, installation and maintenance of all electrical and lighting. Our client base comprises of commercial and industrial clients and no job is too large or small for us to handle.</p>', NULL, NULL, NULL, 8, 1, NULL, '2021-10-05 05:26:14'),
(15, 'Electrician Call out', '', '', '', '', 8, 1, NULL, '2021-09-29 12:38:45'),
(16, 'Handyman Call out', '<p>Handymankol provides a professional one stop shop for all of your maintenance repairs and installations requirements around your home.</p>', NULL, NULL, NULL, 9, 1, NULL, '2021-10-05 05:27:07'),
(17, 'Electrician Call out', '', '', '', '', 8, 1, NULL, '2021-09-29 12:38:45'),
(18, 'Handyman Call out', '', '', '', '', 9, 1, NULL, '2021-09-29 12:38:45'),
(19, 'Plumber Call out', '<p>The most common occasion when a plumber will charge a call out fee simply for showing up at your home is in an emergency out of hours.&nbsp;</p>', NULL, NULL, NULL, 10, 1, NULL, '2021-10-05 05:28:18'),
(20, 'Electrician Call out', '', '', '', '', 8, 1, NULL, '2021-09-29 12:38:45'),
(21, 'Handyman Call out', '', '', '', '', 9, 1, NULL, '2021-09-29 12:38:45'),
(22, 'Plumber Call out', '', '', '', '', 10, 1, NULL, '2021-09-29 12:38:45'),
(23, 'AC Technician Call out', '', '', '', '', 11, 1, NULL, '2021-09-29 12:38:45'),
(24, 'Dining Chair', '<p>Apart from furniture&nbsp;cleaning, we also offer office&nbsp;chair services. If the&nbsp;chair&nbsp;lift mechanism is not working or the change the hydraulic gas lift of the office&nbsp;chair, we can take care of it.</p>', NULL, NULL, NULL, 12, 1, NULL, '2021-10-05 05:29:05'),
(25, 'Dining Chair', '', '', '', '', 12, 1, NULL, '2021-09-29 12:38:45'),
(26, 'Sofa Seat & Cushion', '<p>Bio Shield Sofa Cleaning Services&nbsp;includes the washing and quick drying of sofas covers, chairs, cushions &amp; pillows, without compromising the material of the fabric.</p>', NULL, NULL, NULL, 13, 1, NULL, '2021-10-05 05:29:44'),
(27, 'Single Bed', '<p>Our&nbsp;Mattress Cleaning Services&nbsp;remove all the unwanted stains and dust from your&nbsp;mattress.&nbsp;The high temperature steaming on the&nbsp;mattress&nbsp;kills all the disease causing germs.&nbsp;</p>', NULL, NULL, NULL, 14, 1, NULL, '2021-10-05 05:30:30'),
(28, 'Single Bed', '', '', '', '', 14, 1, NULL, '2021-09-29 12:38:45'),
(29, 'King/Queen Mattress', '<p>It is a perfect&nbsp;mattress&nbsp;for us because it has a soft top, but firm underneath feel. We didn&#39;t want a&nbsp;mattress&nbsp;that was too soft - we can&#39;t stand that sinking feeling in memory foam mattresses like we&#39;ve had in years past.</p>', NULL, NULL, NULL, 15, 1, NULL, '2021-10-05 05:31:25'),
(30, 'Single Bed', '', '', '', '', 14, 1, NULL, '2021-09-29 12:38:45'),
(31, 'King/Queen Mattress', '', '', '', '', 15, 1, NULL, '2021-09-29 12:38:45'),
(32, 'Baby Mattress', '<p>We are the India&rsquo;s top most&nbsp;baby&nbsp;care products manufacturers like&nbsp;baby&nbsp;bedding with Mosquito Net, carrier nest, sleeping bag,&nbsp;baby&nbsp;blankets, romper, wrapper,&nbsp;mattress.</p>', NULL, NULL, NULL, 16, 1, NULL, '2021-10-05 05:32:51'),
(33, 'Bathroom Rugs', '<p>We have decided to avail of their bathroom cleaning service regularly. We are a recently married couple. We both have hectic corporate careers that leave little time for most domestic chores.</p>', NULL, NULL, NULL, 17, 1, NULL, '2021-10-05 05:33:54'),
(34, 'Bathroom Rugs', '', '', '', '', 17, 1, NULL, '2021-09-29 12:38:45'),
(35, 'Dining Table Carpets', '<p>Carpets&nbsp;should not be allowed to be in size less than the&nbsp;table&nbsp;with chairs. Ideally is the&nbsp;carpet&nbsp;to be around the&nbsp;table&nbsp;and chairs, on each side in the same size.&nbsp;</p>', NULL, NULL, NULL, 18, 1, NULL, '2021-10-05 05:34:44'),
(36, 'Bathroom Rugs', '', '', '', '', 17, 1, NULL, '2021-09-29 12:38:45'),
(37, 'Dining Table Carpets', '', '', '', '', 18, 1, NULL, '2021-09-29 12:38:45'),
(38, 'Large Area Carpets', '<p>Our&nbsp;Area Rug cleaning services&nbsp;are guaranteed to be SUPREME and above all the rest. Our process begins with a thorough survey of your&nbsp;area rugs&nbsp;where we will inform you on fiber content and&nbsp;rug services.</p>', NULL, NULL, NULL, 19, 1, NULL, '2021-10-05 05:36:04'),
(39, 'AC Regular Cleaning', '<p>AC cleaning helps eliminate bacteria and mold which form inside your ducts over a period of time. Regular duct cleaning ensures that the occupants breathe clean and pollution-free air.</p>', NULL, NULL, NULL, 20, 1, NULL, '2021-10-05 07:57:20'),
(40, 'AC Regular Cleaning', '', '', '', '', 20, 1, NULL, '2021-09-29 12:38:45'),
(41, 'AC Deep Cleaning(Duct)', '<p>Duct cleaning helps eliminate bacteria and mold which form inside your ducts over a period of time. Regular duct cleaning ensures that the occupants breathe clean and pollution-free air.&nbsp;</p>', NULL, NULL, NULL, 21, 1, NULL, '2021-10-05 07:57:56'),
(42, 'AC Regular Cleaning', '', '', '', '', 20, 1, NULL, '2021-09-29 12:38:45'),
(43, 'AC Deep Cleaning(Duct)', '', '', '', '', 21, 1, NULL, '2021-09-29 12:38:45'),
(44, 'AC Deep Cleaning(Coil)', '<p>&nbsp;Air conditioner coil cleaning can help you save money, maintain efficiency and extend the system&rsquo;s life expectancy.</p>', NULL, NULL, NULL, 22, 1, NULL, '2021-10-05 08:00:34'),
(45, 'One Time Clean', '<p>One time&nbsp;house&nbsp;cleaning services&nbsp;are excellent&nbsp;time-savers for anyone holding an event or celebration at their home. While an experienced professional gets on with the job of&nbsp;cleaning&nbsp;and tidying your home, you can dedicate your&nbsp;time&nbsp;to preparing that special meal or putting up the decorations.</p>', NULL, NULL, NULL, 23, 1, NULL, '2021-10-05 08:01:24'),
(46, 'One Time Clean', '', '', '', '', 23, 1, NULL, '2021-09-29 12:38:45'),
(47, 'Bi-Weekly Clean', '<p>weekly service can be challenging to a lot of people. Some people can&rsquo;t make the time one day every week to have strangers in their home. Other people struggle to fit weekly service into their budget.</p>', NULL, NULL, NULL, 24, 1, NULL, '2021-10-05 08:02:34'),
(48, 'One Time Clean', '', '', '', '', 23, 1, NULL, '2021-09-29 12:38:45'),
(49, 'Bi-Weekly Clean', '', '', '', '', 24, 1, NULL, '2021-09-29 12:38:45'),
(50, 'Weekly Clean', '<p>Weekly or hourly cleaning &ndash;<strong>&nbsp;</strong>Standard cleaning jobs, covering your whole home. Specific room cleaning &ndash; For example, the bathroom or kitchen. End of tenancy cleaning &ndash; A deeper clean between tenants to leave a fresh home for the next occupants. Window cleaning &ndash; On a regular or one-off basis.</p>', NULL, NULL, NULL, 25, 1, NULL, '2021-10-05 08:03:46'),
(51, 'Bed bugs control', '<p>Heat treat clothing, bedding, and other items that can withstand a hot dryer (household dryer at high heat for 30 minutes), which will kill&nbsp;bed bugs<strong>&nbsp;</strong>and eggs. Washing alone might not do the job.</p>', NULL, NULL, NULL, 26, 1, NULL, '2021-10-05 08:04:53'),
(52, 'Cockroaches pest control', '<p>Experts will do assess your home/office and develop service plan accordingly. Get high-quality&nbsp;pest control/cleaning/sanitization service done at your premise.</p>', NULL, NULL, NULL, 27, 1, NULL, '2021-10-05 08:05:56'),
(53, 'Blankets', '<p>we do not realize that our blankets, also used by our kids, might have dust mites, bed bugs, bacteria, and allergens. With our specialized eco-friendly and skin-friendly dry cleaning techniques, we get rid of germs and make your blanket safe for use, without the unpleasant odor of dry clean.&nbsp;</p>', NULL, NULL, NULL, 28, 1, NULL, '2021-10-05 08:07:17'),
(54, 'Bedsheets', '<p>Bed sheets and pillowcases must be cleaned regularly, at least once a week to be precise. We provide 24*7 service on the basic of days, weekly, monthly wise.</p>', NULL, NULL, NULL, 29, 1, NULL, '2021-10-05 08:09:27');

-- --------------------------------------------------------

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `sku`, `product_id`, `title`, `quantity`, `price`, `position`, `compare_at_price`, `barcode`, `cost_price`, `currency_id`, `tax_category_id`, `inventory_policy`, `fulfillment_service`, `inventory_management`, `created_at`, `updated_at`, `status`) VALUES
(1, 'sku-id1632902543d2f860a', 1, NULL, 100, '500.00', 1, '500.00', '7543ebf012007e', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:02:23', 1),
(2, 'sku-id16329025437463ead', 1, 'sku-id-Black-Black', 100, '500.00', 1, '500.00', '1500cdf2d597df', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:02:23', 1),
(3, 'sku-id163290254358c4ffb', 1, 'sku-id-Black-Grey', 100, '500.00', 1, '500.00', '2ea56327679387', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:02:23', 1),
(4, 'sku-id163290254378c0093', 1, 'sku-id-Medium-Black', 100, '500.00', 1, '500.00', '8f47f11a19433f', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:02:23', 1),
(5, 'sku-id1632902543e571e73', 1, 'sku-id-Medium-Grey', 100, '500.00', 1, '500.00', '8f7318b112bbe9', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:02:23', 1),
(6, 'DS001', 2, NULL, 0, '16.00', 1, '17.00', 'ed6ff879142b76', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:03:05', '2021-10-11 10:00:39', 1),
(7, 'DS002', 3, NULL, 0, '14.00', 1, '17.00', 'b7b9918c3844b6', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:03:05', '2021-10-05 05:21:09', 1),
(8, 'DS003', 4, NULL, 0, '20.00', 1, '22.00', '5dec525e062b99', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:03:05', '2021-10-05 05:21:56', 1),
(9, 'MS011', 5, NULL, 0, '11.00', 1, '15.00', '1f419e6e1be5d9', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:03:47', '2021-10-05 05:22:33', 1),
(10, 'MS012', 6, NULL, 0, '14.00', 1, '18.00', 'e8957f8cf757e1', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:03:47', '2021-10-05 05:23:33', 1),
(11, 'MS013', 7, NULL, 0, '14.00', 1, '16.00', '535f5621e39751', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:03:47', '2021-10-05 05:24:25', 1),
(12, 'HM023', 8, NULL, 0, '15.00', 1, '18.00', '38822b3ce09a69', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:22', '2021-10-05 05:29:30', 1),
(13, 'HM024', 9, NULL, 0, '14.00', 1, '18.00', '37d832a4abe4e2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:22', '2021-10-05 05:27:07', 1),
(14, 'HM025', 10, NULL, 0, '20.00', 1, '25.00', '5e7f59cc6d42d0', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:22', '2021-10-05 05:28:18', 1),
(15, 'HM0261632916228b10ee5e', 11, NULL, 0, '0.00', 1, '0.00', '9e2efa67858d54', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:22', '2021-09-29 11:50:28', 1),
(16, 'SC027', 12, NULL, 0, '15.00', 1, '18.00', '242899526fc918', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:39', '2021-10-05 05:29:05', 1),
(17, 'SC028', 13, NULL, 0, '14.00', 1, '16.00', '0071767d6f83ca', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:39', '2021-10-05 05:29:44', 1),
(18, 'MC029', 14, NULL, 0, '10.00', 1, '15.00', '7ff1e446fbe5bf', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:54', '2021-10-05 05:30:30', 1),
(19, 'MC030', 15, NULL, 0, '15.00', 1, '18.00', '74b681dc9d4ea4', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:54', '2021-10-05 05:31:25', 1),
(20, 'MC031', 16, NULL, 0, '9.00', 1, '14.00', 'b288a5425e5a92', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:04:54', '2021-10-05 05:32:52', 1),
(21, 'CC032', 17, NULL, 0, '17.00', 1, '19.00', '7e262331478071', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:27', '2021-10-05 05:33:54', 1),
(22, 'CC033', 18, NULL, 0, '14.00', 1, '19.00', '72813b4680109b', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:27', '2021-10-05 05:34:44', 1),
(23, 'CC034', 19, NULL, 0, '20.00', 1, '24.00', '39c39c25f6def0', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:27', '2021-10-05 05:36:04', 1),
(24, 'AC035', 20, NULL, 0, '12.00', 1, '15.00', '420646e84c183f', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:40', '2021-10-05 07:57:20', 1),
(25, 'AC036', 21, NULL, 0, '14.00', 1, '18.00', 'f7543130a32114', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:40', '2021-10-05 07:57:56', 1),
(26, 'AC037', 22, NULL, 0, '18.00', 1, '21.00', '967091ed47c94d', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:40', '2021-10-05 08:00:34', 1),
(27, 'HC038', 23, NULL, 0, '20.00', 1, '22.00', 'cfef3d8033c247', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:56', '2021-10-05 08:01:24', 1),
(28, 'HC039', 24, NULL, 0, '18.00', 1, '20.00', '1c3b08a2ca51fa', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:57', '2021-10-05 08:02:34', 1),
(29, 'HC040', 25, NULL, 0, '15.00', 1, '18.00', 'ff438cbd8216f3', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:05:57', '2021-10-05 08:03:46', 1),
(30, 'Bedbugscontrol', 26, NULL, 0, '10.00', 1, '15.00', '0d8b97e362a5f1', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:08:58', '2021-10-05 08:04:53', 1),
(31, 'Cockroachespestcontrol', 27, NULL, 0, '12.00', 1, '16.00', 'f4477203d7d244', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:09:45', '2021-10-05 08:05:56', 1),
(32, 'Blankets', 28, NULL, 0, '15.00', 1, '20.00', '8d2d67881a8559', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:11:17', '2021-10-05 08:07:17', 1),
(33, 'Bedsheets', 29, NULL, 0, '16.00', 1, '18.00', 'b6ce1e0d85ad73', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 08:11:56', '2021-10-05 08:09:27', 1);

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
(1, 'Small', 1, 1, NULL, '2021-09-29 12:38:45'),
(2, 'White', 2, 1, NULL, '2021-09-29 12:38:45'),
(3, 'Black', 3, 1, NULL, '2021-09-29 12:38:45'),
(4, 'Grey', 4, 1, NULL, '2021-09-29 12:38:45'),
(5, 'Medium', 5, 1, NULL, '2021-09-29 12:38:45'),
(6, 'Large', 6, 1, NULL, '2021-09-29 12:38:45'),
(7, 'IPhone', 7, 1, NULL, '2021-09-29 12:38:45'),
(8, 'Samsung', 8, 1, NULL, '2021-09-29 12:38:45'),
(9, 'Xiaomi', 9, 1, NULL, '2021-09-29 12:38:45');

-- --------------------------------------------------------


--
-- Dumping data for table `variant_translations`
--


INSERT INTO `variant_translations` (`id`, `title`, `variant_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, NULL, '2021-09-29 12:38:45'),
(2, 'Color', 2, 1, NULL, '2021-09-29 12:38:45'),
(3, 'Phones', 3, 1, NULL, '2021-09-29 12:38:45');


-- --------------------------------------------------------

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `vendor_id`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2021-09-29 08:02:30', '2021-09-29 08:02:30'),
(2, 1, 3, 1, '2021-09-29 08:02:31', '2021-09-29 08:02:31'),
(3, 1, 4, 1, '2021-09-29 08:02:33', '2021-09-29 08:02:33'),
(4, 1, 5, 1, '2021-09-29 08:02:33', '2021-09-29 08:02:33'),
(5, 1, 11, 1, '2021-09-29 08:02:34', '2021-09-29 08:02:34'),
(6, 1, 12, 1, '2021-09-29 08:02:36', '2021-09-29 08:02:36'),
(7, 1, 13, 1, '2021-09-29 08:02:37', '2021-09-29 08:02:37'),
(8, 1, 14, 1, '2021-09-29 08:02:38', '2021-09-29 08:02:38'),
(9, 1, 15, 1, '2021-09-29 08:02:41', '2021-09-29 08:02:41'),
(10, 1, 16, 1, '2021-09-29 08:02:42', '2021-09-29 08:02:42'),
(11, 1, 18, 1, '2021-09-29 08:02:43', '2021-09-29 08:02:43'),
(12, 1, 19, 1, '2021-09-29 08:02:44', '2021-09-29 08:02:44');
-- --------------------------------------------------------


INSERT INTO `banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`, `image_mobile`) VALUES
(2, 'Home services 1', NULL, 'banner/6VYX3rkzeAHONCxo9uXovfeTPpPdodXC3einIkn1.jpg', 1, 3, 1, '2021-09-29 17:56:00', '2024-09-30 12:00:00', NULL, 1, 'vendor', NULL, '2021-10-11 10:29:39', 'banner/oYJ8lc868ZlqEX0yhnqRmX5gvu9WErZe2BwqfJzg.png'),
(3, 'home services2', NULL, 'banner/WXu2FODrtjOgbn1UWIg844On4BBgL7nRIecNl1Dc.jpg', 1, 1, 1, '2021-09-29 17:57:00', '2023-09-30 12:00:00', NULL, 1, 'vendor', NULL, '2021-10-11 10:29:19', 'banner/y2vka7wpPydRQsZIGmjIpf2wjWDKZpEjY9XHL1eJ.png'),
(4, 'home services 3', NULL, 'banner/pJA6aYGXt27Q6lXuirz0rH3fjPC9FhDzfqHzW2wW.jpg', 1, 2, 1, '2021-09-29 17:58:00', '2025-09-30 12:00:00', NULL, NULL, 'category', '2021-09-29 12:29:12', '2021-10-11 10:29:29', 'banner/h6gHMJfVd1YnSW7t2XFdzIDt6ZVkWqh5hZspIYWO.png');


INSERT INTO `cab_booking_layouts` (`id`, `title`, `slug`, `order_by`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vendors', 'vendors', 1, 0, NULL, '2021-10-04 12:36:47'),
(2, 'Featured Products', 'featured_products', 2, 0, NULL, '2021-10-06 05:10:03'),
(3, 'New Products', 'new_products', 3, 1, NULL, NULL),
(4, 'On Sale', 'on_sale', 4, 1, NULL, NULL),
(5, 'Best Sellers', 'best_sellers', 5, 1, NULL, NULL),
(6, 'Brands', 'brands', 6, 0, NULL, '2021-10-06 05:10:03');



INSERT INTO `cab_booking_layout_transaltions` (`id`, `title`, `cab_booking_layout_id`, `language_id`, `created_at`, `updated_at`, `body_html`) VALUES
(1, NULL, 1, 1, '2021-10-04 12:36:47', '2021-10-04 12:36:47', NULL),
(2, NULL, 2, 1, '2021-10-04 12:36:47', '2021-10-04 12:36:47', NULL),
(3, NULL, 3, 1, '2021-10-04 12:36:47', '2021-10-04 12:36:47', NULL),
(4, NULL, 4, 1, '2021-10-04 12:36:47', '2021-10-04 12:36:47', NULL),
(5, NULL, 5, 1, '2021-10-04 12:36:47', '2021-10-04 12:36:47', NULL),
(6, NULL, 6, 1, '2021-10-04 12:36:47', '2021-10-04 12:36:47', NULL);


UPDATE `client_preferences` SET `business_type` = 'home_service' WHERE `client_preferences`.`id` = 1;