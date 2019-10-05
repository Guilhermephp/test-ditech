-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 05-Out-2019 às 03:31
-- Versão do servidor: 10.1.38-MariaDB
-- versão do PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `dates`
--

CREATE TABLE `dates` (
  `date_id` int(11) NOT NULL,
  `date_value` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `dates`
--

INSERT INTO `dates` (`date_id`, `date_value`) VALUES
(1, '08:00:00'),
(2, '09:00:00'),
(3, '10:00:00'),
(4, '11:00:00'),
(5, '13:00:00'),
(6, '14:00:00'),
(7, '15:00:00'),
(8, '16:00:00'),
(9, '17:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `room_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_title`, `room_date`) VALUES
(4, 'salas teste', '2019-10-04 05:18:20');

-- --------------------------------------------------------

--
-- Estrutura da tabela `rooms_users`
--

CREATE TABLE `rooms_users` (
  `rooms_users_id` int(11) NOT NULL,
  `user_room_id` int(11) DEFAULT NULL,
  `date_room_id` int(11) DEFAULT NULL,
  `room_user_id` int(11) DEFAULT NULL,
  `room_user_reserved_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `rooms_users`
--

INSERT INTO `rooms_users` (`rooms_users_id`, `user_room_id`, `date_room_id`, `room_user_id`, `room_user_reserved_date`) VALUES
(5, 8, 4, 4, '2019-10-05 14:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ws_users`
--

CREATE TABLE `ws_users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_lastname` varchar(255) DEFAULT NULL,
  `user_cpf` varchar(255) DEFAULT NULL,
  `user_telephone` varchar(255) DEFAULT NULL,
  `user_cell` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_registration` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_level` int(11) NOT NULL DEFAULT '1',
  `user_genre` int(11) DEFAULT NULL,
  `user_cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `ws_users`
--

INSERT INTO `ws_users` (`user_id`, `user_name`, `user_lastname`, `user_cpf`, `user_telephone`, `user_cell`, `user_email`, `user_password`, `user_registration`, `user_lastupdate`, `user_level`, `user_genre`, `user_cover`) VALUES
(8, 'guilherme', 'natus', '036.407.480-93', '(51)2222-2222', '(22)92222-2222', 'guilherme@webb.com', '7c9cf251d98287ea90a2682f68c2275e', '2019-10-05 06:19:25', '2019-10-05 06:21:34', 3, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`date_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `rooms_users`
--
ALTER TABLE `rooms_users`
  ADD PRIMARY KEY (`rooms_users_id`);

--
-- Indexes for table `ws_users`
--
ALTER TABLE `ws_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dates`
--
ALTER TABLE `dates`
  MODIFY `date_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rooms_users`
--
ALTER TABLE `rooms_users`
  MODIFY `rooms_users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ws_users`
--
ALTER TABLE `ws_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
