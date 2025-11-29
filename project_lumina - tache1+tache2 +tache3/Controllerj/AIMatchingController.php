<?php
// Controllerj/AIMatchingController.php - AI Matching Controller
require_once 'C:\xampp\htdocs\project_lumina - tache1+tache2 +tache3\config.php';

class AIMatchingController {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Config::getConnexion();
    }
    
    /**
     * Execute SQL query
     */
    private function executeQuery($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }
    
    /**
     * Fetch single result
     */
    private function fetch($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Fetch all results
     */
    private function fetchAll($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Calculate AI score for an application
     */
    public function calculateAIScore($applicationId) {
        // First check if score already exists
        $existingScore = $this->fetch(
            "SELECT id FROM application_ai_scores WHERE application_id = ?", 
            [$applicationId]
        );
        
        // Get application with offer details
        $application = $this->getApplicationWithOffer($applicationId);
        
        if (!$application) {
            error_log("AIMatchingController: Application $applicationId not found");
            return false;
        }
        
        try {
            // Calculate score
            $score = $this->calculateScore($application);
            $recommendation = $this->getRecommendation($score);
            $missingSkills = $this->getMissingSkills($application);
            $strengths = $this->getStrengths($application);
            
            // Save score
            return $this->saveAIScore($applicationId, $score, $recommendation, $missingSkills, $strengths);
        } catch (Exception $e) {
            error_log("AIMatchingController Error calculating score for application $applicationId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get application with offer details
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
     * Score calculation algorithm - MODIFIED
     */
    private function calculateScore($application) {
        $score = 0;
        
        // 1. Skills (45 points) - REDUCED from 50 to 45
        $appSkills = array_map('trim', explode(',', $application['skills'] ?? ''));
        $offerSkills = json_decode($application['offer_skills'] ?? '[]', true) ?: [];
        $commonSkills = array_intersect($appSkills, $offerSkills);
        
        if (!empty($offerSkills)) {
            $skillsScore = (count($commonSkills) / count($offerSkills)) * 45; // 50 → 45
            $score += min(45, $skillsScore);
        }
        
        // 2. Experience (25 points) - unchanged
        $expScore = $this->calculateExperienceScore($application);
        $score += $expScore;
        
        // 3. Location (15 points) - unchanged
        $locationScore = $this->calculateLocationScore($application);
        $score += $locationScore;
        
        // 4. Salary (10 points) - unchanged
        $salaryScore = $this->calculateSalaryScore($application);
        $score += $salaryScore;
        
        // 5. NEW: Education/experience relevance via CV (5 points)
        $cvRelevanceScore = $this->calculateCVRelevanceScore($application);
        $score += $cvRelevanceScore;
        
        return min(100, round($score));
    }
    
    /**
     * Experience score - unchanged
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
     * Location score - unchanged
     */
    private function calculateLocationScore($application) {
        $prefLocation = strtolower($application['preferred_location'] ?? '');
        $offerLocation = strtolower($application['offer_location'] ?? '');
        
        if ($prefLocation === $offerLocation) {
            return 15;
        }
        
        // Check proximity (ex: "tunis" and "tunis center")
        if (strpos($offerLocation, $prefLocation) !== false || 
            strpos($prefLocation, $offerLocation) !== false) {
            return 10;
        }
        
        return 5;
    }
    
    /**
     * Salary score - unchanged
     */
    private function calculateSalaryScore($application) {
        $desiredSalary = $application['desired_salary'] ?? 0;
        $maxSalary = $application['salary_max'] ?? 0;
        
        if ($maxSalary > 0 && $desiredSalary <= $maxSalary) {
            return 10;
        }
        
        // If desired salary <= 120% of max, give 5 points
        if ($maxSalary > 0 && $desiredSalary <= ($maxSalary * 1.2)) {
            return 5;
        }
        
        return 0;
    }
    
    /**
     * NEW METHOD: Education/experience relevance score via CV (5 points)
     */
    private function calculateCVRelevanceScore($application) {
        $score = 0;
        
        // 1. Education field match (2 points)
        if ($this->checkEducationFieldMatch($application)) {
            $score += 2;
        }
        
        // 2. Career consistency (2 points)
        if ($this->checkCareerConsistency($application)) {
            $score += 2;
        }
        
        // 3. Relevant training (1 point)
        if ($this->checkRelevantTraining($application)) {
            $score += 1;
        }
        
        return $score;
    }
    
    /**
     * Check education field match
     */
    private function checkEducationFieldMatch($application) {
        $profession = strtolower($application['profession'] ?? '');
        $experience = strtolower($application['experience'] ?? '');
        $offerTitle = strtolower($application['offer_title'] ?? '');
        
        // Keywords by professional field
        $fieldKeywords = [
            'health' => ['doctor', 'nurse', 'care', 'health', 'medical', 'hospital', 'clinic', 'physician', 'nurse'],
            'restaurant' => ['restaurant', 'kitchen', 'waiter', 'chef', 'hospitality', 'cafe', 'bar', 'food', 'culinary'],
            'education' => ['teacher', 'professor', 'education', 'training', 'school', 'university', 'pedagogy'],
            'engineering' => ['engineer', 'technical', 'development', 'design', 'construction', 'technology'],
            'commerce' => ['sales', 'commercial', 'marketing', 'business', 'client', 'negotiation', 'retail'],
            'construction' => ['building', 'construction', 'worker', 'site', 'architecture', 'civil engineering']
        ];
        
        // Find the field of the offer
        $offerField = $this->identifyField($offerTitle, $fieldKeywords);
        
        // Check if profession or experience matches the field
        $professionField = $this->identifyField($profession, $fieldKeywords);
        $experienceField = $this->identifyField($experience, $fieldKeywords);
        
        return ($professionField === $offerField) || ($experienceField === $offerField);
    }
    
    /**
     * Check career consistency
     */
    private function checkCareerConsistency($application) {
        $experience = $application['experience'] ?? '';
        $profession = $application['profession'] ?? '';
        
        // Check duration and progression in experience
        $expText = strtolower($experience);
        
        // Progression indicators
        $progressionIndicators = [
            'evolution', 'promotion', 'increase', 'responsibilities', 'management',
            'lead', 'coordinator', 'supervise', 'manage', 'direct'
        ];
        
        // Check for progression indicators
        foreach ($progressionIndicators as $indicator) {
            if (strpos($expText, $indicator) !== false) {
                return true;
            }
        }
        
        // Check profession consistency
        $professionWords = explode(' ', strtolower($profession));
        $expWords = explode(' ', $expText);
        $commonWords = array_intersect($professionWords, $expWords);
        
        // If at least 2 words in common, consider consistent
        return count($commonWords) >= 2;
    }
    
    /**
     * Check for relevant ongoing training
     */
    private function checkRelevantTraining($application) {
        $experience = strtolower($application['experience'] ?? '');
        $skills = strtolower($application['skills'] ?? '');
        
        // Keywords indicating ongoing training
        $trainingIndicators = [
            'certification', 'diploma', 'training', 'course', 'learning',
            'qualification', 'accreditation', 'license', 'certificate', 'internship',
            'workshop', 'seminar', 'training', 'course', 'certificate'
        ];
        
        $combinedText = $experience . ' ' . $skills;
        
        foreach ($trainingIndicators as $indicator) {
            if (strpos($combinedText, $indicator) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Identify professional field from text
     */
    private function identifyField($text, $fieldKeywords) {
        $text = strtolower($text);
        
        foreach ($fieldKeywords as $field => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($text, $keyword) !== false) {
                    return $field;
                }
            }
        }
        
        return 'other';
    }
    
    /**
     * Determine recommendation based on score - unchanged
     */
    private function getRecommendation($score) {
        if ($score >= 80) return 'strong_match';
        if ($score >= 60) return 'good_match';
        if ($score >= 40) return 'average_match';
        return 'weak_match';
    }
    
    /**
     * Identify missing skills - unchanged
     */
    private function getMissingSkills($application) {
        $appSkills = array_map('trim', explode(',', $application['skills'] ?? ''));
        $offerSkills = json_decode($application['offer_skills'] ?? '[]', true) ?: [];
        
        $missing = array_diff($offerSkills, $appSkills);
        return !empty($missing) ? implode(', ', $missing) : 'No major skills missing';
    }
    
    /**
     * Identify strengths - MODIFIED
     */
    private function getStrengths($application) {
        $strengths = [];
        
        // Skills
        $appSkills = array_map('trim', explode(',', $application['skills'] ?? ''));
        $offerSkills = json_decode($application['offer_skills'] ?? '[]', true) ?: [];
        $commonSkills = array_intersect($appSkills, $offerSkills);
        
        if (!empty($commonSkills)) {
            $strengths[] = count($commonSkills) . ' matching skills';
        }
        
        // Experience
        $expLevel = $application['experience_level'] ?? 'beginner';
        if ($expLevel === 'expert') {
            $strengths[] = 'Expert experience';
        } elseif ($expLevel === 'intermediate') {
            $strengths[] = 'Intermediate experience';
        }
        
        // Location
        $prefLocation = $application['preferred_location'] ?? '';
        $offerLocation = $application['offer_location'] ?? '';
        if ($prefLocation === $offerLocation) {
            $strengths[] = 'Perfect location';
        }
        
        // NEW: Add education relevance if high score
        $cvScore = $this->calculateCVRelevanceScore($application);
        if ($cvScore >= 3) {
            $strengths[] = 'Coherent education path';
        } elseif ($cvScore >= 1) {
            $strengths[] = 'Relevant education';
        }
        
        return !empty($strengths) ? implode(', ', $strengths) : 'Standard points';
    }
    
    /**
     * Save AI score - unchanged
     */
    private function saveAIScore($applicationId, $score, $recommendation, $missingSkills, $strengths) {
        // Check if score already exists
        $existing = $this->fetch(
            "SELECT id FROM application_ai_scores WHERE application_id = ?", 
            [$applicationId]
        );
        
        if ($existing) {
            // Update
            $query = "UPDATE application_ai_scores 
                     SET ai_score = ?, ai_recommendation = ?, missing_skills = ?, strengths = ?, created_at = NOW()
                     WHERE application_id = ?";
            return $this->executeQuery($query, [$score, $recommendation, $missingSkills, $strengths, $applicationId]);
        } else {
            // Insert new
            $query = "INSERT INTO application_ai_scores (application_id, ai_score, ai_recommendation, missing_skills, strengths) 
                     VALUES (?, ?, ?, ?, ?)";
            return $this->executeQuery($query, [$applicationId, $score, $recommendation, $missingSkills, $strengths]);
        }
    }
    
    /**
     * Get AI score for an application - unchanged
     */
    public function getAIScore($applicationId) {
        $query = "SELECT ai_score, ai_recommendation, missing_skills, strengths 
                 FROM application_ai_scores 
                 WHERE application_id = ?";
        
        $score = $this->fetch($query, [$applicationId]);
        
        // CORRECTION: Return null if no score exists
        if (!$score || $score['ai_score'] === null) {
            return null;
        }
        
        return $score;
    }
    
    /**
     * Calculate scores for all applications of an offer - unchanged
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
     * Get detailed AI analysis - unchanged
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
     * Calculate only missing scores - unchanged
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