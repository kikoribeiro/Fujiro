-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 16-Nov-2024 às 16:37
-- Versão do servidor: 8.3.0
-- versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `fujiro`
--

DELIMITER $$
--
-- Procedimentos
--
DROP PROCEDURE IF EXISTS `AddHotel`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddHotel` (IN `p_destination` VARCHAR(255), IN `p_name` VARCHAR(255), IN `p_address` VARCHAR(255), IN `p_city` VARCHAR(255), IN `p_price` DECIMAL(10,2), IN `p_evaluation` INT)   BEGIN
    INSERT INTO hotels (destination, name, address, city, price, evaluation)
    VALUES (p_destination, p_name, p_address, p_city, p_price, p_evaluation);
END$$

DROP PROCEDURE IF EXISTS `AddReservation`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddReservation` (IN `user_id` INT, IN `hotel_id` INT, IN `check_in` DATE, IN `check_out` DATE)   BEGIN
    -- Insert a new reservation record into the reservations table
    INSERT INTO reservations (user_id, hotel_id, check_in, check_out)
    VALUES (user_id, hotel_id, check_in, check_out);
END$$

DROP PROCEDURE IF EXISTS `AddUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddUser` (IN `username` VARCHAR(255), IN `email` VARCHAR(255), IN `password_hash` VARCHAR(255))   BEGIN
    -- Insert a new user record into the users table
    INSERT INTO users (username, email, password_hash)
    VALUES (username, email, password_hash);
END$$

DROP PROCEDURE IF EXISTS `AuthenticateUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AuthenticateUser` (IN `input_username` VARCHAR(255))   BEGIN
    -- Select the user's data based on the provided username
    SELECT user_id, username, email, password_hash, admin
    FROM users
    WHERE username = input_username;
END$$

DROP PROCEDURE IF EXISTS `DeleteHotelById`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteHotelById` (IN `p_hotel_id` INT)   BEGIN
    -- Delete the hotel from the hotels table
    DELETE FROM hotels WHERE hotel_id = p_hotel_id;
END$$

DROP PROCEDURE IF EXISTS `GetAllHotels`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllHotels` ()   BEGIN
    SELECT * FROM hotels;
END$$

DROP PROCEDURE IF EXISTS `GetAllReservations`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllReservations` ()   BEGIN
    SELECT * FROM reservations;
END$$

DROP PROCEDURE IF EXISTS `GetFavoriteHotels`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFavoriteHotels` (IN `userId` INT)   BEGIN
    SELECT hotels.hotel_id, hotels.name, hotels.destination, hotels.address, hotels.city, hotels.price, hotels.evaluation
    FROM hotels
    JOIN favorites ON hotels.hotel_id = favorites.hotel_id
    WHERE favorites.user_id = userId;
END$$

DROP PROCEDURE IF EXISTS `GetHotelDetails`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetHotelDetails` (IN `input_hotel_id` INT)   BEGIN
    -- Retrieve hotel details for the specified hotel ID
    SELECT * 
    FROM hotels 
    WHERE hotel_id = input_hotel_id;
END$$

DROP PROCEDURE IF EXISTS `GetHotelName`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetHotelName` (IN `p_hotel_id` INT)   BEGIN
    SELECT name FROM hotels WHERE hotel_id = p_hotel_id;
END$$

DROP PROCEDURE IF EXISTS `GetHotels`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetHotels` (IN `destination` VARCHAR(255))   BEGIN
    IF destination IS NULL OR destination = '' THEN
        SELECT * FROM hotels;
    ELSE
        SELECT * FROM hotels WHERE LOWER(destination) LIKE LOWER(CONCAT('%', destination, '%'));
    END IF;
END$$

DROP PROCEDURE IF EXISTS `RemoveReservation`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RemoveReservation` (IN `reservation_id` INT, IN `user_id` INT)   BEGIN
    -- Delete the reservation matching the reservation_id and user_id
    DELETE FROM reservations
    WHERE id = reservation_id AND user_id = user_id;
END$$

DROP PROCEDURE IF EXISTS `ToggleFavorite`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ToggleFavorite` (IN `user_id` INT, IN `hotel_id` INT)   BEGIN
    DECLARE favorite_exists INT;

    -- Check if the hotel is already marked as favorite by the user
    SELECT COUNT(*) INTO favorite_exists
    FROM favorites
    WHERE user_id = user_id AND hotel_id = hotel_id;

    IF favorite_exists = 0 THEN
        -- Add hotel to favorites if not already there
        INSERT INTO favorites (user_id, hotel_id) VALUES (user_id, hotel_id);
    ELSE
        -- Remove hotel from favorites if already there
        DELETE FROM favorites WHERE user_id = user_id AND hotel_id = hotel_id;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `ToggleHotelFavorite`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ToggleHotelFavorite` (IN `hotel_id` INT)   BEGIN
    -- Toggle the is_favorite column for the specified hotel
    UPDATE hotels
    SET is_favorite = NOT is_favorite
    WHERE hotel_id = hotel_id;
END$$

DROP PROCEDURE IF EXISTS `UpdateHotel`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateHotel` (IN `p_hotel_id` INT, IN `p_destination` VARCHAR(255), IN `p_name` VARCHAR(255), IN `p_address` VARCHAR(255), IN `p_city` VARCHAR(255), IN `p_price` DECIMAL(10,2), IN `p_evaluation` INT)   BEGIN
    UPDATE hotels
    SET destination = p_destination,
        name = p_name,
        address = p_address,
        city = p_city,
        price = p_price,
        evaluation = p_evaluation
    WHERE hotel_id = p_hotel_id;
END$$

DROP PROCEDURE IF EXISTS `UpdateReservation`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateReservation` (IN `reservation_id` INT, IN `new_check_in` DATE, IN `new_check_out` DATE)   BEGIN
    -- Update the reservation with the given check-in and check-out dates
    UPDATE reservations
    SET check_in = new_check_in,
        check_out = new_check_out
    WHERE id = reservation_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cancellations`
--

DROP TABLE IF EXISTS `cancellations`;
CREATE TABLE IF NOT EXISTS `cancellations` (
  `cancellation_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reservation_id` int UNSIGNED NOT NULL,
  `cancellation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `cancellation_fee` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`cancellation_id`),
  KEY `cancellations_ibfk_1` (`reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `cancellations`
--

INSERT INTO `cancellations` (`cancellation_id`, `reservation_id`, `cancellation_date`, `cancellation_reason`, `cancellation_fee`) VALUES
(1, 3, '2024-11-04 16:23:15', 'Personal reasons', 50.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` char(3) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `countries`
--

INSERT INTO `countries` (`country_id`, `name`, `code`) VALUES
(1, 'France', 'FRA'),
(2, 'United Kingdom', 'GBR'),
(3, 'United States', 'USA'),
(4, 'Japan', 'JPN'),
(5, 'United Arab Emirates', 'UAE'),
(6, 'Italy', 'ITA'),
(7, 'Australia', 'AUS'),
(8, 'South Africa', 'ZAF'),
(9, 'Brazil', 'BRA'),
(10, 'Spain', 'ESP');

-- --------------------------------------------------------

--
-- Estrutura da tabela `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `hotel_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_hotel` (`user_id`,`hotel_id`),
  KEY `hotel_id` (`hotel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `hotels`
--

DROP TABLE IF EXISTS `hotels`;
CREATE TABLE IF NOT EXISTS `hotels` (
  `hotel_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `destination` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country_id` int UNSIGNED DEFAULT NULL,
  `price` int NOT NULL,
  `evaluation` int NOT NULL,
  `is_favorite` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`hotel_id`),
  KEY `fk_hotels_country_id` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `destination`, `name`, `address`, `city`, `country_id`, `price`, `evaluation`, `is_favorite`) VALUES
(1, 'Paris', 'Hotel Ritz Paris', '15 Place Vendôme', 'Paris', 1, 450, 4, 0),
(2, 'London', 'The Ritz London', '150 Piccadilly', 'London', 2, 450, 5, 0),
(3, 'New York', 'The Plaza Hotel', 'Fifth Avenue at Central Park South', 'New York', 3, 600, 4, 0),
(4, 'Tokyo', 'Aman Tokyo', 'The Otemachi Tower, 1-5-6 Otemachi', 'Tokyo', NULL, 550, 5, 0),
(5, 'Dubai', 'Burj Al Arab Jumeirah', 'Jumeirah St - Umm Suqeim', 'Dubai', NULL, 700, 5, 0),
(6, 'Rome', 'Hotel Hassler Roma', 'Piazza Trinità dei Monti, 6', 'Rome', NULL, 550, 5, 0),
(7, 'Sydney', 'Park Hyatt Sydney', '7 Hickson Rd, The Rocks', 'Sydney', NULL, 600, 3, 0),
(8, 'Cape Town', 'The Silo Hotel', 'Silo Square, V&A Waterfront', 'Cape Town', NULL, 550, 5, 0),
(9, 'Rio de Janeiro', 'Belmond Copacabana Palace', 'Avenida Atlântica, 1702', 'Rio de Janeiro', NULL, 500, 4, 0),
(10, 'Barcelona', 'Hotel Arts Barcelona', 'Carrer de la Marina, 19-21', 'Barcelona', NULL, 600, 5, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `hotel_id` int UNSIGNED NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `hotel_id` (`hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `hotel_id`, `check_in`, `check_out`) VALUES
(1, 1, 1, '2024-01-19', '2024-01-27'),
(2, 1, 2, '2024-01-05', '2024-01-26'),
(3, 1, 4, '2024-01-18', '2024-01-27'),
(4, 1, 5, '2024-01-25', '2024-02-02'),
(5, 6, 4, '2024-01-17', '2024-01-29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `hotel_id` int UNSIGNED NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `availability` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`room_id`),
  KEY `rooms_ibfk_1` (`hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `rooms`
--

INSERT INTO `rooms` (`room_id`, `hotel_id`, `room_type`, `price_per_night`, `availability`) VALUES
(1, 1, 'Single', 300.00, 1),
(2, 1, 'Double', 450.00, 1),
(3, 2, 'Suite', 700.00, 1),
(4, 3, 'Single', 500.00, 1),
(5, 4, 'Suite', 550.00, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(128) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `admin`) VALUES
(1, 'Manel', 'manel@manel.pt', '$2y$10$6COuNxBb0tBnKFmw8HH5Ue/MHQG1LrL02wZr2NHsaVD.16cv6Ypja', 0),
(5, 'admin', 'admin@admin.pt', '$2y$10$Nt0Q1KMOfErXa3VmMS5tPuU/IiQLytxJMxvA1wOE4tfm7dJaV9PnG', 1),
(6, 'Antrob', 'antonio@antonio.pt', '$2y$10$ItQlLzsapwtfwXk44GhBDutg9/6pPLT8J0Lsbs10.g/Uq33au0OHi', 0),
(7, 'tiagovski', 'dfghdsgfdh@gsfhdghsgfs', '$2y$10$P3c44b1vB9Y4pezJP7hOROIcRCfszG//jjk31m827dihSFuwR6Wfy', 0);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cancellations`
--
ALTER TABLE `cancellations`
  ADD CONSTRAINT `cancellations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `fk_hotels_country_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`);

--
-- Limitadores para a tabela `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
