<?php


class Application_Model_FormationObjectifMapper {

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
     * @return Application_Model_DbTable_FormationObjectif
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_FormationObjectif');
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
        $obj = new Application_Model_FormationObjectif();
        $obj->setId($row->id);
        $obj->setId_formation($row->id_formation);
        $obj->setObjectif($row->objectif);
        $obj->setEvaluation($row->evaluation);
        return $obj;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_FormationObjectif
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_FormationObjectif();
            $entry->setId($row->id);
            $entry->setId_formation($row->id_formation);
            $entry->setObjectif($row->objectif);
            $entry->setEvaluation($row->evaluation);
            $entries[] = $entry;
        }
        return $entries;
    }

    
    
}

