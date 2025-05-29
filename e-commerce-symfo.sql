-- phpMyAdmin SQL Dump adapté pour e-commerce d'appareils électroniques
-- Base de données : `ecommerce`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table `user`
-- --------------------------------------------------------
CREATE TABLE `user` (
                        `id` INT NOT NULL,
                        `email` VARCHAR(180) NOT NULL,
                        `roles` JSON NOT NULL,
                        `password` VARCHAR(255) NOT NULL,
                        `firstname` VARCHAR(255) NOT NULL,
                        `lastname` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user` (`id`,`email`,`roles`,`password`,`firstname`,`lastname`) VALUES
(1,'admin@electronique.com','["ROLE_ADMIN"]','$2y$13$…','Admin','Electro'),
(2,'user1@example.com','[]','$2y$13$…','Alice','Durand'),
(3,'user2@example.com','[]','$2y$13$…','Bob','Martin');

-- --------------------------------------------------------
-- Table `address`
-- --------------------------------------------------------
CREATE TABLE `address` (
                           `id` INT NOT NULL,
                           `user_id` INT NOT NULL,
                           `name` VARCHAR(255) NOT NULL,
                           `firstname` VARCHAR(255) NOT NULL,
                           `lastname` VARCHAR(255) NOT NULL,
                           `company` VARCHAR(255) DEFAULT NULL,
                           `address` VARCHAR(255) NOT NULL,
                           `postal` VARCHAR(255) NOT NULL,
                           `city` VARCHAR(255) NOT NULL,
                           `country` VARCHAR(255) NOT NULL,
                           `phone` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `address` (`id`,`user_id`,`name`,`firstname`,`lastname`,`company`,`address`,`postal`,`city`,`country`,`phone`) VALUES
(1,2,'Livraison principale','Alice','Durand',NULL,'123 rue de l’Électronique','H2X1Y7','Montréal','CA','+1 514-123-4567');

-- --------------------------------------------------------
-- Table `category`
-- --------------------------------------------------------
CREATE TABLE `category` (
                            `id` INT NOT NULL,
                            `name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `category` (`id`,`name`) VALUES
(1,'Écrans'),
(2,'Ordinateurs portables'),
(3,'Claviers'),
(4,'Souris'),
(5,'Disques durs SSD'),
(6,'Disques durs HDD');

-- --------------------------------------------------------
-- Table `product`
-- --------------------------------------------------------
CREATE TABLE `product` (
                           `id` INT NOT NULL,
                           `category_id` INT NOT NULL,
                           `name` VARCHAR(255) NOT NULL,
                           `slug` VARCHAR(255) NOT NULL,
                           `image` VARCHAR(255) NOT NULL,
                           `subtitle` VARCHAR(255) NOT NULL,
                           `description` LONGTEXT NOT NULL,
                           `price` DOUBLE NOT NULL,
                           `is_in_home` TINYINT(1) NOT NULL,
                           `stock` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product`
(`id`,`category_id`,`name`,`slug`,`image`,`subtitle`,`description`,`price`,`is_in_home`,`stock`)
VALUES
(1,1,'Écran 24″ Full HD','ecran-24-fhd','ecran24fhd.jpg','24 pouces, 1080p','Écran LED 24″ 1080p avec angles de vue larges.',19999,1,30),
(2,2,'Portable 15″ i5 8Go','portable-15-i5','laptop15.jpg','Intel i5, 8 Go RAM','Ordinateur portable 15″, CPU i5, RAM 8 Go, SSD 256 Go.',79999,1,20),
(3,3,'Clavier mécanique RGB','clavier-meca-rgb','claviermeca.jpg','Switchs rouges, rétroéclairé','Clavier mécanique avec éclairage RGB personnalisable.',4999,0,50),
(4,4,'Souris sans fil','souris-wireless','souriswireless.jpg','Optique 1600 DPI','Souris ergonomique sans fil, capteur optique 1600 DPI.',2999,0,50),
(5,5,'SSD 500 Go','ssd-500','ssd500.jpg','500 Go, SATA III','Disque SSD 500 Go, lecture 550 Mo/s, écriture 500 Mo/s.',10999,0,40),
(6,6,'HDD 1 To','hdd-1to','hdd1to.jpg','1 To, 7200 RPM','Disque dur 1 To, 7200 RPM, interface SATA.',7999,0,40);

-- --------------------------------------------------------
-- Table `order`
-- --------------------------------------------------------
CREATE TABLE `order` (
                         `id` INT NOT NULL,
                         `user_id` INT NOT NULL,
                         `created_at` DATETIME NOT NULL,
                         `reference` VARCHAR(255) NOT NULL,
                         `stripe_session` VARCHAR(255) DEFAULT NULL,
                         `state` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- (pas d’insert de commandes initiales)

-- --------------------------------------------------------
-- Table `order_details`
-- --------------------------------------------------------
CREATE TABLE `order_details` (
                                 `id` INT NOT NULL,
                                 `binded_order_id` INT NOT NULL,
                                 `product` VARCHAR(255) NOT NULL,
                                 `quantity` INT NOT NULL,
                                 `price` DOUBLE NOT NULL,
                                 `total` DOUBLE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- (pas d’insert initial)

-- --------------------------------------------------------
-- Index & clés étrangères
-- --------------------------------------------------------
ALTER TABLE `user`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_USER_EMAIL` (`email`);
ALTER TABLE `address`
    ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Address_User` (`user_id`);
ALTER TABLE `category` ADD PRIMARY KEY (`id`);
ALTER TABLE `product`
    ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Product_Category` (`category_id`);
ALTER TABLE `order`
    ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Order_User` (`user_id`);
ALTER TABLE `order_details`
    ADD PRIMARY KEY (`id`),
  ADD KEY `FK_OrderDetails_Order` (`binded_order_id`);

ALTER TABLE `user`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `address`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `category`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `product`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `order`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `order_details`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `address`
    ADD CONSTRAINT `FK_Address_User` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`);
ALTER TABLE `product`
    ADD CONSTRAINT `FK_Product_Category` FOREIGN KEY (`category_id`) REFERENCES `category`(`id`);
ALTER TABLE `order`
    ADD CONSTRAINT `FK_Order_User` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`);
ALTER TABLE `order_details`
    ADD CONSTRAINT `FK_OrderDetails_Order` FOREIGN KEY (`binded_order_id`) REFERENCES `order`(`id`);

COMMIT;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
