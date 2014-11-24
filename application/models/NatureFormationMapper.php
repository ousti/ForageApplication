<?php


class Application_Model_NatureFormationMapper {

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
            $this->setDbTable('Application_Model_DbTable_NaturesFormation');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_NatureFormation$formation
     * @return type
     */ 
    public function save(Application_Model_NatureFormation $nature) {
        $data = array(
            'id' => $nature->getId(),
            'nature' => $nature->getNature(),
           );
        if (null === ($id = $nature->getId())) {
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
     * @param Application_Model_NatureFormation$formation
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $nature = new Application_Model_NatureFormation();
        $nature->setId($row->id);
        $nature->setNature($row->nature);
               
         return $formation;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_formation
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_NatureFormation();
            $entry->setId($row->id);
            $entry->setNature($row->nature_formation);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll($query = NULL) {
       
        if ($query === NULL) {
            $resultSet = $this->getDbTable()->fetchAll();
        } else {
            $table = $this->getDbTable();
            $select = $table->select();
            $select->from($table)
                    ->where($query);
            $resultSet = $this->getDbTable()->fetchAll($select);
        }
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

    
    
    
}

