<?php


class Application_Model_FormationMapper {

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
            $this->setDbTable('Application_Model_DbTable_Formation');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_formation $formation
     * @return type
     */
    public function save(Application_Model_Formation $formation) {
        $data = array(
            'id' => $formation->getId(),
            'intitule' => $formation->getIntitule(),
            'objectif' => $formation->getObjectif(),
            'statut' => $formation->getStatut(),
            'type_formateur' => $formation->getType_formateur(),
            'id_pays' => $formation->getId_pays(),
            'id_direction' => $formation->getId_direction(),
            'id_domaine' => $formation->getId_domaine(),
            'id_nature' => $formation->getId_nature(),
            'id_type' => $formation->getId_type()
          );
        if (null === ($id = $formation->getId()) or $id == '') {
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
     * @param Application_Model_formation $formation
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $formation = new Application_Model_Formation();
        $formation->setId($row->id);
        $formation->setIntitule($row->intitule);
        $formation->setId_pays($row->id_pays);
        $formation->setObjectif($row->objectif);
        $formation->setStatut($row->statut);
        $formation->setType_formateur($row->type_formateur);
        $formation->setId_nature($row->id_nature);
        $formation->setId_domaine($row->id_domaine);
        $formation->setId_Type($row->id_type);
        $formation->setId_direction($row->id_direction);
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
            $entry = new Application_Model_Formation();
            $entry->setId($row->id);
            $entry->setIntitule($row->intitule);
            $entry->setStatut($row->statut);
            $entry->setType_formateur($row->type_formateur);
            $entry->setId_direction($row->id_direction);
            $entry->setId_nature($row->id_nature);
            $entry->setId_domaine($row->id_domaine);
            $entry->setId_pays($row->id_pays);
            $entry->setDirection($row->direction);
            
            $entries[] = $entry;
        }
        return $entries;
    }

    
    /***
     * Sessions de formation en attente/achevé et Formation non cloturée
     */
    public function getCalendrierFormation($exerciceYear = NULL) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('f' => 'formation'), array('*'))
               ->join(array('d' => 'direction'), 'd.id = f.id_direction', array('d.direction'))
               ->join(array('tf' => 'types_formation'), 'tf.id = f.id_type', array('tf.type_formation'))
               ->joinLeft(array('s' => 'session'), 's.id_formation = f.id AND s.status <> 1', array('s.date_debut','s.date_fin', 's.localisation','s.nombre_jour','s.nombre_heure_jour','s.auditeur_prevu'))
               ->joinLeft(array('fe'=>'formation_externe'), 'fe.id_formation = f.id', array(''))
               ->joinLeft(array('o'=>'organisme'), 'fe.id_organisme = o.id', array('o.organisme'))
               ->joinLeft(array('fcd'=>'formation_cout_direct'), 'fcd.id_session = s.id', array('sum(fcd.montant) as cout_direct'))
               ->order('s.date_debut', 'd.id')
               ->group('s.id')
               ; //echo $select->__toString();
        $where = ' f.statut = 0 ';
        if($exerciceYear)
            $where .= ' AND YEAR(s.date_debut) = '.$exerciceYear;
        return $this->fetchAll($select, $where, FALSE);

    }
    
    
    /*
     * Reporting Liste des formations réalisées
     */
    public function getListeFormationRealise($where = array(), $exercice = NULL) {
        $select =  $this->getDbTable()->select();
        $select1 =  $this->getDbTable()->select();
        $select2 =  $this->getDbTable()->select();
        $sub_cout_direct = $select1->setIntegrityCheck(false)
                              ->from('formation_cout_direct', array('id_session',
                                     new Zend_Db_Expr("SUM(montant) AS cout_direct")))
                              ->group('id_session')
                           ;   
        $sub_cout_indirect = $select2->setIntegrityCheck(false)
                              ->from('session_cout_indirect', array('id_session',
                                     new Zend_Db_Expr("SUM(valeur) AS cout_indirect")))
                              ->group('id_session')
                           ; 
        $select->setIntegrityCheck(false)
               ->from(array('s' => 'session'), array('s.id as id_session','s.date_debut','s.date_fin', 's.localisation','s.nombre_jour','s.nombre_heure_jour','s.auditeur_prevu'))
               ->join(array('f' => 'formation'), 's.id_formation = f.id', array('*'))
               ->join(array('d' => 'direction'), 'd.id = f.id_direction', array('d.direction'))
               ->join(array('tf' => 'types_formation'), 'tf.id = f.id_type', array('tf.type_formation'))
               ->joinLeft(array('a' => 'auditeur'), 'a.id_session = s.id AND a.statut = 1', array('count("a.id_personnel") as nbAuditeurPresent'))
               ->joinLeft(array('fe'=>'formation_externe'), 'fe.id_formation = f.id', array('*'))
               ->joinLeft(array('o'=>'organisme'), 'fe.id_organisme = o.id', array('o.organisme'))
               ->joinLeft(array('sci' => $sub_cout_indirect),'sci.id_session = s.id',array('cout_indirect'=>'cout_indirect'))
               ->joinLeft(array('fcd' => $sub_cout_direct),'fcd.id_session = s.id',array('cout_direct'=>'cout_direct'))
               ->order('s.date_debut','d.id')
               ->group('s.id')   
               ; //echo $select->__toString(); echo '<br>';
        
       $sortByPeriod = false;
       
       // Requete de tri
        if(count($where)) { 
            # par pole
            if(array_key_exists('id_pole',$where))
                $select->where('d.id_pole = ?',$where['id_pole']);
            # par direction
            if(array_key_exists('id_direction',$where))
                $select->where('d.id = ?',$where['id_direction']);
            # par cabinet
            if(array_key_exists('id_organisme',$where))
                $select->where('o.id = ?',$where['id_organisme']);
            # par type de formation
            if(array_key_exists('id_type',$where))
                $select->where('tf.id = ?',$where['id_type']);
            # par salarié
            if(array_key_exists('id_personnel',$where)) {
                $select->where('a.id_personnel = ?',$where['id_personnel']);
                if($where['present'])
                   $select->where('a.statut = ?',$where['present']); 
            }
            # par période
            if(array_key_exists('periode',$where)) {
                $sortByPeriod = true;
                $select->where(' EXTRACT(YEAR_MONTH FROM s.date_debut) = ?',  str_replace ('-', '', $where['periode']));
            }
        }
        
       if(!$sortByPeriod and $exercice) :
                $select->where(' EXTRACT(YEAR FROM s.date_debut) = ?',  $exercice);
       endif;
       
       # Execution Requete
       return $this->fetchAll($select, ' s.status = 1');
    }
    
    
     /***
     * Nombre d'auditeur total prévu d'une formation donnée
     */
    public function getTotalAuditeur($idFormation) {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
               ->from(array('f' => 'formation'), array())
               ->join(array('s' => 'session'), 's.id_formation = f.id ', array('sum(s.auditeur_prevu) as nbAuditeurTotal'))
              ->group('f.id')
               ; 
        return $this->fetchAll($select, "id_formation = ".$idFormation, FALSE);

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
        
        # Mapping SQL -> OBJET
        return ($mapping ? $this->processResultSet($resultSet) : $resultSet);
      
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

    
   /*
    * TABLEAU DE BORD DES ACTIONS DE FORMATION REALISEES
    */
   public function getDashboardFormationRealise($where='') {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
            ->distinct()
            ->from(array('f' => 'formation'), array('f.id','f.intitule'))
            ->join(array('d' => 'domaines_formation'), 'd.id = f.id_domaine', array('d.domaine_formation'))
            ->join(array('s' => 'session'), 's.id_formation = f.id', array('s.id as id_session','s.nombre_jour','s.nombre_heure_jour','s.status','s.date_debut'))
            ->join(array('fcd' => 'formation_cout_direct'), 'fcd.id_session = s.id', array('fcd.id_budget'))
            ->join(array('b' => 'budget'), 'fcd.id_budget = b.id ', array('b.id_entite'))
            ->join(array('e' => 'entite'), 'b.id_entite = e.id AND e.inputable_budget = 1', array('e.entite'))
            ->order('s.id')
            ;
               
        if($where=='')
            $where = NULL;
        else {
           $date = str_replace ('-', '', $where['date']);
           $where = (" EXTRACT(YEAR_MONTH FROM s.date_debut) = '".$date."'"); 
           $select->group('s.id');
        }
       return $this->fetchAll($select, $where, FALSE);
    }
   

    
   /*
    * TABLEAU DE BORD HEURE DE FORMATION REALISE
    */
   public function getDashboardHeureFormation($where='') {
        $select =  $this->getDbTable()->select();
        $select->setIntegrityCheck(false)->from(array('f' => 'formation'), array('f.id'))
            ->join(array('d' => 'domaines_formation'), 'd.id = f.id_domaine', array('d.domaine_formation'))
            ->join(array('s' => 'session'), 's.id_formation = f.id AND s.status = 1', array('EXTRACT(YEAR_MONTH FROM s.date_debut) as periode'))
            ->join(array('a' => 'auditeur'), 'a.id_session = s.id AND a.statut = 1 ', array('count(a.id)*s.nombre_jour*s.nombre_heure_jour as heureTotalEntite'))
            ->join(array('p' => 'personnel'), 'a.id_personnel = p.id', array())
            ->join(array('e' => 'entite'), 'p.id_entite = e.id AND e.inputable_budget = 1', array('e.entite'))
            ->group('d.id')
            ->group('e.id')
            ;
               
        if($where=='')
            $where = NULL;
        else {
           $dateD = $where['year'].'-'.'01-01';
           $dateF = $where['date'].'-31';
           $where = (" s.date_debut >= '".$dateD."'");           
           $where .= (" AND s.date_debut <= '".$dateF."'"); 
           $select->group('periode');
          // $where =  "EXTRACT(YEAR_MONTH FROM s.date_debut) = ?";
        } 

       return $this->fetchAll($select, $where, FALSE);

    }
    
    
   
  
    
    
    
    
}



/**
 * 
 * $select->setIntegrityCheck(false)
               ->from(array('s' => 'session'), array('s.id as id_session','s.date_debut','s.date_fin', 's.localisation','s.nombre_jour','s.nombre_heure_jour','s.auditeur_prevu'))
               ->join(array('f' => 'formation'), 's.id_formation = f.id', array('*'))
               ->join(array('d' => 'direction'), 'd.id = f.id_direction', array('d.direction'))
               ->join(array('tf' => 'types_formation'), 'tf.id = f.id_type', array('tf.type_formation'))
               ->joinLeft(array('a' => 'auditeur'), 'a.id_session = s.id AND a.statut = 1', array('count("a.id_personnel") as nbAuditeurPresent'))
               ->joinLeft(array('fe'=>'formation_externe'), 'fe.id_formation = f.id', array('*'))
               ->joinLeft(array('o'=>'organisme'), 'fe.id_organisme = o.id', array('o.organisme'))
               ->joinLeft(array('sci'=>'session_cout_indirect'), 'sci.id_session = s.id ', array('sum(valeur) as coutTotal'))
               ->joinLeft(array('fcd'=>'formation_cout_direct'), 'fcd.id_session = s.id', array('sum(fcd.montant) as cout_direct'))
               ->order('s.date_debut','d.id')
               ->group('s.id')   
               ;
 */