<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/socialcasemodel.php';
require_once __DIR__ . '/notificationcontroller.php';

class SocialCaseController {

    // ================= GET ALL =================
    public function getAllSocialCases() {
        $db = Config::getConnexion();
        $sql = "SELECT sc.*, c.name AS category_name, a.name AS association_name 
                FROM social_case sc
                LEFT JOIN category c ON sc.id_category = c.id_category
                LEFT JOIN association a ON sc.id_association = a.id_association";
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= ADD =================
    public function addSocialCase($socialCase, $associationName = 'Unknown Association') {
        $db = Config::getConnexion();
        $sql = "INSERT INTO social_case 
                (name, phone, email, description, location, 
                 submited_date, updated_date, status, id_category, id_association) 
                VALUES (:n, :p, :e, :d, :l, :idate, :udate, :s, :c, :ida)";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'n'     => $socialCase->getName(),
                'p'     => $socialCase->getPhone(),
                'e'     => $socialCase->getEmail(),
                'd'     => $socialCase->getDescription(),
                'l'     => $socialCase->getLocation(),
                'idate' => $socialCase->getsubmitedDate(),
                'udate' => $socialCase->getUpdatedDate(),
                's'     => $socialCase->getStatus(),
                'c'     => $socialCase->getid_category(),
                'ida'   => $socialCase->getIdAssociation()
            ]);
            
            // Create notification for new case submission
            $case_id = $db->lastInsertId();
            try {
                require_once __DIR__ . '/notificationcontroller.php';
                $notificationController = new NotificationController();
                $message = "You have a new case submission (Case ID: " . $case_id . ") - Association: " . $associationName;
                $result = $notificationController->createNotification($message, $case_id);
                if (!$result) {
                    error_log('Failed to create notification for case ID: ' . $case_id);
                }
            } catch(Exception $e) {
                // Log error but don't fail the case creation
                error_log('Error creating notification: ' . $e->getMessage());
                error_log('Stack trace: ' . $e->getTraceAsString());
            }
            
            return $case_id;
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= DELETE =================
    public function deleteSocialCase($id) {
        $db = Config::getConnexion();
        $sql = "DELETE FROM social_case WHERE id_case = :i";
        try {
            $query = $db->prepare($sql);
            $query->execute(['i'=>$id]);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= UPDATE =================
    public function updateSocialCase($socialCase) {
        $db = Config::getConnexion();
        $sql = "UPDATE social_case SET 
                    name           = :n,
                    phone          = :p,
                    email          = :e,
                    description    = :d,
                    location       = :l,
                    submited_date = :idate,
                    updated_date   = :udate,
                    status         = :s,
                    id_category       = :c,
                    id_association = :ida
                WHERE id_case = :i";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'n'     => $socialCase->getName(),
                'p'     => $socialCase->getPhone(),
                'e'     => $socialCase->getEmail(),
                'd'     => $socialCase->getDescription(),
                'l'     => $socialCase->getLocation(),
                'idate' => $socialCase->getsubmitedDate(),
                'udate' => $socialCase->getUpdatedDate(),
                's'     => $socialCase->getStatus(),
                'c'     => $socialCase->getid_category(),
                'ida'   => $socialCase->getIdAssociation(),
                'i'     => $socialCase->getIdCase()
            ]);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= GET BY ID =================
    public function getSocialCaseById($id) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM social_case WHERE id_case = :i";
        try {
            $query = $db->prepare($sql);
            $query->execute(['i'=>$id]);
            return $query->fetch();
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    public function getAllCategories() {
        $db = Config::getConnexion();
        // Only return categories 1-4 for social case
        $sql = "SELECT id_category, name FROM category WHERE id_category IN (1, 2, 3, 4) ORDER BY id_category";
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    public function getAllAssociations() {
        $db = Config::getConnexion();
        // Only return associations with categories 1-4 for social case
        $sql = "SELECT id_association, name, phone, location, email, availabelity, id_category, rating FROM association WHERE id_category IN (1, 2, 3, 4)";
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    public function getAssociationsByCategory($categoryId) {
        $db = Config::getConnexion();
        // Only allow categories 1-4 for social case
        if (!in_array($categoryId, [1, 2, 3, 4])) {
            return [];
        }
        $sql = "SELECT id_association, name, phone, location, email, availabelity, id_category, rating FROM association WHERE id_category = :category_id";
        try {
            $query = $db->prepare($sql);
            $query->execute(['category_id' => $categoryId]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= ADD ASSOCIATION =================
    public function addAssociation($association) {
        $db = Config::getConnexion();
        $sql = "INSERT INTO association (name, phone, location, email, availabelity, id_category) VALUES (:name, :phone, :location, :email, :availabelity, :id_category)";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'name' => $association->getName(),
                'phone' => $association->getPhone(),
                'location' => $association->getLocation(),
                'email' => $association->getEmail(),
                'availabelity' => $association->getAvailabelity(),
                'id_category' => $association->getIdCategory()
            ]);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= DELETE ASSOCIATION =================
    public function deleteAssociation($id) {
        $db = Config::getConnexion();
        $sql = "DELETE FROM association WHERE id_association = :id";
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= UPDATE ASSOCIATION =================
    public function updateAssociation($association) {
        $db = Config::getConnexion();
        $sql = "UPDATE association SET name = :name, phone = :phone, location = :location, email = :email, availabelity = :availabelity, id_category = :id_category WHERE id_association = :id_association";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'name' => $association->getName(),
                'phone' => $association->getPhone(),
                'location' => $association->getLocation(),
                'email' => $association->getEmail(),
                'availabelity' => $association->getAvailabelity(),
                'id_category' => $association->getIdCategory(),
                'id_association' => $association->getIdAssociation()
            ]);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // ================= GET ASSOCIATION BY ID =================
    public function getAssociationById($id) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM association WHERE id_association = :id";
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    public function getFilteredAssociations($search = '', $category_id = null, $availabelity = null) {
        $db = Config::getConnexion();
        // Only return associations with categories 1-4 for social case
        $sql = "SELECT id_association, name, phone, location, email, availabelity, id_category, rating FROM association WHERE id_category IN (1, 2, 3, 4)";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name LIKE :search OR location LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if ($category_id !== null && $category_id !== '' && in_array($category_id, [1, 2, 3, 4])) {
            $sql .= " AND id_category = :category_id";
            $params[':category_id'] = $category_id;
        }

        if ($availabelity !== null && ($availabelity === '0' || $availabelity === '1')) {
            $sql .= " AND availabelity = :availabelity";
            $params[':availabelity'] = (int)$availabelity;
        }

        try {
            $query = $db->prepare($sql);
            $query->execute($params);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }
}