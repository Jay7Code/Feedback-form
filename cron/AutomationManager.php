<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * AUTOMATION SETTINGS & SCHEDULING MANAGER
 * Controls frequency, recipients, and dispatch rules.
 * ═══════════════════════════════════════════════════════════════
 */

require_once __DIR__ . '/../config.php';

class AutomationManager
{
    private static function initTable($mysqli)
    {
        $sql = "CREATE TABLE IF NOT EXISTS `ef_automation_settings` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `frequency` varchar(20) NOT NULL DEFAULT 'weekly',
            `weekday` varchar(15) NOT NULL DEFAULT 'Monday',
            `month_day` int(11) NOT NULL DEFAULT 1,
            `recipient_emails` text NOT NULL,
            `include_pdf` tinyint(1) NOT NULL DEFAULT 1,
            `include_backup` tinyint(1) NOT NULL DEFAULT 1,
            `last_dispatched_at` datetime DEFAULT NULL,
            `last_dispatched_status` text DEFAULT NULL,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $mysqli->query($sql);

        // Ensure at least one default settings row exists
        $check = $mysqli->query("SELECT id FROM `ef_automation_settings` LIMIT 1");
        if ($check->num_rows === 0) {
            $defaultEmail = defined('SMTP_USERNAME') ? SMTP_USERNAME : 'feedback@theforestwing.com';
            $stmt = $mysqli->prepare("INSERT INTO `ef_automation_settings` (`frequency`, `weekday`, `month_day`, `recipient_emails`, `include_pdf`, `include_backup`) VALUES ('weekly', 'Monday', 1, ?, 1, 1)");
            $stmt->bind_param("s", $defaultEmail);
            $stmt->execute();
        }
    }

    public static function getSettings(): array
    {
        $mysqli = getDBConnection();
        self::initTable($mysqli);

        $res = $mysqli->query("SELECT * FROM `ef_automation_settings` ORDER BY id ASC LIMIT 1");
        if ($row = $res->fetch_assoc()) {
            return [
                'frequency' => $row['frequency'] ?? 'weekly',
                'weekday' => $row['weekday'] ?? 'Monday',
                'month_day' => (int)($row['month_day'] ?? 1),
                'recipient_emails' => $row['recipient_emails'] ?? '',
                'include_pdf' => (bool)($row['include_pdf'] ?? 1),
                'include_backup' => (bool)($row['include_backup'] ?? 1),
                'last_dispatched_at' => $row['last_dispatched_at'],
                'last_dispatched_status' => $row['last_dispatched_status'],
                'updated_at' => $row['updated_at']
            ];
        }

        return [
            'frequency' => 'weekly',
            'weekday' => 'Monday',
            'month_day' => 1,
            'recipient_emails' => 'feedback@theforestwing.com',
            'include_pdf' => true,
            'include_backup' => true,
            'last_dispatched_at' => null,
            'last_dispatched_status' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }

    public static function saveSettings(array $data): bool
    {
        $mysqli = getDBConnection();
        self::initTable($mysqli);

        $frequency = in_array($data['frequency'] ?? '', ['daily', 'weekly', 'monthly', 'disabled']) ? $data['frequency'] : 'weekly';
        $weekday = in_array($data['weekday'] ?? '', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']) ? $data['weekday'] : 'Monday';
        $monthDay = max(1, min(28, (int)($data['month_day'] ?? 1)));
        $recipientEmails = trim($data['recipient_emails'] ?? '');
        $includePdf = !empty($data['include_pdf']) ? 1 : 0;
        $includeBackup = !empty($data['include_backup']) ? 1 : 0;

        $stmt = $mysqli->prepare("UPDATE `ef_automation_settings` SET `frequency` = ?, `weekday` = ?, `month_day` = ?, `recipient_emails` = ?, `include_pdf` = ?, `include_backup` = ? WHERE id = 1");
        $stmt->bind_param("ssisii", $frequency, $weekday, $monthDay, $recipientEmails, $includePdf, $includeBackup);
        return $stmt->execute();
    }

    public static function recordDispatchResult(string $statusMessage): void
    {
        $mysqli = getDBConnection();
        self::initTable($mysqli);

        $now = date('Y-m-d H:i:s');
        $stmt = $mysqli->prepare("UPDATE `ef_automation_settings` SET `last_dispatched_at` = ?, `last_dispatched_status` = ? WHERE id = 1");
        $stmt->bind_param("ss", $now, $statusMessage);
        $stmt->execute();
    }

    public static function isDueToRun(array $settings, bool $force = false): array
    {
        if ($force) {
            return ['due' => true, 'reason' => 'Manually triggered / Force run'];
        }

        $frequency = $settings['frequency'] ?? 'weekly';
        if ($frequency === 'disabled') {
            return ['due' => false, 'reason' => 'Automated dispatch is currently disabled'];
        }

        $lastDispatched = $settings['last_dispatched_at'] ? strtotime($settings['last_dispatched_at']) : 0;
        $today = date('Y-m-d');
        $todayDayName = date('l'); // e.g. Monday
        $todayMonthDay = (int)date('j'); // 1 to 31
        $currentMonthYear = date('Y-m');

        if ($frequency === 'daily') {
            if ($lastDispatched && date('Y-m-d', $lastDispatched) === $today) {
                return ['due' => false, 'reason' => 'Daily report already sent today (' . date('Y-m-d', $lastDispatched) . ')'];
            }
            return ['due' => true, 'reason' => 'Due for daily dispatch'];
        }

        if ($frequency === 'weekly') {
            $targetWeekday = $settings['weekday'] ?? 'Monday';
            if (strcasecmp($todayDayName, $targetWeekday) !== 0) {
                return ['due' => false, 'reason' => "Today is {$todayDayName}, but weekly dispatch is scheduled for {$targetWeekday}"];
            }
            // Check if already dispatched in the last 4 days
            if ($lastDispatched && (time() - $lastDispatched) < (4 * 86400)) {
                return ['due' => false, 'reason' => 'Weekly report was already dispatched earlier this week (' . date('Y-m-d H:i', $lastDispatched) . ')'];
            }
            return ['due' => true, 'reason' => "Due for weekly dispatch on {$todayDayName}"];
        }

        if ($frequency === 'monthly') {
            $targetDay = (int)($settings['month_day'] ?? 1);
            if ($todayMonthDay !== $targetDay) {
                return ['due' => false, 'reason' => "Today is day {$todayMonthDay} of month, but monthly dispatch is scheduled for day {$targetDay}"];
            }
            // Check if already dispatched in this month
            if ($lastDispatched && date('Y-m', $lastDispatched) === $currentMonthYear) {
                return ['due' => false, 'reason' => 'Monthly report was already dispatched this month (' . date('Y-m-d H:i', $lastDispatched) . ')'];
            }
            return ['due' => true, 'reason' => "Due for monthly dispatch on day {$todayMonthDay}"];
        }

        return ['due' => false, 'reason' => 'Unknown schedule condition'];
    }
}
?>
