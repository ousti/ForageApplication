<?php


class Application_Model_SessionCoutIndirect {

    protected $id_session;
    protected $id_type_cout;
    protected $valeur;
    protected $bon_commande;
    protected $demande_achat;
    protected $id_budget;
    protected $observation;






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

        public function getId_type_cout() {
        return $this->id_type_cout;
    }

    public function setId_type_cout($id_type_cout) {
        $this->id_type_cout = $id_type_cout;
    }

    public function getValeur() {
        return $this->valeur;
    }

    public function setValeur($valeur) {
        $this->valeur = $valeur;
    }

    public function getBon_commande() {
        return $this->bon_commande;
    }

    public function setBon_commande($bon_commande) {
        $this->bon_commande = $bon_commande;
    }

    public function getDemande_achat() {
        return $this->demande_achat;
    }

    public function setDemande_achat($demande_achat) {
        $this->demande_achat = $demande_achat;
    }

    public function getId_budget() {
        return $this->id_budget;
    }

    public function setId_budget($id_budget) {
        $this->id_budget = $id_budget;
    }

    public function getObservation() {
        return $this->observation;
    }

    public function setObservation($observation) {
        $this->observation = $observation;
    }



}

