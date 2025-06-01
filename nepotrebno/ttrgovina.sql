-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 01. jun 2025 ob 13.28
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
-- Struktura tabele `izdelek`
--

CREATE TABLE `izdelek` (
  `slika` varchar(255) NOT NULL,
  `id_i` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `opis` text DEFAULT NULL,
  `cena` double NOT NULL,
  `zaloga` int(11) NOT NULL,
  `datum_dodajanja` date NOT NULL,
  `id_ka` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `izdelek`
--

INSERT INTO `izdelek` (`slika`, `id_i`, `ime`, `opis`, `cena`, `zaloga`, `datum_dodajanja`, `id_ka`) VALUES
('images/cables/1.jpeg', 174, 'Premium HDMI kabel', '2m dolžina, 4K podpora', 7.87, 50, '2025-04-04', 1),
('images/cables/prenos (1).jfif', 175, 'USB-C to USB-A adapter ', '3.1 Gen2, 10Gbps', 8.98, 761, '2025-04-04', 1),
('images/cables/001_1111-1_4.jpg.webp', 176, ' URGREEN Cat6 Ethernet kabel', 'Shielded, 5m dolžina', 12.5, 40, '2025-04-04', 1),
('images/cables/bb190663a4b7b0531c7006cb1ce0e47ab278e3ce6a0a06ee04_63bd50eacdc87.png', 177, 'DisplayPort kabel', '1.8m dolžina, 8K podpora', 18.99, 30, '2025-04-04', 1),
('images/cables/md818_5.jpeg', 178, 'Lightning to USB kabel', '1m dolžina, Apple cert.', 19.99, 60, '2025-04-04', 1),
('images/case/images.jfif', 179, 'NZXT H510 Mid Tower', 'ATX ohišje, kaljeno steklo', 79.99, 20, '2025-04-04', 2),
('', 180, 'Corsair 4000D Airflow', 'Visoka pretočnost zraka', 89.99, 15, '2025-04-04', 2),
('', 181, 'Lian Li PC-O11 Dynamic', 'RGB podpora, steklena stranica', 149.99, 10, '2025-04-04', 2),
('', 182, 'Fractal Design Meshify C', 'ATX, črna barva', 99.99, 12, '2025-04-04', 2),
('', 183, 'Cooler Master HAF 700', 'Veliko prostora za hlajenje', 249.99, 5, '2025-04-04', 2),
('', 184, 'Intel Core i9-13900K', '24 jedra, 32 niti, 5.8GHz', 589.99, 8, '2025-04-04', 3),
('images/cpu/e-507cbf075c16257b19eec8c121b9232c.webp', 185, 'AMD Ryzen 9 7900X', '12 jeder, 24 niti, 5.6GHz', 479.99, 10, '2025-04-04', 3),
('', 186, 'Intel Core i7-13700K', '16 jeder, 24 niti, 5.4GHz', 419.99, 15, '2025-04-04', 3),
('', 187, 'AMD Ryzen 7 7700X', '8 jeder, 16 niti, 5.4GHz', 349.99, 18, '2025-04-04', 3),
('', 188, 'Intel Core i5-13600K', '14 jeder, 20 niti, 5.1GHz', 319.99, 22, '2025-04-04', 3),
('', 189, 'NVIDIA RTX 4090', '24GB GDDR6X, 4K gaming', 1599.99, 4, '2025-04-04', 4),
('images/gpu/prenos.jfif', 190, 'AMD Radeon RX 7900 XTX', '24GB GDDR6, Ray Tracing', 999.99, 6, '2025-04-04', 4),
('', 191, 'NVIDIA RTX 4080', '16GB GDDR6X, DLSS 3', 1199.99, 8, '2025-04-04', 4),
('images/gpu/61dglyZM6+L._AC_UF894,1000_QL80_.jpg', 192, 'AMD Radeon RX 7800 XT WHITE', '16GB GDDR6, RDNA3\r\nWHITE', 699.99, 12, '2025-04-04', 4),
('', 193, 'NVIDIA RTX 4070 Ti', '12GB GDDR6X, AI podpora', 799.99, 10, '2025-04-04', 4),
('images/hdd/wd-blue-pc-desktop-hard-drive-1tb.png', 194, 'WD Blue 1TB HDD', '5400RPM, 256MB cache', 49.99, 30, '2025-04-04', 5),
('images/hdd/550.webp', 195, 'Seagate Barracuda 2TB', '7200RPM, 256MB cache', 69.99, 25, '2025-04-04', 5),
('images/hdd/prenos.jfif', 196, 'Toshiba X300 4TB', '7200RPM, 256MB cache', 119.99, 20, '2025-04-04', 5),
('images/hdd/WD-Black-3-5-HDD-left-8TB.png.thumb.1280.1280.png', 197, 'WD Black 8TB', '7200RPM, gaming optimiziran', 249.99, 10, '2025-04-04', 5),
('images/hdd/e-14acbfc03f84d3f92641b87433f39f55.webp', 198, 'Seagate IronWolf 10TB', 'NAS optimiziran, 7200RPM', 299.99, 8, '2025-04-04', 5),
('', 199, 'Logitech G Pro X', 'Gaming headset, BLUE VO!CE mikrofon', 129.99, 15, '2025-04-04', 6),
('', 200, 'Razer Kraken V3', 'THX Spatial Audio, 50mm gonilniki', 99.99, 20, '2025-04-04', 6),
('', 201, 'SteelSeries Arctis 7', 'Wireless, 24h baterija', 149.99, 12, '2025-04-04', 6),
('', 202, 'Corsair HS80 RGB Wireless', 'Dolga življenjska doba baterije, 7.1 surround', 179.99, 8, '2025-04-04', 6),
('', 203, 'HyperX Cloud II', 'Virtual 7.1, udobne blazinice', 99.99, 25, '2025-04-04', 6),
('', 204, 'Logitech G Pro X Keyboard', 'Mehanska tipkovnica, GX Blue Switches', 149.99, 30, '2025-04-04', 7),
('', 205, 'Corsair K95 RGB Platinum', '6 makro tipk, RGB osvetlitev', 199.99, 18, '2025-04-04', 7),
('images/keyboard/06972e0c211a4889b1b0e42fe758de309d591b4c2265be8e82_61dff7a9dce42.jpeg', 206, 'Razer Huntsman V2', 'Optični Switches, Razer RGB', 169.99, 12, '2025-04-04', 7),
('', 207, 'SteelSeries Apex Pro', 'Oftični stikala, nastavljiva trdnost tipk', 249.99, 10, '2025-04-04', 7),
('', 208, 'HyperX Alloy FPS Pro', 'Kompaktna, mehanska tipkovnica', 89.99, 22, '2025-04-04', 7),
('', 209, 'Blue Yeti X', 'Profesionalni USB mikrofon, 4 nastavitve', 199.99, 12, '2025-04-04', 8),
('', 210, 'Razer Seiren Elite', 'XLR mikrofon, visokokakovosten zvok', 249.99, 8, '2025-04-04', 8),
('', 211, 'HyperX QuadCast S', 'RGB osvetlitev, vgrajen anti-vibracijski nastavek', 139.99, 20, '2025-04-04', 8),
('', 212, 'Audio-Technica AT2020', 'XLR, profesionalni studio mikrofon', 99.99, 15, '2025-04-04', 8),
('', 213, 'Shure SM7B', 'Dinamični mikrofon, idealen za podcasting', 399.99, 5, '2025-04-04', 8),
('', 214, 'Samsung Odyssey G7', 'Curved, 240Hz, 1ms odzivni čas', 499.99, 10, '2025-04-04', 9),
('', 215, 'LG UltraGear 27GN950-B', '4K, Nano IPS, 144Hz', 799.99, 6, '2025-04-04', 9),
('', 216, 'ASUS ROG Swift PG259QN', '360Hz, 1ms, G-SYNC', 699.99, 8, '2025-04-04', 9),
('', 217, 'BenQ ZOWIE XL2546K', '240Hz, DyAc+ tehnologija', 499.99, 15, '2025-04-04', 9),
('', 218, 'Dell Alienware AW2521H', '360Hz, IPS, NVIDIA G-SYNC', 699.99, 5, '2025-04-04', 9),
('', 219, 'MSI MAG B550 TOMAHAWK', 'AM4, PCIe 4.0, DDR4 podporna', 139.99, 10, '2025-04-04', 10),
('', 220, 'ASUS ROG Strix Z590-E', 'LGA 1200, Wi-Fi 6, RGB', 289.99, 8, '2025-04-04', 10),
('', 221, 'Gigabyte Z490 AORUS MASTER', 'LGA 1200, 10+1 fazna napajalna enota', 249.99, 12, '2025-04-04', 10),
('', 222, 'MSI MPG Z490 GAMING EDGE WIFI', 'LGA 1200, PCIe 3.0 x16', 179.99, 15, '2025-04-04', 10),
('', 223, 'ASRock B450M PRO4', 'AM4, DDR4, 6 fazni napajalni sistem', 79.99, 20, '2025-04-04', 10),
('', 224, 'Logitech G Pro Wireless', 'Wireless, HERO senzor, 25k DPI', 129.99, 25, '2025-04-04', 11),
('', 225, 'Razer DeathAdder V2', 'Optični senzor, 20k DPI', 69.99, 30, '2025-04-04', 11),
('', 226, 'SteelSeries Rival 600', 'Dual senzor, 12k DPI', 79.99, 20, '2025-04-04', 11),
('', 227, 'Corsair Dark Core RGB/SE', 'Wireless, 18k DPI, Qi brezžično polnjenje', 89.99, 15, '2025-04-04', 11),
('', 228, 'Logitech G703 LIGHTSPEED', 'Wireless, HERO senzor, 25k DPI', 99.99, 18, '2025-04-04', 11),
('', 229, 'Corsair Vengeance LPX 16GB', 'DDR4, 3200MHz, 2x8GB kit', 59.99, 40, '2025-04-04', 12),
('', 230, 'G.SKILL Ripjaws V 32GB', 'DDR4, 3600MHz, 2x16GB kit', 129.99, 30, '2025-04-04', 12),
('', 231, 'Kingston HyperX Fury 16GB', 'DDR4, 2666MHz, 2x8GB kit', 49.99, 50, '2025-04-04', 12),
('', 232, 'Crucial Ballistix 16GB', 'DDR4, 3000MHz, 2x8GB kit', 55.99, 25, '2025-04-04', 12),
('', 233, 'Corsair Dominator Platinum 32GB', 'DDR4, 4000MHz, 2x16GB kit', 179.99, 10, '2025-04-04', 12),
('', 234, 'Bose QuietComfort 35 II', 'Noise Cancelling, Bluetooth', 299.99, 15, '2025-04-04', 13),
('', 235, 'Sony WH-1000XM4', 'Noise Cancelling, 30h baterija', 349.99, 10, '2025-04-04', 13),
('', 236, 'Sennheiser HD 660S', 'Odprt dizajn, audiophile kvalitet', 499.99, 8, '2025-04-04', 13),
('', 237, 'JBL Quantum 800', 'Gaming, 7.1 surround sound', 179.99, 20, '2025-04-04', 13),
('', 238, 'SteelSeries Arctis Pro Wireless', 'Lossless audio, 2.4 GHz wireless', 329.99, 12, '2025-04-04', 13),
('', 239, 'Samsung 970 EVO Plus 1TB', 'NVMe SSD, 3500MB/s branje', 129.99, 40, '2025-04-04', 14),
('', 240, 'WD Black SN850 1TB', 'PCIe Gen4 NVMe, 7000MB/s branje', 149.99, 30, '2025-04-04', 14),
('', 241, 'Crucial P5 Plus 500GB', 'PCIe Gen4, 6600MB/s branje', 89.99, 50, '2025-04-04', 14),
('', 242, 'Kingston KC3000 1TB', 'M.2 NVMe, 7000MB/s branje', 169.99, 20, '2025-04-04', 14),
('', 243, 'ADATA XPG Gammix S70 1TB', 'PCIe Gen4, 7400MB/s branje', 159.99, 15, '2025-04-04', 14);

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

-- --------------------------------------------------------

--
-- Struktura tabele `košarica`
--

CREATE TABLE `košarica` (
  `id_k` int(11) NOT NULL,
  `datum_ustvarjanja` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `naročila`
--

CREATE TABLE `naročila` (
  `id_n` int(11) NOT NULL,
  `datum_narocila` date NOT NULL,
  `status_narocila` varchar(30) NOT NULL,
  `skupna_cena` double DEFAULT NULL,
  `naslov_dostave` varchar(59) NOT NULL,
  `nacin_placila` char(20) NOT NULL,
  `id_u` int(11) DEFAULT NULL,
  `id_k` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `postavke_kosarice`
--

CREATE TABLE `postavke_kosarice` (
  `id_po_ko` int(11) NOT NULL,
  `količina` int(11) NOT NULL,
  `cena_ob_nakupu` double NOT NULL,
  `id_k` int(11) DEFAULT NULL,
  `id_i` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `postavke_narocila`
--

CREATE TABLE `postavke_narocila` (
  `id_po_na` int(11) NOT NULL,
  `količina` int(11) NOT NULL,
  `cena_ob_nakupu` int(11) NOT NULL,
  `id_n` int(11) DEFAULT NULL,
  `id_i` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `privilegiji`
--

CREATE TABLE `privilegiji` (
  `id_p` int(11) NOT NULL,
  `naziv` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `privilegiji`
--

INSERT INTO `privilegiji` (`id_p`, `naziv`) VALUES
(1, 'kupec'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Struktura tabele `uporabniki`
--

CREATE TABLE `uporabniki` (
  `id_u` int(11) NOT NULL,
  `ime` varchar(20) NOT NULL,
  `priimek` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `geslo` varchar(256) NOT NULL,
  `naslov` varchar(100) DEFAULT NULL,
  `datum_registracije` date NOT NULL,
  `id_k` int(11) DEFAULT NULL,
  `id_p` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Odloži podatke za tabelo `uporabniki`
--

INSERT INTO `uporabniki` (`id_u`, `ime`, `priimek`, `email`, `geslo`, `naslov`, `datum_registracije`, `id_k`, `id_p`) VALUES
(2, 'blaz', 'kristan', 'blaz.kristan@scv.si', '184911b3f7af7b8aac99583da7ddfd77cc80c16c', 'klanc', '2025-05-06', NULL, 2),
(4, 'blaz', 'kristan', 'blazokristan@scv.si', '515f6e0ee587e3906c9cc61b79b7cd4ce6c1a702', 'scv', '2025-05-06', NULL, 2),
(5, 'blaz', 'kristam', 'kristanblaz56@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123', '2025-05-29', NULL, 2);

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `izdelek`
--
ALTER TABLE `izdelek`
  ADD PRIMARY KEY (`id_i`),
  ADD KEY `IX_Relationship8` (`id_ka`);

--
-- Indeksi tabele `kategorije`
--
ALTER TABLE `kategorije`
  ADD PRIMARY KEY (`id_ka`);

--
-- Indeksi tabele `košarica`
--
ALTER TABLE `košarica`
  ADD PRIMARY KEY (`id_k`);

--
-- Indeksi tabele `naročila`
--
ALTER TABLE `naročila`
  ADD PRIMARY KEY (`id_n`),
  ADD KEY `IX_Relationship2` (`id_u`),
  ADD KEY `IX_Relationship10` (`id_k`);

--
-- Indeksi tabele `postavke_kosarice`
--
ALTER TABLE `postavke_kosarice`
  ADD PRIMARY KEY (`id_po_ko`),
  ADD KEY `IX_Relationship3` (`id_k`),
  ADD KEY `IX_Relationship6` (`id_i`);

--
-- Indeksi tabele `postavke_narocila`
--
ALTER TABLE `postavke_narocila`
  ADD PRIMARY KEY (`id_po_na`),
  ADD KEY `IX_Relationship4` (`id_n`),
  ADD KEY `IX_Relationship7` (`id_i`);

--
-- Indeksi tabele `privilegiji`
--
ALTER TABLE `privilegiji`
  ADD PRIMARY KEY (`id_p`);

--
-- Indeksi tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  ADD PRIMARY KEY (`id_u`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `IX_Relationship11` (`id_k`),
  ADD KEY `fk_uporabniki_privilegiji` (`id_p`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `izdelek`
--
ALTER TABLE `izdelek`
  MODIFY `id_i` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT tabele `uporabniki`
--
ALTER TABLE `uporabniki`
  MODIFY `id_u` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Omejitve tabel za povzetek stanja
--

--
-- Omejitve za tabelo `izdelek`
--
ALTER TABLE `izdelek`
  ADD CONSTRAINT `Relationship8` FOREIGN KEY (`id_ka`) REFERENCES `kategorije` (`id_ka`);

--
-- Omejitve za tabelo `naročila`
--
ALTER TABLE `naročila`
  ADD CONSTRAINT `Relationship10` FOREIGN KEY (`id_k`) REFERENCES `košarica` (`id_k`),
  ADD CONSTRAINT `Relationship2` FOREIGN KEY (`id_u`) REFERENCES `uporabniki` (`id_u`);

--
-- Omejitve za tabelo `postavke_kosarice`
--
ALTER TABLE `postavke_kosarice`
  ADD CONSTRAINT `Relationship3` FOREIGN KEY (`id_k`) REFERENCES `košarica` (`id_k`),
  ADD CONSTRAINT `Relationship6` FOREIGN KEY (`id_i`) REFERENCES `izdelek` (`id_i`);

--
-- Omejitve za tabelo `postavke_narocila`
--
ALTER TABLE `postavke_narocila`
  ADD CONSTRAINT `Relationship4` FOREIGN KEY (`id_n`) REFERENCES `naročila` (`id_n`),
  ADD CONSTRAINT `Relationship7` FOREIGN KEY (`id_i`) REFERENCES `izdelek` (`id_i`);

--
-- Omejitve za tabelo `uporabniki`
--
ALTER TABLE `uporabniki`
  ADD CONSTRAINT `Relationship11` FOREIGN KEY (`id_k`) REFERENCES `košarica` (`id_k`),
  ADD CONSTRAINT `fk_uporabniki_privilegiji` FOREIGN KEY (`id_p`) REFERENCES `privilegiji` (`id_p`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
