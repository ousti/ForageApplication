<?php


class Application_Model_OrganismeMapper {

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
     * @return Application_Model_DbTable_Organisme
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Organisme');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_Organisme $organisme
     * @return type
     */
    public function save(Application_Model_Organisme $organisme) {
        $data = array(
            'id' => $organisme->getId(),
            'organisme' => $organisme->getOrganisme(),
            'email' => $organisme->getEmail(),
            'tel1' => $organisme->getTel1(),
            'tel2' => $organisme->getTel2(),
            'fax' => $organisme->getFax(),
            'localisation' => $organisme->getLocalisation(),
            'nationalite' => $organisme->getNationalite()
          );
        if (null === ($id = $organisme->getId()) or $id=='') {
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
     * @param Application_Model_Organisme $organisme
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $organisme = new Application_Model_Organisme();
        $organisme->setId($row->id);
        $organisme->setOrganisme($row->organisme);
        $organisme->setTel1($row->tel1);
        $organisme->setEmail($row->email);
        $organisme->setTel2($row->tel2);
        $organisme->setFax($row->fax);
        $organisme->setLocalisation($row->localisation);
        $organisme->setNationalite($row->nationalite);
        
                
         return $organisme;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_Organisme
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Organisme();
            $entry->setId($row->id);
            $entry->setOrganisme($row->organisme);
            $entry->setTel1($row->tel1);
            $entry->setTel2($row->tel2);
            $entry->setEmail($row->email);
            $entry->setFax($row->fax);
            $entry->setLocalisation($row->localisation);
            $entry->setNationalite($row->nationalite);
            $entry->setNbFormationDone($row->nbFormationDone);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function fetchAll($query = NULL) {
       
       $table = $this->getDbTable();
       $select = $table->select();
       $select->setIntegrityCheck(false)
               ->from(array('o' => 'organisme'), array('*'))
               ->joinLeft(array('fe' => 'formation_externe'), 'fe.id_organisme = o.id', array('count(fe.id_formation) as nbFormationDone'))
               ->order('o.organisme ASC')
               ->group('o.id');
        
        if ($query <> NULL) {
           # par nationalite
            if(array_key_exists('nationalite',$query))
                $select->where('nationalite = ?',$query['nationalite']);
                $resultSet = $this->getDbTable()->fetchAll($select);
        }
        
        $resultSet = $this->getDbTable()->fetchAll($select);
        
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

