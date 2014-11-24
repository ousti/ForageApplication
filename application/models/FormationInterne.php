<?php


class Application_Model_FormationInterne {

    protected $id_session;
    protected $id_personnel;
    protected $montant_horaire;
    
    
       
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

    public function getId_session() {
        return $this->id_session;
    }

    public function setId_session($id_session) {
        $this->id_session = $id_session;
    }

    
    
    public function getId_personnel() {
        return $this->id_personnel;
    }

    public function setId_personnel($id_personnel) {
        $this->id_personnel = $id_personnel;
    }

    public function getMontant_horaire() {
        return $this->montant_horaire;
    }

    public function setMontant_horaire($montant_horaire) {
        $this->montant_horaire = $montant_horaire;
    }






}

