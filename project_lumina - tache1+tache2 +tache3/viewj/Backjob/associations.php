<?php
// viewj/Backjob/associations.php - Vue des associations par catégorie
$category = $_GET['category'] ?? 'health';
?>
<section id="associations-section" class="jobs-section active">
    <button class="btn btn-back" onclick="window.location.href='index.php?view=categories'">← Back to Categories</button>
    <h2 class="section-title"><?= ucfirst($category) ?> Associations</h2>
    <div class="associations-grid">
        <?php foreach ($associationsByCategory[$category] as $association): ?>
        <div class="association-card">
            <div class="association-header">
                <h3 class="association-name"><?= htmlspecialchars($association['name']) ?></h3>
                <span class="rating"><?= $association['rating'] ?></span>
            </div>
            <p class="location">📍 <?= htmlspecialchars($association['location']) ?></p>
            <p class="description"><?= htmlspecialchars($association['description']) ?></p>
            <div class="active-offers">
                <span class="badge"><?= $association['active_offers'] ?> active positions</span>
            </div>
            <button class="btn new-offer-btn" onclick="window.location.href='index.php?view=create_offer&association_id=<?= $association['id'] ?>&category=<?= $category ?>'">
                ➕ New Offer
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</section>