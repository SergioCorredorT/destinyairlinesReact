-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-11-2023 a las 21:42:55
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE `books` (
  `id_BOOKS` int(11) NOT NULL,
  `id_FLIGHTS` int(11) NOT NULL,
  `id_USERS` int(11) NOT NULL,
  `bookCode` varchar(10) NOT NULL,
  `checkinDate` date DEFAULT NULL,
  `price` double DEFAULT NULL,
  `direction` enum('departure','return') NOT NULL DEFAULT 'departure',
  `invoiced` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books_services`
--

CREATE TABLE `books_services` (
  `id_BOOKS_SERVICES` int(11) NOT NULL,
  `id_BOOKS` int(11) NOT NULL,
  `id_SERVICES` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flights_itineraries`
--

CREATE TABLE `flights_itineraries` (
  `id_ITINERARIES_FLIGHTS` int(11) NOT NULL,
  `id_FLIGHTS` int(11) NOT NULL,
  `id_ITINERARIES` int(11) NOT NULL
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
  `lastAttempt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `id_USERS` (`id_USERS`);

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
  MODIFY `id_ADDITIONAL_INFORMATIONS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `airplanes`
--
ALTER TABLE `airplanes`
  MODIFY `id_AIRPLANES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `airports`
--
ALTER TABLE `airports`
  MODIFY `id_AIRPORTS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `books`
--
ALTER TABLE `books`
  MODIFY `id_BOOKS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `books_services`
--
ALTER TABLE `books_services`
  MODIFY `id_BOOKS_SERVICES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id_CURRENCIES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `flights`
--
ALTER TABLE `flights`
  MODIFY `id_FLIGHTS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `flights_itineraries`
--
ALTER TABLE `flights_itineraries`
  MODIFY `id_ITINERARIES_FLIGHTS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itineraries`
--
ALTER TABLE `itineraries`
  MODIFY `id_ITINERARIES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `passengers`
--
ALTER TABLE `passengers`
  MODIFY `id_PASSENGERS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `passengers_books_services`
--
ALTER TABLE `passengers_books_services`
  MODIFY `id_PASSENGERS_SERVICES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id_SERVICES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_USERS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user_temp_ids`
--
ALTER TABLE `user_temp_ids`
  MODIFY `id_USER_TEMP_IDS` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`id_USERS`) REFERENCES `users` (`id_USERS`);

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
