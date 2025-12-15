<?php
// Controllerj/AIMatchingController.php - Contrôleur pour le matching IA
require_once __DIR__ . '/../config.php';

class AIMatchingController {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Config::getConnexion();
    }
    
    /**
     * Exécute une requête SQL
     */
    private function executeQuery($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }
    
    /**
     * Récupère un seul résultat
     */
    private function fetch($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère tous les résultats
     */
    private function fetchAll($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Calcule le score IA pour une candidature
     */
    /**
 * Calcule le score IA pour une candidature
 */
public function calculateAIScore($applicationId) {
    // Vérifier d'abord si un score existe déjà
    $existingScore = $this->fetch(
        "SELECT id FROM application_ai_scores WHERE application_id = ?", 
        [$applicationId]
    );
    
    // Si un score existe déjà, on peut soit le recalculer, soit retourner true
    // Pour l'instant, on recalcule toujours
    // if ($existingScore) {
    //     return true; // Score déjà calculé
    // }
    
    // Récupérer la candidature et l'offre associée
    $application = $this->getApplicationWithOffer($applicationId);
    
    if (!$application) {
        error_log("AIMatchingController: Application $applicationId not found");
        return false;
    }
    
    try {
        // Calculer le score
        $score = $this->calculateScore($application);
        $recommendation = $this->getRecommendation($score);
        $missingSkills = $this->getMissingSkills($application);
        $strengths = $this->getStrengths($application);
        
        // Sauvegarder le score
        return $this->saveAIScore($applicationId, $score, $recommendation, $missingSkills, $strengths);
    } catch (Exception $e) {
        error_log("AIMatchingController Error calculating score for application $applicationId: " . $e->getMessage());
        return false;
    }
}
    
    /**
     * Récupère une candidature avec les détails de l'offre
     */
    private function getApplicationWithOffer($applicationId) {
        $query = "SELECT a.*, o.title as offer_title, o.location as offer_location, 
                         o.salary_min, o.salary_max, o.description as offer_description,
                         o.skills as offer_skills, o.contract_types as offer_contract_types
                  FROM applications a 
                  JOIN offers o ON a.offer_id = o.id 
                  WHERE a.id = ?";
        
        return $this->fetch($query, [$applicationId]);
    }
    
    /**
     * Algorithme de calcul de score
     */
    private function calculateScore($application) {
        $score = 0;
        
        // 1. Compétences (50 points)
        $appSkills = array_map('trim', explode(',', $application['skills'] ?? ''));
        $offerSkills = json_decode($application['offer_skills'] ?? '[]', true) ?: [];
        $commonSkills = array_intersect($appSkills, $offerSkills);
        
        if (!empty($offerSkills)) {
            $skillsScore = (count($commonSkills) / count($offerSkills)) * 50;
            $score += min(50, $skillsScore);
        }
        
        // 2. Expérience (25 points)
        $expScore = $this->calculateExperienceScore($application);
        $score += $expScore;
        
        // 3. Localisation (15 points)
        $locationScore = $this->calculateLocationScore($application);
        $score += $locationScore;
        
        // 4. Salaire (10 points)
        $salaryScore = $this->calculateSalaryScore($application);
        $score += $salaryScore;
        
        return min(100, round($score));
    }
    
    /**
     * Score expérience
     */
    private function calculateExperienceScore($application) {
        $expLevel = $application['experience_level'] ?? 'beginner';
        
        switch($expLevel) {
            case 'expert': return 25;
            case 'intermediate': return 20;
            case 'beginner': return 10;
            default: return 5;
        }
    }
    
    /**
     * Score localisation
     */
    private function calculateLocationScore($application) {
        $prefLocation = strtolower($application['preferred_location'] ?? '');
        $offerLocation = strtolower($application['offer_location'] ?? '');
        
        if ($prefLocation === $offerLocation) {
            return 15;
        }
        
        // Vérifier la proximité (ex: "tunis" et "tunis centre")
        if (strpos($offerLocation, $prefLocation) !== false || 
            strpos($prefLocation, $offerLocation) !== false) {
            return 10;
        }
        
        return 5;
    }
    
    /**
     * Score salaire
     */
    private function calculateSalaryScore($application) {
        $desiredSalary = $application['desired_salary'] ?? 0;
        $maxSalary = $application['salary_max'] ?? 0;
        
        if ($maxSalary > 0 && $desiredSalary <= $maxSalary) {
            return 10;
        }
        
        // Si salaire désiré <= 120% du max, on donne 5 points
        if ($maxSalary > 0 && $desiredSalary <= ($maxSalary * 1.2)) {
            return 5;
        }
        
        return 0;
    }
    
    /**
     * Détermine la recommandation basée sur le score
     */
    private function getRecommendation($score) {
        if ($score >= 80) return 'strong_match';
        if ($score >= 60) return 'good_match';
        if ($score >= 40) return 'average_match';
        return 'weak_match';
    }
    
    /**
     * Identifie les compétences manquantes
     */
    private function getMissingSkills($application) {
        $appSkills = array_map('trim', explode(',', $application['skills'] ?? ''));
        $offerSkills = json_decode($application['offer_skills'] ?? '[]', true) ?: [];
        
        $missing = array_diff($offerSkills, $appSkills);
        return !empty($missing) ? implode(', ', $missing) : 'Aucune compétence majeure manquante';
    }
    
    /**
     * Identifie les points forts
     */
    private function getStrengths($application) {
        $strengths = [];
        
        // Compétences
        $appSkills = array_map('trim', explode(',', $application['skills'] ?? ''));
        $offerSkills = json_decode($application['offer_skills'] ?? '[]', true) ?: [];
        $commonSkills = array_intersect($appSkills, $offerSkills);
        
        if (!empty($commonSkills)) {
            $strengths[] = count($commonSkills) . ' compétences correspondantes';
        }
        
        // Expérience
        $expLevel = $application['experience_level'] ?? 'beginner';
        if ($expLevel === 'expert') {
            $strengths[] = 'Expérience expert';
        } elseif ($expLevel === 'intermediate') {
            $strengths[] = 'Expérience intermédiaire';
        }
        
        // Localisation
        $prefLocation = $application['preferred_location'] ?? '';
        $offerLocation = $application['offer_location'] ?? '';
        if ($prefLocation === $offerLocation) {
            $strengths[] = 'Localisation parfaite';
        }
        
        return !empty($strengths) ? implode(', ', $strengths) : 'Points standards';
    }
    
    /**
     * Sauvegarde le score IA
     */
    private function saveAIScore($applicationId, $score, $recommendation, $missingSkills, $strengths) {
        // Vérifier si un score existe déjà
        $existing = $this->fetch(
            "SELECT id FROM application_ai_scores WHERE application_id = ?", 
            [$applicationId]
        );
        
        if ($existing) {
            // Mettre à jour
            $query = "UPDATE application_ai_scores 
                     SET ai_score = ?, ai_recommendation = ?, missing_skills = ?, strengths = ?, created_at = NOW()
                     WHERE application_id = ?";
            return $this->executeQuery($query, [$score, $recommendation, $missingSkills, $strengths, $applicationId]);
        } else {
            // Insérer nouveau
            $query = "INSERT INTO application_ai_scores (application_id, ai_score, ai_recommendation, missing_skills, strengths) 
                     VALUES (?, ?, ?, ?, ?)";
            return $this->executeQuery($query, [$applicationId, $score, $recommendation, $missingSkills, $strengths]);
        }
    }
    
    /**
 * Récupère le score IA d'une candidature
 */
public function getAIScore($applicationId) {
    $query = "SELECT ai_score, ai_recommendation, missing_skills, strengths 
             FROM application_ai_scores 
             WHERE application_id = ?";
    
    $score = $this->fetch($query, [$applicationId]);
    
    // CORRECTION : Retourner null si aucun score n'existe
    if (!$score || $score['ai_score'] === null) {
        return null;
    }
    
    return $score;
}
    
    /**
     * Calcule les scores pour toutes les candidatures d'une offre
     */
    public function calculateScoresForOffer($offerId = null) {
        if ($offerId) {
            $query = "SELECT id FROM applications WHERE offer_id = ?";
            $applications = $this->fetchAll($query, [$offerId]);
        } else {
            $query = "SELECT id FROM applications";
            $applications = $this->fetchAll($query);
        }
        
        $processed = 0;
        foreach ($applications as $app) {
            if ($this->calculateAIScore($app['id'])) {
                $processed++;
            }
        }
        
        return $processed;
    }
    
    /**
     * Récupère l'analyse détaillée IA
     */
    public function getDetailedAnalysis($applicationId) {
        $query = "SELECT aas.*, a.full_name, a.skills as candidate_skills, 
                         o.title as offer_title, o.skills as required_skills
                  FROM application_ai_scores aas
                  JOIN applications a ON aas.application_id = a.id
                  JOIN offers o ON a.offer_id = o.id
                  WHERE aas.application_id = ?";
        
        return $this->fetch($query, [$applicationId]);
    }

    /**
     * Calcule les scores manquants seulement
     */
    public function calculateMissingScores() {
        $query = "SELECT a.id 
                 FROM applications a 
                 LEFT JOIN application_ai_scores aas ON a.id = aas.application_id 
                 WHERE aas.id IS NULL";
        
        $applications = $this->fetchAll($query);
        
        $processed = 0;
        foreach ($applications as $app) {
            if ($this->calculateAIScore($app['id'])) {
                $processed++;
            }
        }
        
        return $processed;
    }
}
?>