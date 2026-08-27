<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * DATABASE BACKUP UTILITY (Pure PHP)
 * Generates clean, standard SQL dump files for MySQL / MariaDB
 * without requiring shell access or mysqldump executable.
 * ═══════════════════════════════════════════════════════════════
 */

require_once __DIR__ . '/../config.php';

class DatabaseBackup
{
    public static function createBackup(?string $outputDir = null): array
    {
        $mysqli = getDBConnection();
        $dbName = DB_NAME;

        if ($outputDir === null) {
            $outputDir = __DIR__ . '/backups';
        }
        if (!is_dir($outputDir)) {
            @mkdir($outputDir, 0755, true);
        }

        $timestamp = date('Y-m-d_His');
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        $filepath = rtrim($outputDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        $fp = fopen($filepath, 'w');
        if (!$fp) {
            throw new Exception("Unable to create backup file at: {$filepath}");
        }

        // Header comments
        $header = "-- ========================================================\n";
        $header .= "-- John Hay Hotels - Forest Wing\n";
        $header .= "-- Database Backup: {$dbName}\n";
        $header .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $header .= "-- Host: " . DB_HOST . "\n";
        $header .= "-- ========================================================\n\n";
        $header .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $header .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $header .= "SET NAMES utf8mb4;\n\n";
        fwrite($fp, $header);

        // Get all tables
        $tablesResult = $mysqli->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
        $tables = [];
        while ($row = $tablesResult->fetch_array()) {
            $tables[] = $row[0];
        }

        foreach ($tables as $table) {
            fwrite($fp, "\n-- --------------------------------------------------------\n");
            fwrite($fp, "-- Table structure for table `{$table}`\n");
            fwrite($fp, "-- --------------------------------------------------------\n\n");
            fwrite($fp, "DROP TABLE IF EXISTS `{$table}`;\n");

            // Create table DDL
            $createResult = $mysqli->query("SHOW CREATE TABLE `{$table}`");
            if ($createRow = $createResult->fetch_array()) {
                fwrite($fp, $createRow[1] . ";\n\n");
            }

            // Table Data
            fwrite($fp, "-- Dumping data for table `{$table}`\n");
            $dataResult = $mysqli->query("SELECT * FROM `{$table}`");
            $numRows = $dataResult->num_rows;

            if ($numRows > 0) {
                $fieldsInfo = $dataResult->fetch_fields();
                $fieldCount = count($fieldsInfo);

                $chunkSize = 100;
                $rowCount = 0;
                $valuesBatch = [];

                while ($row = $dataResult->fetch_array(MYSQLI_NUM)) {
                    $escapedValues = [];
                    for ($i = 0; $i < $fieldCount; $i++) {
                        $val = $row[$i];
                        if ($val === null) {
                            $escapedValues[] = "NULL";
                        } elseif (is_numeric($val) && !is_string($val)) {
                            $escapedValues[] = $val;
                        } else {
                            $escapedValues[] = "'" . $mysqli->real_escape_string($val) . "'";
                        }
                    }
                    $valuesBatch[] = "(" . implode(", ", $escapedValues) . ")";
                    $rowCount++;

                    if (count($valuesBatch) >= $chunkSize || $rowCount == $numRows) {
                        $insertSql = "INSERT INTO `{$table}` VALUES \n  " . implode(",\n  ", $valuesBatch) . ";\n";
                        fwrite($fp, $insertSql);
                        $valuesBatch = [];
                    }
                }
            } else {
                fwrite($fp, "-- (No rows)\n");
            }
            fwrite($fp, "\n");
        }

        // Footer
        fwrite($fp, "SET FOREIGN_KEY_CHECKS=1;\n");
        fwrite($fp, "-- End of backup\n");
        fclose($fp);

        return [
            'success' => true,
            'filename' => $filename,
            'filepath' => $filepath,
            'filesize' => filesize($filepath),
            'filesize_formatted' => round(filesize($filepath) / 1024, 2) . ' KB',
            'tables_count' => count($tables),
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }
}
?>
