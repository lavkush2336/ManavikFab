-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 12, 2025 at 10:53 PM
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
(1, 'ManavikFab', '1760284976_brand_image1.png');

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
  `Price` int(10) NOT NULL,
  `Quantity` int(10) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `img1` varchar(255) NOT NULL,
  `img2` varchar(255) DEFAULT NULL,
  `img3` varchar(255) DEFAULT NULL,
  `img4` varchar(255) DEFAULT NULL,
  `img5` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `Name`, `Category`, `Brand`, `Price`, `Quantity`, `Description`, `img1`, `img2`, `img3`, `img4`, `img5`) VALUES
(1, 'Women Embroidered Stole', 'Stalls', 'ManavikFab', 1120, 0, 'This Elegant Viscose Rayon Stole offers a luxurious feel and a beautiful drape, perfect for elevating any ensemble. Crafted entirely from high-quality Viscose Rayon, it features a stylish tasselled border for a sophisticated finish. Due to its generous size (71.12m x 2.032m), it provides exceptional versatility for layering. To maintain its superior quality, this embroidered accessory requires Dry Clean only.', 'product_1760297195_1.jpg', 'product_1760296843_2.webp', 'product_1760296843_3.webp', 'product_1760296843_4.webp', 'product_1760296843_5.webp');

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
  `Verified` int(1) NOT NULL,
  `eotp` int(10) DEFAULT NULL,
  `potp` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`UserID`, `Name`, `Email`, `Phone`, `DOB`, `Password`, `Date`, `Verified`, `eotp`, `potp`) VALUES
(1, 'Divyam Puri', 'divyampu@gmail.com', '6284908998', '2005-02-11', '$2y$10$OYgBt/NLnmj37Bq76NNtZurjGeHcZRvCCTPYYWMnOUPPXPqMIFewq', NULL, 0, 0, 0),
(2, 'Rimmi Puri', 'rimmipu@gmail.com', '8968839777', '1978-08-22', '$2y$10$j8k61bXH5Z5swVGMwvdi6.5O8ym3fL6YOP2841awaLX4y84ONwvDy', NULL, 1, 957207, 219701);

--
-- Indexes for dumped tables
--

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
  MODIFY `UserID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
