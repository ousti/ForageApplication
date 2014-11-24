<?php


class Application_Model_DbTable_SessionCoutIndirect extends Zend_Db_Table_Abstract {

    protected $_name = 'session_cout_indirect';
    protected $_primary = array('id_session','id_type_cout');
    
     protected $_referenceMap    = array(
        'Session' => array(
            'columns'           => array('id_session'),
            'refTableClass'     => 'Application_Model_DbTable_Session',
            'refColumns'        => array('id'),
            'onDelete'      => self::CASCADE 
        ),
        'Cout' => array(
            'columns'           => array('id_type_cout'),
            'refTableClass'     => 'Application_Model_DbTable_TypesCout',
            'refColumns'        => array('id')
        )
    );

}

