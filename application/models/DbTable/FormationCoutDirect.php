<?php


class Application_Model_DbTable_FormationCoutDirect extends Zend_Db_Table_Abstract {

    protected $_name = 'formation_cout_direct';
    protected $_primary = 'id';
    
    
    # Reference Bon de commande
    protected $_referenceMap = array(
               'Formation' => array(
                    'columns' => 'id_formation',
                    'refTableClass' => 'Application_Model_DbTable_Formation',
                    'refColumns' => array('id')
                ), 
               'Budget' => array(
                     'columns' => array('id_budget'),
                     'refTableClass' => 'Application_Model_DbTable_Budget',
                     'refColumns' => array('id')
                )
   );
    
    
    public function insert(array $data)
    {
        // Ajout d'un timestamp
        if (empty($data['enregistre_le'])) {
            $data['enregistre_le'] = date('Y-m-d H:i:s');
        }
        return parent::insert($data);
    }
 
    

}

