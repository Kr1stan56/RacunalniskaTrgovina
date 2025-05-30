/*
Created: 2. 04. 2025
Modified: 2. 04. 2025
Model: MySQL 8.0
Database: MySQL 8.0
*/

-- Create tables section -------------------------------------------------

-- Table uporabniki

CREATE TABLE `uporabniki`
(
  `id_u` Int NOT NULL,
  `ime` Varchar(20) NOT NULL,
  `priimek` Varchar(30) NOT NULL,
  `email` Varchar(200) NOT NULL,
  `geslo` Varchar(256) NOT NULL,
  `naslov` Varchar(100),
  `datum_registracije` Date NOT NULL,
  `id_k` Int
)
;

CREATE INDEX `IX_Relationship11` ON `uporabniki` (`id_k`)
;

ALTER TABLE `uporabniki` ADD PRIMARY KEY (`id_u`)
;

ALTER TABLE `uporabniki` ADD UNIQUE `email` (`email`)
;

-- Table kategorije

CREATE TABLE `kategorije`
(
  `id_ka` Int NOT NULL,
  `ime` Varchar(50) NOT NULL,
  `opis` Text
)
;

ALTER TABLE `kategorije` ADD PRIMARY KEY (`id_ka`)
;

-- Table izdelek

CREATE TABLE `izdelek`
(
  `slika` Varchar(100),
  `id_i` Int NOT NULL,
  `ime` Varchar(50) NOT NULL,
  `opis` Text,
  `cena` Double NOT NULL,
  `zaloga` Int NOT NULL,
  `datum_dodajanja` Date NOT NULL,
  `id_ka` Int
)
;

CREATE INDEX `IX_Relationship8` ON `izdelek` (`id_ka`)
;

ALTER TABLE `izdelek` ADD PRIMARY KEY (`id_i`)
;

-- Table košarica

CREATE TABLE `košarica`
(
  `id_k` Int NOT NULL,
  `datum_ustvarjanja` Date NOT NULL,
  `status` Bool NOT NULL
)
;

ALTER TABLE `košarica` ADD PRIMARY KEY (`id_k`)
;

-- Table postavke_kosarice

CREATE TABLE `postavke_kosarice`
(
  `id_po_ko` Int NOT NULL,
  `količina` Int NOT NULL,
  `cena_ob_nakupu` Double NOT NULL,
  `id_k` Int,
  `id_i` Int
)
;

CREATE INDEX `IX_Relationship3` ON `postavke_kosarice` (`id_k`)
;

CREATE INDEX `IX_Relationship6` ON `postavke_kosarice` (`id_i`)
;

ALTER TABLE `postavke_kosarice` ADD PRIMARY KEY (`id_po_ko`)
;

-- Table naročila

CREATE TABLE `naročila`
(
  `id_n` Int NOT NULL,
  `datum_narocila` Date NOT NULL,
  `status_narocila` Varchar(30) NOT NULL,
  `skupna_cena` Double,
  `naslov_dostave` Varchar(59) NOT NULL,
  `nacin_placila` Char(20) NOT NULL,
  `id_u` Int,
  `id_k` Int
)
;

CREATE INDEX `IX_Relationship2` ON `naročila` (`id_u`)
;

CREATE INDEX `IX_Relationship10` ON `naročila` (`id_k`)
;

ALTER TABLE `naročila` ADD PRIMARY KEY (`id_n`)
;

-- Table postavke_narocila

CREATE TABLE `postavke_narocila`
(
  `id_po_na` Int NOT NULL,
  `količina` Int NOT NULL,
  `cena_ob_nakupu` Int NOT NULL,
  `id_n` Int,
  `id_i` Int
)
;

CREATE INDEX `IX_Relationship4` ON `postavke_narocila` (`id_n`)
;

CREATE INDEX `IX_Relationship7` ON `postavke_narocila` (`id_i`)
;

ALTER TABLE `postavke_narocila` ADD PRIMARY KEY (`id_po_na`)
;

-- Create foreign keys (relationships) section -------------------------------------------------

ALTER TABLE `naročila` ADD CONSTRAINT `Relationship2` FOREIGN KEY (`id_u`) REFERENCES `uporabniki` (`id_u`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `postavke_kosarice` ADD CONSTRAINT `Relationship3` FOREIGN KEY (`id_k`) REFERENCES `košarica` (`id_k`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `postavke_narocila` ADD CONSTRAINT `Relationship4` FOREIGN KEY (`id_n`) REFERENCES `naročila` (`id_n`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `postavke_kosarice` ADD CONSTRAINT `Relationship6` FOREIGN KEY (`id_i`) REFERENCES `izdelek` (`id_i`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `postavke_narocila` ADD CONSTRAINT `Relationship7` FOREIGN KEY (`id_i`) REFERENCES `izdelek` (`id_i`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `izdelek` ADD CONSTRAINT `Relationship8` FOREIGN KEY (`id_ka`) REFERENCES `kategorije` (`id_ka`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `naročila` ADD CONSTRAINT `Relationship10` FOREIGN KEY (`id_k`) REFERENCES `košarica` (`id_k`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `uporabniki` ADD CONSTRAINT `Relationship11` FOREIGN KEY (`id_k`) REFERENCES `košarica` (`id_k`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

