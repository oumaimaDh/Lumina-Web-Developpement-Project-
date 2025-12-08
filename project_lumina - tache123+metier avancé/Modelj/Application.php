<?php
class Application {
    private $id;
    private $offer_id;
    private $association_id;
    private $full_name;
    private $email;
    private $phone;
    private $profession;
    private $desired_salary;
    private $preferred_location;
    private $skills;
    private $experience;
    private $experience_level;
    private $cv_filename;
    private $cover_letter;
    private $status;
    private $applied_at;

    public function __construct($offer_id, $association_id, $full_name, $email, $phone, $profession, $desired_salary, $preferred_location, $skills, $experience, $experience_level, $cv_filename, $cover_letter = '', $status = 'submitted') {
        $this->offer_id = $offer_id;
        $this->association_id = $association_id;
        $this->full_name = $full_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->profession = $profession;
        $this->desired_salary = $desired_salary;
        $this->preferred_location = $preferred_location;
        $this->skills = $skills;
        $this->experience = $experience;
        $this->experience_level = $experience_level;
        $this->cv_filename = $cv_filename;
        $this->cover_letter = $cover_letter;
        $this->status = $status;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getOfferId() { return $this->offer_id; }
    public function getAssociationId() { return $this->association_id; }
    public function getFullName() { return $this->full_name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getProfession() { return $this->profession; }
    public function getDesiredSalary() { return $this->desired_salary; }
    public function getPreferredLocation() { return $this->preferred_location; }
    public function getSkills() { return $this->skills; }
    public function getExperience() { return $this->experience; }
    public function getExperienceLevel() { return $this->experience_level; }
    public function getCvFilename() { return $this->cv_filename; }
    public function getCoverLetter() { return $this->cover_letter; }
    public function getStatus() { return $this->status; }
    public function getAppliedAt() { return $this->applied_at; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setOfferId($offer_id) { $this->offer_id = $offer_id; }
    public function setAssociationId($association_id) { $this->association_id = $association_id; }
    public function setFullName($full_name) { $this->full_name = $full_name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setProfession($profession) { $this->profession = $profession; }
    public function setDesiredSalary($desired_salary) { $this->desired_salary = $desired_salary; }
    public function setPreferredLocation($preferred_location) { $this->preferred_location = $preferred_location; }
    public function setSkills($skills) { $this->skills = $skills; }
    public function setExperience($experience) { $this->experience = $experience; }
    public function setExperienceLevel($experience_level) { $this->experience_level = $experience_level; }
    public function setCvFilename($cv_filename) { $this->cv_filename = $cv_filename; }
    public function setCoverLetter($cover_letter) { $this->cover_letter = $cover_letter; }
    public function setStatus($status) { $this->status = $status; }
    public function setAppliedAt($applied_at) { $this->applied_at = $applied_at; }
}
?>