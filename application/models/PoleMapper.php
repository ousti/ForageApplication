<?php

class Application_Model_PoleMapper {

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
            $this->setDbTable('Application_Model_DbTable_Pole');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Pole $pole) {
        $data = array(
            'pole' => $pole->getPole(),
        );

        if (null === ($id = $pole->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Pole $pole) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $pole->setPole($row->pole);
    }


    public function fetchAll($query = null) {
        if ($query == null) {
            $resultSet = $this->getDbTable()->fetchAll();
        } else {
            $resultSet = $this->getDbTable()->fetchAll($query);
        }
        return $this->processResults($resultSet);
    }

    private function processResults($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Pole();
            $entry->setId($row->id);
            $entry->setPole($row->pole);
            $entries[] = $entry;
        }
        return $entries;
    }

}

