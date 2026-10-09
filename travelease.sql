-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 05, 2026 at 09:11 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelease`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `BookingID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `FlightID` int NOT NULL,
  `BookingDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `Status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'Confirmed',
  PRIMARY KEY (`BookingID`),
  KEY `UserID` (`UserID`),
  KEY `FlightID` (`FlightID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`BookingID`, `UserID`, `FlightID`, `BookingDate`, `Status`) VALUES
(1, 1, 3, '2026-10-05 19:38:38', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

DROP TABLE IF EXISTS `flights`;
CREATE TABLE IF NOT EXISTS `flights` (
  `FlightID` int NOT NULL AUTO_INCREMENT,
  `FlightNumber` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Departure` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Destination` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DepartureDate` date NOT NULL,
  `DepartureTime` time NOT NULL,
  `ArrivalTime` time NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Stops` int DEFAULT '0',
  `Baggage` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AvailableSeats` int DEFAULT '100',
  PRIMARY KEY (`FlightID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`FlightID`, `FlightNumber`, `Departure`, `Destination`, `DepartureDate`, `DepartureTime`, `ArrivalTime`, `Price`, `Stops`, `Baggage`, `AvailableSeats`) VALUES
(1, 'QF401', 'Hyderabad', 'Delhi', '2026-10-20', '07:00:00', '08:35:00', 189.00, 0, '23kg', 80),
(2, 'VA808', 'Hyderabad', 'Delhi', '2026-10-20', '09:30:00', '11:05:00', 169.00, 0, '23kg', 65),
(3, 'JQ501', 'Hyderabad', 'Delhi', '2026-10-20', '13:00:00', '14:35:00', 139.00, 0, '20kg', 50),
(4, 'QF510', 'Delhi', 'Hyderabad', '2026-10-21', '08:00:00', '09:30:00', 185.00, 0, '23kg', 70),
(5, 'JQ402', 'Hyderabad', 'Goa', '2026-10-22', '10:15:00', '11:40:00', 149.00, 0, '20kg', 55),
(6, 'VA515', 'Hyderabad', 'Brisbane', '2026-10-22', '12:00:00', '13:30:00', 159.00, 0, '23kg', 75),
(7, 'QF81', 'Hyderabad', 'Goa', '2026-10-25', '10:30:00', '16:50:00', 699.00, 0, '30kg', 45),
(8, 'SQ212', 'Hyderabad', 'Goa', '2026-10-25', '15:45:00', '21:55:00', 749.00, 0, '30kg', 40),
(9, 'CX100', 'Hyderabad', 'China', '2026-10-26', '14:00:00', '21:20:00', 720.00, 0, '23kg', 35),
(10, 'QF127', 'Hyderabad', 'USA', '2026-10-28', '11:00:00', '20:00:00', 780.00, 0, '30kg', 30);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Role` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Name`, `Email`, `Password`, `Role`) VALUES
(1, 'Test User', 'test@travelease.com', '$2y$10$.bl6Uj/hXFfIZEaAAtfj5uJOR2Q2OLCH4dpqi0C9uLGcNpQhIDFNu', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
