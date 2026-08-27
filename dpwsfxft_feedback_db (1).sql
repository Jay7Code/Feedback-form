-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 25, 2026 at 04:18 PM
-- Server version: 10.11.18-MariaDB-cll-lve
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dpwsfxft_feedback_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL DEFAULT '',
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `must_change_password` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `is_active`, `created_at`, `updated_at`, `must_change_password`) VALUES
(1, 'Admin', '$2y$10$s0x2Xf9NQEJiXpNW5ASLl.33SPZZSNU/gOKB23EO88351irfKs.3i', 'Administrator', 1, '2026-03-26 03:27:51', '2026-04-25 04:53:14', 0),
(2, 'jfmarquez', '$2y$10$QolpuWzQzXVd65Yt85QSOuwa2zATI5.FCl96IWjWwDC8aqm9gqqH2', 'John Victor Marquez', 1, '2026-04-01 08:30:47', '2026-04-01 08:30:47', 0),
(3, 'Jason', '$2y$10$o6lHeP.u7iB5g.XNc7mFZOO.q/XMN8Ythgk6F0Ygy2WKdfupdM19K', 'Jason Fernandez', 1, '2026-04-02 02:05:24', '2026-04-02 02:50:21', 0),
(5, 'Jaynielle', '$2y$10$u1lGhVDrKeOoolnBMZPkZO/kRnhO.nCq9a.e6ERoHomiFpmkh3nC6', 'Jaynielle Fortes', 1, '2026-04-07 01:44:17', '2026-04-07 01:44:17', 0),
(7, 'Chloe', '$2y$10$t6s8x1eGPy.9MFCrQUWJKe.kEFTiHLH9pv8J46aKMDppRH77mrFAC', 'Chloe Wasing', 1, '2026-04-10 05:56:46', '2026-04-10 07:55:42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ef_admin_users`
--

CREATE TABLE `ef_admin_users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ef_admin_users`
--

INSERT INTO `ef_admin_users` (`id`, `full_name`, `email`, `username`, `password`, `is_active`, `created_at`) VALUES
(1, 'Administrator', 'administrator09@gmail.com', 'admin', '$2y$10$eRiUAP3y7to/Ujqff2Ezh.5it.1uPQwkDTM1wu9qmzgv4TUJk9HQq', 1, '2026-03-29 00:06:56'),
(2, 'Nathan Bravo', 'than21321@gmail.com', 'than', '$2y$10$VYTYn7LytFNs2ifwsMg/t.jcde.2lEsvS7gURM3sFMCbrwDCUSZdO', 1, '2026-04-05 03:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `ef_attendees`
--

CREATE TABLE `ef_attendees` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `attendee_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ef_attendees`
--

INSERT INTO `ef_attendees` (`id`, `event_id`, `attendee_name`, `email`, `contact_no`, `created_at`) VALUES
(1, 1, 'Juan Dela Cruz', 'arjayvelasco09@gmail.com', '0931231423', '2026-03-29 00:08:37'),
(2, 2, 'Post Malone', 'arjayvelasco09@gmail.com', '0939172341', '2026-03-29 00:11:56'),
(3, 3, 'One direction', 'arjayvelasco09@gmail.com', '093124123', '2026-03-29 00:13:54'),
(4, 4, 'John Hay Hotels Org', 'arjayvelasco09@gmail.com', '09321423114', '2026-03-29 01:13:18'),
(5, 5, 'JHH company', 'jhh@gmail.com', '0931231242', '2026-03-29 03:29:10'),
(6, 6, 'BTG ph', 'arjayvelasco09@gmail.com', '0987681323', '2026-04-01 02:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `ef_events`
--

CREATE TABLE `ef_events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ef_events`
--

INSERT INTO `ef_events` (`id`, `event_name`, `event_date`, `event_time`, `location_id`, `created_at`) VALUES
(1, 'Mancom', '2026-03-30', '10:07:00', 2, '2026-03-29 00:08:37'),
(2, 'Marvel general assembly', '2026-03-25', '08:00:00', 3, '2026-03-29 00:11:56'),
(3, 'Shopee Express Meeting', '2026-03-31', '12:00:00', 3, '2026-03-29 00:13:54'),
(4, 'John Hay Hotels Got Talent', '2026-03-24', '14:00:00', 1, '2026-03-29 01:13:18'),
(5, 'Mancom 2', '2026-03-30', '08:00:00', 2, '2026-03-29 03:29:10'),
(6, 'Team Building PH', '2026-04-10', '08:00:00', 2, '2026-04-01 02:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `ef_event_feedbacks`
--

CREATE TABLE `ef_event_feedbacks` (
  `id` int(11) NOT NULL,
  `attendee_id` int(11) NOT NULL,
  `event_planning` tinyint(4) NOT NULL DEFAULT 0,
  `speaker_effectiveness` tinyint(4) NOT NULL DEFAULT 0,
  `venue_setup` tinyint(4) NOT NULL DEFAULT 0,
  `time_management` tinyint(4) NOT NULL DEFAULT 0,
  `audience_participation` tinyint(4) NOT NULL DEFAULT 0,
  `overall_experience` tinyint(4) NOT NULL DEFAULT 0,
  `food_beverages` tinyint(4) NOT NULL DEFAULT 0,
  `technical_support` tinyint(4) NOT NULL DEFAULT 0,
  `effective_aspects` text DEFAULT NULL,
  `improvement_suggestions` text DEFAULT NULL,
  `participate_future` varchar(10) DEFAULT NULL,
  `additional_feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ef_event_feedbacks`
--

INSERT INTO `ef_event_feedbacks` (`id`, `attendee_id`, `event_planning`, `speaker_effectiveness`, `venue_setup`, `time_management`, `audience_participation`, `overall_experience`, `food_beverages`, `technical_support`, `effective_aspects`, `improvement_suggestions`, `participate_future`, `additional_feedback`, `created_at`) VALUES
(1, 1, 3, 3, 4, 2, 2, 4, 3, 5, 'test', 'test', 'No', 'test', '2026-03-29 00:08:38'),
(2, 2, 2, 4, 3, 3, 5, 4, 2, 3, '', '', 'Yes', 'Yes', '2026-03-29 00:11:56'),
(3, 3, 2, 4, 5, 4, 3, 4, 3, 3, 'test', 'test', 'Yes', 'test', '2026-03-29 00:13:54'),
(4, 4, 1, 1, 1, 1, 1, 1, 1, 1, 'test', 'test', 'No', 'test', '2026-03-29 01:13:18'),
(5, 5, 3, 3, 3, 3, 3, 3, 3, 3, 'test', 'test', 'No', 'test', '2026-03-29 03:29:10'),
(6, 6, 5, 5, 5, 5, 5, 5, 5, 5, 'The ambiance is magnificent.', 'Add more speakers.', 'Yes', 'More variety of foods', '2026-04-01 02:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `ef_locations`
--

CREATE TABLE `ef_locations` (
  `id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ef_locations`
--

INSERT INTO `ef_locations` (`id`, `location_name`, `created_at`) VALUES
(1, '19th T', '2026-03-29 00:04:23'),
(2, 'Adivay Hall', '2026-03-29 00:04:23'),
(3, 'St. Patrick\'s', '2026-03-29 00:04:23');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `stay_id` int(11) NOT NULL,
  `overall_rating` tinyint(4) NOT NULL DEFAULT 0,
  `general_comments` text DEFAULT NULL,
  `repeat_visit` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `stay_id`, `overall_rating`, `general_comments`, `repeat_visit`, `created_at`) VALUES
(23, 23, 5, 'We loved our stay here.', 'Yes', '2026-03-28 02:42:06'),
(25, 25, 4, 'bathrooms need to be updated. fans must be cleaned properly. AC option for summer', 'Yes', '2026-04-04 01:23:40'),
(26, 26, 5, 'Had a pleasant experience. Beautiful property amidst nature', 'Yes', '2026-04-04 02:40:59'),
(27, 27, 5, 'Clarification of morning availability', 'Yes', '2026-04-04 02:51:05'),
(28, 28, 5, 'I smelled strong detergent or something when we entered the room after check in.', 'Yes', '2026-04-04 03:18:14'),
(30, 30, 4, 'Aa', 'Yes', '2026-04-04 04:30:11'),
(31, 31, 4, 'Improve on food menu , limited options', 'Yes', '2026-04-04 10:51:33'),
(33, 33, 4, 'Bath mats weren’t available since hotel was full, according to front desk. Towels weren’t available when we arrived as well, had to request them. Would have been nice to have both items readily available since they were part of what we’ve paid for.', 'Yes', '2026-04-05 02:29:03'),
(34, 34, 5, 'They are very accommodating and also entertained my autistic son heheh im grateful', 'Yes', '2026-04-05 02:45:37'),
(35, 35, 4, '', 'Yes', '2026-04-05 03:00:13'),
(36, 36, 5, 'Thank u', 'Yes', '2026-04-05 03:33:48'),
(38, 38, 5, 'Please be mindful of the number of toiletries requires per room accomodation. We are actually 3 adults, but the set of toiletries are for two only. We needed to request for additional set which was not provided inmediately.', 'Yes', '2026-04-06 03:39:12'),
(39, 39, 5, 'The air conditioning add on is perfect for jot summer months', 'Yes', '2026-04-09 02:34:03'),
(40, 40, 3, 'Waiters should be attentive upon customer’s request', 'Yes', '2026-04-09 03:07:34'),
(41, 41, 3, 'Information pamphlet in the room about hotel facilities and breakfast.', 'Yes', '2026-04-09 03:17:06'),
(42, 42, 5, 'Perfect', 'Yes', '2026-04-09 03:28:04'),
(43, 43, 4, 'None', 'Yes', '2026-04-09 04:26:13'),
(44, 44, 4, 'King size bed room feels tight compared to the Queen double bed accommodation. But location is very quiet and nice views of forest.', 'Yes', '2026-04-09 04:48:57'),
(45, 45, 4, 'King size bed room feels tight compared to the Queen double bed accommodation. But location is very quiet and nice views of forest.', 'Yes', '2026-04-09 04:49:37'),
(46, 46, 4, 'Airconditioning/cooling in the rooms have to be improved. \r\nI stayed on deluxe room sunrise side (room 557). Room temperature evening 26+ and 31+ (Celsius) daytime. On evening/1st night- hotel front desk helped to fix cooling units with Engineering team. And same thing on day 2/evening 2. Room was still warm. My comfort level/sleep in the evening was horrible and uncomfortable as everything was annoying since temperature in the room was warm even with screen doors balcony open. I needed to move rooms as I had a booking for 1bedroom suite. Day/evening 3: Im very thankful to Chloe who helped to provide complimentary upgrade for my 2nights so I can have same room for my 5th-6th night as I did not want to keep moving rooms due to the cooling issue.  I was moved to room on forest side, and I requested for Engineering to arrange cooling unit and the great thing was that they put a duct attachment to the cooling unit &amp; windows (which Im guessing is for the warm air). That made such a difference that I can have restful sleep on 3rd evening. And not get annoyed/uncomfortable as I had a total of 6nights with family/2other adults joining me for 2nights.\r\nMany thanks to Chloe Wasing for prioritizing my concerns and resolving the issue and provide good client service.', 'Yes', '2026-04-09 08:18:14'),
(47, 47, 5, 'Better bedroom slippers perhaps', 'Yes', '2026-04-10 02:37:07'),
(48, 48, 5, 'Nothing', 'Yes', '2026-04-10 03:04:32'),
(49, 49, 5, 'Our overall stay was excellent', 'Yes', '2026-04-10 03:35:40'),
(50, 50, 4, 'keep it up.', 'Yes', '2026-04-18 02:30:59'),
(51, 51, 5, 'Good stay in Baguio', 'Yes', '2026-04-18 02:44:19'),
(52, 52, 5, 'None', 'Yes', '2026-04-18 03:20:36'),
(53, 53, 5, 'Yes', 'Yes', '2026-04-18 04:08:50'),
(54, 54, 5, 'None', 'Yes', '2026-04-18 04:31:29'),
(55, 55, 4, 'keep it up.', 'Yes', '2026-04-18 09:58:32'),
(56, 56, 5, 'N/A', 'Yes', '2026-04-20 02:34:37'),
(57, 57, 5, 'Better welcome drinks', 'Yes', '2026-05-10 00:30:06'),
(58, 58, 3, 'First, need to honor requests or reply faster for 3rd party sites when rooms are available for reservations instead of cancelling and raising rates.\r\nSecond need to make sure room keys actually work before giving them to the client, test them at front desk using programming encoder.\r\nStock room with towels for x amount of persons before check in, including hand towels on towel rings and face towels with bath towels.\r\nClean and clear room of spider webs and dust doors, door frames and shower frames\r\nFix sliding doors slides and door overall length for upstairs closet \r\nroom 480... \r\nFix hot water pipe pressure noise on upstairs of room 480\r\n Need to install a hot water circ pump and grundfos 367010 Thermal Bypass Bridge Valve. \r\nNeed to replace the sleeper couch or steam clean the cushions in room 480.\r\nOtherwise we love the groundskeeping, the staff, the ambiance of the hotel but the room lacked cleanliness and standard amenities for the pricing.\r\nAlso why complimentary beverages and games at the bar only on your arrival day 10-5, when you can&#039;t check in until 3 only allowing 2 hours... Should be more like 3 pm to 9 pm and remove the not available dart game.', 'No', '2026-05-10 05:48:17'),
(59, 59, 4, 'None', 'Yes', '2026-05-14 22:09:18'),
(60, 60, 5, 'none', 'Yes', '2026-05-17 02:42:22'),
(61, 61, 5, 'N/a', 'Yes', '2026-05-20 03:14:40'),
(62, 62, 5, 'Nothing', 'Yes', '2026-05-21 00:12:32'),
(63, 63, 5, 'None that come to mind', 'Yes', '2026-05-23 01:08:18'),
(64, 64, 5, '', 'Yes', '2026-05-23 02:49:39'),
(65, 65, 4, '.', 'Yes', '2026-05-23 03:14:17'),
(66, 66, 4, '.', 'Yes', '2026-05-23 11:30:46'),
(67, 67, 5, '', 'Yes', '2026-05-23 22:08:33'),
(68, 68, 5, '-', 'Yes', '2026-05-24 02:10:17'),
(69, 69, 5, 'None', 'Yes', '2026-06-06 02:14:04'),
(70, 70, 5, 'I gave the toilet facilities a poor rating because the bathroom in room 555 has an unpleasant odor. I think it’s due to plumbing issues. Please look into this. At times it smelled like a public toilet.', 'Yes', '2026-06-07 04:07:34'),
(71, 71, 5, 'N/A', 'Yes', '2026-06-13 22:58:27'),
(72, 72, 5, 'Swimming pool', 'Yes', '2026-06-14 00:40:29'),
(73, 73, 4, 'Carpet could use deep cleaning.\r\n\r\nHad no floor and hand towels', 'Yes', '2026-06-14 00:53:39'),
(74, 74, 5, 'It was just we thought it has a free breakfast', 'Yes', '2026-06-14 01:01:16'),
(75, 75, 5, 'Service par excellence', 'Yes', '2026-06-14 01:09:53'),
(76, 76, 4, 'Very welcoming.', 'Yes', '2026-06-14 01:25:47'),
(77, 77, 5, 'The hotel and the service is great however there are times the electricity shuts down and the elevator doesn’t work so we had to use the stairs. Also, there’s a foul smell when you enter the lower ground from the elevator. I think it’s coming from the service area or from one of the nearest washrooms in the area.', 'Yes', '2026-06-14 01:31:31'),
(78, 78, 5, 'More dining room staff. \r\n\r\nInclude coffee and tea as welcome drinks', 'Yes', '2026-06-14 01:35:55'),
(79, 79, 5, 'The air conditioning', 'Yes', '2026-06-14 02:31:00'),
(80, 80, 5, 'The air conditioning', 'Yes', '2026-06-14 05:42:49'),
(81, 81, 5, 'None', 'Yes', '2026-06-20 03:07:24'),
(82, 82, 5, 'None at the momennt', 'Yes', '2026-06-20 03:15:54'),
(83, 83, 5, 'No. Very much satisfied with my stay.', 'Yes', '2026-06-20 03:25:12'),
(84, 84, 5, 'We were booked on the connecting room and the neighbor guest were very inconsiderate and loud - we actually reported it since it sounded like some furnitures were banging and sounded like man hit the woman. It went on for an hour despite my report on front desk. We just hoped they were both fine coz nobody called me back after from the front desk.', 'Yes', '2026-06-20 03:36:44'),
(85, 85, 5, 'Was good', 'Yes', '2026-06-20 03:43:56'),
(86, 86, 5, 'None', 'Yes', '2026-06-20 04:41:17'),
(87, 87, 5, 'Just greatful for the stay 😊 keep it up', 'Yes', '2026-06-21 00:19:13'),
(88, 88, 5, 'n/a', 'Yes', '2026-06-21 00:52:41'),
(89, 89, 5, 'Continue the good service', 'Yes', '2026-06-21 01:04:25'),
(90, 90, 5, 'Good', 'Yes', '2026-06-21 01:08:52'),
(91, 91, 5, 'N/A', 'Yes', '2026-06-21 01:15:02'),
(92, 92, 5, 'na', 'Yes', '2026-06-21 01:23:22'),
(93, 93, 5, 'Super okay', 'Yes', '2026-06-21 01:23:28'),
(94, 94, 5, 'N/A', 'Yes', '2026-06-21 01:35:06'),
(95, 95, 5, 'Facial tissue', 'Yes', '2026-06-21 01:42:13'),
(96, 96, 5, 'The waiters need to smile more', 'Yes', '2026-06-21 02:33:33'),
(97, 97, 5, 'No suggestions, our stay in the hotel was a great experience', 'Yes', '2026-06-21 02:37:29'),
(98, 98, 5, 'None all goods', 'Yes', '2026-06-21 02:46:24'),
(99, 99, 5, 'NA', 'Yes', '2026-06-21 02:46:50'),
(100, 100, 5, 'N/a', 'Yes', '2026-06-21 02:47:53'),
(101, 101, 5, 'My overall experience is good', 'Yes', '2026-06-21 03:27:33'),
(102, 102, 5, 'we will definitely coming back! thank you', 'Yes', '2026-06-28 02:25:25'),
(103, 103, 5, 'May maingay po na naglilinis sa tabing room which cause na maagang nagising', 'Yes', '2026-06-28 02:32:56'),
(104, 104, 5, 'None thank you', 'Yes', '2026-06-28 03:04:14'),
(105, 105, 5, 'stronger bidet water pressure', 'Yes', '2026-06-28 03:29:13'),
(106, 106, 5, 'NA', 'Yes', '2026-06-28 03:44:39'),
(107, 107, 5, 'I feel like the water shower needs improvement as it’s difficult to gauge the hot and cold water', 'Yes', '2026-06-28 04:08:39'),
(108, 108, 4, 'None', 'Yes', '2026-06-28 04:16:50'),
(109, 109, 5, 'I feel like the water shower needs improvement as it’s difficult to gauge the hot and cold water', 'Yes', '2026-06-28 04:29:51'),
(110, 110, 4, 'None', 'Yes', '2026-06-28 05:13:47'),
(111, 111, 5, 'screen in our room window was missing', 'Yes', '2026-07-02 03:04:31'),
(112, 112, 4, 'I hope I get a room with a better view next time', 'Yes', '2026-07-05 00:52:37'),
(113, 113, 5, 'Actually, your place is so nice.', 'Yes', '2026-07-05 01:07:31'),
(114, 114, 5, 'None', 'Yes', '2026-07-05 01:21:03'),
(115, 115, 5, 'None. We enjoyed our stay and will definitely come back', 'Yes', '2026-07-05 01:23:50'),
(116, 116, 4, 'none', 'Yes', '2026-07-05 01:26:17'),
(117, 117, 5, 'Nothing. Our stay was excellent', 'Yes', '2026-07-05 02:03:36'),
(118, 118, 5, 'Na', 'Yes', '2026-07-05 02:31:15'),
(119, 119, 5, '.', 'Yes', '2026-07-05 03:00:05'),
(120, 120, 5, 'More recreational activities', 'Yes', '2026-07-05 03:28:46'),
(121, 121, 5, 'More recreational activities', 'Yes', '2026-07-05 05:17:00'),
(122, 122, 5, 'Actually, your place is so nice.', 'Yes', '2026-07-06 10:52:55'),
(123, 123, 5, 'Actually, your place is so nice.', 'Yes', '2026-07-07 04:27:40'),
(124, 124, 5, 'Clearer specifications as to number of persons per room, so with essential items such as pillows and towels.', 'Yes', '2026-07-12 01:53:16'),
(125, 125, 5, 'Na', 'Yes', '2026-07-12 02:09:47'),
(126, 126, 3, 'I would like to express my disappointment regarding several aspects of our recent stay.\r\n\r\nFirst, I specifically reserved a room with either a king-size or queen-size bed and even availed of your anniversary room setup to make the occasion truly special. Prior to our arrival, I also sent a reference photo of the desired setup, which clearly showed that it was intended for a king-size or queen-size bed, not a double bed.\r\n\r\nUnfortunately, we were assigned a room with a double bed, and the anniversary setup was arranged on that bed instead. This was disappointing, as it did not match the reservation I had made or the expectations established through our prior communication. It was especially disheartening considering this stay was meant to celebrate a very special occasion.\r\n\r\nIn addition to the room concern, we also experienced several service and food safety issues during our stay.\r\n\r\nWhile having drinks at the bar, we ordered a Sangria and discovered a small fly inside the drink. We immediately brought it to the attention of the server. However, the response was disappointing. She simply acknowledged it by saying “okay,” took the drink away, and discarded it without offering any apology or showing any concern for the incident. Although we found the response unprofessional, we chose not to make an issue of it and decided to let it pass.\r\n\r\nUnfortunately, later that evening, we encountered another issue when we ordered food through Room Service. We found what appeared to be a piece of thread, fiber, or cotton-like material in our food. I even took a photograph for documentation. When we informed the staff, they sincerely apologized, which we appreciated.\r\n\r\nHowever, because this was already the second food-related incident we had experienced on the same day, I requested to speak with a supervisor or manager to discuss my concerns regarding food safety and quality control. Regrettably, no supervisor or manager came to speak with us. Instead, the staff offered us a complimentary fruit platter as compensation, which we politely declined.\r\n\r\nTo be clear, our concern was never about receiving compensation. We were not looking for complimentary items or gestures. Our primary concern was the apparent lapses in food safety, food handling, and quality assurance, as well as the lack of management presence in addressing a serious concern raised by a guest.\r\n\r\nDespite these unfortunate experiences, I appreciate the staff members who extended their apologies where appropriate. However, I believe these incidents warrant serious attention. I hope management will thoroughly review what happened not only regarding the room assignment but also the food safety practices, staff response, and guest complaint handling procedures.\r\n\r\nI share this feedback in the hope that meaningful improvements can be made to prevent similar experiences for future guests. As someone who chose your property to celebrate an important milestone, I expected a much higher standard of service and attention to detail.\r\n\r\nThank you for taking the time to review my feedback. I look forward to your response.\r\n\r\nKind regards,\r\n\r\nMr. Jeremy Fadri', 'No', '2026-07-17 08:12:31'),
(127, 127, 3, 'I would like to express my disappointment regarding several aspects of our recent stay.\r\n\r\nFirst, I specifically reserved a room with either a king-size or queen-size bed and even availed of your anniversary room setup to make the occasion truly special. Prior to our arrival, I also sent a reference photo of the desired setup, which clearly showed that it was intended for a king-size or queen-size bed, not a double bed.\r\n\r\nUnfortunately, we were assigned a room with a double bed, and the anniversary setup was arranged on that bed instead. This was disappointing, as it did not match the reservation I had made or the expectations established through our prior communication. It was especially disheartening considering this stay was meant to celebrate a very special occasion.\r\n\r\nIn addition to the room concern, we also experienced several service and food safety issues during our stay.\r\n\r\nWhile having drinks at the bar, we ordered a Sangria and discovered a small fly inside the drink. We immediately brought it to the attention of the server. However, the response was disappointing. She simply acknowledged it by saying “okay,” took the drink away, and discarded it without offering any apology or showing any concern for the incident. Although we found the response unprofessional, we chose not to make an issue of it and decided to let it pass.\r\n\r\nUnfortunately, later that evening, we encountered another issue when we ordered food through Room Service. We found what appeared to be a piece of thread, fiber, or cotton-like material in our food. I even took a photograph for documentation. When we informed the staff, they sincerely apologized, which we appreciated.\r\n\r\nHowever, because this was already the second food-related incident we had experienced on the same day, I requested to speak with a supervisor or manager to discuss my concerns regarding food safety and quality control. Regrettably, no supervisor or manager came to speak with us. Instead, the staff offered us a complimentary fruit platter as compensation, which we politely declined.\r\n\r\nTo be clear, our concern was never about receiving compensation. We were not looking for complimentary items or gestures. Our primary concern was the apparent lapses in food safety, food handling, and quality assurance, as well as the lack of management presence in addressing a serious concern raised by a guest.\r\n\r\nDespite these unfortunate experiences, I appreciate the staff members who extended their apologies where appropriate. However, I believe these incidents warrant serious attention. I hope management will thoroughly review what happened not only regarding the room assignment but also the food safety practices, staff response, and guest complaint handling procedures.\r\n\r\nI share this feedback in the hope that meaningful improvements can be made to prevent similar experiences for future guests. As someone who chose your property to celebrate an important milestone, I expected a much higher standard of service and attention to detail.\r\n\r\nThank you for taking the time to review my feedback. I look forward to your response.\r\n\r\nKind regards,\r\n\r\nMr. Jeremy Fadri', 'No', '2026-07-18 04:57:31'),
(128, 128, 5, 'they should offer a breakfast buffet next time.', 'Yes', '2026-07-19 02:08:33'),
(129, 129, 5, 'none', 'Yes', '2026-07-19 02:56:20'),
(130, 130, 5, 'Fragrance of the room especially the bath room.', 'Yes', '2026-07-19 02:58:31'),
(131, 131, 5, 'night activities (more live performance)', 'Yes', '2026-07-19 02:59:50'),
(132, 132, 5, 'they should offer a breakfast buffet next time.', 'Yes', '2026-07-20 05:39:51'),
(133, 133, 5, 'they should offer a breakfast buffet next time.', 'Yes', '2026-07-20 14:08:32'),
(134, 134, 4, 'Not really', 'Yes', '2026-08-04 04:17:41'),
(135, 135, 4, 'Not really', 'Yes', '2026-08-04 05:57:26'),
(136, 136, 4, 'Not really', 'Yes', '2026-08-04 09:32:18'),
(137, 137, 5, 'None at all.', 'Yes', '2026-08-16 02:02:19'),
(138, 138, 5, 'n/a', 'Yes', '2026-08-16 02:02:27'),
(139, 139, 4, 'nothing', 'Yes', '2026-08-16 02:10:14'),
(140, 140, 5, 'None', 'Yes', '2026-08-16 02:13:15'),
(141, 141, 5, 'Keep up the goodwork', 'Yes', '2026-08-16 03:10:35'),
(142, 142, 5, 'There is a foul odor along the hallway during the whole duration of our trip. We can also smell the foul odor inside our room, especially in the toilet. Staff tried removing the smell in the hallway by putting fans but it wasn’t enough', 'Yes', '2026-08-22 01:17:49');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks_legacy`
--

CREATE TABLE `feedbacks_legacy` (
  `id` int(11) NOT NULL,
  `frontdesk` tinyint(4) NOT NULL DEFAULT 0,
  `reservations` tinyint(4) NOT NULL DEFAULT 0,
  `telephone_operator` tinyint(4) NOT NULL DEFAULT 0,
  `valet` tinyint(4) NOT NULL DEFAULT 0,
  `housekeeping` tinyint(4) NOT NULL DEFAULT 0,
  `accommodation` tinyint(4) NOT NULL DEFAULT 0,
  `safety` tinyint(4) NOT NULL DEFAULT 0,
  `security` tinyint(4) NOT NULL DEFAULT 0,
  `overall_service` tinyint(4) NOT NULL DEFAULT 0,
  `frontdesk_comments` text DEFAULT NULL,
  `food_quality` tinyint(4) NOT NULL DEFAULT 0,
  `serving_time` tinyint(4) NOT NULL DEFAULT 0,
  `wait_staff` tinyint(4) NOT NULL DEFAULT 0,
  `grooming` tinyint(4) NOT NULL DEFAULT 0,
  `behavior` tinyint(4) NOT NULL DEFAULT 0,
  `fnb_service` tinyint(4) NOT NULL DEFAULT 0,
  `bar` tinyint(4) NOT NULL DEFAULT 0,
  `bartender` tinyint(4) NOT NULL DEFAULT 0,
  `fnb_comments` text DEFAULT NULL,
  `helpful_staff_names` varchar(500) DEFAULT NULL,
  `overall_rating` tinyint(4) NOT NULL DEFAULT 0,
  `suggestions_future` text DEFAULT NULL,
  `other_comments` text DEFAULT NULL,
  `first_stay` varchar(10) DEFAULT NULL,
  `purpose_of_stay` varchar(100) DEFAULT NULL,
  `other_purpose_text` varchar(255) DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `other_nationality_text` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `room_no` varchar(20) NOT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_fnb`
--

CREATE TABLE `feedback_fnb` (
  `feedback_id` int(11) NOT NULL,
  `food_quality` tinyint(4) NOT NULL DEFAULT 0,
  `serving_time` tinyint(4) NOT NULL DEFAULT 0,
  `grooming` tinyint(4) NOT NULL DEFAULT 0,
  `behavior` tinyint(4) NOT NULL DEFAULT 0,
  `fnb_service` tinyint(4) NOT NULL DEFAULT 0,
  `bar` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback_fnb`
--

INSERT INTO `feedback_fnb` (`feedback_id`, `food_quality`, `serving_time`, `grooming`, `behavior`, `fnb_service`, `bar`) VALUES
(23, 2, 2, 2, 2, 2, 2),
(25, 2, 2, 3, 3, 3, 0),
(26, 2, 2, 3, 3, 3, 2),
(27, 2, 2, 2, 2, 2, 0),
(28, 2, 2, 3, 3, 3, 3),
(30, 2, 0, 2, 2, 2, 0),
(31, 2, 3, 3, 3, 3, 2),
(33, 3, 3, 3, 3, 3, 3),
(34, 3, 3, 3, 3, 3, 3),
(35, 2, 2, 3, 3, 3, 3),
(36, 3, 3, 3, 3, 3, 3),
(38, 3, 3, 3, 3, 3, 0),
(39, 2, 3, 3, 3, 3, 3),
(40, 3, 3, 3, 2, 1, 3),
(41, 3, 3, 2, 2, 2, 0),
(42, 2, 2, 2, 2, 2, 2),
(43, 2, 2, 3, 3, 3, 3),
(44, 2, 2, 2, 2, 2, 0),
(45, 2, 2, 2, 2, 2, 0),
(46, 0, 0, 2, 2, 2, 2),
(47, 2, 2, 3, 3, 3, 2),
(48, 3, 3, 3, 3, 3, 3),
(49, 3, 3, 3, 3, 3, 0),
(50, 2, 3, 3, 3, 3, 0),
(51, 3, 3, 3, 3, 3, 3),
(52, 3, 3, 3, 3, 3, 3),
(53, 3, 3, 3, 3, 3, 3),
(54, 3, 3, 3, 3, 3, 3),
(55, 2, 3, 3, 3, 3, 0),
(56, 3, 3, 3, 3, 3, 3),
(57, 2, 2, 2, 2, 2, 2),
(58, 3, 3, 3, 3, 2, 2),
(59, 0, 0, 0, 0, 0, 0),
(60, 0, 0, 0, 0, 0, 0),
(61, 2, 3, 3, 3, 3, 3),
(62, 3, 3, 3, 3, 3, 3),
(63, 2, 2, 3, 3, 3, 3),
(64, 0, 0, 0, 0, 0, 0),
(65, 2, 2, 3, 3, 3, 3),
(66, 2, 2, 3, 3, 3, 3),
(67, 0, 0, 0, 0, 0, 0),
(68, 3, 3, 3, 3, 3, 3),
(69, 3, 3, 3, 3, 3, 3),
(70, 2, 2, 3, 3, 3, 3),
(71, 0, 0, 0, 0, 0, 0),
(72, 2, 1, 3, 3, 3, 0),
(73, 0, 0, 0, 0, 0, 0),
(74, 0, 0, 0, 0, 0, 0),
(75, 3, 3, 3, 3, 3, 3),
(76, 3, 3, 3, 3, 3, 3),
(77, 3, 3, 3, 3, 3, 3),
(78, 3, 3, 3, 3, 3, 3),
(79, 2, 2, 3, 3, 3, 3),
(80, 2, 2, 3, 3, 3, 3),
(81, 3, 3, 3, 3, 3, 3),
(82, 3, 3, 3, 3, 3, 3),
(83, 3, 3, 3, 3, 3, 0),
(84, 0, 0, 0, 0, 0, 0),
(85, 3, 3, 3, 3, 3, 3),
(86, 3, 3, 3, 3, 3, 3),
(87, 3, 3, 3, 3, 3, 3),
(88, 3, 3, 3, 3, 3, 3),
(89, 2, 2, 2, 2, 2, 2),
(90, 2, 2, 2, 2, 2, 2),
(91, 3, 3, 3, 3, 3, 3),
(92, 3, 3, 3, 3, 3, 3),
(93, 3, 3, 3, 3, 3, 3),
(94, 3, 3, 3, 3, 3, 3),
(95, 2, 3, 3, 3, 2, 3),
(96, 3, 3, 3, 2, 2, 2),
(97, 0, 0, 0, 0, 0, 0),
(98, 3, 3, 3, 3, 3, 3),
(99, 3, 3, 3, 3, 3, 0),
(100, 2, 2, 3, 3, 3, 2),
(101, 3, 3, 3, 3, 3, 3),
(102, 3, 3, 3, 3, 3, 3),
(103, 2, 2, 2, 2, 2, 2),
(104, 3, 3, 3, 3, 3, 0),
(105, 3, 3, 3, 3, 3, 3),
(106, 3, 3, 3, 3, 3, 3),
(107, 0, 0, 0, 0, 0, 0),
(108, 2, 2, 3, 3, 3, 0),
(109, 0, 0, 0, 0, 0, 0),
(110, 2, 2, 3, 3, 3, 0),
(111, 3, 3, 3, 3, 3, 3),
(112, 2, 2, 2, 2, 2, 2),
(113, 2, 2, 3, 3, 3, 2),
(114, 3, 3, 3, 3, 3, 3),
(115, 3, 3, 3, 3, 3, 3),
(116, 2, 2, 2, 2, 2, 2),
(117, 3, 3, 3, 3, 3, 3),
(118, 2, 3, 3, 2, 2, 0),
(119, 3, 3, 3, 3, 3, 3),
(120, 2, 3, 3, 3, 2, 2),
(121, 2, 3, 3, 3, 2, 2),
(122, 2, 2, 3, 3, 3, 2),
(123, 2, 2, 3, 3, 3, 2),
(124, 3, 3, 3, 3, 3, 3),
(125, 0, 0, 0, 0, 0, 0),
(126, 1, 3, 2, 2, 2, 1),
(127, 1, 2, 2, 2, 2, 1),
(128, 3, 3, 3, 3, 3, 3),
(129, 0, 0, 0, 0, 0, 0),
(130, 3, 3, 3, 3, 3, 3),
(131, 3, 3, 3, 3, 3, 3),
(132, 3, 3, 3, 3, 3, 3),
(133, 3, 3, 3, 3, 3, 3),
(134, 3, 2, 2, 3, 3, 0),
(135, 3, 2, 2, 3, 3, 0),
(136, 3, 2, 2, 3, 3, 0),
(137, 3, 3, 3, 3, 3, 3),
(138, 3, 3, 3, 3, 3, 3),
(139, 2, 2, 2, 2, 2, 2),
(140, 3, 3, 3, 3, 3, 3),
(141, 0, 0, 0, 0, 0, 0),
(142, 0, 0, 3, 3, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `feedback_foh`
--

CREATE TABLE `feedback_foh` (
  `feedback_id` int(11) NOT NULL,
  `frontdesk` tinyint(4) NOT NULL DEFAULT 0,
  `reservations` tinyint(4) NOT NULL DEFAULT 0,
  `check_in_rating` tinyint(4) NOT NULL DEFAULT 0,
  `check_out_rating` tinyint(4) NOT NULL DEFAULT 0,
  `telephone_operator` tinyint(4) NOT NULL DEFAULT 0,
  `valet` tinyint(4) NOT NULL DEFAULT 0,
  `housekeeping` tinyint(4) NOT NULL DEFAULT 0,
  `accommodation` tinyint(4) NOT NULL DEFAULT 0,
  `safety` tinyint(4) NOT NULL DEFAULT 0,
  `security` tinyint(4) NOT NULL DEFAULT 0,
  `friendliness` tinyint(4) NOT NULL DEFAULT 0,
  `attentiveness` tinyint(4) NOT NULL DEFAULT 0,
  `courteousness` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback_foh`
--

INSERT INTO `feedback_foh` (`feedback_id`, `frontdesk`, `reservations`, `check_in_rating`, `check_out_rating`, `telephone_operator`, `valet`, `housekeeping`, `accommodation`, `safety`, `security`, `friendliness`, `attentiveness`, `courteousness`) VALUES
(23, 2, 2, 2, 2, 2, 0, 2, 2, 0, 2, 2, 2, 2),
(25, 3, 3, 3, 3, 3, 0, 2, 2, 0, 3, 3, 3, 3),
(26, 3, 2, 3, 3, 3, 0, 3, 2, 0, 3, 3, 3, 3),
(27, 3, 3, 3, 3, 3, 0, 2, 2, 0, 3, 3, 3, 3),
(28, 3, 3, 3, 3, 2, 0, 3, 2, 0, 3, 3, 3, 3),
(30, 2, 3, 2, 1, 2, 0, 2, 2, 0, 2, 3, 2, 3),
(31, 3, 0, 2, 2, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(33, 3, 3, 3, 2, 3, 0, 2, 2, 0, 3, 3, 3, 3),
(34, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(35, 3, 3, 3, 3, 3, 0, 3, 2, 0, 3, 3, 3, 3),
(36, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(38, 3, 3, 3, 3, 3, 0, 2, 3, 0, 2, 3, 3, 3),
(39, 3, 2, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(40, 3, 3, 3, 3, 3, 0, 3, 2, 0, 3, 3, 3, 3),
(41, 2, 3, 2, 2, 2, 0, 3, 2, 0, 3, 3, 2, 2),
(42, 2, 2, 2, 2, 2, 0, 2, 2, 0, 2, 3, 2, 3),
(43, 3, 3, 3, 3, 3, 0, 3, 2, 0, 3, 3, 3, 3),
(44, 2, 2, 2, 2, 2, 0, 2, 2, 0, 2, 3, 3, 3),
(45, 2, 2, 2, 2, 2, 0, 2, 2, 0, 2, 3, 3, 3),
(46, 3, 2, 3, 0, 0, 0, 2, 1, 0, 2, 3, 3, 3),
(47, 3, 3, 3, 3, 0, 0, 3, 2, 0, 3, 3, 3, 3),
(48, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(49, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(50, 2, 2, 2, 2, 2, 0, 2, 2, 0, 2, 3, 3, 3),
(51, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(52, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(53, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(54, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(55, 2, 2, 2, 2, 2, 0, 2, 2, 0, 2, 3, 3, 3),
(56, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(57, 2, 2, 2, 2, 2, 0, 2, 3, 0, 2, 3, 3, 3),
(58, 3, 0, 1, 2, 0, 0, 0, 2, 0, 3, 3, 2, 3),
(59, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(60, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(61, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(62, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(63, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(64, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(65, 3, 3, 3, 2, 2, 0, 3, 2, 0, 3, 3, 3, 3),
(66, 3, 3, 3, 2, 2, 0, 3, 2, 0, 3, 3, 3, 3),
(67, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(68, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(69, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(70, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(71, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(72, 3, 3, 3, 2, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(73, 3, 3, 3, 3, 3, 0, 3, 2, 0, 3, 3, 3, 3),
(74, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(75, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(76, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(77, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(78, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(79, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(80, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(81, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(82, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(83, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(84, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(85, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(86, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(87, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(88, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(89, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 2, 2, 2),
(90, 2, 3, 3, 3, 2, 0, 3, 3, 0, 3, 2, 2, 2),
(91, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(92, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(93, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(94, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(95, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(96, 3, 3, 3, 3, 2, 0, 2, 3, 0, 3, 3, 3, 3),
(97, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(98, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(99, 3, 0, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(100, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(101, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(102, 3, 3, 3, 3, 0, 0, 3, 3, 0, 3, 3, 3, 3),
(103, 2, 3, 2, 2, 2, 0, 3, 2, 0, 2, 3, 3, 3),
(104, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(105, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(106, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(107, 3, 3, 3, 3, 2, 0, 3, 2, 0, 3, 3, 3, 3),
(108, 2, 3, 3, 3, 3, 0, 2, 3, 0, 2, 3, 3, 3),
(109, 3, 3, 3, 3, 2, 0, 3, 2, 0, 3, 3, 3, 3),
(110, 2, 3, 3, 3, 3, 0, 2, 3, 0, 2, 3, 3, 3),
(111, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(112, 2, 2, 3, 2, 2, 0, 3, 3, 0, 3, 3, 2, 2),
(113, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(114, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(115, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(116, 2, 2, 2, 2, 2, 0, 2, 2, 0, 2, 2, 2, 2),
(117, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(118, 3, 3, 3, 2, 3, 0, 3, 2, 0, 3, 3, 3, 3),
(119, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(120, 2, 2, 2, 2, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(121, 2, 2, 2, 2, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(122, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(123, 3, 3, 3, 3, 2, 0, 3, 3, 0, 3, 3, 3, 3),
(124, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(125, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(126, 2, 1, 1, 2, 1, 0, 2, 2, 0, 2, 2, 1, 2),
(127, 2, 1, 1, 2, 1, 0, 2, 2, 0, 2, 2, 1, 2),
(128, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(129, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(130, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(131, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(132, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(133, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(134, 3, 3, 3, 3, 0, 0, 3, 3, 0, 2, 3, 3, 3),
(135, 3, 3, 3, 3, 0, 0, 3, 3, 0, 2, 3, 3, 3),
(136, 3, 3, 3, 3, 0, 0, 3, 3, 0, 2, 3, 3, 3),
(137, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(138, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(139, 2, 2, 3, 2, 2, 0, 2, 3, 0, 2, 2, 2, 2),
(140, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(141, 3, 3, 3, 3, 3, 0, 3, 3, 0, 3, 3, 3, 3),
(142, 3, 3, 3, 3, 3, 0, 2, 1, 0, 3, 3, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `feedback_guestroom`
--

CREATE TABLE `feedback_guestroom` (
  `feedback_id` int(11) NOT NULL,
  `cleanliness` tinyint(4) NOT NULL DEFAULT 0,
  `ambiance` tinyint(4) NOT NULL DEFAULT 0,
  `comfort` tinyint(4) NOT NULL DEFAULT 0,
  `bathroom` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback_guestroom`
--

INSERT INTO `feedback_guestroom` (`feedback_id`, `cleanliness`, `ambiance`, `comfort`, `bathroom`) VALUES
(23, 2, 2, 2, 2),
(25, 2, 2, 2, 2),
(26, 2, 2, 3, 3),
(27, 3, 3, 3, 3),
(28, 2, 3, 2, 2),
(30, 2, 2, 2, 2),
(31, 3, 3, 3, 3),
(33, 3, 3, 3, 2),
(34, 3, 3, 3, 3),
(35, 2, 3, 3, 2),
(36, 3, 3, 3, 3),
(38, 2, 3, 3, 2),
(39, 3, 3, 3, 3),
(40, 3, 3, 3, 3),
(41, 3, 2, 2, 2),
(42, 2, 2, 2, 2),
(43, 2, 2, 2, 2),
(44, 2, 3, 2, 2),
(45, 2, 3, 2, 2),
(46, 1, 1, 1, 1),
(47, 2, 2, 3, 2),
(48, 3, 3, 3, 3),
(49, 3, 3, 3, 3),
(50, 3, 3, 3, 2),
(51, 3, 3, 3, 3),
(52, 3, 3, 3, 3),
(53, 3, 3, 3, 3),
(54, 3, 3, 3, 3),
(55, 3, 3, 3, 2),
(56, 3, 3, 3, 3),
(57, 3, 3, 3, 3),
(58, 2, 3, 2, 2),
(59, 3, 3, 3, 3),
(60, 3, 3, 3, 3),
(61, 3, 3, 3, 3),
(62, 3, 3, 3, 3),
(63, 2, 2, 3, 2),
(64, 3, 3, 3, 3),
(65, 3, 3, 2, 3),
(66, 3, 3, 2, 3),
(67, 3, 3, 3, 3),
(68, 3, 3, 3, 3),
(69, 3, 3, 3, 3),
(70, 3, 3, 3, 1),
(71, 3, 3, 3, 3),
(72, 3, 3, 3, 3),
(73, 2, 3, 2, 2),
(74, 3, 3, 3, 3),
(75, 3, 3, 3, 2),
(76, 3, 3, 3, 3),
(77, 3, 3, 3, 3),
(78, 3, 3, 3, 3),
(79, 3, 3, 3, 2),
(80, 3, 3, 3, 2),
(81, 3, 3, 3, 3),
(82, 3, 3, 3, 3),
(83, 3, 3, 3, 3),
(84, 3, 3, 3, 3),
(85, 3, 3, 3, 3),
(86, 3, 3, 3, 3),
(87, 3, 3, 3, 3),
(88, 3, 3, 3, 3),
(89, 2, 2, 2, 2),
(90, 3, 3, 2, 2),
(91, 3, 3, 3, 3),
(92, 3, 3, 3, 3),
(93, 3, 3, 3, 3),
(94, 3, 3, 3, 3),
(95, 3, 3, 3, 3),
(96, 3, 3, 3, 3),
(97, 3, 3, 3, 3),
(98, 3, 3, 3, 3),
(99, 3, 3, 3, 3),
(100, 3, 3, 3, 3),
(101, 3, 3, 3, 3),
(102, 3, 3, 3, 3),
(103, 3, 3, 3, 3),
(104, 3, 3, 3, 2),
(105, 3, 3, 3, 3),
(106, 3, 3, 3, 3),
(107, 2, 3, 3, 2),
(108, 2, 2, 2, 2),
(109, 2, 3, 3, 2),
(110, 2, 2, 2, 2),
(111, 3, 3, 3, 2),
(112, 2, 2, 3, 3),
(113, 2, 3, 3, 2),
(114, 3, 3, 3, 3),
(115, 3, 3, 3, 3),
(116, 2, 2, 2, 2),
(117, 3, 3, 3, 2),
(118, 3, 3, 2, 2),
(119, 3, 3, 3, 3),
(120, 3, 3, 3, 2),
(121, 3, 3, 3, 2),
(122, 2, 3, 3, 2),
(123, 2, 3, 3, 2),
(124, 3, 3, 3, 3),
(125, 3, 3, 3, 3),
(126, 2, 2, 2, 2),
(127, 2, 2, 2, 2),
(128, 3, 3, 3, 3),
(129, 3, 3, 3, 3),
(130, 3, 3, 3, 3),
(131, 3, 3, 3, 3),
(132, 3, 3, 3, 3),
(133, 3, 3, 3, 3),
(134, 2, 3, 3, 2),
(135, 2, 3, 3, 2),
(136, 2, 3, 3, 2),
(137, 3, 3, 3, 3),
(138, 3, 3, 3, 3),
(139, 2, 2, 3, 2),
(140, 3, 3, 3, 3),
(141, 3, 3, 3, 3),
(142, 1, 3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback_helpful_staff`
--

CREATE TABLE `feedback_helpful_staff` (
  `id` int(11) NOT NULL,
  `feedback_id` int(11) NOT NULL,
  `staff_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback_helpful_staff`
--

INSERT INTO `feedback_helpful_staff` (`id`, `feedback_id`, `staff_name`) VALUES
(23, 23, 'Everyone were helpful'),
(25, 25, 'joshua'),
(26, 26, 'All were helpful and courteous'),
(27, 27, 'Sorry I do not remember'),
(28, 27, 'but who bring my luggage when I leave'),
(29, 28, 'N/A'),
(32, 30, 'Aa'),
(33, 31, 'Don’t remember'),
(35, 33, 'All'),
(36, 34, 'All esp bunny mascots on saturday afternoon april4'),
(37, 36, 'N/a'),
(40, 38, 'During our check-in'),
(41, 38, 'I forgot her name but she gave assistance in securing for my baby’s crib and bathtub'),
(42, 39, 'Allan Rivera'),
(43, 40, 'N/A'),
(44, 41, 'N/a'),
(45, 42, 'N/A'),
(46, 43, 'All'),
(47, 44, 'Lobby security staff'),
(48, 45, 'Lobby security staff'),
(49, 46, 'Chloe Wasing and MJ Villanueva just ask for ice terminal'),
(50, 47, 'Angel from front desk'),
(51, 48, 'Rrr'),
(52, 49, 'All of the staff are helpful'),
(53, 50, 'cant remember but everyone are nice'),
(54, 51, 'N/A'),
(55, 52, 'Mark cabriana'),
(56, 53, 'Mike'),
(57, 54, 'Mark cabriana'),
(58, 55, 'cant remember but everyone are nice'),
(59, 56, 'Mostly everyone were helpful.'),
(60, 57, 'Mikyla jovero'),
(61, 58, 'Mikyla'),
(62, 59, 'Cant remember'),
(63, 60, 'Angel'),
(64, 61, 'Angel and Dominic'),
(65, 62, 'Everyone'),
(66, 62, 'Mark Sid'),
(67, 63, 'The staff at the St. Patrick’s Bar and Kokomo Cafe'),
(68, 65, '.'),
(69, 66, '.'),
(70, 68, '-'),
(71, 69, 'All of them'),
(72, 70, 'Everyone was exceptionally kind. Everyone. Oh how I wish I got their names.  Oh I remember one. Our server that one time we had dinner in the lobby. Her name is Karen Ly. She’s just very kind'),
(73, 70, 'helpful'),
(74, 70, 'and professional.'),
(75, 71, 'N/A'),
(76, 72, 'Angelou'),
(77, 73, 'n/a'),
(78, 74, 'Forgot the names'),
(79, 75, 'Front desk &amp; concierge'),
(80, 76, 'Stacey'),
(81, 77, 'N/a'),
(82, 78, 'Kathrina Oraa-Allanigue'),
(83, 79, 'All staff members were very helpful'),
(84, 80, 'All staff members were very helpful'),
(85, 81, 'Sanchai'),
(86, 82, 'Gio and Alan'),
(87, 83, 'All staff'),
(88, 84, 'Dont remember the names'),
(89, 85, 'Sky'),
(90, 86, 'Sanchai'),
(91, 87, 'Everybody'),
(92, 88, 'sanchai'),
(93, 89, 'Camp john hay fhr forest'),
(94, 90, 'Camp john hay the forest'),
(95, 91, 'N/A'),
(96, 92, 'excellent'),
(97, 93, 'Jerome Toledo and Jacklord Garcia'),
(98, 94, 'All the staff very accomodating'),
(99, 95, 'All'),
(100, 96, 'Sancha'),
(101, 97, 'Ms. Sanchai'),
(102, 98, 'Sanchai'),
(103, 99, 'NA'),
(104, 100, 'Sunshine'),
(105, 101, 'All the staff'),
(106, 102, 'sunshine'),
(107, 103, 'Sanchai'),
(108, 104, 'Sanchai'),
(109, 105, 'Sanchai'),
(110, 106, 'Sanchai'),
(111, 107, 'Ava from the Reservations team was excellent and very accommodating'),
(112, 108, 'Sanchai'),
(113, 109, 'Ava from the Reservations team was excellent and very accommodating'),
(114, 110, 'Sanchai'),
(115, 111, 'Sanchai'),
(116, 111, 'Angel'),
(117, 111, 'and Josh'),
(118, 112, 'Ava'),
(119, 113, 'All of them'),
(120, 114, 'Thanks to sir Mark'),
(121, 114, 'maam Sanchai and maam Angel for being so accomodating! The guards and staff were all kind. ⭐️⭐️⭐️'),
(122, 115, 'N/a'),
(123, 116, 'all'),
(124, 117, 'Did’nt remember the names'),
(125, 118, 'Cant remember'),
(126, 119, '.'),
(127, 120, 'Lodger'),
(128, 121, 'Lodger'),
(129, 122, 'All of them'),
(130, 123, 'All of them'),
(131, 124, 'JC'),
(132, 125, 'Na'),
(133, 126, 'Mr. Andrew from Room Service'),
(134, 126, 'who apologized for the foreign object found in the food.'),
(135, 127, 'Mr. Andrew from Room Service'),
(136, 127, 'who apologized for the foreign object found in the food. Also the receptionist from Le Chef who provides good table and view for our anniversary dinner on July 15.'),
(137, 128, 'N/A'),
(138, 129, 'angelu'),
(139, 130, 'All the front desk'),
(140, 131, 'Everyone'),
(141, 132, 'N/A'),
(142, 133, 'N/A'),
(143, 134, 'Mary'),
(144, 135, 'Mary'),
(145, 136, 'Mary'),
(146, 137, 'Sheena'),
(147, 138, 'n/a'),
(148, 139, 'NA'),
(149, 140, 'We forgot to ask their names.'),
(150, 141, 'Duty at august 15 at the reception area (Guy) who assisted ha'),
(151, 142, 'N/a');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `other_nationality_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `guest_name`, `email`, `address`, `contact_no`, `nationality`, `other_nationality_text`, `created_at`) VALUES
(23, 'Rodrida', 'Rodridamalahito@gmail.com', 'Circulo Verde', '09190683937', 'Filipino', '', '2026-03-28 02:42:06'),
(25, 'Cristine Soon', 'cristinejoyflores@gmail.com', 'Quezon City', '09175403028', 'Filipino', '', '2026-04-04 01:23:40'),
(26, 'Shilpa', 'shil.shetty@gmail.com', 'Manila', '+631254555444', 'Indian', '', '2026-04-04 02:40:59'),
(27, 'Tatsuya Nagafuji', 'tatsuya1nagafuji@gmail.com', '29D Lincoln tower', '+639171996058', 'Japanese', '', '2026-04-04 02:51:05'),
(28, 'Yoko Funaba', 'jirokunforever@gmail.com', '302A Palm Tower, 11th Avenue, McKinley Parkway, Bonifacio Global City, Taguig,', '+639175582203', 'Japanese', '', '2026-04-04 03:18:14'),
(30, 'Aa', 'Aa', 'Aa', '11', 'Filipino', '', '2026-04-04 04:30:11'),
(31, 'Vishesh Raj', 'visshukar@yahoo.co.in', '12 Paya street Ayala Alabang Village', '+639694950355', 'Indian', '', '2026-04-04 10:51:33'),
(33, 'Alexa', 'alexadelacosta@gmail.com', 'Pasig', '09451163960', 'Filipino', '', '2026-04-05 02:29:03'),
(34, 'Diana Eder', 'Dianapobre@gmail.com', 'Sta Cruz Manila', '639190987491', 'Filipino', '', '2026-04-05 02:45:37'),
(35, 'Vida', 'vidasison08@gmail.com', 'Pangasinan', '09162085732', 'Filipino', '', '2026-04-05 03:00:13'),
(36, 'Julius Badilla', 'juliusbadilla28@gmail.com', 'Cabanatuan City, Nueva Ecija', '09975502615', 'Filipino', '', '2026-04-05 03:33:48'),
(38, 'Nicole Paulen Miralles', 'pollen.sh@gmail.com', 'Quezon City', '09178165119', 'Filipino', '', '2026-04-06 03:39:12'),
(39, 'Roberto Panganiban', 'keyonman@yahoo.com', '36 Rd 1  Bagong Pagasa QC', '09684513712', 'Filipino', '', '2026-04-09 02:34:03'),
(40, 'Djhoana Ronquillo', 'drvronquillo@gmail.com', 'Subic, Zambales', '09950122469', 'Filipino', '', '2026-04-09 03:07:34'),
(41, 'Liddle', 'ceajaeliddle@yahoo.com', '258 Woodfield Road', '07492050696', 'British', '', '2026-04-09 03:17:06'),
(42, 'Erlinda Emery', 'Lindagalveemeey@hotmail.co. uk', '62 A Bruce grove  tottenham  n17 6rn', '+4479529295077', 'British', '', '2026-04-09 03:28:04'),
(43, 'Arnie dela Cruz', 'delacruz_arnie@yahoo.com', 'Bacoor, Cavite', '+639178485358', 'Filipino', '', '2026-04-09 04:26:13'),
(44, 'Abi', 'alesaca@gmail.com', 'Makati', '09177126055', 'Filipino', '', '2026-04-09 04:48:57'),
(45, 'Abi', 'alesaca@gmail.com', 'Makati', '09177126055', 'Filipino', '', '2026-04-09 04:49:37'),
(46, 'Roarllene Galang', 'roarllene_galang@yahoo.com', 'Marina One Residences', '+6591720821', 'Filipino', '', '2026-04-09 08:18:14'),
(47, 'Darcy Rose Sanico', 'sanicodee@gmail.com', 'Muntinlupa City', '09178980457', 'Filipino', '', '2026-04-10 02:37:07'),
(48, 'Arcel', 'Arcel.garcia18@gmail.com', 'Angeles city', '09182781220', 'Filipino', '', '2026-04-10 03:04:32'),
(49, 'Apple Cruz', 'apuzon@yahoo.com', 'San Juan Metro Manila', '09178361619', 'Filipino', '', '2026-04-10 03:35:40'),
(50, 'Desiree Galve', 'galvedesiree@gmail.com', 'Block Q Lot 5, Sta. Cristina 2 Covered Court', '09952849916', 'Filipino', '', '2026-04-18 02:30:59'),
(51, 'Irvin Salvador', 'iam.irvinsalvador@gmail.com', 'Makati City', '09762647612', 'Filipino', '', '2026-04-18 02:44:19'),
(52, 'Mark cabriana', 'markcabriana@yahoo.com', 'Tacloban City', '09176231557', 'Filipino', '', '2026-04-18 03:20:36'),
(53, 'Nerique Louis Taruc', 'Neriquetaruc@gmail.com', 'Blk 32 lot 21 hausland pampanga', '09952674761', 'Filipino', '', '2026-04-18 04:08:50'),
(54, 'Mark cabriana', 'markcabriana@yahoo.com', 'Tacloban City', '09176231557', 'Filipino', '', '2026-04-18 04:31:29'),
(55, 'Desiree Galve', 'galvedesiree@gmail.com', 'Block Q Lot 5, Sta. Cristina 2 Covered Court', '09952849916', 'Filipino', '', '2026-04-18 09:58:32'),
(56, 'Che', 'rochellebolante@yahoo.com', 'Tandang Sora, Quezon City', '9988443559', 'Filipino', '', '2026-04-20 02:34:37'),
(57, 'C', 'Dandel@yahoo.com', 'Qc', '09228584488', 'Filipino', '', '2026-05-10 00:30:06'),
(58, 'WESLEY SWEET', 'wessweet74@gmail.com', '815 NE 127th St', '+12063132973', 'Canadian', '', '2026-05-10 05:48:17'),
(59, 'Toni', 'ranthonettetan@gmail.com', 'Davao', '09257859909', 'Filipino', '', '2026-05-14 22:09:18'),
(60, 'Angelica Jiao', 'angelicabaradas@gmail.com', 'San Juan Nepomuceno Betis, Guagua, Pampanga', '09954563919', 'Filipino', '', '2026-05-17 02:42:22'),
(61, 'Mary Grace Irabagon', 'mgli_090277@yahoo.com', 'Cabanatuan City, Nueva Ecija', '639177709277', 'Filipino', '', '2026-05-20 03:14:40'),
(62, 'Steve Bravo', 'stevesbravo4@gmail.com', '305 Cobblestone Lane, Crawford Texas 76638', '12547096024', 'Filipino', '', '2026-05-21 00:12:32'),
(63, 'Angel Whaling', 'angel.whaling@gmail.com', 'Paseo Heights, Makati, PH', '09271304819', 'American', '', '2026-05-23 01:08:18'),
(64, 'Mary Lyn', 'mntorres93@yahoo.com', 'Bacolor Pampanga', '09214312802', 'Filipino', '', '2026-05-23 02:49:39'),
(65, 'Eryll Sy', 'eryllsy@gmail.com', 'Pampanga', '09178558614', 'Filipino', '', '2026-05-23 03:14:17'),
(66, 'Eryll Sy', 'eryllsy@gmail.com', 'Pampanga', '09178558614', 'Filipino', '', '2026-05-23 11:30:46'),
(67, 'Mary Lyn', 'mntorres93@yahoo.com', 'Bacolor Pampanga', '09214312802', 'Filipino', '', '2026-05-23 22:08:33'),
(68, 'Jaime Maglalang', 'jaimemiguel35@gmail.com', 'Tarlac, Tarlac', '09560493890', 'Filipino', '', '2026-05-24 02:10:17'),
(69, 'Ak', 'altameta18@icloud.com', 'Valenzuela City', '09765154421', 'Filipino', '', '2026-06-06 02:14:04'),
(70, 'Rodel Francisco', 'franciscofam0715@gmail.com', '1 Kalaw Ledesma Circle, Tierra Verde Homes II, Congressional Avenue Extension, Brgy. Pasong Tamo 1107 QC', '09173192583', 'Filipino', '', '2026-06-07 04:07:34'),
(71, 'Nadine', 'nadine.w.dy@gmail.com', 'Manila', '09177991211', 'Filipino', '', '2026-06-13 22:58:27'),
(72, 'Pik kwan young', 'sktrend@gmail.com', '9 President M. Roxas street , Marikina', '09178485812', 'Filipino', '', '2026-06-14 00:40:29'),
(73, 'Vinci Jayme', 'vjayme@gmail.com', '50 Rest Haven, QC', '09985550916', 'Filipino', '', '2026-06-14 00:53:39'),
(74, 'Pao', 'superpaoyeah@naver.col', 'Pasay City', '09690943871', 'Filipino', '', '2026-06-14 01:01:16'),
(75, 'Angelica Marie S. Ibardaloza', 'jinkysiasat@gmail.com', 'Muntinlupa City', '09208107488', 'Filipino', '', '2026-06-14 01:09:53'),
(76, 'George Fleming', 'george.fleming84@gmail.com', 'Georgefleming84@gmail.com', '09496280732', 'American', '', '2026-06-14 01:25:47'),
(77, 'Maxene Santos', 'maxenesurio@gmail.com', 'Pulilan, Bulacan', '+639695139541', 'Filipino', '', '2026-06-14 01:31:31'),
(78, 'Kathrina Oraa-Allanigue', 'kathrina_oraa@yahoo.com', '7484 K.Segundo st. Fortunata Village Sucat Paranaque', '09189851195', 'Filipino', '', '2026-06-14 01:35:55'),
(79, 'Krisha Alquitran', 'krishaalquitran2@gmail.com', '420-A Kayumanggi St Barangka Drive Mandaluyong City', '09158438228', 'Filipino', '', '2026-06-14 02:31:00'),
(80, 'Krisha Alquitran', 'krishaalquitran2@gmail.com', '420-A Kayumanggi St Barangka Drive Mandaluyong City', '09158438228', 'Filipino', '', '2026-06-14 05:42:49'),
(81, 'Adriane Jay Cunanan', 'cadrianejay21@gmail.com', 'Pao Manaoag Pangasinan', '09500461755', 'Filipino', '', '2026-06-20 03:07:24'),
(82, 'Krizia Quisora', 'kriziaquisora@gmail.com', 'Cainta Rizal', '9761930383', 'Filipino', '', '2026-06-20 03:15:54'),
(83, 'Annabel Barrozo', 'roannie04@yahoo.com', '623 Burton Ave Cornwall Ontario', '6474492171', 'Canadian', '', '2026-06-20 03:25:12'),
(84, 'Lav Lenon', 'Lhovequenano@gmail.com', 'Quezon City', '09171455683', 'Filipino', '', '2026-06-20 03:36:44'),
(85, 'Deon', 'dongyeon717@naver.com', 'Angeles', '09629241148', 'Filipino', '', '2026-06-20 03:43:56'),
(86, 'Adriane Jay Cunanan', 'cadrianejay21@gmail.com', 'Pao Manaoag Pangasinan', '09500461755', 'Filipino', '', '2026-06-20 04:41:17'),
(87, 'MERISSA Cristobal', 'merissa75@yahoo.com na', 'Pasig', '06088965168', 'Filipino', '', '2026-06-21 00:19:13'),
(88, 'zena', 'jessazenapondoc@gmail.com', 'Gawaran Ave., Saint Joseph Homes', '09931456930', 'Filipino', '', '2026-06-21 00:52:41'),
(89, 'Vivian Gawaran Ramirez', 'Vivianramirez42@yahoo.com', 'Bacoor', '09987938885', 'Filipino', '', '2026-06-21 01:04:25'),
(90, 'Vivian', 'Vivian', 'Bacoor', '09987938885', 'Filipino', '', '2026-06-21 01:08:52'),
(91, 'John aldrin Ocampo', 'ocampojohnaldrin917@gmail.com', '211 Tortona St.', '09955874639', 'Filipino', '', '2026-06-21 01:15:02'),
(92, 'Jeff Quijano', 'Jeffquijano1213@gmail.com', 'blk 22 lot 9 bukidnon st. addas greenfields', '09932823194', 'Filipino', '', '2026-06-21 01:23:22'),
(93, 'Renz Benedict Mendoza Nolasco', 'renzbenedictnolasco@gmail.con', 'Bacoor City', '09940567034', 'Filipino', '', '2026-06-21 01:23:28'),
(94, 'GRACE DELGADO SILVESTRE', 'barangayzapoteuno@gmail.com', 'Garnet St.', '09056954207', 'Filipino', '', '2026-06-21 01:35:06'),
(95, 'Ivan ramos', 'ramosivanverna@gmail.com', 'Bulacan', '09959967851', 'Filipino', '', '2026-06-21 01:42:13'),
(96, 'Henry john Lopez', 'Iamhenrylopez@gmail.com', 'Molino 1 bacoor', '9539375370', 'Filipino', '', '2026-06-21 02:33:33'),
(97, 'Chelsea Garfin', 'chelseadeniseg@gmail.com', 'Manila', '09270255695', 'Filipino', '', '2026-06-21 02:37:29'),
(98, 'Reynato Alejandro', 'reynatoalejandro@gmail.com', 'Panapaan 1 bacoor cavite', '09678053167', 'Filipino', '', '2026-06-21 02:46:24'),
(99, 'Liezel', 'liezel.sabater@gmail.com', 'Molino III Bacoor Cavite', '09203749686', 'Filipino', '', '2026-06-21 02:46:50'),
(100, 'Dane Lyn Alisla', 'alisladanelyn2@gmail.com', 'Bacoor Cavite', '09513947632', 'Filipino', '', '2026-06-21 02:47:53'),
(101, 'Patrick Ruiz Ledesma', 'patrickruizledesma@gmail.com', '036 Real Bacoor Cavite', '09550948456', 'Filipino', '', '2026-06-21 03:27:33'),
(102, 'rose ann valero', 'annevalero25@gmail.com', 'villa arca subdivision quezon city', '09163146295', 'Filipino', '', '2026-06-28 02:25:25'),
(103, 'Kate', 'kateromasanta26@gmail.com', 'Angeles pampanga', '09186367232', 'Filipino', '', '2026-06-28 02:32:56'),
(104, 'Aizza', 'perezaizza@gmail.com', 'Quezon City', '09243219736', 'Filipino', '', '2026-06-28 03:04:14'),
(105, 'Jimi Santiago', 'santiagojimi27@gmail.com', 'Alabang', '09560449718', 'Filipino', '', '2026-06-28 03:29:13'),
(106, 'Jullie Cuison', 'julliecuison@gmail.com', 'Parañaque City', '+639959517464', 'Filipino', '', '2026-06-28 03:44:39'),
(107, 'Kayteelyn', 'kayteelyntolentino@gmail.com', 'Pasig', '09175229547', 'Filipino', '', '2026-06-28 04:08:39'),
(108, 'Carlo Fernandez', 'c.fernandez5485@gmail.com', 'Baguio City', '09171759333', 'Filipino', '', '2026-06-28 04:16:50'),
(109, 'Kayteelyn', 'kayteelyntolentino@gmail.com', 'Pasig', '09175229547', 'Filipino', '', '2026-06-28 04:29:51'),
(110, 'Carlo Fernandez', 'c.fernandez5485@gmail.com', 'Baguio City', '09171759333', 'Filipino', '', '2026-06-28 05:13:47'),
(111, 'Arnel Pauco', 'arnelpauco@gmail.com', '8237 Camachile', '09178914302', 'Filipino', '', '2026-07-02 03:04:31'),
(112, 'Jann Danae Aninag-Palado', 'janndanae0194@gmail.com', 'Magsingal, Ilocos Sur', '09166813933', 'Filipino', '', '2026-07-05 00:52:37'),
(113, 'Liezel Tiquis', 'liezeltiquis@gmail.com', 'Bukal Padre Garcia Batangas', '+639662393226', 'Filipino', '', '2026-07-05 01:07:31'),
(114, 'Rocelle Manuel', 'rocelleannemanuel22@gmail.com', 'San Rafael, Bulacan', '09151443041', 'Filipino', '', '2026-07-05 01:21:03'),
(115, 'Cefrinne Garcia', 'clt_cef@yahoo.com', 'Mataasnakahoy Batangas', '09175241531', 'Filipino', '', '2026-07-05 01:23:50'),
(116, 'Maria Lourdes Niar', 'mlt_aen@yahoo.com', 'Mataas na Kahoy Batangas', '9665484407', 'Filipino', '', '2026-07-05 01:26:17'),
(117, 'Arra Villegas', 'arrattorres@gmail.com', 'Darasa Tanauan City, Batangas', '+639184441406', 'Filipino', '', '2026-07-05 02:03:36'),
(118, 'Jose amistoso', 'amistosojoseagustin@gmail.com', '9582 Kalayaan Avenue, Barangay Guadalupe Nuevo', '09205051186', 'Filipino', '', '2026-07-05 02:31:15'),
(119, 'Krystle', 'krystle_magrey@yahoo.com', 'Pampangq', '639691994498', 'Filipino', '', '2026-07-05 03:00:05'),
(120, 'Arielle Villegas', 'elleiravillegas@gmail.com', 'Tanauan Batangas', '9985488447', 'Filipino', '', '2026-07-05 03:28:46'),
(121, 'Arielle Villegas', 'elleiravillegas@gmail.com', 'Tanauan Batangas', '9985488447', 'Filipino', '', '2026-07-05 05:17:00'),
(122, 'Liezel Tiquis', 'liezeltiquis@gmail.com', 'Bukal Padre Garcia Batangas', '+639662393226', 'Filipino', '', '2026-07-06 10:52:55'),
(123, 'Liezel Tiquis', 'liezeltiquis@gmail.com', 'Bukal Padre Garcia Batangas', '+639662393226', 'Filipino', '', '2026-07-07 04:27:40'),
(124, 'Martha Roberta Barrion', 'omavcruz@gmail.com', 'Amadeo, Cavite', '09988606939', 'Filipino', '', '2026-07-12 01:53:16'),
(125, 'Archie', 'Meneses', 'murexdps_ajm@yahoo.com', '09989580681', 'Filipino', '', '2026-07-12 02:09:47'),
(126, 'JEREMY FADRI', 'fadrijeremy@gmail.com', '173 selecta drive balintawak Quezon City', '09567284777', 'Filipino', '', '2026-07-17 08:12:31'),
(127, 'JEREMY FADRI', 'fadrijeremy@gmail.com', '173 selecta drive balintawak Quezon City', '09567284777', 'Filipino', '', '2026-07-18 04:57:31'),
(128, 'Precious', 'precious25taruc@gmail.com', 'Valenzuela City', '09422690663', 'Filipino', '', '2026-07-19 02:08:33'),
(129, 'Malcolm Jose', 'malcolmjose16@gmail.com', 'manila', '09171350116', 'Filipino', '', '2026-07-19 02:56:20'),
(130, 'Paula Canoza', 'paugcanoza@gmail.com', 'Mabalacat Pampanga', '+639690870107', 'Filipino', '', '2026-07-19 02:58:31'),
(131, 'Edwin Macabante', 'macabante.edwin@gmail.com', 'San Jose City, Nueva Ecija', '09453748940', 'Filipino', '', '2026-07-19 02:59:50'),
(132, 'Precious', 'precious25taruc@gmail.com', 'Valenzuela City', '09422690663', 'Filipino', '', '2026-07-20 05:39:51'),
(133, 'Precious', 'precious25taruc@gmail.com', 'Valenzuela City', '09422690663', 'Filipino', '', '2026-07-20 14:08:32'),
(134, 'Carlos Nalupta', 'carlos.nalupta30@gmail.com', 'Ilocos Norte Paoay', '09773268636', 'Australian', '', '2026-08-04 04:17:41'),
(135, 'Carlos Nalupta', 'carlos.nalupta30@gmail.com', 'Ilocos Norte Paoay', '09773268636', 'Australian', '', '2026-08-04 05:57:26'),
(136, 'Carlos Nalupta', 'carlos.nalupta30@gmail.com', 'Ilocos Norte Paoay', '09773268636', 'Australian', '', '2026-08-04 09:32:18'),
(137, 'Camille Pacheco', 'rpcamilla108@gmail.com', 'Qc', '09175131914', 'Filipino', '', '2026-08-16 02:02:19'),
(138, 'Kresia Angela M. Tabuada', 'kmtbauada8@gmail.com', 'Fort Bonifacio, Taguig City', '09164331559', 'Filipino', '', '2026-08-16 02:02:27'),
(139, 'Chris', 'clchun4352@icloud.com', 'Makati', '+639295907777', 'Chinese', '', '2026-08-16 02:10:14'),
(140, 'annie gonzales', 'annie.gonzales@deped.gov.ph', 'Purok 2', '09085783675', 'Filipino', '', '2026-08-16 02:13:15'),
(141, 'Julie Ann De Guzman Carlos', 'julieanndeguzman96@gmail.com', 'Basista, Pangasinan', '09761553310', 'Filipino', '', '2026-08-16 03:10:35'),
(142, 'Nowell Paolo Sim', 'nowellsim@gmail.com', '1336 Soler St. RM 1104 Grand Century Mansion', '09178120051', 'Filipino', '', '2026-08-22 01:17:49');

-- --------------------------------------------------------

--
-- Table structure for table `stays`
--

CREATE TABLE `stays` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `room_no` varchar(20) NOT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `first_stay` varchar(10) DEFAULT NULL,
  `purpose_of_stay` varchar(100) DEFAULT NULL,
  `other_purpose_text` varchar(255) DEFAULT NULL,
  `find_out_about_us` varchar(100) DEFAULT NULL,
  `other_find_out_text` varchar(255) DEFAULT NULL,
  `mode_of_reservation` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stays`
--

INSERT INTO `stays` (`id`, `guest_id`, `room_no`, `check_in`, `check_out`, `first_stay`, `purpose_of_stay`, `other_purpose_text`, `find_out_about_us`, `other_find_out_text`, `mode_of_reservation`, `created_at`) VALUES
(23, 23, '476', '2026-03-26', '2026-03-28', 'Yes', 'Holiday', '', 'Others', 'Klook', 'Hotel Website', '2026-03-28 02:42:06'),
(25, 25, '352', '2026-04-01', '2026-04-04', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-04 01:23:40'),
(26, 26, '134', '2026-04-02', '2026-04-04', 'Yes', 'Holiday', '', 'Others', 'Friends', 'Hotel Website', '2026-04-04 02:40:59'),
(27, 27, '155', '2026-04-02', '2026-04-04', 'Yes', 'Holiday', '', 'Others', 'Friend recommended', 'Hotel Website', '2026-04-04 02:51:05'),
(28, 28, '432', '2026-04-02', '2026-04-04', 'Yes', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-04 03:18:14'),
(30, 30, '374', '2026-04-01', '2026-04-04', 'No', 'Holiday', '', 'Others', 'Aa', 'Hotel Website', '2026-04-04 04:30:11'),
(31, 31, '261', '2026-04-02', '2026-04-04', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-04 10:51:33'),
(33, 33, '243', '2026-04-03', '2026-04-05', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-04-05 02:29:03'),
(34, 34, '371', '2026-04-04', '2026-04-05', 'No', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-04-05 02:45:37'),
(35, 35, '346', '2026-04-04', '2026-04-05', 'No', 'Holiday', '', 'Others', 'Family', 'Hotel Website', '2026-04-05 03:00:13'),
(36, 36, '256', '2026-04-03', '2026-04-05', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-05 03:33:48'),
(38, 38, '372', '2026-04-02', '2026-04-05', 'Yes', 'Holiday', '', 'Internet', '', 'Email', '2026-04-06 03:39:12'),
(39, 39, '555', '2026-04-07', '2026-04-09', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-09 02:34:03'),
(40, 40, '551', '2026-04-08', '2026-04-09', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-04-09 03:07:34'),
(41, 41, '356', '2026-04-06', '2026-04-09', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-04-09 03:17:06'),
(42, 42, '446', '2026-04-06', '2026-04-09', 'Yes', 'Holiday', '', 'Others', 'N/a', 'Hotel Website', '2026-04-09 03:28:04'),
(43, 43, '332', '2026-04-07', '2026-04-09', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-04-09 04:26:13'),
(44, 44, '550', '2026-04-07', '2026-04-09', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-09 04:48:57'),
(45, 45, '550', '2026-04-07', '2026-04-09', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-09 04:49:37'),
(46, 46, '460', '2026-04-06', '2026-04-12', 'Yes', 'Holiday', '', 'Internet', '', 'Email', '2026-04-09 08:18:14'),
(47, 47, '452', '2026-04-09', '2026-04-10', 'No', 'Business', '', 'Others', 'I frequent Baguio', 'Hotel Website', '2026-04-10 02:37:07'),
(48, 48, '246', '2026-04-08', '2026-04-10', 'Yes', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-04-10 03:04:32'),
(49, 49, '333', '2026-04-08', '2026-04-10', 'Yes', 'Others', 'Vacation', 'Hotel Website', '', 'Hotel Website', '2026-04-10 03:35:40'),
(50, 50, '443', '2026-04-17', '2026-04-18', 'Yes', 'Others', 'casual travel', 'Internet', '', 'Travel Agency', '2026-04-18 02:30:59'),
(51, 51, '147', '2026-04-16', '2026-04-18', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-04-18 02:44:19'),
(52, 52, '536', '2026-04-16', '2026-04-18', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-04-18 03:20:36'),
(53, 53, '341', '2026-04-17', '2026-04-18', 'No', 'Holiday', '', 'Print Advertisement', '', 'Walk-in', '2026-04-18 04:08:50'),
(54, 54, '536', '2026-04-16', '2026-04-18', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-04-18 04:31:29'),
(55, 55, '443', '2026-04-17', '2026-04-18', 'Yes', 'Others', 'casual travel', 'Internet', '', 'Travel Agency', '2026-04-18 09:58:32'),
(56, 56, '458', '2026-04-18', '2026-04-20', 'No', 'Holiday', '', 'Hotel Website', '', 'Walk-in', '2026-04-20 02:34:37'),
(57, 57, '000', '2026-05-08', '2026-05-10', 'No', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-05-10 00:30:06'),
(58, 58, '480', '2026-05-09', '2026-05-10', 'Yes', 'Holiday', '', 'Others', 'Friend had Christmas dinner here last year', 'Email', '2026-05-10 05:48:17'),
(59, 59, '360', '2026-05-13', '2026-05-15', 'No', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-05-14 22:09:18'),
(60, 60, '462', '2026-05-16', '2026-05-17', 'Yes', 'Holiday', '', 'Others', 'Agoda', 'Hotel Website', '2026-05-17 02:42:22'),
(61, 61, '250', '2026-05-19', '2026-05-20', 'Yes', 'Others', 'Vacation', 'Hotel Website', '', 'Hotel Website', '2026-05-20 03:14:40'),
(62, 62, '443', '2026-05-14', '2026-05-21', 'Yes', 'Others', 'Visiting family and friends', 'Others', 'Family Friends', 'Hotel Website', '2026-05-21 00:12:32'),
(63, 63, '241', '2026-05-21', '2026-05-23', 'Yes', 'Holiday', '', 'Others', 'Family, word of mouth', 'Travel Agency', '2026-05-23 01:08:18'),
(64, 64, '533', '2026-05-22', '2026-05-23', 'Yes', 'Others', 'Celebrate Anniversary', 'Internet', '', 'Travel Agency', '2026-05-23 02:49:39'),
(65, 65, '263', '2026-05-22', '2026-05-23', 'Yes', 'Business', '', 'Hotel Website', '', 'Phone', '2026-05-23 03:14:17'),
(66, 66, '263', '2026-05-22', '2026-05-23', 'Yes', 'Business', '', 'Hotel Website', '', 'Phone', '2026-05-23 11:30:46'),
(67, 67, '533', '2026-05-22', '2026-05-23', 'Yes', 'Others', 'Celebrate Anniversary', 'Internet', '', 'Travel Agency', '2026-05-23 22:08:33'),
(68, 68, '518', '2026-05-23', '2026-05-24', 'Yes', 'Holiday', '', 'Travel Agency', '', 'Travel Agency', '2026-05-24 02:10:17'),
(69, 69, '366', '2026-06-05', '2026-06-06', 'Yes', 'Holiday', '', 'Travel Agency', '', 'Travel Agency', '2026-06-06 02:14:04'),
(70, 70, '555', '2026-06-03', '2026-06-07', 'No', 'Business', '', 'Hotel Website', '', 'Email', '2026-06-07 04:07:34'),
(71, 71, '451', '2026-06-12', '2026-06-14', 'No', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-06-13 22:58:27'),
(72, 72, '363', '2026-06-12', '2026-06-14', 'No', 'Holiday', '', 'Others', 'Brothet', 'Phone', '2026-06-14 00:40:29'),
(73, 73, '478', '2026-06-12', '2026-06-14', 'No', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-06-14 00:53:39'),
(74, 74, '335', '2026-06-13', '2026-06-14', 'Yes', 'Holiday', '', 'Others', 'Family', 'Hotel Website', '2026-06-14 01:01:16'),
(75, 75, '371', '2026-06-12', '2026-06-14', 'No', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-06-14 01:09:53'),
(76, 76, '350', '2026-06-12', '2026-06-14', 'Yes', 'Business', '', 'Internet', '', 'Hotel Website', '2026-06-14 01:25:47'),
(77, 77, '471', '2026-06-12', '2026-06-14', 'No', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-06-14 01:31:31'),
(78, 78, '467', '2026-06-11', '2026-06-14', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-06-14 01:35:55'),
(79, 79, '423', '2026-06-11', '2026-06-14', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-06-14 02:31:00'),
(80, 80, '423', '2026-06-11', '2026-06-14', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-06-14 05:42:49'),
(81, 81, '380', '2026-06-18', '2026-06-20', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Walk-in', '2026-06-20 03:07:24'),
(82, 82, '453', '2026-06-18', '2026-06-20', 'Yes', 'Others', 'Leisure', 'Internet', '', 'Hotel Website', '2026-06-20 03:15:54'),
(83, 83, '380', '2026-06-18', '2026-06-20', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Travel Agency', '2026-06-20 03:25:12'),
(84, 84, '539', '2026-06-19', '2026-06-20', 'No', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-06-20 03:36:44'),
(85, 85, '546', '2026-06-19', '2026-06-20', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-06-20 03:43:56'),
(86, 86, '380', '2026-06-18', '2026-06-20', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Walk-in', '2026-06-20 04:41:17'),
(87, 87, '274', '2026-06-20', '2026-06-21', 'Yes', 'Holiday', '', 'Print Advertisement', '', 'Hotel Website', '2026-06-21 00:19:13'),
(88, 88, '341', '2026-06-19', '2026-06-21', 'Yes', 'Business', '', 'Others', 'Barangay Seminar', 'Travel Agency', '2026-06-21 00:52:41'),
(89, 89, '157', '2026-06-19', '2026-06-21', 'Yes', 'Others', 'Seminar of accounting of Bacoor, Cavite', 'Others', 'Agoda', 'Phone', '2026-06-21 01:04:25'),
(90, 90, '157', '2026-06-19', '2026-06-21', 'Yes', 'Others', '', 'Others', '', 'Walk-in', '2026-06-21 01:08:52'),
(91, 91, '162', '2026-06-19', '2026-06-21', 'Yes', 'Others', 'Seminar', 'Internet', '', 'Hotel Website', '2026-06-21 01:15:02'),
(92, 92, '471', '2026-06-19', '2026-06-21', 'Yes', 'Business', '', 'Others', 'brgy seminar', 'Travel Agency', '2026-06-21 01:23:22'),
(93, 93, '471', '2026-06-19', '2026-06-21', 'Yes', 'Others', 'Government', 'Internet', '', 'Hotel Website', '2026-06-21 01:23:28'),
(94, 94, '366', '2026-06-19', '2026-06-21', 'Yes', 'Others', 'Seminar', 'Internet', '', 'Hotel Website', '2026-06-21 01:35:06'),
(95, 95, '452', '2026-06-19', '2026-06-21', 'No', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-06-21 01:42:13'),
(96, 96, '358', '2026-06-19', '2026-06-21', 'Yes', 'Business', '', 'Hotel Website', '', 'Hotel Website', '2026-06-21 02:33:33'),
(97, 97, '570', '2026-06-20', '2026-06-21', 'Yes', 'Holiday', '', 'Internet', '', 'Email', '2026-06-21 02:37:29'),
(98, 98, '364', '2026-06-19', '2026-06-21', 'Yes', 'Business', '', 'Internet', '', 'Travel Agency', '2026-06-21 02:46:24'),
(99, 99, '359', '2026-06-19', '2026-06-21', 'Yes', 'Others', 'Seminar', 'Others', 'Seminar', 'Phone', '2026-06-21 02:46:50'),
(100, 100, '359', '2026-06-19', '2026-06-21', 'Yes', 'Others', 'Seminar', 'Others', 'Seminar', 'Travel Agency', '2026-06-21 02:47:53'),
(101, 101, '247', '2026-06-19', '2026-06-21', 'Yes', 'Others', 'Seminar', 'Others', 'Seminar', 'Travel Agency', '2026-06-21 03:27:33'),
(102, 102, '138', '2026-06-25', '2026-06-28', 'Yes', 'Others', 'vacation', 'Hotel Website', '', 'Hotel Website', '2026-06-28 02:25:25'),
(103, 103, '555', '2026-06-26', '2026-06-28', 'Yes', 'Others', 'Birthday celeb', 'Internet', '', 'Hotel Website', '2026-06-28 02:32:56'),
(104, 104, '346', '2026-06-26', '2026-06-28', 'No', 'Holiday', '', 'Others', 'Friends', 'Hotel Website', '2026-06-28 03:04:14'),
(105, 105, '437', '2026-06-27', '2026-06-28', 'No', 'Business', '', 'Internet', '', 'Phone', '2026-06-28 03:29:13'),
(106, 106, '365', '2026-06-26', '2026-06-28', 'Yes', 'Business', '', 'Hotel Website', '', 'Hotel Website', '2026-06-28 03:44:39'),
(107, 107, '136', '2026-06-26', '2026-06-28', 'Yes', 'Holiday', '', 'Travel Agency', '', 'Travel Agency', '2026-06-28 04:08:39'),
(108, 108, '571', '2026-06-27', '2026-06-28', 'No', 'Others', 'Anniversary', 'Others', 'Family', 'Travel Agency', '2026-06-28 04:16:50'),
(109, 109, '136', '2026-06-26', '2026-06-28', 'Yes', 'Holiday', '', 'Travel Agency', '', 'Travel Agency', '2026-06-28 04:29:51'),
(110, 110, '571', '2026-06-27', '2026-06-28', 'No', 'Others', 'Anniversary', 'Others', 'Family', 'Travel Agency', '2026-06-28 05:13:47'),
(111, 111, '346', '2026-06-29', '2026-07-02', 'No', 'Holiday', '', 'Others', 'stayed at Manor, wanted to stay', 'Travel Agency', '2026-07-02 03:04:31'),
(112, 112, '373', '2026-07-03', '2026-07-05', 'Yes', 'Holiday', '', 'Internet', '', 'Phone', '2026-07-05 00:52:37'),
(113, 113, '363', '2026-07-04', '2026-07-05', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-07-05 01:07:31'),
(114, 114, '432, 367', '2026-07-02', '2026-07-05', 'Yes', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-07-05 01:21:03'),
(115, 115, '556', '2026-07-04', '2026-07-05', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-07-05 01:23:50'),
(116, 116, '351', '2026-07-04', '2026-07-05', 'Yes', 'Others', 'Family outing', 'Others', 'google', 'Hotel Website', '2026-07-05 01:26:17'),
(117, 117, '350', '2026-07-03', '2026-07-05', 'Yes', 'Others', 'Birthday Celebration', 'Hotel Website', '', 'Hotel Website', '2026-07-05 02:03:36'),
(118, 118, '372', '2026-07-03', '2026-07-05', 'No', 'Holiday', '', 'Others', 'Regulars', 'Hotel Website', '2026-07-05 02:31:15'),
(119, 119, '478', '2026-07-04', '2026-07-05', 'Yes', 'Holiday', '', 'Others', 'Social media', 'Travel Agency', '2026-07-05 03:00:05'),
(120, 120, '358', '2026-07-04', '2026-07-05', 'Yes', 'Others', 'Birthday', 'Internet', '', 'Travel Agency', '2026-07-05 03:28:46'),
(121, 121, '358', '2026-07-04', '2026-07-05', 'Yes', 'Others', 'Birthday', 'Internet', '', 'Travel Agency', '2026-07-05 05:17:00'),
(122, 122, '363', '2026-07-04', '2026-07-05', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-07-06 10:52:55'),
(123, 123, '363', '2026-07-04', '2026-07-05', 'Yes', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-07-07 04:27:40'),
(124, 124, '333', '2026-07-10', '2026-07-12', 'Yes', 'Others', 'Leisure', 'Others', 'Agoda', 'Hotel Website', '2026-07-12 01:53:16'),
(125, 125, '241', '2026-07-11', '2026-07-12', 'No', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-07-12 02:09:47'),
(126, 126, '466', '2026-07-15', '2026-07-17', 'Yes', 'Others', 'ANNIVERSARY', 'Others', 'AGODA', 'Email', '2026-07-17 08:12:31'),
(127, 127, '466', '2026-07-15', '2026-07-17', 'Yes', 'Others', 'ANNIVERSARY', 'Others', 'AGODA', 'Email', '2026-07-18 04:57:31'),
(128, 128, '452', '2026-07-18', '2026-07-19', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-07-19 02:08:33'),
(129, 129, '258', '2026-07-17', '2026-07-19', 'No', 'Holiday', '', 'Internet', '', 'Travel Agency', '2026-07-19 02:56:20'),
(130, 130, '141', '2026-07-18', '2026-07-19', 'No', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-07-19 02:58:31'),
(131, 131, '354', '2026-07-18', '2026-07-19', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-07-19 02:59:50'),
(132, 132, '452', '2026-07-18', '2026-07-19', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-07-20 05:39:51'),
(133, 133, '452', '2026-07-18', '2026-07-19', 'Yes', 'Holiday', '', 'Hotel Website', '', 'Hotel Website', '2026-07-20 14:08:32'),
(134, 134, '456', '2026-08-02', '2026-08-04', 'No', 'Holiday', '', 'Others', 'Word of mouth', 'Email', '2026-08-04 04:17:41'),
(135, 135, '456', '2026-08-02', '2026-08-04', 'No', 'Holiday', '', 'Others', 'Word of mouth', 'Email', '2026-08-04 05:57:26'),
(136, 136, '456', '2026-08-02', '2026-08-04', 'No', 'Holiday', '', 'Others', 'Word of mouth', 'Email', '2026-08-04 09:32:18'),
(137, 137, '261', '2026-08-15', '2026-08-16', 'No', 'Holiday', '', 'Hotel Website', '', 'Phone', '2026-08-16 02:02:19'),
(138, 138, '432', '2026-08-14', '2026-08-16', 'Yes', 'Holiday', '', 'Others', 'Family', 'Hotel Website', '2026-08-16 02:02:27'),
(139, 139, '367', '2026-08-15', '2026-08-16', 'No', 'Holiday', '', 'Internet', '', 'Hotel Website', '2026-08-16 02:10:14'),
(140, 140, '359', '2026-08-15', '2026-08-16', 'No', 'Others', 'Staycation and relaxation', 'Others', 'We are regular guest in this hotel.', 'Hotel Website', '2026-08-16 02:13:15'),
(141, 141, '555', '2026-08-15', '2026-08-16', 'Yes', 'Holiday', '', 'Internet', '', 'Phone', '2026-08-16 03:10:35'),
(142, 142, '258 and 268', '2026-08-21', '2026-08-22', 'No', 'Holiday', '', 'Internet', '', 'Email', '2026-08-22 01:17:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `ef_admin_users`
--
ALTER TABLE `ef_admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `ef_attendees`
--
ALTER TABLE `ef_attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `ef_events`
--
ALTER TABLE `ef_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_event_identity` (`event_name`,`event_date`,`event_time`,`location_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `ef_event_feedbacks`
--
ALTER TABLE `ef_event_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendee_id` (`attendee_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `ef_locations`
--
ALTER TABLE `ef_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_location_name` (`location_name`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stay_id` (`stay_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_overall_rating` (`overall_rating`);

--
-- Indexes for table `feedbacks_legacy`
--
ALTER TABLE `feedbacks_legacy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_room_no` (`room_no`),
  ADD KEY `idx_overall_rating` (`overall_rating`);

--
-- Indexes for table `feedback_fnb`
--
ALTER TABLE `feedback_fnb`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `feedback_foh`
--
ALTER TABLE `feedback_foh`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `feedback_guestroom`
--
ALTER TABLE `feedback_guestroom`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `feedback_helpful_staff`
--
ALTER TABLE `feedback_helpful_staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_id` (`feedback_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stays`
--
ALTER TABLE `stays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_id` (`guest_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ef_admin_users`
--
ALTER TABLE `ef_admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ef_attendees`
--
ALTER TABLE `ef_attendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ef_events`
--
ALTER TABLE `ef_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ef_event_feedbacks`
--
ALTER TABLE `ef_event_feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ef_locations`
--
ALTER TABLE `ef_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `feedbacks_legacy`
--
ALTER TABLE `feedbacks_legacy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback_helpful_staff`
--
ALTER TABLE `feedback_helpful_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `stays`
--
ALTER TABLE `stays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ef_attendees`
--
ALTER TABLE `ef_attendees`
  ADD CONSTRAINT `ef_attendees_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `ef_events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ef_events`
--
ALTER TABLE `ef_events`
  ADD CONSTRAINT `ef_events_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `ef_locations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ef_event_feedbacks`
--
ALTER TABLE `ef_event_feedbacks`
  ADD CONSTRAINT `ef_event_feedbacks_ibfk_1` FOREIGN KEY (`attendee_id`) REFERENCES `ef_attendees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`stay_id`) REFERENCES `stays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_fnb`
--
ALTER TABLE `feedback_fnb`
  ADD CONSTRAINT `feedback_fnb_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_foh`
--
ALTER TABLE `feedback_foh`
  ADD CONSTRAINT `feedback_foh_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_guestroom`
--
ALTER TABLE `feedback_guestroom`
  ADD CONSTRAINT `feedback_guestroom_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_helpful_staff`
--
ALTER TABLE `feedback_helpful_staff`
  ADD CONSTRAINT `feedback_helpful_staff_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stays`
--
ALTER TABLE `stays`
  ADD CONSTRAINT `stays_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
