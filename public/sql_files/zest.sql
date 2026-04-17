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
(1, 'Aramex', 'aramex', NULL, 'vendor/jUKjbs8NML5OiZ7lMJGJtJt2Gp8qDSuxy5ISumw4.png', 'vendor/LScwOAff8dQYbXUFUj3i2L8UYJsmAXGqg4OX5auH.png', 'Chandigarh, India', 'aramex123@support.com', NULL, '8596365478', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 1, 1, 1, 1, 0, 0, NULL, '2021-09-29 06:52:30', 1, NULL, 0),
(2, 'Green Cab', 'green-cab', NULL, 'vendor/WcPwyD9QS3traKxqhCcKUDxOCWV4vGFovcnRaSoX.jpg', 'vendor/uVJNBtJSqmXdnEa8I9FYnw9fNTQlgRN7todvIDcY.jpg', 'Chandigarh, India', 'green@support.com', NULL, '7485587489', '30.733314800000', '76.779417900000', '0.00', NULL, NULL, 1, '0.00', '0.00', 0, 0, 1, 2, 1, 0, 0, '2021-09-29 06:53:39', '2021-09-29 07:16:52', 1, NULL, 0);

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
(2, 'category/icon/KT5Rqx0kwl2nyhMheq9mAigrOddSxJREl8w9RLZ3.svg', 'Delivery', 7, 'category/image/AYBCHNuQ7TSu8CousGVCAhqr28PYK0JFl9qrlEeJ.jpg', 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', NULL, '2021-09-29 07:00:41', NULL, 1),
(3, NULL, 'Restaurant', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:29', '2021-09-29 06:44:29', 1),
(4, NULL, 'Supermarket', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:37', '2021-09-29 06:44:37', 1),
(5, NULL, 'Pharmacy', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:42', '2021-09-29 06:44:42', 1),
(6, NULL, 'Send something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:21', '2021-09-29 06:44:21', 1),
(7, NULL, 'Buy something', 1, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:25', '2021-09-29 06:44:25', 1),
(8, NULL, 'Vegetables', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:37', '2021-09-29 06:44:37', 1),
(9, NULL, 'Fruits', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:37', '2021-09-29 06:44:37', 1),
(10, NULL, 'Dairy and Eggs', 1, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:37', '2021-09-29 06:44:37', 1),
(11, NULL, 'E-Commerce', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:48', '2021-09-29 06:44:48', 1),
(12, NULL, 'Cloth', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:44:57', '2021-09-29 06:44:57', 1),
(13, NULL, 'Dispatcher', 1, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL, '1', NULL, NULL, NULL, NULL, '2021-09-29 06:45:03', '2021-09-29 06:45:03', 1),
(14, 'category/icon/jluS5lfXcXaZn0dCiQAe12qB3SDzQtbtxBZbTGVX.svg', 'cabservice', 7, 'category/image/3PTns9pRMt3mTHEa5duRGpaXEX9rcxiiKlhCEaog.jpg', 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 06:47:56', '2021-09-29 07:16:34', '2021-09-29 07:16:34', 1),
(15, 'category/icon/nqZHRx86u1lkYUcrQA2nyir3yNt0WwfAy68X7dh3.svg', 'motoservice', 7, 'category/image/QRVHWkBqIGwS7aLgvOZWCVnVvPhSmH0yfoRlyxr6.png', 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 06:48:47', '2021-09-29 07:16:39', '2021-09-29 07:16:39', 1),
(16, 'category/icon/SU5cxHI7EOHnRmSHHYN5H9jpngCs7VgVoPjRgDz6.svg', 'autoservice', 7, 'category/image/tXDVfwT9s010BRWKWhDNCbs6Wnvwhu0mg2zq8rZO.jpg', 1, 1, 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, '0', '2021-09-29 06:49:21', '2021-09-29 07:16:43', '2021-09-29 07:16:43', 1);

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
(1, 'Small parcel', 1, 1, NULL, '2021-09-29 07:09:51');
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
(1, 'J.Crew', 'default/default_image.png', NULL, 1, 2, NULL, '2021-09-29 07:06:21'),
(2, 'Allform', 'default/default_image.png', NULL, 2, 2, NULL, '2021-09-29 07:06:24'),
(3, 'EyeBuyDirect', 'default/default_image.png', NULL, 3, 2, NULL, '2021-09-29 07:06:28'),
(4, 'In Pictures', 'default/default_image.png', NULL, 4, 2, NULL, '2021-09-29 07:06:31');

-- --------------------------------------------------------
INSERT INTO `variants` (`id`, `title`, `type`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, 2, NULL, '2021-09-29 07:06:09'),
(2, 'Color', 2, 2, 2, NULL, '2021-09-29 07:06:13'),
(3, 'Phones', 1, 3, 2, NULL, '2021-09-29 07:06:17');
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
(1, 'root', '', 'root', '', '', 1, 1, NULL, '2021-09-29 07:09:51'),
(2, 'Delivery', '', 'Delivery', NULL, NULL, 2, 1, NULL, '2021-09-29 07:09:51'),
(3, 'Restaurant', '', 'Restaurant', '', '', 3, 1, NULL, '2021-09-29 07:09:51'),
(4, 'Supermarket', '', 'Supermarket', '', '', 4, 1, NULL, '2021-09-29 07:09:51'),
(5, 'Pharmacy', '', 'Pharmacy', '', '', 5, 1, NULL, '2021-09-29 07:09:51'),
(6, 'Send something', '', 'Send something', '', '', 6, 1, NULL, '2021-09-29 07:09:51'),
(7, 'Buy something', '', 'Buy something', '', '', 7, 1, NULL, '2021-09-29 07:09:51'),
(8, 'Vegetables', '', 'Vegetables', '', '', 8, 1, NULL, '2021-09-29 07:09:51'),
(9, 'Fruits', '', 'Fruits', '', '', 9, 1, NULL, '2021-09-29 07:09:51'),
(10, 'Dairy and Eggs', '', 'Dairy and Eggs', '', '', 10, 1, NULL, '2021-09-29 07:09:51'),
(11, 'E-Commerce', '', 'E-Commerce', '', '', 11, 1, NULL, '2021-09-29 07:09:51'),
(12, 'Cloth', '', 'Cloth', '', '', 12, 1, NULL, '2021-09-29 07:09:51'),
(13, 'Dispatcher', '', 'Dispatcher', '', '', 13, 1, NULL, '2021-09-29 07:09:51'),
(14, 'Cab Service', NULL, 'Cab Service', NULL, NULL, 14, 1, '2021-09-29 06:47:56', '2021-09-29 07:09:51'),
(15, 'Moto Service', NULL, 'Moto Service', NULL, NULL, 15, 1, '2021-09-29 06:48:47', '2021-09-29 07:09:51'),
(16, 'Auto Service', NULL, 'Auto Service', NULL, NULL, 16, 1, '2021-09-29 06:49:21', '2021-09-29 07:09:51');


-- --------------------------------------------------------

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `title`, `url_slug`, `description`, `body_html`, `vendor_id`, `category_id`, `type_id`, `country_origin_id`, `is_new`, `is_featured`, `is_live`, `is_physical`, `weight`, `weight_unit`, `has_inventory`, `has_variant`, `sell_when_out_of_stock`, `requires_shipping`, `Requires_last_mile`, `averageRating`, `inquiry_only`, `publish_at`, `created_at`, `updated_at`, `brand_id`, `tax_category_id`, `deleted_at`, `pharmacy_check`, `tags`, `need_price_from_dispatcher`, `mode_of_service`) VALUES
(1, 'sku-id1632898865', '1', 'sku-id1632898865', NULL, NULL, 1, NULL, 1, NULL, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, '2021-09-29 07:01:05', NULL, NULL, '2021-09-29 07:01:05', 0, NULL, NULL, NULL),
(2, 'CAB584', 'RoyoXL', 'CAB584', NULL, '', 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 06:56:35', NULL, '2021-09-29 07:16:34', NULL, NULL, '2021-09-29 07:16:34', 0, NULL, '0', NULL),
(3, 'CAB585', 'Royo Platinum', 'CAB585', NULL, '', 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 06:57:19', NULL, '2021-09-29 07:16:34', NULL, NULL, '2021-09-29 07:16:34', 0, NULL, '0', NULL),
(4, 'CAB586', 'Royo Pool', 'CAB586', NULL, '', 2, 14, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 06:58:10', NULL, '2021-09-29 07:16:34', NULL, NULL, '2021-09-29 07:16:34', 0, NULL, '0', NULL),
(5, 'CAB587', 'Royo Moto', 'CAB587', NULL, '', 2, 15, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 06:59:24', NULL, '2021-09-29 07:16:39', NULL, NULL, '2021-09-29 07:16:39', 0, NULL, '0', NULL),
(6, 'CAB588', 'Royo Auto', 'CAB588', NULL, '', 2, 16, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 06:59:45', NULL, '2021-09-29 07:16:43', NULL, NULL, '2021-09-29 07:16:43', 0, NULL, '0', NULL),
(7, 'Small1', NULL, 'small1', NULL, NULL, 1, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 07:02:07', '2021-09-29 07:01:37', '2021-09-29 07:02:07', NULL, NULL, NULL, 0, NULL, '0', NULL),
(8, 'Medium2', NULL, 'medium2', NULL, NULL, 1, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 07:02:55', '2021-09-29 07:02:25', '2021-09-29 07:02:55', NULL, NULL, NULL, 0, NULL, '0', NULL),
(9, 'Large3', NULL, 'large3', NULL, NULL, 1, 2, 1, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, '2021-09-29 07:03:30', '2021-09-29 07:03:07', '2021-09-29 07:03:30', NULL, NULL, NULL, 0, NULL, '0', NULL);


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
(5, 15, NULL, NULL),
(2, 14, NULL, NULL),
(3, 14, NULL, NULL),
(4, 14, NULL, NULL),
(5, 15, NULL, NULL),
(6, 16, NULL, NULL),
(7, 2, '2021-09-29 07:01:37', '2021-09-29 07:01:37'),
(8, 2, '2021-09-29 07:02:25', '2021-09-29 07:02:25'),
(9, 2, '2021-09-29 07:03:07', '2021-09-29 07:03:07');

-- --------------------------------------------------------

--
-- Dumping data for table `vendor_media`
--

INSERT INTO `vendor_media` (`id`, `media_type`, `vendor_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'prods/x0K7cnRGStIVd1eTGMeUtlS5P88LuGfUxED0PJCh.jpg', '2021-09-29 06:55:58', '2021-09-29 06:55:58'),
(2, 1, 2, 'prods/BXw5jbuASfUX10zPM8QwCiFzKxiDpEX3zfGIQQ1F.jpg', '2021-09-29 06:56:15', '2021-09-29 06:56:15'),
(3, 1, 2, 'prods/FCK22ETsfYameGMYPlog7y0MkFKXEqcDDc5aUDx9.jpg', '2021-09-29 06:56:30', '2021-09-29 06:56:30'),
(4, 1, 2, 'prods/FPKGSfI0hZDmxgKvnaICosto2xsTMy5dJigoaumf.jpg', '2021-09-29 06:56:53', '2021-09-29 06:56:53'),
(5, 1, 2, 'prods/mpPqJMDzZKppDoHse7mwDTawWIEQbr0UHHV8Y1BN.jpg', '2021-09-29 06:56:59', '2021-09-29 06:56:59'),
(6, 1, 2, 'prods/CHAu0pGLv7nS7GJGRg2C7bBNWRl3SMhxtHpQBgjk.jpg', '2021-09-29 06:57:10', '2021-09-29 06:57:10'),
(7, 1, 2, 'prods/zaa8WbNpcaLV4tr6IHcQAR6DmsbfYLonhEIlxTyX.jpg', '2021-09-29 06:57:15', '2021-09-29 06:57:15'),
(8, 1, 2, 'prods/QbTNrhKiRI4NxQcJHVIW9qdmr6cZ5yblNc0ei93f.jpg', '2021-09-29 06:57:46', '2021-09-29 06:57:46'),
(9, 1, 2, 'prods/pxLweQrJhxGAvL2QlafkEWwgeD4gHHUPWNmuMWdh.jpg', '2021-09-29 06:57:54', '2021-09-29 06:57:54'),
(10, 1, 2, 'prods/myJRaAHYUbMV4vdSANIVBamtOcT1bSaQbg8NX1dy.jpg', '2021-09-29 06:58:02', '2021-09-29 06:58:02'),
(11, 1, 2, 'prods/8GBVqJ1bTqQNa87Pnh7cf3TIZvkWt8GSl4esuwr6.jpg', '2021-09-29 06:58:06', '2021-09-29 06:58:06'),
(12, 1, 2, 'prods/SEIft5qS6sHC0m8dKwDoZ9lXjcF3SXz5FBPhUatv.jpg', '2021-09-29 06:58:58', '2021-09-29 06:58:58'),
(13, 1, 2, 'prods/BzUzGTFFp70jz2WAQxLAy2OrswdH8XBpfsFeENVF.jpg', '2021-09-29 06:59:02', '2021-09-29 06:59:02'),
(14, 1, 2, 'prods/Y06jeN51gPkKNwPgD3XUCYW6epj2nOhC6PNJkGIv.jpg', '2021-09-29 06:59:09', '2021-09-29 06:59:09'),
(15, 1, 2, 'prods/HfkTm308MamECpX2Wu41xZefHkztKVIialtk2OEQ.jpg', '2021-09-29 06:59:13', '2021-09-29 06:59:13'),
(16, 1, 2, 'prods/hXhQ0zoeVfGjT2krMZJ09aN1FRgnSfqMQgm3ZhO5.jpg', '2021-09-29 06:59:18', '2021-09-29 06:59:18'),
(17, 1, 2, 'prods/HmzH3QjEZHv2Tq5gncQypCnWZz4MFLTzHlcUqeai.jpg', '2021-09-29 06:59:44', '2021-09-29 06:59:44'),
(18, 1, 1, 'prods/Y7fFoJTHRj2NtMYNelce09hamhojRBnfEmv4v6lR.jpg', '2021-09-29 07:01:52', '2021-09-29 07:01:52'),
(19, 1, 1, 'prods/LSvkg0gyBy9ojDeCBJTE2TTcA02TcY1MtGkvVuDX.jpg', '2021-09-29 07:02:51', '2021-09-29 07:02:51'),
(20, 1, 1, 'prods/V3deImxLrVr67rIVfrwaN45ORyfPH0kWr0scwpt5.jpg', '2021-09-29 07:03:27', '2021-09-29 07:03:27');

-- --------------------------------------------------------
--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `media_id`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, NULL, NULL),
(2, 2, 2, 1, NULL, NULL),
(3, 2, 3, 1, NULL, NULL),
(4, 3, 4, 1, NULL, NULL),
(5, 3, 5, 1, NULL, NULL),
(6, 3, 6, 1, NULL, NULL),
(7, 3, 7, 1, NULL, NULL),
(8, 4, 8, 1, NULL, NULL),
(9, 4, 9, 1, NULL, NULL),
(10, 4, 10, 1, NULL, NULL),
(11, 4, 11, 1, NULL, NULL),
(12, 5, 12, 1, NULL, NULL),
(13, 5, 13, 1, NULL, NULL),
(14, 5, 14, 1, NULL, NULL),
(15, 5, 16, 1, NULL, NULL),
(16, 6, 17, 1, NULL, NULL),
(17, 7, 18, 1, NULL, NULL),
(18, 8, 19, 1, NULL, NULL),
(19, 9, 20, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `product_translations`
--

INSERT INTO `product_translations` (`id`, `title`, `body_html`, `meta_title`, `meta_keyword`, `meta_description`, `product_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Xiaomi', NULL, 'Xiaomi', 'Xiaomi', NULL, 1, 1, NULL, '2021-09-29 07:09:51'),
(2, 'RoyoXL', NULL, NULL, NULL, NULL, 2, 1, NULL, '2021-09-29 07:09:51'),
(3, 'RoyoXL', '', '', '', '', 2, 1, NULL, '2021-09-29 07:09:51'),
(4, 'Royo Platinum', NULL, NULL, NULL, NULL, 3, 1, NULL, '2021-09-29 07:09:51'),
(5, 'RoyoXL', '', '', '', '', 2, 1, NULL, '2021-09-29 07:09:51'),
(6, 'Royo Platinum', '', '', '', '', 3, 1, NULL, '2021-09-29 07:09:51'),
(7, 'Royo Pool', NULL, NULL, NULL, NULL, 4, 1, NULL, '2021-09-29 07:09:51'),
(8, 'RoyoXL', '', '', '', '', 2, 1, NULL, '2021-09-29 07:09:51'),
(9, 'Royo Platinum', '', '', '', '', 3, 1, NULL, '2021-09-29 07:09:51'),
(10, 'Royo Pool', '', '', '', '', 4, 1, NULL, '2021-09-29 07:09:51'),
(11, 'Royo Moto', NULL, NULL, NULL, NULL, 5, 1, NULL, '2021-09-29 07:09:51'),
(12, 'RoyoXL', '', '', '', '', 2, 1, NULL, '2021-09-29 07:09:51'),
(13, 'Royo Platinum', '', '', '', '', 3, 1, NULL, '2021-09-29 07:09:51'),
(14, 'Royo Pool', '', '', '', '', 4, 1, NULL, '2021-09-29 07:09:51'),
(15, 'Royo Moto', '', '', '', '', 5, 1, NULL, '2021-09-29 07:09:51'),
(16, 'Royo Auto', NULL, NULL, NULL, NULL, 6, 1, NULL, '2021-09-29 07:09:51'),
(17, 'Small Box', NULL, NULL, NULL, NULL, 7, 1, NULL, '2021-09-29 07:09:51'),
(18, 'Medium Box', NULL, NULL, NULL, NULL, 8, 1, NULL, '2021-09-29 07:09:51'),
(19, 'Large Box', NULL, NULL, NULL, NULL, 9, 1, NULL, '2021-09-29 07:09:51');

-- --------------------------------------------------------

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `sku`, `product_id`, `title`, `quantity`, `price`, `position`, `compare_at_price`, `barcode`, `cost_price`, `currency_id`, `tax_category_id`, `inventory_policy`, `fulfillment_service`, `inventory_management`, `created_at`, `updated_at`, `status`) VALUES
(1, 'sku-id163289886557b3a00', 1, NULL, 100, '500.00', 1, '500.00', '7543ebf012007e', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:01:05', 1),
(2, 'sku-id16328988651739718', 1, 'sku-id-Black-Black', 100, '500.00', 1, '500.00', '1500cdf2d597df', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:01:05', 1),
(3, 'sku-id1632898865877f6ad', 1, 'sku-id-Black-Grey', 100, '500.00', 1, '500.00', '2ea56327679387', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:01:05', 1),
(4, 'sku-id1632898865935b23b', 1, 'sku-id-Medium-Black', 100, '500.00', 1, '500.00', '8f47f11a19433f', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:01:05', 1),
(5, 'sku-id1632898865ca66561', 1, 'sku-id-Medium-Grey', 100, '500.00', 1, '500.00', '8f7318b112bbe9', '300.00', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:01:05', 1),
(6, 'CAB584', 2, NULL, 0, NULL, 1, NULL, '89dcea62735137', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 06:55:17', '2021-09-29 06:56:35', 1),
(7, 'CAB585', 3, NULL, 0, NULL, 1, NULL, '78862907feaf04', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 06:55:17', '2021-09-29 06:57:19', 1),
(8, 'CAB586', 4, NULL, 0, NULL, 1, NULL, 'c6a865b50bde13', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 06:55:17', '2021-09-29 06:58:10', 1),
(9, 'CAB587', 5, NULL, 0, NULL, 1, NULL, '89802c0256ccd9', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 06:55:17', '2021-09-29 06:59:24', 1),
(10, 'CAB588', 6, NULL, 0, NULL, 1, NULL, 'dcfe4743043475', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 06:55:17', '2021-09-29 06:59:45', 1),
(11, 'Small1', 7, NULL, 0, NULL, 1, NULL, 'c737cb4c1dc3e2', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:01:37', '2021-10-05 04:50:40', 1),
(12, 'Medium2', 8, NULL, 0, NULL, 1, NULL, '4776bf460c77cb', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:02:25', '2021-10-05 04:50:17', 1),
(13, 'Large3', 9, NULL, 0, NULL, 1, NULL, 'c91af13d9eca13', NULL, NULL, NULL, NULL, NULL, NULL, '2021-09-29 07:03:07', '2021-09-29 07:03:30', 1);
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
(1, 'Small', 1, 1, NULL, '2021-09-29 07:09:51'),
(2, 'White', 2, 1, NULL, '2021-09-29 07:09:51'),
(3, 'Black', 3, 1, NULL, '2021-09-29 07:09:51'),
(4, 'Grey', 4, 1, NULL, '2021-09-29 07:09:51'),
(5, 'Medium', 5, 1, NULL, '2021-09-29 07:09:51'),
(6, 'Large', 6, 1, NULL, '2021-09-29 07:09:51'),
(7, 'IPhone', 7, 1, NULL, '2021-09-29 07:09:51'),
(8, 'Samsung', 8, 1, NULL, '2021-09-29 07:09:51'),
(9, 'Xiaomi', 9, 1, NULL, '2021-09-29 07:09:51');

-- --------------------------------------------------------


--
-- Dumping data for table `variant_translations`
--
INSERT INTO `variant_translations` (`id`, `title`, `variant_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Size', 1, 1, NULL, '2021-09-29 07:09:51'),
(2, 'Color', 2, 1, NULL, '2021-09-29 07:09:51'),
(3, 'Phones', 3, 1, NULL, '2021-09-29 07:09:51');



-- --------------------------------------------------------

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `vendor_id`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 14, 1, '2021-09-29 06:54:44', '2021-09-29 06:54:44'),
(2, 2, 15, 1, '2021-09-29 06:54:46', '2021-09-29 06:54:46'),
(3, 2, 16, 1, '2021-09-29 06:54:47', '2021-09-29 06:54:47'),
(4, 1, 2, 1, '2021-09-29 07:01:21', '2021-09-29 07:01:21');

-- --------------------------------------------------------

INSERT INTO `mobile_banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Taxi 1', NULL, 'banner/UKarszRa3k84hl0d7uFJO8MWxeTTuqTqq140iQ0W.jpg', 0, 1, 1, '2021-09-29 12:36:00', '2022-09-30 12:00:00', NULL, NULL, NULL, NULL, '2021-09-29 07:07:34');


INSERT INTO `cab_booking_layouts` (`id`, `title`, `slug`, `order_by`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Vendors', 'vendors', 2, 0, NULL, '2021-09-29 07:05:26'),
(2, 'Featured Products', 'featured_products', 3, 0, NULL, '2021-09-29 07:05:26'),
(3, 'New Products', 'new_products', 4, 0, NULL, '2021-09-29 07:05:26'),
(4, 'On Sale', 'on_sale', 5, 0, NULL, '2021-09-29 07:05:26'),
(5, 'Best Sellers', 'best_sellers', 6, 0, NULL, '2021-09-29 07:05:26'),
(6, 'Brands', 'brands', 7, 0, NULL, '2021-09-29 07:05:26'),
(7, 'Pickup Delivery', 'pickup_delivery', 1, 1, '2021-09-29 07:05:07', '2021-09-29 07:05:26'),
(8, 'Dynamic Page', 'dynamic_page', 8, 1, '2021-10-14 01:05:38', '2021-10-14 01:05:38');



INSERT INTO `cab_booking_layout_categories` (`id`, `cab_booking_layout_id`, `category_id`, `created_at`, `updated_at`) VALUES
(2, 7, 2, '2021-09-29 07:05:19', '2021-09-29 07:05:19');


INSERT INTO `cab_booking_layout_transaltions` (`id`, `title`, `cab_booking_layout_id`, `language_id`, `created_at`, `updated_at`, `body_html`) VALUES
(1, NULL, 1, 1, '2021-09-29 07:05:19', '2021-09-29 07:05:19', NULL),
(2, NULL, 2, 1, '2021-09-29 07:05:19', '2021-09-29 07:05:19', NULL),
(3, NULL, 3, 1, '2021-09-29 07:05:19', '2021-09-29 07:05:19', NULL),
(4, NULL, 4, 1, '2021-09-29 07:05:19', '2021-09-29 07:05:19', NULL),
(5, NULL, 5, 1, '2021-09-29 07:05:19', '2021-09-29 07:05:19', NULL),
(6, NULL, 6, 1, '2021-09-29 07:05:19', '2021-09-29 07:05:19', NULL),
(7, NULL, 7, 1, '2021-09-29 07:05:19', '2021-09-29 07:05:19', NULL),
(8, NULL, 8, 1, '2021-10-14 01:06:39', '2021-10-14 01:06:39', '<div class=\"cab-content-area\">\n\n        <!-- Royo Business Start From Here -->\n        <section class=\"royo-business p-0\">\n            <div class=\"container p-64\">\n                <div class=\"row\">\n                    <div class=\"col-12\">\n                        <h2 class=\"title-36\">Royo for Business</h2>\n                        <div class=\"description-text\">\n                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, labore!</p>\n                        </div>\n                        <a class=\"btn btn-solid new-btn d-inline-block\" href=\"#\">See how</a>\n                    </div>\n                </div>\n            </div>\n        </section>\n\n        <!-- Royo Business Start From Here -->\n        <section class=\"royo-rental p-0\">\n            <div class=\"container\">                \n               \n                <div class=\"row align-items-center p-64\">\n                    <div class=\"col-sm-6\">\n                        <div class=\"cab-img-box\">\n                            <img class=\"img-fluid\" src=\"https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_1116,h_744/v1624484990/assets/fa/f20c42-425a-4243-866b-b480d3bd68b4/original/gettyimages-1139275491-2048x2048_With-Mask.png\" alt=\"\">\n                        </div>\n                    </div>\n                    <div class=\"offset-md-1 col-sm-6 col-md-5 pl-lg-4\">\n                        <div class=\"\">\n                            <h2 class=\"title-52\">Royo for Business</h2>\n                            <div class=\"description-text\">\n                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem nisi officiis numquam!</p>\n                            </div>\n                            <a class=\"learn-more bottom-line\" href=\"#\">Learn more</a>\n                        </div>\n                    </div>\n                </div>\n\n                <div class=\"row align-items-center p-64\">\n                    <div class=\"col-sm-6 order-md-1\">\n                        <div class=\"cab-img-box\">\n                            <img class=\"img-fluid\" src=\"https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1623719981/assets/4d/b05e4c-7340-40c4-a3e9-da0de41f14fc/original/rentals-iindia.jpg\" alt=\"\">\n                        </div>\n                    </div>\n                    <div class=\"col-sm-6 order-md-0\">\n                        <div class=\"pr-lg-5 mr-lg-5\">\n                            <h2 class=\"title-52\">Royo Intercity </h2>\n                            <div class=\"description-text\">\n                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem nisi officiis numquam!</p>\n                            </div>\n                            <a class=\"learn-more\" href=\"#\">Learn more</a>\n                        </div>\n                    </div>\n                </div>\n                \n            </div>\n        </section>\n\n        <!-- Focused On Safety Start From Here -->\n        <section class=\"focused-on-safety p-0\">\n            <div class=\"container p-64\">\n                <div class=\"row mb-4 pb-2\">\n                    <div class=\"col-12\">\n                        <div class=\"title-36\">Focused on safety, wherever you go</div>\n                    </div>\n                </div>\n                <div class=\"row\">\n                    <div class=\"col-md-6\">\n                        <div class=\"safety-box\">\n                            <div class=\"safety-img\">\n                                <img class=\"img-fluid\" src=\"https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1613520218/assets/3e/e98625-31e6-4536-8646-976a1ee3f210/original/Safety_Home_Img2x.png\" alt=\"\">\n                            </div>\n                            <div class=\"safety-content\">\n                                <h3 class=\"mt-0\">Our commitment to your safety</h3>\n                                <div class=\"safety-text\">\n                                    <p>With every safety feature and every standard in our Community Guidelines, we\'re committed to helping to create a safe environment for our users.</p>\n                                </div>\n                                <div class=\"safety-links\">\n                                    <a class=\"bottom-line\" href=\"#\">\n                                        <span>Read about our Community Guidelines</span>\n                                    </a>\n                                    <a class=\"bottom-line\" href=\"#\">\n                                        <span>See all safety features</span>\n                                    </a>\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n                    <div class=\"col-md-6\">\n                        <div class=\"safety-box\">\n                            <div class=\"safety-img\">\n                                <img class=\"img-fluid\" src=\"https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1613520218/assets/3e/e98625-31e6-4536-8646-976a1ee3f210/original/Safety_Home_Img2x.png\" alt=\"\">\n                            </div>\n                            <div class=\"safety-content\">\n                                <h3 class=\"mt-0\">Setting 10,000+ cities in motion</h3>\n                                <div class=\"safety-text\">\n                                    <p>With every safety feature and every standard in our Community Guidelines, we\'re committed to helping to create a safe environment for our users.</p>\n                                </div>\n                                <div class=\"safety-links\">\n                                    <a class=\"bottom-line\" href=\"#\">\n                                        <span>View all cities</span>\n                                    </a>\n                                </div>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n        </section>\n\n    </div>');






UPDATE `client_preferences` SET `business_type` = 'taxi' WHERE `client_preferences`.`id` = 1;

UPDATE `client_preferences` SET `is_hyperlocal` = 0 WHERE `client_preferences`.`id` = 1;
