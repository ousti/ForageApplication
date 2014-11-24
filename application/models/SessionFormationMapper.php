<?php


class Application_Model_SessionFormationMapper {

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
     * @return Application_Model_DbTable_Session
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Session');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_formation $obj
     * @return type
     */
    public function save(Application_Model_SessionFormation $obj) {
        $data = array(
            'id' => $obj->getId(),
            'date_debut' => $obj->getDate_debut(),
            'date_fin' => $obj->getDate_fin(),
            'localisation' => $obj->getLocalisation(),
            'status' => $obj->getStatus(),
            'id_formation' => $obj->getId_formation(),
            'nombre_heure_jour'=> $obj->getNombre_heure_jour(),
            'auditeur_prevu' => $obj->getAuditeur_prevu(),
            'nombre_jour'=> $obj->getNombre_jour()
        );
        if (null === ($id = $obj->getId()) or $id == '') {
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
     * @param Application_Model_formation $obj
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $obj = new Application_Model_SessionFormation();
        $obj->setId($row->id);
        $obj->setId_formation($row->id_formation);
        $obj->setDate_debut($row->date_debut);
        $obj->setDate_fin($row->date_fin);
        $obj->setStatus($row->status);
        $obj->setNombre_jour($row->nombre_jour);
        $obj->setLocalisation($row->localisation);
        $obj->setNombre_heure_jour($row->nombre_heure_jour);
        $obj->setAuditeur_prevu($row->auditeur_prevu);
        
         return $obj;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_formation
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $obj = new Application_Model_SessionFormation();
            $obj->setId($row->id);
            $obj->setId_formation($row->id_formation);
            $obj->setDate_debut($row->date_debut);
            $obj->setDate_fin($row->date_fin);
            $obj->setStatus($row->status);
            $obj->setNombre_heure_jour($row->nombre_heure_jour);
            $obj->setNombre_jour($row->nombre_jour);
            $obj->setLocalisation($row->localisation);
            $obj->setAuditeur_prevu($row->auditeur_prevu);
            $entries[] = $obj;
        }
        return $entries;
    }

    public function fetchAll($query = NULL) {
       
        $table = $this->getDbTable();
        $select = $table->select();
        $select->setIntegrityCheck(false)
                    ->from(array('s' => 'session'), array('*'))
                    ->join(array('f' => 'formation'), 's.id_formation = f.id', array('d.direction'))
                   ;
        $resultSet = $table->fetchAll($select);
        
        return $this->processResultSet($resultSet);
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
        $subQuery = $this->getDbTable()->select()->setIntegrityCheck(false)
                            ->from(array('a'=>'auditeur'), array('a.id_session',
                                   new Zend_Db_Expr("COUNT( a.id_personnel ) AS nbPresentParSession")))
                            ->where('a.statut = ?',1)
                            ->group('a.id_session')
                          ; 
        $select->setIntegrityCheck(false)
               ->from(array('s' => 'session'), array('SUM(s.auditeur_prevu) as nbAuditeurPrevu','EXTRACT(YEAR_MONTH FROM s.date_debut) as periode'))
               ->joinLeft(array('au' => $subQuery),' au.id_session = s.id ', array('SUM(au.nbPresentParSession) AS nbAuditeurPresent'))
               ->join(array('f' => 'formation'), 's.id_formation = f.id ', array())
               ->join(array('d' => 'domaines_formation'), 'd.id = f.id_domaine', array('d.domaine_formation'))
               //->join(array('p' => 'personnel'), 'a.id_personnel = p.id', array())
               //->join(array('e' => 'entite'), 'p.id_entite = e.id AND e.inputable_budget = 1', array('e.entite'))
               ->group('d.id')
               //->group('e.id')
                ;
        
        if($where)
        {    
           $date = str_replace('-','',$where['date']);
           $where = (" EXTRACT(YEAR_MONTH FROM s.date_debut) = '".$date."'");  
           $select->where($where);
           $select->group('periode');
        } 
        
        echo $select->__toString();
        return $this->getDbTable()->fetchAll($select);
    }
    
}

