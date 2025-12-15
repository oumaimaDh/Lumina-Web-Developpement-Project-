<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/notificationmodel.php';

class NotificationController {

    // ================= CREATE NOTIFICATION =================
    public function createNotification($message, $id_case) {
        $db = Config::getConnexion();
        
        // Check if notification table exists
        try {
            $checkTable = $db->query("SHOW TABLES LIKE 'notification'");
            if ($checkTable->rowCount() == 0) {
                error_log('Notification table does not exist. Please run setup_notifications.php');
                return false;
            }
        } catch(Exception $e) {
            error_log('Error checking notification table: ' . $e->getMessage());
            return false;
        }
        
        $sql = "INSERT INTO notification (message, id_case, created_at, is_read) 
                VALUES (:message, :id_case, NOW(), 0)";
        try {
            $query = $db->prepare($sql);
            $result = $query->execute([
                'message' => $message,
                'id_case' => $id_case
            ]);
            
            if ($result) {
                return $db->lastInsertId();
            } else {
                error_log('Failed to create notification - execute returned false');
                return false;
            }
        } catch(Exception $e) {
            error_log('Error creating notification: ' . $e->getMessage());
            error_log('SQL: ' . $sql);
            error_log('Parameters: message=' . $message . ', id_case=' . $id_case);
            return false;
        }
    }

    // ================= GET ALL NOTIFICATIONS =================
    public function getAllNotifications() {
        $db = Config::getConnexion();
        $sql = "SELECT n.*, sc.name as case_name 
                FROM notification n 
                LEFT JOIN social_case sc ON n.id_case = sc.id_case 
                ORDER BY n.created_at DESC";
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= GET UNREAD COUNT =================
    public function getUnreadCount() {
        $db = Config::getConnexion();
        $sql = "SELECT COUNT(*) as count FROM notification WHERE is_read = 0";
        try {
            $query = $db->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch(Exception $e) {
            return 0;
        }
    }

    // ================= MARK AS READ =================
    public function markAsRead($id_notification) {
        $db = Config::getConnexion();
        $sql = "UPDATE notification SET is_read = 1 WHERE id_notification = :id";
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id_notification]);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    // ================= MARK ALL AS READ =================
    public function markAllAsRead() {
        $db = Config::getConnexion();
        $sql = "UPDATE notification SET is_read = 1 WHERE is_read = 0";
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    // ================= DELETE NOTIFICATION =================
    public function deleteNotification($id_notification) {
        $db = Config::getConnexion();
        $sql = "DELETE FROM notification WHERE id_notification = :id";
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id_notification]);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }
}

