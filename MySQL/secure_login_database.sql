-- phpMyAdmin SQL Dump
-- version 4.1.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 27, 2014 at 10:32 PM
-- Server version: 5.6.15
-- PHP Version: 5.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `secure_login`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addgametolibrary`(IN `gid` INT, IN `uid` INT, IN `genre` VARCHAR(100), IN `platform` VARCHAR(100), IN `name` VARCHAR(100))
BEGIN
  IF (SELECT COUNT(*) FROM gamelibrary WHERE gameID=gid AND userID=uid)=0 THEN
  INSERT INTO gamelibrary (gameID, userID, genre, platform, name, lentdate, returneddate, status, userborrowid) VALUES ( gid, uid ,genre, platform, name, 0, 0, 0, 0); 
  END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accountValidate`
--

CREATE TABLE IF NOT EXISTS `accountValidate` (
  `email` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accountValidate`
--


-- --------------------------------------------------------

--
-- Table structure for table `bugreport`
--

CREATE TABLE IF NOT EXISTS `bugreport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(19) NOT NULL,
  `iphost` varchar(100) NOT NULL,
  `HTTP_USER_AGENT` varchar(500) NOT NULL,
  `userID` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `shortdecription` varchar(250) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `REQUEST_URI` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bugreport`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(1, 'Alingsås'),
(2, '\n					Arboga\n				'),
(3, '\n					Arvika\n				'),
(4, '\n					Askersund\n				'),
(5, '\n					Avaskär\n				'),
(6, '\n					Avesta\n				'),
(7, '\n					Boden\n				'),
(8, '\n					Bollnäs\n				'),
(9, '\n					Borgholm\n				'),
(10, '\n					Borlänge\n				'),
(11, '\n					Borås\n				'),
(12, '\n					Broo\n				'),
(13, '\n					Brätte\n				'),
(14, '\n					Båstad\n				'),
(15, '\n					Djursholm\n				'),
(16, '\n					Eksjö\n				'),
(17, '\n					Elleholm\n				'),
(18, '\n					Enköping\n				'),
(19, '\n					Eskilstuna\n				'),
(20, '\n					Eslöv\n				'),
(21, '\n					Fagersta\n				'),
(22, '\n					Falkenberg\n				'),
(23, '\n					Falköping\n				'),
(24, '\n					Falsterbo\n				'),
(25, '\n					Falun\n				'),
(26, '\n					Filipstad\n				'),
(27, '\n					Flen\n				'),
(28, '\n					Gränna\n				'),
(29, '\n					Gävle\n				'),
(30, '\n					Göteborg\n				'),
(31, '\n					Hagfors\n				'),
(32, '\n					Halmstad\n				'),
(33, '\n					Haparanda\n				'),
(34, '\n					Hedemora\n				'),
(35, '\n					Helsingborg\n				'),
(36, '\n					Hjo\n				'),
(37, '\n					Hudiksvall\n				'),
(38, '\n					Huskvarna\n				'),
(39, '\n					Härnösand\n				'),
(40, '\n					Hässleholm\n				'),
(41, '\n					Höganäs\n				'),
(42, '\n					Järle (Jerle)\n				'),
(43, '\n					Jönköping\n				'),
(44, '\n					Kalmar\n				'),
(45, '\n					Karlshamn\n				'),
(46, '\n					Karlskoga\n				'),
(47, '\n					Karlskrona\n				'),
(48, '\n					Karlstad\n				'),
(49, '\n					Katrineholm\n				'),
(50, '\n					Kiruna\n				'),
(51, '\n					Kongahälla\n				'),
(52, '\n					Kramfors\n				'),
(53, '\n					Kristianopel\n				'),
(54, '\n					Kristianstad\n				'),
(55, '\n					Kristinehamn\n				'),
(56, '\n					Kumla\n				'),
(57, '\n					Kungsbacka\n				'),
(58, '\n					Kungälv\n				'),
(59, '\n					Köping\n				'),
(60, '\n					Laholm\n				'),
(61, '\n					Landskrona\n				'),
(62, '\n					Lidingö\n				'),
(63, '\n					Lidköping\n				'),
(64, '\n					Lindesberg\n				'),
(65, '\n					Linköping\n				'),
(66, '\n					Ljungby\n				'),
(67, '\n					Lomma\n				'),
(68, '\n					Ludvika\n				'),
(69, '\n					Luntertun\n				'),
(70, '\n					Luleå\n				'),
(71, '\n					Lund\n				'),
(72, '\n					Lycksele\n				'),
(73, '\n					Lyckå\n				'),
(74, '\n					Lysekil\n				'),
(75, '\n					Lödöse\n				'),
(76, '\n					Malmö\n				'),
(77, '\n					Mariefred\n				'),
(78, '\n					Mariestad\n				'),
(79, '\n					Marstrand\n				'),
(80, '\n					Mjölby\n				'),
(81, '\n					Motala\n				'),
(82, '\n					Mölndal\n				'),
(83, '\n					Mönsterås\n				'),
(84, '\n					Nacka\n				'),
(85, '\n					Nora\n				'),
(86, '\n					Norrköping\n				'),
(87, '\n					Norrtälje\n				'),
(88, '\n					Nybro\n				'),
(89, '\n					Nyköping\n				'),
(90, '\n					Nya Lidköping\n				'),
(91, '\n					Nynäshamn\n				'),
(92, '\n					Nässjö\n				'),
(93, '\n					Oskarshamn\n				'),
(94, '\n					Oxelösund\n				'),
(95, '\n					Piteå\n				'),
(96, '\n					Ronneby\n				'),
(97, '\n					sedan 1882\n				'),
(98, '\n					Sala\n				'),
(99, '\n					Sandviken\n				'),
(100, '\n					Sigtuna\n				'),
(101, '\n					Simrishamn\n				'),
(102, '\n					Skanör\n				'),
(103, '\n					Skanör med Falsterbo\n			'),
(104, '\n					Skara\n				'),
(105, '\n					Skellefteå\n				'),
(106, '\n					Skänninge\n				'),
(107, '\n					Skövde\n				'),
(108, '\n					Sollefteå\n				'),
(109, '\n					Solna\n				'),
(110, '\n					Stockholm\n				'),
(111, '\n					Strängnäs\n				'),
(112, '\n					Strömstad\n				'),
(113, '\n					Sundbyberg\n				'),
(114, '\n					Sundsvall\n				'),
(115, '\n					Säffle\n				'),
(116, '\n					Säter\n				'),
(117, '\n					Sävsjö\n				'),
(118, '\n					Söderhamn\n				'),
(119, '\n					Söderköping\n				'),
(120, '\n					Södertälje\n				'),
(121, '\n					Sölvesborg\n				'),
(122, '\n					Tidaholm\n				'),
(123, '\n					Tommarp\n				'),
(124, '\n					Torget\n				'),
(125, '\n					Torshälla\n				'),
(126, '\n					Tranås\n				'),
(127, '\n					Trelleborg\n				'),
(128, '\n					Trollhättan\n				'),
(129, '\n					Trosa\n				'),
(130, '\n					Uddevalla\n				'),
(131, '\n					Ulricehamn\n				'),
(132, '\n					Umeå\n				'),
(133, '\n					Uppsala\n				'),
(134, '\n					Vadstena\n				'),
(135, '\n					Varberg\n				'),
(136, '\n					Vaxholm\n				'),
(137, '\n					Vetlanda\n				'),
(138, '\n					Vimmerby\n				'),
(139, '\n					Visby\n				'),
(140, '\n					Vä\n				'),
(141, '\n					Vänersborg\n				'),
(142, '\n					Värnamo\n				'),
(143, '\n					Västervik\n				'),
(144, '\n					Västerås\n				'),
(145, '\n					Växjö\n				'),
(146, '\n					Ystad\n				'),
(147, '\n					Åhus\n				'),
(148, '\n					Åmål\n				'),
(149, '\n					Älvsborg\n				'),
(150, '\n					Ängelholm\n				'),
(151, '\n					Örebro\n				'),
(152, '\n					Öregrund\n				'),
(153, '\n					Örnsköldsvik\n				'),
(154, '\n					Östersund\n				'),
(155, '\n					Östhammar\n				'),
(156, '\n					Örebro\n				'),
(157, '\n					Öregrund\n				'),
(158, '\n					Örnsköldsvik\n				'),
(159, '\n					Östersund\n				'),
(160, '\n					Östhammar\n				');

-- --------------------------------------------------------

--
-- Table structure for table `consoles`
--

CREATE TABLE IF NOT EXISTS `consoles` (
  `id` int(11) NOT NULL,
  `console` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `consoles`
--

INSERT INTO `consoles` (`id`, `console`) VALUES
(25, '3DO'),
(4911, 'Amiga'),
(4914, 'Amstrad CPC'),
(4916, 'Android'),
(23, 'Arcade'),
(22, 'Atari 2600'),
(26, 'Atari 5200'),
(27, 'Atari 7800'),
(28, 'Atari Jaguar'),
(29, 'Atari Jaguar CD'),
(30, 'Atari XE'),
(31, 'Colecovision'),
(40, 'Commodore 64'),
(32, 'Intellivision'),
(4915, 'IOS'),
(37, 'Mac OS'),
(14, 'Microsoft Xbox'),
(15, 'Microsoft Xbox 360'),
(4920, 'Microsoft Xbox One'),
(4922, 'Neo Geo Pocket'),
(4923, 'Neo Geo Pocket Color'),
(24, 'NeoGeo'),
(4912, 'Nintendo 3DS'),
(3, 'Nintendo 64'),
(8, 'Nintendo DS'),
(7, 'Nintendo Entertainment System (NES)'),
(4, 'Nintendo Game Boy'),
(5, 'Nintendo Game Boy Advance'),
(41, 'Nintendo Game Boy Color'),
(2, 'Nintendo GameCube'),
(4918, 'Nintendo Virtual Boy'),
(9, 'Nintendo Wii'),
(38, 'Nintendo Wii U'),
(4921, 'Ouya'),
(1, 'PC'),
(4917, 'Philips CD-i'),
(33, 'Sega 32X'),
(21, 'Sega CD'),
(16, 'Sega Dreamcast'),
(20, 'Sega Game Gear'),
(18, 'Sega Genesis'),
(35, 'Sega Master System'),
(36, 'Sega Mega Drive'),
(17, 'Sega Saturn'),
(4913, 'Sinclair ZX Spectrum'),
(10, 'Sony Playstation'),
(11, 'Sony Playstation 2'),
(12, 'Sony Playstation 3'),
(4919, 'Sony Playstation 4'),
(39, 'Sony Playstation Vita'),
(13, 'Sony PSP'),
(6, 'Super Nintendo (SNES)'),
(34, 'TurboGrafx 16');

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE IF NOT EXISTS `friend` (
  `userid` int(11) NOT NULL,
  `friend` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `requestFromUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friend`
--

-- --------------------------------------------------------

--
-- Table structure for table `gamelibrary`
--

CREATE TABLE IF NOT EXISTS `gamelibrary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `genre` varchar(200) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `lentdate` datetime NOT NULL,
  `returneddate` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `userborrowid` int(11) NOT NULL,
  `platform` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;

--
-- Dumping data for table `gamelibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE IF NOT EXISTS `genres` (
  `id` int(11) NOT NULL,
  `genre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `genre`) VALUES
(1, 'Action'),
(2, 'Adventure'),
(3, 'Construction and Management Simulation'),
(4, 'Fighting'),
(5, 'Flight Simulator'),
(6, 'Horror'),
(7, 'Life Simulation'),
(8, 'MMO'),
(9, 'Music'),
(10, 'Platform'),
(11, 'Puzzle'),
(12, 'Racing'),
(13, 'Role-Playing'),
(14, 'Sandbox'),
(15, 'Shooter'),
(15, 'Sports'),
(15, 'Stealth'),
(15, 'Strategy');

-- --------------------------------------------------------

--
-- Table structure for table `lentgames`
--

CREATE TABLE IF NOT EXISTS `lentgames` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userlentid` int(11) NOT NULL,
  `userborrowedid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `returned` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `borrowed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lentgames`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_attempts`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `members`
--

-- --------------------------------------------------------

--
-- Table structure for table `queryCache`
--

CREATE TABLE IF NOT EXISTS `queryCache` (
  `id` int(11) NOT NULL,
  `platformID` int(11) NOT NULL,
  `platformName` varchar(50) NOT NULL,
  `genre` varchar(200) NOT NULL,
  `filepath` varchar(100) NOT NULL,
  `gameTitle` varchar(100) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `queryCache`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `userandborrowedgames`
--
CREATE TABLE IF NOT EXISTS `userandborrowedgames` (
`id` int(11)
,`username` varchar(30)
,`firstname` varchar(20)
,`lastname` varchar(30)
,`gameID` int(11)
,`genre` varchar(200)
,`platform` varchar(100)
,`name` varchar(100)
,`lentdate` datetime
,`userID` int(11)
,`status` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `userandgamelibrary`
--
CREATE TABLE IF NOT EXISTS `userandgamelibrary` (
`id` int(11)
,`username` varchar(30)
,`firstname` varchar(20)
,`lastname` varchar(30)
,`gameID` int(11)
,`genre` varchar(200)
,`platform` varchar(100)
,`name` varchar(100)
,`lentdate` datetime
,`userborrowid` int(11)
,`status` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `userconsole`
--

CREATE TABLE IF NOT EXISTS `userconsole` (
  `userid` int(11) NOT NULL,
  `consoleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userconsole`
--

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE IF NOT EXISTS `userdata` (
  `id` int(11) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `city` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `platforms` varchar(100) NOT NULL,
  `img` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdata`
--

-- --------------------------------------------------------

--
-- Table structure for table `userlibrary`
--

CREATE TABLE IF NOT EXISTS `userlibrary` (
  `userid` int(11) NOT NULL,
  `gameid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `usernamesdndid`
--
CREATE TABLE IF NOT EXISTS `usernamesdndid` (
`id` int(11)
,`username` varchar(30)
,`email` varchar(50)
,`firstname` varchar(20)
,`lastname` varchar(30)
,`rating` int(11)
,`city` int(11)
,`platforms` varchar(100)
,`img` varchar(50)
);
-- --------------------------------------------------------

--
-- Structure for view `userandborrowedgames`
--
DROP TABLE IF EXISTS `userandborrowedgames`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `userandborrowedgames` AS select `userdata`.`id` AS `id`,`members`.`username` AS `username`,`userdata`.`firstname` AS `firstname`,`userdata`.`lastname` AS `lastname`,`gamelibrary`.`gameID` AS `gameID`,`gamelibrary`.`genre` AS `genre`,`gamelibrary`.`platform` AS `platform`,`gamelibrary`.`name` AS `name`,`gamelibrary`.`lentdate` AS `lentdate`,`gamelibrary`.`userID` AS `userID`,`gamelibrary`.`status` AS `status` from ((`members` join `userdata`) join `gamelibrary`) where ((`members`.`id` = `userdata`.`id`) and (`members`.`id` = `gamelibrary`.`userborrowid`) and (`userdata`.`id` = `gamelibrary`.`userborrowid`));

-- --------------------------------------------------------

--
-- Structure for view `userandgamelibrary`
--
DROP TABLE IF EXISTS `userandgamelibrary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `userandgamelibrary` AS select `userdata`.`id` AS `id`,`members`.`username` AS `username`,`userdata`.`firstname` AS `firstname`,`userdata`.`lastname` AS `lastname`,`gamelibrary`.`gameID` AS `gameID`,`gamelibrary`.`genre` AS `genre`,`gamelibrary`.`platform` AS `platform`,`gamelibrary`.`name` AS `name`,`gamelibrary`.`lentdate` AS `lentdate`,`gamelibrary`.`userborrowid` AS `userborrowid`,`gamelibrary`.`status` AS `status` from ((`members` join `userdata`) join `gamelibrary`) where ((`members`.`id` = `userdata`.`id`) and (`members`.`id` = `gamelibrary`.`userID`) and (`userdata`.`id` = `gamelibrary`.`userID`));

-- --------------------------------------------------------

--
-- Structure for view `usernamesdndid`
--
DROP TABLE IF EXISTS `usernamesdndid`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usernamesdndid` AS select `userdata`.`id` AS `id`,`members`.`username` AS `username`,`members`.`email` AS `email`,`userdata`.`firstname` AS `firstname`,`userdata`.`lastname` AS `lastname`,`userdata`.`rating` AS `rating`,`userdata`.`city` AS `city`,`userdata`.`platforms` AS `platforms`,`userdata`.`img` AS `img` from (`members` join `userdata`) where (`members`.`id` = `userdata`.`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
