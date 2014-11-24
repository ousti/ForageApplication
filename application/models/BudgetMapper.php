<?php


class Application_Model_BudgetMapper {

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
            $this->setDbTable('Application_Model_DbTable_Budget');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_formation $formation
     * @return type
     */
    public function save(Application_Model_Budget $obj) {
        $data = array(
            'id_entite' => $obj->getId_entite(),
            'montant' => $obj->getMontant(),
            'id_exercice' => $obj->getId_exercice()
          );
         try {
            $id = $this->getDbTable()->insert($data);
            return $id;
         } 
         catch(Exception $e) {
            $this->getDbTable()->update($data, array(
                                              'id = ?' => $obj->getId()
                                             
                             ));
         }
    }
    
     public function getEntiteBudgetEncours($idEntite = NULL, $exercice = NULL) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('b' => 'budget'), array('*'))
               ->join(array('e' => 'exercice'), 'b.id_exercice = e.id', array('e.annee', 'e.montant_budget'))
               ->join(array('en' => 'entite'), 'b.id_entite = en.id', array('en.entite'))
               ->order('e.id')
               //->group('e.id')
               ; 
        # Budget par exercice
        if($exercice) 
            $select->where('e.annee = ? ', $exercice);
        
        # Budget d'une entité donnée e.en_cours = 1
        if($idEntite) 
            $select->where('en.id = ? ', $idEntite);
        
        return $this->fetchAll($select, '0 = 0 ', FALSE);
     }
   
     
     
    /*
    * TABLEAU DE BORD BUDGET FORMATION REALISE PAR EXERCICE/MOIS
    */
   public function getBudgetFormation($where='') {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
            ->from(array('b' => 'budget'), '', array())
            ->join(array('e' => 'entite'), 'b.id_entite = e.id AND e.inputable_budget = 1', array('e.entite'))
            ->join(array('fcd' => 'formation_cout_direct'), 'b.id = fcd.id_budget', array('SUM(fcd.montant) as totalCoutDirect'))
            ->join(array('sci' => 'session_cout_indirect'), 'b.id = sci.id_budget', array('SUM(sci.valeur) as totalCoutIndirect'))
            ->join(array('s' => 'session'), 's.status = 1 AND fcd.id_session = s.id AND sci.id_session = s.id', array('EXTRACT(YEAR_MONTH FROM s.date_debut) as periode'))
            ->join(array('f' => 'formation'),'s.id_formation = f.id ', array())
            ->join(array('d' => 'domaines_formation'), 'd.id = f.id_domaine', array('d.domaine_formation'))
            ->group('d.id')
            ->group('e.id')
            ;
               
        if($where=='')
            $where = NULL;  
        else {
           $date = str_replace('-','',$where['date']);
           $where = (" EXTRACT(YEAR_MONTH FROM s.date_debut) = '".$date."'");  
           $select->group('periode');
        } 
       
        return $this->fetchAll($select, $where, FALSE);

    }
    
    
    
     /*
      * BUDGET ALLOUE AUX DOMAINES PAR EXERCICE
      */
      public function getMontantDomaineAlloue($idBudget='') {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('b' => 'budget'), array('*'))
               ->join(array('bd' => 'budget_domaine'), 'bd.id_budget = b.id', array('bd.id_domaine', 'bd.montant as allocationDomaine'))
              // ->where('b.id = ?',$idBudget)
               ; 
        if($idBudget) {
            $where  = "b.id = $idBudget";
        }
        else {
            $select->join(array('d' => 'domaines_formation'), 'd.id = bd.id_domaine', array('d.domaine_formation'));
            $where = 'b.id_exercice = 1';
        }
        
        return $this->fetchAll($select, $where, FALSE);
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
        
        //echo $select->__toString();
        
        # Recordset
        $resultSet = $table->fetchAll($select);
        
        # Mapping SQL -> Objet
        if($mapping)
            return $this->processResultSet($resultSet);
        else
            return $resultSet;
    }
    
}

