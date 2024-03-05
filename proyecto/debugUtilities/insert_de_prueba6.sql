-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.28-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.6.0.6781
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla destiny_airlines.additional_informations
CREATE TABLE IF NOT EXISTS `additional_informations` (
  `id_ADDITIONAL_INFORMATIONS` int(11) NOT NULL AUTO_INCREMENT,
  `id_PASSENGERS` int(11) NOT NULL,
  `assistiveDevices` varchar(50) DEFAULT NULL,
  `medicalEquipment` varchar(50) DEFAULT NULL,
  `mobilityLimitations` varchar(50) DEFAULT NULL,
  `communicationNeeds` varchar(50) DEFAULT NULL,
  `medicationRequirements` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_ADDITIONAL_INFORMATIONS`),
  KEY `additional_informations_ibfk_1` (`id_PASSENGERS`),
  CONSTRAINT `additional_informations_ibfk_1` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.additional_informations: ~14 rows (aproximadamente)
INSERT INTO `additional_informations` (`id_ADDITIONAL_INFORMATIONS`, `id_PASSENGERS`, `assistiveDevices`, `medicalEquipment`, `mobilityLimitations`, `communicationNeeds`, `medicationRequirements`) VALUES
	(1, 1, NULL, NULL, NULL, NULL, NULL),
	(2, 2, NULL, NULL, NULL, NULL, NULL),
	(3, 3, NULL, NULL, NULL, NULL, NULL),
	(4, 4, NULL, NULL, NULL, NULL, NULL),
	(41, 43, 'cane', '', '', '', ''),
	(42, 44, 'cane', '', '', '', ''),
	(43, 45, 'cane', '', '', '', ''),
	(44, 46, 'cane', '', '', '', ''),
	(45, 47, 'cane', 'NULL', 'NULL', 'NULL', 'NULL'),
	(46, 48, 'cane', 'NULL', 'NULL', 'NULL', 'NULL'),
	(47, 49, 'cane', 'NULL', 'NULL', 'NULL', 'NULL'),
	(48, 50, 'cane', 'NULL', 'NULL', 'NULL', 'NULL'),
	(49, 51, 'cane', 'NULL', 'NULL', 'NULL', 'NULL'),
	(50, 52, 'cane', 'NULL', 'NULL', 'NULL', 'NULL');

-- Volcando estructura para tabla destiny_airlines.airplanes
CREATE TABLE IF NOT EXISTS `airplanes` (
  `id_AIRPLANES` int(11) NOT NULL AUTO_INCREMENT,
  `airplaneCode` varchar(10) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `seats` int(4) NOT NULL,
  PRIMARY KEY (`id_AIRPLANES`),
  UNIQUE KEY `airplaneCode` (`airplaneCode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.airplanes: ~4 rows (aproximadamente)
INSERT INTO `airplanes` (`id_AIRPLANES`, `airplaneCode`, `model`, `seats`) VALUES
	(1, 'A123', 'Boeing 737', 180),
	(2, 'B456', 'Airbus A320', 200),
	(3, 'C789', 'Boeing 777', 280),
	(4, 'D012', 'Airbus A350', 250);

-- Volcando estructura para tabla destiny_airlines.airports
CREATE TABLE IF NOT EXISTS `airports` (
  `id_AIRPORTS` int(11) NOT NULL AUTO_INCREMENT,
  `id_CURRENCIES` int(11) DEFAULT NULL,
  `IATA` varchar(3) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_AIRPORTS`),
  UNIQUE KEY `IATA` (`IATA`),
  UNIQUE KEY `name` (`name`),
  KEY `id_CURRENCIES` (`id_CURRENCIES`),
  CONSTRAINT `airports_ibfk_1` FOREIGN KEY (`id_CURRENCIES`) REFERENCES `currencies` (`id_CURRENCIES`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.airports: ~4 rows (aproximadamente)
INSERT INTO `airports` (`id_AIRPORTS`, `id_CURRENCIES`, `IATA`, `name`) VALUES
	(1, 1, 'JFK', 'John F. Kennedy International Airport'),
	(2, 2, 'LHR', 'Heathrow Airport'),
	(3, 3, 'CDG', 'Charles de Gaulle Airport'),
	(4, 4, 'PEK', 'Beijing Capital International Airport');

-- Volcando estructura para tabla destiny_airlines.books
CREATE TABLE IF NOT EXISTS `books` (
  `id_BOOKS` int(11) NOT NULL AUTO_INCREMENT,
  `id_FLIGHTS` int(11) DEFAULT NULL,
  `id_USERS` int(11) DEFAULT NULL,
  `id_PRIMARY_CONTACT_INFORMATIONS` int(11) NOT NULL,
  `bookCode` varchar(10) NOT NULL,
  `checkinDate` date DEFAULT NULL,
  `direction` enum('departure','return') NOT NULL DEFAULT 'departure',
  `adultsNumber` tinyint(3) DEFAULT 1,
  `childsNumber` tinyint(3) DEFAULT 0,
  `infantsNumber` tinyint(3) DEFAULT 0,
  PRIMARY KEY (`id_BOOKS`),
  UNIQUE KEY `bookCode` (`bookCode`),
  KEY `id_PRIMARY_CONTACT_INFORMATIONS` (`id_PRIMARY_CONTACT_INFORMATIONS`),
  KEY `books_ibfk_2` (`id_USERS`),
  KEY `books_ibfk_1` (`id_FLIGHTS`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`id_FLIGHTS`) REFERENCES `flights` (`id_FLIGHTS`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `books_ibfk_2` FOREIGN KEY (`id_USERS`) REFERENCES `users` (`id_USERS`) ON DELETE SET NULL,
  CONSTRAINT `books_ibfk_3` FOREIGN KEY (`id_PRIMARY_CONTACT_INFORMATIONS`) REFERENCES `primary_contact_informations` (`id_PRIMARY_CONTACT_INFORMATIONS`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.books: ~10 rows (aproximadamente)
INSERT INTO `books` (`id_BOOKS`, `id_FLIGHTS`, `id_USERS`, `id_PRIMARY_CONTACT_INFORMATIONS`, `bookCode`, `checkinDate`, `direction`, `adultsNumber`, `childsNumber`, `infantsNumber`) VALUES
	(25, 1, 1, 1, 'BK123', '2023-10-19', 'departure', 1, 0, 0),
	(26, 2, 2, 1, 'BK456', '2023-10-20', 'departure', 1, 0, 0),
	(27, 3, 1, 1, 'BK789', '2023-10-21', 'departure', 1, 0, 0),
	(28, 4, 2, 2, 'BK012', '2023-10-22', 'departure', 1, 2, 1),
	(29, 1, NULL, 1, 'd5336a6d-5', NULL, 'departure', 2, 0, 0),
	(61, 1, NULL, 39, '7e95bb6b-0', NULL, 'departure', 2, 0, 0),
	(62, 1, NULL, 40, '333f86a2-0', NULL, 'departure', 2, 0, 0),
	(63, 1, NULL, 41, 'cacca5d3-e', NULL, 'departure', 2, 0, 0),
	(64, 1, NULL, 42, '649c6bcd-d', NULL, 'departure', 2, 0, 0),
	(65, 1, NULL, 43, '2ea2e3fc-b', NULL, 'departure', 2, 0, 0);

-- Volcando estructura para tabla destiny_airlines.books_services
CREATE TABLE IF NOT EXISTS `books_services` (
  `id_BOOKS_SERVICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_BOOKS` int(11) NOT NULL,
  `id_SERVICES` int(11) NOT NULL,
  PRIMARY KEY (`id_BOOKS_SERVICES`),
  KEY `books_services_ibfk_1` (`id_BOOKS`),
  KEY `books_services_ibfk_2` (`id_SERVICES`),
  CONSTRAINT `books_services_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`) ON DELETE CASCADE,
  CONSTRAINT `books_services_ibfk_2` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.books_services: ~8 rows (aproximadamente)
INSERT INTO `books_services` (`id_BOOKS_SERVICES`, `id_BOOKS`, `id_SERVICES`) VALUES
	(11, 25, 3),
	(12, 26, 3),
	(13, 25, 4),
	(14, 26, 3),
	(15, 27, 4),
	(60, 63, 10),
	(61, 64, 10),
	(62, 65, 10);

-- Volcando estructura para tabla destiny_airlines.currencies
CREATE TABLE IF NOT EXISTS `currencies` (
  `id_CURRENCIES` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `idToEuro` double(10,3) DEFAULT NULL,
  `euroToId` double(10,3) DEFAULT NULL,
  PRIMARY KEY (`id_CURRENCIES`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.currencies: ~4 rows (aproximadamente)
INSERT INTO `currencies` (`id_CURRENCIES`, `name`, `idToEuro`, `euroToId`) VALUES
	(1, 'Euro', 1.000, 1.000),
	(2, 'US Dollar', 0.850, 1.180),
	(3, 'British Pound', 1.120, 0.890),
	(4, 'Chinese Yuan', 0.120, 8.250);

-- Volcando estructura para tabla destiny_airlines.flights
CREATE TABLE IF NOT EXISTS `flights` (
  `id_FLIGHTS` int(11) NOT NULL AUTO_INCREMENT,
  `id_AIRPLANES` int(11) DEFAULT NULL,
  `id_ITINERARIES` int(11) NOT NULL,
  `flightCode` varchar(50) NOT NULL,
  `date` date DEFAULT NULL,
  `hour` time DEFAULT NULL,
  `price` double DEFAULT NULL,
  `freeSeats` int(11) DEFAULT NULL,
  `arrivalHour` time DEFAULT NULL,
  PRIMARY KEY (`id_FLIGHTS`),
  UNIQUE KEY `flightCode` (`flightCode`),
  KEY `id_AIRPLANES` (`id_AIRPLANES`),
  KEY `id_ITINERARIES` (`id_ITINERARIES`),
  CONSTRAINT `flights_ibfk_1` FOREIGN KEY (`id_AIRPLANES`) REFERENCES `airplanes` (`id_AIRPLANES`),
  CONSTRAINT `flights_ibfk_2` FOREIGN KEY (`id_ITINERARIES`) REFERENCES `itineraries` (`id_ITINERARIES`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.flights: ~4 rows (aproximadamente)
INSERT INTO `flights` (`id_FLIGHTS`, `id_AIRPLANES`, `id_ITINERARIES`, `flightCode`, `date`, `hour`, `price`, `freeSeats`, `arrivalHour`) VALUES
	(1, 1, 1, '1231', '2024-10-20', '08:00:00', 350, 136, '10:30:00'),
	(2, 2, 1, '345345', '2023-10-21', '15:30:00', 420, 200, '18:00:00'),
	(3, 3, 2, '324', '2023-10-22', '10:45:00', 280, 100, '13:15:00'),
	(4, 4, 3, '3545', '2023-12-23', '19:20:00', 500, 100, '22:00:00');

-- Volcando estructura para tabla destiny_airlines.invoices
CREATE TABLE IF NOT EXISTS `invoices` (
  `id_INVOICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_BOOKS` int(11) DEFAULT NULL,
  `invoiceCode` varchar(50) NOT NULL,
  `invoicedDate` datetime DEFAULT current_timestamp(),
  `price` double NOT NULL,
  `isPaid` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_INVOICES`),
  UNIQUE KEY `invoiceCode` (`invoiceCode`),
  KEY `invoices_ibfk_1` (`id_BOOKS`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.invoices: ~8 rows (aproximadamente)
INSERT INTO `invoices` (`id_INVOICES`, `id_BOOKS`, `invoiceCode`, `invoicedDate`, `price`, `isPaid`) VALUES
	(1, 28, 'weaewwea23231', '2023-11-05 23:03:14', 30, 0),
	(2, 28, 'ewqewqq2312', '2023-11-05 23:03:14', 40, 0),
	(27, NULL, 'de672819-ca38-4930-83e2-d7d69b5cacf5', '2023-12-06 19:48:33', 790, 1),
	(29, 61, '64be8e6b-a76f-40ba-9cda-b3dea4e21ab3', '2023-12-13 22:17:59', 700, 0),
	(30, 62, '93333094-e0ea-4f50-a414-c60854683c86', '2023-12-14 14:57:25', 700, 1),
	(31, 63, 'a6850b59-fc7c-4c8d-b983-4f28e961871c', '2024-01-31 17:24:07', 800, 0),
	(32, 64, '7ed7c4f0-00a3-4bf7-b991-679ab11796cc', '2024-01-31 17:26:25', 800, 0),
	(33, 65, '15edee8f-86c7-42d3-a57c-b579f71f96c5', '2024-01-31 17:28:38', 800, 1);

-- Volcando estructura para tabla destiny_airlines.itineraries
CREATE TABLE IF NOT EXISTS `itineraries` (
  `id_ITINERARIES` int(11) NOT NULL AUTO_INCREMENT,
  `origin` int(11) NOT NULL,
  `destiny` int(11) NOT NULL,
  `routeCode` varchar(20) NOT NULL,
  PRIMARY KEY (`id_ITINERARIES`),
  UNIQUE KEY `routeCode` (`routeCode`),
  KEY `origin` (`origin`),
  KEY `destiny` (`destiny`),
  CONSTRAINT `itineraries_ibfk_1` FOREIGN KEY (`origin`) REFERENCES `airports` (`id_AIRPORTS`),
  CONSTRAINT `itineraries_ibfk_2` FOREIGN KEY (`destiny`) REFERENCES `airports` (`id_AIRPORTS`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.itineraries: ~4 rows (aproximadamente)
INSERT INTO `itineraries` (`id_ITINERARIES`, `origin`, `destiny`, `routeCode`) VALUES
	(1, 1, 2, 'ABC123'),
	(2, 2, 1, 'DEF456'),
	(3, 3, 4, 'GHI789'),
	(4, 4, 3, 'JKL012');

-- Volcando estructura para tabla destiny_airlines.passengers
CREATE TABLE IF NOT EXISTS `passengers` (
  `id_PASSENGERS` int(11) NOT NULL AUTO_INCREMENT,
  `id_BOOKS` int(11) NOT NULL,
  `passengerCode` varchar(50) NOT NULL,
  `documentationType` varchar(50) NOT NULL DEFAULT '',
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date DEFAULT NULL,
  `nationality` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `ageCategory` enum('infant','child','adult') NOT NULL DEFAULT 'adult',
  `dateBirth` date NOT NULL,
  PRIMARY KEY (`id_PASSENGERS`),
  UNIQUE KEY `passengerCode` (`passengerCode`),
  KEY `passengers_ibfk_1` (`id_BOOKS`),
  CONSTRAINT `passengers_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.passengers: ~14 rows (aproximadamente)
INSERT INTO `passengers` (`id_PASSENGERS`, `id_BOOKS`, `passengerCode`, `documentationType`, `documentCode`, `expirationDate`, `nationality`, `country`, `firstName`, `lastName`, `title`, `ageCategory`, `dateBirth`) VALUES
	(1, 25, 'P001', 'DNI', '12345678A', '2023-10-16', '', '', 'David', 'García', 'Mr', 'adult', '2002-12-13'),
	(2, 25, 'P002', 'Passport', 'AB123456', '2023-10-16', '', '', 'Laura', 'López', 'Ms', 'adult', '2003-12-13'),
	(3, 25, 'P003', 'DNI', '87654321B', '2023-10-16', '', '', 'Carlos', 'Martín', 'Mr', 'child', '2013-12-13'),
	(4, 25, 'P004', 'Passport', 'CD789012', '2023-10-16', '', '', 'Elena', 'Sánchez', 'Ms', 'adult', '2003-12-13'),
	(43, 61, 'bd64ed7e-0607-4aaa-a0d1-b879249f3745', 'DNI', '12345678A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2013-11-01'),
	(44, 61, '34aa2034-fcd4-4cd5-b76e-9e2794b11316', 'DNI', '12345677A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2013-11-01'),
	(45, 62, '30520be8-5837-4fd7-9050-ce7e0f44f445', 'DNI', '12345678A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2013-11-01'),
	(46, 62, '89fb13b2-46c1-4dd8-8a4d-5e71874313c1', 'DNI', '12345677A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2013-11-01'),
	(47, 63, '23cbc433-2ed6-4392-9352-140476143c3a', 'DNI', '12345678A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2003-11-01'),
	(48, 63, '3586b546-de6e-4b38-b543-320280dd89e9', 'DNI', '12345677A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2002-11-01'),
	(49, 64, 'd709af17-fe39-4e23-ad68-b7803316f803', 'DNI', '12345678A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2003-11-01'),
	(50, 64, 'abfb7f59-3a59-47d1-bf4b-7e6984684f41', 'DNI', '12345677A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2002-11-01'),
	(51, 65, 'a532d94f-14d3-413d-8eea-a859e0f8edab', 'DNI', '12345678A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2003-11-01'),
	(52, 65, 'ec466c0e-a8d9-481e-b493-07e15c31fc3b', 'DNI', '12345677A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult', '2002-11-01');

-- Volcando estructura para tabla destiny_airlines.passengers_books_services
CREATE TABLE IF NOT EXISTS `passengers_books_services` (
  `id_PASSENGERS_SERVICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_PASSENGERS` int(11) NOT NULL,
  `id_BOOKS` int(11) NOT NULL,
  `id_SERVICES` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_PASSENGERS_SERVICES`),
  KEY `passengers_books_services_ibfk_1` (`id_PASSENGERS`),
  KEY `passengers_books_services_ibfk_2` (`id_BOOKS`),
  KEY `passengers_books_services_ibfk_3` (`id_SERVICES`),
  CONSTRAINT `passengers_books_services_ibfk_1` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`) ON DELETE CASCADE,
  CONSTRAINT `passengers_books_services_ibfk_2` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`) ON DELETE CASCADE,
  CONSTRAINT `passengers_books_services_ibfk_3` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.passengers_books_services: ~4 rows (aproximadamente)
INSERT INTO `passengers_books_services` (`id_PASSENGERS_SERVICES`, `id_PASSENGERS`, `id_BOOKS`, `id_SERVICES`) VALUES
	(6, 1, 28, 1),
	(8, 2, 25, NULL),
	(9, 3, 26, 11),
	(10, 4, 27, NULL);

-- Volcando estructura para tabla destiny_airlines.primary_contact_informations
CREATE TABLE IF NOT EXISTS `primary_contact_informations` (
  `id_PRIMARY_CONTACT_INFORMATIONS` int(11) NOT NULL AUTO_INCREMENT,
  `documentationType` varchar(50) NOT NULL DEFAULT '',
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `phoneNumber1` varchar(20) NOT NULL,
  `phoneNumber2` varchar(20) DEFAULT NULL,
  `country` varchar(25) NOT NULL,
  `townCity` varchar(50) NOT NULL,
  `streetAddress` varchar(50) NOT NULL,
  `zipCode` varchar(5) NOT NULL,
  `companyName` varchar(50) DEFAULT NULL,
  `companyTaxNumber` varchar(50) DEFAULT NULL,
  `companyPhoneNumber` varchar(20) DEFAULT NULL,
  `dateBirth` date NOT NULL,
  PRIMARY KEY (`id_PRIMARY_CONTACT_INFORMATIONS`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.primary_contact_informations: ~8 rows (aproximadamente)
INSERT INTO `primary_contact_informations` (`id_PRIMARY_CONTACT_INFORMATIONS`, `documentationType`, `documentCode`, `expirationDate`, `title`, `firstName`, `lastName`, `emailAddress`, `phoneNumber1`, `phoneNumber2`, `country`, `townCity`, `streetAddress`, `zipCode`, `companyName`, `companyTaxNumber`, `companyPhoneNumber`, `dateBirth`) VALUES
	(1, 'DNI', '232332', '2026-11-20', NULL, 'Sergi', 'waa', 'aaa@gmail.com', '11111111', NULL, 'España', 'Albacete', 'Calle falsa 123', '0909', NULL, NULL, NULL, '2005-12-13'),
	(2, 'DNI', '232332', '2026-11-20', NULL, 'Sergi', 'waa', 'aaa2@gmail.com', '11111111', NULL, 'España', 'Albacete', 'Calle falsa 123', '0909', NULL, NULL, NULL, '2006-09-13'),
	(36, 'DNI', '12345678A', '2024-12-01', 'Mr', 'Sergio', 'Corredor', 'sergiodesarrolladorweb@gmail.com', '123456789', '123456788', 'España', 'Albacete', 'calle falsa 123', '3127', 'Compañía turbia', NULL, NULL, '2006-12-10'),
	(39, 'DNI', '12345678A', '2024-12-01', 'Mr', 'Sergio', 'Corredor', 'sergiodesarrolladorweb@gmail.com', '123456789', '123456788', 'España', 'Albacete', 'calle falsa 123', '3127', 'Compañía turbia', NULL, NULL, '2006-03-16'),
	(40, 'DNI', '12345678A', '2024-12-01', 'Mr', 'Sergio', 'Corredor', 'sergiodesarrolladorweb@gmail.com', '123456789', '123456788', 'España', 'Albacete', 'calle falsa 123', '3127', 'Compañía turbia', NULL, NULL, '2003-11-01'),
	(41, 'DNI', '12345678A', '2024-12-01', 'Mr', 'Sergio', 'Corredor', 'sergiodesarrolladorweb@gmail.com', '123456789', '123456788', 'España', 'Albacete', 'calle falsa 123', '3127', 'Compañía turbia', NULL, NULL, '2003-11-01'),
	(42, 'DNI', '12345678A', '2024-12-01', 'Mr', 'Sergio', 'Corredor', 'sergiodesarrolladorweb@gmail.com', '123456789', '123456788', 'España', 'Albacete', 'calle falsa 123', '3127', 'Compañía turbia', NULL, NULL, '2003-11-01'),
	(43, 'DNI', '12345678A', '2024-12-01', 'Mr', 'Sergio', 'Corredor', 'sergiodesarrolladorweb@gmail.com', '123456789', '123456788', 'España', 'Albacete', 'calle falsa 123', '3127', 'Compañía turbia', NULL, NULL, '2003-11-01');

-- Volcando estructura para tabla destiny_airlines.services
CREATE TABLE IF NOT EXISTS `services` (
  `id_SERVICES` int(11) NOT NULL AUTO_INCREMENT,
  `serviceCode` varchar(10) NOT NULL,
  `serviceGroupingType` enum('individual','collective','both') NOT NULL,
  `type` varchar(30) NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `billingCategory` enum('PaidService','PercentageDiscount') NOT NULL,
  `priceOrDiscount` double DEFAULT NULL,
  `description` varchar(600) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id_SERVICES`),
  UNIQUE KEY `serviceCode` (`serviceCode`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.services: ~18 rows (aproximadamente)
INSERT INTO `services` (`id_SERVICES`, `serviceCode`, `serviceGroupingType`, `type`, `subtype`, `billingCategory`, `priceOrDiscount`, `description`, `status`) VALUES
	(1, 'SRV001', 'individual', 'Elige tu Trono', 'Elige tu Trono', 'PaidService', 15, 'Los pasajeros pueden elegir su asiento preferido por un costo adicional', 'active'),
	(3, 'SRV003', 'collective', 'Traslado sin Estrés', 'Traslado sin Estrés', 'PaidService', 30, 'Ofrece un servicio de transporte para llevar a todos los pasajeros de la reserva al aeropuerto', 'active'),
	(4, 'SRV004', 'collective', 'Paquetes de Ensueño', 'Paquetes de Ensueño', 'PaidService', 25, 'Ofrece paquetes que incluyen vuelos, alojamiento y actividades turísticas', 'active'),
	(5, 'SRV005', 'individual', 'Más Espacio, Más Aventuras', 'Más Espacio, Más Aventuras', 'PaidService', 20, 'Los pasajeros pueden pagar por equipaje adicional', 'active'),
	(6, 'SRV006', 'individual', 'Check-in Exprés', 'Check-in Exprés', 'PaidService', 10, 'Ofrece un check-in más rápido en el aeropuerto', 'active'),
	(7, 'SRV007', 'individual', 'Embarque Real', 'Embarque Real', 'PaidService', 10, 'Los pasajeros pueden ser los primeros en abordar el avión', 'active'),
	(8, 'SRV008', 'individual', 'Conexión Nube', 'Navegación Básica', 'PaidService', 5, 'Para navegación web y correo electrónico', 'active'),
	(9, 'SRV009', 'collective', 'Reserva en Equipo', 'Reserva en Equipo', 'PercentageDiscount', 10, 'Ofrece descuentos para reservas de grupo para más de X personas', 'active'),
	(10, 'SRV010', 'collective', 'Tu Asistente de Viaje', 'Tu Asistente de Viaje', 'PaidService', 100, 'Ofrece servicios de conserjería para ayudar a los pasajeros a planificar su viaje', 'active'),
	(11, 'SRV011', 'individual', 'Acceso al Paraíso VIP', 'Acceso Básico', 'PaidService', 50, 'Acceso a la sala VIP con bebidas y snacks gratuitos', 'active'),
	(12, 'SRV012', 'collective', 'Descuento de Vuelo Redondo', 'Descuento de Vuelo Redondo', 'PercentageDiscount', 15, 'Un descuento para los clientes que reserven un vuelo de ida y vuelta. Esto puede incentivar a los clientes a reservar ambos vuelos con la misma aerolínea', 'active'),
	(13, 'SRV013', 'individual', 'Delicias Gourmet', 'Delicias del mar', 'PaidService', 20, 'Una selección de platos de mariscos', 'active'),
	(14, 'SRV014', 'individual', 'Delicias Gourmet', 'Sabores del Mundo', 'PaidService', 15, 'Platos internacionales de varios países', 'active'),
	(15, 'SRV015', 'individual', 'Delicias Gourmet', 'Opción Saludable', 'PaidService', 12, 'Comidas bajas en calorías y nutritivas.', 'active'),
	(19, 'SRV016', 'individual', 'Acceso al Paraíso VIP', 'Acceso Premium', 'PaidService', 50, 'Acceso a la sala VIP con comidas completas, duchas y áreas de descanso.', 'active'),
	(20, 'SRV017', 'individual', 'Acceso al Paraíso VIP', 'Acceso Familiar', 'PaidService', 50, 'Acceso a la sala VIP con áreas de juegos para niños', 'active'),
	(22, 'SRV018', 'individual', 'Conexión Nube', 'Streaming', 'PaidService', 5, 'Para ver vídeos y escuchar música online', 'active'),
	(23, 'SRV019', 'individual', 'Conexión Nube', 'Máxima velocidad', 'PaidService', 5, 'Experimenta la libertad de navegar por la web a la velocidad de la luz', 'active');

-- Volcando estructura para tabla destiny_airlines.services_invoices
CREATE TABLE IF NOT EXISTS `services_invoices` (
  `id_SERVICES_INVOICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_INVOICES` int(11) NOT NULL,
  `id_SERVICES` int(11) DEFAULT NULL,
  `id_PASSENGERS` int(11) DEFAULT NULL,
  `addRemove` enum('add','remove') NOT NULL DEFAULT 'add',
  `oldPrice` double NOT NULL,
  PRIMARY KEY (`id_SERVICES_INVOICES`),
  KEY `services_invoices_ibfk_1` (`id_INVOICES`),
  KEY `services_invoices_ibfk_2` (`id_SERVICES`),
  KEY `services_invoices_ibfk_3` (`id_PASSENGERS`),
  CONSTRAINT `services_invoices_ibfk_1` FOREIGN KEY (`id_INVOICES`) REFERENCES `invoices` (`id_INVOICES`) ON DELETE CASCADE,
  CONSTRAINT `services_invoices_ibfk_2` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`) ON DELETE SET NULL,
  CONSTRAINT `services_invoices_ibfk_3` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.services_invoices: ~8 rows (aproximadamente)
INSERT INTO `services_invoices` (`id_SERVICES_INVOICES`, `id_INVOICES`, `id_SERVICES`, `id_PASSENGERS`, `addRemove`, `oldPrice`) VALUES
	(98, 27, 4, NULL, 'add', 25),
	(99, 27, 1, NULL, 'add', 15),
	(100, 27, 5, NULL, 'add', 20),
	(101, 27, 5, NULL, 'add', 20),
	(102, 27, 6, NULL, 'add', 10),
	(103, 31, 10, NULL, 'add', 100),
	(104, 32, 10, NULL, 'add', 100),
	(105, 33, 10, NULL, 'add', 100);

-- Volcando estructura para tabla destiny_airlines.users
CREATE TABLE IF NOT EXISTS `users` (
  `id_USERS` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(3) DEFAULT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `townCity` varchar(50) NOT NULL,
  `streetAddress` varchar(50) NOT NULL,
  `zipCode` varchar(5) NOT NULL,
  `country` varchar(25) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `passwordHash` varchar(100) NOT NULL,
  `phoneNumber1` varchar(20) NOT NULL,
  `phoneNumber2` varchar(20) DEFAULT NULL,
  `phoneNumber3` varchar(20) DEFAULT NULL,
  `companyName` varchar(50) DEFAULT NULL,
  `companyTaxNumber` varchar(50) DEFAULT NULL,
  `companyPhoneNumber` varchar(20) DEFAULT NULL,
  `currentLoginAttempts` tinyint(2) NOT NULL DEFAULT 0,
  `lastAttempt` datetime DEFAULT NULL,
  `lastForgotPasswordEmail` datetime DEFAULT NULL,
  `documentationType` varchar(50) NOT NULL DEFAULT '',
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date NOT NULL,
  `dateBirth` date NOT NULL,
  `isEmailVerified` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_USERS`),
  UNIQUE KEY `emailAddress` (`emailAddress`)
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.users: ~5 rows (aproximadamente)
INSERT INTO `users` (`id_USERS`, `title`, `firstName`, `lastName`, `townCity`, `streetAddress`, `zipCode`, `country`, `emailAddress`, `passwordHash`, `phoneNumber1`, `phoneNumber2`, `phoneNumber3`, `companyName`, `companyTaxNumber`, `companyPhoneNumber`, `currentLoginAttempts`, `lastAttempt`, `lastForgotPasswordEmail`, `documentationType`, `documentCode`, `expirationDate`, `dateBirth`, `isEmailVerified`) VALUES
	(1, 'Mr', 'John', 'Doe', 'New York', '123 Main St', '11113', 'USA', 'john.doe@example.com', '', '123456789', '987654321', '123456', '', '', '', 0, NULL, NULL, 'DNI', '', '2049-12-14', '2008-12-14', 0),
	(2, 'Ms', 'Alice', 'Smith', 'London', '456 Elm St', 'SW1A ', 'UK', 'alice.smith@example.com', '', '111222333', '', '', 'XYZ Company', '12345678', '999888777', 0, NULL, NULL, 'DNI', '', '2038-11-17', '2009-11-17', 0),
	(96, 'Dr', 'Michael', 'Anderson', 'Sydney', '456 George St', '2000', 'Australia', 'michael.anderson@example.com', '', '987654321', '987123654', '789456123', 'Tech Corp', 'AUS098765432', '+61 987 654 321', 0, NULL, NULL, 'DNI', '', '2025-08-17', '1997-08-17', 0),
	(131, NULL, 'A555', '', '', '', '25', '', 'aaaa5@example.com', '$2y$10$.EG9ubn2GmCllCHzXNdCau/4Tc9HQC8dugE8TjilPfmcHsuPx0yT2', '', NULL, NULL, NULL, NULL, NULL, 1, '2024-01-23 16:27:47', NULL, 'DNI', '', '2028-08-05', '2002-08-05', 0),
	(187, NULL, 'Sergio', 'Corredor', 'Moscú', 'calle falsa 123', '266', 'Afghanistan', 'sergiodesarrolladorweb@gmail.com', '$2y$10$lME/elvOAQFK2mt7xbssI.fKdhcQ208kFWViIhOZmO/MJTxd2Yhoi', '1111111111', '22222222222', NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', NULL, 'dni', '12345678A', '2028-11-16', '1981-01-22', 1);

-- Volcando estructura para tabla destiny_airlines.user_temp_ids
CREATE TABLE IF NOT EXISTS `user_temp_ids` (
  `id_USER_TEMP_IDS` int(11) NOT NULL AUTO_INCREMENT,
  `id_USERS` int(11) NOT NULL,
  `tempId` varchar(40) NOT NULL,
  `unlockEmailPending` datetime DEFAULT current_timestamp(),
  `recordCause` varchar(50) NOT NULL,
  PRIMARY KEY (`id_USER_TEMP_IDS`),
  UNIQUE KEY `id_USERS` (`id_USERS`),
  UNIQUE KEY `tempId` (`tempId`),
  CONSTRAINT `user_temp_ids_ibfk_1` FOREIGN KEY (`id_USERS`) REFERENCES `users` (`id_USERS`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla destiny_airlines.user_temp_ids: ~0 rows (aproximadamente)

-- Volcando estructura para disparador destiny_airlines.update_seats
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `update_seats` BEFORE DELETE ON `books` FOR EACH ROW BEGIN
  DECLARE total INT;
  DECLARE flight_date DATE;
  SET total = OLD.adultsNumber + OLD.childsNumber;
  SELECT date INTO flight_date FROM flights WHERE id_FLIGHTS = OLD.id_FLIGHTS;
  IF flight_date > CURDATE() THEN
    UPDATE flights SET freeSeats = freeSeats + total WHERE id_FLIGHTS = OLD.id_FLIGHTS;
  END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
