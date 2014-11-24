<?php


class Application_Model_BonCommande {

    protected $id;
    protected $id_formation;
    protected $id_budget;
    protected $montant;
    protected $observation;
    protected $enregistre_le;
    protected $enregistre_par;    
    
       
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

    public function getId_formation() {
        return $this->id_formation;
    }

    public function setId_formation($id_formation) {
        $this->id_formation = $id_formation;
    }

    public function getId_budget() {
        return $this->id_budget;
    }

    public function setId_budget($id_budget) {
        $this->id_budget = $id_budget;
    }

    public function getMontant() {
        return $this->montant;
    }

    public function setMontant($montant) {
        $this->montant = $montant;
    }

    public function getObservation() {
        return $this->observation;
    }

    public function setObservation($observation) {
        $this->observation = $observation;
    }

    public function getEnregistre_le() {
        return $this->enregistre_le;
    }

    public function setEnregistre_le($enregistre_le) {
        $this->enregistre_le = $enregistre_le;
    }

    public function getEnregistre_par() {
        return $this->enregistre_par;
    }

    public function setEnregistre_par($enregistre_par) {
        $this->enregistre_par = $enregistre_par;
    }


    
    
   

}

