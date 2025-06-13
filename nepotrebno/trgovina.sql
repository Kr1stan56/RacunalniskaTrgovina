-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 13. jun 2025 ob 08.38
-- Različica strežnika: 10.4.32-MariaDB
-- Različica PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `trgovina`
--

-- --------------------------------------------------------

--
-- Struktura tabele `kategorije`
--

CREATE TABLE `kategorije` (
  `id_ka` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `opis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `kategorije`
--

INSERT INTO `kategorije` (`id_ka`, `ime`, `opis`) VALUES
(0, 'ssd', NULL),
(1, 'cables', 'Kabli in povezave'),
(2, 'case', 'Računalniška ohišja'),
(3, 'cpu', 'Procesorji'),
(4, 'gpu', 'Grafične kartice'),
(5, 'hdd', 'Trdi diski'),
(6, 'headset', 'Slušalke'),
(7, 'keyboard', 'Tipkovnice'),
(8, 'microphone', 'Mikrofoni'),
(9, 'monitor', 'Monitorji'),
(10, 'motherboard', 'Matične plošče'),
(11, 'mouse', 'Miške'),
(12, 'ram', 'Pomnilniki'),
(13, 'speakers', 'Zvočniki'),
(14, 'webcam', 'Spletne kamere');

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `kategorije`
--
ALTER TABLE `kategorije`
  ADD PRIMARY KEY (`id_ka`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
