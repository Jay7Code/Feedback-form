<?php
require 'config.php';
try {
    $pdo = getDBConnection();
    // Rename old table
    $pdo->exec("RENAME TABLE feedbacks TO feedbacks_legacy");
    
    // Create new tables
    $sql = file_get_contents('database_normalized.sql');
    $pdo->exec($sql);
    echo "Recreated.\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
