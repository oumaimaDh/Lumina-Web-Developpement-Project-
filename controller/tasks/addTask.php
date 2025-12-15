<?php
require_once "../../config/db.php";
require_once "../../model/TaskModel.php";

$db = new Database();
$pdo = $db->connect();
$model = new TaskModel($pdo);

$tasks = $model->getAllTasks();

foreach ($tasks as $t) {

    // Correct column mapping
    $column = [
        1 => "todoTasks",
        2 => "inProgressTasks",
        3 => "inReviewTasks",
        4 => "doneTasks"
    ][$t["status_id"]];

    // Progress dots
    $dots = "";
    $filled = intval($t["progress"] / 10);
    for ($i = 1; $i <= 10; $i++) {
        $dots .= $i <= $filled
            ? "<span class='dot full'></span>"
            : "<span class='dot empty'></span>";
    }

    // Assignees: AD, MK, JS → bubbles
    $avatars = "";
    foreach (explode(",", $t["assignees"]) as $a) {
        $initials = strtoupper(substr(trim($a), 0, 2));
        $avatars .= "<span class='avatar'>$initials</span>";
    }

    echo "
    <div class='task-card-wrapper' data-column='$column'>
        <div class='task-card'>

            <div class='task-header'>
                <span class='badge category'>{$t['category_name']}</span>
                <span class='badge priority'>{$t['priority_name']}</span>
            </div>

            <h3 class='task-title'>{$t['title']}</h3>
            <p class='task-desc'>{$t['description']}</p>

            <div class='progress-bar'>
                $dots
                <span class='progress-text'>{$t['progress']}%</span>
            </div>

            <div class='task-footer'>
                <div class='assignees'>$avatars</div>
            </div>

        </div>
    </div>
    ";
}
?>