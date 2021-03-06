-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2018 at 06:23 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `market`
--

-- --------------------------------------------------------

--
-- Table structure for table `kite_margin`
--

CREATE TABLE `kite_margin` (
  `id` int(11) NOT NULL,
  `Multiplier` tinyint(4) NOT NULL,
  `Scrip` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kite_margin`
--

INSERT INTO `kite_margin` (`id`, `Multiplier`, `Scrip`) VALUES
(1, 3, '3MINDIA'),
(2, 3, 'AARTIIND'),
(3, 3, 'ABAN'),
(4, 3, 'ABB'),
(5, 3, 'ABFRL'),
(6, 14, 'ACC'),
(7, 3, 'ADANIENT'),
(8, 3, 'ADANIPORTS'),
(9, 5, 'ADANIPOWER'),
(10, 8, 'AJANTPHARM'),
(11, 3, 'AKZOINDIA'),
(12, 5, 'ALBK'),
(13, 3, 'ALKEM'),
(14, 3, 'ALLCARGO'),
(15, 14, 'AMARAJABAT'),
(16, 14, 'AMBUJACEM'),
(17, 3, 'ANDHRABANK'),
(18, 14, 'APOLLOHOSP'),
(19, 5, 'APOLLOTYRE'),
(20, 14, 'ARVIND'),
(21, 3, 'ASAHIINDIA'),
(22, 14, 'ASHOKLEY'),
(23, 14, 'ASIANPAINT'),
(24, 3, 'ASTRAZEN'),
(25, 3, 'ATFL'),
(26, 3, 'ATUL'),
(27, 3, 'AUBANK'),
(28, 7, 'AUROPHARMA'),
(29, 3, 'AUTOAXLES'),
(30, 14, 'AXISBANK'),
(31, 14, 'BAJAJ-AUTO'),
(32, 3, 'BAJAJCORP'),
(33, 3, 'BAJAJELEC'),
(34, 3, 'BAJAJFINSV'),
(35, 3, 'BAJAJHLDNG'),
(36, 7, 'BAJFINANCE'),
(37, 3, 'BALKRISIND'),
(38, 3, 'BALRAMCHIN'),
(39, 3, 'BANCOINDIA'),
(40, 14, 'BANKBARODA'),
(41, 10, 'BANKBEES'),
(42, 7, 'BANKINDIA'),
(43, 14, 'BATAINDIA'),
(44, 9, 'BEL'),
(45, 9, 'BEML'),
(46, 3, 'BERGEPAINT'),
(47, 8, 'BHARATFIN'),
(48, 14, 'BHARATFORG'),
(49, 14, 'BHARTIARTL'),
(50, 11, 'BHEL'),
(51, 9, 'BIOCON'),
(52, 3, 'BLUEDART'),
(53, 14, 'BOSCHLTD'),
(54, 3, 'BPCL'),
(55, 11, 'BRITANNIA'),
(56, 3, 'BRNL'),
(57, 3, 'BSE'),
(58, 9, 'CADILAHC'),
(59, 14, 'CANBK'),
(60, 3, 'CANFINHOME'),
(61, 3, 'CAPACITE'),
(62, 3, 'CAPF'),
(63, 14, 'CASTROLIND'),
(64, 3, 'CDSL'),
(65, 14, 'CEATLTD'),
(66, 3, 'CENTURYPLY'),
(67, 8, 'CENTURYTEX'),
(68, 9, 'CESC'),
(69, 8, 'CGPOWER'),
(70, 3, 'CHENNPETRO'),
(71, 3, 'CHOLAFIN'),
(72, 14, 'CIPLA'),
(73, 7, 'COALINDIA'),
(74, 3, 'COCHINSHIP'),
(75, 14, 'COLPAL'),
(76, 9, 'CONCOR'),
(77, 3, 'COROMANDEL'),
(78, 3, 'COX&KINGS'),
(79, 3, 'CRISIL'),
(80, 3, 'CROMPTON'),
(81, 3, 'CUB'),
(82, 3, 'CUMMINSIND'),
(83, 3, 'CYIENT'),
(84, 14, 'DABUR'),
(85, 3, 'DALMIABHA'),
(86, 3, 'DBCORP'),
(87, 14, 'DCBBANK'),
(88, 3, 'DCMSHRIRAM'),
(89, 3, 'DHFL'),
(90, 3, 'DIAMONDYD'),
(91, 9, 'DISHTV'),
(92, 7, 'DIVISLAB'),
(93, 3, 'DIXON'),
(94, 5, 'DLF'),
(95, 3, 'DMART'),
(96, 7, 'DRREDDY'),
(97, 3, 'ECLERX'),
(98, 11, 'EICHERMOT'),
(99, 3, 'EIDPARRY'),
(100, 3, 'EMAMILTD'),
(101, 3, 'ENDURANCE'),
(102, 11, 'ENGINERSIN'),
(103, 3, 'EQUITAS'),
(104, 3, 'ERIS'),
(105, 3, 'ESCORTS'),
(106, 3, 'EVEREADY'),
(107, 11, 'EXIDEIND'),
(108, 14, 'FEDERALBNK'),
(109, 3, 'FEL'),
(110, 3, 'FINCABLES'),
(111, 3, 'FORTIS'),
(112, 3, 'FRETAIL'),
(113, 3, 'GAIL'),
(114, 3, 'GANECOS'),
(115, 3, 'GDL'),
(116, 3, 'GEPIL'),
(117, 3, 'GESHIP'),
(118, 3, 'GICHSGFIN'),
(119, 3, 'GICRE'),
(120, 3, 'GILLETTE'),
(121, 3, 'GLAXO'),
(122, 11, 'GLENMARK'),
(123, 5, 'GMRINFRA'),
(124, 3, 'GNA'),
(125, 3, 'GNFC'),
(126, 3, 'GODFRYPHLP'),
(127, 3, 'GODREJCP'),
(128, 7, 'GODREJIND'),
(129, 3, 'GODREJPROP'),
(130, 10, 'GOLDBEES'),
(131, 3, 'GPPL'),
(132, 10, 'GRANULES'),
(133, 11, 'GRASIM'),
(134, 3, 'GREAVESCOT'),
(135, 3, 'GRUH'),
(136, 3, 'GSFC'),
(137, 3, 'GSKCONS'),
(138, 3, 'GSPL'),
(139, 3, 'GUJALKALI'),
(140, 3, 'GUJFLUORO'),
(141, 3, 'GUJGASLTD'),
(142, 9, 'HAVELLS'),
(143, 3, 'HCC'),
(144, 9, 'HCLTECH'),
(145, 14, 'HDFC'),
(146, 5, 'HDFCAMC'),
(147, 14, 'HDFCBANK'),
(148, 3, 'HEIDELBERG'),
(149, 14, 'HEROMOTOCO'),
(150, 11, 'HEXAWARE'),
(151, 3, 'HGS'),
(152, 3, 'HIKAL'),
(153, 3, 'HINDALCO'),
(154, 3, 'HINDPETRO'),
(155, 14, 'HINDUNILVR'),
(156, 11, 'HINDZINC'),
(157, 3, 'HONAUT'),
(158, 3, 'HSIL'),
(159, 3, 'HUDCO'),
(160, 3, 'IBREALEST'),
(161, 3, 'IBULHSGFIN'),
(162, 14, 'ICICIBANK'),
(163, 3, 'ICICIGI'),
(164, 3, 'ICICIPRULI'),
(165, 3, 'ICIL'),
(166, 5, 'IDBI'),
(167, 7, 'IDEA'),
(168, 5, 'IDFC'),
(169, 5, 'IDFCBANK'),
(170, 3, 'IEX'),
(171, 5, 'IFCI'),
(172, 14, 'IGL'),
(173, 3, 'IGPL'),
(174, 3, 'IIFL'),
(175, 3, 'IL&FSTRANS'),
(176, 3, 'INDHOTEL'),
(177, 11, 'INDIACEM'),
(178, 3, 'INDIANB'),
(179, 3, 'INDIGO'),
(180, 14, 'INDUSINDBK'),
(181, 2, 'INFIBEAM'),
(182, 9, 'INFRATEL'),
(183, 9, 'INFY'),
(184, 3, 'INOXLEISUR'),
(185, 3, 'INOXWIND'),
(186, 3, 'INTELLECT'),
(187, 3, 'IOB'),
(188, 3, 'IOC'),
(189, 3, 'IPCALAB'),
(190, 9, 'IRB'),
(191, 14, 'ITC'),
(192, 3, 'JAGRAN'),
(193, 3, 'JAMNAAUTO'),
(194, 3, 'JETAIRWAYS'),
(195, 8, 'JINDALSTEL'),
(196, 11, 'JISLJALEQS'),
(197, 3, 'JKCEMENT'),
(198, 3, 'JKTYRE'),
(199, 3, 'JPASSOCIAT'),
(200, 3, 'JSL'),
(201, 3, 'JSLHISAR'),
(202, 5, 'JSWENERGY'),
(203, 14, 'JSWSTEEL'),
(204, 3, 'JUBILANT'),
(205, 11, 'JUBLFOOD'),
(206, 3, 'JUSTDIAL'),
(207, 3, 'KAJARIACER'),
(208, 3, 'KALPATPOWR'),
(209, 3, 'KANSAINER'),
(210, 3, 'KARURVYSYA'),
(211, 3, 'KEC'),
(212, 3, 'KEI'),
(213, 3, 'KESORAMIND'),
(214, 3, 'KHADIM'),
(215, 14, 'KOTAKBANK'),
(216, 3, 'KOTAKNIFTY'),
(217, 3, 'KPIT'),
(218, 13, 'KSCL'),
(219, 14, 'KTKBANK'),
(220, 11, 'L&TFH'),
(221, 3, 'LALPATHLAB'),
(222, 3, 'LGBBROSLTD'),
(223, 11, 'LICHSGFIN'),
(224, 3, 'LINDEINDIA'),
(225, 10, 'LIQUIDBEES'),
(226, 3, 'LOVABLE'),
(227, 14, 'LT'),
(228, 11, 'LUPIN'),
(229, 14, 'M&M'),
(230, 11, 'M&MFIN'),
(231, 3, 'M100'),
(232, 3, 'M50'),
(233, 3, 'MAGMA'),
(234, 3, 'MAHINDCIE'),
(235, 3, 'MAHLOG'),
(236, 3, 'MAJESCO'),
(237, 3, 'MANAPPURAM'),
(238, 3, 'MANGALAM'),
(239, 3, 'MANINFRA'),
(240, 11, 'MARICO'),
(241, 3, 'MARKSANS'),
(242, 14, 'MARUTI'),
(243, 3, 'MASFIN'),
(244, 3, 'MATRIMONY'),
(245, 11, 'MCDOWELL-N'),
(246, 11, 'MCLEODRUSS'),
(247, 3, 'MCX'),
(248, 3, 'MEGH'),
(249, 3, 'MERCATOR'),
(250, 3, 'MFSL'),
(251, 3, 'MGL'),
(252, 3, 'MINDAIND'),
(253, 9, 'MINDTREE'),
(254, 3, 'MOIL'),
(255, 3, 'MOLDTKPAC'),
(256, 11, 'MOTHERSUMI'),
(257, 3, 'MPHASIS'),
(258, 11, 'MRF'),
(259, 3, 'MRPL'),
(260, 3, 'MUKANDLTD'),
(261, 3, 'MUTHOOTFIN'),
(262, 3, 'NATIONALUM'),
(263, 3, 'NAUKRI'),
(264, 3, 'NBCC'),
(265, 11, 'NCC'),
(266, 3, 'NESTLEIND'),
(267, 3, 'NETWORK18'),
(268, 3, 'NFL'),
(269, 3, 'NH'),
(270, 5, 'NHPC'),
(271, 3, 'NIACL'),
(272, 3, 'NIF100IWIN'),
(273, 10, 'NIFTYBEES'),
(274, 3, 'NIFTYIWIN'),
(275, 3, 'NIITLTD'),
(276, 9, 'NIITTECH'),
(277, 3, 'NLCINDIA'),
(278, 11, 'NMDC'),
(279, 3, 'NOCIL'),
(280, 3, 'NRBBEARING'),
(281, 11, 'NTPC'),
(282, 3, 'OBEROIRLTY'),
(283, 8, 'OFSS'),
(284, 5, 'OIL'),
(285, 3, 'OMAXE'),
(286, 3, 'ONGC'),
(287, 7, 'ORIENTBANK'),
(288, 3, 'ORIENTCEM'),
(289, 9, 'PAGEIND'),
(290, 3, 'PARAGMILK'),
(291, 3, 'PCJEWELLER'),
(292, 3, 'PEL'),
(293, 3, 'PERSISTENT'),
(294, 14, 'PETRONET'),
(295, 9, 'PFC'),
(296, 3, 'PFIZER'),
(297, 3, 'PGHH'),
(298, 3, 'PHOENIXLTD'),
(299, 11, 'PIDILITIND'),
(300, 3, 'PIIND'),
(301, 5, 'PNB'),
(302, 3, 'PNBHOUSING'),
(303, 3, 'POLYPLEX'),
(304, 14, 'POWERGRID'),
(305, 3, 'PRESTIGE'),
(306, 11, 'PTC'),
(307, 3, 'PVR'),
(308, 3, 'QUICKHEAL'),
(309, 3, 'RADIOCITY'),
(310, 3, 'RAJESHEXPO'),
(311, 3, 'RALLIS'),
(312, 3, 'RAMCOCEM'),
(313, 3, 'RAMCOIND'),
(314, 5, 'RAYMOND'),
(315, 3, 'RBLBANK'),
(316, 3, 'RCF'),
(317, 3, 'RCOM'),
(318, 11, 'RECLTD'),
(319, 3, 'RELAXO'),
(320, 9, 'RELCAPITAL'),
(321, 14, 'RELIANCE'),
(322, 9, 'RELINFRA'),
(323, 3, 'REPCOHOME'),
(324, 3, 'RICOAUTO'),
(325, 3, 'RKFORGE'),
(326, 3, 'RNAM'),
(327, 3, 'ROLTA'),
(328, 5, 'RPOWER'),
(329, 3, 'SADBHAV'),
(330, 5, 'SAIL'),
(331, 3, 'SALASAR'),
(332, 3, 'SANGHIIND'),
(333, 3, 'SANOFI'),
(334, 3, 'SAREGAMA'),
(335, 3, 'SBILIFE'),
(336, 14, 'SBIN'),
(337, 3, 'SCHAND'),
(338, 3, 'SCI'),
(339, 3, 'SHANKARA'),
(340, 3, 'SHARDAMOTR'),
(341, 3, 'SHRIRAMCIT'),
(342, 3, 'SICAL'),
(343, 9, 'SIEMENS'),
(344, 3, 'SINTEX'),
(345, 3, 'SIS'),
(346, 3, 'SJVN'),
(347, 3, 'SKFINDIA'),
(348, 3, 'SNOWMAN'),
(349, 3, 'SOBHA'),
(350, 3, 'SOLARINDS'),
(351, 5, 'SOUTHBANK'),
(352, 3, 'SPARC'),
(353, 3, 'SREINFRA'),
(354, 11, 'SRF'),
(355, 11, 'SRTRANSFIN'),
(356, 7, 'STAR'),
(357, 3, 'SUNDARMFIN'),
(358, 3, 'SUNDRMFAST'),
(359, 7, 'SUNPHARMA'),
(360, 3, 'SUNTECK'),
(361, 5, 'SUNTV'),
(362, 3, 'SUPREMEIND'),
(363, 3, 'SUZLON'),
(364, 3, 'SYMPHONY'),
(365, 5, 'SYNDIBANK'),
(366, 3, 'SYNGENE'),
(367, 11, 'TATACHEM'),
(368, 3, 'TATACOFFEE'),
(369, 9, 'TATACOMM'),
(370, 9, 'TATAELXSI'),
(371, 9, 'TATAGLOBAL'),
(372, 3, 'TATAINVEST'),
(373, 11, 'TATAMOTORS'),
(374, 11, 'TATAMTRDVR'),
(375, 9, 'TATAPOWER'),
(376, 3, 'TATASPONGE'),
(377, 14, 'TATASTEEL'),
(378, 3, 'TBZ'),
(379, 3, 'TCI'),
(380, 9, 'TCS'),
(381, 9, 'TECHM'),
(382, 3, 'TEJASNET'),
(383, 3, 'TEXRAIL'),
(384, 3, 'THERMAX'),
(385, 3, 'THOMASCOOK'),
(386, 3, 'THYROCARE'),
(387, 3, 'TIFIN'),
(388, 3, 'TIRUMALCHM'),
(389, 11, 'TITAN'),
(390, 3, 'TNPETRO'),
(391, 3, 'TNPL'),
(392, 7, 'TORNTPHARM'),
(393, 8, 'TORNTPOWER'),
(394, 3, 'TRENT'),
(395, 3, 'TTKPRESTIG'),
(396, 5, 'TV18BRDCST'),
(397, 11, 'TVSMOTOR'),
(398, 11, 'UBL'),
(399, 3, 'UCOBANK'),
(400, 3, 'UJJIVAN'),
(401, 11, 'ULTRACEMCO'),
(402, 13, 'UNIONBANK'),
(403, 11, 'UPL'),
(404, 11, 'VEDL'),
(405, 3, 'VGUARD'),
(406, 3, 'VIJAYABANK'),
(407, 9, 'VOLTAS'),
(408, 3, 'VTL'),
(409, 3, 'WABCOINDIA'),
(410, 3, 'WELENT'),
(411, 3, 'WHIRLPOOL'),
(412, 9, 'WIPRO'),
(413, 7, 'WOCKPHARMA'),
(414, 3, 'WONDERLA'),
(415, 3, 'YESBANK'),
(416, 7, 'ZEEL');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kite_margin`
--
ALTER TABLE `kite_margin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kite_margin`
--
ALTER TABLE `kite_margin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=417;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
