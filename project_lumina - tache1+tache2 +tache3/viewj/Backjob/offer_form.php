<?php
// viewj/Backjob/offer_form.php - Formulaire création/édition d'offre
?>
<section id="offer-form-section" class="jobs-section active">
    <h2 class="section-title">
        <button class="btn btn-back" onclick="window.location.href='index.php?view=associations&category=<?= $_GET['category'] ?? 'health' ?>'">← Back to Associations</button>
        <?= isset($_GET['edit_id']) ? 'Update Offer' : 'Create New Offer' ?>
    </h2>
    
    <form method="POST" id="new-offer-form">
        <input type="hidden" name="<?= isset($_GET['edit_id']) ? 'update_offer' : 'create_offer' ?>" value="1">
        <?php if (isset($_GET['edit_id'])): ?>
        <input type="hidden" name="offer_id" value="<?= $_GET['edit_id'] ?>">
        <?php endif; ?>
        
        <input type="hidden" name="association_id" value="<?= $_GET['association_id'] ?? ($editingOffer['association_id'] ?? '1') ?>">
        
        <div class="form-group">
            <label for="job-title">Job Title *</label>
            <input type="text" id="job-title" name="job-title" 
                   value="<?= htmlspecialchars($editingOffer['title'] ?? '') ?>" 
                   placeholder="e.g., Senior Nurse, Doctor..." required>
            <div class="validation-message" id="title-validation"></div>
        </div>
        
        <div class="form-group">
            <label for="job-location">Location *</label>
            <input type="text" id="job-location" name="job-location" 
                   value="<?= htmlspecialchars($editingOffer['location'] ?? '') ?>" 
                   placeholder="Enter job location" required>
            <div class="validation-message" id="location-validation"></div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="salary-min">Minimum Salary (TND) *</label>
                <input type="number" id="salary-min" name="salary-min" 
                       value="<?= $editingOffer['salary_min'] ?? '' ?>" 
                       min="0" max="10000" step="100" required>
            </div>
            <div class="form-group">
                <label for="salary-max">Maximum Salary (TND) *</label>
                <input type="number" id="salary-max" name="salary-max" 
                       value="<?= $editingOffer['salary_max'] ?? '' ?>" 
                       min="0" max="10000" step="100" required>
            </div>
        </div>
        <div class="validation-message" id="salary-validation"></div>
        
        <div class="form-group">
            <label for="expiration-date">Expiration Date *</label>
            <input type="date" id="expiration-date" name="expiration-date" 
                   value="<?= $editingOffer['expiration_date'] ?? '' ?>" required>
            <div class="validation-message" id="date-validation"></div>
        </div>
        
        <div class="form-group">
            <label>Contract Type</label>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px;">
                <?php
                $contractTypes = ['CDD', 'CDI', 'Stage', 'Freelance'];
                $selectedTypes = $editingOffer ? json_decode($editingOffer['contract_types'], true) : [];
                foreach ($contractTypes as $type): 
                ?>
                <label style="display: flex; align-items: center; gap: 5px;">
                    <input type="checkbox" name="contract-type[]" value="<?= $type ?>" 
                        <?= in_array($type, $selectedTypes) ? 'checked' : '' ?>>
                    <?= $type ?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="job-description">Job Description *</label>
            <textarea id="job-description" name="job-description" rows="5" 
                      placeholder="Describe the position, responsibilities, requirements..." 
                      required><?= htmlspecialchars($editingOffer['description'] ?? '') ?></textarea>
            <div class="char-count"><span id="char-counter">0</span>/5000 characters</div>
            <div class="validation-message" id="description-validation"></div>
        </div>
        
        <div class="form-group">
            <label for="required-skills">Required Skills *</label>
            <div class="skills-container">
                <input type="text" id="skill-input" placeholder="Add a skill...">
                <button type="button" id="add-skill" class="btn">+ Add</button>
            </div>
            <div class="skills-tags" id="skills-tags">
                <!-- Skills will be added here dynamically -->
            </div>
            <div class="validation-message" id="skills-validation"></div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn publish-btn" id="submit-btn">
                🚀 <?= isset($_GET['edit_id']) ? 'Update' : 'Publish' ?> Offer
            </button>
            <button type="button" class="btn cancel-btn" 
                    onclick="window.location.href='index.php?view=offers'">
                ❌ Cancel
            </button>
        </div>
    </form>
</section>