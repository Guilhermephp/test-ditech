-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 04-Out-2019 às 05:54
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
(4, 'salas teste', '2019-10-04 05:18:20'),
(5, 'sala', '2019-10-04 07:49:34');

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
(1, 2, 1, 4, '2019-10-04 11:00:00'),
(2, 2, 2, 4, '2019-10-05 12:00:00'),
(3, 2, 7, 5, '2019-10-04 18:00:00');

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
(2, 'Guilhermert', 'Natus', '036.407.480-93', '(51)9999-9999', '(51)99999-9999', 'guilherme@webb.com', '896ae574a13abc5fde48ffcd2c70acd0', '2016-08-31 15:38:23', '2019-10-03 06:12:30', 3, 1, NULL),
(6, 'Cion', 'Cion', '036.407.480-93', '(99) 9999-9999', '(99) 99999-9999', 'contato@agenciacion.com', 'a74f53e0e42f893fb94bd0637d571022', '2018-03-20 12:13:47', '2018-03-20 12:18:06', 3, 1, NULL);

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
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rooms_users`
--
ALTER TABLE `rooms_users`
  MODIFY `rooms_users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ws_users`
--
ALTER TABLE `ws_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
