<?php


class Application_Model_AuditeurMapper {

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * @return Application_Model_DbTable_formation
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Auditeur');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_formation $formation
     * @return type
     */
    public function save(Application_Model_Auditeur $obj) {
        $data = array(
            'id' => $obj->getId(),
            'statut' => $obj->getStatut(),
            'id_session' => $obj->getId_session(),
            'id_personnel' => $obj->getId_personnel()
          );
        if (null === ($id = $obj->getId())) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return $id;
        }
    }

    /**
     *
     * @param type $id
     * @param Application_Model_Auditeur $obj
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $obj = new Application_Model_Auditeur();
        $obj->setId($row->id);
        $obj->setId_session($row->id_session);
        $obj->setId_personnel($row->id_personnel);
        $obj->setStatut($row->statut);                
         return $obj;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_Auditeur
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $obj = new Application_Model_Auditeur();
            $obj->setId($row->id);
            $obj->setId_session($row->id_session);
            $obj->setId_personnel($row->id_personnel);
            $obj->setStatut($row->statut);                
            $entries[] = $obj;
        }
        return $entries;
    }

    public function fetchAll($query = NULL) {
       
        $table = $this->getDbTable();
        $select = $table->select();
        if ($query === NULL) {
            $resultSet = $table()->fetchAll();
        } 
        else {
            $select->setIntegrityCheck(false)
                    ->from(array('a' => 'auditeur'), array('*'))
                    ->join(array('s' => 'session'), 'a.id_session = s.id', array('s.id as id_session', 's.date_debut'))
                    ->join(array('f' => 'formation'), 's.id_formation = f.id', array('f.id as id_formation'))
                    ->join(array('p' => 'personnel'), 'p.id = a.id_personnel', array("p.nom", "p.prenoms", "p.matricule"
                                                                                     ))
                    ->join(array('e' => 'entite'), 'p.id_entite = e.id', array('e.entite'))
                    ->join(array('d' => 'direction'), 'p.id_direction = d.id', array('d.direction'))
                    ->where($query)
                    ->order('p.nom ASC')
                    ;
            $resultSet = $table->fetchAll($select);
            return $resultSet;
        }
        return $this->processResultSet($resultSet);
    }
    
    
    public function count($IdFormation) {
       
        $table = $this->getDbTable();
        $select = $table->select();
        $select->setIntegrityCheck(false)
                    ->from(array('a' => 'auditeur'), array('count(DISTINCT a.id_personnel) as nbAuditeur'))
                    ->join(array('s' => 'session'), 'a.id_session = s.id','')
                    ->join(array('f' => 'formation'), 's.id_formation = f.id','')
                    ->where('f.id = ? ',$IdFormation)
                    ;
        return $table->fetchAll($select);
    }
    

    public function fetchByIds(array $ids) {
        if (count($ids) == 0) {
            return array();
        }
        $in = "id IN(" . implode(", ", $ids) . ")";
        $select = $this->getDbTable()
                ->select()
                ->from($this->getDbTable())
                ->where($in);
        $resultSet = $this->getDbTable()->fetchAll($select);
        return $this->processResultSet($resultSet);
    }

    
    
     /***
     * DASHBOARD ETAT DES PARTCIPANTS SUR FORMATION PAR MOIS 
     */
    public function getEtatParticipant($where='') {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
                    ->from(array('a' => 'auditeur'), array('a.statut'))
                    ->join(array('s' => 'session'), 'a.id_session = s.id AND s.status = 1', array())
                    ->join(array('f' => 'formation'), 's.id_formation = f.id', array())
                    ->join(array('d' => 'domaines_formation'), 'f.id_domaine = d.id', array('d.id as id_domaine','d.domaine_formation'))
                    ->join(array('p' => 'personnel'), 'p.id = a.id_personnel AND p.type_salarie=1', array('p.id_categorie','p.genre'))
                    ->join(array('e' => 'entite'), 'p.id_entite = e.id', array('e.entite','e.id as id_entite'))
                    ;
        if($where)
        {    
           $date = str_replace('-','',$where['date']);
           $where = (" EXTRACT(YEAR_MONTH FROM s.date_debut) = '".$date."'");  
           $select->where($where);
          
        } 
        
        //echo $select->__toString();
        return $this->getDbTable()->fetchAll($select);
    }
    
    
    public function getActifForYtd($where) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
                ->from(array('a' => 'auditeur'), array('COUNT(DISTINCT a.id_personnel) as salarieForme'))
                ->join(array('s' => 'session'), 'a.id_session = s.id AND a.statut = 1 AND s.status = 1', array())
                ->join(array('p' => 'personnel'), 'p.id = a.id_personnel', array())
                ->join(array('e' => 'entite'), 'p.id_entite = e.id', array('e.entite','e.id as id_entite'))
                ->group('e.id');
      
        $dateD = $where['year'].'-'.'01-01';
        $dateF = $where['date'].'-31';
        $cond = (" s.date_debut >= '".$dateD."' AND s.date_debut <= '".$dateF."' "); 
        $select->where($cond);
    
        return $this->getDbTable()->fetchAll($select);
    }
    
    
    
     /***
     * REPARTITION DES PARTCIPANTS PAR CATEGORIE SOCIOPROFESSIONNELLE 
     */
    public function getRepartitionParticipant($where='') {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
                    ->from(array('a' => 'auditeur'), array())
                    ->join(array('s' => 'session'), 'a.id_session = s.id AND s.status = 1 AND a.statut = 1', array())
                    ->join(array('f' => 'formation'), 's.id_formation = f.id', array())
                    ->join(array('d' => 'domaines_formation'), 'f.id_domaine = d.id', array('d.id as id_domaine','d.domaine_formation'))
                    ->join(array('p' => 'personnel'), 'p.id = a.id_personnel', array('p.genre','p.id_categorie'))
                    ->join(array('e' => 'entite'), 'p.id_entite = e.id', array('e.entite','e.id as id_entite'))
                    ->group('d.id')->group('e.id')->group('p.genre');
        if($where)
        {    
           $date = str_replace('-','',$where['date']);
           $where = (" EXTRACT(YEAR_MONTH FROM s.date_debut) = '".$date."'");  
           $select->where($where);
           //$select->group('periode');
          
        } 
        
        //echo $select->__toString();
        return $this->getDbTable()->fetchAll($select);
    }
}

