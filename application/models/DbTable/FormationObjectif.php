<?php


class Application_Model_DbTable_FormationObjectif extends Zend_Db_Table_Abstract {

    protected $_name = 'formation_objectif';
    protected $_primary = 'id';
    
    
    # Reference Formation
    protected $_referenceMap = array(
               'Formation' => array(
                    'columns' => 'id_formation',
                    'refTableClass' => 'Application_Model_DbTable_Formation',
                    'refColumns' => array('id')
                )
               
   );
    
    
    
    /*
    public function insert(array $data)
    {
        
        if (empty($data['enregistre_le'])) {
            $data['enregistre_le'] = date('Y-m-d H:i:s');
        }
        return parent::insert($data);
    }
    */
    

}

