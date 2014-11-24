<?php


class Application_Model_OrganismeFormation {

    protected $id_formation;
    protected $id_organisme;
    protected $formateur;
    
    
       
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

    public function getId_formation() {
        return $this->id_formation;
    }

    public function setId_formation($id_formation) {
        $this->id_formation = $id_formation;
    }

    public function getId_organisme() {
        return $this->id_organisme;
    }

    public function setId_organisme($id_organisme) {
        $this->id_organisme = $id_organisme;
    }

    public function getFormateur() {
        return $this->formateur;
    }

    public function setFormateur($formateur) {
        $this->formateur = $formateur;
    }




}

