<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Association.php';

class AssociationController {
    
    // Map category IDs to category names for job department (categories 5-9)
    private function getCategoryNameFromId($id_category) {
        $categoryMap = [
            5 => 'health',
            6 => 'education',
            7 => 'commerce',
            8 => 'construction',
            9 => 'restaurant'
        ];
        return $categoryMap[$id_category] ?? 'other';
    }

    // Récupérer toutes les associations (only job department categories 5-9)
    public function getAssociations() {
        $conn = Config::getConnexion();
        $sql = "SELECT a.*, c.name as category_name,
                CASE 
                    WHEN a.id_category = 5 THEN 'health'
                    WHEN a.id_category = 6 THEN 'education'
                    WHEN a.id_category = 7 THEN 'commerce'
                    WHEN a.id_category = 8 THEN 'construction'
                    WHEN a.id_category = 9 THEN 'restaurant'
                    ELSE 'other'
                END as category
                FROM association a
                LEFT JOIN category c ON a.id_category = c.id_category
                WHERE a.id_category IN (5, 6, 7, 8, 9)
                ORDER BY a.name";

        try {
            $query = $conn->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Add active_offers count
            foreach ($results as &$assoc) {
                $assoc['id'] = $assoc['id_association'];
                $assoc['active_offers'] = $this->getActiveOffersCount($assoc['id_association']);
            }
            
            return $results;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Récupérer les associations par catégorie (job department only)
    public function getAssociationsByCategory($category) {
        $conn = Config::getConnexion();
        // Map category name to id
        $categoryIdMap = [
            'health' => 5,
            'education' => 6,
            'commerce' => 7,
            'construction' => 8,
            'restaurant' => 9
        ];
        
        $categoryId = $categoryIdMap[$category] ?? null;
        if (!$categoryId) {
            return [];
        }
        
        $sql = "SELECT a.*, c.name as category_name,
                CASE 
                    WHEN a.id_category = 5 THEN 'health'
                    WHEN a.id_category = 6 THEN 'education'
                    WHEN a.id_category = 7 THEN 'commerce'
                    WHEN a.id_category = 8 THEN 'construction'
                    WHEN a.id_category = 9 THEN 'restaurant'
                    ELSE 'other'
                END as category
                FROM association a
                LEFT JOIN category c ON a.id_category = c.id_category
                WHERE a.id_category = :category_id
                ORDER BY a.name";

        try {
            $query = $conn->prepare($sql);
            $query->execute([':category_id' => $categoryId]);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Add active_offers count
            foreach ($results as &$assoc) {
                $assoc['id'] = $assoc['id_association'];
                $assoc['active_offers'] = $this->getActiveOffersCount($assoc['id_association']);
            }
            
            return $results;
        } catch (Exception $e) {
            return [];
        }
    }

    // Récupérer une association par ID
    public function getAssociationById($id) {
        $conn = Config::getConnexion();
        $sql = "SELECT a.*, c.name as category_name,
                CASE 
                    WHEN a.id_category = 5 THEN 'health'
                    WHEN a.id_category = 6 THEN 'education'
                    WHEN a.id_category = 7 THEN 'commerce'
                    WHEN a.id_category = 8 THEN 'construction'
                    WHEN a.id_category = 9 THEN 'restaurant'
                    ELSE 'other'
                END as category
                FROM association a
                LEFT JOIN category c ON a.id_category = c.id_category
                WHERE a.id_association = :id";
        
        try {
            $query = $conn->prepare($sql);
            $query->execute([':id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $result['id'] = $result['id_association'];
                $result['active_offers'] = $this->getActiveOffersCount($id);
            }
            return $result;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Get active offers count for an association
    private function getActiveOffersCount($association_id) {
        $conn = Config::getConnexion();
        $sql = "SELECT COUNT(*) as count FROM offers WHERE id_association = :id AND status = 'active'";
        try {
            $query = $conn->prepare($sql);
            $query->execute([':id' => $association_id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    // Récupérer les associations avec le nombre d'offres actives (job department only)
    public function getAssociationsWithOfferCount() {
        $conn = Config::getConnexion();
        $sql = "SELECT a.*, c.name as category_name,
                CASE 
                    WHEN a.id_category = 5 THEN 'health'
                    WHEN a.id_category = 6 THEN 'education'
                    WHEN a.id_category = 7 THEN 'commerce'
                    WHEN a.id_category = 8 THEN 'construction'
                    WHEN a.id_category = 9 THEN 'restaurant'
                    ELSE 'other'
                END as category,
                COUNT(o.id) as active_offers
                FROM association a
                LEFT JOIN category c ON a.id_category = c.id_category
                LEFT JOIN offers o ON a.id_association = o.id_association AND o.status = 'active'
                WHERE a.id_category IN (5, 6, 7, 8, 9)
                GROUP BY a.id_association
                ORDER BY a.name";

        try {
            $query = $conn->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Map id_association to id for compatibility
            foreach ($results as &$assoc) {
                $assoc['id'] = $assoc['id_association'];
            }
            
            return $results;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>