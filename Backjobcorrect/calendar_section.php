<?php
// viewj/Backjob/calendar_section.php - Section Calendrier des Interviews

// Initialiser le contrôleur des interviews
// Define base path
$basePath = realpath(dirname(__DIR__) . '/..');
if (!$basePath) {
    $basePath = dirname(dirname(__DIR__));
}
require_once $basePath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'InterviewController.php';
$interviewController = new InterviewController();

// Si on vient de changer le status d'une application à "interview"
$preselectedApplicationId = $_GET['application_id'] ?? null;
$preselectedApplication = null;
if ($preselectedApplicationId) {
    $preselectedApplication = $interviewController->getApplicationById($preselectedApplicationId);
}

// Gérer les actions
$action = $_GET['action'] ?? '';
$interviewId = $_GET['interview_id'] ?? null;

// DÉPLACER TOUT LE TRAITEMENT DES FORMULAIRES ICI, AVANT TOUTE SORTIE
$redirectNeeded = false;
$redirectUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['schedule_interview'])) {
        $interviewData = [
            'application_id' => $_POST['application_id'],
            'interview_date' => $_POST['interview_date'],
            'interview_time' => $_POST['interview_time'],
            'interview_type' => $_POST['interview_type'],
            'location' => $_POST['location'] ?? '',
            'meeting_link' => $_POST['meeting_link'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'duration' => $_POST['duration'] ?? 60,
            'status' => 'scheduled'
        ];
        
        if ($interviewController->scheduleInterview($interviewData)) {
            $redirectNeeded = true;
            $redirectUrl = 'index.php?view=calendar&success=1';
        }
    }
    
    if (isset($_POST['update_interview'])) {
        $interviewController->updateInterview(
            $_POST['interview_id'],
            $_POST['interview_date'],
            $_POST['interview_time'],
            $_POST['interview_type'],
            $_POST['location'] ?? '',
            $_POST['meeting_link'] ?? '',
            $_POST['notes'] ?? '',
            $_POST['duration'] ?? 60,
            $_POST['status'] ?? 'scheduled'
        );
        
        $redirectNeeded = true;
        $redirectUrl = 'index.php?view=calendar&success=1';
    }
    
    if (isset($_POST['cancel_interview'])) {
        $interviewController->updateInterviewStatus($_POST['interview_id'], 'cancelled');
        $redirectNeeded = true;
        $redirectUrl = 'index.php?view=calendar&success=1';
    }
    
    if (isset($_POST['complete_interview'])) {
        $interviewController->updateInterviewStatus($_POST['interview_id'], 'completed');
        $redirectNeeded = true;
        $redirectUrl = 'index.php?view=calendar&success=1';
    }
    
    // Si une redirection est nécessaire, faire un redirect JavaScript
    if ($redirectNeeded) {
        echo '<script>window.location.href = "' . $redirectUrl . '";</script>';
        exit;
    }
}

// Récupérer les données
$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('n');
$selectedDate = $_GET['date'] ?? date('Y-m-d');

// Récupérer les interviews
$interviews = $interviewController->getInterviewsByMonth($year, $month);
$dailyInterviews = $interviewController->getInterviewsByDate($selectedDate);
$upcomingInterviews = $interviewController->getUpcomingInterviews();

// Récupérer les candidatures avec status "interview"
$interviewApplications = $interviewController->getApplicationsForInterview();

// Si on veut éditer une interview
$editingInterview = null;
if ($interviewId) {
    $editingInterview = $interviewController->getInterviewById($interviewId);
}

// Calculer les statistiques
$stats = $interviewController->getInterviewStats();

// Vérifier si on a un message de succès
$showSuccess = isset($_GET['success']);
?>

<section id="calendar-section" class="jobs-section active">
    <h2 class="section-title">📅 Interview Calendar</h2>
    
    <!-- Message de succès -->
    <?php if ($showSuccess): ?>
    <div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">
        ✅ Interview scheduled successfully!
    </div>
    <?php endif; ?>
    
    <!-- Statistiques -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: #3B82F6;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Scheduled</div>
                <div class="stat-value"><?= $stats['scheduled'] ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #10B981;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Completed</div>
                <div class="stat-value"><?= $stats['completed'] ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #EF4444;">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Cancelled</div>
                <div class="stat-value"><?= $stats['cancelled'] ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #8B5CF6;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">This Week</div>
                <div class="stat-value"><?= $stats['this_week'] ?></div>
            </div>
        </div>
    </div>
    
    <!-- Conteneur principal -->
    <div class="calendar-container" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- Calendrier -->
        <div class="calendar-main" style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
            <!-- En-tête du calendrier -->
            <div class="calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <div>
                    <h3 style="color: var(--primary-dark); margin: 0; font-size: 1.4rem;">
                        <?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?>
                    </h3>
                </div>
                <div class="calendar-navigation" style="display: flex; gap: 10px;">
                    <button class="btn" onclick="window.location.href='index.php?view=calendar&year=<?= date('Y', strtotime('-1 month', mktime(0, 0, 0, $month, 1, $year))) ?>&month=<?= date('n', strtotime('-1 month', mktime(0, 0, 0, $month, 1, $year))) ?>'">
                        ← Prev
                    </button>
                    <button class="btn" onclick="window.location.href='index.php?view=calendar&year=<?= date('Y') ?>&month=<?= date('n') ?>'">
                        Today
                    </button>
                    <button class="btn" onclick="window.location.href='index.php?view=calendar&year=<?= date('Y', strtotime('+1 month', mktime(0, 0, 0, $month, 1, $year))) ?>&month=<?= date('n', strtotime('+1 month', mktime(0, 0, 0, $month, 1, $year))) ?>'">
                        Next →
                    </button>
                </div>
            </div>
            
            <!-- Grille du calendrier -->
            <div class="calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">
                <!-- En-têtes des jours -->
                <?php 
                $dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                foreach ($dayHeaders as $day): 
                ?>
                <div class="calendar-day-header" style="text-align: center; padding: 12px; font-weight: 600; color: var(--primary-medium); font-size: 0.9rem;">
                    <?= $day ?>
                </div>
                <?php endforeach; ?>
                
                <!-- Jours du mois -->
                <?php
                $firstDay = date('w', mktime(0, 0, 0, $month, 1, $year));
                $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
                $currentDay = 1;

                // Celles vides avant le premier jour
                for ($i = 0; $i < $firstDay; $i++) {
                    echo '<div class="calendar-day empty" style="aspect-ratio: 1; background: #f8f9fa; border-radius: 8px;"></div>';
                }

                // Jours du mois
                while ($currentDay <= $daysInMonth) {
                    $currentDate = date('Y-m-d', mktime(0, 0, 0, $month, $currentDay, $year));
                    $isToday = $currentDate == date('Y-m-d');
                    $hasInterview = isset($interviews[$currentDate]) && count($interviews[$currentDate]) > 0;
                    
                    $dayClasses = 'calendar-day';
                    $dayStyles = 'aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; padding: 8px;';
                    
                    if ($isToday) {
                        $dayStyles .= ' background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium)); color: white; font-weight: bold;';
                    } else if ($selectedDate == $currentDate) {
                        $dayStyles .= ' background: var(--accent-pink); color: var(--primary-dark); border: 2px solid var(--primary-light);';
                    } else {
                        $dayStyles .= ' background: white; border: 1px solid rgba(0,0,0,0.08);';
                    }
                    
                    echo '<div class="' . $dayClasses . '" 
                         style="' . $dayStyles . '"
                         onclick="window.location.href=\'index.php?view=calendar&date=' . $currentDate . '&year=' . $year . '&month=' . $month . '\'">
                        <div style="font-size: 1.1rem; font-weight: ' . ($isToday ? 'bold' : 'normal') . ';">
                            ' . $currentDay . '
                        </div>';
                    
                    if ($hasInterview) {
                        echo '<div style="margin-top: 4px;">
                                <span style="display: inline-block; width: 6px; height: 6px; background: #EF4444; border-radius: 50%;"></span>
                                <span style="font-size: 0.7rem; color: ' . ($isToday ? 'rgba(255,255,255,0.8)' : 'var(--primary-medium)') . ';">
                                    ' . count($interviews[$currentDate]) . '
                                </span>
                              </div>';
                    }
                    
                    echo '</div>';
                    
                    $currentDay++;
                }
                ?>
            </div>
            
            <!-- Légende -->
            <div class="calendar-legend" style="display: flex; gap: 20px; margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.08);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; background: var(--primary-dark); border-radius: 50%;"></div>
                    <span style="font-size: 0.85rem; color: var(--primary-medium);">Today</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; background: var(--accent-pink); border-radius: 50%;"></div>
                    <span style="font-size: 0.85rem; color: var(--primary-medium);">Selected</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; background: #EF4444; border-radius: 50%;"></div>
                    <span style="font-size: 0.85rem; color: var(--primary-medium);">Has Interview</span>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="calendar-sidebar" style="display: flex; flex-direction: column; gap: 25px;">
            <!-- Interviews du jour sélectionné -->
            <div class="daily-interviews" style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
                <h4 style="color: var(--primary-dark); margin-bottom: 20px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-calendar-day"></i>
                    Interviews for <?= date('M j, Y', strtotime($selectedDate)) ?>
                </h4>
                
                <?php if (empty($dailyInterviews)): ?>
                <div style="text-align: center; padding: 20px; color: var(--primary-medium);">
                    <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                    <p>No interviews scheduled</p>
                </div>
                <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($dailyInterviews as $interview): ?>
                    <div class="interview-item" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid <?= $interview['status'] == 'completed' ? '#10B981' : ($interview['status'] == 'cancelled' ? '#EF4444' : '#3B82F6') ?>;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div>
                                <strong style="color: var(--primary-dark);"><?= $interview['candidate_name'] ?></strong>
                                <div style="font-size: 0.85rem; color: var(--primary-medium); margin-top: 4px;">
                                    <?= date('h:i A', strtotime($interview['interview_time'])) ?> • 
                                    <?= $interview['interview_type'] == 'in_person' ? '📍 In Person' : '🌐 Online' ?>
                                </div>
                            </div>
                            <span class="badge" style="background: <?= $interview['status'] == 'completed' ? '#10B981' : ($interview['status'] == 'cancelled' ? '#EF4444' : '#3B82F6') ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem;">
                                <?= ucfirst($interview['status']) ?>
                            </span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--primary-dark); margin-bottom: 10px;">
                            Position: <?= $interview['position'] ?>
                        </div>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <?php if ($interview['interview_type'] == 'in_person'): ?>
                            <span style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">
                                📍 <?= $interview['location'] ?>
                            </span>
                            <?php else: ?>
                            <span style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">
                                🌐 Online Meeting
                            </span>
                            <?php endif; ?>
                            <span style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">
                                ⏱️ <?= $interview['duration'] ?> min
                            </span>
                        </div>
                        <div style="display: flex; gap: 8px; margin-top: 12px;">
                            <?php if ($interview['status'] == 'scheduled'): ?>
                            <button class="btn" onclick="completeInterview(<?= $interview['id'] ?>)" style="background: #10B981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer;">
                                Complete
                            </button>
                            <button class="btn" onclick="cancelInterview(<?= $interview['id'] ?>)" style="background: #EF4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer;">
                                Cancel
                            </button>
                            <?php endif; ?>
                            <button class="btn" onclick="editInterview(<?= $interview['id'] ?>)" style="background: var(--primary-light); color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer;">
                                Edit
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Bouton pour planifier une nouvelle interview -->
            <div style="text-align: center;">
                <button class="btn" onclick="showScheduleForm()" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium)); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus"></i> Schedule New Interview
                </button>
            </div>
        </div>
    </div>
    
    <!-- Prochaines interviews -->
    <div class="upcoming-interviews" style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
        <h4 style="color: var(--primary-dark); margin-bottom: 20px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-clock"></i>
            Upcoming Interviews (Next 7 Days)
        </h4>
        
        <?php if (empty($upcomingInterviews)): ?>
        <div style="text-align: center; padding: 20px; color: var(--primary-medium);">
            <i class="fas fa-calendar-check" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
            <p>No upcoming interviews</p>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
            <?php foreach ($upcomingInterviews as $interview): ?>
            <div class="upcoming-card" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #3B82F6;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                    <div>
                        <strong style="color: var(--primary-dark);"><?= $interview['candidate_name'] ?></strong>
                        <div style="font-size: 0.85rem; color: var(--primary-medium); margin-top: 4px;">
                            <?= date('M j, Y', strtotime($interview['interview_date'])) ?> at <?= date('h:i A', strtotime($interview['interview_time'])) ?>
                        </div>
                    </div>
                    <span style="background: #3B82F6; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem;">
                        <?= $interview['interview_type'] == 'in_person' ? '📍' : '🌐' ?>
                    </span>
                </div>
                <div style="font-size: 0.85rem; color: var(--primary-dark);">
                    Position: <?= $interview['position'] ?>
                </div>
                <div style="margin-top: 10px;">
                    <a href="index.php?view=applications" class="btn" style="background: var(--primary-light); color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; text-decoration: none; display: inline-block;">
                        View Application
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Modal pour planifier une interview -->
<div id="schedule-modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; border-radius: 12px; padding: 30px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(0,0,0,0.08);">
            <h3 style="color: var(--primary-dark); margin: 0; font-size: 1.3rem;">
                <i class="fas fa-calendar-plus"></i> Schedule Interview
            </h3>
            <button onclick="closeScheduleForm()" style="background: none; border: none; color: var(--primary-medium); font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <!-- Zone pour les messages d'erreur -->
        <div id="validation-errors" style="display: none; background: #fee; color: #c33; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fcc;">
            <strong>Please fix the following errors:</strong>
            <ul id="error-list" style="margin: 8px 0 0 20px; padding: 0;"></ul>
        </div>
        
        <form method="POST" id="schedule-form" onsubmit="return validateScheduleForm()">
            <?php if ($editingInterview): ?>
            <input type="hidden" name="update_interview" value="1">
            <input type="hidden" name="interview_id" value="<?= $editingInterview['id'] ?>">
            <?php else: ?>
            <input type="hidden" name="schedule_interview" value="1">
            <?php endif; ?>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Candidate *</label>
                <select name="application_id" id="application_id" style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px; background: white; color: var(--primary-dark);">
                    <option value="">Select Candidate</option>
                    <?php foreach ($interviewApplications as $application): ?>
                    <option value="<?= $application['id'] ?>" 
                        <?= ($preselectedApplication && $preselectedApplication['id'] == $application['id']) || 
                            ($editingInterview && $editingInterview['application_id'] == $application['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($application['full_name']) ?> - <?= htmlspecialchars($application['profession']) ?> (Applied: <?= date('M j', strtotime($application['applied_at'])) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Date *</label>
                    <input type="date" name="interview_date" id="interview_date" value="<?= $editingInterview['interview_date'] ?? $selectedDate ?>" 
                           style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px;">
                </div>
                
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Time *</label>
                    <input type="time" name="interview_time" id="interview_time" value="<?= $editingInterview['interview_time'] ?? '10:00' ?>" 
                           style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px;">
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Interview Type *</label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="interview_type" id="interview_type_in_person" value="in_person" <?= (!$editingInterview || $editingInterview['interview_type'] == 'in_person') ? 'checked' : '' ?>>
                        <span>📍 In Person</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="interview_type" id="interview_type_online" value="online" <?= ($editingInterview && $editingInterview['interview_type'] == 'online') ? 'checked' : '' ?>>
                        <span>🌐 Online</span>
                    </label>
                </div>
            </div>
            
            <div id="location-field" class="form-group" style="margin-bottom: 20px; <?= ($editingInterview && $editingInterview['interview_type'] == 'online') ? 'display: none;' : '' ?>">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Location *</label>
                <input type="text" name="location" id="location" value="<?= $editingInterview['location'] ?? '' ?>" placeholder="Enter interview location"
                       style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px;">
            </div>
            
            <div id="meeting-field" class="form-group" style="margin-bottom: 20px; <?= (!$editingInterview || $editingInterview['interview_type'] == 'in_person') ? 'display: none;' : '' ?>">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Meeting Link</label>
                <input type="text" name="meeting_link" id="meeting_link" value="<?= $editingInterview['meeting_link'] ?? '' ?>" placeholder="https://meet.google.com/xxx-yyyy-zzz or Zoom/Teams link"
                       style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px;">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Duration (minutes)</label>
                    <select name="duration" id="duration" style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px;">
                        <option value="30" <?= ($editingInterview && $editingInterview['duration'] == 30) ? 'selected' : '' ?>>30 min</option>
                        <option value="45" <?= ($editingInterview && $editingInterview['duration'] == 45) ? 'selected' : '' ?>>45 min</option>
                        <option value="60" <?= (!$editingInterview || $editingInterview['duration'] == 60) ? 'selected' : '' ?>>60 min</option>
                        <option value="90" <?= ($editingInterview && $editingInterview['duration'] == 90) ? 'selected' : '' ?>>90 min</option>
                        <option value="120" <?= ($editingInterview && $editingInterview['duration'] == 120) ? 'selected' : '' ?>>120 min</option>
                    </select>
                </div>
                
                <?php if ($editingInterview): ?>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Status</label>
                    <select name="status" id="status" style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px;">
                        <option value="scheduled" <?= ($editingInterview && $editingInterview['status'] == 'scheduled') ? 'selected' : '' ?>>Scheduled</option>
                        <option value="completed" <?= ($editingInterview && $editingInterview['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= ($editingInterview && $editingInterview['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-dark);">Notes</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Additional notes for the interview..." style="width: 100%; padding: 12px; border: 2px solid rgba(107,133,168,0.2); border-radius: 8px;"><?= $editingInterview['notes'] ?? '' ?></textarea>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button type="button" onclick="closeScheduleForm()" style="background: #EF4444; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Cancel
                </button>
                <button type="submit" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <?= $editingInterview ? 'Update Interview' : 'Schedule Interview' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Styles pour le calendrier */
.calendar-day:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.interview-item:hover {
    transform: translateX(5px);
    transition: transform 0.3s ease;
}

.upcoming-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Animation pour la modal */
@keyframes modalAppear {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

#schedule-modal .modal-content {
    animation: modalAppear 0.3s ease;
}

/* Style pour les erreurs */
.input-error {
    border-color: #EF4444 !important;
    background-color: rgba(239, 68, 68, 0.05) !important;
}

.error-message {
    color: #EF4444;
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
}

/* Responsive */
@media (max-width: 1024px) {
    .calendar-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .calendar-grid {
        gap: 4px;
    }
    
    .calendar-day-header {
        padding: 8px 4px;
        font-size: 0.8rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
// Fonction principale de validation du formulaire
function validateScheduleForm() {
    const errors = [];
    const errorElements = [];
    
    // Réinitialiser les styles d'erreur
    document.querySelectorAll('.input-error').forEach(el => {
        el.classList.remove('input-error');
    });
    
    // Supprimer les anciens messages d'erreur
    document.querySelectorAll('.error-message').forEach(el => {
        el.remove();
    });
    
    // Masquer le bloc d'erreurs général
    document.getElementById('validation-errors').style.display = 'none';
    document.getElementById('error-list').innerHTML = '';
    
    // 1. Validation du candidat (required)
    const applicationId = document.getElementById('application_id').value;
    if (!applicationId || applicationId === '') {
        errors.push('Please select a candidate');
        errorElements.push('application_id');
    }
    
    // 2. Validation de la date (required)
    const interviewDate = document.getElementById('interview_date').value;
    if (!interviewDate) {
        errors.push('Interview date is required');
        errorElements.push('interview_date');
    } else {
        // Validation: date ne doit pas être dans le passé
        const selectedDate = new Date(interviewDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            errors.push('Interview date cannot be in the past');
            errorElements.push('interview_date');
        }
    }
    
    // 3. Validation de l'heure (required)
    const interviewTime = document.getElementById('interview_time').value;
    if (!interviewTime) {
        errors.push('Interview time is required');
        errorElements.push('interview_time');
    }
    
    // 4. Validation du type d'interview (required)
    const interviewType = document.querySelector('input[name="interview_type"]:checked');
    if (!interviewType) {
        errors.push('Please select an interview type');
    }
    
    // 5. Validation conditionnelle selon le type
    if (interviewType && interviewType.value === 'in_person') {
        const location = document.getElementById('location').value;
        if (!location || location.trim() === '') {
            errors.push('Location is required for in-person interviews');
            errorElements.push('location');
        }
    }
    
    if (interviewType && interviewType.value === 'online') {
        const meetingLink = document.getElementById('meeting_link').value;
        if (meetingLink && meetingLink.trim() !== '') {
            // Validation basique de l'URL (pas trop stricte)
            if (!meetingLink.includes('://') && !meetingLink.startsWith('http')) {
                errors.push('Please enter a valid meeting link (include http:// or https://)');
                errorElements.push('meeting_link');
            }
        }
    }
    
    // 6. Validation de la durée (doit être un nombre)
    const duration = document.getElementById('duration').value;
    if (duration && isNaN(parseInt(duration))) {
        errors.push('Duration must be a number');
        errorElements.push('duration');
    }
    
    // Afficher les erreurs s'il y en a
    if (errors.length > 0) {
        // Afficher le bloc d'erreurs général
        const errorList = document.getElementById('error-list');
        errors.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            errorList.appendChild(li);
        });
        document.getElementById('validation-errors').style.display = 'block';
        
        // Appliquer les styles d'erreur aux champs concernés
        errorElements.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.classList.add('input-error');
                
                // Ajouter un message d'erreur sous le champ
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = errors.find(e => e.toLowerCase().includes(fieldId.replace('_', ' '))) || 'Invalid value';
                field.parentNode.appendChild(errorDiv);
            }
        });
        
        // Faire défiler jusqu'au haut du formulaire
        document.getElementById('validation-errors').scrollIntoView({ behavior: 'smooth' });
        
        return false; // Empêcher la soumission
    }
    
    // Validation supplémentaire: vérifier les conflits d'horaire (optionnel)
    return validateScheduleConflicts(interviewDate, interviewTime);
}

// Fonction pour valider les conflits d'horaire (optionnelle)
function validateScheduleConflicts(date, time) {
    // Cette fonction pourrait faire une requête AJAX pour vérifier
    // si le recruteur a déjà un entretien à cette heure
    // Pour l'instant, on retourne true
    
    // Exemple de logique:
    // const currentDate = new Date().toISOString().split('T')[0];
    // if (date === currentDate) {
    //     const currentTime = new Date().getHours() + ':' + new Date().getMinutes();
    //     if (time < currentTime) {
    //         alert('Cannot schedule interview in the past');
    //         return false;
    //     }
    // }
    
    return true;
}

// Gestion de l'affichage des champs selon le type d'interview
document.querySelectorAll('input[name="interview_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const locationField = document.getElementById('location-field');
        const meetingField = document.getElementById('meeting-field');
        
        if (this.value === 'in_person') {
            locationField.style.display = 'block';
            meetingField.style.display = 'none';
            
            // Nettoyer les styles d'erreur
            document.getElementById('meeting_link').classList.remove('input-error');
            const meetingError = document.querySelector('#meeting-field .error-message');
            if (meetingError) meetingError.remove();
        } else {
            locationField.style.display = 'none';
            meetingField.style.display = 'block';
            
            // Nettoyer les styles d'erreur
            document.getElementById('location').classList.remove('input-error');
            const locationError = document.querySelector('#location-field .error-message');
            if (locationError) locationError.remove();
        }
    });
});

// Validation en temps réel pour certains champs
document.getElementById('interview_date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        this.classList.add('input-error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = 'Date cannot be in the past';
        this.parentNode.appendChild(errorDiv);
    } else {
        this.classList.remove('input-error');
        const errorDiv = this.parentNode.querySelector('.error-message');
        if (errorDiv) errorDiv.remove();
    }
});

document.getElementById('meeting_link').addEventListener('blur', function() {
    const value = this.value.trim();
    if (value !== '' && !value.includes('://') && !value.startsWith('http')) {
        this.classList.add('input-error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = 'Include http:// or https:// in the URL';
        this.parentNode.appendChild(errorDiv);
    } else {
        this.classList.remove('input-error');
        const errorDiv = this.parentNode.querySelector('.error-message');
        if (errorDiv) errorDiv.remove();
    }
});

// Fonctions pour la modal
function showScheduleForm() {
    document.getElementById('schedule-modal').style.display = 'flex';
    // Réinitialiser les erreurs
    document.getElementById('validation-errors').style.display = 'none';
}

function closeScheduleForm() {
    document.getElementById('schedule-modal').style.display = 'none';
    // Si on était en mode édition, on recharge pour sortir du mode édition
    if (window.location.href.includes('interview_id=')) {
        window.location.href = window.location.href.split('&interview_id=')[0];
    }
}

// Fonctions pour les actions sur les interviews
function completeInterview(interviewId) {
    if (confirm('Mark this interview as completed?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'complete_interview';
        input.value = '1';
        form.appendChild(input);
        
        const input2 = document.createElement('input');
        input2.type = 'hidden';
        input2.name = 'interview_id';
        input2.value = interviewId;
        form.appendChild(input2);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelInterview(interviewId) {
    if (confirm('Cancel this interview?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'cancel_interview';
        input.value = '1';
        form.appendChild(input);
        
        const input2 = document.createElement('input');
        input2.type = 'hidden';
        input2.name = 'interview_id';
        input2.value = interviewId;
        form.appendChild(input2);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function editInterview(interviewId) {
    window.location.href = 'index.php?view=calendar&interview_id=' + interviewId + '&date=<?= $selectedDate ?>';
}

// Si on est en mode édition, ouvrir automatiquement la modal
<?php if ($editingInterview): ?>
document.addEventListener('DOMContentLoaded', function() {
    showScheduleForm();
});
<?php endif; ?>

// Fermer la modal en cliquant à l'extérieur
window.onclick = function(event) {
    const modal = document.getElementById('schedule-modal');
    if (event.target === modal) {
        closeScheduleForm();
    }
}
</script>