-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipg67.eigbox.net
-- Generation Time: Dec 03, 2017 at 11:49 PM
-- Server version: 5.6.37
-- PHP Version: 4.4.9
-- 
-- Database: `zbadmin`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `accounts`
-- 

CREATE TABLE `accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `purchase_id` int(10) unsigned NOT NULL,
  `total_installments` int(11) NOT NULL,
  `installment_amount` double NOT NULL,
  `installment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accounts_client_id_foreign` (`client_id`),
  KEY `accounts_purchase_id_foreign` (`purchase_id`),
  KEY `accounts_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `accounts`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `clients`
-- 

CREATE TABLE `clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cnic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `guarantor_id` int(10) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `photo` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_phone_unique` (`phone`),
  UNIQUE KEY `clients_account_number_unique` (`account_number`),
  KEY `clients_guarantor_id_foreign` (`guarantor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=10 ;

-- 
-- Dumping data for table `clients`
-- 

INSERT INTO `clients` VALUES (8, '33333333333', '03226373195', 'Fakhar Ali', 'Gulzar Ahmed', NULL, NULL, 'Hozri', 'House # 347, Street #1, Township', 'House # 347, Street #1, Township', 9, NULL, '2017-09-13 15:18:28', '2017-09-13 15:18:28', '1', 0, NULL);
INSERT INTO `clients` VALUES (9, '3312245454584', '03017029162', 'Sarfraz', 'Gulzar Ahmed', NULL, NULL, 'Hozri', '49-c I Block Allama Iqbal colony fsd', '49-c I Block Allama Iqbal colony fsd', NULL, NULL, '2017-09-13 15:18:28', '2017-09-13 15:18:28', '1505315908', 1, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `committee_installments`
-- 

CREATE TABLE `committee_installments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `received_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `committee_id` int(10) unsigned NOT NULL,
  `committee_member_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `committee_installments_committee_id_foreign` (`committee_id`),
  KEY `committee_installments_committee_member_id_foreign` (`committee_member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `committee_installments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `committee_members`
-- 

CREATE TABLE `committee_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `committee_id` int(11) NOT NULL,
  `cnic` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci,
  `achieved_product` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `achieved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `committee_members`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `committee_products`
-- 

CREATE TABLE `committee_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `committee_id` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_special` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `committee_products_product_name_unique` (`product_name`),
  KEY `committee_products_committee_id_foreign` (`committee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `committee_products`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `committees`
-- 

CREATE TABLE `committees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_at` date NOT NULL,
  `end_at` date NOT NULL,
  `total_members` int(11) NOT NULL DEFAULT '0',
  `installment_amount` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `committees`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `companies`
-- 

CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `companies`
-- 

INSERT INTO `companies` VALUES (3, 'Honda', NULL, NULL, NULL, 'This is Honda Company', NULL, '2017-09-13 15:21:49', '2017-09-13 15:21:49');
INSERT INTO `companies` VALUES (4, 'Panasonic', NULL, NULL, NULL, 'This is Panasonic', NULL, '2017-09-13 15:22:08', '2017-09-13 15:22:08');
INSERT INTO `companies` VALUES (5, 'Ghani', NULL, NULL, NULL, 'This is Ghani', NULL, '2017-09-13 15:22:26', '2017-09-13 15:22:26');
INSERT INTO `companies` VALUES (6, 'Road Prince', NULL, NULL, NULL, 'This is Road Prince', NULL, '2017-09-13 15:22:50', '2017-09-13 15:22:50');
INSERT INTO `companies` VALUES (7, 'New Asia', NULL, NULL, NULL, 'This is New Asia', NULL, '2017-09-13 15:23:14', '2017-09-13 15:23:14');
INSERT INTO `companies` VALUES (8, 'United', NULL, NULL, NULL, 'United', NULL, '2017-09-13 15:23:34', '2017-09-13 15:23:34');

-- --------------------------------------------------------

-- 
-- Table structure for table `installments`
-- 

CREATE TABLE `installments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transaction_id` int(10) unsigned NOT NULL,
  `amount` double NOT NULL COMMENT 'installemnt being payed',
  `receiver` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'receiver of payment',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `receipt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `installments_transaction_id_foreign` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `installments`
-- 

INSERT INTO `installments` VALUES (1, '2017-09-20 12:10:53', '2017-09-20 12:11:44', 25, 5000, 'test', NULL, '1234567890', '2017-09-20 00:00:00');

-- --------------------------------------------------------

-- 
-- Table structure for table `migrations`
-- 

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `migrations`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `parties`
-- 

CREATE TABLE `parties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parties_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `parties`
-- 

INSERT INTO `parties` VALUES (3, 'Qadriya Corporation', 'Haji Ramzan Main D-Type Road Muhammadi Chownk FSD', '03016086978', 'Haji@gmail.com', NULL, 1, '2017-09-13 15:19:48', '2017-09-13 15:19:48');
INSERT INTO `parties` VALUES (4, 'Rana Traders', 'Rana Naveed Mansorabad', '03008616609', 'naveed@gmail.com', '03018616609', 1, '2017-09-13 15:20:54', '2017-09-13 15:20:54');

-- --------------------------------------------------------

-- 
-- Table structure for table `password_resets`
-- 

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
-- Dumping data for table `password_resets`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `product_models`
-- 

CREATE TABLE `product_models` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type_id` int(10) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_models_product_type_id_foreign` (`product_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `product_models`
-- 

INSERT INTO `product_models` VALUES (6, '70 CC', 5, NULL, '2017-09-13 15:25:39', '2017-09-13 15:25:39');
INSERT INTO `product_models` VALUES (7, '125 CC', 5, NULL, '2017-09-13 15:26:00', '2017-09-13 15:26:00');
INSERT INTO `product_models` VALUES (8, '125', 5, NULL, '2017-09-14 15:06:46', '2017-09-14 15:06:46');

-- --------------------------------------------------------

-- 
-- Table structure for table `product_types`
-- 

CREATE TABLE `product_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `product_types`
-- 

INSERT INTO `product_types` VALUES (5, 'Motor Cycle', 1, NULL, '2017-09-13 15:24:07', '2017-09-13 15:24:07');
INSERT INTO `product_types` VALUES (6, 'Car', 1, NULL, '2017-09-13 15:24:27', '2017-09-13 15:24:27');
INSERT INTO `product_types` VALUES (7, 'Washing Machine', 1, NULL, '2017-09-13 15:24:46', '2017-09-13 15:24:46');

-- --------------------------------------------------------

-- 
-- Table structure for table `products`
-- 

CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_type_id` int(10) unsigned NOT NULL,
  `product_model_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `unit_price` double DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_alias_unique` (`alias`),
  KEY `products_product_type_id_foreign` (`product_type_id`),
  KEY `products_product_model_id_foreign` (`product_model_id`),
  KEY `products_company_id_foreign` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `products`
-- 

INSERT INTO `products` VALUES (6, 'Honda Motor Cycle 70 CC', 5, 6, 3, 63000, 1, NULL, '2017-09-13 15:36:25', '2017-09-13 15:36:25');
INSERT INTO `products` VALUES (7, 'Ghani Motor Cycle 125 CC', 5, 7, 5, 43500, 1, NULL, '2017-09-13 15:37:19', '2017-09-13 15:37:19');
INSERT INTO `products` VALUES (8, 'Road Prince Motor Cycle 70 CC', 5, 6, 6, 43500, 1, NULL, '2017-09-13 15:38:06', '2017-09-13 15:38:06');
INSERT INTO `products` VALUES (9, 'Honda Motor Cycle 125 CC', 5, 7, 3, 105500, 1, NULL, '2017-09-14 15:04:41', '2017-09-14 15:04:41');
INSERT INTO `products` VALUES (10, 'Honda Motor Cycle 125', 5, 8, 3, 105500, 1, NULL, '2017-09-14 15:07:21', '2017-09-14 15:07:21');

-- --------------------------------------------------------

-- 
-- Table structure for table `purchase_product`
-- 

CREATE TABLE `purchase_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_product_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_product_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `purchase_product`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `purchases`
-- 

CREATE TABLE `purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_invoice_unique` (`invoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `purchases`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sale_products`
-- 

CREATE TABLE `sale_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_products_purchase_id_foreign` (`purchase_id`),
  KEY `sale_products_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sale_products`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sales`
-- 

CREATE TABLE `sales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_type` int(11) NOT NULL DEFAULT '0',
  `client_id` int(10) unsigned DEFAULT NULL,
  `party_id` int(10) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_invoice_unique` (`invoice`),
  KEY `sales_client_id_foreign` (`client_id`),
  KEY `sales_party_id_foreign` (`party_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sales`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `stocks`
-- 

CREATE TABLE `stocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `available_quantity` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stocks_product_id_unique` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `stocks`
-- 

INSERT INTO `stocks` VALUES (4, 6, 0, NULL, '2017-09-14 15:08:26', '2017-09-14 15:10:37');

-- --------------------------------------------------------

-- 
-- Table structure for table `transaction_products`
-- 

CREATE TABLE `transaction_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` double NOT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `casing_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advance` double NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_products_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_products_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=31 ;

-- 
-- Dumping data for table `transaction_products`
-- 

INSERT INTO `transaction_products` VALUES (29, 24, 6, 1, 105500, 'red', '1234612', '21346455', '123456', 105500, NULL, '2017-09-14 15:08:26', '2017-09-14 15:08:26');
INSERT INTO `transaction_products` VALUES (30, 25, 6, 1, 145000, 'red', NULL, NULL, NULL, 35000, NULL, '2017-09-14 15:10:37', '2017-09-14 15:10:37');

-- --------------------------------------------------------

-- 
-- Table structure for table `transactions`
-- 

CREATE TABLE `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL COMMENT '0 => purchase, 1 => sale on cash, 2 => issued on installments',
  `client_id` int(10) unsigned DEFAULT NULL,
  `party_id` int(10) unsigned DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `total_installments` double DEFAULT NULL,
  `installment_amount` double DEFAULT NULL,
  `installment_day` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 => in-progress, 1 => fulfilled, 2 => defaulter',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_invoice_unique` (`invoice`),
  KEY `transactions_client_id_foreign` (`client_id`),
  KEY `transactions_party_id_foreign` (`party_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=26 ;

-- 
-- Dumping data for table `transactions`
-- 

INSERT INTO `transactions` VALUES (24, '201', 0, NULL, 3, 0, NULL, NULL, NULL, 1, NULL, '2017-09-14 15:08:26', '2017-09-14 15:08:26');
INSERT INTO `transactions` VALUES (25, '744', 1, 8, NULL, 110000, 22, 5000, 22, 0, NULL, '2017-09-14 15:10:37', '2017-09-14 15:10:37');

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'fsdfas', 'info@zbtraders.com', '$2y$10$yyld9pHyzEJpX/8l/7hHdO6hcLNH7y0h8H4Yt32VfIiz55jGns6QC', '6kOtS258IUqZlRSTaXEL2WeTgXI8dl1enKlsqhH10Lahg3DP9WVmQGlU1yfJ', '2017-07-02 00:17:05', '2017-07-02 00:17:05');

-- 
-- Constraints for dumped tables
-- 

-- 
-- Constraints for table `accounts`
-- 
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `accounts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `accounts_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`);

-- 
-- Constraints for table `clients`
-- 
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_guarantor_id_foreign` FOREIGN KEY (`guarantor_id`) REFERENCES `clients` (`id`);

-- 
-- Constraints for table `committee_installments`
-- 
ALTER TABLE `committee_installments`
  ADD CONSTRAINT `committee_installments_committee_id_foreign` FOREIGN KEY (`committee_id`) REFERENCES `committees` (`id`),
  ADD CONSTRAINT `committee_installments_committee_member_id_foreign` FOREIGN KEY (`committee_member_id`) REFERENCES `committee_members` (`id`);

-- 
-- Constraints for table `committee_products`
-- 
ALTER TABLE `committee_products`
  ADD CONSTRAINT `committee_products_committee_id_foreign` FOREIGN KEY (`committee_id`) REFERENCES `committees` (`id`);

-- 
-- Constraints for table `installments`
-- 
ALTER TABLE `installments`
  ADD CONSTRAINT `installments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

-- 
-- Constraints for table `product_models`
-- 
ALTER TABLE `product_models`
  ADD CONSTRAINT `product_models_product_type_id_foreign` FOREIGN KEY (`product_type_id`) REFERENCES `product_types` (`id`);

-- 
-- Constraints for table `products`
-- 
ALTER TABLE `products`
  ADD CONSTRAINT `products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `products_product_model_id_foreign` FOREIGN KEY (`product_model_id`) REFERENCES `product_models` (`id`),
  ADD CONSTRAINT `products_product_type_id_foreign` FOREIGN KEY (`product_type_id`) REFERENCES `product_types` (`id`);

-- 
-- Constraints for table `purchase_product`
-- 
ALTER TABLE `purchase_product`
  ADD CONSTRAINT `purchase_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_product_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`);

-- 
-- Constraints for table `sale_products`
-- 
ALTER TABLE `sale_products`
  ADD CONSTRAINT `sale_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`);

-- 
-- Constraints for table `sales`
-- 
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `sales_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`);

-- 
-- Constraints for table `stocks`
-- 
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

-- 
-- Constraints for table `transaction_products`
-- 
ALTER TABLE `transaction_products`
  ADD CONSTRAINT `transaction_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `transaction_products_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

-- 
-- Constraints for table `transactions`
-- 
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `transactions_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`);
