-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2022 at 07:29 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jesm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customers`
--

CREATE TABLE `tbl_customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `customer_contact_number` varchar(15) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_customers`
--

INSERT INTO `tbl_customers` (`customer_id`, `customer_name`, `customer_address`, `customer_contact_number`, `remarks`, `date_added`, `date_last_modified`) VALUES
(22, 'Juan Company', 'Brgy. 16, Bacolod City', '09090943434', 'sample', '2022-02-11 15:10:55', '2022-06-16 09:59:06'),
(23, 'Bacolod Dsd Corp.', 'Brgy. 6, Bacolod City', '4234234', '', '2022-09-13 13:29:03', '2022-09-13 13:29:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_order`
--

CREATE TABLE `tbl_job_order` (
  `jo_id` int(11) NOT NULL,
  `reference_number` varchar(75) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(3) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jo_date` date NOT NULL,
  `status` varchar(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_job_order`
--

INSERT INTO `tbl_job_order` (`jo_id`, `reference_number`, `customer_id`, `service_id`, `remarks`, `user_id`, `jo_date`, `status`, `date_added`, `date_last_modified`) VALUES
(1, 'JO-20220628094021', 22, 3, '', 0, '2022-06-28', 'F', '2022-06-28 15:40:27', '2022-06-28 16:08:24'),
(4, 'JO-20220628103215', 22, 3, '', 0, '2022-06-28', 'F', '2022-06-28 16:32:23', '2022-06-28 16:32:51'),
(5, 'JO-20220831090632', 22, 3, '', 0, '2022-08-31', 'F', '2022-08-31 15:06:39', '2022-08-31 15:06:55'),
(7, 'JO-20220831102813', 22, 4, '', 0, '2022-08-31', 'F', '2022-08-31 16:28:22', '2022-08-31 16:29:22'),
(8, 'JO-20220913071326', 22, 3, '', 0, '2022-09-13', 'F', '2022-09-13 13:13:33', '2022-09-13 13:27:16'),
(9, 'JO-20220913071632', 22, 6, '', 0, '2022-09-13', 'F', '2022-09-13 13:16:39', '2022-09-13 13:16:52'),
(10, 'JO-20220913080300', 22, 5, '', 0, '2022-09-13', 'F', '2022-09-13 14:03:24', '2022-09-13 14:04:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_order_details`
--

CREATE TABLE `tbl_job_order_details` (
  `jo_detail_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cost` decimal(11,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_job_order_details`
--

INSERT INTO `tbl_job_order_details` (`jo_detail_id`, `jo_id`, `product_id`, `qty`, `price`, `cost`, `amount`) VALUES
(2, 2, 11, 2, '0.00', '0.00', '0.00'),
(6, 1, 11, 2, '12.00', '0.00', '24.00'),
(7, 1, 11, 3, '12.00', '0.00', '36.00'),
(8, 1, 12, 3, '10.00', '0.00', '30.00'),
(9, 4, 11, 12, '12.00', '0.00', '144.00'),
(10, 5, 11, 1, '12.00', '51.36', '12.00'),
(11, 5, 12, 2, '10.00', '31.37', '20.00'),
(12, 7, 12, 1, '10.00', '31.37', '10.00'),
(13, 7, 12, 3, '10.00', '31.37', '30.00'),
(14, 9, 11, 3, '320.00', '51.36', '960.00'),
(15, 9, 12, 2, '550.00', '31.37', '1100.00'),
(16, 8, 12, 2, '550.00', '153.70', '1100.00'),
(17, 10, 12, 5, '550.00', '153.70', '2750.00'),
(18, 10, 14, 20, '120.00', '116.24', '2400.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(75) NOT NULL,
  `product_price` decimal(11,2) NOT NULL,
  `product_cost` decimal(11,2) NOT NULL,
  `product_img` text NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`product_id`, `product_name`, `product_price`, `product_cost`, `product_img`, `remarks`, `date_added`, `date_last_modified`) VALUES
(11, 'Motor Control', '320.00', '117.50', '', '', '2022-06-28 14:59:38', '2022-09-13 13:26:30'),
(12, 'Circuit Breakers', '550.00', '153.70', '', '', '2022-06-28 16:08:02', '2022-09-13 13:26:30'),
(13, 'Lugs', '230.00', '63.64', '', '', '2022-09-13 13:15:46', '2022-09-13 14:01:55'),
(14, 'Electrical Connectors', '120.00', '116.24', '', '', '2022-09-13 13:16:04', '2022-09-13 14:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order`
--

CREATE TABLE `tbl_purchase_order` (
  `po_id` int(11) NOT NULL,
  `reference_number` varchar(30) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `po_date` date NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` varchar(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_purchase_order`
--

INSERT INTO `tbl_purchase_order` (`po_id`, `reference_number`, `supplier_id`, `po_date`, `remarks`, `status`, `date_added`, `date_last_modified`) VALUES
(1, 'PO-20220628074924', 4, '2022-06-28', '', 'F', '2022-06-28 13:49:29', '2022-06-28 13:49:29'),
(2, 'PO-20220628084722', 4, '2022-06-28', '', 'F', '2022-06-28 14:47:28', '2022-06-28 14:47:28'),
(3, 'PO-20220818093809', 4, '2022-08-18', '', 'F', '2022-08-18 15:38:13', '2022-08-18 15:38:13'),
(4, 'PO-20220818094128', 4, '2022-08-18', '', 'F', '2022-08-18 15:41:31', '2022-08-18 15:41:31'),
(5, 'PO-20220913071657', 4, '2022-09-13', '', 'F', '2022-09-13 13:17:01', '2022-09-13 13:26:30'),
(6, 'PO-20220913075856', 4, '2022-09-13', '', 'F', '2022-09-13 13:59:22', '2022-09-13 14:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order_details`
--

CREATE TABLE `tbl_purchase_order_details` (
  `po_detail_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` decimal(11,2) NOT NULL,
  `supplier_price` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_purchase_order_details`
--

INSERT INTO `tbl_purchase_order_details` (`po_detail_id`, `po_id`, `product_id`, `qty`, `supplier_price`) VALUES
(1, 1, 11, '1.00', '21.00'),
(2, 3, 11, '12.00', '30.00'),
(3, 3, 12, '2.00', '122.00'),
(4, 4, 11, '120.00', '50.00'),
(5, 4, 12, '50.00', '12.00'),
(6, 2, 11, '1.00', '10.00'),
(7, 2, 12, '2.00', '500.00'),
(8, 5, 11, '230.00', '150.00'),
(9, 5, 12, '200.00', '180.00'),
(10, 5, 13, '100.00', '50.00'),
(12, 5, 14, '59.00', '130.00'),
(13, 6, 14, '50.00', '100.00'),
(14, 6, 13, '10.00', '200.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `service_remarks` varchar(255) NOT NULL,
  `service_fee` decimal(11,2) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`service_id`, `service_name`, `service_remarks`, `service_fee`, `date_added`, `date_last_modified`) VALUES
(3, 'Outlet Repair and Installation', 'a', '0.00', '0000-00-00 00:00:00', '2022-09-13 13:10:27'),
(4, 'Panel Upgrades', ' ', '0.00', '2022-08-31 15:01:12', '2022-09-13 13:10:31'),
(5, 'Electrical set up and rewiring', 'sample\r\n', '0.00', '2022-09-13 13:12:21', '2022-09-13 13:12:21'),
(6, 'Washer/Dryer & Appliance Lines', 'sample\r\n', '0.00', '2022-09-13 13:16:27', '2022-09-13 13:16:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_suppliers`
--

CREATE TABLE `tbl_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_address` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_suppliers`
--

INSERT INTO `tbl_suppliers` (`supplier_id`, `supplier_name`, `supplier_address`, `contact_number`, `remarks`, `date_added`, `date_last_modified`) VALUES
(4, 'Supplier Beta', 'Barangay Banago, Bacolod City', '021214444', 'beta only', '2022-05-25 11:10:07', '2022-05-25 11:10:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_category` varchar(1) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `date_added` datetime NOT NULL,
  `date_last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_fullname`, `user_category`, `username`, `password`, `date_added`, `date_last_modified`) VALUES
(1, 'Juan Dela Cruz', 'A', 'admin', '0cc175b9c0f1b6a831c399e269772661', '2022-06-28 00:00:00', '2022-06-28 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tbl_job_order`
--
ALTER TABLE `tbl_job_order`
  ADD PRIMARY KEY (`jo_id`);

--
-- Indexes for table `tbl_job_order_details`
--
ALTER TABLE `tbl_job_order_details`
  ADD PRIMARY KEY (`jo_detail_id`) USING BTREE;

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  ADD PRIMARY KEY (`po_id`);

--
-- Indexes for table `tbl_purchase_order_details`
--
ALTER TABLE `tbl_purchase_order_details`
  ADD PRIMARY KEY (`po_detail_id`);

--
-- Indexes for table `tbl_services`
--
ALTER TABLE `tbl_services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_job_order`
--
ALTER TABLE `tbl_job_order`
  MODIFY `jo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_job_order_details`
--
ALTER TABLE `tbl_job_order_details`
  MODIFY `jo_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  MODIFY `po_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_purchase_order_details`
--
ALTER TABLE `tbl_purchase_order_details`
  MODIFY `po_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
