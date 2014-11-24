<?php


class Application_Model_Formation {

    protected $id;
    protected $intitule;
    protected $objectif;
    protected $direction;
    protected $enregistre_le;
    protected $enregistre_par;
    protected $id_direction;
    protected $statut;
    protected $type_formateur;
    protected $id_nature;
    protected $id_domaine;
    protected $id_type;
    protected $id_pays;
    protected $date_debut; // date de debut de la session
    
    
       
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

    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }
    
    public function setIntitule($intitule) {
        $this->intitule = $intitule;
    }
    public function getIntitule() {
        return $this->intitule;
    }
    
    public function getId_direction() {
        return $this->id_direction;
    }

    public function setId_direction($direction_id) {
        $this->id_direction = $direction_id;
    }
    
    public function getType_formateur() {
        return $this->type_formateur;
    }

    public function setType_formateur($type_formateur) {
        $this->type_formateur = $type_formateur;
    }

    public function getId_domaine() {
        return $this->id_domaine;
    }

    public function setId_domaine($id_domaine) {
        $this->id_domaine = $id_domaine;
    }
 
 
   
    
    /*** CHAMPS SPECIFIQUE ********/
    public function getBudget() {
        $model = new Application_Model_BudgetMapper();
        $result = $model->getEntiteBudgetEncours($this->id_budget);
        return $result->current();
    }
   
    public function getDirection() {
        $model = new Application_Model_DbTable_Direction();
        $result = $model->find($this->id_direction);
        return $result->current();
    }
   
    public function getPole($id_pole) {
        $model = new Application_Model_DbTable_Pole();
        $result = $model->find($id_pole);
        return $result->current();
    }
    
    public function getTypeFormation() {
        $model = new Application_Model_DbTable_TypesFormation();
        $result = $model->find($this->id_type);
        return $result->current();
    }
    
    public function getNatureFormation() {
        $model = new Application_Model_DbTable_NaturesFormation();
        $result = $model->find($this->id_nature);
        return $result->current();
    }
    
    public function getDomaineFormation() {
        $model = new Application_Model_DbTable_DomainesFormation();
        $result = $model->find($this->id_domaine);
        return $result->current();
    }
    
    public function getNombreSession() {
        $model = new Application_Model_DbTable_Session();
        $rowCount = $model->fetchAll("id_formation = ".$this->id)->count();
        return $rowCount;
    }
    
    # Nombre d'auditeurs de toutes les sessions d'une formation
    public function getTotalNombreAuditeurPrevu() {
        $model = new Application_Model_FormationMapper();
        $rowCount = $model->getTotalAuditeur($this->id)->current();
        return $rowCount['nbAuditeurTotal'];
    }
            
    public function getCoutTotal() {
        $coutDirectMapper = new Application_Model_FormationCoutDirectMapper();
        $result = $coutDirectMapper->getTotalCoutDirect($this->id)->current();
        $coutIndirectMapper = new Application_Model_SessionCoutIndirectMapper();
        $result1 = $coutIndirectMapper->getTotalCoutIndirect($this->id)->current();
        return $result['coutDirectTotal']+$result1['coutIndirectTotal'];
    }
    
    /*
    public function setDirection($direction) {
        $this->direction = $direction;
    }
    
    public function getDirection() {
        return $this->direction;
    }*/
    
    

    public function getId_pays() {
        return $this->id_pays;
    }

    public function setId_pays($id_pays) {
        $this->id_pays = $id_pays;
    }

    public function getDate_debut() {
        return $this->date_debut;
    }

    public function setDate_debut($date_debut) {
        $this->date_debut = $date_debut;
    }
    
    public function getId_nature() {
        return $this->id_nature;
    }
    public function setId_nature($nature_id) {
        $this->id_nature = $nature_id;
    }
    
    public function getId_type() {
        return $this->id_type;
    }
    public function setId_type($type_id) {
        $this->id_type = $type_id;
    }
    
    
    public function getObjectif() {
        return $this->objectif;
    }

    public function setObjectif($objectif) {
        $this->objectif = $objectif;
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

    public function getStatut() {
        return $this->statut;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    
    /*** Liste specifique ****/
    public function getListByPole() {
        $model = new Application_Model_PoleMapper();
        $result = $model->fetchAll(' id = '.$this->id_pole);
        return $result[0];
    }
    public function getListByDirections() {
        $model = new Application_Model_DirectionMapper();
        $result = $model->fetchAll(' id = '.$this->id_direction);
        return $result[0];
    }
    
    public function getListByType() {
        $model = new Application_Model_TypeFormationMapper();
        $result = $model->fetchAll(' id = '.$this->id_type);
        return $result[0];
    }
    
    public function getListByNature() {
        $model = new Application_Model_NatureFormationMapper();
        $result = $model->fetchAll(' id = '.$this->id_nature);
        return $result[0];
    }
   
        
    
    /*
    public function getOrganismes() {
        $model = new Application_Model_OrganismeMapper();
        $result = $model->fetchAll(' id = '.$this->id_organisme);
        return $result[0];
    }
    
    
    public function setOrganisme($organisme) {
        $this->organisme = $organisme;
    }
    public function getOrganisme() {
        return $this->organisme;
    }*/
    
    
    
   

}

