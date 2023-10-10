CREATE DATABASE IF NOT EXISTS destiny_airlines;
USE destiny_airlines;

CREATE TABLE `AIRPORTS` (
  `id_AIRPORTS` int PRIMARY KEY AUTO_INCREMENT,
  `id_CURRENCIES` int,
  `IATA` varchar(3) UNIQUE NOT NULL,
  `name` varchar(50) UNIQUE
);

CREATE TABLE `CURRENCIES` (
  `id_CURRENCIES` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(20) UNIQUE NOT NULL,
  `idToEuro` double(10,3),
  `euroToId` double(10,3)
);

CREATE TABLE `FLIGHTS_ITINERARIES` (
  `id_ITINERARIES_FLIGHTS` int PRIMARY KEY AUTO_INCREMENT,
  `id_FLIGHTS` int NOT NULL,
  `id_ITINERARIES` int NOT NULL
);

CREATE TABLE `ITINERARIES` (
  `id_ITINERARIES` int PRIMARY KEY AUTO_INCREMENT,
  `origin` int NOT NULL,
  `destiny` int NOT NULL,
  `routeCode` varchar(20) UNIQUE NOT NULL
);

CREATE TABLE `FLIGHTS` (
  `id_FLIGHTS` int PRIMARY KEY AUTO_INCREMENT,
  `id_AIRPLANES` int,
  `date` date,
  `hour` time,
  `price` double,
  `freeSeats` int,
  `arrivalHour` time
);

CREATE TABLE `AIRPLANES` (
  `id_AIRPLANES` int PRIMARY KEY AUTO_INCREMENT,
  `airplaneCode` varchar(10) UNIQUE NOT NULL,
  `model` varchar(50),
  `seats` int(4) NOT NULL
);

CREATE TABLE `USERS` (
  `id_USERS` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(3),
  `firstName` varchar(50),
  `lastName` varchar(50),
  `townCity` varchar(50),
  `streetAddress` varchar(50),
  `zipCode` varchar(5),
  `country` varchar(25),
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phoneNumber1` varchar(20),
  `phoneNumber2` varchar(20),
  `phoneNumber3` varchar(20),
  `companyName` varchar(50),
  `companyTaxNumber` varchar(50),
  `companyPhoneNumber` varchar(20)
);

CREATE TABLE `BOOKS` (
  `id_BOOKS` int PRIMARY KEY AUTO_INCREMENT,
  `id_FLIGHTS` int NOT NULL,
  `id_USERS` int NOT NULL,
  `bookCode` varchar(10) UNIQUE NOT NULL,
  `bookDate` date,
  `price` double,
  `direction` ENUM ('departure', 'return') NOT NULL DEFAULT "departure",
  `invoiced` date,
  `deadLine` date
);

CREATE TABLE `SERVICES` (
  `id_SERVICES` int PRIMARY KEY AUTO_INCREMENT,
  `serviceCode` varchar(10) UNIQUE NOT NULL,
  `serviceGroupingType` ENUM ('individual', 'colective', 'both') NOT NULL,
  `type` varchar(30) NOT NULL,
  `subtype` varchar(30) NOT NULL,
  `description` varchar(600),
  `price` double
);

CREATE TABLE `BOOKS_SERVICES` (
  `id_BOOKS_SERVICES` int PRIMARY KEY AUTO_INCREMENT,
  `id_BOOKS` int NOT NULL,
  `id_SERVICES` int NOT NULL
);

CREATE TABLE `PASSENGERS_SERVICES` (
  `id_PASSENGERS_SERVICES` int PRIMARY KEY AUTO_INCREMENT,
  `id_PASSENGERS` int NOT NULL,
  `id_BOOKS` int NOT NULL,
  `id_SERVICES` int NOT NULL
);

CREATE TABLE `PASSENGERS` (
  `id_PASSENGERS` int PRIMARY KEY AUTO_INCREMENT,
  `passengerCode` varchar(50) UNIQUE NOT NULL,
  `documentationType` ENUM ('DNI', 'Passport', 'Drivers_license', 'Residence_card_or_work_permit') NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `firstName` varchar(50),
  `lastName` varchar(50),
  `title` varchar(50),
  `ageCategory` ENUM ('infant', 'child', 'adult') NOT NULL DEFAULT "adult",
  `billed` date
);

CREATE TABLE `ADDITIONAL_INFORMATIONS` (
  `id_ADDITIONAL_INFORMATIONS` int PRIMARY KEY AUTO_INCREMENT,
  `id_PASSENGERS` int NOT NULL,
  `nationality` varchar(50),
  `dateBirth` date,
  `country` varchar(50),
  `expirationDate` date
);

ALTER TABLE `AIRPORTS` ADD FOREIGN KEY (`id_CURRENCIES`) REFERENCES `CURRENCIES` (`id_CURRENCIES`);

ALTER TABLE `FLIGHTS_ITINERARIES` ADD FOREIGN KEY (`id_FLIGHTS`) REFERENCES `FLIGHTS` (`id_FLIGHTS`);

ALTER TABLE `FLIGHTS_ITINERARIES` ADD FOREIGN KEY (`id_ITINERARIES`) REFERENCES `ITINERARIES` (`id_ITINERARIES`);

ALTER TABLE `ITINERARIES` ADD FOREIGN KEY (`origin`) REFERENCES `AIRPORTS` (`id_AIRPORTS`);

ALTER TABLE `ITINERARIES` ADD FOREIGN KEY (`destiny`) REFERENCES `AIRPORTS` (`id_AIRPORTS`);

ALTER TABLE `FLIGHTS` ADD FOREIGN KEY (`id_AIRPLANES`) REFERENCES `AIRPLANES` (`id_AIRPLANES`);

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_FLIGHTS`) REFERENCES `FLIGHTS` (`id_FLIGHTS`);

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_USERS`) REFERENCES `USERS` (`id_USERS`);

ALTER TABLE `BOOKS_SERVICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`);

ALTER TABLE `BOOKS_SERVICES` ADD FOREIGN KEY (`id_SERVICES`) REFERENCES `SERVICES` (`id_SERVICES`);

ALTER TABLE `PASSENGERS_SERVICES` ADD FOREIGN KEY (`id_PASSENGERS`) REFERENCES `PASSENGERS` (`id_PASSENGERS`);

ALTER TABLE `PASSENGERS_SERVICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`);

ALTER TABLE `PASSENGERS_SERVICES` ADD FOREIGN KEY (`id_SERVICES`) REFERENCES `SERVICES` (`id_SERVICES`);

ALTER TABLE `ADDITIONAL_INFORMATIONS` ADD FOREIGN KEY (`id_PASSENGERS`) REFERENCES `PASSENGERS` (`id_PASSENGERS`);
