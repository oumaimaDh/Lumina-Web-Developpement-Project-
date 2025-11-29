<?php
// viewj/Backjob/applications.php - Gestion des applications

// DÉBUT DU PHP - AUCUN OUTPUT AVANT CE POINT
require_once 'C:\xampp\htdocs\project_lumina - tache1+tache2 +tache3\Controllerj\ApplicationController.php';
require_once 'C:\xampp\htdocs\project_lumina - tache1+tache2 +tache3\Controllerj\AIMatchingController.php';

$applicationController = new ApplicationController();
$aiMatchingController = new AIMatchingController();

// Initialiser les variables de message
$showAISuccess = false;
$aiMessage = '';

// Gérer les actions - DOIT ÊTRE AU DÉBUT

if (isset($_GET['action'])) {
    // Actions qui nécessitent une redirection
    if ($_GET['action'] === 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
        $applicationController->updateApplicationStatus($_GET['id'], $_GET['status']);
        // Utiliser JavaScript pour la redirection
        echo '<script>window.location.href = "index.php?view=applications";</script>';
        exit;
    }
    
    if ($_GET['action'] === 'delete_application' && isset($_GET['id'])) {
        $applicationController->deleteApplication($_GET['id']);
        // Utiliser JavaScript pour la redirection
        echo '<script>window.location.href = "index.php?view=applications";</script>';
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
        <div class="alert alert-success"><?= $aiMessage ?></div>
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
    <div class="filters">
        <select id="status-filter" onchange="window.location.href='index.php?view=applications&status=' + this.value">
            <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Status</option>
            <option value="submitted" <?= $statusFilter === 'submitted' ? 'selected' : '' ?>>Submitted</option>
            <option value="viewed" <?= $statusFilter === 'viewed' ? 'selected' : '' ?>>Viewed</option>
            <option value="interview" <?= $statusFilter === 'interview' ? 'selected' : '' ?>>Interview</option>
        </select>
        
        <!-- BOUTON CALCUL SCORES IA -->
        <button class="btn btn-primary" onclick="calculateAllAIScores()" id="ai-calculate-btn">
            <i class="fas fa-robot"></i> Calculer Scores IA
        </button>

        <!-- BOUTON CALCUL SCORES MANQUANTS -->
        <button class="btn btn-outline" onclick="calculateMissingScores()" id="ai-missing-btn">
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
            <div class="application-card" id="application-<?= $application['id'] ?>">
                <!-- En-tête candidature -->
                <div class="candidate-info">
                    <div class="candidate-main">
                        <h3 class="candidate-name"><?= htmlspecialchars($application['full_name']) ?></h3>
                        <p class="candidate-email"><?= htmlspecialchars($application['email']) ?> • <?= htmlspecialchars($application['phone']) ?></p>
                    </div>
                    <div class="application-status status-<?= $application['status'] ?>">
                        <?= strtoupper($application['status']) ?>
                    </div>
                </div>
                
                <!-- SECTION IA -->
                <div class="ai-recommendations">
                    <div class="ai-score-header">
                        <strong>Score IA: </strong>
                        <?php if ($application['ai_recommendation'] === 'not_calculated'): ?>
                            <span class="ai-score-badge score-not-calculated">
                                Non calculé
                            </span>
                        <?php else: ?>
                            <span class="ai-score-badge score-<?= floor($application['ai_score'] / 25) ?>">
                                <?= $application['ai_score'] ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($application['ai_recommendation'] !== 'not_calculated'): ?>
                        <div style="margin: 8px 0;">
                            <strong>Recommandation:</strong>
                            <?php if ($application['ai_recommendation'] == 'strong_match'): ?>
                                <span class="recommendation positive">✅ Candidat idéal</span>
                            <?php elseif ($application['ai_recommendation'] == 'good_match'): ?>
                                <span class="recommendation positive">👍 Bon profil</span>
                            <?php elseif ($application['ai_recommendation'] == 'average_match'): ?>
                                <span class="recommendation neutral">🤔 À examiner</span>
                            <?php else: ?>
                                <span class="recommendation negative">❌ Non recommandé</span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($application['ai_strengths'])): ?>
                            <div style="margin: 8px 0;">
                                <strong>Points forts:</strong> 
                                <span class="strengths"><?= htmlspecialchars($application['ai_strengths']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($application['ai_missing_skills']) && $application['ai_missing_skills'] !== 'Aucune compétence majeure manquante'): ?>
                            <div style="margin: 8px 0;">
                                <strong>Compétences manquantes:</strong> 
                                <span class="missing-skills"><?= htmlspecialchars($application['ai_missing_skills']) ?></span>
                            </div>
                        <?php endif; ?>

                        <button class="btn-view-analysis" onclick="showAIAnalysis(<?= $application['id'] ?>)">
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
                <div class="application-details">
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
                        <div class="skills-section">
                            <h4>Skills</h4>
                            <p><?= htmlspecialchars($application['skills']) ?></p>
                        </div>
                        
                        <div class="experience-section">
                            <h4>Experience</h4>
                            <p><?= htmlspecialchars($application['experience']) ?></p>
                        </div>
                        
                        <?php if (!empty($application['cv_filename'])): ?>
                        <div class="cv-section">
                            <h4>CV</h4>
                            <?php
                            $uploadsDir = '/project_lumina - tache1+tache2 +tache3/uploads/';
                            $cvFilename = htmlspecialchars($application['cv_filename']);
                            $publicCvPath = $uploadsDir . $cvFilename;
                            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $publicCvPath;
                            
                            if (file_exists($fullPath)): ?>
                                <a href="<?= $publicCvPath ?>" target="_blank" class="btn btn-outline" download>
                                    <i class="fas fa-download"></i> Download CV
                                </a>
                            <?php else: ?>
                                <p class="text-muted">CV file not found</p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="application-actions">
                    <div class="status-actions">
                        <span>Update Status:</span>
                        <button class="btn <?= $application['status'] === 'submitted' ? 'btn-primary' : 'btn-outline' ?>" 
                                onclick="updateApplicationStatus(<?= $application['id'] ?>, 'submitted')">
                            Submitted
                        </button>
                        <button class="btn <?= $application['status'] === 'viewed' ? 'btn-primary' : 'btn-outline' ?>" 
                                onclick="updateApplicationStatus(<?= $application['id'] ?>, 'viewed')">
                            Viewed
                        </button>
                        <button class="btn <?= $application['status'] === 'interview' ? 'btn-primary' : 'btn-outline' ?>" 
                                onclick="updateApplicationStatus(<?= $application['id'] ?>, 'interview')">
                            Interview
                        </button>
                    </div>
                    <button class="btn delete-offer" 
                            onclick="if(confirm('Are you sure you want to delete this application?')) deleteApplication(<?= $application['id'] ?>)">
                        🗑️ Delete
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Modal Analyse IA -->
<div id="ai-analysis-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Analyse IA Détaillée</h3>
            <span class="close-modal" onclick="closeAIAnalysis()">&times;</span>
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
</style>

<script>
// FONCTIONS EXISTANTES
function updateApplicationStatus(applicationId, status) {
    window.location.href = 'index.php?view=applications&action=update_status&id=' + applicationId + '&status=' + status;
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
            <div class="analysis-header">
                <h4>📊 Analyse IA Détaillée</h4>
                <p>Candidat: <strong>${appData.name}</strong></p>
            </div>

            <div class="score-breakdown">
                <h5>Score Global: <span class="score-value">${appData.score}</span></h5>
                <div class="recommendation-badge">
                    <strong>Recommandation:</strong>
                    <span class="rec-badge">${appData.recommendation}</span>
                </div>
            </div>

            <div class="analysis-details">
                <div class="detail-section">
                    <h6>✅ Points Forts</h6>
                    <p>${appData.strengths}</p>
                </div>

                <div class="detail-section">
                    <h6>📝 Compétences Manquantes</h6>
                    <p>${appData.missingSkills}</p>
                </div>

                <div class="detail-section">
                    <h6>🛠️ Compétences</h6>
                    <p>${appData.skills}</p>
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