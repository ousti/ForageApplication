<?php


class Application_Model_FormationCoutDirect {

    protected $id;
    protected $id_session;
    protected $id_budget;
    protected $montant;
    protected $numero_da;
    protected $numero_commande;
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

    public function getId_session() {
        return $this->id_session;
    }

    public function setId_session($id_session) {
        $this->id_session = $id_session;
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

    public function getNumero_da() {
        return $this->numero_da;
    }

    public function setNumero_da($numero_da) {
        $this->numero_da = $numero_da;
    }

    public function getNumero_commande() {
        return $this->numero_commande;
    }

    public function setNumero_commande($numero_commande) {
        $this->numero_commande = $numero_commande;
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

