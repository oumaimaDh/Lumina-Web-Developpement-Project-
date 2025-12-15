<?php
// viewj/Backjob/offers.php - Liste des offres (READ)
?>
<section id="offers-section" class="jobs-section active">
    <h2 class="section-title">All Job Offers</h2>
    <div class="offers-grid">
        <?php foreach ($offers as $offer): ?>
        <div class="offer-card">
            <div class="offer-header">
                <h3 class="offer-title"><?= htmlspecialchars($offer['title']) ?></h3>
                <span class="offer-status status-<?= $offer['status'] ?>">
                    <?= strtoupper($offer['status']) ?>
                </span>
            </div>
            <div class="offer-details">
                <p class="offer-location">📍 <?= htmlspecialchars($offer['location']) ?></p>
                <p class="offer-salary">💰 <?= $offer['salary_min'] ?>-<?= $offer['salary_max'] ?> TND</p>
                <p>Expires: <?= $offer['expiration_date'] ?></p>
                <p>Association: <?= htmlspecialchars($offer['association_name']) ?></p>
                <p>Contract: <?= implode(', ', json_decode($offer['contract_types'], true) ?? []) ?></p>
            </div>
            <div class="offer-actions">
                <button class="btn update-offer" 
                        onclick="window.location.href='index.php?view=edit_offer&edit_id=<?= $offer['id'] ?>&category=<?= $offer['category'] ?? 'health' ?>'">
                    ✏️ Update
                </button>
                <button class="btn delete-offer" 
                        onclick="if(confirm('Are you sure?')) window.location.href='index.php?action=delete_offer&id=<?= $offer['id'] ?>'">
                    🗑️ Delete
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>