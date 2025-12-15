<?php
// viewj/Backjob/categories.php - Vue des catégories (using database categories 5-9)
// Get database categories from index.php if not already set
if (!isset($dbCategories)) {
    $basePath = realpath(dirname(__DIR__) . '/..');
    if (!$basePath) {
        $basePath = dirname(dirname(__DIR__));
    }
    require_once $basePath . DIRECTORY_SEPARATOR . 'config.php';
    $db = Config::getConnexion();
    $sql = "SELECT id_category, name FROM category WHERE id_category IN (5, 6, 7, 8, 9) ORDER BY id_category";
    try {
        $query = $db->prepare($sql);
        $query->execute();
        $dbCategories = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
        $dbCategories = [];
    }
}

// Map category IDs to icons and category names for controller
$categoryMap = [
    5 => ['name' => 'Health & Hospitals', 'icon' => '🏥', 'key' => 'health'],
    6 => ['name' => 'Education', 'icon' => '🎓', 'key' => 'education'],
    7 => ['name' => 'Commerce & Services', 'icon' => '🛍️', 'key' => 'commerce'],
    8 => ['name' => 'Construction', 'icon' => '🏗️', 'key' => 'construction'],
    9 => ['name' => 'Restaurant', 'icon' => '☕', 'key' => 'restaurant']
];
?>
<section id="categories-section" class="jobs-section active">
    <h2 class="section-title">Browse by Category</h2>
    <div class="category-grid">
        <?php foreach ($dbCategories as $dbCat): 
            $categoryId = $dbCat['id_category'];
            $categoryInfo = $categoryMap[$categoryId] ?? ['name' => $dbCat['name'], 'icon' => '📁', 'key' => 'other'];
            $categoryKey = $categoryInfo['key'];
            $assocs = $associationsByCategory[$categoryKey] ?? [];
        ?>
        <div class="category-card" onclick="window.location.href='index.php?view=associations&category=<?= $categoryKey ?>'">
            <div class="category-icon">
                <?= $categoryInfo['icon'] ?>
            </div>
            <h3 class="category-name">
                <?= htmlspecialchars($dbCat['name']) ?>
            </h3>
            <p class="category-count"><?= count($assocs) ?> associations</p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
