<?php


class Application_Model_DbTable_LieuxFormation extends Zend_Db_Table_Abstract {

    protected $_name = 'p';
    protected $_primary = 'id';
    
    # Reference Formation
    /*protected $_referenceMap = array(
        'direction' => array(
            'columns' => array('id_direction'),
            'refTableClass' => 'Direction',
            'refColumns' => array('id')
            )
        );*/

}

