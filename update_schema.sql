-- This script applies the new schema structures dynamically to the existing database.

-- 1. Modify the `stays` table
ALTER TABLE `stays` 
ADD COLUMN `find_out_about_us` VARCHAR(100) DEFAULT NULL AFTER `other_purpose_text`,
ADD COLUMN `other_find_out_text` VARCHAR(255) DEFAULT NULL AFTER `find_out_about_us`,
ADD COLUMN `mode_of_reservation` VARCHAR(100) DEFAULT NULL AFTER `other_find_out_text`;

-- 2. Modify the `feedbacks` table
-- Add the consolidated general_comments and repeat_visit
ALTER TABLE `feedbacks`
ADD COLUMN `general_comments` TEXT DEFAULT NULL AFTER `overall_rating`,
ADD COLUMN `repeat_visit` VARCHAR(10) DEFAULT NULL AFTER `general_comments`;

-- Drop the old scattered comment fields that are no longer used
ALTER TABLE `feedbacks`
DROP COLUMN `suggestions_future`,
DROP COLUMN `other_comments`;

-- 3. Modify the `feedback_foh` table
-- Add the new rating categories for Hotel Process and Forest Wing Hospitality
ALTER TABLE `feedback_foh`
ADD COLUMN `check_in_rating` TINYINT(4) NOT NULL DEFAULT 0 AFTER `reservations`,
ADD COLUMN `check_out_rating` TINYINT(4) NOT NULL DEFAULT 0 AFTER `check_in_rating`,
ADD COLUMN `friendliness` TINYINT(4) NOT NULL DEFAULT 0 AFTER `security`,
ADD COLUMN `attentiveness` TINYINT(4) NOT NULL DEFAULT 0 AFTER `friendliness`,
ADD COLUMN `courteousness` TINYINT(4) NOT NULL DEFAULT 0 AFTER `attentiveness`;

-- Remove comment trace from foh
ALTER TABLE `feedback_foh`
DROP COLUMN `frontdesk_comments`,
DROP COLUMN `overall_service`;

-- 4. Modify the `feedback_fnb` table
-- Remove comment trace from fnb and unused fields (wait_staff, bartender)
ALTER TABLE `feedback_fnb`
DROP COLUMN `fnb_comments`,
DROP COLUMN `wait_staff`,
DROP COLUMN `bartender`;

-- 5. Create the new `feedback_guestroom` table
CREATE TABLE IF NOT EXISTS `feedback_guestroom` (
  `feedback_id` int(11) NOT NULL,
  `cleanliness` tinyint(4) NOT NULL DEFAULT 0,
  `ambiance` tinyint(4) NOT NULL DEFAULT 0,
  `comfort` tinyint(4) NOT NULL DEFAULT 0,
  `bathroom` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`feedback_id`),
  FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
