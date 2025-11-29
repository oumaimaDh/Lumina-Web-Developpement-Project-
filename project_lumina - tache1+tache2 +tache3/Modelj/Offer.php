<?php
class Offer {
    private $id;
    private $association_id;
    private $title;
    private $location;
    private $salary_min;
    private $salary_max;
    private $expiration_date;
    private $description;
    private $contract_types;
    private $skills;
    private $status;

    public function __construct($association_id, $title, $location, $salary_min, $salary_max, $expiration_date, $description, $contract_types, $skills, $status = 'active') {
        $this->association_id = $association_id;
        $this->title = $title;
        $this->location = $location;
        $this->salary_min = $salary_min;
        $this->salary_max = $salary_max;
        $this->expiration_date = $expiration_date;
        $this->description = $description;
        $this->contract_types = $contract_types;
        $this->skills = $skills;
        $this->status = $status;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getAssociationId() { return $this->association_id; }
    public function getTitle() { return $this->title; }
    public function getLocation() { return $this->location; }
    public function getSalaryMin() { return $this->salary_min; }
    public function getSalaryMax() { return $this->salary_max; }
    public function getExpirationDate() { return $this->expiration_date; }
    public function getDescription() { return $this->description; }
    public function getContractTypes() { return $this->contract_types; }
    public function getSkills() { return $this->skills; }
    public function getStatus() { return $this->status; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setAssociationId($association_id) { $this->association_id = $association_id; }
    public function setTitle($title) { $this->title = $title; }
    public function setLocation($location) { $this->location = $location; }
    public function setSalaryMin($salary_min) { $this->salary_min = $salary_min; }
    public function setSalaryMax($salary_max) { $this->salary_max = $salary_max; }
    public function setExpirationDate($expiration_date) { $this->expiration_date = $expiration_date; }
    public function setDescription($description) { $this->description = $description; }
    public function setContractTypes($contract_types) { $this->contract_types = $contract_types; }
    public function setSkills($skills) { $this->skills = $skills; }
    public function setStatus($status) { $this->status = $status; }
}
?>