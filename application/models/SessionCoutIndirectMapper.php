<?php


class Application_Model_SessionCoutIndirectMapper {

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
            $this->setDbTable('Application_Model_DbTable_SessionCoutIndirect');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_SessionCoutIndirect $formation
     * @return type
     */
    public function save(Application_Model_SessionCoutIndirect $cf) {
        $data = array(
            'id_session' => $cf->getId_session(),
            'valeur' => $cf->getValeur(),
            'id_budget' => $cf->getId_budget(),
            'observation' => $cf->getObservation(),
            'bon_commande' => $cf->getBon_commande(),
            'demande_achat' => $cf->getDemande_achat(),
            'id_type_cout' => $cf->getId_type_cout()
          );
         try {
            $id = $this->getDbTable()->insert($data);
            return $id;
         } 
         catch(Exception $e) {
            $this->getDbTable()->update($data, array(
                                              'id_session = ?' => $cf->getId_session(),
                                              'id_type_cout = ?' => $cf->getId_type_cout()
                             ));
         }
        
        
    }
   
    /***
     * Cout Indirect d'une formation donnée
     */
    public function getTotalCoutIndirect($idFormation) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('f' => 'formation'), array())
               ->join(array('s' => 'session'), 's.id_formation = f.id ', array())
               ->join(array('sci' => 'session_cout_indirect'), 's.id = sci.id_session ', array('sum(sci.valeur) as coutIndirectTotal'))
               ->where("id_formation = ".$idFormation)
               ->group('f.id')
               ; //echo $select->__toString();
        return $this->getDbTable()->fetchAll($select);

    }
    
     public function fetchAll($query = NULL) {
       
        $table = $this->getDbTable();
        $select = $table->select();
        if ($query === NULL) {
            $resultSet = $table()->fetchAll();
        } 
        else {
            $select->setIntegrityCheck(false)
                    ->from(array('sci' => 'session_cout_indirect'), array('*'))
                    ->join(array('s' => 'session'), 'sci.id_session = s.id', array('s.id as id_session'))
                    ->join(array('f' => 'formation'), 's.id_formation = f.id', array('f.id as id_formation'))
                    ->join(array('b' => 'budget'), 'sci.id_budget = b.id', array('b.id_entite'))
                    ->join(array('e' => 'exercice'), 'b.id_exercice = e.id', array('e.annee'))
                    ->join(array('en' => 'entite'), 'b.id_entite = en.id', array('en.entite'))
                     ->where($query)
                    //->order('p.nom ASC')
                    ;
            $resultSet = $table->fetchAll($select);
            return $resultSet;
        }
        return $this->processResultSet($resultSet);
    }
    
    
    
    
}

