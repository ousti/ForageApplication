<?php


class Application_Model_DbTable_Direction extends Zend_Db_Table_Abstract {

    protected $_name = 'direction';
    protected $_primary = 'id';
    
    # Reference Formation
    protected $_referenceMap = array(
        'Pole' => array(
            'columns' => array('id_pole'),
            'refTableClass' => 'Pole',
            'refColumns' => array('id')
            )
        );

}

