-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2023 a las 22:43:39
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `destiny_airlines`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `additional_informations`
--

CREATE TABLE `additional_informations` (
  `id_ADDITIONAL_INFORMATIONS` int(11) NOT NULL,
  `id_PASSENGERS` int(11) NOT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `dateBirth` date DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `expirationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `additional_informations`
--

INSERT INTO `additional_informations` (`id_ADDITIONAL_INFORMATIONS`, `id_PASSENGERS`, `nationality`, `dateBirth`, `country`, `expirationDate`) VALUES
(1, 1, 'Spanish', '1985-05-15', 'Spain', '2023-10-16'),
(2, 2, 'British', '1990-08-20', 'United Kingdom', '2023-10-16'),
(3, 3, 'Spanish', '2010-03-02', 'Spain', '2023-10-16'),
(4, 4, 'French', '1982-11-10', 'France', '2023-10-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `airplanes`
--

CREATE TABLE `airplanes` (
  `id_AIRPLANES` int(11) NOT NULL,
  `airplaneCode` varchar(10) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `seats` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `airplanes`
--

INSERT INTO `airplanes` (`id_AIRPLANES`, `airplaneCode`, `model`, `seats`) VALUES
(1, 'A123', 'Boeing 737', 180),
(2, 'B456', 'Airbus A320', 200),
(3, 'C789', 'Boeing 777', 280),
(4, 'D012', 'Airbus A350', 250);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `airports`
--

CREATE TABLE `airports` (
  `id_AIRPORTS` int(11) NOT NULL,
  `id_CURRENCIES` int(11) DEFAULT NULL,
  `IATA` varchar(3) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `airports`
--

INSERT INTO `airports` (`id_AIRPORTS`, `id_CURRENCIES`, `IATA`, `name`) VALUES
(1, 1, 'JFK', 'John F. Kennedy International Airport'),
(2, 2, 'LHR', 'Heathrow Airport'),
(3, 3, 'CDG', 'Charles de Gaulle Airport'),
(4, 4, 'PEK', 'Beijing Capital International Airport');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE `books` (
  `id_BOOKS` int(11) NOT NULL,
  `id_FLIGHTS` int(11) NOT NULL,
  `id_USERS` int(11) NOT NULL,
  `id_PRIMARY_CONTACT_INFORMATIONS` int(11) NOT NULL,
  `bookCode` varchar(10) NOT NULL,
  `checkinDate` date DEFAULT NULL,
  `price` double DEFAULT NULL,
  `direction` enum('departure','return') NOT NULL DEFAULT 'departure',
  `invoiced` datetime DEFAULT current_timestamp(),
  `adultsNumber` tinyint(3) DEFAULT 1,
  `childsNumber` tinyint(3) DEFAULT 0,
  `infantsNumber` tinyint(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `books`
--

INSERT INTO `books` (`id_BOOKS`, `id_FLIGHTS`, `id_USERS`, `id_PRIMARY_CONTACT_INFORMATIONS`, `bookCode`, `checkinDate`, `price`, `direction`, `invoiced`, `adultsNumber`, `childsNumber`, `infantsNumber`) VALUES
(25, 1, 1, 1, 'BK123', '2023-10-19', 350, 'departure', '2023-10-20 00:00:00', 1, 0, 0),
(26, 2, 2, 1, 'BK456', '2023-10-20', 420, 'departure', '2023-10-21 00:00:00', 1, 0, 0),
(27, 3, 3, 1, 'BK789', '2023-10-21', 280, 'departure', '2023-10-22 00:00:00', 1, 0, 0),
(28, 4, 4, 1, 'BK012', '2023-10-22', 500, 'departure', '2023-10-23 00:00:00', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books_services`
--

CREATE TABLE `books_services` (
  `id_BOOKS_SERVICES` int(11) NOT NULL,
  `id_BOOKS` int(11) NOT NULL,
  `id_SERVICES` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `books_services`
--

INSERT INTO `books_services` (`id_BOOKS_SERVICES`, `id_BOOKS`, `id_SERVICES`) VALUES
(11, 1, 3),
(12, 1, 3),
(13, 2, 4),
(14, 3, 3),
(15, 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currencies`
--

CREATE TABLE `currencies` (
  `id_CURRENCIES` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `idToEuro` double(10,3) DEFAULT NULL,
  `euroToId` double(10,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `currencies`
--

INSERT INTO `currencies` (`id_CURRENCIES`, `name`, `idToEuro`, `euroToId`) VALUES
(1, 'Euro', 1.000, 1.000),
(2, 'US Dollar', 0.850, 1.180),
(3, 'British Pound', 1.120, 0.890),
(4, 'Chinese Yuan', 0.120, 8.250);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flights`
--

CREATE TABLE `flights` (
  `id_FLIGHTS` int(11) NOT NULL,
  `id_AIRPLANES` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hour` time DEFAULT NULL,
  `price` double DEFAULT NULL,
  `freeSeats` int(11) DEFAULT NULL,
  `arrivalHour` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `flights`
--

INSERT INTO `flights` (`id_FLIGHTS`, `id_AIRPLANES`, `date`, `hour`, `price`, `freeSeats`, `arrivalHour`) VALUES
(1, 1, '2023-10-20', '08:00:00', 350, 150, '10:30:00'),
(2, 2, '2023-10-21', '15:30:00', 420, 200, '18:00:00'),
(3, 3, '2023-10-22', '10:45:00', 280, 100, '13:15:00'),
(4, 4, '2023-10-23', '19:20:00', 500, 180, '22:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flights_itineraries`
--

CREATE TABLE `flights_itineraries` (
  `id_ITINERARIES_FLIGHTS` int(11) NOT NULL,
  `id_FLIGHTS` int(11) NOT NULL,
  `id_ITINERARIES` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `flights_itineraries`
--

INSERT INTO `flights_itineraries` (`id_ITINERARIES_FLIGHTS`, `id_FLIGHTS`, `id_ITINERARIES`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 2),
(4, 4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id_INVOICES` int(11) NOT NULL,
  `id_BOOKS` int(11) NOT NULL,
  `invoiceCode` varchar(50) NOT NULL,
  `invoicedDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itineraries`
--

CREATE TABLE `itineraries` (
  `id_ITINERARIES` int(11) NOT NULL,
  `origin` int(11) NOT NULL,
  `destiny` int(11) NOT NULL,
  `routeCode` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `itineraries`
--

INSERT INTO `itineraries` (`id_ITINERARIES`, `origin`, `destiny`, `routeCode`) VALUES
(1, 1, 2, 'ABC123'),
(2, 2, 1, 'DEF456'),
(3, 3, 4, 'GHI789'),
(4, 4, 3, 'JKL012');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `passengers`
--

CREATE TABLE `passengers` (
  `id_PASSENGERS` int(11) NOT NULL,
  `passengerCode` varchar(50) NOT NULL,
  `documentationType` enum('DNI','Passport','Drivers_license','Residence_card_or_work_permit') NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `ageCategory` enum('infant','child','adult') NOT NULL DEFAULT 'adult',
  `billed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `passengers`
--

INSERT INTO `passengers` (`id_PASSENGERS`, `passengerCode`, `documentationType`, `documentCode`, `firstName`, `lastName`, `title`, `ageCategory`, `billed`) VALUES
(1, 'P001', 'DNI', '12345678A', 'David', 'García', 'Mr', 'adult', '2023-10-16'),
(2, 'P002', 'Passport', 'AB123456', 'Laura', 'López', 'Ms', 'adult', '2023-10-16'),
(3, 'P003', 'DNI', '87654321B', 'Carlos', 'Martín', 'Mr', 'child', '2023-10-16'),
(4, 'P004', 'Passport', 'CD789012', 'Elena', 'Sánchez', 'Ms', 'adult', '2023-10-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `passengers_books_services`
--

CREATE TABLE `passengers_books_services` (
  `id_PASSENGERS_SERVICES` int(11) NOT NULL,
  `id_PASSENGERS` int(11) NOT NULL,
  `id_BOOKS` int(11) NOT NULL,
  `id_SERVICES` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `passengers_books_services`
--

INSERT INTO `passengers_books_services` (`id_PASSENGERS_SERVICES`, `id_PASSENGERS`, `id_BOOKS`, `id_SERVICES`) VALUES
(6, 1, 28, 1),
(8, 2, 25, NULL),
(9, 3, 26, 2),
(10, 4, 27, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `primary_contact_informations`
--

CREATE TABLE `primary_contact_informations` (
  `id_PRIMARY_CONTACT_INFORMATIONS` int(11) NOT NULL,
  `documentationType` enum('DNI','Passport','Drivers_license','Residence_card_or_work_permit') NOT NULL,
  `documentCode` varchar(30) NOT NULL,
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
  `companyPhoneNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `primary_contact_informations`
--

INSERT INTO `primary_contact_informations` (`id_PRIMARY_CONTACT_INFORMATIONS`, `documentationType`, `documentCode`, `title`, `firstName`, `lastName`, `emailAddress`, `phoneNumber1`, `phoneNumber2`, `country`, `townCity`, `streetAddress`, `zipCode`, `companyName`, `companyTaxNumber`, `companyPhoneNumber`) VALUES
(1, 'DNI', '232332', NULL, 'Sergi', 'waa', 'aaa@gmail.com', '11111111', NULL, 'España', 'Albacete', 'Calle falsa 123', '0909', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id_SERVICES` int(11) NOT NULL,
  `serviceCode` varchar(10) NOT NULL,
  `serviceGroupingType` enum('individual','colective','both') NOT NULL,
  `type` varchar(30) NOT NULL,
  `subtype` varchar(30) NOT NULL,
  `description` varchar(600) DEFAULT NULL,
  `price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id_SERVICES`, `serviceCode`, `serviceGroupingType`, `type`, `subtype`, `description`, `price`) VALUES
(1, 'SRV001', 'individual', 'In-flight Meal', 'Regular', 'Standard meal during the flight', 15),
(2, 'SRV002', 'individual', 'Priority Boarding', 'Regular', 'Priority boarding for the flight', 20),
(3, 'SRV003', 'colective', 'Airport Transfer', 'Shuttle', 'Shuttle service to/from the airport', 30),
(4, 'SRV004', 'colective', 'Lounge Access', 'Regular', 'Access to airport lounge', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_USERS` int(11) NOT NULL,
  `title` varchar(3) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `townCity` varchar(50) DEFAULT NULL,
  `streetAddress` varchar(50) DEFAULT NULL,
  `zipCode` varchar(5) DEFAULT NULL,
  `country` varchar(25) DEFAULT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `passwordHash` varchar(100) NOT NULL,
  `phoneNumber1` varchar(20) DEFAULT NULL,
  `phoneNumber2` varchar(20) DEFAULT NULL,
  `phoneNumber3` varchar(20) DEFAULT NULL,
  `companyName` varchar(50) DEFAULT NULL,
  `companyTaxNumber` varchar(50) DEFAULT NULL,
  `companyPhoneNumber` varchar(20) DEFAULT NULL,
  `currentLoginAttempts` tinyint(2) NOT NULL DEFAULT 0,
  `lastAttempt` datetime DEFAULT NULL,
  `lastForgotPasswordEmail` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_USERS`, `title`, `firstName`, `lastName`, `townCity`, `streetAddress`, `zipCode`, `country`, `emailAddress`, `passwordHash`, `phoneNumber1`, `phoneNumber2`, `phoneNumber3`, `companyName`, `companyTaxNumber`, `companyPhoneNumber`, `currentLoginAttempts`, `lastAttempt`, `lastForgotPasswordEmail`) VALUES
(1, 'Mr', 'John', 'Doe', 'New York', '123 Main St', '11113', 'USA', 'john.doe@example.com', '', '123456789', '987654321', '123456', '', '', '', 0, NULL, NULL),
(2, 'Ms', 'Alice', 'Smith', 'London', '456 Elm St', 'SW1A ', 'UK', 'alice.smith@example.com', '', '111222333', '', '', 'XYZ Company', '12345678', '999888777', 0, NULL, NULL),
(96, 'Dr', 'Michael', 'Anderson', 'Sydney', '456 George St', '2000', 'Australia', 'michael.anderson@example.com', '', '987654321', '987123654', '789456123', 'Tech Corp', 'AUS098765432', '+61 987 654 321', 0, NULL, NULL),
(130, NULL, 'A5554', NULL, NULL, NULL, '29', NULL, 'sergiodesarrolladorweb@gmail.com', '$2y$10$3Pgu7t/t/zkiQjcz5o.wUe/Z8ZqoflTjyKikawD8Yezqqk6REdsZS', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2023-11-05 01:05:23', '2023-11-05 14:20:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_temp_ids`
--

CREATE TABLE `user_temp_ids` (
  `id_USER_TEMP_IDS` int(11) NOT NULL,
  `id_USERS` int(11) NOT NULL,
  `tempId` varchar(40) NOT NULL,
  `unlockEmailPending` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `additional_informations`
--
ALTER TABLE `additional_informations`
  ADD PRIMARY KEY (`id_ADDITIONAL_INFORMATIONS`),
  ADD KEY `id_PASSENGERS` (`id_PASSENGERS`);

--
-- Indices de la tabla `airplanes`
--
ALTER TABLE `airplanes`
  ADD PRIMARY KEY (`id_AIRPLANES`),
  ADD UNIQUE KEY `airplaneCode` (`airplaneCode`);

--
-- Indices de la tabla `airports`
--
ALTER TABLE `airports`
  ADD PRIMARY KEY (`id_AIRPORTS`),
  ADD UNIQUE KEY `IATA` (`IATA`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `id_CURRENCIES` (`id_CURRENCIES`);

--
-- Indices de la tabla `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id_BOOKS`),
  ADD UNIQUE KEY `bookCode` (`bookCode`),
  ADD KEY `id_FLIGHTS` (`id_FLIGHTS`),
  ADD KEY `id_USERS` (`id_USERS`),
  ADD KEY `id_PRIMARY_CONTACT_INFORMATIONS` (`id_PRIMARY_CONTACT_INFORMATIONS`);

--
-- Indices de la tabla `books_services`
--
ALTER TABLE `books_services`
  ADD PRIMARY KEY (`id_BOOKS_SERVICES`),
  ADD KEY `id_BOOKS` (`id_BOOKS`),
  ADD KEY `id_SERVICES` (`id_SERVICES`);

--
-- Indices de la tabla `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id_CURRENCIES`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`id_FLIGHTS`),
  ADD KEY `id_AIRPLANES` (`id_AIRPLANES`);

--
-- Indices de la tabla `flights_itineraries`
--
ALTER TABLE `flights_itineraries`
  ADD PRIMARY KEY (`id_ITINERARIES_FLIGHTS`),
  ADD KEY `id_FLIGHTS` (`id_FLIGHTS`),
  ADD KEY `id_ITINERARIES` (`id_ITINERARIES`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id_INVOICES`),
  ADD UNIQUE KEY `invoiceCode` (`invoiceCode`),
  ADD KEY `id_BOOKS` (`id_BOOKS`);

--
-- Indices de la tabla `itineraries`
--
ALTER TABLE `itineraries`
  ADD PRIMARY KEY (`id_ITINERARIES`),
  ADD UNIQUE KEY `routeCode` (`routeCode`),
  ADD KEY `origin` (`origin`),
  ADD KEY `destiny` (`destiny`);

--
-- Indices de la tabla `passengers`
--
ALTER TABLE `passengers`
  ADD PRIMARY KEY (`id_PASSENGERS`),
  ADD UNIQUE KEY `passengerCode` (`passengerCode`);

--
-- Indices de la tabla `passengers_books_services`
--
ALTER TABLE `passengers_books_services`
  ADD PRIMARY KEY (`id_PASSENGERS_SERVICES`),
  ADD KEY `id_PASSENGERS` (`id_PASSENGERS`),
  ADD KEY `id_BOOKS` (`id_BOOKS`),
  ADD KEY `id_SERVICES` (`id_SERVICES`);

--
-- Indices de la tabla `primary_contact_informations`
--
ALTER TABLE `primary_contact_informations`
  ADD PRIMARY KEY (`id_PRIMARY_CONTACT_INFORMATIONS`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_SERVICES`),
  ADD UNIQUE KEY `serviceCode` (`serviceCode`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_USERS`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indices de la tabla `user_temp_ids`
--
ALTER TABLE `user_temp_ids`
  ADD PRIMARY KEY (`id_USER_TEMP_IDS`),
  ADD UNIQUE KEY `id_USERS` (`id_USERS`),
  ADD UNIQUE KEY `tempId` (`tempId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `additional_informations`
--
ALTER TABLE `additional_informations`
  MODIFY `id_ADDITIONAL_INFORMATIONS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `airplanes`
--
ALTER TABLE `airplanes`
  MODIFY `id_AIRPLANES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `airports`
--
ALTER TABLE `airports`
  MODIFY `id_AIRPORTS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `books`
--
ALTER TABLE `books`
  MODIFY `id_BOOKS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `books_services`
--
ALTER TABLE `books_services`
  MODIFY `id_BOOKS_SERVICES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id_CURRENCIES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `flights`
--
ALTER TABLE `flights`
  MODIFY `id_FLIGHTS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `flights_itineraries`
--
ALTER TABLE `flights_itineraries`
  MODIFY `id_ITINERARIES_FLIGHTS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id_INVOICES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itineraries`
--
ALTER TABLE `itineraries`
  MODIFY `id_ITINERARIES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `passengers`
--
ALTER TABLE `passengers`
  MODIFY `id_PASSENGERS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `passengers_books_services`
--
ALTER TABLE `passengers_books_services`
  MODIFY `id_PASSENGERS_SERVICES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `primary_contact_informations`
--
ALTER TABLE `primary_contact_informations`
  MODIFY `id_PRIMARY_CONTACT_INFORMATIONS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id_SERVICES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_USERS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de la tabla `user_temp_ids`
--
ALTER TABLE `user_temp_ids`
  MODIFY `id_USER_TEMP_IDS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `additional_informations`
--
ALTER TABLE `additional_informations`
  ADD CONSTRAINT `additional_informations_ibfk_1` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`);

--
-- Filtros para la tabla `airports`
--
ALTER TABLE `airports`
  ADD CONSTRAINT `airports_ibfk_1` FOREIGN KEY (`id_CURRENCIES`) REFERENCES `currencies` (`id_CURRENCIES`);

--
-- Filtros para la tabla `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`id_FLIGHTS`) REFERENCES `flights` (`id_FLIGHTS`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`id_USERS`) REFERENCES `users` (`id_USERS`),
  ADD CONSTRAINT `books_ibfk_3` FOREIGN KEY (`id_PRIMARY_CONTACT_INFORMATIONS`) REFERENCES `primary_contact_informations` (`id_PRIMARY_CONTACT_INFORMATIONS`);

--
-- Filtros para la tabla `books_services`
--
ALTER TABLE `books_services`
  ADD CONSTRAINT `books_services_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`),
  ADD CONSTRAINT `books_services_ibfk_2` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`);

--
-- Filtros para la tabla `flights`
--
ALTER TABLE `flights`
  ADD CONSTRAINT `flights_ibfk_1` FOREIGN KEY (`id_AIRPLANES`) REFERENCES `airplanes` (`id_AIRPLANES`);

--
-- Filtros para la tabla `flights_itineraries`
--
ALTER TABLE `flights_itineraries`
  ADD CONSTRAINT `flights_itineraries_ibfk_1` FOREIGN KEY (`id_FLIGHTS`) REFERENCES `flights` (`id_FLIGHTS`),
  ADD CONSTRAINT `flights_itineraries_ibfk_2` FOREIGN KEY (`id_ITINERARIES`) REFERENCES `itineraries` (`id_ITINERARIES`);

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`);

--
-- Filtros para la tabla `itineraries`
--
ALTER TABLE `itineraries`
  ADD CONSTRAINT `itineraries_ibfk_1` FOREIGN KEY (`origin`) REFERENCES `airports` (`id_AIRPORTS`),
  ADD CONSTRAINT `itineraries_ibfk_2` FOREIGN KEY (`destiny`) REFERENCES `airports` (`id_AIRPORTS`);

--
-- Filtros para la tabla `passengers_books_services`
--
ALTER TABLE `passengers_books_services`
  ADD CONSTRAINT `passengers_books_services_ibfk_1` FOREIGN KEY (`id_PASSENGERS`) REFERENCES `passengers` (`id_PASSENGERS`),
  ADD CONSTRAINT `passengers_books_services_ibfk_2` FOREIGN KEY (`id_BOOKS`) REFERENCES `books` (`id_BOOKS`),
  ADD CONSTRAINT `passengers_books_services_ibfk_3` FOREIGN KEY (`id_SERVICES`) REFERENCES `services` (`id_SERVICES`);

--
-- Filtros para la tabla `user_temp_ids`
--
ALTER TABLE `user_temp_ids`
  ADD CONSTRAINT `user_temp_ids_ibfk_1` FOREIGN KEY (`id_USERS`) REFERENCES `users` (`id_USERS`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
