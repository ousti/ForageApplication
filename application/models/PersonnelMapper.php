<?php


class Application_Model_PersonnelMapper {

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
            $this->setDbTable('Application_Model_DbTable_Personnel');
        }
        return $this->_dbTable;
    }

    /**
     *
     * @param Application_Model_Personnel $p
     * @return type
     */
    public function save(Application_Model_Personnel $p) {
        $data = array(
            'id' => $p->getId(),
            'nom' => $p->getNom(),
            'prenoms' => $p->getPrenoms(),
            'id_direction' => $p->getId_direction(),
            'matricule' => $p->getMatricule(),
            'fonction' => $p->getFonction(),
            'id_categorie' => $p->getId_categorie(),
            'date_embauche' => $p->getDate_embauche(),
            'date_naissance' => $p->getDate_naissance(),
            'cnps' => $p->getCnps(),
            'superieur_hierarchique' => $p->getSuperieur_hierarchique(),
            'genre' => $p->getGenre(),
            'nationalite' => $p->getNationalite(),
            'id_entite' => $p->getId_entite(),
            'type_salarie' => $p->getType_salarie()
          );
        if (null === ($id = $p->getId()) or $id == '') {
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
     * @param Application_Model_Organisme $p
     * @return type
     */
    public function find($id) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $p = new Application_Model_Personnel();
        $p->setId($row->id);
        $p->setMatricule($row->matricule);
        $p->setNom($row->nom);
        $p->setPrenoms($row->prenoms);
        $p->setId_direction($row->id_direction);
        $p->setId_categorie($row->id_categorie);
        $p->setId_Entite($row->id_entite);
        $p->setGenre($row->genre);
        $p->setCnps($row->cnps);
        $p->setFonction($row->fonction);
        $p->setDate_embauche($row->date_embauche);
        $p->setDate_naissance($row->date_naissance);
        $p->setNationalite($row->nationalite);
        $p->setType_salarie($row->type_salarie);
        $p->setSuperieur_hierarchique($row->superieur_hierarchique);
       
        return $p;
    }

    /**
     *
     * @param type $resultSet
     * @return Application_Model_Organisme
     */
    private function processResultSet($resultSet) {
        $entries = array();
        foreach ($resultSet as $row) {
            $p = new Application_Model_Personnel();
            $p->setId($row->id);
            $p->setMatricule($row->matricule);
            $p->setNom($row->nom);
            $p->setPrenoms($row->prenoms);
            $p->setId_direction($row->id_direction);
            $p->setId_categorie($row->id_categorie);
            $p->setId_Entite($row->id_entite);
            $p->setGenre($row->genre);
            $p->setCnps($row->cnps);
            $p->setDate_embauche($row->date_embauche);
            $p->setDate_naissance($row->date_naissance);
            $p->setNationalite($row->nationalite);
            $p->setType_salarie($row->type_salarie);
            $entries[] = $p;
        }
        return $entries;
    }

    
    public function fetchAll($query = array(), $mapping = FALSE) {
        
        $table = $this->getDbTable();
        $select = $table->select();
        $select->setIntegrityCheck(false)
               ->from(array('p' => 'personnel'), array('*'))
               ->join(array('e'=>'entite'), 'p.id_entite = e.id', array('e.id as id_entite','e.entite'))
               ->join(array('d'=>'direction'), 'p.id_direction = d.id', array('d.id as id_direction','d.direction'))
               ->join(array('c'=>'categorie'), 'c.id = p.id_categorie', array('c.id as id_categorie','c.categorie'))
               ;
            
        if ($query === NULL) {
            $select->order('e.id ASC')
               ->order('d.direction ASC')
               ->order('p.nom ASC');
        } 
        # Requete de Tri
        else if(count($query)) { 
                # par entite
                if(array_key_exists('id_entite',$query))
                    $select->where('p.id_entite = ?',$query['id_entite']);
                # par direction
                if(array_key_exists('id_direction',$query))
                    $select->where('p.id_direction = ?',$query['id_direction']);
                if(array_key_exists('id_pole',$query))
                    $select->where('d.id_pole = ?',$query['id_pole']);
                if(array_key_exists('id_domaine',$query))
                    $select->where('f.id_domaine = ?',$query['id_domaine']);
                # par nom ou matricule
                if(array_key_exists('byField',$query))
                    $select->where('p.nom = ? OR p.matricule = ? ',$query['byField'],$query['byField']);
                /***** REPORT *****/
                if(array_key_exists('report',$query)) :
                $select->join(array('a'=>'auditeur'), 'p.id = a.id_personnel AND a.statut = 1', array())
                       ->join(array('s'=>'session'), 's.id = a.id_session AND s.status = 1', array('s.date_debut','s.date_fin','s.nombre_jour','s.nombre_heure_jour'))
                       ->join(array('f'=>'formation'), 'f.id = s.id_formation', array('f.intitule'))
                       ->join(array('do'=>'domaines_formation'), 'do.id = f.id_domaine', array('do.domaine_formation'))
                       ->joinLeft(array('fe'=>'formation_externe'), 'f.id = fe.id_formation', array())
                       ->joinLeft(array('o'=>'organisme'), 'o.id = fe.id_organisme', array('o.organisme'))
                       ->order('s.date_debut ASC')
                       ;
                
                 if(!isset($query['date_fin']) and empty($query['date_fin']))
                   $select->where(' EXTRACT(YEAR_MONTH FROM s.date_debut) = ?',  str_replace ('-', '', $query['date_debut']));
                 else {
                     $dd =  str_replace ('-', '', $query['date_debut']);
                     $df =  str_replace ('-', '', $query['date_fin']);
                     $select->where(' EXTRACT(YEAR_MONTH FROM s.date_debut) >= ? ',$dd);
                     $select->where(' EXTRACT(YEAR_MONTH FROM s.date_debut) <= ? ',$df);
                 }
               endif;
                
            
        }  //echo $select->__toString();
        
        $resultSet = $this->getDbTable()->fetchAll($select);
        if($mapping)
            return $this->processResultSet($resultSet);
        else
            return $resultSet;
    }

    
    public function getNotLinkedToSession($idSession) {
        if ($idSession == 0 or $idSession == '') {
            return array();
        }
        $table = $this->getDbTable();
        $select = $table->select()->setIntegrityCheck(false);
        $ss_req = " SELECT id_personnel FROM formation_interne where id_session = $idSession";
        $req = $select->from(array('p'=>'personnel'), array('p.id', 'p.matricule','p.nom', 'p.prenoms'))
               ->where(" p.id NOT IN (".$ss_req.")")
               ->order('p.nom');
        $resultSet = $table->fetchAll($req);
        return ($resultSet);
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

