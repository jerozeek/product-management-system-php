-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2019 at 11:39 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pms`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` int(100) NOT NULL,
  `customer_id` int(200) NOT NULL,
  `customer_name` varchar(200) DEFAULT NULL,
  `paid` int(100) NOT NULL,
  `due` int(100) NOT NULL,
  `total` int(200) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `employee` varchar(100) NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_details`
--

CREATE TABLE `bill_details` (
  `id` int(100) NOT NULL,
  `bill_id` int(200) NOT NULL,
  `product` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `profit` int(200) NOT NULL,
  `subtotal` int(200) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(100) NOT NULL,
  `store_name` varchar(1000) NOT NULL,
  `address` varchar(1000) NOT NULL DEFAULT 'no 54 aba owerri road, abia state',
  `logo` varchar(1000) NOT NULL,
  `status` int(1) NOT NULL,
  `return_sales` int(1) NOT NULL DEFAULT '0',
  `sale_expired` int(10) NOT NULL DEFAULT '0',
  `store_key` varchar(12) NOT NULL,
  `code` int(55) NOT NULL DEFAULT '55'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `store_name`, `address`, `logo`, `status`, `return_sales`, `sale_expired`, `store_key`, `code`) VALUES
(1, 'Links Supermarket', 'no 50 aba owerri road, abia state', '1565561138.jpeg', 1, 0, 0, '234758930288', 55);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `balance_in` int(100) NOT NULL DEFAULT '0',
  `balance_out` int(100) NOT NULL DEFAULT '0',
  `created` date NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenses_id` int(100) NOT NULL,
  `employee` varchar(1000) NOT NULL,
  `purpose` varchar(1000) NOT NULL,
  `amount` int(100) NOT NULL,
  `assigned_by` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `product` varchar(1000) NOT NULL DEFAULT '[{"product":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `customer` varchar(2000) NOT NULL DEFAULT '[{"customer":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `sales` varchar(1000) NOT NULL DEFAULT '[{"sales":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `employee` varchar(1000) NOT NULL DEFAULT '[{"employee":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `report` varchar(1000) NOT NULL DEFAULT '[{"product":"0","sales":"0","employee":"0","customer":"0"}]',
  `expired` varchar(1000) NOT NULL DEFAULT '[{"expire":"0"}]',
  `expenses` varchar(1000) NOT NULL DEFAULT '[{"expenses":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `employee_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `product`, `customer`, `sales`, `employee`, `report`, `expired`, `expenses`, `employee_id`) VALUES
(1, '[{"product":"1","add":"1","view":"1","edit":"1","delete":"1"}]', '[{"customer":"1","add":"1","view":"1","edit":"1","delete":"1"}]', '[{"sales":"1","add":"1","view":"1","edit":"1","delete":"1"}]', '[{"employee":"1","add":"1","view":"1","edit":"1","delete":"1"}]', '[{"employee":"1","product":"1","sales":"1","customer":"1"}]', '[{"expire":"1"}]', '[{"expenses":"1","add":"1","view":"1","edit":"1","delete":"1"}]', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `cost_price` float NOT NULL,
  `selling_price` float NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `expiry` date NOT NULL,
  `barcode` int(200) NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(200) NOT NULL DEFAULT 'cashier',
  `last_login` date NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `last_login`, `deleted`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '2019-10-15', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_details`
--
ALTER TABLE `bill_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_key` (`store_key`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenses_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bill_details`
--
ALTER TABLE `bill_details`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenses_id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
