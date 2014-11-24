<?php


class Application_Model_Personnel {

    protected $id;
    protected $id_direction;
    protected $matricule;
    protected $nom;
    protected $prenoms;
    protected $genre;
    protected $date_naissance;
    protected $nationalite;
    protected $fonction;
    protected $id_categorie;
    protected $date_embauche;
    protected $id_entite;
    protected $cnps;
    protected $superieur_hierarchique;
    protected $type_salarie;


    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid content property');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid content property');
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId_direction() {
        return $this->id_direction;
    }

    public function setId_direction($id_direction) {
        $this->id_direction = $id_direction;
    }

    
    public function getMatricule() {
        return $this->matricule;
    }

    public function setMatricule($matricule) {
        $this->matricule = $matricule;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getPrenoms() {
        return $this->prenoms;
    }

    public function setPrenoms($prenoms) {
        $this->prenoms = $prenoms;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function setGenre($genre) {
        $this->genre = $genre;
    }

    public function getDate_naissance() {
        return $this->date_naissance;
    }

    public function setDate_naissance($date_naissance) {
        $this->date_naissance = $date_naissance;
    }

    public function getNationalite() {
        return $this->nationalite;
    }

    public function setNationalite($nationalite) {
        $this->nationalite = $nationalite;
    }

    public function getFonction() {
        return $this->fonction;
    }

    public function setFonction($fonction) {
        $this->fonction = $fonction;
    }

    public function getId_categorie() {
        return $this->id_categorie;
    }

    public function setId_categorie($id_categorie) {
        $this->id_categorie = $id_categorie;
    }

    
    public function getDate_embauche() {
        return $this->date_embauche;
    }

    public function setDate_embauche($date_embauche) {
        $this->date_embauche = $date_embauche;
    }

    public function getId_entite() {
        return $this->id_entite;
    }

    public function setId_entite($id_entite) {
        $this->id_entite = $id_entite;
    }

    
    public function getCnps() {
        return $this->cnps;
    }

    public function setCnps($cnps) {
        $this->cnps = $cnps;
    }

    public function getSuperieur_hierarchique() {
        return $this->superieur_hierarchique;
    }

    public function setSuperieur_hierarchique($superieur_hierarchique) {
        $this->superieur_hierarchique = $superieur_hierarchique;
    }

    public function getType_salarie() {
        return $this->type_salarie;
    }

    public function setType_salarie($type_salarie) {
        $this->type_salarie = $type_salarie;
    }





}

