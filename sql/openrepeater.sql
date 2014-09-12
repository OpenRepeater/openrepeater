-- phpMyAdmin SQL Dump
-- version 4.1.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 25, 2014 at 10:38 PM
-- Server version: 5.5.37-0+wheezy1
-- PHP Version: 5.4.4-14+deb7u10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `repeater`
--

-- --------------------------------------------------------

--
-- Table structure for table `ctcss`
--

CREATE TABLE IF NOT EXISTS `ctcss` (
  `toneFreqHz` decimal(4,1) NOT NULL,
  `code` varchar(3) NOT NULL,
  `icomHam` int(11) NOT NULL,
  `gmrs` int(11) NOT NULL,
  PRIMARY KEY (`toneFreqHz`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ctcss`
--

INSERT INTO `ctcss` (`toneFreqHz`, `code`, `icomHam`, `gmrs`) VALUES
(67.0, 'XZ', 1, 1),
(69.3, '', 0, 0),
(71.9, 'XA', 2, 2),
(74.4, 'WA', 3, 3),
(77.0, 'XB', 4, 4),
(79.7, 'SP', 5, 5),
(82.5, 'YZ', 6, 6),
(85.4, 'YA', 7, 7),
(88.5, 'YB', 8, 8),
(91.5, 'ZZ', 9, 9),
(94.8, 'ZA', 10, 10),
(97.4, 'ZD', 11, 11),
(100.0, '1Z', 12, 12),
(103.5, '1B', 13, 13),
(107.2, '1B', 14, 14),
(110.9, '2Z', 15, 15),
(114.8, '2A', 16, 16),
(118.8, '2B', 17, 17),
(123.0, '3Z', 18, 18),
(127.3, '3A', 19, 19),
(131.8, '3B', 20, 20),
(136.5, '4Z', 21, 21),
(141.3, '4A', 22, 22),
(146.3, '4B', 23, 23),
(151.4, '5Z', 24, 24),
(156.7, '5A', 25, 25),
(159.8, '', 0, 0),
(162.2, '5B', 26, 26),
(165.5, '', 0, 0),
(167.9, '6Z', 27, 27),
(171.3, '', 0, 0),
(173.8, '6A', 28, 28),
(177.3, '', 0, 0),
(179.9, '6B', 29, 29),
(183.5, '', 0, 0),
(186.2, '7Z', 30, 30),
(189.9, '', 0, 0),
(192.8, '7Z', 31, 31),
(196.6, '', 0, 0),
(199.5, '', 0, 0),
(203.5, 'M1', 32, 32),
(206.5, '8Z', 0, 0),
(210.7, 'M2', 33, 33),
(218.1, 'M3', 0, 34),
(225.7, 'M4', 0, 35),
(229.1, '9Z', 0, 0),
(233.6, 'M5', 0, 36),
(241.8, 'M6', 0, 37),
(250.3, 'M7', 0, 38),
(254.1, 'OZ', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `keyID` varchar(25) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`keyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`keyID`, `value`) VALUES
('callSign', 'MYCALL'),
('courtesy', 'Sat Pass.wav'),
('echolink_callSign', 'MYCALL-R'),
('echolink_desc', 'Welcome to the\r\nOpen Repeater\r\nTest Server!'),
('echolink_enabled', 'True'),
('echolink_location', 'OpenRepeater Test Server'),
('echolink_password', '000000'),
('echolink_sysop', 'OpenRepeater'),
('help_enabled', 'True'),
('idLongTimeValueMin', '60'),
('idTimeValueMin', '10'),
('parrot_enabled', 'False'),
('phoneticCallSign', 'False'),
('repeaterTimeoutSec', '240'),
('rxFreq', '144.0'),
('rxTone', '77.0'),
('timeoutMsg', 'Sorry, but the repeater has timed out. Please wait until you hear the repeater reset'),
('txFreq', '144.6'),
('txTailValueSec', '3.3'),
('txTone', '67.0'),
('voiceID', 'This is the %%CALLSIGN%% portable repeater.'),
('voicemail_enabled', 'True');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(28) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `salt`) VALUES
(1, 'admin', '296b3b1cb08596a9d9e9e9c46f92b5092cd42ad86f72c3b2f936aa0c9d6269d3', '46ea926d');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
