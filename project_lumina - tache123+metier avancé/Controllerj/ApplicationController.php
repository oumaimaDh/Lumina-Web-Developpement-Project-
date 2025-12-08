<?php
class ApplicationController {
    private $db;
    private $uploadPath = 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\uploads/';

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=lumina', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create uploads directory if it doesn't exist
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    public function addApplication($data) {
        try {
            $sql = "INSERT INTO applications (offer_id, association_id, full_name, email, phone, profession, 
                    desired_salary, preferred_location, skills, experience, experience_level, 
                    cv_filename, cover_letter, status, applied_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                $data['offer_id'],
                $data['association_id'],
                $data['full_name'],
                $data['email'],
                $data['phone'],
                $data['profession'],
                $data['desired_salary'],
                $data['preferred_location'],
                $data['skills'],
                $data['experience'],
                $data['experience_level'],
                $data['cv_filename'],
                $data['cover_letter'],
                $data['status']
            ]);
            
            return $success;
        } catch (PDOException $e) {
            error_log("ApplicationController Error: " . $e->getMessage());
            return false;
        }
    }
public function getApplications() {
    try {
        $sql = "SELECT DISTINCT a.*, o.title as offer_title, ass.name as association_name 
                FROM applications a 
                LEFT JOIN offers o ON a.offer_id = o.id 
                LEFT JOIN associations ass ON a.association_id = ass.id 
                ORDER BY a.applied_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("ApplicationController Error: " . $e->getMessage());
        return [];
    }
}

public function getApplicationsByStatus($status) {
    try {
        $sql = "SELECT DISTINCT a.*, o.title as offer_title, ass.name as association_name 
                FROM applications a 
                LEFT JOIN offers o ON a.offer_id = o.id 
                LEFT JOIN associations ass ON a.association_id = ass.id 
                WHERE a.status = ? 
                ORDER BY a.applied_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("ApplicationController Error: " . $e->getMessage());
        return [];
    }
}

    public function updateApplicationStatus($id, $status) {
        try {
            $sql = "UPDATE applications SET status = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log("ApplicationController Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteApplication($id) {
        try {
            // Get CV filename before deletion
            $sql = "SELECT cv_filename FROM applications WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $application = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Delete the application
            $sql = "DELETE FROM applications WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([$id]);
            
            // Delete the CV file if it exists
            if ($success && $application && !empty($application['cv_filename'])) {
                $filePath = $this->uploadPath . $application['cv_filename'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            return $success;
        } catch (PDOException $e) {
            error_log("ApplicationController Error: " . $e->getMessage());
            return false;
        }
    }

    public function getApplicationStats() {
        try {
            $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                    SUM(CASE WHEN status = 'viewed' THEN 1 ELSE 0 END) as viewed,
                    SUM(CASE WHEN status = 'interview' THEN 1 ELSE 0 END) as interview
                    FROM applications";
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ApplicationController Error: " . $e->getMessage());
            return ['total' => 0, 'submitted' => 0, 'viewed' => 0, 'interview' => 0];
        }
    }

    /**
     * Récupère l'analyse IA détaillée
     */
    public function getAIAnalysis($applicationId) {
        require_once 'C:\xampp\htdocs\project_lumina - tache1+tache2 +tache3\Controllerj\AIMatchingController.php';
        $aiController = new AIMatchingController();
        return $aiController->getDetailedAnalysis($applicationId);
    }
}
?>