-- --------------------------------------------------------
-- Hoszt:                        127.0.0.1
-- Szerver verzió:               11.6.0-MariaDB-log - mariadb.org binary distribution
-- Szerver OS:                   Win64
-- HeidiSQL Verzió:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Tábla adatainak mentése homeplus.property_types: ~11 rows (hozzávetőleg)
INSERT INTO `property_types` (`id`, `name`) VALUES
	(1, 'Ház'),
	(2, 'Lakás'),
	(3, 'Telek'),
	(4, 'Nyaraló'),
	(5, 'Tároló'),
	(6, 'Garázs/parkoló'),
	(7, 'Iroda/Üzlet'),
	(8, 'Kereskedelmi'),
	(9, 'Ipari'),
	(10, 'Mezőgazdasági'),
	(11, 'Vendéglátó');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
