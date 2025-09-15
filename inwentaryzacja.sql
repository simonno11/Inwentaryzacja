-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Wrz 09, 2025 at 01:26 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inwentaryzacja`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `firmy`
--

CREATE TABLE `firmy` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `firmy`
--

INSERT INTO `firmy` (`id`, `name`) VALUES
(1, 'Google'),
(2, 'Meta');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `uzytkownik` varchar(100) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `uprawnienia` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `uzytkownik`, `haslo`, `uprawnienia`) VALUES
(1, 'admin', '$2y$10$jphCUQ5HfdQkunq/QXac6OnvnjJtspks0dwR1ByLLysFSA8rm5yTG', 'admin'),
(2, 'jan', '$2y$10$YZbVdflcHt/hRzdVwvpbzeRPrPAhwi8g8SrtDVogtoyeyEhDuu//q', 'user');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wyposazenie`
--

CREATE TABLE `wyposazenie` (
  `id` int(11) NOT NULL,
  `firmy_id` int(11) DEFAULT NULL,
  `budynek` varchar(100) DEFAULT NULL,
  `numer_wyposazenia` varchar(50) DEFAULT NULL,
  `nazwa` varchar(100) DEFAULT NULL,
  `ilosc` int(11) DEFAULT NULL,
  `cena` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `firmy`
--
ALTER TABLE `firmy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uzytkownik` (`uzytkownik`);

--
-- Indeksy dla tabeli `wyposazenie`
--
ALTER TABLE `wyposazenie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firmy_id` (`firmy_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `firmy`
--
ALTER TABLE `firmy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wyposazenie`
--
ALTER TABLE `wyposazenie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wyposazenie`
--
ALTER TABLE `wyposazenie`
  ADD CONSTRAINT `wyposazenie_ibfk_1` FOREIGN KEY (`firmy_id`) REFERENCES `firmy` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
