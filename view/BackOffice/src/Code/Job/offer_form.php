<?php
// viewj/Backjob/offer_form.php - Formulaire création/édition d'offre
?>
<section id="offer-form-section" class="jobs-section active">
    <h2 class="section-title">
        <button class="btn btn-back" onclick="window.location.href='index.php?view=associations&category=<?= $_GET['category'] ?? 'health' ?>'">← Back to Associations</button>
        <?= isset($_GET['edit_id']) ? 'Update Offer' : 'Create New Offer' ?>
    </h2>
    
    <form method="POST" id="new-offer-form" novalidate> <!-- AJOUT: novalidate pour désabler validation HTML5 -->
        <input type="hidden" name="<?= isset($_GET['edit_id']) ? 'update_offer' : 'create_offer' ?>" value="1">
        <?php if (isset($_GET['edit_id'])): ?>
        <input type="hidden" name="offer_id" value="<?= $_GET['edit_id'] ?>">
        <?php endif; ?>
        
        <input type="hidden" name="association_id" value="<?= $_GET['association_id'] ?? ($editingOffer['association_id'] ?? '1') ?>">
        
        <div class="form-group">
            <label for="job-title">Job Title *</label>
            <input type="text" id="job-title" name="job-title" 
                   value="<?= htmlspecialchars($editingOffer['title'] ?? '') ?>" 
                   placeholder="e.g., Senior Nurse, Doctor..."
                   class="form-control">
            <div class="field-msg" id="job-title-msg"></div>
        </div>
        
        <div class="form-group">
            <label for="job-location">Location *</label>
            <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Cliquez sur la carte pour sélectionner l'emplacement / Click on the map to select location</p>
            <div id="job-location-map" style="height: 300px; width: 100%; border-radius: 8px; margin: 10px 0; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: #e8e8e8; position: relative; z-index: 1;"></div>
            <div style="margin-top: 10px; padding: 10px; background: #f6e7fe; border-radius: 8px;">
                <p><strong>Selected Location:</strong> <span id="selected-job-location">Click on the map to select location</span></p>
                <input type="hidden" id="job-location" name="job-location" value="<?= htmlspecialchars($editingOffer['location'] ?? '') ?>">
                <input type="hidden" name="job_loc_lat" id="job_loc_lat" value="">
                <input type="hidden" name="job_loc_lng" id="job_loc_lng" value="">
            </div>
            <div class="field-msg" id="job-location-msg"></div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="salary-min">Minimum Salary (TND) *</label>
                <input type="number" id="salary-min" name="salary-min" 
                       value="<?= $editingOffer['salary_min'] ?? '' ?>" 
                       min="0" max="10000" step="100"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="salary-max">Maximum Salary (TND) *</label>
                <input type="number" id="salary-max" name="salary-max" 
                       value="<?= $editingOffer['salary_max'] ?? '' ?>" 
                       min="0" max="10000" step="100"
                       class="form-control">
            </div>
        </div>
        <div class="field-msg" id="salary-msg"></div>
        
        <div class="form-group">
            <label for="expiration-date">Expiration Date *</label>
            <input type="date" id="expiration-date" name="expiration-date" 
                   value="<?= $editingOffer['expiration_date'] ?? '' ?>"
                   class="form-control">
            <div class="field-msg" id="expiration-date-msg"></div>
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
                      class="form-control"><?= htmlspecialchars($editingOffer['description'] ?? '') ?></textarea>
            <div class="char-count"><span id="char-counter">0</span>/5000 characters</div>
            <div class="field-msg" id="job-description-msg"></div>
        </div>
        
        <div class="form-group">
            <label for="required-skills">Required Skills *</label>
            <div class="skills-container">
                <input type="text" id="skill-input" placeholder="Add a skill..." class="form-control">
                <button type="button" id="add-skill" class="btn">+ Add</button>
            </div>
            <div class="skills-tags" id="skills-tags">
                <!-- Skills will be added here dynamically -->
            </div>
            <div class="field-msg" id="skills-msg"></div>
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

<script>
// Initialisation immédiate pour garantir que le JS est prêt
document.addEventListener('DOMContentLoaded', function() {
    console.log('Offer form page loaded');
    // Garantir que l'initialisation se fait
    if (typeof initOfferForm === 'function') {
        initOfferForm();
    }
});
</script>