<?php
// viewj/Backjob/categories.php - Vue des catégories
?>
<section id="categories-section" class="jobs-section active">
    <h2 class="section-title">Browse by Category</h2>
    <div class="category-grid">
        <?php foreach ($associationsByCategory as $category => $assocs): ?>
        <div class="category-card" onclick="window.location.href='index.php?view=associations&category=<?= $category ?>'">
            <div class="category-icon">
                <?php 
                $icons = [
                    'health' => '🏥', 'restaurants' => '🍴', 'education' => '🎓',
                    'construction' => '🏗️', 'commerce' => '💼', 'other' => '🛠️'
                ];
                echo $icons[$category] ?? '📁';
                ?>
            </div>
            <h3 class="category-name">
                <?php 
                $names = [
                    'health' => 'Health & Hospitals', 'restaurants' => 'Cafés & Restaurants',
                    'education' => 'Education', 'construction' => 'Construction',
                    'commerce' => 'Commerce & Services', 'other' => 'Other Services'
                ];
                echo $names[$category] ?? ucfirst($category);
                ?>
            </h3>
            <p class="category-count"><?= count($assocs) ?> associations</p>
        </div>
        <?php endforeach; ?>
    </div>
</section>