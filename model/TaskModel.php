<?php

class TaskModel {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllTasks() {
        $sql = "
            SELECT 
                t.id,
                t.title,
                t.description,
                t.status_id,
                t.priority_id,
                t.category_id,
                t.progress,
                t.assignees,

                CASE 
                    WHEN t.status_id = 1 THEN 'To Do'
                    WHEN t.status_id = 2 THEN 'In Progress'
                    WHEN t.status_id = 3 THEN 'In Review'
                    WHEN t.status_id = 4 THEN 'Done'
                    ELSE 'Unknown'
                END AS status_name,

                CASE 
                    WHEN t.priority_id = 1 THEN 'Low'
                    WHEN t.priority_id = 2 THEN 'Medium'
                    WHEN t.priority_id = 3 THEN 'High'
                    ELSE 'Medium'
                END AS priority_name,

                CASE 
                    WHEN t.category_id = 1 THEN 'Planning'
                    WHEN t.category_id = 2 THEN 'Design'
                    WHEN t.category_id = 3 THEN 'Development'
                    WHEN t.category_id = 4 THEN 'Marketing'
                    ELSE 'General'
                END AS category_name

            FROM tasks t
            ORDER BY t.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>