-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2018 at 08:31 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `market`
--

-- --------------------------------------------------------

--
-- Table structure for table `kite_watch`
--

CREATE TABLE `kite_watch` (
  `id` int(11) NOT NULL,
  `absoluteChange` float(8,2) NOT NULL,
  `averagePrice` float(8,2) NOT NULL,
  `change` float(8,2) NOT NULL,
  `highPrice` float(8,2) NOT NULL,
  `lastPrice` float(8,2) NOT NULL,
  `sma1` float(8,2) NOT NULL,
  `sma2` float(8,2) NOT NULL,
  `lastQuantity` int(8) NOT NULL,
  `lowPrice` float(8,2) NOT NULL,
  `openPrice` float(8,2) NOT NULL,
  `totalBuyQuantity` int(11) NOT NULL,
  `totalSellQuantity` int(11) NOT NULL,
  `tradingsymbol` varchar(20) NOT NULL,
  `volume` bigint(20) NOT NULL,
  `insert_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kite_watch`
--
ALTER TABLE `kite_watch`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kite_watch`
--
ALTER TABLE `kite_watch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
