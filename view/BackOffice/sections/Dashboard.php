<?php
require_once __DIR__ . '/../../controller/DashboardController.php';

$dashboardController = new DashboardController();
$stats = $dashboardController->getOverviewStats();
$recentActivity = $dashboardController->getRecentActivity();
?>

<div class="welcome-card">
    <h2>Welcome to Lumina Dashboard 👋</h2>
    <p>Manage your forum, social cases, and community</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Total Questions</div>
            <div class="stat-value"><?= $stats['totalQuestions'] ?></div>
            <div class="stat-trend positive">↑ 12% from last month</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-comments"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Total Responses</div>
            <div class="stat-value"><?= $stats['totalResponses'] ?></div>
            <div class="stat-trend positive">↑ 8% from last month</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Active Users</div>
            <div class="stat-value"><?= $stats['totalUsers'] ?></div>
            <div class="stat-trend">→ No change</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-heart"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Total Likes</div>
            <div class="stat-value"><?= $stats['totalLikes'] ?></div>
            <div class="stat-trend positive">↑ 15% from last month</div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="section-title">
    <i class="fas fa-clock"></i>
    <h3>Recent Activity</h3>
</div>

<div class="widget-card">
    <div class="widget-header">
        <i class="fas fa-list"></i>
        <h4>Latest Questions</h4>
    </div>
    <div class="participants-list">
        <?php foreach($recentActivity as $activity): ?>
            <div class="participant-item">
                <div class="participant-info">
                    <div class="participant-avatar">
                        <i class="fas fa-question"></i>
                    </div>
                    <div class="participant-details">
                        <div class="participant-name"><?= htmlspecialchars($activity['title']) ?></div>
                        <div class="participant-event"><?= $activity['date_question'] ?></div>
                    </div>
                </div>
                <span class="badge status-pending">New</span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="section-title">
    <i class="fas fa-bolt"></i>
    <h3>Quick Actions</h3>
</div>

<div class="quick-actions-grid">
    <div class="quick-action-card" onclick="document.querySelector('[data-tab=forum]').click();">
        <i class="fas fa-comments"></i>
        <span>View Forum</span>
    </div>
    <div class="quick-action-card">
        <i class="fas fa-plus"></i>
        <span>Add Question</span>
    </div>
    <div class="quick-action-card">
        <i class="fas fa-chart-bar"></i>
        <span>View Analytics</span>
    </div>
    <div class="quick-action-card" onclick="document.querySelector('[data-tab=settings]').click();">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </div>
</div>