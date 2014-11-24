<?php


class Application_Model_Organisme {

    protected $id;
    protected $organisme;
    protected $tel1;
    protected $tel2;
    protected $fax;
    protected $email;
    protected $localisation;
    protected $nationalite;
    protected $nbFormationDone;
       
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

    public function getOrganisme() {
        return $this->organisme;
    }

    public function setOrganisme($organisme) {
        $this->organisme = $organisme;
    }
    
    public function getTel1() {
        return $this->tel1;
    }

    public function setTel1($tel1) {
        $this->tel1 = $tel1;
    }

    public function getTel2() {
        return $this->tel2;
    }

    public function setTel2($tel2) {
        $this->tel2 = $tel2;
    }

    public function getFax() {
        return $this->fax;
    }

    public function setFax($fax) {
        $this->fax = $fax;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getLocalisation() {
        return $this->localisation;
    }

    public function setLocalisation($localisation) {
        $this->localisation = $localisation;
    }

    public function getNationalite() {
        return $this->nationalite;
    }

    public function setNationalite($nationalite) {
        $this->nationalite = $nationalite;
    }

    public function getNbFormationDone() {
        return $this->nbFormationDone;
    }

    public function setNbFormationDone($nbFormationDone) {
        $this->nbFormationDone = $nbFormationDone;
    }




}

