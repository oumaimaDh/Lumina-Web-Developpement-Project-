<?php
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\config.php';
require_once 'C:\xampp\htdocs\project_lumina - tache123+metier avancé\Modelj\Offer.php';

class OfferController {
    
    // Récupérer toutes les offres
    public function getOffers() {
        $conn = Config::getConnexion();
        $sql = "SELECT o.*, a.name as association_name 
                FROM offers o 
                LEFT JOIN associations a ON o.association_id = a.id 
                ORDER BY o.created_at DESC";

        try {
            $query = $conn->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Ajouter une offre
    public function addOffer($offerData) {
        $conn = Config::getConnexion();
        $sql = "INSERT INTO offers (association_id, title, location, salary_min, salary_max, expiration_date, description, contract_types, skills, status) 
                VALUES (:association_id, :title, :location, :salary_min, :salary_max, :expiration_date, :description, :contract_types, :skills, :status)";

        try {
            $query = $conn->prepare($sql);
            $query->execute([
                ':association_id' => $offerData['association_id'],
                ':title' => $offerData['title'],
                ':location' => $offerData['location'],
                ':salary_min' => $offerData['salary_min'],
                ':salary_max' => $offerData['salary_max'],
                ':expiration_date' => $offerData['expiration_date'],
                ':description' => $offerData['description'],
                ':contract_types' => $offerData['contract_types'],
                ':skills' => $offerData['skills'],
                ':status' => $offerData['status']
            ]);
            return true;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Supprimer une offre
    public function deleteOffer($id) {
        $conn = Config::getConnexion();
        $sql = "DELETE FROM offers WHERE id = :id";
        
        try {
            $query = $conn->prepare($sql);
            $query->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Récupérer une offre par ID
    public function getOfferById($id) {
        $conn = Config::getConnexion();
        $sql = "SELECT * FROM offers WHERE id = :id";
        
        try {
            $query = $conn->prepare($sql);
            $query->execute([':id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Mettre à jour une offre
    public function updateOffer($id, $title, $location, $salary_min, $salary_max, $expiration_date, $description, $contract_types, $skills, $status) {
        $conn = Config::getConnexion();
        $sql = "UPDATE offers SET title = :title, location = :location, salary_min = :salary_min, salary_max = :salary_max, 
                expiration_date = :expiration_date, description = :description, contract_types = :contract_types, 
                skills = :skills, status = :status WHERE id = :id";
        
        try {
            $query = $conn->prepare($sql);
            $query->execute([
                ':title' => $title,
                ':location' => $location,
                ':salary_min' => $salary_min,
                ':salary_max' => $salary_max,
                ':expiration_date' => $expiration_date,
                ':description' => $description,
                ':contract_types' => $contract_types,
                ':skills' => $skills,
                ':status' => $status,
                ':id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>