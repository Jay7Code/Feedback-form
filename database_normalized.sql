CREATE TABLE IF NOT EXISTS `guests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guest_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `other_nationality_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`guest_id`) REFERENCES `guests`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stay_id` int(11) NOT NULL,
  `overall_rating` tinyint(4) NOT NULL DEFAULT 0,
  `general_comments` text DEFAULT NULL,
  `repeat_visit` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`stay_id`) REFERENCES `stays`(`id`) ON DELETE CASCADE,
  KEY `idx_created_at` (`created_at`),
  KEY `idx_overall_rating` (`overall_rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedback_foh` (
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
  `courteousness` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`feedback_id`),
  FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedback_fnb` (
  `feedback_id` int(11) NOT NULL,
  `food_quality` tinyint(4) NOT NULL DEFAULT 0,
  `serving_time` tinyint(4) NOT NULL DEFAULT 0,
  `grooming` tinyint(4) NOT NULL DEFAULT 0,
  `behavior` tinyint(4) NOT NULL DEFAULT 0,
  `fnb_service` tinyint(4) NOT NULL DEFAULT 0,
  `bar` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`feedback_id`),
  FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedback_guestroom` (
  `feedback_id` int(11) NOT NULL,
  `cleanliness` tinyint(4) NOT NULL DEFAULT 0,
  `ambiance` tinyint(4) NOT NULL DEFAULT 0,
  `comfort` tinyint(4) NOT NULL DEFAULT 0,
  `bathroom` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`feedback_id`),
  FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `feedback_helpful_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feedback_id` int(11) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
