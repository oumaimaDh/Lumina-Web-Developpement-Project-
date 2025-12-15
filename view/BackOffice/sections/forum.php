<h2>📊 Dashboard Statistiques</h2>

<div class="forum-stats-grid">
    <div class="forum-stat-card">
        <div class="stat-icon-wrapper">❓</div>
        <div class="stat-label">Questions</div>
        <div class="stat-number"><?= $stats['totalQuestions'] ?></div>
    </div>
    <div class="forum-stat-card">
        <div class="stat-icon-wrapper">💬</div>
        <div class="stat-label">Réponses</div>
        <div class="stat-number"><?= $stats['totalResponses'] ?></div>
    </div>
    <div class="forum-stat-card">
        <div class="stat-icon-wrapper">❤️</div>
        <div class="stat-label">Likes</div>
        <div class="stat-number"><?= $stats['totalLikes'] ?></div>
    </div>
</div>

<div class="top-question-section">
    <h3>🔥 Question la plus likée</h3>
    <div class="top-question-content">
        <div class="top-question-title"><?= $stats['topQuestion']['title'] ?></div>
        <span class="top-question-likes">❤️ <?= $stats['topQuestion']['likes'] ?> likes</span>
    </div>
</div>

<h2>Forum Questions</h2>
<div id="questions-list">
    <?php foreach($questions as $q): ?>
        <div class="question-preview" data-id="<?= $q['id_question'] ?>" style="cursor: pointer;" onclick="toggleQuestionDetails(<?= $q['id_question'] ?>)">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <h3><?= htmlspecialchars($q['title']) ?></h3>
                <i class="fas fa-chevron-down expand-icon" style="transition: transform 0.3s;"></i>
            </div>
            <p><?= htmlspecialchars($q['content']) ?></p>
            <small><?= $q['date_question'] ?></small>
            <div>
                <button type="button" class="like-btn" data-id="<?= $q['id_question'] ?>">
                    ❤️ Like (<span class="like-count"><?= $q['likes'] ?></span>)
                </button>
                <button class="delete-btn" onclick="event.stopPropagation(); deleteQuestion(<?= $q['id_question'] ?>)">
                    🗑️ Delete
                </button>
            </div>
            
            <!-- Hidden details section for each question -->
            <div id="details-<?= $q['id_question'] ?>" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 2px solid #ddd;">
                <h4>💬 Responses</h4>
                <div id="responses-<?= $q['id_question'] ?>" class="response-box" style="min-height: 100px;">
                    <p style="text-align: center; color: #999;">Loading responses...</p>
                </div>
                
                <button class="btn-primary" onclick="event.stopPropagation(); toggleReplyForm(<?= $q['id_question'] ?>)" style="margin-top: 15px;">
                    ✍️ Write a Response
                </button>
                
                <div id="reply-form-<?= $q['id_question'] ?>" style="display: none; margin-top: 15px;">
                    <form onsubmit="submitResponse(event, <?= $q['id_question'] ?>)">
                        <textarea name="content" placeholder="Write your response here..." required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; min-height: 100px;"></textarea>
                        <div style="margin-top: 10px; display: flex; gap: 10px;">
                            <button type="submit" class="btn-primary">Send</button>
                            <button type="button" class="btn-secondary" onclick="event.stopPropagation(); toggleReplyForm(<?= $q['id_question'] ?>)">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>