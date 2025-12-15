<?php
class Association {
    private $id;
    private $name;
    private $category;
    private $location;
    private $description;
    private $rating;
    private $active_offers;

    public function __construct($name, $category, $location, $description, $rating, $active_offers = 0) {
        $this->name = $name;
        $this->category = $category;
        $this->location = $location;
        $this->description = $description;
        $this->rating = $rating;
        $this->active_offers = $active_offers;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getCategory() { return $this->category; }
    public function getLocation() { return $this->location; }
    public function getDescription() { return $this->description; }
    public function getRating() { return $this->rating; }
    public function getActiveOffers() { return $this->active_offers; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setCategory($category) { $this->category = $category; }
    public function setLocation($location) { $this->location = $location; }
    public function setDescription($description) { $this->description = $description; }
    public function setRating($rating) { $this->rating = $rating; }
    public function setActiveOffers($active_offers) { $this->active_offers = $active_offers; }
}
?>