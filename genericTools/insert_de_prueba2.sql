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

-- Volcando datos para la tabla destiny_airlines.additional_informations: ~6 rows (aproximadamente)
DELETE FROM `additional_informations`;
INSERT INTO `additional_informations` (`id_ADDITIONAL_INFORMATIONS`, `id_PASSENGERS`, `dateBirth`, `assistiveDevices`, `medicalEquipment`, `mobilityLimitations`, `communicationNeeds`, `medicationRequirements`) VALUES
	(1, 1, '1985-05-15', NULL, NULL, NULL, NULL, NULL),
	(2, 2, '1990-08-20', NULL, NULL, NULL, NULL, NULL),
	(3, 3, '2010-03-02', NULL, NULL, NULL, NULL, NULL),
	(4, 4, '1982-11-10', NULL, NULL, NULL, NULL, NULL),
	(37, 39, '2013-11-01', 'cane', '', '', '', ''),
	(38, 40, '2013-11-01', 'cane', '', '', '', '');

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
DELETE FROM `airplanes`;
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
DELETE FROM `airports`;
INSERT INTO `airports` (`id_AIRPORTS`, `id_CURRENCIES`, `IATA`, `name`) VALUES
	(1, 1, 'JFK', 'John F. Kennedy International Airport'),
	(2, 2, 'LHR', 'Heathrow Airport'),
	(3, 3, 'CDG', 'Charles de Gaulle Airport'),
	(4, 4, 'PEK', 'Beijing Capital International Airport');

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

-- Volcando datos para la tabla destiny_airlines.books: ~5 rows (aproximadamente)
DELETE FROM `books`;
INSERT INTO `books` (`id_BOOKS`, `id_FLIGHTS`, `id_USERS`, `id_PRIMARY_CONTACT_INFORMATIONS`, `bookCode`, `checkinDate`, `direction`, `adultsNumber`, `childsNumber`, `infantsNumber`) VALUES
	(25, 1, 1, 1, 'BK123', '2023-10-19', 'departure', 1, 0, 0),
	(26, 2, 2, 1, 'BK456', '2023-10-20', 'departure', 1, 0, 0),
	(27, 3, 3, 1, 'BK789', '2023-10-21', 'departure', 1, 0, 0),
	(28, 4, 4, 1, 'BK012', '2023-10-22', 'departure', 1, 0, 0),
	(29, 1, 138, 1, 'd5336a6d-5', NULL, 'departure', 2, 0, 0),
	(59, 1, 138, 36, '0a3719cc-5', NULL, 'departure', 2, 0, 0);

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

-- Volcando datos para la tabla destiny_airlines.books_services: ~5 rows (aproximadamente)
DELETE FROM `books_services`;
INSERT INTO `books_services` (`id_BOOKS_SERVICES`, `id_BOOKS`, `id_SERVICES`) VALUES
	(11, 1, 3),
	(12, 1, 3),
	(13, 2, 4),
	(14, 3, 3),
	(15, 4, 4),
	(59, 59, 4);

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
DELETE FROM `currencies`;
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
DELETE FROM `flights`;
INSERT INTO `flights` (`id_FLIGHTS`, `id_AIRPLANES`, `id_ITINERARIES`, `flightCode`, `date`, `hour`, `price`, `freeSeats`, `arrivalHour`) VALUES
	(1, 1, 1, '1231', '2024-10-20', '08:00:00', 350, 148, '10:30:00'),
	(2, 2, 1, '345345', '2023-10-21', '15:30:00', 420, 200, '18:00:00'),
	(3, 3, 2, '324', '2023-10-22', '10:45:00', 280, 100, '13:15:00'),
	(4, 4, 3, '3545', '2023-10-23', '19:20:00', 500, 180, '22:00:00');

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

-- Volcando datos para la tabla destiny_airlines.invoices: ~2 rows (aproximadamente)
DELETE FROM `invoices`;
INSERT INTO `invoices` (`id_INVOICES`, `id_BOOKS`, `invoiceCode`, `invoicedDate`, `price`, `isPaid`) VALUES
	(1, 28, 'weaewwea23231', '2023-11-05 23:03:14', 30, 0),
	(2, 28, 'ewqewqq2312', '2023-11-05 23:03:14', 40, 0),
	(27, 59, 'de672819-ca38-4930-83e2-d7d69b5cacf5', '2023-12-06 19:48:33', 790, 1);

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
DELETE FROM `itineraries`;
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

-- Volcando datos para la tabla destiny_airlines.passengers: ~6 rows (aproximadamente)
DELETE FROM `passengers`;
INSERT INTO `passengers` (`id_PASSENGERS`, `id_BOOKS`, `passengerCode`, `documentationType`, `documentCode`, `expirationDate`, `nationality`, `country`, `firstName`, `lastName`, `title`, `ageCategory`) VALUES
	(1, 25, 'P001', 'DNI', '12345678A', '2023-10-16', '', '', 'David', 'García', 'Mr', 'adult'),
	(2, 25, 'P002', 'Passport', 'AB123456', '2023-10-16', '', '', 'Laura', 'López', 'Ms', 'adult'),
	(3, 25, 'P003', 'DNI', '87654321B', '2023-10-16', '', '', 'Carlos', 'Martín', 'Mr', 'child'),
	(4, 25, 'P004', 'Passport', 'CD789012', '2023-10-16', '', '', 'Elena', 'Sánchez', 'Ms', 'adult'),
	(39, 59, 'd2e9c674-e3b5-4395-9e8d-179cb88f428c', 'DNI', '12345678A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult'),
	(40, 59, '1738ebec-1ff9-48f5-9812-e14901ee637a', 'DNI', '12345677A', '2024-12-01', 'Española', 'España', 'Sergio', 'Corredor', 'Mr', 'adult');

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

-- Volcando datos para la tabla destiny_airlines.passengers_books_services: ~8 rows (aproximadamente)
DELETE FROM `passengers_books_services`;
INSERT INTO `passengers_books_services` (`id_PASSENGERS_SERVICES`, `id_PASSENGERS`, `id_BOOKS`, `id_SERVICES`) VALUES
	(6, 1, 28, 1),
	(8, 2, 25, NULL),
	(9, 3, 26, 11),
	(10, 4, 27, NULL),
	(72, 39, 59, 1),
	(73, 39, 59, 5),
	(74, 40, 59, 5),
	(75, 40, 59, 6);

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

-- Volcando datos para la tabla destiny_airlines.primary_contact_informations: ~1 rows (aproximadamente)
DELETE FROM `primary_contact_informations`;
INSERT INTO `primary_contact_informations` (`id_PRIMARY_CONTACT_INFORMATIONS`, `documentationType`, `documentCode`, `expirationDate`, `title`, `firstName`, `lastName`, `emailAddress`, `phoneNumber1`, `phoneNumber2`, `country`, `townCity`, `streetAddress`, `zipCode`, `companyName`, `companyTaxNumber`, `companyPhoneNumber`) VALUES
	(1, 'DNI', '232332', '2026-11-20', NULL, 'Sergi', 'waa', 'aaa@gmail.com', '11111111', NULL, 'España', 'Albacete', 'Calle falsa 123', '0909', NULL, NULL, NULL),
	(36, 'DNI', '12345678A', '2024-12-01', 'Mr', 'Sergio', 'Corredor', 'sergiodesarrolladorweb@gmail.com', '123456789', '123456788', 'España', 'Albacete', 'calle falsa 123', '3127', 'Compañía turbia', NULL, NULL);

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
DELETE FROM `services`;
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

-- Volcando datos para la tabla destiny_airlines.services_invoices: ~5 rows (aproximadamente)
DELETE FROM `services_invoices`;
INSERT INTO `services_invoices` (`id_SERVICES_INVOICES`, `id_INVOICES`, `id_SERVICES`, `id_PASSENGERS`, `addRemove`, `oldPrice`) VALUES
	(98, 27, 4, NULL, 'add', 25),
	(99, 27, 1, 39, 'add', 15),
	(100, 27, 5, 39, 'add', 20),
	(101, 27, 5, 40, 'add', 20),
	(102, 27, 6, 40, 'add', 10);

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

-- Volcando datos para la tabla destiny_airlines.users: ~5 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id_USERS`, `title`, `firstName`, `lastName`, `townCity`, `streetAddress`, `zipCode`, `country`, `emailAddress`, `passwordHash`, `phoneNumber1`, `phoneNumber2`, `phoneNumber3`, `companyName`, `companyTaxNumber`, `companyPhoneNumber`, `currentLoginAttempts`, `lastAttempt`, `lastForgotPasswordEmail`, `documentationType`, `documentCode`, `expirationDate`) VALUES
	(1, 'Mr', 'John', 'Doe', 'New York', '123 Main St', '11113', 'USA', 'john.doe@example.com', '', '123456789', '987654321', '123456', '', '', '', 0, NULL, NULL, 'DNI', '', '0000-00-00'),
	(2, 'Ms', 'Alice', 'Smith', 'London', '456 Elm St', 'SW1A ', 'UK', 'alice.smith@example.com', '', '111222333', '', '', 'XYZ Company', '12345678', '999888777', 0, NULL, NULL, 'DNI', '', '0000-00-00'),
	(96, 'Dr', 'Michael', 'Anderson', 'Sydney', '456 George St', '2000', 'Australia', 'michael.anderson@example.com', '', '987654321', '987123654', '789456123', 'Tech Corp', 'AUS098765432', '+61 987 654 321', 0, NULL, NULL, 'DNI', '', '0000-00-00'),
	(131, NULL, 'A555', '', '', '', '25', '', 'aaaa5@example.com', '$2y$10$.EG9ubn2GmCllCHzXNdCau/4Tc9HQC8dugE8TjilPfmcHsuPx0yT2', '', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'DNI', '', '0000-00-00'),
	(138, NULL, 'Serg', '', '', '', '25', '', 'sergiodesarrolladorweb@gmail.com', '$2y$10$uiwtsHD/9wWlP0H5m1Y14eRtGJ8BaTAWiLcR96vgkzFd8kaORR8ra', '', NULL, NULL, NULL, NULL, NULL, 0, '2023-12-11 16:00:13', NULL, 'DNI', '', '0000-00-00');

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

-- Volcando datos para la tabla destiny_airlines.user_temp_ids: ~0 rows (aproximadamente)
DELETE FROM `user_temp_ids`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
