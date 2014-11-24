<?php


class Application_Model_FormationCoutDirectMapper {

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
     * @return Application_Model_DbTable_FormationCoutDirect
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_FormationCoutDirect');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_FormationCoutDirect $obj
     * @return type
     */
    public function save(Application_Model_FormationCoutDirect $obj) {
        $data = array(
            'id' => $obj->getId(),
            'id_budget' => $obj->getId_budget(),
            'id_session' => $obj->getId_session(),
            'montant' => $obj->getMontant(),
            'numero_commande' => $obj->getNumero_commande(),
            'numero_da' => $obj->getNumero_da(),
            'observation' => $obj->getObservation()
          );
        if (null === ($id = $obj->getId()) or $id == '') {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            //return $idElt;
        }
    }

    /**
     *
     * @param type $id
     * @param Application_Model_FormationCoutDirect $obj
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $obj = new Application_Model_FormationCoutDirect();
        $obj->setId($row->id);
        $obj->setId_budget($row->id_budget);
        $obj->setId_session($row->id_session);
        $obj->setMontant($row->montant);
        $obj->setNumero_commande($row->numero_commande);
        $obj->setNumero_da($row->numero_da);
        $obj->setObservation($row->observation);
        return $obj;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_FormationCoutDirect
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_FormationCoutDirect();
            $entry->setId($row->id);
            $entry->setId_budget($row->id_budget);
            $entry->setId_session($row->id_session);
            $entry->setObservation($row->observation);
            $entry->setMontant($row->montant);
            $entry->setNumero_commande($row->numero_commande);
            $entry->setNumero_da($row->numero_da);
            $entries[] = $entry;
        }
        return $entries;
    }

    
    public function getList($query = NULL) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('fcd' => 'formation_cout_direct'), array('*'))
               ->join(array('s' => 'session'), 's.id = fcd.id_session', array('s.date_debut','s.date_fin'))
               ->join(array('f' => 'formation'), 'f.id = s.id_formation', array('f.intitule'))
               ->join(array('b' => 'budget'), 'fcd.id_budget = b.id', array('b.id_entite'))
               ->join(array('e' => 'exercice'), 'b.id_exercice = e.id', array('e.annee'))
               ->join(array('en' => 'entite'), 'b.id_entite = en.id', array('en.entite'))
               ->order('s.id')
               ; 
        return $this->fetchAll($select, $query, FALSE);

    }
    
    /***
     * Cout direct d'une formation donnée
     */
    public function getTotalCoutDirect($idFormation) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('f' => 'formation'), array())
               ->join(array('s' => 'session'), 's.id_formation = f.id ', array())
               ->join(array('fcd' => 'formation_cout_direct'), 's.id = fcd.id_session ', array('sum(fcd.montant) as coutDirectTotal'))
              ->group('f.id')
               ; 
        return $this->fetchAll($select, "id_formation = ".$idFormation, FALSE);

    }
    
    public function fetchAll($select = NULL, $query = NULL, $mapping = FALSE) {
       
        $table = $this->getDbTable();
        if($select == NULL) {
            $select = $table->select();
            $select->setIntegrityCheck(false);
        }
        # Conditions 
        if ($query <> NULL) {
            $select->where($query);
        }
        //echo $select->__toString();
        # Recordset
        $resultSet = $table->fetchAll($select);
        
        # Mapping SQL -> OBJET
        return ($mapping ? $this->processResultSet($resultSet) : $resultSet);
      
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

    
    
    
}

