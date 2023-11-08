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
  `firstName` varchar(50),
  `lastName` varchar(50),
  `country` varchar(25),
  `townCity` varchar(50),
  `streetAddress` varchar(50),
  `zipCode` varchar(5),
  `emailAddress` varchar(50) UNIQUE NOT NULL,
  `passwordHash` varchar(100) NOT NULL,
  `phoneNumber1` varchar(20),
  `phoneNumber2` varchar(20),
  `phoneNumber3` varchar(20),
  `companyName` varchar(50),
  `companyTaxNumber` varchar(50),
  `companyPhoneNumber` varchar(20),
  `currentLoginAttempts` tinyint NOT NULL DEFAULT 0,
  `lastAttempt` datetime
);

CREATE TABLE `USER_TEMP_IDS` (
  `id_USER_TEMP_IDS` int PRIMARY KEY AUTO_INCREMENT,
  `id_USERS` int NOT NULL,
  `tempId` varchar(40) UNIQUE NOT NULL,
  `unlockEmailPending` datetime DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `BOOKS` (
  `id_BOOKS` int PRIMARY KEY AUTO_INCREMENT,
  `id_FLIGHTS` int NOT NULL,
  `id_USERS` int NOT NULL,
  `id_PRIMARY_CONTACT_INFORMATIONS` int NOT NULL,
  `bookCode` varchar(10) UNIQUE NOT NULL,
  `price` double,
  `direction` ENUM ('departure', 'return') NOT NULL DEFAULT "departure",
  `checkinDate` date,
  `adultsNumber` tinyint DEFAULT 1,
  `childsNumber` tinyint DEFAULT 0,
  `infantsNumber` tinyint DEFAULT 0
);

CREATE TABLE `PRIMARY_CONTACT_INFORMATIONS` (
  `id_PRIMARY_CONTACT_INFORMATIONS` int PRIMARY KEY AUTO_INCREMENT,
  `documentationType` ENUM ('DNI', 'Passport', 'Drivers_license', 'Residence_card_or_work_permit') NOT NULL,
  `documentCode` varchar(30) NOT NULL,
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
  `id_BOOKS` int NOT NULL,
  `invoiceCode` varchar(50) UNIQUE NOT NULL,
  `invoicedDate` datetime DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `SERVICES` (
  `id_SERVICES` int PRIMARY KEY AUTO_INCREMENT,
  `serviceCode` varchar(10) UNIQUE NOT NULL,
  `serviceGroupingType` ENUM ('individual', 'collective', 'both') NOT NULL,
  `type` varchar(30) NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `billingCategory` ENUM ('PaidService', 'PercentageDiscount') NOT NULL,
  `priceOrDiscount` double NOT NULL,
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
  `id_SERVICES` int
);

CREATE TABLE `PASSENGERS` (
  `id_PASSENGERS` int PRIMARY KEY AUTO_INCREMENT,
  `passengerCode` varchar(50) UNIQUE NOT NULL,
  `documentationType` ENUM ('DNI', 'Passport', 'Drivers_license', 'Residence_card_or_work_permit') NOT NULL,
  `documentCode` varchar(30) NOT NULL,
  `expirationDate` date NOT NULL,
  `title` varchar(50),
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `ageCategory` ENUM ('infant', 'child', 'adult') NOT NULL DEFAULT "adult",
  `nationality` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `billed` date
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

ALTER TABLE `USER_TEMP_IDS` ADD FOREIGN KEY (`id_USERS`) REFERENCES `USERS` (`id_USERS`);

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_FLIGHTS`) REFERENCES `FLIGHTS` (`id_FLIGHTS`);

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_USERS`) REFERENCES `USERS` (`id_USERS`);

ALTER TABLE `BOOKS` ADD FOREIGN KEY (`id_PRIMARY_CONTACT_INFORMATIONS`) REFERENCES `PRIMARY_CONTACT_INFORMATIONS` (`id_PRIMARY_CONTACT_INFORMATIONS`);

ALTER TABLE `INVOICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`);

ALTER TABLE `BOOKS_SERVICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`);

ALTER TABLE `BOOKS_SERVICES` ADD FOREIGN KEY (`id_SERVICES`) REFERENCES `SERVICES` (`id_SERVICES`);

ALTER TABLE `PASSENGERS_BOOKS_SERVICES` ADD FOREIGN KEY (`id_PASSENGERS`) REFERENCES `PASSENGERS` (`id_PASSENGERS`);

ALTER TABLE `PASSENGERS_BOOKS_SERVICES` ADD FOREIGN KEY (`id_BOOKS`) REFERENCES `BOOKS` (`id_BOOKS`);

ALTER TABLE `PASSENGERS_BOOKS_SERVICES` ADD FOREIGN KEY (`id_SERVICES`) REFERENCES `SERVICES` (`id_SERVICES`);

ALTER TABLE `ADDITIONAL_INFORMATIONS` ADD FOREIGN KEY (`id_PASSENGERS`) REFERENCES `PASSENGERS` (`id_PASSENGERS`);
