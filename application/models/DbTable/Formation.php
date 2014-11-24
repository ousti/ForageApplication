<?php


class Application_Model_DbTable_Formation extends Zend_Db_Table_Abstract {

    protected $_name = 'formation';
    protected $_primary = 'id';
    protected $_dependentTables = array('Application_Model_DbTable_FormationExterne',
                                        'Application_Model_DbTable_Session',
                                        'Application_Model_DbTable_FormationObjectif',
                                        );
    
    # Reference Formation
    protected $_referenceMap = array(
                'direction' => array(
                    'columns' => 'id_direction',
                    'refTableClass' => 'Application_Model_DbTable_Direction',
                    'refColumns' => array('id')
                ), 
               'types_formation' => array(
                     'columns' => array('id_type'),
                     'refTableClass' => 'Application_Model_DbTable_TypesFormation',
                     'refColumns' => array('id')
                ),
               'domaines_formation' => array(
                     'columns' => array('id_domaine'),
                     'refTableClass' => 'Application_Model_DbTable_DomainesFormation',
                     'refColumns' => array('id')
                ),
               'natures_formation' => array(
                     'columns' => array('id_domaine'),
                     'refTableClass' => 'Application_Model_DbTable_NaturesFormation',
                     'refColumns' => array('id')
                ),
               'pays' => array(
                     'columns' => array('id_pays'),
                     'refTableClass' => 'Application_Model_DbTable_Pays',
                     'refColumns' => array('id')
                )
   );
    
    
    public function insert(array $data)
    {
        // Ajout d'un timestamp
        if (empty($data['enregistre_le'])) {
            $data['enregistre_le'] = time();
        }
        return parent::insert($data);
    }
 
    

}

