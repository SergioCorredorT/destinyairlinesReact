-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.28-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para destiny_airlines
CREATE DATABASE IF NOT EXISTS `destiny_airlines` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `destiny_airlines`;

-- Volcando estructura para tabla destiny_airlines.additional_informations
CREATE TABLE IF NOT EXISTS `additional_informations` (
  `id_ADDITIONAL_INFORMATIONS` int(11) NOT NULL AUTO_INCREMENT,
  `id_PASSENGERS` int(11) NOT NULL,
  `dateBirth` date DEFAULT NULL,
  `assistiveDevices` enum('wheelchair','serviceAnimal','crutches','cane','other') DEFAULT NULL,
  `medicalEquipment` enum('oxygenTank','CPAPMachine','other') DEFAULT NULL,
  `mobilityLimitations` enum('difficultyWalking','difficultyClimbingStairs','other') DEFAULT NULL,
  `communicationNeeds` enum('signLanguageInterpreter','hearingAid','other') DEFAULT NULL,
  `medicationRequirements` enum('insulin','other') DEFAULT NULL,
  PRIMARY KEY (`id_ADDITIONAL_INFORMATIONS`),
  KEY `id_PASSENGERS` (`id_PASSENGERS`),
  CONSTRAINT `additional_informations_ibfk_1` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.airplanes
CREATE TABLE IF NOT EXISTS `airplanes` (
  `id_AIRPLANES` int(11) NOT NULL AUTO_INCREMENT,
  `airplaneCode` varchar(10) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `seats` int(4) NOT NULL,
  PRIMARY KEY (`id_AIRPLANES`),
  UNIQUE KEY `airplaneCode` (`airplaneCode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.books
CREATE TABLE IF NOT EXISTS `books` (
  `id_BOOKS` int(11) NOT NULL AUTO_INCREMENT,
  `id_FLIGHTS` int(11) NOT NULL,
  `id_USERS` int(11) NOT NULL,
  `id_PRIMARY_CONTACT_INFORMATIONS` int(11) NOT NULL,
  `bookCode` varchar(10) NOT NULL,
  `checkinDate` date DEFAULT NULL,
  `direction` enum('departure','return') NOT NULL DEFAULT 'departure',
  `adultsNumber` tinyint(3) DEFAULT 1,
  `childsNumber` tinyint(3) DEFAULT 0,
  `infantsNumber` tinyint(3) DEFAULT 0,
  PRIMARY KEY (`id_BOOKS`),
  UNIQUE KEY `bookCode` (`bookCode`),
  KEY `id_FLIGHTS` (`id_FLIGHTS`),
  KEY `id_USERS` (`id_USERS`),
  KEY `id_PRIMARY_CONTACT_INFORMATIONS` (`id_PRIMARY_CONTACT_INFORMATIONS`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`id_FLIGHTS`) REFERENCES `flights` (`id_FLIGHTS`),
  CONSTRAINT `books_ibfk_2` FOREIGN KEY (`id_USERS`) REFERENCES `users` (`id_USERS`),
  CONSTRAINT `books_ibfk_3` FOREIGN KEY (`id_PRIMARY_CONTACT_INFORMATIONS`) REFERENCES `primary_contact_informations` (`id_PRIMARY_CONTACT_INFORMATIONS`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.books_services
CREATE TABLE IF NOT EXISTS `books_services` (
  `id_BOOKS_SERVICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_BOOKS` int(11) NOT NULL,
  `id_SERVICES` int(11) NOT NULL,
  PRIMARY KEY (`id_BOOKS_SERVICES`),
  KEY `id_BOOKS` (`id_BOOKS`),
  KEY `id_SERVICES` (`id_SERVICES`),
  CONSTRAINT `books_services_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`),
  CONSTRAINT `books_services_ibfk_2` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.currencies
CREATE TABLE IF NOT EXISTS `currencies` (
  `id_CURRENCIES` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `idToEuro` double(10,3) DEFAULT NULL,
  `euroToId` double(10,3) DEFAULT NULL,
  PRIMARY KEY (`id_CURRENCIES`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.invoices
CREATE TABLE IF NOT EXISTS `invoices` (
  `id_INVOICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_BOOKS` int(11) NOT NULL,
  `invoiceCode` varchar(50) NOT NULL,
  `invoicedDate` datetime DEFAULT current_timestamp(),
  `price` double NOT NULL,
  `isPaid` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_INVOICES`),
  UNIQUE KEY `invoiceCode` (`invoiceCode`),
  KEY `id_BOOKS` (`id_BOOKS`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.passengers
CREATE TABLE IF NOT EXISTS `passengers` (
  `id_PASSENGERS` int(11) NOT NULL AUTO_INCREMENT,
  `id_BOOKS` int(11) NOT NULL,
  `passengerCode` varchar(50) NOT NULL,
  `documentationType` enum('DNI','Passport','Drivers_license','Residence_card_or_work_permit') NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date DEFAULT NULL,
  `nationality` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `ageCategory` enum('infant','child','adult') NOT NULL DEFAULT 'adult',
  PRIMARY KEY (`id_PASSENGERS`),
  UNIQUE KEY `passengerCode` (`passengerCode`),
  KEY `id_BOOKS` (`id_BOOKS`),
  CONSTRAINT `passengers_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.passengers_books_services
CREATE TABLE IF NOT EXISTS `passengers_books_services` (
  `id_PASSENGERS_SERVICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_PASSENGERS` int(11) NOT NULL,
  `id_BOOKS` int(11) NOT NULL,
  `id_SERVICES` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_PASSENGERS_SERVICES`),
  KEY `id_PASSENGERS` (`id_PASSENGERS`),
  KEY `id_BOOKS` (`id_BOOKS`),
  KEY `id_SERVICES` (`id_SERVICES`),
  CONSTRAINT `passengers_books_services_ibfk_1` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`),
  CONSTRAINT `passengers_books_services_ibfk_2` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`),
  CONSTRAINT `passengers_books_services_ibfk_3` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.primary_contact_informations
CREATE TABLE IF NOT EXISTS `primary_contact_informations` (
  `id_PRIMARY_CONTACT_INFORMATIONS` int(11) NOT NULL AUTO_INCREMENT,
  `documentationType` enum('DNI','Passport','Drivers_license','Residence_card_or_work_permit') NOT NULL,
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
  PRIMARY KEY (`id_PRIMARY_CONTACT_INFORMATIONS`),
  UNIQUE KEY `emailAddress` (`emailAddress`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.services_invoices
CREATE TABLE IF NOT EXISTS `services_invoices` (
  `id_SERVICES_INVOICES` int(11) NOT NULL AUTO_INCREMENT,
  `id_INVOICES` int(11) NOT NULL,
  `id_SERVICES` int(11) NOT NULL,
  `id_PASSENGERS` int(11) DEFAULT NULL,
  `addRemove` enum('add','remove') NOT NULL DEFAULT 'add',
  `oldPrice` double NOT NULL,
  PRIMARY KEY (`id_SERVICES_INVOICES`),
  KEY `id_INVOICES` (`id_INVOICES`),
  KEY `id_SERVICES` (`id_SERVICES`),
  KEY `id_PASSENGERS` (`id_PASSENGERS`),
  CONSTRAINT `services_invoices_ibfk_1` FOREIGN KEY (`id_INVOICES`) REFERENCES `invoices` (`id_INVOICES`),
  CONSTRAINT `services_invoices_ibfk_2` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`),
  CONSTRAINT `services_invoices_ibfk_3` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

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
  `documentationType` enum('DNI','Passport','Drivers_license','Residence_card_or_work_permit') NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date NOT NULL,
  PRIMARY KEY (`id_USERS`),
  UNIQUE KEY `emailAddress` (`emailAddress`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla destiny_airlines.user_temp_ids
CREATE TABLE IF NOT EXISTS `user_temp_ids` (
  `id_USER_TEMP_IDS` int(11) NOT NULL AUTO_INCREMENT,
  `id_USERS` int(11) NOT NULL,
  `tempId` varchar(40) NOT NULL,
  `unlockEmailPending` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_USER_TEMP_IDS`),
  UNIQUE KEY `id_USERS` (`id_USERS`),
  UNIQUE KEY `tempId` (`tempId`),
  CONSTRAINT `user_temp_ids_ibfk_1` FOREIGN KEY (`id_USERS`) REFERENCES `users` (`id_USERS`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
