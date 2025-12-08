<?php
require_once 'C:\xampp\htdocs\project_lumina - tache1+tache2 +tache3\config.php';
require_once 'C:\xampp\htdocs\project_lumina - tache1+tache2 +tache3\Modelj\Association.php';

class AssociationController {
    
    // Récupérer toutes les associations
    public function getAssociations() {
        $conn = Config::getConnexion();
        $sql = "SELECT * FROM associations ORDER BY name";

        try {
            $query = $conn->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Récupérer les associations par catégorie
    public function getAssociationsByCategory($category) {
        $conn = Config::getConnexion();
        $sql = "SELECT * FROM associations WHERE category = :category ORDER BY name";

        try {
            $query = $conn->prepare($sql);
            $query->execute([':category' => $category]);
            return $query->fetchAll();
        } catch (Exception $e) {
            // Si pas de données, retourner tableau vide
            return [];
        }
    }

    // Récupérer une association par ID
    public function getAssociationById($id) {
        $conn = Config::getConnexion();
        $sql = "SELECT * FROM associations WHERE id = :id";
        
        try {
            $query = $conn->prepare($sql);
            $query->execute([':id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

// Récupérer les associations avec le nombre d'offres actives
public function getAssociationsWithOfferCount() {
    $conn = Config::getConnexion();
    $sql = "SELECT a.*, COUNT(o.id) as active_offers 
            FROM associations a 
            LEFT JOIN offers o ON a.id = o.association_id AND o.status = 'active'
            GROUP BY a.id 
            ORDER BY a.name";

    try {
        $query = $conn->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    } catch (Exception $e) {
        die('Erreur: ' . $e->getMessage());
    }
}
}
?>