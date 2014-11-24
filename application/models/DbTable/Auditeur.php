<?php


class Application_Model_DbTable_Auditeur extends Zend_Db_Table_Abstract {

    protected $_name = 'auditeur';
    protected $_primary = 'id';
    
    
    protected $_referenceMap    = array(
        'Formation' => array(
            'columns'           => array('id_formation'),
            'refTableClass'     => 'Application_Model_DbTable_Formation',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE 
        ),
        'Personnel' => array(
            'columns'           => array('id_personnel'),
            'refTableClass'     => 'Application_Model_DbTable_Personnel',
            'refColumns'        => array('id')
        )
    );

}

