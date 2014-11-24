<?php


class Application_Model_SessionFormation {

    protected $id;
    protected $id_formation;
    protected $localisation;
    protected $date_debut;
    protected $date_fin;
    protected $nombre_jour;
    protected $nombre_heure_jour;
    protected $enregistre_le;
    protected $enregistre_par;
    protected $status;
    protected $auditeur_prevu;
   

    
    
       
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

    public function getLocalisation() {
        return $this->localisation;
    }

    public function setLocalisation($localisation) {
        $this->localisation = $localisation;
    }

    public function getDate_debut() {
        return $this->date_debut;
    }

    public function setDate_debut($date_debut) {
        $this->date_debut = $date_debut;
    }

    public function getDate_fin() {
        return $this->date_fin;
    }

    public function setDate_fin($date_fin) {
        $this->date_fin = $date_fin;
    }
    
    public function getAuditeur_prevu() {
        return $this->auditeur_prevu;
    }

    public function setAuditeur_prevu($auditeur_prevu) {
        $this->auditeur_prevu = $auditeur_prevu;
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
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
  
    public function getNombre_jour() {
        return $this->nombre_jour;
    }

    public function setNombre_jour($nombre_jour) {
        $this->nombre_jour = $nombre_jour;
    }

    public function getNombre_heure_jour() {
        return $this->nombre_heure_jour;
    }

    public function setNombre_heure_jour($nombre_heure_jour) {
        $this->nombre_heure_jour = $nombre_heure_jour;
    }



    
    

}

