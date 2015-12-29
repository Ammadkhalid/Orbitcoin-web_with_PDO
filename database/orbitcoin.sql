-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2015 at 05:06 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orbitcoin`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses_balance`
--

CREATE TABLE `addresses_balance` (
  `ID` int(11) NOT NULL,
  `Address` mediumtext NOT NULL,
  `Balance` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sender_address`
--

CREATE TABLE `sender_address` (
  `ID` int(11) NOT NULL,
  `Username` varchar(2555) NOT NULL,
  `Prev_tx_id` mediumtext NOT NULL,
  `next_tx_id` mediumtext NOT NULL,
  `Sender` varchar(25555) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `send_transations`
--

CREATE TABLE `send_transations` (
  `ID` int(11) NOT NULL,
  `Account` varchar(255) NOT NULL,
  `To_address` varchar(2555) NOT NULL,
  `Tx_id` varchar(25555) NOT NULL,
  `Amount_send` mediumtext NOT NULL,
  `Date_send` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Code` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Wallet_address` varchar(255) NOT NULL,
  `Session_id` varchar(255) NOT NULL,
  `Email_confirm` enum('No','Yes') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_previous_tx_id`
--

CREATE TABLE `user_previous_tx_id` (
  `ID` int(11) NOT NULL,
  `Username` varchar(2555) NOT NULL,
  `Prev_tx_id` varchar(25555) NOT NULL,
  `next_tx_id` mediumtext NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_receive_transations`
--

CREATE TABLE `user_receive_transations` (
  `ID` int(11) NOT NULL,
  `Tx_username` varchar(2555) NOT NULL,
  `Date` int(255) NOT NULL,
  `Amount` varchar(255) NOT NULL,
  `Tx_id` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses_balance`
--
ALTER TABLE `addresses_balance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sender_address`
--
ALTER TABLE `sender_address`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `send_transations`
--
ALTER TABLE `send_transations`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_previous_tx_id`
--
ALTER TABLE `user_previous_tx_id`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_receive_transations`
--
ALTER TABLE `user_receive_transations`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses_balance`
--
ALTER TABLE `addresses_balance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sender_address`
--
ALTER TABLE `sender_address`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `send_transations`
--
ALTER TABLE `send_transations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_previous_tx_id`
--
ALTER TABLE `user_previous_tx_id`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_receive_transations`
--
ALTER TABLE `user_receive_transations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
