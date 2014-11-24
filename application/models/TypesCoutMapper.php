<?php


class Application_Model_TypesCoutMapper {

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
            $this->setDbTable('Application_Model_DbTable_TypesCout');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_TypeFormation$formation
     * @return type
     */ 
    public function save(Application_Model_TypeFormation $type) {
        $data = array(
            'id' => $type->getId(),
            'type' => $type->getType(),
           );
        if (null === ($id = $type->getId())) {
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
     * @param Application_Model_TypesCout $typeCout
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $type = new Application_Model_TypesCout();
        $type->setId($row->id);
        $type->setType($row->type);
        $type->setLibelle($row->libelle);
               
         return $type;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_formation
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_TypesCout();
            $entry->setId($row->id);
            $entry->setLibelle($row->libelle);
            $entry->setType($row->type);
            $entries[] = $entry;
        }
        return $entries;
    }

    
    
    public function fetchAll($query = NULL, $integrity = TRUE) {
              
        if ($query === NULL) {
            $resultSet = $this->getDbTable()->fetchAll();
        } else {
            $table = $this->getDbTable();
            $select = $table->select()->setIntegrityCheck($integrity);
            $select->from($table)
                   ->where($query);
            $resultSet = $this->getDbTable()->fetchAll($select);
        }
        return $this->processResultSet($resultSet);
    }

    
    
    public function getNotLinkedToSession($idSession) {
        if ($idSession == 0 or $idSession == '') {
            return array();
        }
        $table = $this->getDbTable();
        $select = $table->select()->setIntegrityCheck(false);
        $ss_req = " SELECT id_type_cout FROM session_cout_indirect where id_session = $idSession";
        $req = $select->from(array('tc'=>'types_cout'), array('tc.id', 'tc.libelle','tc.type'))
               ->where(" tc.id NOT IN (".$ss_req.")");
        $resultSet = $table->fetchAll($req);
        return $this->processResultSet($resultSet);
    }

    
    
    
}
