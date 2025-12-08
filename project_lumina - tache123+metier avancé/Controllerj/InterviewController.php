<?php
// Controllerj/InterviewController.php
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\config.php';
class InterviewController {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Config::getConnexion();
    }
    
    /**
     * Planifier une interview
     */
    public function scheduleInterview($data) {
        $sql = "INSERT INTO interviews (application_id, interview_date, interview_time, interview_type, 
                location, meeting_link, notes, duration, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['application_id'],
                $data['interview_date'],
                $data['interview_time'],
                $data['interview_type'],
                $data['location'],
                $data['meeting_link'],
                $data['notes'],
                $data['duration'],
                $data['status']
            ]);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mettre à jour une interview
     */
    public function updateInterview($id, $date, $time, $type, $location, $meetingLink, $notes, $duration, $status) {
        $sql = "UPDATE interviews SET interview_date = ?, interview_time = ?, interview_type = ?, 
                location = ?, meeting_link = ?, notes = ?, duration = ?, status = ?, updated_at = NOW() 
                WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$date, $time, $type, $location, $meetingLink, $notes, $duration, $status, $id]);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupérer les interviews par mois
     */
    public function getInterviewsByMonth($year, $month) {
        $startDate = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));
        $endDate = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
        
        $sql = "SELECT i.*, a.full_name as candidate_name, a.profession as position 
                FROM interviews i 
                JOIN applications a ON i.application_id = a.id 
                WHERE i.interview_date BETWEEN ? AND ? AND i.status != 'cancelled'
                ORDER BY i.interview_date, i.interview_time";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$startDate, $endDate]);
            $interviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Grouper par date
            $grouped = [];
            foreach ($interviews as $interview) {
                $date = $interview['interview_date'];
                if (!isset($grouped[$date])) {
                    $grouped[$date] = [];
                }
                $grouped[$date][] = $interview;
            }
            
            return $grouped;
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer les interviews par date
     */
    public function getInterviewsByDate($date) {
        $sql = "SELECT i.*, a.full_name as candidate_name, a.profession as position 
                FROM interviews i 
                JOIN applications a ON i.application_id = a.id 
                WHERE i.interview_date = ? AND i.status != 'cancelled'
                ORDER BY i.interview_time";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer les prochaines interviews
     */
    public function getUpcomingInterviews() {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+7 days'));
        
        $sql = "SELECT i.*, a.full_name as candidate_name, a.profession as position 
                FROM interviews i 
                JOIN applications a ON i.application_id = a.id 
                WHERE i.interview_date BETWEEN ? AND ? AND i.status = 'scheduled'
                ORDER BY i.interview_date, i.interview_time 
                LIMIT 10";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer les candidatures avec status "interview"
     */
    public function getApplicationsForInterview() {
        $sql = "SELECT a.*, o.title as offer_title 
                FROM applications a 
                JOIN offers o ON a.offer_id = o.id 
                WHERE a.status = 'interview'
                ORDER BY a.applied_at DESC";
        
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer une interview par ID
     */
    public function getInterviewById($id) {
        $sql = "SELECT i.*, a.full_name as candidate_name, a.profession as position 
                FROM interviews i 
                JOIN applications a ON i.application_id = a.id 
                WHERE i.id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Mettre à jour le statut d'une interview
     */
    public function updateInterviewStatus($id, $status) {
        $sql = "UPDATE interviews SET status = ?, updated_at = NOW() WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupérer les statistiques des interviews
     */
    public function getInterviewStats() {
        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        
        $sql = "SELECT 
                SUM(CASE WHEN status = 'scheduled' THEN 1 ELSE 0 END) as scheduled,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN interview_date BETWEEN ? AND ? AND status = 'scheduled' THEN 1 ELSE 0 END) as this_week
                FROM interviews";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$weekStart, $weekEnd]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'scheduled' => $stats['scheduled'] ?? 0,
                'completed' => $stats['completed'] ?? 0,
                'cancelled' => $stats['cancelled'] ?? 0,
                'this_week' => $stats['this_week'] ?? 0
            ];
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return ['scheduled' => 0, 'completed' => 0, 'cancelled' => 0, 'this_week' => 0];
        }
    }
    
    /**
     * Récupérer l'application par ID
     */
    public function getApplicationById($id) {
        $sql = "SELECT a.*, o.title as offer_title, ass.name as association_name 
                FROM applications a 
                JOIN offers o ON a.offer_id = o.id 
                LEFT JOIN associations ass ON a.association_id = ass.id 
                WHERE a.id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("InterviewController Error: " . $e->getMessage());
            return null;
        }
    }
}
?>