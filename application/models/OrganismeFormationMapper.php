<?php


class Application_Model_OrganismeFormationMapper {

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
            $this->setDbTable('Application_Model_DbTable_FormationExterne');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_formation $formation
     * @return type
     */
    public function save(Application_Model_OrganismeFormation $cf) {
        $data = array(
            'id_formation' => $cf->getId_formation(),
            'formateur' => $cf->getFormateur(),
            'id_organisme' => $cf->getId_organisme()
          );
        if (null === ($id_formation = $cf->getId_formation())) {
           // throw new Exception('Erreur : veuillez selectionner la formation');
        } else {
            try {
                 // insertion
                 $id = $this->getDbTable()->insert($data);
            }
            catch (Exception $e) {
                // update
                $this->getDbTable()->update($data, array('id_formation = ?' => $id_formation));
            }
        }
    }
   
    
    
}

