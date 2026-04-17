<?


















INSERT INTO `clients` (`id`, `name`, `email`, `phone_number`, `password`, `encpass`, `country_id`, `timezone`, `custom_domain`, `is_deleted`, `is_blocked`, `database_path`, `database_name`, `database_username`, `database_password`, `logo`, `company_name`, `company_address`, `status`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Varun', 'Varun@varun.com', '8877665567', '$2y$10$dxxKmnIK9AZY0YwTlJV9He6W99C20.h0wISlAxIFEH0vSIxwWHOwS', '11qq22ww', NULL, 'Africa/Abidjan', 'Varun12345', 0, 0, 'Varun', 'varun', 'Varun', 'Varun', 'Clientlogo/IpuIfxZHlM8zpgVeajUfexxnp0GG9yK1CKyKjIVr.jpg', 'Varun Comp', 'Varun Deldi', 0, '1f6993', NULL, '2021-01-12 07:16:57');

INSERT INTO `client_preferences` (`id`, `client_code`, `theme_admin`, `distance_unit`, `currency_id`, `language_id`, `date_format`, `time_format`, `fb_login`, `fb_client_id`, `fb_client_secret`, `fb_client_url`, `twitter_login`, `twitter_client_id`, `twitter_client_secret`, `twitter_client_url`, `google_login`, `google_client_id`, `google_client_secret`, `google_client_url`, `apple_login`, `apple_client_id`, `apple_client_secret`, `apple_client_url`, `Default_location_name`, `Default_latitude`, `Default_longitude`, `map_provider`, `map_key`, `map_secret`, `sms_provider`, `sms_key`, `sms_secret`, `sms_from`, `verify_email`, `verify_phone`, `web_template_id`, `app_template_id`, `personal_access_token_v1`, `personal_access_token_v2`, `mail_type`, `mail_driver`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from`, `created_at`, `updated_at`, `is_hyperlocal`, `need_delivery_service`, `delivery_service_key`, `dispatcher_key`) VALUES
(1, '1f6993', 'light', 'metric', 80, NULL, 'YYYY-MM-DD', '12', 1, 'fbKey', 'fbKey1', 'fbKey2', 0, 'Twitter', 'Twitter1', 'Twitter2', 0, 'Goodle', 'Goodle1', 'Goodle2', 1, 'Apple', 'Apple1', 'Apple2', 'Chandigarh, Punjab, India', '30.53899440', '75.95503290', 1, 'adasdad1112221', NULL, 1, 'wqeqwe', 'wqeqwe', 'qweqw', 1, 0, 1, 2, 'JoggWmjnjMVp67jufIFFV6Q4prJtvX', 'tgJQyy9lvX37lDchH3ggnuA7CeYSsAVHkzSsV7SHXnXaujUDjbARhHRQ2I28', 'smtp', 'Mail Driver', 'ddqqw', 22, 'qweqweqw', 'wewerwr', 'tlc', 'asasas@gmail.com', '2020-12-29 07:07:10', '2021-02-23 01:44:53', 1, 1, 'asasfaasdhv', 'ssaassadsadd');

INSERT INTO `client_currencies` (`client_code`, `currency_id`, `created_at`, `updated_at`) VALUES
('1f6993', 147, '2021-01-14 01:51:27', '2021-01-14 01:51:27'),
('1f6993', 86, '2021-01-14 01:57:58', '2021-01-14 01:57:58'),
('1f6993', 121, '2021-01-14 01:57:58', '2021-01-14 01:57:58');



INSERT INTO `client_languages` (`id`, `client_code`, `language_id`, `created_at`, `updated_at`) VALUES
(1, '1f6993', 1, '2020-12-30 01:18:17', '2020-12-30 01:18:17'),
(2, '1f6993', 2, '2020-12-30 01:18:17', '2020-12-30 01:18:17'),
(3, '1f6993', 3, '2021-01-14 01:51:27', '2021-01-14 01:51:27'),
(4, '1f6993', 4, '2021-01-21 06:33:09', '2021-01-21 06:33:09');

INSERT INTO `vendors` (`id`, `name`, `desc`, `logo`, `banner`, `address`, `latitude`, `longitude`, `order_min_amount`, `order_pre_time`, `auto_reject_time`, `commission_percent`, `commission_fixed_per_order`, `commission_monthly`, `dine_in`, `takeaway`, `delivery`, `status`, `created_at`, `updated_at`, `add_category`, `setting`) VALUES
(1, 'Mac donald', 'Mac donald cbd', 'default/default_logo.png', 'default/default_image.png', 'Chandigarh, India', '30.73331480', '76.7794179000', '500.00', '22', '60', 3, '40.00', '1000.00', 0, 1, 1, 1, '2021-01-06 01:10:47', '2021-01-14 00:30:34', 1, 0),
(2, 'test', 'sa', 'vendor/60002a496c839.jpeg', 'vendor/60002a496f0c8.jpeg', 'Delhi, India', '28.68627380', '77.2217831000', '0.00', '22', '60', 1, '0.00', '0.00', 0, 1, 1, 1, '2021-01-14 05:56:01', '2021-01-18 08:13:26', 1, 0);


INSERT INTO `vendor_media` (`id`, `media_type`, `vendor_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'prods/601a37938ec68.jpg', '2021-02-03 00:11:39', '2021-02-03 00:11:39'),
(2, 1, 1, 'prods/601a379391198.jpg', '2021-02-03 00:11:39', '2021-02-03 00:11:39'),
(3, 1, 1, 'prods/601a38e8b5e3c.jpeg', '2021-02-03 00:17:20', '2021-02-03 00:17:20'),
(4, 1, 1, 'prods/601a38e8b9317.jpg', '2021-02-03 00:17:20', '2021-02-03 00:17:20'),
(5, 1, 1, 'prods/601a3f65a2dbc.JPG', '2021-02-03 00:45:01', '2021-02-03 00:45:01'),
(6, 1, 1, 'prods/601a40bc93f7f.jpg', '2021-02-03 00:50:44', '2021-02-03 00:50:44'),
(7, 1, 1, 'prods/601a40f7a6ec1.jpeg', '2021-02-03 00:51:43', '2021-02-03 00:51:43'),
(8, 1, 1, 'prods/601a451d36c56.jpeg', '2021-02-03 01:09:25', '2021-02-03 01:09:25'),
(9, 1, 1, 'prods/601a468bbf58b.jpg', '2021-02-03 01:15:31', '2021-02-03 01:15:31'),
(10, 1, 1, 'prods/601a468bbf9bb.jpeg', '2021-02-03 01:15:31', '2021-02-03 01:15:31'),
(11, 1, 1, 'prods/601a46993f8d0.jpg', '2021-02-03 01:15:45', '2021-02-03 01:15:45'),
(12, 1, 1, 'prods/601a469941460.png', '2021-02-03 01:15:45', '2021-02-03 01:15:45'),
(13, 1, 1, 'prods/601a46ada432e.png', '2021-02-03 01:16:05', '2021-02-03 01:16:05'),
(14, 1, 1, 'prods/601a46ada5cc2.jpg', '2021-02-03 01:16:05', '2021-02-03 01:16:05'),
(15, 1, 1, 'prods/601a4816c1562.jpg', '2021-02-03 01:22:06', '2021-02-03 01:22:06'),
(16, 1, 1, 'prods/601a4816c3686.jpeg', '2021-02-03 01:22:06', '2021-02-03 01:22:06'),
(17, 1, 1, 'prods/601a48680cee8.jpg', '2021-02-03 01:23:28', '2021-02-03 01:23:28'),
(18, 1, 1, 'prods/601a48680d545.png', '2021-02-03 01:23:28', '2021-02-03 01:23:28'),
(19, 1, 1, 'prods/601a6a9bc18ea.jpg', '2021-02-03 03:49:23', '2021-02-03 03:49:23');


INSERT INTO `service_areas` (`id`, `name`, `description`, `geo_array`, `zoom_level`, `vendor_id`, `polygon`, `created_at`, `updated_at`) VALUES
(4, 'Chandigarh, India', 'defaut', '(30.781127921936033, 76.79030811472472),(30.76991885106682, 76.78481495066222),(30.760773641875268, 76.78275501413879),(30.749267144554732, 76.80232441111144),(30.756348228713083, 76.8273869721466),(30.769328863793827, 76.84832966013488),(30.79410521437037, 76.84523975534972),(30.80649099596058, 76.82910358591613),(30.81268328816784, 76.80438434763488),(30.80206768576443, 76.7999211518341),(30.78761678706821, 76.789278146463)', 12, 1, '\0\0\0\0\0\0\0\0\0\0\0\0\0,\B5\DE\FF\F7\C7>@d\A0|h\942S@\E8\E7\DDf\C5>@d\A0|h:2S@Z\83\B7\C2\C2>@d\A0|\A82S@sԹ\F8Ͽ>@d\A0|HY3S@ѵ\9A  \A0\C1>@d\A0|\E8\F34S@݅\BC\F2\C4>@d\A0|K6S@\C6M\B5zJ\CB>@d\A0|h6S@N+\A41v\CE>@d\A0|5S@Ư\D0>@d\A0|{3S@a\89\CFNT\CD>@d\A0|\E813S@=\F6@\A1\C9>@d\A0|\88\832S@,\B5\DE\FF\F7\C7>@d\A0|h\942S@', '2021-01-11 07:35:20', '2021-01-11 07:35:20'),
(5, 'First', 'Area Description', '(30.74649357756969, 76.70258915110168),(30.739559311815203, 76.69829761667785),(30.730706332381974, 76.69366275950011),(30.716982607085626, 76.69434940500793),(30.70635644592969, 76.69795429392394),(30.705028093474883, 76.71117221994933),(30.715359241564492, 76.71924030466613),(30.73247699333457, 76.7259350983673),(30.740297023386926, 76.71100055857238),(30.74664110971575, 76.70825397654113)', 16, 1, '\0\0\0\0\0\0\0\0\0\0\0\0\0\FBU\FE3\Z\BF>@d\A0|8\F7,S@ղQ\C2S\BD>@d\A0|\E8\B0,S@\89\8F\F8\91\BB>@d\A0|\F8d,S@\CC;,\8C\B7>@d\A0|8p,S@9\96\AA\C6Ӵ>@d\A0|H\AB,S@a<\9C\B8|\B4>@d\A0|؃-S@.i\83\C8!\B7>@d\A0|.S@\C3q\BB\9C\83\BB>@d\A0|\B8u.S@\E3\C5\84\BD>@d\A0|\81-S@,\DF#\BF>@d\A0|T-S@\FBU\FE3\Z\BF>@d\A0|8\F7,S@', '2021-01-11 07:59:07', '2021-01-12 04:25:36'),
(6, 'Codebrew', 'Innovations', '(30.71524774805395, 76.80416080233152),(30.71381805527184, 76.80713268992002),(30.710174548809007, 76.8114134955078),(30.709833251280152, 76.81642386194761),(30.712074723782475, 76.82370874163206),(30.712794197739743, 76.82763549562986),(30.71735996505325, 76.82955595728453),(30.724406502824337, 76.83004948374327),(30.728427594608476, 76.82659479853208),(30.73096374742619, 76.81998583551939),(30.73020751975065, 76.8097397970825),(30.727980302552655, 76.80433246370848),(30.723262858928763, 76.8011138128906),(30.718457561733132, 76.80125328775938),(30.716972575006942, 76.80248710390623)', 16, 1, '\0\0\0\0\0\0\0\0\0\0\0\0\0*\8CT\FF\CB>@\A2o\E4\AC].S@oH\B3\8E\CA>@\A2o\E49.S@\98ܸ7\CA>@\A2o\E4\D1-S@oH\B3\8E\CA>@\A2o\E4\AB-S@kg\C29)\CA>@\A2o\E4\\\90-S@Z\E5*i\C7>@\A2o\E4\EC\B4-S@\0u\87=\C6>@\A2o\E4d\C4-S@֥\CAa\C9\C4>@\A2o\E44N.S@7oH\FE\C1>@\A2o\E4\84/S@\94\8FU_\B1\C5>@\A2o\E4\C40S@TȨB\C9>@\A2o\E4!0S@\9A=Ɓ\CB>@\A2o\E4\DC0S@@TĞ\CB>@\A2o䔰/S@?\EDg\FF\CB>@\A2o\E4\9C/S@y\BFdi\E2\CB>@\A2o䬷.S@*\8CT\FF\CB>@\A2o\E4\AC].S@', '2021-01-11 23:57:25', '2021-01-12 05:01:10'),
(7, 'Dhanas', 'Dhanas', '(30.793304403439983, 76.72446749027452),(30.791239934143068, 76.72223589237413),(30.789912751900914, 76.71588442142686),(30.791239934143068, 76.713566992838),(30.789691553079518, 76.71193620975694),(30.7789484269209, 76.71416780765733),(30.774376360028327, 76.71511194523057),(30.768697845434723, 76.72352335270128),(30.7577823569147, 76.73605463321886),(30.772237738026504, 76.75047418888292),(30.785511175352738, 76.7520191412755),(30.794948949712854, 76.75176164921007),(30.79539132265586, 76.74515268619737),(30.796865884430414, 76.73459551151475),(30.796423518273034, 76.72996065433702)', 16, 1, '\0\0\0\0\0\0\0\0\0\0\0\0\0*\8CT\FF\CB>@\A2o\E4\AC].S@oH\B3\8E\CA>@\A2o\E49.S@\98ܸ7\CA>@\A2o\E4\D1-S@oH\B3\8E\CA>@\A2o\E4\AB-S@kg\C29)\CA>@\A2o\E4\\\90-S@Z\E5*i\C7>@\A2o\E4\EC\B4-S@\0u\87=\C6>@\A2o\E4d\C4-S@֥\CAa\C9\C4>@\A2o\E44N.S@7oH\FE\C1>@\A2o\E4\84/S@\94\8FU_\B1\C5>@\A2o\E4\C40S@TȨB\C9>@\A2o\E4!0S@\9A=Ɓ\CB>@\A2o\E4\DC0S@@TĞ\CB>@\A2o䔰/S@?\EDg\FF\CB>@\A2o\E4\9C/S@y\BFdi\E2\CB>@\A2o䬷.S@*\8CT\FF\CB>@\A2o\E4\AC].S@', '2021-01-12 00:16:32', '2021-01-12 05:01:29'),
(10, 'mohali area', 'mohali area Description', '(30.705019951091952, 76.71701693626918),(30.703710029955875, 76.71650195213832),(30.70151448844952, 76.71697402092494),(30.697252412325376, 76.71725297066249),(30.68682706062567, 76.71326184364833),(30.6818077287653, 76.7160298833517),(30.681392514997025, 76.72441983315028),(30.682490520857865, 76.73109316917933),(30.689668772536287, 76.73111462685145),(30.69048067486138, 76.73467660042323),(30.698248758667752, 76.73019194695033),(30.70404107762136, 76.72817614312399),(30.705000454932623, 76.7272963785671),(30.705959822703903, 76.72532227273214),(30.705535488135528, 76.72036555047262)', 16, 1, '\0\0\0\0\0\0\0\0\0\0\0\0\0\AB\F7\00|\B4>@\FD\0\9B\E3-S@\87,W&\B4>@\FD\0+\DB-S@\94\8Ft\96\B3>@\FD\0\E7\E2-S@\9E\FES\"\B2>@\FD\0y\E7-S@#e\F3\E5ӯ>@\FD\0\A6-S@\FE4\89\F3\8A\AE>@\FD\0o\D3-S@æg\BDo\AE>@\FD\0\E5\\.S@\EAⲷ\AE>@\FD\0;\CA.S@\A6\F7!\8E\B0>@\FD\0\95\CA.S@\B5mWð>@\FD\0\F1/S@Z\F3>n\C0\B2>@\FD\0w\BB.S@\A1l;  <\B4>@zp\9A.S@ʙ\E9\E8z\B4>@z\8C.S@\A0\CDnȹ\B4>@z\AEk.S@\A1\B5G\F9\9D\B4>@zx\Z.S@\AB\F7\00|\B4>@\FD\0\9B\E3-S@', '2021-01-12 01:00:39', '2021-01-12 05:02:21'),
(18, 'Sohan', 'asdddddddd', '(30.77571817533361, 76.61246009022761),(30.73913432973638, 76.58774085194636),(30.697222857739856, 76.5839643016534),(30.65706521690579, 76.64335913807918),(30.64968148207086, 76.74944586903621),(30.662676478195273, 76.79957099110652),(30.720837190650307, 76.83630652577449),(30.77866787513938, 76.83493323475886),(30.80344182003866, 76.8095273509698),(30.8078650669849, 76.74807257802058),(30.80963430875229, 76.68249793202449)', 12, 2, '\0\0\0\0\0\0\0\0\0\0\0\0\0|\F8aw\95\C6>@\80h΋2\'S@\92\91M\E87\BD>@\80h΋\9D%S@/|2}\B2>@\80hΫ_%S@\F3m5\A8>@\80h\CE\CB,)S@\A8O\8E\86Q\A6>@\80h\CE\EB\F6/S@Z\ADi*\A5\A9>@\80h\CE+,3S@f\95?Ɉ\B8>@\80h\CE\865S@a+\"\C7V\C7>@\80h΋o5S@N\F5\\\AE\CD>@\80h\CEK\CF3S@\EFG\BA>\D0\CE>@\80h\CEk\E0/S@\87ϭ1D\CF>@\80h\CE\AE+S@|\F8aw\95\C6>@\80h΋2\'S@', '2021-02-20 06:58:21', '2021-02-20 06:58:21');


INSERT INTO `slot_days` (`id`, `slot_id`, `day`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL),
(2, 1, 3, NULL, NULL),
(3, 1, 4, NULL, NULL),
(12, 4, 6, '2021-01-11 00:29:40', '2021-01-11 00:29:40'),
(13, 5, 5, '2021-01-11 00:32:59', '2021-01-11 00:32:59'),
(15, 7, 3, NULL, NULL),
(18, 8, 4, '2021-01-11 02:43:09', '2021-01-11 02:43:09'),
(19, 9, 5, NULL, NULL),
(20, 9, 6, NULL, NULL),
(21, 9, 7, NULL, NULL),
(22, 10, 1, NULL, NULL);


INSERT INTO `vendor_slots` (`id`, `vendor_id`, `category_id`, `geo_id`, `start_time`, `end_time`, `dine_in`, `takeaway`, `delivery`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '10:00:00', '19:30:00', 1, 1, 1, '2021-01-08 07:49:32', '2021-01-08 07:49:32'),
(3, 1, NULL, NULL, '04:00:00', '04:30:00', 1, 1, 1, '2021-01-08 08:41:30', '2021-01-08 08:41:30'),
(4, 1, NULL, NULL, '09:00:00', '19:30:00', 1, 1, 1, '2021-01-11 00:29:40', '2021-01-11 00:29:40'),
(5, 1, NULL, NULL, '10:30:00', '19:30:00', 1, 1, 1, '2021-01-11 00:32:59', '2021-01-11 00:32:59'),
(7, 1, NULL, NULL, '03:00:00', '07:00:00', 1, 1, 1, '2021-01-11 02:42:47', '2021-01-11 02:42:47'),
(8, 1, NULL, NULL, '03:00:00', '08:00:00', 1, 1, 1, '2021-01-11 02:43:09', '2021-01-11 02:43:09'),
(9, 1, NULL, NULL, '01:00:00', '08:00:00', 1, 1, 1, '2021-01-11 02:50:05', '2021-01-11 02:50:05'),
(10, 1, NULL, NULL, '00:00:00', '23:59:00', 1, 1, 1, '2021-01-14 02:24:30', '2021-01-14 02:24:30');


INSERT INTO `vendor_slot_dates` (`id`, `vendor_id`, `category_id`, `start_time`, `end_time`, `specific_date`, `working_today`, `dine_in`, `takeaway`, `delivery`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '05:00:00', '19:00:00', '2021-01-09', 1, 1, 1, 1, NULL, NULL),
(2, 1, NULL, '06:00:00', '19:30:00', '2021-01-11', 1, 1, 1, 1, '2021-01-11 00:13:52', '2021-01-11 00:13:52'),
(4, 1, NULL, '01:30:00', '05:00:00', '2021-01-12', 1, 1, 1, 1, NULL, '2021-01-12 00:14:10'),
(5, 1, NULL, '03:00:00', '04:00:00', '2021-01-26', 1, 1, 1, 1, '2021-01-11 02:50:38', '2021-01-11 02:50:38'),
(6, 1, NULL, '15:30:00', '21:30:00', '2021-01-12', 1, 0, 1, 0, NULL, NULL),
(7, 2, NULL, '01:30:00', '18:30:00', '2021-01-28', 1, 1, 1, 1, NULL, NULL);


INSERT INTO `categories` (`id`, `icon`, `slug`, `type_id`, `image`, `is_visible`, `status`, `position`, `is_core`, `can_add_products`, `parent_id`, `vendor_id`, `client_code`, `display_mode`, `created_at`, `updated_at`) VALUES
(1, NULL, 'root', NULL, NULL, 0, 1, 1, 1, 1, NULL, NULL, NULL, '1', NULL, NULL),
(2, NULL, 'Electronic', NULL, NULL, 1, 1, 4, 1, 1, 1, NULL, '1f6993', '1', '2021-01-04 06:06:14', '2021-01-22 07:54:52'),
(3, NULL, 'Mobile', NULL, NULL, 1, 1, 1, 1, 1, 2, NULL, '1f6993', '1', '2021-01-04 06:10:10', '2021-01-06 05:17:29'),
(4, 'category/icon/5ff326c76698a.png', 'samsung', NULL, 'category/image/5ff326c769005.jpeg', 1, 1, 1, 1, 1, 3, NULL, '1f6993', '1', '2021-01-04 09:01:35', '2021-01-18 02:37:03'),
(6, 'category/icon/5ff43a7c5df25.jpeg', 'Apple', NULL, 'category/image/5ff43a7c5e126.jpg', 1, 1, 2, 1, 1, 3, NULL, '1f6993', '2', '2021-01-05 04:37:56', '2021-01-18 02:37:03'),
(7, NULL, 'food', NULL, NULL, 1, 1, 3, 1, 1, 1, NULL, '1f6993', '2', '2021-01-06 05:16:48', '2021-01-22 07:54:52'),
(10, 'category/icon/5ff43a7c5df25.jpeg', 'Lava', NULL, 'category/image/5ff43a7c5e126.jpg', 1, 1, 3, 0, 0, 3, 1, '1f6993', '2', '2021-01-05 04:37:56', '2021-01-18 02:37:03'),
(11, 'category/icon/60004b200e784.jpeg', 'Pizza', NULL, 'category/image/60004b200eafc.jpeg', 1, 1, 1, 1, 1, 7, NULL, '1f6993', '1', '2021-01-14 08:16:08', '2021-01-14 08:16:08'),
(12, NULL, 'Cloth', NULL, NULL, 1, 1, 1, 1, 1, 1, NULL, '1f6993', '1', '2021-01-19 05:47:38', '2021-01-19 05:47:38'),
(14, NULL, 'Toy', NULL, 'category/icon/VwRfQmB7812ajFFIPOMdaN0jmmThkjgqhZwa8zNI.jpg', 1, 1, 2, 0, 0, 1, NULL, '1f6993', '1', '2021-01-19 05:53:11', '2021-02-23 06:41:18'),
(15, NULL, 'robot', NULL, NULL, 1, 1, 1, 0, 1, 14, NULL, '1f6993', '1', '2021-01-19 05:53:50', '2021-01-19 05:53:50'),
(16, NULL, 'test', NULL, NULL, 1, 1, 1, 1, 1, 11, NULL, '1f6993', '2', '2021-01-22 01:33:21', '2021-01-22 01:37:36');


INSERT INTO `category_histories` (`id`, `category_id`, `action`, `updater_role`, `update_id`, `client_code`, `created_at`, `updated_at`) VALUES
(1, 14, 'Add', 'Admin', 52, 1, '2021-01-19 05:53:11', '2021-01-19 05:53:11'),
(2, 15, 'Add', 'Admin', 52, 1, '2021-01-19 05:53:50', '2021-01-19 05:53:50'),
(3, 16, 'Add', 'Admin', 52, 1, '2021-01-22 01:33:21', '2021-01-22 01:33:21'),
(4, 16, 'Update', 'Admin', 52, 1, '2021-01-22 01:36:36', '2021-01-22 01:36:36'),
(5, 16, 'Update', 'Admin', 52, 1, '2021-01-22 01:36:48', '2021-01-22 01:36:48'),
(6, 16, 'Update', 'Admin', 52, 1, '2021-01-22 01:37:36', '2021-01-22 01:37:36'),
(7, 14, 'Update', 'Admin', 52, 1, '2021-02-23 06:39:39', '2021-02-23 06:39:39'),
(8, 14, 'Update', 'Admin', 52, 1, '2021-02-23 06:41:18', '2021-02-23 06:41:18');


INSERT INTO `category_translations` (`id`, `name`, `trans-slug`, `meta_title`, `meta_description`, `meta_keywords`, `category_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'root', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL),
(2, 'Electronic', NULL, 'Electronic  Meta Title', 'Meta Title Meta Description', 'Meta Title Meta Keywords', 2, 1, '2021-01-04 06:06:14', '2021-01-04 06:06:14'),
(3, 'Electronic - 1', NULL, 'Electronic - 1Title', 'Electronic - 1Description', 'Electronic - 1Keywords', 2, 2, '2021-01-04 06:06:14', '2021-01-04 06:06:14'),
(4, 'Mobile', NULL, 'Mobile  Meta Title', 'Meta Title Meta Description', 'Meta Title Meta Keywords', 3, 1, '2021-01-04 06:10:10', '2021-01-04 06:10:10'),
(5, 'Mobile - 1', NULL, 'Mobile - 1Title', 'Mobile - 1Description', 'Mobile - 1Keywords', 3, 2, '2021-01-04 06:10:10', '2021-01-04 06:10:10'),
(6, 'samsung', NULL, 'samsung Title- Default', 'samsung Meta Description - Default', 'samsung Keywords', 4, 1, '2021-01-04 09:01:35', '2021-01-05 04:17:02'),
(8, 'samsung 2', NULL, 'samsung Title 2', 'samsung Description 2', 'samsung Keywords 2', 4, 2, '2021-01-04 09:01:35', '2021-01-05 04:17:02'),
(9, 'Apple', NULL, 'Apple Default Title', 'Apple Default Description', 'Apple Default Keywords', 6, 1, '2021-01-05 04:37:56', '2021-01-05 04:37:56'),
(10, 'Apple Hindi Name', NULL, 'Apple Hindi Title', 'Apple Hindi Meta Description', 'Apple Hindi  Keywords', 6, 2, '2021-01-05 04:37:56', '2021-01-05 04:37:56'),
(11, 'Food', NULL, NULL, NULL, NULL, 7, 1, '2021-01-06 05:16:48', '2021-01-06 05:16:48'),
(12, 'Khana', NULL, NULL, NULL, NULL, 7, 2, '2021-01-06 05:16:48', '2021-01-06 05:16:48'),
(13, 'Lava', NULL, 'Lava Default Title', 'Lava Default Description', 'Lava Default Keywords', 10, 1, '2021-01-05 04:37:56', '2021-01-05 04:37:56'),
(14, 'Pizza', NULL, 'Description', 'Description', 'Description', 11, 1, '2021-01-14 08:16:08', '2021-01-14 08:16:08'),
(15, 'Pizza', NULL, 'Description', 'Description', 'Description', 11, 2, '2021-01-14 08:16:08', '2021-01-14 08:16:08'),
(16, 'Pizza', NULL, 'Description', 'Description', 'Description', 11, 3, '2021-01-14 08:16:08', '2021-01-14 08:16:08'),
(17, 'Cloth', NULL, 'Cloth', 'Cloth', 'Cloth', 12, 1, '2021-01-19 05:47:38', '2021-01-19 05:47:38'),
(18, NULL, NULL, NULL, NULL, NULL, 12, 2, '2021-01-19 05:47:38', '2021-01-19 05:47:38'),
(19, NULL, NULL, NULL, NULL, NULL, 12, 3, '2021-01-19 05:47:38', '2021-01-19 05:47:38'),
(23, 'Toy', NULL, 'Toy', 'Toy', 'Toy', 14, 1, '2021-01-19 05:53:11', '2021-01-19 05:53:11'),
(24, NULL, NULL, NULL, NULL, NULL, 14, 2, '2021-01-19 05:53:11', '2021-01-19 05:53:11'),
(25, NULL, NULL, NULL, NULL, NULL, 14, 3, '2021-01-19 05:53:11', '2021-01-19 05:53:11'),
(26, 'robot', NULL, 'robot', 'robot', 'robot', 15, 1, '2021-01-19 05:53:50', '2021-01-19 05:53:50'),
(27, NULL, NULL, NULL, NULL, NULL, 15, 2, '2021-01-19 05:53:50', '2021-01-19 05:53:50'),
(28, NULL, NULL, NULL, NULL, NULL, 15, 3, '2021-01-19 05:53:50', '2021-01-19 05:53:50'),
(29, 'test', NULL, 'test', 'test', 'test', 16, 1, '2021-01-22 01:33:21', '2021-01-22 01:33:21'),
(30, NULL, NULL, NULL, NULL, NULL, 16, 2, '2021-01-22 01:33:21', '2021-01-22 01:33:21'),
(31, NULL, NULL, NULL, NULL, NULL, 16, 3, '2021-01-22 01:33:21', '2021-01-22 01:33:21'),
(32, NULL, NULL, NULL, NULL, NULL, 16, 4, '2021-01-22 01:33:21', '2021-01-22 01:33:21');



INSERT INTO `brands` (`id`, `title`, `image`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Samsung', 'brand/6006a8942481e.jpeg', 2, 1, '2021-01-19 01:27:29', '2021-01-19 04:27:12'),
(2, 'LG', 'brand/60068dff46d4e.jpeg', 1, 1, '2021-01-19 02:15:03', '2021-01-19 04:27:12');

INSERT INTO `brand_categories` (`brand_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, '2021-01-19 04:08:28'),
(2, 2, NULL, '2021-01-21 04:31:48');

INSERT INTO `brand_translations` (`id`, `title`, `brand_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Samsung', 1, 1, NULL, NULL),
(2, 'Samsung H', 1, 2, NULL, NULL),
(3, 'Samsung I', 1, 3, NULL, '2021-01-19 04:05:39'),
(4, 'LG', 2, 1, NULL, NULL),
(5, 'LG', 2, 2, NULL, NULL),
(6, 'LG', 2, 3, NULL, NULL);

INSERT INTO `banners` (`id`, `name`, `description`, `image`, `validity_on`, `sorting`, `status`, `start_date_time`, `end_date_time`, `redirect_category_id`, `redirect_vendor_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Karan', NULL, 'banner/5fec8b4368202.jpg', 0, 5, 1, '2020-12-24 12:00:00', '2021-01-02 12:00:00', NULL, NULL, NULL, '2020-12-30 03:14:27', '2021-01-15 08:41:27'),
(4, 'testd', NULL, 'banner/5fed843581961.jpg', 1, 3, 1, '2020-12-22 12:00:00', '2021-01-09 12:00:00', NULL, NULL, NULL, '2020-12-30 20:56:37', '2021-02-16 02:31:20'),
(6, 'new pizza', NULL, 'banner/5feef3f4243e1.png', 1, 4, 1, '2021-01-25 12:00:00', '2021-02-06 12:00:00', NULL, NULL, NULL, '2020-12-31 23:05:40', '2021-02-16 02:31:20'),
(7, 'Hello Sing', NULL, 'banner/5feef9665fac5.jpg', 1, 2, 1, '2021-01-05 12:00:00', '2021-02-05 12:00:00', NULL, NULL, NULL, '2020-12-31 23:28:54', '2021-02-16 02:31:20'),
(8, 'Drinks', NULL, 'banner/5fef09b1c7620.png', 1, 1, 1, '2021-01-01 12:00:00', '2022-01-07 12:00:00', NULL, NULL, NULL, '2021-01-01 00:38:25', '2021-01-22 07:54:10');



INSERT INTO `tax_categories` (`id`, `title`, `code`, `description`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'first', 'aq1q1q', 'test,ncs l.mvkdm', 1, NULL, '2021-01-19 08:25:53', '2021-01-19 08:25:53'),
(2, 'Second1', 'adasdA1', 'adasd1', 1, NULL, '2021-01-19 08:36:36', '2021-01-19 08:44:18'),
(3, 'GST', 'GSTTSG', 'fdfsdgsd', 1, NULL, '2021-01-19 23:54:18', '2021-01-19 23:54:18');


INSERT INTO `tax_rates` (`id`, `identifier`, `is_zip`, `zip_code`, `zip_from`, `zip_to`, `state`, `country`, `tax_rate`, `tax_amount`, `created_at`, `updated_at`) VALUES
(1, 'GST', 1, '127021', NULL, NULL, 'Haryana', 'India', '20.00', NULL, NULL, NULL),
(2, 'test2', 2, NULL, '111122', '111233', 'afsssfd', 'Shri lanka', '40.00', NULL, '2021-01-20 03:39:09', '2021-01-20 03:39:09'),
(3, 'test21', 2, NULL, '111122', '111233', 'afsssfd', 'Shri lanka', '40.00', NULL, '2021-01-20 03:39:34', '2021-01-20 03:39:34'),
(4, 'asdasd', 0, '', '', '', 'sadasd', 'asdas', '30.00', NULL, '2021-01-20 03:58:45', '2021-01-20 08:08:38');

INSERT INTO `tax_rate_categories` (`id`, `tax_cate_id`, `tax_rate_id`, `created_at`, `updated_at`) VALUES
(1, 3, 2, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 2, 3, NULL, NULL),
(5, 1, 1, NULL, NULL),
(9, 1, 4, '2021-01-20 08:08:31', '2021-01-20 08:08:31');


INSERT INTO `addon_sets` (`id`, `title`, `min_select`, `max_select`, `position`, `status`, `is_core`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'sauces', 1, 3, 1, 1, 1, 1, '2021-01-21 01:17:27', '2021-01-21 05:47:43');

INSERT INTO `addon_set_translations` (`id`, `title`, `addon_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'sauces', 1, 1, NULL, '2021-01-21 05:47:43'),
(2, 'sauce -hh', 1, 2, NULL, '2021-01-21 05:47:43'),
(3, 'sauce -ii', 1, 3, NULL, '2021-01-21 05:47:43');


INSERT INTO `addon_options` (`id`, `title`, `addon_id`, `position`, `price`, `created_at`, `updated_at`) VALUES
(1, 'chilli', 1, 1, '10.00', '2021-01-21 01:17:27', '2021-01-21 01:17:27'),
(2, 'tomato', 1, 2, '15.00', '2021-01-21 01:17:27', '2021-01-21 01:17:27'),
(3, 'sweet', 1, 3, '20.00', '2021-01-21 01:17:27', '2021-01-21 01:17:27'),
(5, 'mixed', 1, 5, '20.00', '2021-01-21 01:17:27', '2021-01-21 01:17:27'),
(6, 'new', 1, 1, '12.00', '2021-01-21 05:41:39', '2021-01-21 05:41:39');

INSERT INTO `addon_option_translations` (`id`, `title`, `addon_opt_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'chilli', 1, 1, NULL, '2021-01-21 05:47:24'),
(2, 'chilli1', 1, 2, NULL, '2021-01-21 05:47:24'),
(3, 'chilli2', 1, 3, NULL, '2021-01-21 05:47:24'),
(4, 'tomato', 2, 1, NULL, '2021-01-21 05:47:24'),
(5, 'tomato1', 2, 2, NULL, '2021-01-21 05:47:24'),
(6, 'tomato2', 2, 3, NULL, '2021-01-21 05:47:24'),
(7, 'sweet', 3, 1, NULL, '2021-01-21 05:47:24'),
(8, 'sweet1', 3, 2, NULL, '2021-01-21 05:47:24'),
(9, 'sweet2', 3, 3, NULL, '2021-01-21 05:47:24'),
(13, 'onion', 5, 1, NULL, '2021-01-21 05:47:24'),
(14, 'onion1', 5, 2, NULL, '2021-01-21 05:47:24'),
(15, 'onion2', 5, 3, NULL, '2021-01-21 05:47:24'),
(16, 'new', 6, 1, '2021-01-21 05:41:39', '2021-01-21 05:47:24'),
(17, 'new1', 6, 2, '2021-01-21 05:41:39', '2021-01-21 05:47:24'),
(18, 'new2', 6, 3, '2021-01-21 05:41:39', '2021-01-21 05:47:24');

INSERT INTO `variants` (`id`, `title`, `type`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ram-En', 1, 2, 1, '2021-01-18 07:02:11', '2021-01-19 04:27:03'),
(5, 'mobi', 2, 1, 1, '2021-01-19 01:00:03', '2021-01-19 04:27:03'),
(8, 'Screen Size', 1, 3, 1, '2021-01-19 01:12:55', '2021-01-19 04:09:36'),
(9, 'Colour', 2, 4, 1, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(10, 'Size', 1, 5, 1, '2021-01-28 01:44:45', '2021-01-28 01:44:45');


INSERT INTO `variant_categories` (`variant_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2021-01-18 07:02:11', '2021-01-18 08:05:21'),
(5, 10, NULL, NULL),
(8, 3, NULL, NULL),
(9, 12, NULL, NULL),
(10, 12, NULL, NULL);


INSERT INTO `variant_options` (`id`, `title`, `variant_id`, `hexacode`, `position`, `created_at`, `updated_at`) VALUES
(1, '2GB', 1, '', 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(2, '4GB', 1, '', 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(3, '8GB', 1, '', 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(4, '16GB', 1, '', 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(16, 'mo28', 5, '#2812e3', 1, '2021-01-19 01:00:03', '2021-01-19 01:00:03'),
(17, 'mo31', 5, '#31971c', 1, '2021-01-19 01:00:03', '2021-01-19 01:00:03'),
(21, '6', 8, '', 1, '2021-01-19 01:12:55', '2021-01-19 01:12:55'),
(22, '7', 8, '', 1, '2021-01-19 01:12:55', '2021-01-19 01:12:55'),
(23, '8', 8, '', 1, '2021-01-19 01:12:55', '2021-01-19 01:12:55'),
(24, 'Red', 9, '#d51a1a', 1, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(25, 'Green', 9, '#38c124', 1, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(26, 'Blue', 9, '#2409e6', 1, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(27, 'Mahroon', 9, '#802525', 1, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(28, 'S', 10, '', 1, '2021-01-28 01:44:45', '2021-01-28 01:44:45'),
(29, 'M', 10, '', 1, '2021-01-28 01:44:45', '2021-01-28 01:44:45'),
(30, 'L', 10, '', 1, '2021-01-28 01:44:45', '2021-01-28 01:44:45'),
(31, 'XL', 10, '', 1, '2021-01-28 01:44:45', '2021-01-28 01:44:45'),
(32, 'XXL', 10, '', 1, '2021-01-28 01:44:45', '2021-01-28 01:44:45');


INSERT INTO `variant_option_translations` (`id`, `title`, `variant_option_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, '2GB', 1, 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(2, '2GB', 1, 2, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(3, '2GB', 1, 3, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(4, '4GB', 2, 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(5, '4GB', 2, 2, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(6, '4GB', 2, 3, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(7, '8GB', 3, 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(8, '8GB', 3, 2, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(9, '8GB', 3, 3, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(10, '16GB', 4, 1, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(11, '16GB', 4, 2, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(12, '16GB', 4, 3, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(52, 'mo28', 16, 1, NULL, NULL),
(53, 'mo28', 16, 2, NULL, NULL),
(54, 'mo28', 16, 3, NULL, NULL),
(55, 'mo31', 17, 1, NULL, NULL),
(56, 'mo31', 17, 2, NULL, NULL),
(57, 'mo31', 17, 3, NULL, NULL),
(67, '6', 21, 1, NULL, NULL),
(68, '6', 21, 2, NULL, NULL),
(69, '6', 21, 3, NULL, NULL),
(70, '7', 22, 1, NULL, NULL),
(71, '7', 22, 2, NULL, NULL),
(72, '7', 22, 3, NULL, NULL),
(73, '8', 23, 1, NULL, NULL),
(74, '8', 23, 2, NULL, NULL),
(75, '8', 23, 3, NULL, NULL),
(76, 'Red', 24, 1, NULL, NULL),
(77, 'Red', 24, 2, NULL, NULL),
(78, 'Red', 24, 3, NULL, NULL),
(79, 'Red', 24, 4, NULL, NULL),
(80, 'Green', 25, 1, NULL, NULL),
(81, 'Green', 25, 2, NULL, NULL),
(82, 'Green', 25, 3, NULL, NULL),
(83, 'Green', 25, 4, NULL, NULL),
(84, 'Blue', 26, 1, NULL, NULL),
(85, 'Blue', 26, 2, NULL, NULL),
(86, 'Blue', 26, 3, NULL, NULL),
(87, 'Blue', 26, 4, NULL, NULL),
(88, 'Mahroon', 27, 1, NULL, NULL),
(89, 'Mahroon', 27, 2, NULL, NULL),
(90, 'Mahroon', 27, 3, NULL, NULL),
(91, 'Mahroon', 27, 4, NULL, NULL),
(92, 'S', 28, 1, NULL, NULL),
(93, 'S', 28, 2, NULL, NULL),
(94, 'S', 28, 3, NULL, NULL),
(95, 'S', 28, 4, NULL, NULL),
(96, 'M', 29, 1, NULL, NULL),
(97, NULL, 29, 2, NULL, NULL),
(98, NULL, 29, 3, NULL, NULL),
(99, NULL, 29, 4, NULL, NULL),
(100, 'L', 30, 1, NULL, NULL),
(101, NULL, 30, 2, NULL, NULL),
(102, NULL, 30, 3, NULL, NULL),
(103, NULL, 30, 4, NULL, NULL),
(104, 'XL', 31, 1, NULL, NULL),
(105, NULL, 31, 2, NULL, NULL),
(106, NULL, 31, 3, NULL, NULL),
(107, NULL, 31, 4, NULL, NULL),
(108, 'XXL', 32, 1, NULL, NULL),
(109, NULL, 32, 2, NULL, NULL),
(110, NULL, 32, 3, NULL, NULL),
(111, NULL, 32, 4, NULL, NULL);

INSERT INTO `variant_translations` (`id`, `title`, `variant_id`, `language_id`, `created_at`, `updated_at`) VALUES
(1, 'Ram-En', 1, 1, '2021-01-18 07:02:11', '2021-01-18 08:05:21'),
(2, 'Ram H', 1, 2, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(3, 'Ram I', 1, 3, '2021-01-18 07:02:11', '2021-01-18 07:02:11'),
(13, 'mobi', 5, 1, '2021-01-19 01:00:03', '2021-01-19 01:00:03'),
(14, 'mobi -H', 5, 2, '2021-01-19 01:00:03', '2021-01-19 01:00:03'),
(15, 'mobi -I', 5, 3, '2021-01-19 01:00:03', '2021-01-19 01:00:03'),
(22, 'Screen Size', 8, 1, '2021-01-19 01:12:55', '2021-01-19 01:12:55'),
(23, 'Screen Size H', 8, 2, '2021-01-19 01:12:55', '2021-01-19 01:12:55'),
(24, 'Screen Size H', 8, 3, '2021-01-19 01:12:55', '2021-01-19 01:12:55'),
(25, 'Colour', 9, 1, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(26, 'Colour', 9, 2, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(27, 'Colour', 9, 3, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(28, 'Colour', 9, 4, '2021-01-28 01:43:18', '2021-01-28 01:43:18'),
(29, 'Size', 10, 1, '2021-01-28 01:44:45', '2021-01-28 01:44:45'),
(30, 'Size', 10, 2, '2021-01-28 01:44:45', '2021-01-28 01:44:45'),
(31, 'Size', 10, 3, '2021-01-28 01:44:45', '2021-01-28 01:44:45'),
(32, 'Size', 10, 4, '2021-01-28 01:44:45', '2021-01-28 01:44:45');


INSERT INTO `products` (`id`, `sku`, `title`, `url_slug`, `description`, `body_html`, `vendor_id`, `category_id`, `type_id`, `country_origin_id`, `is_new`, `is_featured`, `is_live`, `is_physical`, `weight`, `weight_unit`, `has_inventory`, `has_variant`, `sell_when_out_of_stock`, `requires_shipping`, `Requires_last_mile`, `publish_at`, `created_at`, `updated_at`) VALUES
(3, 'tommy-shirt', NULL, 'tommy-shirt', NULL, NULL, 1, NULL, NULL, NULL, 1, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, '2021-02-01 06:21:52', '2021-02-01 06:21:52'),
(5, 'samsung-420', NULL, 'samsung-420', NULL, NULL, 1, NULL, NULL, NULL, 0, 1, 0, 0, NULL, NULL, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '2021-02-01 06:57:17', '2021-02-02 06:30:10'),
(6, 'iphone', NULL, 'iphone', NULL, NULL, 1, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, '2021-02-03 03:48:59', '2021-02-03 03:48:59'),
(7, 'adadasd', NULL, 'adadasd', NULL, NULL, NULL, NULL, 1, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, '2021-02-23 08:44:04', '2021-02-23 08:44:04');

INSERT INTO `product_categories` (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(3, 12, NULL, NULL),
(5, 3, NULL, NULL),
(6, 3, NULL, NULL),
(7, 2, NULL, NULL);

INSERT INTO `product_images` (`id`, `product_id`, `media_id`, `is_default`, `created_at`, `updated_at`) VALUES
(4, 5, 1, 1, '2021-02-03 00:11:39', '2021-02-03 00:11:39'),
(5, 5, 2, 1, '2021-02-03 00:11:39', '2021-02-03 00:11:39'),
(6, 5, 3, 1, '2021-02-03 00:17:20', '2021-02-03 00:17:20'),
(7, 5, 4, 1, '2021-02-03 00:17:20', '2021-02-03 00:17:20'),
(8, 5, 5, 1, '2021-02-03 00:45:01', '2021-02-03 00:45:01'),
(9, 5, 6, 1, '2021-02-03 00:50:44', '2021-02-03 00:50:44'),
(10, 5, 7, 1, '2021-02-03 00:51:43', '2021-02-03 00:51:43'),
(11, 5, 8, 1, '2021-02-03 01:09:25', '2021-02-03 01:09:25'),
(12, 5, 16, 1, NULL, NULL),
(13, 5, 17, 1, NULL, NULL),
(14, 5, 18, 1, NULL, NULL),
(15, 6, 19, 1, '2021-02-03 03:49:23', '2021-02-03 03:49:23');


INSERT INTO `product_translations` (`id`, `title`, `body_html`, `meta_title`, `meta_keyword`, `meta_description`, `product_id`, `language_id`, `created_at`, `updated_at`) VALUES
(2, 'erwerwer', 'fasfdad', '', 'asdasdasd', '', 3, 1, NULL, NULL),
(3, 'asdas', 'asdsa', 'asdasd', NULL, NULL, 5, 1, NULL, '2021-02-02 06:30:10'),
(4, '', '', '', '', '', 6, 1, NULL, NULL),
(5, '', '', '', '', '', 7, 1, NULL, NULL);


INSERT INTO `product_variants` (`id`, `sku`, `product_id`, `title`, `quantity`, `price`, `position`, `compare_at_price`, `barcode`, `cost_price`, `currency_id`, `tax_category_id`, `inventory_policy`, `fulfillment_service`, `inventory_management`, `created_at`, `updated_at`) VALUES
(14, 'samsung-420-1*21', 5, NULL, 600, '540.00', 1, '450.00', 'b1b2536551498a', '400.00', NULL, 1, NULL, NULL, NULL, '2021-02-02 06:28:34', '2021-02-03 01:22:13'),
(15, 'samsung-420-1*22', 5, NULL, 610, '550.00', 1, '460.00', 'caa3e5448d4368', '410.00', NULL, 1, NULL, NULL, NULL, '2021-02-02 06:28:34', '2021-02-03 01:22:13'),
(16, 'samsung-420-2*21', 5, NULL, 620, '560.00', 1, '470.00', '89f8b88be705f1', '420.00', NULL, 1, NULL, NULL, NULL, '2021-02-02 06:28:34', '2021-02-03 01:22:13'),
(17, 'samsung-420-2*22', 5, NULL, 630, '570.00', 1, '480.00', '9c33eac9c58e52', '430.00', NULL, 1, NULL, NULL, NULL, '2021-02-02 06:28:34', '2021-02-03 01:22:13'),
(20, 'samsung-420-3*21', 5, NULL, 330, '230.00', 1, '220.00', 'f60c60c2630231', '120.00', NULL, 1, NULL, NULL, NULL, '2021-02-02 06:42:15', '2021-02-03 01:22:13'),
(21, 'samsung-420-3*22', 5, NULL, 330, '240.00', 1, '220.00', '6620ad709e1541', '130.00', NULL, 1, NULL, NULL, NULL, '2021-02-02 06:42:15', '2021-02-03 01:22:13'),
(23, 'iphone-1*22', 6, 'iphone-2GB-7', 0, '500.00', 1, '500.00', 'dcb9236c083dc7', '300.00', NULL, NULL, NULL, NULL, NULL, '2021-02-03 03:49:09', '2021-02-03 03:49:09'),
(24, 'iphone-1*23', 6, 'iphone-2GB-8', 0, '500.00', 1, '500.00', 'dfbcaa5df0de1c', '300.00', NULL, NULL, NULL, NULL, NULL, '2021-02-03 03:49:09', '2021-02-03 03:49:09'),
(25, 'iphone-2*22', 6, 'iphone-4GB-7', 0, '500.00', 1, '500.00', 'aa4174b0578e41', '300.00', NULL, NULL, NULL, NULL, NULL, '2021-02-03 03:49:09', '2021-02-03 03:49:09'),
(26, 'iphone-2*23', 6, 'iphone-4GB-8', 0, '500.00', 1, '500.00', '8804a28adff4a4', '300.00', NULL, NULL, NULL, NULL, NULL, '2021-02-03 03:49:09', '2021-02-03 03:49:09'),
(27, 'tommy-shirt-26*30', 3, 'tommy-shirt-Blue-L', 0, '200.00', 1, '200.00', '1c2ce53f42fce3', '100.00', NULL, NULL, NULL, NULL, NULL, '2021-02-16 02:31:56', '2021-02-16 02:31:56'),
(28, 'tommy-shirt-27*30', 3, 'tommy-shirt-Mahroon-L', 0, '200.00', 1, '200.00', 'a88f5cb96e1c40', '100.00', NULL, NULL, NULL, NULL, NULL, '2021-02-16 02:31:56', '2021-02-16 02:31:56'),
(29, 'adadasd', 7, NULL, 0, NULL, 1, NULL, '8c529b438f1561', NULL, NULL, NULL, NULL, NULL, NULL, '2021-02-23 08:44:04', '2021-02-23 08:44:04');

INSERT INTO `product_variant_images` (`product_variant_id`, `product_image_id`, `created_at`, `updated_at`) VALUES
(17, 6, NULL, NULL),
(17, 7, NULL, NULL),
(16, 4, NULL, NULL),
(16, 9, NULL, NULL),
(16, 10, NULL, NULL),
(21, 11, NULL, NULL),
(20, 8, NULL, NULL),
(20, 12, NULL, NULL),
(20, 14, NULL, NULL);

INSERT INTO `product_variant_sets` (`id`, `product_id`, `product_variant_id`, `variant_type_id`, `variant_option_id`, `created_at`, `updated_at`) VALUES
(9, 5, 14, 1, 1, NULL, NULL),
(10, 5, 14, 8, 21, NULL, NULL),
(11, 5, 15, 1, 1, NULL, NULL),
(12, 5, 15, 8, 22, NULL, NULL),
(13, 5, 16, 1, 2, NULL, NULL),
(14, 5, 16, 8, 21, NULL, NULL),
(15, 5, 17, 1, 2, NULL, NULL),
(16, 5, 17, 8, 22, NULL, NULL),
(21, 5, 20, 1, 3, NULL, NULL),
(22, 5, 20, 8, 21, NULL, NULL),
(23, 5, 21, 1, 3, NULL, NULL),
(24, 5, 21, 8, 22, NULL, NULL),
(25, 6, 23, 1, 1, NULL, NULL),
(26, 6, 23, 8, 22, NULL, NULL),
(27, 6, 24, 1, 1, NULL, NULL),
(28, 6, 24, 8, 23, NULL, NULL),
(29, 6, 25, 1, 2, NULL, NULL),
(30, 6, 25, 8, 22, NULL, NULL),
(31, 6, 26, 1, 2, NULL, NULL),
(32, 6, 26, 8, 23, NULL, NULL),
(33, 3, 27, 9, 26, NULL, NULL),
(34, 3, 27, 10, 30, NULL, NULL),
(35, 3, 28, 9, 27, NULL, NULL),
(36, 3, 28, 10, 30, NULL, NULL);


INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `password`, `is_verified_phone`, `type`, `status`, `device_type`, `device_token`, `country_id`, `role_id`, `auth_token`, `system_id`, `remember_token`, `created_at`, `updated_at`, `verified_email`, `verified_phone`) VALUES
(1, 'Bajirav', 'bajirao123@yopmail.com', '9211420421', '2021-02-09 18:30:00', '$2y$10$J2OcmK/4PouQXXEcGj3Aguj3Zc9T2S0QWeOFT8eQ5A4PQ76HZUv5u', 1, 1, 1, 'Android', 'h78whwyyvfv98vvhvhvhvhrhvhevhervhervhz', NULL, 2, 'eyJ0eXAiOiJqd3QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTM3Mjc0MzMsImV4cCI6MTYxNjE0NjYzMywiaXNzIjoicm95b29yZGVycy5jb20ifQ.tDg2P35p5iW3TqnAQAB_Tva-VmsP1_hCf_b5hevTAXc', NULL, NULL, NULL, '2021-02-19 04:07:13', 1, 1),
(2, 'Sanjeev', 'sanjeev321@yopmail.com', '9211420420', NULL, '$2y$10$Qwh3fZR/8UFHHZ/g8TRa0.7K834PEFVdrUA3TyjpXiV8tUrIBlhVe', 0, 1, 1, 'iOS', 'sadassa', 226, 2, NULL, NULL, NULL, '2021-02-19 06:12:34', '2021-02-19 06:12:34', 0, 0),
(3, 'Sanjeev', 'sanjeev121@yopmail.com', '9211420427', NULL, '$2y$10$RCzBz9USQy.54dKUoDVzVeRu3jqQXb471aps6LJUxoaXLbvT/p3Z.', 0, 1, 1, 'Android', 'sadassa', 226, 2, 'eyJ0eXAiOiJqd3QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTM5NzQ2NjksImV4cCI6MTYxNjM5Mzg2OSwiaXNzIjoicm95b29yZGVycy5jb20ifQ.SClvbE0Rx02HRj8nAcyc2-Pxi4zA53_V-guYcxs-pyA', NULL, NULL, '2021-02-22 00:47:49', '2021-02-22 00:47:49', 0, 0);


INSERT INTO `user_devices` (`id`, `user_id`, `device_type`, `device_token`, `access_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'iOS', 'sadassa', NULL, '2021-02-19 03:35:44', '2021-02-19 03:35:44'),
(2, 2, 'iOS', 'sadassa', '', NULL, NULL),
(3, 3, 'Android', 'sadassa', '', NULL, NULL);

INSERT INTO `user_verifications` (`id`, `user_id`, `email_token`, `phone_token`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 1, NULL, NULL),
(2, 2, NULL, NULL, 0, NULL, NULL),
(3, 3, NULL, NULL, 0, NULL, NULL);


