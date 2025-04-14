-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2025 at 02:41 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php-cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `descrip` varchar(10000) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `title`, `descrip`, `img`, `url`, `date`, `status`) VALUES
(1, 'Tentang Kami', '<h3><b><span style=\"font-size: 1rem; color: rgb(33, 37, 41); font-family: Arial;\">Not interested in custom validation feedback messages or writing JavaScript to change form behaviors? All good, you can use the browser defaults. Try submitting the form below. Depending on your </span><span style=\"font-size: 1rem; font-family: Arial; background-color: rgb(255, 255, 0);\">browser and OS</span><span style=\"font-size: 1rem; color: rgb(33, 37, 41); font-family: Arial;\">, you’ll see a slightly different style of feedback.</span></b></h3>', '1660341006_4.png', '', '2025-04-09 15:09:43', '0');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ad_id` int NOT NULL,
  `ad_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pict` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ad_email` varchar(100) NOT NULL,
  `ad_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ad_id`, `ad_name`, `pict`, `ad_email`, `ad_password`) VALUES
(5, 'Frans Andreas Pasaribu', 'admin_5_1742370598.png', 'admin@mail.com', '$2y$10$tdQZLfzVbPBQ7v2GnM1unubYkMEimQ8P3qNRohpEZsGb/qLDRFmIe'),
(8, 'Arjuna', 'admin_8_1742370611.png', 'arjuna@gmail.com', '$2y$10$HAANQ5Rfj.oB246XpOq7ueBwCYJ3GrJU/Y9PqP6esv2kpzSrtL23K'),
(10, 'Kolbu', 'admin_10_1742542128.png', 'kolbu@gmail.com', '$2y$10$aBirYZO5gBhsMGKWREXEw.YhRiOJCg4mRiOCoBq0vgmc6rfk5HV9q');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int NOT NULL,
  `title` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `descrip` varchar(10000) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `title`, `category`, `descrip`, `img`, `url`, `date`) VALUES
(10, 'Pep Guardiola Sebut 3 Klub yang Bisa Hentikan Real Madrid di Liga Champions', 'Olahraga', 'Bola.com, Jakarta - Pep Guardiola menyebut Liverpool sebagai salah satu dari tiga klub yang dapat menghentikan Real Madrid meraih gelar Liga Champions setelah Manchester City tersingkir di babak play-off, sementara Carlo Ancelotti telah memberi tahu Kylian Mbappe apa yang harus dilakukan untuk mencapai level Cristiano Ronaldo.\r\n\r\nReal Madrid mengalahkan Man City 3-1 di Santiago Bernabeu pada leg kedua babak play-off Knockout pada Rabu malam, melaju ke babak 16 besar kompetisi klub terkemuka Eropa dengan agregat 6-3. Setelah menang 3-2 di leg pertama di Etihad Stadium pekan lalu, juara bertahan Spanyol dan Eropa itu menjadi favorit untuk lolos, dan gol Mbappe pada menit keempat menetapkan nada di Spanyol.', '32891858_4.png', '', '2025-03-19 06:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `cat_name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `cat_name`) VALUES
(5, 'Transportations'),
(6, 'Technology'),
(12, 'Olahraga');

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id_info` int NOT NULL,
  `logo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `lokasi` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gmail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `maps_url` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id_info`, `logo`, `lokasi`, `gmail`, `maps_url`) VALUES
(1, '502247371Logo LMM Black list White.png', 'https://www.google.co.id/maps/place/Biro+Piskologi+Lisa+Mitra+Mandiri/@-7.3068437,112.7495879,21z/data=!4m15!1m8!3m7!1s0x2dd7fb082088ef3f:0xbec137fac84a9118!2sJl.+Bendul+Merisi+IX+No.1,+Bendul+Merisi,+Kec.+Wonocolo,+Surabaya,+Jawa+Timur+60239!3b1!8m2!3d-7.3067917!4d112.7493157!16s%2Fg%2F11gfgbl6wl!3m5!1s0x2dd7fb081fdd3647:0x17cab628a96b6958!8m2!3d-7.3068328!4d112.7495706!16s%2Fg%2F11c1xpxl6j?entry=ttu&g_ep=EgoyMDI1MDIxOS4xIKXMDSoASAFQAw%3D%3D', 'lisamitramandiri@yahoo.co.id', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d989.3545361332668!2d112.74892686948607!3d-7.306831469329307!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb081fdd3647%3A0x17cab628a96b6958!2sBiro%20Piskologi%20Lisa%20Mitra%20Mandiri!5e0!3m2!1sen!2sid!4v1739858880277!5m2!1sen!2sid');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja`
--

CREATE TABLE `jam_kerja` (
  `id_jam` int NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `waktu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jam_kerja`
--

INSERT INTO `jam_kerja` (`id_jam`, `deskripsi`, `waktu`) VALUES
(1, '<span style=\"font-size: 18px;\">Kami bekerja 5 hari seminggu.</span>', 'Senin - Jumat: 08:00 - 17:00');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_gal`
--

CREATE TABLE `kategori_gal` (
  `id` int NOT NULL,
  `kat_gal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori_gal`
--

INSERT INTO `kategori_gal` (`id`, `kat_gal`) VALUES
(1, 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024'),
(2, 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024'),
(5, 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022');

-- --------------------------------------------------------

--
-- Table structure for table `klien`
--

CREATE TABLE `klien` (
  `id` int NOT NULL,
  `klien` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `klien`
--

INSERT INTO `klien` (`id`, `klien`, `gambar`) VALUES
(9, 'Bapennas', '324346231BAPPENAS-NEW-2023.jpg'),
(10, 'Cirebon', '1785495941Cirebon.png'),
(11, 'CV Berkat Jaya Plastind', '351420206CV BERKAT JAYA PLASTIND.jpg'),
(12, 'Jasindo', '667147142img_logo_JAsindo.jpg'),
(13, 'Koperasi', '1496781438LAMBANG-KOPERASI.png'),
(14, 'ACC', '912768163logo acc.jpg'),
(15, 'Apotek Satria', '573785034LOGO APOTEK SATRIA.jpg'),
(16, 'Askrindo', '1365586051logo askrindo.jpg'),
(17, 'Asuransi Astra', '1305267166Logo Asuransi Astra.png'),
(18, 'Kementerian Bapennas', '128398376LOGO BAPPENAS.png'),
(19, 'BPKN', '433386581Logo BPKN.jpg'),
(20, 'Badan Pusat Statistik', '616527267LOGO bps.png'),
(21, 'Indofarma', '440630776Logo Indofarma.png'),
(22, 'Jabal Noer', '550619823LOgo Jabal NOer.jpg'),
(23, 'K3PG', '1123598895Logo K3PG.png'),
(24, 'Kementerian Pertanian', '676017418LOGO KEMENTERIAN PERTANIANl.png'),
(25, 'Kominfo', '1346437865LOGO KOMINFO.png'),
(26, 'LSS', '1645788224Logo LSS.jpg'),
(27, 'Moonzaya', '1377734181Logo MOONZAYA.jpg'),
(28, 'PDAM Sidoarjo', '58668138LOGO PDAM DARJO.png'),
(29, 'PG Candi Baru', '372501432LOGO PG CANDI BARU.png'),
(30, 'Politeknik Pelayaran Surabaya', '1814221415Logo Politeknik Pelassyaran SBY.jpg'),
(31, 'POLITEKNIK PERKERETAAPIAN', '817996733LOGO POLITEKNIK PERKERETAAPIAN.png'),
(34, 'Inkote', '1653907265Logo PT. INKOTE.jpg'),
(35, 'PT. JEWEL DYNA ORALCARE', '1724551664LOGO PT. JEWEL DYNA ORALCARE.png'),
(36, 'PT. Light Steel Solutions Global', '909777699Logo PT. Light Steel Solutions Global.png'),
(37, 'PT. Nylex', '1249126791Logo PT. Nylex.jpg'),
(38, 'PT. Rapid Plast', '1337227868logo PT. Rapid Plast.png'),
(39, 'PT. Vascomm', '1701196429Logo PT. Vascomm.png'),
(40, 'PT. YOLITA JAYA INDONESIA', '1277442757LOGO PT. YOLITA JAYA INDONESIA.jpg'),
(41, 'Rajawali Tanjungsari', '968530858logo Rajawali Tanjungsari.png'),
(42, 'RSMM', '1320388220LOGO RSMM.png'),
(43, 'SMK6', '284182240Logo SMK6.png'),
(45, 'SULBAR', '630466040LOGO SULBAR.png'),
(47, 'YDSF', '961951110Logo ydsf2.jpg'),
(48, 'BKKBN', '613475251Logo_BKKBN_(2020).png'),
(49, 'KEMENKES', '684797082Logo_KEMENKES.png'),
(50, 'Kementerian Perdagangan Republik Indonesia', '1513469786Logo_Kementerian_Perdagangan_Republik_Indonesia.png'),
(51, 'LKPP', '626413380Logo_LKPP.png'),
(52, 'Sucofindo', '1512106727Logo_sucofindo.png'),
(53, 'Askrindo', '927494017logo-askrindo-ifg.png'),
(54, 'SOS Indonesia', '384913700Logo-Desa-Anak-SOS.png'),
(55, 'Harris Mobil Group', '1904124998logo-harris-mobil-group.png'),
(56, 'Mastan', '1749204136logo-mastan.jpg'),
(57, 'PT. Garam', '1365330495logo-new PT. GARAM.png'),
(59, 'PG Rajawali', '1827267656logo-PG RAJAWALI.png'),
(60, 'Sragen', '1193986942logo-sragen-sm.jpg'),
(61, 'UIN SUNAN AMPEL', '1489775960Logo-UIN_SUNAN_AMPEL.jpg'),
(62, 'PANCARAN GROUP', '146982105Lowongan Kerja Samarinda PANCARAN GROUP 2019.gif'),
(63, 'PT SINKO PRIMA ALLOY', '1285658089PT SINKO PRIMA ALLOY_.png'),
(64, 'PT Sucofindo', '1102887688PT_Sucofindo.png'),
(65, 'RAJAWALI NUSINDO', '1458323301RAJAWALI NUSINDO.jpg'),
(67, 'ROYAL GUARD LOGO', '615049697ROYAL-GUARD-LOGO.png'),
(68, 'SMKN 2 Probolinggo', '1900605909SMKN 2 Probolinggo.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int NOT NULL,
  `galery` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `status` enum('1','0') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `galery`, `foto`, `kategori`, `uploaded_on`, `status`) VALUES
(320, '', '2057995084DSC01478_24_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(321, '', '1907403175DSC01483_25_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(322, '', '33818366DSC01516_26_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(323, '', '812837495DSC01542_27_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(324, '', '924282853DSC01613_28_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(325, '', '991143276DSC01622_29_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(326, '', '2012258483DSC01637_30_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(327, '', '40216136DSC01640_31_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(328, '', '951500318DSC01667_32_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(329, '', '2100245172DSC01698_33_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(330, '', '2137913612DSC01702_34_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(331, '', '185652806DSC01772_35_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(332, '', '1949368044DSC01843_36_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(333, '', '1220972538DSC01844_37_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(334, '', '1518467317DSC01855_38_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(335, '', '769563576DSC01863_39_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(336, '', '869254524DSC01864_40_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(337, '', '938681805DSC01867_41_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(338, '', '2144406568DSC01871_42_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(339, '', '274898537DSC01879_43_11zon.jpg', 'Asesmen Pegawai Kontrak Politeknik Pelayaran Surabaya 2022', '2025-03-14 14:18:44', '1'),
(340, '', '1785608925DSC09750_2_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(341, '', '1100038074DSC09754_3_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(342, '', '1784709090DSC09755_4_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(343, '', '1164759202DSC09771_5_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(344, '', '1045196241DSC09801_7_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(345, '', '2084628610DSC09814_8_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(346, '', '1400280580DSC09832_9_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(347, '', '1299878102DSC09866_10_11zon.jpg', 'Psikotes Siswa KB TK Jabal Noer 04 Mei 2024', '2025-03-14 14:22:31', '1'),
(348, '', '524797093DSC08224_1_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(349, '', '200622933DSC08226_2_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(350, '', '160663025DSC08233_3_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(351, '', '572842476DSC08249_4_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(352, '', '9150796DSC08250_5_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(353, '', '1184669060DSC08252_6_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(354, '', '1862304568DSC08253_7_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(355, '', '2140537463DSC08276_8_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1'),
(356, '', '586883158DSC08290_9_11zon.jpg', 'Psikotes Perumda Delta Tirta Sidoarjo 21 Februari 2024', '2025-03-14 14:24:28', '1');

-- --------------------------------------------------------

--
-- Table structure for table `projek`
--

CREATE TABLE `projek` (
  `id` int NOT NULL,
  `mitra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tahun` year DEFAULT NULL,
  `deskrip` varchar(255) DEFAULT NULL,
  `upload` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projek`
--

INSERT INTO `projek` (`id`, `mitra`, `tahun`, `deskrip`, `upload`) VALUES
(3, 'Zoom', 2025, 'Berikan projek', '2025-04-08 08:17:48'),
(20, 'RT', 2023, 'Makan makan', '2025-04-08 08:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `id_pro` int NOT NULL,
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`id_pro`, `name`, `pdf`, `date`) VALUES
(43, 'Proposal Lisa Mitra Mandiri', '1649692473_Proposal Lisa Mitra Mandiri Copyright 2025.pdf', '2025-04-10 09:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `descrip` varchar(10000) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `descrip`, `img`, `date`) VALUES
(37, 'Logistic Services', '<h3 style=\"font-family: &quot;Source Sans Pro&quot;, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; color: rgb(0, 0, 0);\"><span style=\"color: rgb(36, 36, 36); font-family: Arial; font-size: 16px;\">We are one of the best serving logistics company. Here, the clients get real-time pricing. The price for logistics transport service from USA, Canada, India &amp; China with shipping facilities with on-time deliveries in all the major areas around the country.Â.</span></h3>', '546355177_4.png', '2025-04-10 01:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE `social` (
  `id` int NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  ` created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `social`
--

INSERT INTO `social` (`id`, `facebook`, `twitter`, `instagram`, `linkedin`, `whatsapp`, `phone`, ` created_at`) VALUES
(1, 'https://www.facebook.com/share/18zG6wv8V7/', '', 'https://www.instagram.com/lisamitramandiri?igsh=NGkzM2dsd3QyYzlz', '', '+6282228112280', '031 843 7854sd', '2025-04-14 02:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `descrip` varchar(10000) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `title`, `designation`, `descrip`, `img`, `facebook`, `twitter`, `instagram`, `linkedin`, `whatsapp`, `url`, `date`, `status`) VALUES
(1, 'Dr. Noeri Djati Perwitasari, S.Psi., M.Psi., Psikolog', 'Direktur', '', '1676503376_Cuplikan layar 2025-03-12 152851.png', '', '', '', '', '+6281252983049', '', '2025-03-27 07:02:06', '0'),
(2, 'Moh Rizal Kurniawan, S.Psi', 'Staf', '', '897521386_Cuplikan layar 2025-03-12 152928.png', '', '', '', '', '+628995927382', '', '2025-03-12 07:00:33', '0'),
(3, 'Arum Laili Fitroh, S.Psi', 'Staf', '', '1785141359_Cuplikan layar 2025-03-12 153906.png', '', '', '', '', '+6281358396114', '', '2025-03-12 07:09:32', '0'),
(4, 'Noven Anggraini Permatasari', 'Staf', '', '644138187_Cuplikan layar 2025-03-12 153648.png', '', '', '', '', '+6282298617397', '', '2025-03-12 07:08:50', '0');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `descrip` varchar(10000) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `title`, `designation`, `descrip`, `img`, `url`, `date`, `status`) VALUES
(29, 'Lillian Grace', 'VP, Green Valley Intenational', 'The staff is amazing! Very helpful and considerate with a sense of urgency &Loads are 99% on time.', '1462204079_avatar2.png', NULL, 'Wed 19 Mar 2025', '0'),
(30, 'Roman Dexter', 'Business Man, Newyork, USA', 'I only use GLOBAL DIGITAL SYSTEM CORPORATION  for my shipping needs. My clients have all come to expect the excellent shipping.', '534621237_avatar04.png', NULL, 'Wed 19 Mar 2025', '0'),
(31, 'Lambas', 'Direktur', 'Mantap benar ini, sangat mantap mantap mantappp', '1770162712_avatar.png', NULL, 'Wed 19 Mar 2025', '0');

-- --------------------------------------------------------

--
-- Table structure for table `visitor`
--

CREATE TABLE `visitor` (
  `id_visitor` int NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `visit_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `user_agent` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `device` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `visitor`
--

INSERT INTO `visitor` (`id_visitor`, `ip_address`, `visit_date`, `user_agent`, `browser`, `device`) VALUES
(5, '127.0.0.1', '2025-03-13 00:00:00', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(6, '127.0.0.1', '2025-03-13 00:00:00', 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/15.0 Mobile/14E304 Safari/602.1', 'Apple Safari', 'Mobile'),
(7, '127.0.0.1', '2025-03-13 00:00:00', 'Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-G980F Build/PPR1.180610.011) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Mobile Safari/537.36', 'Google Chrome', 'Mobile'),
(8, '127.0.0.1', '2025-03-13 00:00:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(10, '127.0.0.1', '2025-03-14 00:00:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(11, '127.0.0.1', '2025-03-14 00:00:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', 'Mozilla Firefox', 'Desktop'),
(12, '127.0.0.1', '2025-03-14 00:00:00', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', 'Google Chrome', 'Mobile'),
(13, '127.0.0.1', '2025-03-14 00:00:00', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(14, '127.0.0.1', '2025-03-14 00:00:00', 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/15.0 Mobile/14E304 Safari/602.1', 'Apple Safari', 'Mobile'),
(15, '127.0.0.1', '2025-03-14 00:00:00', 'Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-G980F Build/PPR1.180610.011) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Mobile Safari/537.36', 'Google Chrome', 'Mobile'),
(16, '127.0.0.1', '2025-03-14 00:00:00', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1', 'Apple Safari', 'Mobile'),
(17, '127.0.0.1', '2025-03-15 00:00:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(29, '127.0.0.1', '2025-03-17 15:08:51', 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/17.5 Mobile/15A5341f Safari/604.1', 'Apple Safari', 'Mobile'),
(30, '127.0.0.1', '2025-03-17 23:02:50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(31, '127.0.0.1', '2025-03-17 22:32:45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(32, '127.0.0.1', '2025-03-18 15:55:58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(33, '127.0.0.1', '2025-03-18 15:42:57', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1', 'Apple Safari', 'Mobile'),
(34, '127.0.0.1', '2025-03-18 15:48:05', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(35, '127.0.0.1', '2025-03-18 15:58:02', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', 'Google Chrome', 'Mobile'),
(36, '127.0.0.1', '2025-03-19 08:25:50', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', 'Google Chrome', 'Mobile'),
(37, '127.0.0.1', '2025-03-19 23:17:04', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(38, '127.0.0.1', '2025-03-19 23:24:30', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(39, '127.0.0.1', '2025-03-20 15:58:24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(40, '127.0.0.1', '2025-03-20 15:55:34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(41, '127.0.0.1', '2025-03-20 09:15:17', 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/17.5 Mobile/15A5341f Safari/604.1', 'Apple Safari', 'Tablet'),
(42, '127.0.0.1', '2025-03-20 09:16:49', 'Mozilla/5.0 (Linux; Android 11; SM-T970) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Safari/537.36', 'Google Chrome', 'Tablet'),
(43, '127.0.0.1', '2025-03-20 11:59:36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'Microsoft Edge', 'Desktop'),
(44, '127.0.0.1', '2025-03-21 16:08:44', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(45, '127.0.0.1', '2025-03-21 16:01:32', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(46, '127.0.0.1', '2025-03-24 09:15:31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'Microsoft Edge', 'Desktop'),
(47, '127.0.0.1', '2025-03-24 15:57:16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(48, '127.0.0.1', '2025-03-24 13:57:38', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(49, '127.0.0.1', '2025-03-25 15:54:53', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(50, '127.0.0.1', '2025-03-25 15:17:34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(51, '127.0.0.1', '2025-03-25 09:11:47', 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/17.5 Mobile/15A5341f Safari/604.1', 'Apple Safari', 'Tablet'),
(52, '127.0.0.1', '2025-03-25 09:11:32', 'Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-G980F Build/PPR1.180610.011) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Mobile Safari/537.36', 'Google Chrome', 'Mobile'),
(53, '127.0.0.1', '2025-03-26 15:59:58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(54, '127.0.0.1', '2025-03-26 15:47:39', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(55, '127.0.0.1', '2025-03-26 14:46:11', 'Mozilla/5.0 (Linux; Android 12; Surface Duo) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Mobile Safari/537.36 EdgA/45.11.4.5118', 'Microsoft Edge', 'Mobile'),
(56, '127.0.0.1', '2025-03-26 14:46:20', 'Mozilla/5.0 (Linux; Android 11; SM-T970) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Safari/537.36', 'Google Chrome', 'Tablet'),
(57, '127.0.0.1', '2025-03-26 14:47:09', 'Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-F900U Build/PPR1.180610.011) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Mobile Safari/537.36', 'Google Chrome', 'Mobile'),
(58, '127.0.0.1', '2025-03-26 14:52:02', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.1 Safari/605.1.15', 'Apple Safari', 'Desktop'),
(59, '127.0.0.1', '2025-03-27 15:33:20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(60, '127.0.0.1', '2025-03-27 11:52:56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(61, '127.0.0.1', '2025-03-27 11:53:11', 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/17.5 Mobile/15A5341f Safari/604.1', 'Apple Safari', 'Tablet'),
(62, '127.0.0.1', '2025-03-27 11:53:15', 'Mozilla/5.0 (Linux; Android 11; SM-T970) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Safari/537.36', 'Google Chrome', 'Tablet'),
(63, '127.0.0.1', '2025-03-27 11:53:18', 'Mozilla/5.0 (Linux; Android 12; Surface Duo) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.6422.35 Mobile Safari/537.36 EdgA/45.11.4.5118', 'Microsoft Edge', 'Mobile'),
(64, '127.0.0.1', '2025-03-28 19:31:56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(65, '127.0.0.1', '2025-04-04 18:41:47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(66, '127.0.0.1', '2025-04-08 16:53:17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(67, '127.0.0.1', '2025-04-08 10:36:21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(68, '127.0.0.1', '2025-04-09 09:20:50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(69, '', '2025-04-09 09:12:27', '', 'Unknown Browser', 'Unknown Device'),
(70, '127.0.0.1', '2025-04-09 16:45:16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(71, '127.0.0.1', '2025-04-09 10:49:25', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(72, '127.0.0.1', '2025-04-09 15:53:25', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(73, '127.0.0.1', '2025-04-10 16:38:19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop'),
(74, '127.0.0.1', '2025-04-10 15:46:37', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'Apple Safari', 'Mobile'),
(75, '127.0.0.1', '2025-04-14 09:31:53', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'Google Chrome', 'Desktop');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id_info`);

--
-- Indexes for table `jam_kerja`
--
ALTER TABLE `jam_kerja`
  ADD PRIMARY KEY (`id_jam`);

--
-- Indexes for table `kategori_gal`
--
ALTER TABLE `kategori_gal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klien`
--
ALTER TABLE `klien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projek`
--
ALTER TABLE `projek`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id_pro`) USING BTREE;

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social`
--
ALTER TABLE `social`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor`
--
ALTER TABLE `visitor`
  ADD PRIMARY KEY (`id_visitor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ad_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id_info` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jam_kerja`
--
ALTER TABLE `jam_kerja`
  MODIFY `id_jam` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori_gal`
--
ALTER TABLE `kategori_gal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `klien`
--
ALTER TABLE `klien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

--
-- AUTO_INCREMENT for table `projek`
--
ALTER TABLE `projek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id_pro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `social`
--
ALTER TABLE `social`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `visitor`
--
ALTER TABLE `visitor`
  MODIFY `id_visitor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
