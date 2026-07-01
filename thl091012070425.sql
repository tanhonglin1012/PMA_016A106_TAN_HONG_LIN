-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2026 at 10:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thl091012070425`
--

-- --------------------------------------------------------

--
-- Table structure for table `calon`
--

CREATE TABLE `calon` (
  `idCalon` varchar(4) NOT NULL,
  `namacalon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `calon`
--

INSERT INTO `calon` (`idCalon`, `namacalon`) VALUES
('C01', 'Donald'),
('C02', 'Kevin'),
('C03', 'Cindy');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `idKategori` varchar(3) NOT NULL,
  `namaKategori` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`idKategori`, `namaKategori`) VALUES
('K01', 'Pengerusi'),
('K02', 'Naib Pengerusi'),
('K03', 'Setiausaha');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `namaPengguna` varchar(12) NOT NULL,
  `kataLaluan` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`namaPengguna`, `kataLaluan`) VALUES
('010606060655', '123'),
('020918071234', '123'),
('021101081345', '123'),
('123456789012', '123');

-- --------------------------------------------------------

--
-- Table structure for table `pengundi`
--

CREATE TABLE `pengundi` (
  `noKP` varchar(14) NOT NULL,
  `nama` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--'''''''''''''''
-- Dumping data for table `pengundi`
--

INSERT INTO `pengundi` (`noKP`, `nama`) VALUES
('010606060655', 'Yi Aun'),
('020918071234', 'Yan Ru'),
('021101081345', 'Hong Ling');

-- --------------------------------------------------------

--
-- Table structure for table `rekod_undi`
--

CREATE TABLE `rekod_undi` (
  `idUndi` varchar(3) NOT NULL,
  `Masa` time NOT NULL,
  `noKP` varchar(14) NOT NULL,
  `idCalon` varchar(4) NOT NULL,
  `idKategori` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rekod_undi`
--

INSERT INTO `rekod_undi` (`idUndi`, `Masa`, `noKP`, `idCalon`, `idKategori`) VALUES
('1', '09:00:00', '021101081345', 'C01', 'K01'),
('2', '09:00:00', '021101081345', 'C02', 'K03'),
('3', '09:00:00', '021101081345', 'C03', 'K02'),
('4', '09:10:00', '020918071234', 'C01', 'K01'),
('5', '09:10:00', '020918071234', 'C02', 'K02'),
('6', '09:10:00', '020918071234', 'C03', 'K03'),
('7', '10:10:00', '010606060655', 'C01', 'K01'),
('8', '10:10:00', '010606060655', 'C02', 'K02'),
('9', '10:10:00', '010606060655', 'C03', 'K03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calon`
--
ALTER TABLE `calon`
  ADD PRIMARY KEY (`idCalon`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`idKategori`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`namaPengguna`);

--
-- Indexes for table `pengundi`
--
ALTER TABLE `pengundi`
  ADD PRIMARY KEY (`noKP`);

--
-- Indexes for table `rekod_undi`
--
ALTER TABLE `rekod_undi`
  ADD PRIMARY KEY (`idUndi`),
  ADD KEY `noKP` (`noKP`),
  ADD KEY `idCalon` (`idCalon`),
  ADD KEY `idKategori` (`idKategori`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rekod_undi`
--
ALTER TABLE `rekod_undi`
  ADD CONSTRAINT `rekod_undi_ibfk_1` FOREIGN KEY (`noKP`) REFERENCES `pengundi` (`noKP`),
  ADD CONSTRAINT `rekod_undi_ibfk_2` FOREIGN KEY (`idCalon`) REFERENCES `calon` (`idCalon`),
  ADD CONSTRAINT `rekod_undi_ibfk_3` FOREIGN KEY (`idKategori`) REFERENCES `kategori` (`idKategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
