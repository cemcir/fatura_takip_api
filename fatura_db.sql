-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 28 Ağu 2025, 16:56:30
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `fatura_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `faturalar`
--

CREATE TABLE `faturalar` (
  `id` int(11) NOT NULL,
  `fatura_no` varchar(64) NOT NULL,
  `tutar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `adres` mediumtext NOT NULL,
  `birim` varchar(100) NOT NULL,
  `eklenme_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `faturalar`
--

INSERT INTO `faturalar` (`id`, `fatura_no`, `tutar`, `adres`, `birim`, `eklenme_tarihi`) VALUES
(6, 'FTR-0001', 150.50, 'Atakum / Samsun', 'Bilgi İşlem', '2025-08-28 10:51:25'),
(7, '12345', 0.00, '', 'Bilgi İşlem', '2025-08-28 13:29:53'),
(10, '123456', 100.00, 'Atakum/Samsun', 'Bilgi İşlem', '2025-08-28 13:30:21');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `faturalar`
--
ALTER TABLE `faturalar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fatura_no` (`fatura_no`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `faturalar`
--
ALTER TABLE `faturalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
