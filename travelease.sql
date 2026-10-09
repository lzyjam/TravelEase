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

INSERT INTO flights
(airline, flight_number, source, destination, departure_date, departure_time,
 arrival_date, arrival_time, total_seats, available_seats, price, status)
VALUES
('IndiGo', '6E101', 'Hyderabad', 'Delhi', '2026-11-10', '06:30:00',
 '2026-11-10', '08:45:00', 180, 145, 4999.00, 'ACTIVE'),

('Air India', 'AI542', 'Hyderabad', 'Mumbai', '2026-11-11', '09:15:00',
 '2026-11-11', '10:55:00', 160, 120, 5299.00, 'ACTIVE'),

('Vistara', 'UK875', 'Hyderabad', 'Bengaluru', '2026-11-12', '11:20:00',
 '2026-11-12', '12:35:00', 150, 98, 3799.00, 'ACTIVE'),

('Akasa Air', 'QP1345', 'Hyderabad', 'Chennai', '2026-11-13', '14:10:00',
 '2026-11-13', '15:35:00', 189, 170, 3299.00, 'ACTIVE'),

('IndiGo', '6E204', 'Delhi', 'Hyderabad', '2026-11-14', '16:00:00',
 '2026-11-14', '18:20:00', 180, 132, 4799.00, 'ACTIVE'),

('Air India', 'AI618', 'Mumbai', 'Hyderabad', '2026-11-15', '18:45:00',
 '2026-11-15', '20:20:00', 160, 110, 5499.00, 'ACTIVE'),

('Vistara', 'UK899', 'Bengaluru', 'Chennai', '2026-11-16', '07:40:00',
 '2026-11-16', '08:50:00', 150, 76, 2999.00, 'ACTIVE'),

('Akasa Air', 'QP1612', 'Delhi', 'Bengaluru', '2026-11-17', '20:15:00',
 '2026-11-17', '22:55:00', 189, 154, 5899.00, 'ACTIVE'),

('IndiGo', '6E333', 'Chennai', 'Hyderabad', '2026-11-18', '10:05:00',
 '2026-11-18', '11:35:00', 180, 180, 3199.00, 'ACTIVE'),

('Air India', 'AI701', 'Hyderabad', 'Kolkata', '2026-11-19', '13:30:00',
 '2026-11-19', '15:40:00', 160, 42, 6399.00, 'ACTIVE');

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
