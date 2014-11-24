<?php


class Application_Model_BonCommandeMapper {

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
     * @return Application_Model_DbTable_BonCommande
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_BonCommande');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_BonCommande $bon
     * @return type
     */
    public function save(Application_Model_BonCommande $bon) {
        $data = array(
            'id' => $bon->getId(),
            'id_budget' => $bon->getId_budget(),
            'id_formation' => $bon->getId_formation(),
            'montant' => $bon->getMontant(),
            'observation' => $bon->getObservation()
          );
        if (null === ($id = $bon->getId()) or $id == '') {
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
     * @param Application_Model_BonCommande $bon
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $bon = new Application_Model_BonCommande();
        $bon->setId($row->id);
        $bon->setId_budget($row->id_budget);
        $bon->setId_formation($row->id_formation);
        $bon->setMontant($row->montant);
        $bon->setObservation($row->observation);
        return $bon;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_BonCommande
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_BonCommande();
            $entry->setId($row->id);
            $entry->setId_budget($row->id_budget);
            $entry->setId_formation($row->id_formation);
            $entry->setObservation($row->observation);
            $entry->setMontant($row->montant);
            $entries[] = $entry;
        }
        return $entries;
    }

    
    public function getList($query = NULL) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('bc' => 'bon_commande'), array('*'))
               ->join(array('f' => 'formation'), 'f.id = bc.id_formation', array('f.intitule'))
               ->join(array('b' => 'budget'), 'bc.id_budget = b.id', array('b.id_entite'))
               ->join(array('e' => 'exercice'), 'b.id_exercice = e.id', array('e.annee'))
               ->join(array('en' => 'entite'), 'b.id_entite = en.id', array('en.entite'))
               ; 
        return $this->fetchAll($select, $query, FALSE);

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

