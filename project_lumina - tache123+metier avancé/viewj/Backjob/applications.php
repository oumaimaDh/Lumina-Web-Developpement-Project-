<?php
// viewj/Backjob/applications.php - Gestion des applications

// DÉBUT DU PHP - AUCUN OUTPUT AVANT CE POINT
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\ApplicationController.php';
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Controllerj\AIMatchingController.php';

$applicationController = new ApplicationController();
$aiMatchingController = new AIMatchingController();

// Initialiser les variables de message
$showAISuccess = false;
$aiMessage = '';

// Gérer les actions - DOIT ÊTRE AU DÉBUT
if (isset($_GET['action'])) {
    // Mettre à jour le statut ET rediriger vers le calendrier si c'est "interview"
    if ($_GET['action'] === 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
        $applicationController->updateApplicationStatus($_GET['id'], $_GET['status']);
        
        // Vérifier si c'est un statut "interview" et si on doit rediriger vers le calendrier
        if ($_GET['status'] === 'interview') {
            // Rediriger vers le calendrier avec l'ID de l'application
            echo '<script>window.location.href = "index.php?view=calendar&application_id=' . $_GET['id'] . '";</script>';
        } else {
            // Rediriger vers la page des applications
            echo '<script>window.location.href = "index.php?view=applications";</script>';
        }
        exit;
    }
    
    // Actions IA qui affichent juste un message
    if ($_GET['action'] === 'calculate_ai_scores') {
        $processed = $aiMatchingController->calculateScoresForOffer();
        $showAISuccess = true;
        $aiMessage = $processed . ' AI scores calculated successfully!';
    }

    if ($_GET['action'] === 'calculate_missing_scores') {
        $processed = $aiMatchingController->calculateMissingScores();
        $showAISuccess = true;
        $aiMessage = $processed . ' missing AI scores calculated successfully!';
    }
    
    // Suppression d'application
    if ($_GET['action'] === 'delete_application' && isset($_GET['id'])) {
        $applicationController->deleteApplication($_GET['id']);
        echo '<script>window.location.href = "index.php?view=applications";</script>';
        exit;
    }
}

// Récupérer les applications UNE SEULE FOIS
$statusFilter = $_GET['status'] ?? 'all';

if ($statusFilter === 'all') {
    $applications = $applicationController->getApplications();
} else {
    $applications = $applicationController->getApplicationsByStatus($statusFilter);
}

// Déduplication RÉELLE basée sur l'ID
$uniqueApplications = [];
$seenIds = [];

foreach ($applications as $application) {
    $id = $application['id'];
    if (!isset($seenIds[$id])) {
        $seenIds[$id] = true;
        $uniqueApplications[] = $application;
    }
}

$applications = $uniqueApplications;
$stats = $applicationController->getApplicationStats();

// Récupérer les scores IA
foreach ($applications as &$application) {
    $aiScore = $aiMatchingController->getAIScore($application['id']);
    
    if ($aiScore === null) {
        $application['ai_score'] = 0;
        $application['ai_recommendation'] = 'not_calculated';
        $application['ai_missing_skills'] = '';
        $application['ai_strengths'] = '';
    } else {
        $application['ai_score'] = $aiScore['ai_score'];
        $application['ai_recommendation'] = $aiScore['ai_recommendation'];
        $application['ai_missing_skills'] = $aiScore['missing_skills'] ?? '';
        $application['ai_strengths'] = $aiScore['strengths'] ?? '';
    }
}
unset($application);
?>

<!-- MAINTENANT ON COMMENCE L'OUTPUT HTML -->
<section id="applications-section" class="jobs-section active">
    <h2 class="section-title">Manage Applications</h2>
    
    <!-- Afficher les messages de succès -->
    <?php if ($showAISuccess): ?>
        <div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">
            <i class="fas fa-check-circle"></i> <?= $aiMessage ?>
        </div>
    <?php endif; ?>
    
    <!-- Statistiques -->
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Total Applications</div>
                <div class="stat-value"><?= count($applications) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Submitted</div>
                <div class="stat-value"><?= $stats['submitted'] ?? 0 ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Viewed</div>
                <div class="stat-value"><?= $stats['viewed'] ?? 0 ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Interview</div>
                <div class="stat-value"><?= $stats['interview'] ?? 0 ?></div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters" style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
        <select id="status-filter" onchange="window.location.href='index.php?view=applications&status=' + this.value" style="padding: 10px 15px; border: 2px solid rgba(107,133,168,0.3); border-radius: 8px; background: white; color: var(--primary-dark); min-width: 200px;">
            <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Status</option>
            <option value="submitted" <?= $statusFilter === 'submitted' ? 'selected' : '' ?>>Submitted</option>
            <option value="viewed" <?= $statusFilter === 'viewed' ? 'selected' : '' ?>>Viewed</option>
            <option value="interview" <?= $statusFilter === 'interview' ? 'selected' : '' ?>>Interview</option>
        </select>
        
        <!-- BOUTON CALCUL SCORES IA -->
        <button class="btn btn-primary" onclick="calculateAllAIScores()" id="ai-calculate-btn" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium)); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-robot"></i> Calculer Scores IA
        </button>

        <!-- BOUTON CALCUL SCORES MANQUANTS -->
        <button class="btn btn-outline" onclick="calculateMissingScores()" id="ai-missing-btn" style="background: white; color: var(--primary-dark); border: 2px solid var(--primary-light); padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-calculator"></i> Scores Manquants
        </button>
    </div>

    <!-- Liste des applications -->
    <div class="applications-list">
        <?php if (empty($applications)): ?>
            <div class="no-results" style="text-align: center; padding: 3rem;">
                <i class="fas fa-file-alt" style="font-size: 3rem; color: #aaa; margin-bottom: 1rem;"></i>
                <h3 style="color: var(--primary-dark); margin-bottom: 0.5rem;">No Applications Found</h3>
                <p style="color: #aaa;">No applications match your current filter.</p>
            </div>
        <?php else: ?>
            <?php foreach ($applications as $application): ?>
            <div class="application-card" id="application-<?= $application['id'] ?>" style="background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
                <!-- En-tête candidature -->
                <div class="candidate-info" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                    <div class="candidate-main">
                        <h3 class="candidate-name" style="color: var(--primary-dark); margin-bottom: 5px; font-size: 1.2rem;"><?= htmlspecialchars($application['full_name']) ?></h3>
                        <p class="candidate-email" style="color: var(--primary-medium); font-size: 0.9rem;"><?= htmlspecialchars($application['email']) ?> • <?= htmlspecialchars($application['phone']) ?></p>
                    </div>
                    <div class="application-status status-<?= $application['status'] ?>" style="padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: <?= $application['status'] === 'submitted' ? 'rgba(107,133,168,0.15)' : ($application['status'] === 'viewed' ? 'rgba(245,158,11,0.15)' : 'rgba(16,185,129,0.15)') ?>; color: <?= $application['status'] === 'submitted' ? 'var(--primary-light)' : ($application['status'] === 'viewed' ? '#F59E0B' : '#10B981') ?>; border: 1px solid <?= $application['status'] === 'submitted' ? 'rgba(107,133,168,0.3)' : ($application['status'] === 'viewed' ? 'rgba(245,158,11,0.3)' : 'rgba(16,185,129,0.3)') ?>;">
                        <?= strtoupper($application['status']) ?>
                    </div>
                </div>
                
                <!-- SECTION IA -->
                <div class="ai-recommendations" style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #ddd; border-top: 1px solid #e9ecef;">
                    <div class="ai-score-header" style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <strong>Score IA: </strong>
                        <?php if ($application['ai_recommendation'] === 'not_calculated'): ?>
                            <span class="ai-score-badge score-not-calculated" style="padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 0.9rem; color: white; background: #6c757d;">
                                Non calculé
                            </span>
                        <?php else: ?>
                            <?php
                            $scoreLevel = floor($application['ai_score'] / 25);
                            $scoreColors = [
                                0 => '#dc3545', // 0-24%
                                1 => '#fd7e14', // 25-49%
                                2 => '#20c997', // 50-74%
                                3 => '#0d6efd'  // 75-100%
                            ];
                            $scoreColor = $scoreColors[$scoreLevel] ?? '#6c757d';
                            ?>
                            <span class="ai-score-badge score-<?= $scoreLevel ?>" style="padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 0.9rem; color: white; background: <?= $scoreColor ?>;">
                                <?= $application['ai_score'] ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($application['ai_recommendation'] !== 'not_calculated'): ?>
                        <div style="margin: 8px 0;">
                            <strong>Recommandation:</strong>
                            <?php if ($application['ai_recommendation'] == 'strong_match'): ?>
                                <span class="recommendation positive" style="padding: 6px 12px; border-radius: 6px; font-weight: bold; margin: 0 8px; background: #d1e7dd; color: #0f5132;">
                                    ✅ Candidat idéal
                                </span>
                            <?php elseif ($application['ai_recommendation'] == 'good_match'): ?>
                                <span class="recommendation positive" style="padding: 6px 12px; border-radius: 6px; font-weight: bold; margin: 0 8px; background: #d1e7dd; color: #0f5132;">
                                    👍 Bon profil
                                </span>
                            <?php elseif ($application['ai_recommendation'] == 'average_match'): ?>
                                <span class="recommendation neutral" style="padding: 6px 12px; border-radius: 6px; font-weight: bold; margin: 0 8px; background: #fff3cd; color: #664d03;">
                                    🤔 À examiner
                                </span>
                            <?php else: ?>
                                <span class="recommendation negative" style="padding: 6px 12px; border-radius: 6px; font-weight: bold; margin: 0 8px; background: #f8d7da; color: #842029;">
                                    ❌ Non recommandé
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($application['ai_strengths'])): ?>
                            <div style="margin: 8px 0;">
                                <strong>Points forts:</strong> 
                                <span class="strengths" style="color: #198754; font-weight: 500;"><?= htmlspecialchars($application['ai_strengths']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($application['ai_missing_skills']) && $application['ai_missing_skills'] !== 'Aucune compétence majeure manquante'): ?>
                            <div style="margin: 8px 0;">
                                <strong>Compétences manquantes:</strong> 
                                <span class="missing-skills" style="color: #dc3545; font-weight: 500;"><?= htmlspecialchars($application['ai_missing_skills']) ?></span>
                            </div>
                        <?php endif; ?>

                        <button class="btn-view-analysis" onclick="showAIAnalysis(<?= $application['id'] ?>)" style="background: #6c757d; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-top: 8px; font-size: 0.9rem; display: block;">
                            📊 Analyse détaillée
                        </button>
                    <?php else: ?>
                        <div style="margin: 8px 0; color: #6c757d;">
                            <i>Cliquez sur "Calculer Scores IA" pour analyser cette candidature</i>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- FIN SECTION IA -->
                
                <!-- Détails candidature -->
                <div class="application-details" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin: 20px 0;">
                    <div class="application-meta">
                        <p><strong>Position:</strong> <?= htmlspecialchars($application['profession']) ?></p>
                        <p><strong>Offer:</strong> <?= htmlspecialchars($application['offer_title']) ?></p>
                        <p><strong>Association:</strong> <?= htmlspecialchars($application['association_name']) ?></p>
                        <p><strong>Desired Salary:</strong> <?= $application['desired_salary'] ?> DT/month</p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($application['preferred_location']) ?></p>
                        <p><strong>Experience Level:</strong> <?= ucfirst($application['experience_level']) ?></p>
                        <p><strong>Applied:</strong> <?= date('M j, Y H:i', strtotime($application['applied_at'])) ?></p>
                    </div>
                    
                    <div class="application-content">
                        <div class="skills-section" style="margin-bottom: 15px;">
                            <h4 style="color: var(--primary-dark); margin-bottom: 8px; font-size: 1rem;">Skills</h4>
                            <p style="color: var(--primary-medium); line-height: 1.5;"><?= htmlspecialchars($application['skills']) ?></p>
                        </div>
                        
                        <div class="experience-section" style="margin-bottom: 15px;">
                            <h4 style="color: var(--primary-dark); margin-bottom: 8px; font-size: 1rem;">Experience</h4>
                            <p style="color: var(--primary-medium); line-height: 1.5;"><?= htmlspecialchars($application['experience']) ?></p>
                        </div>
                        
                        <?php if (!empty($application['cv_filename'])): ?>
                        <div class="cv-section">
                            <h4 style="color: var(--primary-dark); margin-bottom: 8px; font-size: 1rem;">CV</h4>
                            <?php
                            $uploadsDir = '/project_lumina - tache123+metier avancé/uploads/';
                            $cvFilename = htmlspecialchars($application['cv_filename']);
                            $publicCvPath = $uploadsDir . $cvFilename;
                            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $publicCvPath;
                            
                            if (file_exists($fullPath)): ?>
                                <a href="<?= $publicCvPath ?>" target="_blank" class="btn btn-outline" download style="background: white; color: var(--primary-dark); border: 2px solid var(--primary-light); padding: 8px 16px; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-download"></i> Download CV
                                </a>
                            <?php else: ?>
                                <p class="text-muted" style="color: #aaa;">CV file not found</p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="application-actions" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.08);">
                    <div class="status-actions" style="display: flex; align-items: center; gap: 10px;">
                        <span style="color: var(--primary-dark); font-weight: 600;">Update Status:</span>
                        <button class="btn <?= $application['status'] === 'submitted' ? 'btn-primary' : 'btn-outline' ?>" 
                                onclick="updateApplicationStatus(<?= $application['id'] ?>, 'submitted')"
                                style="background: <?= $application['status'] === 'submitted' ? 'linear-gradient(135deg, var(--primary-dark), var(--primary-medium))' : 'white' ?>; 
                                        color: <?= $application['status'] === 'submitted' ? 'white' : 'var(--primary-dark)' ?>; 
                                        border: 2px solid <?= $application['status'] === 'submitted' ? 'transparent' : 'var(--primary-light)' ?>; 
                                        padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                            Submitted
                        </button>
                        <button class="btn <?= $application['status'] === 'viewed' ? 'btn-primary' : 'btn-outline' ?>" 
                                onclick="updateApplicationStatus(<?= $application['id'] ?>, 'viewed')"
                                style="background: <?= $application['status'] === 'viewed' ? 'linear-gradient(135deg, var(--primary-dark), var(--primary-medium))' : 'white' ?>; 
                                        color: <?= $application['status'] === 'viewed' ? 'white' : 'var(--primary-dark)' ?>; 
                                        border: 2px solid <?= $application['status'] === 'viewed' ? 'transparent' : 'var(--primary-light)' ?>; 
                                        padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                            Viewed
                        </button>
                        <button class="btn <?= $application['status'] === 'interview' ? 'btn-primary' : 'btn-outline' ?>" 
                                onclick="updateApplicationStatus(<?= $application['id'] ?>, 'interview')"
                                style="background: <?= $application['status'] === 'interview' ? 'linear-gradient(135deg, var(--primary-dark), var(--primary-medium))' : 'white' ?>; 
                                        color: <?= $application['status'] === 'interview' ? 'white' : 'var(--primary-dark)' ?>; 
                                        border: 2px solid <?= $application['status'] === 'interview' ? 'transparent' : 'var(--primary-light)' ?>; 
                                        padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                            Interview
                        </button>
                    </div>
                    <button class="btn delete-offer" 
                            onclick="if(confirm('Are you sure you want to delete this application?')) deleteApplication(<?= $application['id'] ?>)"
                            style="background: #EF4444; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        🗑️ Delete
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Modal Analyse IA -->
<div id="ai-analysis-modal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: white; margin: 5% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px; max-height: 80vh; overflow-y: auto;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.1);">
            <h3 style="color: var(--primary-dark); margin: 0; font-size: 1.3rem;">Analyse IA Détaillée</h3>
            <span class="close-modal" onclick="closeAIAnalysis()" style="color: var(--primary-medium); font-size: 1.5rem; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body" id="ai-analysis-content">
            <!-- Contenu chargé dynamiquement -->
        </div>
    </div>
</div>

<style>
/* STYLES IA */
.ai-recommendations {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
    margin: 10px 0;
    border-left: 4px solid #ddd;
    border-top: 1px solid #e9ecef;
}

.ai-score-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.ai-score-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
    color: white;
}

.ai-score-badge.score-0 { background: #dc3545; } /* 0-24% */
.ai-score-badge.score-1 { background: #fd7e14; } /* 25-49% */  
.ai-score-badge.score-2 { background: #20c997; } /* 50-74% */
.ai-score-badge.score-3 { background: #0d6efd; } /* 75-100% */
.ai-score-badge.score-not-calculated { background: #6c757d; }

.recommendation {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: bold;
    margin: 0 8px;
}

.recommendation.positive { background: #d1e7dd; color: #0f5132; }
.recommendation.neutral { background: #fff3cd; color: #664d03; }
.recommendation.negative { background: #f8d7da; color: #842029; }

.strengths {
    color: #198754;
    font-weight: 500;
}

.missing-skills {
    color: #dc3545;
    font-weight: 500;
}

.btn-view-analysis {
    background: #6c757d;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 8px;
    font-size: 0.9rem;
    display: block;
}

.btn-view-analysis:hover {
    background: #5c636a;
}

/* Modal IA */
#ai-analysis-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

#ai-analysis-modal .modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

/* Loading animation */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Styles pour les boutons */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-primary:hover {
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.delete-offer:hover {
    background: #dc2626 !important;
    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
}

/* Transition pour les cartes */
.application-card {
    transition: all 0.3s ease;
}

.application-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>

<script>
// FONCTIONS EXISTANTES
function updateApplicationStatus(applicationId, status) {
    // Cette fonction va maintenant gérer les redirections différemment
    if (status === 'interview') {
        // Pour les interviews, on met à jour le statut et on redirige vers le calendrier
        window.location.href = 'index.php?view=applications&action=update_status&id=' + applicationId + '&status=' + status;
    } else {
        // Pour les autres status, on reste sur la même page
        window.location.href = 'index.php?view=applications&action=update_status&id=' + applicationId + '&status=' + status;
    }
}

function deleteApplication(applicationId) {
    window.location.href = 'index.php?view=applications&action=delete_application&id=' + applicationId;
}

// ========== FONCTIONS IA AMÉLIORÉES ==========
function calculateAllAIScores() {
    if (confirm('Calculer les scores IA pour TOUTES les candidatures ? Cette opération peut prendre quelques secondes.')) {
        const btn = document.getElementById('ai-calculate-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<div class="loading-spinner"></div> Calcul en cours...';
        btn.disabled = true;
        
        // Désactiver aussi l'autre bouton
        const missingBtn = document.getElementById('ai-missing-btn');
        if (missingBtn) missingBtn.disabled = true;
        
        // Rediriger après un court délai pour voir l'animation
        setTimeout(() => {
            window.location.href = 'index.php?view=applications&action=calculate_ai_scores';
        }, 500);
    }
}

function calculateMissingScores() {
    if (confirm('Calculer les scores IA seulement pour les candidatures sans score ?')) {
        const btn = document.getElementById('ai-missing-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<div class="loading-spinner"></div> Calcul des scores manquants...';
        btn.disabled = true;
        
        // Désactiver aussi l'autre bouton
        const calculateBtn = document.getElementById('ai-calculate-btn');
        if (calculateBtn) calculateBtn.disabled = true;
        
        setTimeout(() => {
            window.location.href = 'index.php?view=applications&action=calculate_missing_scores';
        }, 500);
    }
}

function showAIAnalysis(applicationId) {
    // Récupérer les données de la carte d'application
    const applicationCard = document.getElementById('application-' + applicationId);
    if (!applicationCard) return;
    
    const appData = {
        name: applicationCard.querySelector('.candidate-name').textContent,
        score: applicationCard.querySelector('.ai-score-badge').textContent,
        recommendation: applicationCard.querySelector('.recommendation')?.textContent || 'Non calculé',
        strengths: applicationCard.querySelector('.strengths')?.textContent || 'Non spécifié',
        missingSkills: applicationCard.querySelector('.missing-skills')?.textContent || 'Aucune compétence manquante',
        skills: applicationCard.querySelector('.skills-section p')?.textContent || 'Non spécifié'
    };
    
    // Afficher l'analyse dans la modal
    document.getElementById('ai-analysis-content').innerHTML = `
        <div class="detailed-analysis">
            <div class="analysis-header" style="margin-bottom: 20px;">
                <h4 style="color: var(--primary-dark); margin-bottom: 10px;">📊 Analyse IA Détaillée</h4>
                <p style="color: var(--primary-medium);">Candidat: <strong>${appData.name}</strong></p>
            </div>

            <div class="score-breakdown" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h5 style="color: var(--primary-dark); margin-bottom: 10px;">Score Global: <span class="score-value" style="color: #0d6efd; font-size: 1.2rem;">${appData.score}</span></h5>
                <div class="recommendation-badge" style="display: flex; align-items: center; gap: 10px;">
                    <strong style="color: var(--primary-dark);">Recommandation:</strong>
                    <span class="rec-badge" style="padding: 6px 12px; border-radius: 6px; font-weight: bold; background: ${appData.recommendation.includes('idéal') || appData.recommendation.includes('Bon') ? '#d1e7dd' : appData.recommendation.includes('examiner') ? '#fff3cd' : '#f8d7da'}; color: ${appData.recommendation.includes('idéal') || appData.recommendation.includes('Bon') ? '#0f5132' : appData.recommendation.includes('examiner') ? '#664d03' : '#842029'};">${appData.recommendation}</span>
                </div>
            </div>

            <div class="analysis-details">
                <div class="detail-section" style="margin-bottom: 15px;">
                    <h6 style="color: var(--primary-dark); margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-check-circle" style="color: #10B981;"></i> Points Forts
                    </h6>
                    <p style="color: var(--primary-medium); background: rgba(16, 185, 129, 0.1); padding: 10px; border-radius: 6px;">${appData.strengths}</p>
                </div>

                <div class="detail-section" style="margin-bottom: 15px;">
                    <h6 style="color: var(--primary-dark); margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-exclamation-triangle" style="color: #EF4444;"></i> Compétences Manquantes
                    </h6>
                    <p style="color: var(--primary-medium); background: rgba(239, 68, 68, 0.1); padding: 10px; border-radius: 6px;">${appData.missingSkills}</p>
                </div>

                <div class="detail-section" style="margin-bottom: 15px;">
                    <h6 style="color: var(--primary-dark); margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-tools" style="color: #3B82F6;"></i> Compétences
                    </h6>
                    <p style="color: var(--primary-medium); background: rgba(59, 130, 246, 0.1); padding: 10px; border-radius: 6px;">${appData.skills}</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('ai-analysis-modal').style.display = 'block';
}

function closeAIAnalysis() {
    document.getElementById('ai-analysis-modal').style.display = 'none';
}

// Fermer modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('ai-analysis-modal');
    if (event.target === modal) {
        closeAIAnalysis();
    }
}
</script>