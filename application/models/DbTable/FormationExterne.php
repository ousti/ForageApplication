<?php


class Application_Model_DbTable_FormationExterne extends Zend_Db_Table_Abstract {

    protected $_name = 'formation_externe';
    protected $_primary = array('id_formation');
    
     protected $_referenceMap    = array(
        'Formation' => array(
            'columns'           => array('id_formation'),
            'refTableClass'     => 'Application_Model_DbTable_Formation',
            'refColumns'        => array('id'),
            'onDelete'      => self::CASCADE 
        ),
        'Organisme' => array(
            'columns'           => array('id_organisme'),
            'refTableClass'     => 'Application_Model_DbTable_Organisme',
            'refColumns'        => array('id')
        )
    );

}

