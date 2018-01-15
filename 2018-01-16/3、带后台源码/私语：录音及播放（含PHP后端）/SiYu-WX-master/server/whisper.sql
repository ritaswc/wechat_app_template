-- phpMyAdmin SQL Dump
-- version 4.4.14.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-04-24 10:25:28
-- 服务器版本： 5.6.26-log
-- PHP Version: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whisper`
--

-- --------------------------------------------------------

--
-- 表的结构 `t_session`
--

CREATE TABLE IF NOT EXISTS `t_session` (
  `id` int(11) NOT NULL,
  `session` varchar(32) NOT NULL,
  `session_key` varchar(24) NOT NULL,
  `openid` varchar(32) NOT NULL,
  `last_active_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_session`
--

INSERT INTO `t_session` (`id`, `session`, `session_key`, `openid`, `last_active_datetime`) VALUES
(8, 'ba4dbfc8f9e9112692e85e2a48cbea12', 'SvxRFTcXKXqqkV2brd1AHw==', 'oswzr0KVS8zjM1UuGUuef8r_08Gc', '2017-01-16 12:12:00');

-- --------------------------------------------------------

--
-- 表的结构 `t_user_info`
--

CREATE TABLE IF NOT EXISTS `t_user_info` (
  `id` int(11) NOT NULL,
  `openid` varchar(32) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `avatar_url` varchar(200) NOT NULL,
  `gender` int(11) NOT NULL,
  `city` varchar(32) NOT NULL,
  `reg_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `t_user_info`
--

INSERT INTO `t_user_info` (`id`, `openid`, `nickname`, `avatar_url`, `gender`, `city`, `reg_datetime`) VALUES
(1, 'oswzr0KVS8zjM1UuGUuef8r_08Gc', '白蛙', 'http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83erL6iaEl9ge6xHH3Z7ex1r1pGZSkeOkicWXAHMeKQXvPf6ibSzb1srpaZz0j0hAVgesMUial66zpfcPww/0', 1, 'Suqian', '2017-01-14 20:59:31');

-- --------------------------------------------------------

--
-- 表的结构 `t_whisper`
--

CREATE TABLE IF NOT EXISTS `t_whisper` (
  `id` int(11) NOT NULL,
  `poster_openid` varchar(32) NOT NULL,
  `duration` int(11) NOT NULL,
  `tag` varchar(24) NOT NULL,
  `src` varchar(200) NOT NULL,
  `post_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_session`
--
ALTER TABLE `t_session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session` (`session`);

--
-- Indexes for table `t_user_info`
--
ALTER TABLE `t_user_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `openid` (`openid`);

--
-- Indexes for table `t_whisper`
--
ALTER TABLE `t_whisper`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_session`
--
ALTER TABLE `t_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `t_user_info`
--
ALTER TABLE `t_user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_whisper`
--
ALTER TABLE `t_whisper`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
