<?php


class Application_Model_FormationInterneMapper {

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
            $this->setDbTable('Application_Model_DbTable_FormationInterne');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_formation $formation
     * @return type
     */
    public function save(Application_Model_FormationInterne $cf,$op,$id_formateur_old='') {
        $data = array(
            'id_session' => $cf->getId_session(),
            'montant_horaire' => $cf->getMontant_horaire(),
            'id_personnel' => $cf->getId_personnel()
          );
        try {
            if ($op == 'add') {

                $id = $this->getDbTable()->insert($data);
                return $id;
            } else if ($op == 'edit') {
                $res = $this->getDbTable()->update($data, array(
                    'id_session = ?' => $cf->getId_session(),
                    'id_personnel = ?' => $id_formateur_old
                ));
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    
     public function fetchAll($query = NULL) {
       
        $table = $this->getDbTable();
        $select = $table->select();
        if ($query === NULL) {
            $resultSet = $table()->fetchAll();
        } 
        else {
        $select->setIntegrityCheck(false)
                ->from(array('fi' => 'formation_interne'), array('*'))
                ->join(array('s' => 'session'), 'fi.id_session = s.id', array('s.id as id_session','s.nombre_heure_jour','s.nombre_jour'))
                ->join(array('f' => 'formation'), 's.id_formation = f.id', array('f.id as id_formation'))
                ->join(array('p' => 'personnel'), 'fi.id_personnel = p.id', array('p.id as id_personnel','p.nom','p.prenoms','p.matricule','p.fonction'))
                ->join(array('d' => 'direction'), 'p.id_direction = d.id', array('d.direction'))
                ->where($query)
                //->order('p.nom ASC')
                ;
        $resultSet = $table->fetchAll($select);
            return $resultSet;
        }
        return $this->processResultSet($resultSet);
    }
    
   
    
    
}

