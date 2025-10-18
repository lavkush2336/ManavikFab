-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2025 at 01:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ManavikFab`
--

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

CREATE TABLE `Address` (
  `AddressID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `FName` varchar(50) NOT NULL,
  `LName` varchar(50) NOT NULL,
  `Address` varchar(1000) NOT NULL,
  `City` varchar(50) NOT NULL,
  `State` varchar(50) NOT NULL,
  `Pin` int(10) NOT NULL,
  `Phone` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Address`
--

INSERT INTO `Address` (`AddressID`, `UserID`, `FName`, `LName`, `Address`, `City`, `State`, `Pin`, `Phone`) VALUES
(1, 1, 'Divyam', 'Puri', '1047, Street No. 6, Guru Nanak Nagar, Majitha Road', 'Amritsar', 'Punjab', 143001, 6284908998),
(2, 1, 'Amit', 'Puri', '15-B, Liberty Market, Railway Link Road, INA Colony', 'Amritsar', 'Punjab', 143001, 9878916868);

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `AdminID` int(10) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`AdminID`, `Email`, `Password`) VALUES
(1, 'pooja@gmail.com', '$2y$10$hdoI9BMdmY4qqtauw3ykrugX7VtFle1GNq83lPrg7HFzy6ELafvLG');

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `BrandID` int(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Logo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`BrandID`, `Name`, `Logo`) VALUES
(1, 'ManavikFab', '1760367102_brand_image1.png');

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `CategoryID` int(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Slug` varchar(50) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `PCategory` int(5) DEFAULT NULL,
  `Status` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`CategoryID`, `Name`, `Slug`, `Description`, `PCategory`, `Status`) VALUES
(1, 'Stalls', 'Ethnic', 'Stalls (Ethnic-Wear)', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(10) NOT NULL,
  `Name` varchar(150) NOT NULL,
  `Category` varchar(50) NOT NULL,
  `Brand` varchar(50) NOT NULL,
  `SPrice` int(10) NOT NULL,
  `Price` int(10) NOT NULL,
  `Quantity` int(10) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `Colour` varchar(10) NOT NULL,
  `Size` varchar(10) NOT NULL,
  `img1` varchar(255) NOT NULL,
  `img2` varchar(255) DEFAULT NULL,
  `img3` varchar(255) DEFAULT NULL,
  `img4` varchar(255) DEFAULT NULL,
  `img5` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `Name`, `Category`, `Brand`, `SPrice`, `Price`, `Quantity`, `Description`, `Colour`, `Size`, `img1`, `img2`, `img3`, `img4`, `img5`) VALUES
(1, 'Women Ethnic Motifs Embroidered Stole', 'Stalls', 'ManavikFab', 1695, 1120, 50, 'This Elegant Viscose Rayon Stole offers a luxurious feel and a beautiful drape, perfect for elevating any ensemble. Crafted entirely from high-quality Viscose Rayon, it features a stylish tasselled border for a sophisticated finish. Due to its generous size (71.12m x 2.032m), it provides exceptional versatility for layering. To maintain its superior quality, this embroidered accessory requires Dry Clean only.', 'Maroon', 'All Sizes', 'product_1760367244_1.png', 'product_1760367244_2.png', 'product_1760367244_3.png', 'product_1760367244_4.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `UserID` int(255) NOT NULL,
  `Name` varchar(1000) NOT NULL,
  `Email` varchar(1000) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `DOB` varchar(10) NOT NULL,
  `Password` varchar(1000) NOT NULL,
  `Date` varchar(10) DEFAULT NULL,
  `IP` varchar(255) NOT NULL,
  `Remember` int(1) NOT NULL,
  `Verified` int(1) NOT NULL,
  `eotp` int(10) DEFAULT NULL,
  `potp` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`UserID`, `Name`, `Email`, `Phone`, `DOB`, `Password`, `Date`, `IP`, `Remember`, `Verified`, `eotp`, `potp`) VALUES
(1, 'Divyam Puri', 'divyampu@gmail.com', '6284908998', '2005-02-11', '$2y$10$OYgBt/NLnmj37Bq76NNtZurjGeHcZRvCCTPYYWMnOUPPXPqMIFewq', NULL, '::1', 1, 1, 615726, 473837),
(2, 'Rimmi Puri', 'rimmipu@gmail.com', '8968839777', '1978-08-22', '$2y$10$j8k61bXH5Z5swVGMwvdi6.5O8ym3fL6YOP2841awaLX4y84ONwvDy', NULL, '::2', 0, 1, 615726, 473837),
(3, 'Lavkush', 'lavkush@gmail.com', '9999999999', '2006-04-13', '$2y$10$gRBksQdcvCVF4NHTndhuh.VQm3TSP/tCyrK5eOvXtrFl22mjLrO2O', NULL, '::3', 0, 1, 615726, 473837),
(4, 'Lavish', 'lavish@gmail.com', '8888888888', '2004-12-22', '$2y$10$Cd7wiaG5CFpEuvhBroBC5uLyOU8mG2hUMPQPOhOGOO/tgbwb/eTdK', '2025-10-13', '::4', 0, 1, 615726, 473837);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`AddressID`);

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`BrandID`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Address`
--
ALTER TABLE `Address`
  MODIFY `AddressID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `AdminID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `BrandID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `CategoryID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `UserID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
