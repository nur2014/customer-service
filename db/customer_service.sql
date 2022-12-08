-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2022 at 06:44 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `customer_service`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `address`, `created_by`, `updated_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Alfred Futterkiste', 'Germany', 1, 1, 0, '2022-12-07 23:32:53', '2022-12-07 23:32:53'),
(2, 'Island Trading', 'UK', 1, 1, 0, '2022-12-07 23:33:27', '2022-12-07 23:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `cus_installment_details`
--

CREATE TABLE `cus_installment_details` (
  `id` int(11) NOT NULL,
  `customer_main_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `expire_date` varchar(50) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cus_installment_details`
--

INSERT INTO `cus_installment_details` (`id`, `customer_main_id`, `amount`, `expire_date`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 500, '2022-12-29', 'NA', '2022-12-07 23:36:38', '2022-12-07 23:36:38'),
(2, 1, 1000, '2022-01-14', 'NA', '2022-12-07 23:36:38', '2022-12-07 23:36:38'),
(3, 1, 6000, '2022-02-09', 'NA', '2022-12-07 23:36:38', '2022-12-07 23:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `cus_installment_mains`
--

CREATE TABLE `cus_installment_mains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cus_installment_mains`
--

INSERT INTO `cus_installment_mains` (`id`, `customer_id`, `created_by`, `updated_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, '2022-12-07 23:36:38', '2022-12-07 23:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `master_components`
--

CREATE TABLE `master_components` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `component_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `component_name_bn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description_bn` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sorting_order` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `master_components`
--

INSERT INTO `master_components` (`id`, `component_name`, `component_name_bn`, `description`, `description_bn`, `sorting_order`, `created_by`, `updated_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Customer Configuration', 'Customer Configuration', 'Customer Configuration', 'Customer Configuration', 1, 1, 1, 0, '2021-02-13 19:16:23', '2021-09-06 07:28:30');

-- --------------------------------------------------------

--
-- Table structure for table `master_menus`
--

CREATE TABLE `master_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menu_name_bn` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sorting_order` int(11) NOT NULL,
  `component_id` bigint(20) UNSIGNED DEFAULT NULL,
  `module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `associated_urls` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `master_menus`
--

INSERT INTO `master_menus` (`id`, `menu_name`, `menu_name_bn`, `url`, `sorting_order`, `component_id`, `module_id`, `service_id`, `associated_urls`, `created_by`, `updated_by`, `status`, `created_at`, `updated_at`) VALUES
(892, 'Customer', 'Customer', '/org-profile/customer-list', 31, 1, 3, NULL, '', 1, 1, 0, '2022-12-04 03:40:31', '2022-12-04 03:40:31'),
(893, 'Customer Installment', 'Customer Installment', '/org-profile/cus-installment-list', 31, 1, 3, NULL, '', 1, 1, 0, '2022-12-05 00:04:41', '2022-12-05 00:04:41');

-- --------------------------------------------------------

--
-- Table structure for table `master_modules`
--

CREATE TABLE `master_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `module_name_bn` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `component_id` bigint(20) UNSIGNED NOT NULL,
  `sorting_order` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `master_modules`
--

INSERT INTO `master_modules` (`id`, `module_name`, `module_name_bn`, `component_id`, `sorting_order`, `created_by`, `updated_by`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Customer Management ', 'Customer Management ', 1, 1, 1, 1, 0, '2021-02-13 21:00:08', '2021-03-24 18:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `master_services`
--

CREATE TABLE `master_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `service_name_bn` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `component_id` bigint(20) UNSIGNED DEFAULT NULL,
  `module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sorting_order` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menu_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `execution_type` int(11) NOT NULL COMMENT '0=insert, 1=update,2=delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `username`, `menu_name`, `table_name`, `data_id`, `ip`, `execution_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'http://localhost:8080/org-profile/cus-installment-list', 'master_wards', 11, '', 2, '2022-12-07 05:42:31', '2022-12-07 05:42:31'),
(2, 1, 'admin', 'http://localhost:8080/org-profile/cus-installment-list', 'master_wards', 10, '', 2, '2022-12-07 05:43:07', '2022-12-07 05:43:07'),
(3, 1, 'admin', 'http://localhost:8080/org-profile/cus-installment-list', 'master_wards', 3, '', 2, '2022-12-07 05:43:45', '2022-12-07 05:43:45'),
(4, 1, 'admin', 'http://localhost:8080/org-profile/cus-installment-list', 'master_wards', 4, '', 2, '2022-12-07 05:44:13', '2022-12-07 05:44:13'),
(5, 1, 'admin', 'http://localhost:8080/org-profile/cus-installment-list', 'master_wards', 5, '', 2, '2022-12-07 05:44:17', '2022-12-07 05:44:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cus_installment_details`
--
ALTER TABLE `cus_installment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cus_installment_mains`
--
ALTER TABLE `cus_installment_mains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_components`
--
ALTER TABLE `master_components`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_components_component_name_unique` (`component_name`);

--
-- Indexes for table `master_menus`
--
ALTER TABLE `master_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_menus_module_id_foreign` (`module_id`),
  ADD KEY `master_menus_component_id_foreign` (`component_id`),
  ADD KEY `master_menus_service_id_foreign` (`service_id`);

--
-- Indexes for table `master_modules`
--
ALTER TABLE `master_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_modules_component_id_foreign` (`component_id`);

--
-- Indexes for table `master_services`
--
ALTER TABLE `master_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_services_module_id_foreign` (`module_id`),
  ADD KEY `master_services_component_id_foreign` (`component_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username_index` (`username`),
  ADD KEY `uid_index` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cus_installment_details`
--
ALTER TABLE `cus_installment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cus_installment_mains`
--
ALTER TABLE `cus_installment_mains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master_components`
--
ALTER TABLE `master_components`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `master_menus`
--
ALTER TABLE `master_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=894;

--
-- AUTO_INCREMENT for table `master_modules`
--
ALTER TABLE `master_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `master_services`
--
ALTER TABLE `master_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
