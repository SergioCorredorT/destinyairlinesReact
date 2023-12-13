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

CREATE TABLE `ITINERARIES` (
  `id_ITINERARIES` int PRIMARY KEY AUTO_INCREMENT,
  `origin` int NOT NULL,
  `destiny` int NOT NULL,
  `routeCode` varchar(20) UNIQUE NOT NULL
);

CREATE TABLE `FLIGHTS` (
  `id_FLIGHTS` int PRIMARY KEY AUTO_INCREMENT,
  `id_AIRPLANES` int,
  `id_ITINERARIES` int NOT NULL,
  `flightCode` varchar(50) UNIQUE NOT NULL,
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
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `country` varchar(25) NOT NULL,
  `townCity` varchar(50) NOT NULL,
  `streetAddress` varchar(50) NOT NULL,
  `zipCode` varchar(5) NOT NULL,
  `emailAddress` varchar(50) UNIQUE NOT NULL,
  `passwordHash` varchar(100) NOT NULL,
  `phoneNumber1` varchar(20) NOT NULL,
  `phoneNumber2` varchar(20),
  `phoneNumber3` varchar(20),
  `companyName` varchar(50),
  `companyTaxNumber` varchar(50),
  `companyPhoneNumber` varchar(20),
  `currentLoginAttempts` tinyint NOT NULL DEFAULT 0,
  `lastAttempt` datetime,
  `documentationType` varchar(50) NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date NOT NULL
);

CREATE TABLE `USER_TEMP_IDS` (
  `id_USER_TEMP_IDS` int PRIMARY KEY AUTO_INCREMENT,
  `id_USERS` int NOT NULL,
  `tempId` varchar(40) UNIQUE NOT NULL,
  `unlockEmailPending` datetime DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `BOOKS` (
  `id_BOOKS` int PRIMARY KEY AUTO_INCREMENT,
  `id_FLIGHTS` int,
  `id_USERS` int,
  `id_PRIMARY_CONTACT_INFORMATIONS` int NOT NULL,
  `bookCode` varchar(10) UNIQUE NOT NULL,
  `direction` ENUM ('departure', 'return') NOT NULL DEFAULT "departure",
  `checkinDate` date,
  `adultsNumber` tinyint DEFAULT 1,
  `childsNumber` tinyint DEFAULT 0,
  `infantsNumber` tinyint DEFAULT 0
);

CREATE TABLE `PRIMARY_CONTACT_INFORMATIONS` (
  `id_PRIMARY_CONTACT_INFORMATIONS` int PRIMARY KEY AUTO_INCREMENT,
  `documentationType` varchar(50) NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date NOT NULL,
  `title` varchar(50),
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) UNIQUE NOT NULL,
  `phoneNumber1` varchar(20) NOT NULL,
  `phoneNumber2` varchar(20),
  `country` varchar(25) NOT NULL,
  `townCity` varchar(50) NOT NULL,
  `streetAddress` varchar(50) NOT NULL,
  `zipCode` varchar(5) NOT NULL,
  `companyName` varchar(50),
  `companyTaxNumber` varchar(50),
  `companyPhoneNumber` varchar(20)
);

CREATE TABLE `INVOICES` (
  `id_INVOICES` int PRIMARY KEY AUTO_INCREMENT,
  `id_BOOKS` int,
  `invoiceCode` varchar(50) UNIQUE NOT NULL,
  `invoicedDate` datetime DEFAULT (CURRENT_TIMESTAMP),
  `price` double NOT NULL,
  `isPaid` tinyint(1) NOT NULL DEFAULT 0
);

CREATE TABLE `SERVICES_INVOICES` (
  `id_SERVICES_INVOICES` int PRIMARY KEY AUTO_INCREMENT,
  `id_INVOICES` int NOT NULL,
  `id_SERVICES` int,
  `id_PASSENGERS` int,
  `addRemove` ENUM ('add', 'remove') NOT NULL DEFAULT "add",
  `oldPrice` double NOT NULL
);

CREATE TABLE `SERVICES` (
  `id_SERVICES` int PRIMARY KEY AUTO_INCREMENT,
  `serviceCode` varchar(10) UNIQUE NOT NULL,
  `serviceGroupingType` ENUM ('individual', 'collective', 'both') NOT NULL,
  `type` varchar(30) NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `billingCategory` ENUM ('PaidService', 'PercentageDiscount') NOT NULL,
  `priceOrDiscount` double NOT NULL,
  `status` enum NOT NULL DEFAULT "active",
  `description` varchar(600)
);

CREATE TABLE `BOOKS_SERVICES` (
  `id_BOOKS_SERVICES` int PRIMARY KEY AUTO_INCREMENT,
  `id_BOOKS` int NOT NULL,
  `id_SERVICES` int NOT NULL
);

CREATE TABLE `PASSENGERS_BOOKS_SERVICES` (
  `id_PASSENGERS_BOOKS_SERVICES` int PRIMARY KEY AUTO_INCREMENT,
  `id_PASSENGERS` int NOT NULL,
  `id_BOOKS` int NOT NULL,
  `id_SERVICES` int NOT NULL
);

CREATE TABLE `PASSENGERS` (
  `id_PASSENGERS` int PRIMARY KEY AUTO_INCREMENT,
  `id_BOOKS` int NOT NULL,
  `passengerCode` varchar(50) UNIQUE NOT NULL,
  `documentationType` varchar(50) NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date NOT NULL,
  `title` varchar(50),
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `ageCategory` ENUM ('infant', 'child', 'adult') NOT NULL DEFAULT "adult",
  `nationality` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL
);

CREATE TABLE `ADDITIONAL_INFORMATIONS` (
  `id_ADDITIONAL_INFORMATIONS` int PRIMARY KEY AUTO_INCREMENT,
  `id_PASSENGERS` int UNIQUE NOT NULL,
  `dateBirth` date,
  `assistiveDevices` ENUM ('wheelchair', 'serviceAnimal', 'crutches', 'cane', 'other'),
  `medicalEquipment` ENUM ('oxygenTank', 'CPAPMachine', 'other'),
  `mobilityLimitations` ENUM ('difficultyWalking', 'difficultyClimbingStairs', 'other'),
  `communicationNeeds` ENUM ('signLanguageInterpreter', 'hearingAid', 'other'),
  `medicationRequirements` ENUM ('insulin', 'other')
);

ALTER TABLE `AIRPORTS` ADD FOREIGN KEY (`id_CURRENCIES`) REFERENCES `CURRENCIES` (`id_CURRENCIES`);

ALTER TABLE `ITINERARIES` ADD FOREIGN KEY (`origin`) REFERENCES `AIRPORTS` (`id_AIRPORTS`);

ALTER TABLE `ITINERARIES` ADD FOREIGN KEY (`destiny`) REFERENCES `AIRPORTS` (`id_AIRPORTS`);

ALTER TABLE `FLIGHTS` ADD FOREIGN KEY (`id_AIRPLANES`) REFERENCES `AIRPLANES` (`id_AIRPLANES`);

ALTER TABLE `FLIGHTS` ADD FOREIGN KEY (`id_ITINERARIES`) REFERENCES `ITINERARIES` (`id_ITINERARIES`);

ALTER TABLE `USER_TEMP_IDS` ADD FOREIGN KEY (`id_USERS`) REFERENCES `USERS` (`id_USERS`) ON DELETE CASCADE;

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_FLIGHTS`) REFERENCES `FLIGHTS` (`id_FLIGHTS`) ON DELETE SET NULL;

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_USERS`) REFERENCES `USERS` (`id_USERS`) ON DELETE SET NULL;

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_PRIMARY_CONTACT_INFORMATIONS`) REFERENCES `PRIMARY_CONTACT_INFORMATIONS` (`id_PRIMARY_CONTACT_INFORMATIONS`);

ALTER TABLE `INVOICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`) ON DELETE SET NULL;

ALTER TABLE `SERVICES_INVOICES` ADD FOREIGN KEY (`id_INVOICES`) REFERENCES `INVOICES` (`id_INVOICES`) ON DELETE CASCADE;

ALTER TABLE `SERVICES_INVOICES` ADD FOREIGN KEY (`id_SERVICES`) REFERENCES `SERVICES` (`id_SERVICES`) ON DELETE SET NULL;

ALTER TABLE `SERVICES_INVOICES` ADD FOREIGN KEY (`id_PASSENGERS`) REFERENCES `PASSENGERS` (`id_PASSENGERS`) ON DELETE SET NULL;

ALTER TABLE `BOOKS_SERVICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`) ON DELETE CASCADE;

ALTER TABLE `BOOKS_SERVICES` ADD FOREIGN KEY (`id_SERVICES`) REFERENCES `SERVICES` (`id_SERVICES`) ON DELETE CASCADE;

ALTER TABLE `PASSENGERS_BOOKS_SERVICES` ADD FOREIGN KEY (`id_PASSENGERS`) REFERENCES `PASSENGERS` (`id_PASSENGERS`) ON DELETE CASCADE;

ALTER TABLE `PASSENGERS_BOOKS_SERVICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`) ON DELETE CASCADE;

ALTER TABLE `PASSENGERS_BOOKS_SERVICES` ADD FOREIGN KEY (`id_SERVICES`) REFERENCES `SERVICES` (`id_SERVICES`) ON DELETE CASCADE;

ALTER TABLE `PASSENGERS` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`) ON DELETE CASCADE;

ALTER TABLE `ADDITIONAL_INFORMATIONS` ADD FOREIGN KEY (`id_PASSENGERS`) REFERENCES `PASSENGERS` (`id_PASSENGERS`) ON DELETE CASCADE;
