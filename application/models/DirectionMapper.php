<?php

class Application_Model_DirectionMapper {

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

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Direction');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Direction $direction) {
        $data = array(
            'direction' => $direction->getDirection(),
        );

        if (null === ($id = $direction->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Direction $direction) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $direction->setDirection($row->direction);
    }

   public function getListeDirection() {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('d' => 'direction'), array('*'))
               ->joinLeft(array('f' => 'formation'), 'f.id_direction = d.id AND f.statut = 1 ', array('count(f.id) as nbFormation'))
               ->join(array('p' => 'pole'), 'd.id_pole = p.id', array('p.pole'))
               ->order('p.id','d.direction')
               ->group('d.id')
               ; 
       return $this->fetchAll($select);
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
        
        # Mapping SQL -> Objet
        return ($mapping ? $this->processResults($resultSet) : $resultSet);
    }
    
    /*
    public function fetchAll($query = null, $mapping = FALSE) {
        if ($query == null) {
            $resultSet = $this->getDbTable()->fetchAll();
        } else {
            $resultSet = $this->getDbTable()->fetchAll($query);
        }
        
        return ($mapping ? $this->processResults($resultSet) : $resultSet);
    }*/

    private function processResults($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Direction();
            $entry->setId($row->id);
            $entry->setDirection($row->direction);
            $entries[] = $entry;
        }
        return $entries;
    }

}

