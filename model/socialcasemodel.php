<?php

class SocialCase {

    private ?int $id_case;
    private string $name;
    private string $phone;
    private string $email;
    
    private string $description;
    private string $location;
    private string $submited_date;
    private string $updated_date;
    private string $status;
    private int $id_category;
    private ?int $id_association;

    public function __construct(?int $id_case, string $name, string $phone, string $email, string $description, string $location, string $submited_date, string $updated_date, string $status, int $id_category, ?int $id_association){
        $this->id_case = $id_case;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        
        $this->description = $description;
        $this->location = $location;
        $this->submited_date = $submited_date;
        $this->updated_date = $updated_date;
        $this->status = $status;
        $this->id_category = $id_category;
        $this->id_association = $id_association;
    }

    // ========== GETTERS & SETTERS ==========

    public function getIdCase(): ?int { return $this->id_case; }
    public function setIdCase(?int $id_case): self { $this->id_case = $id_case; return $this; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getPhone(): string { return $this->phone; }
    public function setPhone(string $phone): self { $this->phone = $phone; return $this; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    

    public function getsubmitedDate(): string { return $this->submited_date; }
    public function setsubmitedDate(string $submited_date): self { $this->submited_date = $submited_date; return $this; }

    public function getUpdatedDate(): string { return $this->updated_date; }
    public function setUpdatedDate(string $updated_date): self { $this->updated_date = $updated_date; return $this; }

    public function getid_category(): int { return $this->id_category; }
    public function setid_category(int $id_category): self { $this->id_category = $id_category; return $this; }

    public function getIdAssociation(): ?int { return $this->id_association; }
    public function setIdAssociation(?int $id_association): self { $this->id_association = $id_association; return $this; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getLocation(): string { return $this->location; }
    public function setLocation(string $location): self { $this->location = $location; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): self { $this->status = $status; return $this; }

    public function getCreatedAt(): string { return $this->created_at; }
    public function setCreatedAt(string $created_at): self { $this->created_at = $created_at; return $this; }

    // ========== CRUD METHODS ========== 

    public function create(): void {
        $pdo = config::getConnexion();
        $query = "INSERT INTO social_case (name, phone, email,  description, location, submited_date, updated_date, status, id_category, id_association) VALUES (:name, :phone, :email,  :description, :location, :submited_date, :updated_date, :status, :id_category, :id_association)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            
            'description' => $this->description,
            'location' => $this->location,
            'submited_date' => $this->submited_date,
            'updated_date' => $this->updated_date,
            'status' => $this->status,
            'id_category' => $this->id_category,
            'id_association' => $this->id_association
        ]);
    }

    public static function getAll(): array {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM social_case";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(int $id_case): ?SocialCase {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM social_case WHERE id_case = :id_case";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_case' => $id_case]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        return new SocialCase(
            $data['id_case'],
            $data['name'],
            $data['phone'],
            $data['email'],
           
            $data['description'],
            $data['location'],
            $data['submited_date'],
            $data['updated_date'],
            $data['status'],
            $data['id_category'],
            $data['id_association'],
        );
    }

    public function update(): void {
        $pdo = config::getConnexion();
        $query = "UPDATE social_case SET name = :name, phone = :phone, email = :email, description = :description, location = :location, submited_date = :submited_date, updated_date = :updated_date, status = :status, id_category = :id_category, id_association = :id_association WHERE id_case = :id_case";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
          
            'description' => $this->description,
            'location' => $this->location,
            'submited_date' => $this->submited_date,
            'updated_date' => $this->updated_date,
            'status' => $this->status,
            'id_category' => $this->id_category,
            'id_association' => $this->id_association,
            'id_case' => $this->id_case
        ]);
    }

    public static function delete(int $id_case): void {
        $pdo = config::getConnexion();
        $query = "DELETE FROM social_case WHERE id_case = :id_case";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_case' => $id_case]);
    }
}

class Association {
    private ?int $id_association;
    private string $name;
    private string $phone;
    private string $location;
    private string $email;
    private int $availabelity;
    private int $id_category;

    public function __construct(?int $id_association, string $name, string $phone, string $location, string $email, int $availabelity, int $id_category) {
        $this->id_association = $id_association;
        $this->name = $name;
        $this->phone = $phone;
        $this->location = $location;
        $this->email = $email;
        $this->availabelity = $availabelity;
        $this->id_category = $id_category;
    }

    // Getters
    public function getIdAssociation(): ?int { return $this->id_association; }
    public function getName(): string { return $this->name; }
    public function getPhone(): string { return $this->phone; }
    public function getLocation(): string { return $this->location; }
    public function getEmail(): string { return $this->email; }
    public function getAvailabelity(): int { return $this->availabelity; }
    public function getIdCategory(): int { return $this->id_category; }

    // Setters
    public function setIdAssociation(?int $id_association): self { $this->id_association = $id_association; return $this; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function setPhone(string $phone): self { $this->phone = $phone; return $this; }
    public function setLocation(string $location): self { $this->location = $location; return $this; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function setAvailabelity(int $availabelity): self { $this->availabelity = $availabelity; return $this; }
    public function setIdCategory(int $id_category): self { $this->id_category = $id_category; return $this; }

    // CRUD Methods
    public function create(): void {
        $pdo = config::getConnexion();
        $query = "INSERT INTO association (name, phone, location, email, availabelity, id_category) VALUES (:name, :phone, :location, :email, :availabelity, :id_category)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $this->name,
            'phone' => $this->phone,
            'location' => $this->location,
            'email' => $this->email,
            'availabelity' => $this->availabelity,
            'id_category' => $this->id_category
        ]);
    }

    public static function getAll(): array {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM association";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(int $id_association): ?Association {
        $pdo = config::getConnexion();
        $query = "SELECT * FROM association WHERE id_association = :id_association";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_association' => $id_association]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        return new Association(
            $data['id_association'],
            $data['name'],
            $data['phone'],
            $data['location'],
            $data['email'],
            $data['availabelity'],
            $data['id_category']
        );
    }

    public function update(): void {
        $pdo = config::getConnexion();
        $query = "UPDATE association SET name = :name, phone = :phone, location = :location, email = :email, availabelity = :availabelity, id_category = :id_category WHERE id_association = :id_association";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $this->name,
            'phone' => $this->phone,
            'location' => $this->location,
            'email' => $this->email,
            'availabelity' => $this->availabelity,
            'id_category' => $this->id_category,
            'id_association' => $this->id_association
        ]);
    }

    public static function delete(int $id_association): void {
        $pdo = config::getConnexion();
        $query = "DELETE FROM association WHERE id_association = :id_association";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_association' => $id_association]);
    }
}
?>