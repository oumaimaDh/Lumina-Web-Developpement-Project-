<?php
/**
 * Setup script to create the notifications table
 * Run this file once to create the notification table in your database
 * Access via: http://localhost/Social%20Case/view_menna/Back/setup_notifications.php
 */

// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
require_once $basePath . DIRECTORY_SEPARATOR . 'config.php';

$db = Config::getConnexion();

$sql = "CREATE TABLE IF NOT EXISTS `notification` (
  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  `id_case` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_notification`),
  KEY `idx_id_case` (`id_case`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Setup Notifications</title>";
echo "<style>body{font-family:Arial,sans-serif;padding:20px;max-width:800px;margin:0 auto;}";
echo "h2{color:#333;} table{border-collapse:collapse;width:100%;margin:20px 0;}";
echo "th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f2f2f2;}</style></head><body>";

try {
    // Check if table exists first
    try {
        $checkTable = $db->query("SHOW TABLES LIKE 'notification'");
        $tableExists = $checkTable->rowCount() > 0;
    } catch (Exception $e) {
        $tableExists = false;
    }
    
    if ($tableExists) {
        echo "<h2 style='color: green;'>✓ Notification table already exists!</h2>";
    } else {
        // Try to create table
        try {
            $db->exec($sql);
            echo "<h2 style='color: green;'>✓ Notification table created successfully!</h2>";
            $tableExists = true;
        } catch (Exception $e) {
            echo "<h2 style='color: red;'>✗ Error creating table:</h2>";
            echo "<p style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p>Trying alternative method...</p>";
            
            // Try without IF NOT EXISTS
            try {
                $sql2 = "CREATE TABLE `notification` (
                  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
                  `message` varchar(255) NOT NULL,
                  `id_case` int(11) NOT NULL,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `is_read` tinyint(1) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id_notification`),
                  KEY `idx_id_case` (`id_case`),
                  KEY `idx_is_read` (`is_read`),
                  KEY `idx_created_at` (`created_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
                $db->exec($sql2);
                echo "<h2 style='color: green;'>✓ Notification table created successfully (alternative method)!</h2>";
                $tableExists = true;
            } catch (Exception $e2) {
                echo "<p style='color: red;'>Alternative method also failed: " . htmlspecialchars($e2->getMessage()) . "</p>";
                echo "<p><strong>Please run this SQL manually in phpMyAdmin:</strong></p>";
                echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;overflow-x:auto;'>" . htmlspecialchars($sql) . "</pre>";
            }
        }
    }
    
    // Try to add foreign key constraint if it doesn't exist
    try {
        $db->exec("ALTER TABLE `notification` 
                   ADD CONSTRAINT `fk_notification_case` 
                   FOREIGN KEY (`id_case`) REFERENCES `social_case` (`id_case`) 
                   ON DELETE CASCADE");
        echo "<p style='color: green;'>✓ Foreign key constraint added successfully!</p>";
    } catch (Exception $e) {
        // Foreign key might already exist or table structure might be different
        if (strpos($e->getMessage(), 'Duplicate foreign key') !== false || 
            strpos($e->getMessage(), 'already exists') !== false) {
            echo "<p style='color: orange;'>⚠ Foreign key constraint already exists or cannot be added.</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Could not add foreign key constraint: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p>This is okay - the table will still work without the foreign key constraint.</p>";
        }
    }
    
    if ($tableExists) {
        echo "<hr>";
        echo "<h3>Table Structure:</h3>";
        try {
            $result = $db->query("DESCRIBE notification");
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (Exception $e) {
            echo "<p style='color: orange;'>Could not describe table: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li><a href='notifications.php' style='color: #007bff;'>Go to Notifications Page</a></li>";
    echo "<li><a href='indexb.php' style='color: #007bff;'>Go to Dashboard</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>✗ Error:</h2>";
    echo "<p style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;overflow-x:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";

