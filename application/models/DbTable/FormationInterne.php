<?php


class Application_Model_DbTable_FormationInterne extends Zend_Db_Table_Abstract {

    protected $_name = 'formation_interne';
    protected $_primary = array('id_session','id_personnel');
    
     protected $_referenceMap    = array(
        'Personnel' => array(
            'columns'           => array('id_personnel'),
            'refTableClass'     => 'Application_Model_DbTable_Personnel',
            'refColumns'        => array('id')
        ),
         'Session' => array(
            'columns'           => array('id_session'),
            'refTableClass'     => 'Application_Model_DbTable_Session',
            'refColumns'        => array('id'),
            'onDelete'      => self::CASCADE 
        )
    );

}

